<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Setting;
use App\Models\Category;
use Carbon\Carbon;
use App\Events\StatusUpdated;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrFail();
        $user = auth()->user();

        // Get categories with current booked appointments count
        $categories = Category::select('categories.*')
            ->with(['services'])
            ->withCount(['services'])
            ->addSelect(['current_appointments_count' => function($query) use ($user) {
                $query->selectRaw('COUNT(appointments.id)')
                    ->from('appointments')
                    ->join('services', 'appointments.service_id', '=', 'services.id')
                    ->whereColumn('services.category_id', 'categories.id')
                    ->whereNotIn('appointments.status', ['Cancelled', 'Completed', 'No Show']);
                if (!$user->hasRole('admin')) {
                    $query->where(function($q) use ($user) {
                        if ($user->employee) {
                            $q->where('appointments.employee_id', $user->employee->id);
                        }
                        $q->orWhere('appointments.user_id', $user->id);
                    });
                }
            }])
            ->get();

        // Start with base query
        $query = Appointment::query()->with(['employee.user', 'service', 'user']);

        // Only admins can see all data - no conditions added
        if (!$user->hasRole('admin')) {
            $query->where(function($q) use ($user) {
                if ($user->employee) {
                    $q->where('employee_id', $user->employee->id);
                }
                $q->orWhere('user_id', $user->id);
            });
        }

        // Format the appointments with proper date handling
        $appointments = $query->get()->map(function ($appointment) {
            try {
                if (!str_contains($appointment->booking_time ?? '', '-')) {
                    throw new \Exception("Invalid time format");
                }

                // Parse booking date
                $bookingDate = Carbon::parse($appointment->booking_date);

                // Parse start and end times
                [$startTime, $endTime] = array_map('trim', explode('-', $appointment->booking_time));

                // Create proper datetime objects
                $startDateTime = Carbon::createFromFormat('h:i A', $startTime)
                    ->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);

                $endDateTime = Carbon::createFromFormat('h:i A', $endTime)
                    ->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);

                // Handle overnight appointments (if end time is next day)
                if ($endDateTime->lt($startDateTime)) {
                    $endDateTime->addDay();
                }

                return [
                    'id' => $appointment->id, // Add appointment ID
                    'title' => sprintf('%s - %s',
                        $appointment->name,
                        $appointment->service->title ?? 'Service'
                    ),
                    'start' => $startDateTime->toIso8601String(),
                    'end' => $endDateTime->toIso8601String(),
                    'description' => $appointment->notes,
                    'email' => $appointment->email,
                    'phone' => $appointment->phone,
                    'amount' => $appointment->amount,
                    'status' => $appointment->status,
                    'staff' => $appointment->employee->user->name ?? 'Unassigned',
                    'color' => $this->getStatusColor($appointment->status),
                    'service_title' => $appointment->service->title ?? 'Service', // Add service title
                    'name' => $appointment->name, // Add client name
                    'notes' => $appointment->notes, // Add notes
                ];
            } catch (\Exception $e) {
                \Log::error("Format error for appointment {$appointment->id}: {$e->getMessage()}");
                return null;
            }
        })->filter();

        return view('backend.dashboard.index', compact('appointments', 'categories'));
    }

    // Helper function to get color based on status
    private function getStatusColor($status)
    {
        $colors = [
            'Pending payment' => '#f39c12',
            'Processing' => '#3498db',
            'Confirmed' => '#2ecc71',
            'Cancelled' => '#ff0000',
            'Completed' => '#008000',
            'On Hold' => '#95a5a6',
            'Rescheduled' => '#f1c40f',
            'No Show' => '#e67e22',
        ];

        return $colors[$status] ?? '#7f8c8d';
    }


    // In AppointmentController.php
    public function updateStatus(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'status' => 'required|in:Pending payment,Processing,Confirmed,Cancelled,Completed,On Hold,No Show'
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $appointment->status = $request->status;
        $appointment->save();

        event(new StatusUpdated($appointment));

        return back()->with('success', 'Status updated successfully');
    }
}
