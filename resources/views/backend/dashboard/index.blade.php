@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Appointments</h1>
        <div class="category-stats">
            <h5 class="mb-3" style="font-weight: bold;">Pending Appointments:</h5>
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-auto">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="category-info">
                                <span class="category-name">{{ $category->title }}</span>
                                <span class="category-count">{{ $category->current_appointments_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@stop

@section('content')
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-sm-12">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Appointment Modal -->
    <form id="appointmentStatusForm" method="POST" action="{{ route('dashboard.update.status') }}">
        @csrf
        <input type="hidden" name="appointment_id" id="modalAppointmentId">

        <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Client:</strong> <span id="modalAppointmentName">N/A</span></p>
                        <p><strong>Service:</strong> <span id="modalService">N/A</span></p>
                        <p><strong>Email:</strong> <span id="modalEmail">N/A</span></p>
                        <p><strong>Phone:</strong> <span id="modalPhone">N/A</span></p>
                        <p><strong>Staff:</strong> <span id="modalStaff">N/A</span></p>
                        <p><strong>Date & Time:</strong> <span id="modalStartTime">N/A</span></p>
                        <p><strong>Amount: PHP</strong> <span id="modalAmount">N/A</span></p>
                        <p><strong>Notes:</strong> <span id="modalNotes">N/A</span></p>
                        <p><strong>Current Status:</strong> <span id="modalStatusBadge">N/A</span></p>

                        <div class="form-group">
                            <label><strong>Change Status:</strong></label>
                            <select name="status" class="form-control" id="modalStatusSelect">
                                <option value="Pending payment">Pending payment</option>
                                <option value="Processing">Processing</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Completed">Completed</option>
                                <option value="On Hold">On Hold</option>
                                <option value="No Show">No Show</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateStatusBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" />
    <style>
        .category-stats {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .category-card {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .category-icon {
            background: #007bff;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .category-info {
            display: flex;
            flex-direction: column;
        }

        .category-name {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .category-count {
            font-size: 1.2rem;
            font-weight: bold;
            color: #343a40;
        }

        #calendar {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .fc-toolbar h2 {
            font-size: 1.2em;
        }

        /* Calendar View Optimizations */
        .fc-view {
            background: white;
        }

        .fc-event {
            border-radius: 4px;
            border: none;
            padding: 2px 4px;
            margin: 1px 0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .fc-event:hover {
            transform: scale(1.02);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .fc-time-grid-event {
            min-height: 25px;
        }

        .fc-time-grid-event .fc-content {
            padding: 2px 4px;
        }

        .fc-time-grid-event .fc-time {
            font-size: 0.85em;
            padding: 0 4px;
        }

        .fc-time-grid-event .fc-title {
            font-size: 0.9em;
            font-weight: 500;
        }

        .fc-time-grid {
            min-height: 600px !important;
        }

        .fc-slats td {
            height: 40px;
        }

        .fc-axis {
            width: 70px !important;
            padding: 0 10px;
        }

        .fc-content-skeleton {
            padding-bottom: 5px;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 8px 8px 0 0;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 0 0 8px 8px;
        }

        .btn-close {
            padding: 0.5rem;
            margin: -0.5rem -0.5rem -0.5rem auto;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap 5 tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Get appointments data
            var appointments = {!! json_encode($appointments ?? []) !!};

            // Initialize calendar
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaDay'
                },
                defaultView: 'month',
                editable: false,
                slotDuration: '00:30:00',
                minTime: '06:00:00',
                maxTime: '22:00:00',
                events: appointments,
                eventRender: function(event, element) {
                    element.tooltip({
                        title: event.description || 'No description',
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                },
                eventClick: function(calEvent, jsEvent, view) {
                    // Populate modal with event data
                    $('#modalAppointmentId').val(calEvent.id);
                    $('#modalAppointmentName').text(calEvent.name || calEvent.title.split(' - ')[0] || 'N/A');
                    $('#modalService').text(calEvent.service_title || calEvent.title.split(' - ')[1] || 'N/A');
                    $('#modalEmail').text(calEvent.email || 'N/A');
                    $('#modalPhone').text(calEvent.phone || 'N/A');
                    $('#modalStaff').text(calEvent.staff || 'N/A');
                    $('#modalAmount').text(calEvent.amount || 'N/A');
                    $('#modalNotes').text(calEvent.description || calEvent.notes || 'N/A');
                    $('#modalStartTime').text(moment(calEvent.start).format('MM-DD-YYYY h:mm A'));
                    $('#modalEndTime').text(calEvent.end ? moment(calEvent.end).format('MM-DD-YYYY h:mm A') : 'N/A');

                    // Get the status from the calendar event
                    var status = calEvent.status || 'Pending payment';
                    $('#modalStatusSelect').val(status);

                    // Set status badge
                    var statusColors = {
                        'Pending payment': '#f39c12',
                        'Processing': '#3498db',
                        'Confirmed': '#2ecc71',
                        'Cancelled': '#ff0000',
                        'Completed': '#008000',
                        'On Hold': '#95a5a6',
                        'No Show': '#e67e22'
                    };

                    var badgeColor = statusColors[status] || '#7f8c8d';
                    $('#modalStatusBadge').html(
                        '<span class="badge px-2 py-1" style="background-color: ' + badgeColor + '; color: white;">' + status + '</span>'
                    );

                    $('#appointmentModal').modal('show');
                }
            });

            // Update modal event handlers to use Bootstrap 5 syntax
            $('#appointmentModal').on('show.bs.modal', function (event) {
                // Modal show logic will be handled by the event click handler
            });

            // Form submission handling
            $('#appointmentStatusForm').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $submitBtn = $('#updateStatusBtn');
                var $spinner = $submitBtn.find('.spinner-border');

                $submitBtn.prop('disabled', true);
                $spinner.removeClass('d-none');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        $('#appointmentModal').modal('hide');
                        // Refresh calendar or show success message
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred while updating the status.');
                    },
                    complete: function() {
                        $submitBtn.prop('disabled', false);
                        $spinner.addClass('d-none');
                    }
                });
            });
        });
    </script>
@stop
