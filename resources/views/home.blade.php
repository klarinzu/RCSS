@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
@php
     $appointments = Appointment::whereMonth('booking_date', Carbon::now()->month)
            ->whereYear('booking_date', Carbon::now()->year)
            ->where('status', 'Confirmed') // Only show confirmed appointments
            ->get();
@endphp
    <p>Welcome to this beautiful admin panel.</p>
    <div id="calendar"></div>

    <!-- Modal Component -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            events: [
                @foreach($appointments as $appointment)
                    {
                        title: '{{ $appointment->name }} - {{ $appointment->service->name }}',  // Appointment Name and Service
                        start: '{{ $appointment->booking_date }}T{{ $appointment->booking_time }}', // Date + Time
                        end: '{{ $appointment->booking_date }}T{{ \Carbon\Carbon::parse($appointment->booking_time)->addHours(1)->format('H:i') }}', // Add one hour to the appointment time for end time
                        description: '{{ $appointment->notes }}', // Description (Optional)
                        color: 'green', // Color for confirmed appointments
                    },
                @endforeach
            ],
            // Optional: Customization for the calendar
            eventClick: function(event) {
                // If you want to show more info when clicking on an event, you can do so here.
                showModal(event.title);  // Display event title (name + service)
            },
        });
    });

    // Function to show modal instead of alert
    function showModal(message) {
        const modalBody = document.getElementById('alertModalBody');
        modalBody.textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('alertModal'));
        modal.show();
    }
</script>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.css" rel="stylesheet">
@stop

@section('js')
    <!-- jQuery and FullCalendar JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stop
