<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $setting->meta_title }}</title>
      <!-- SEO Meta Tags -->
      <meta name="description" content="{{ $setting->meta_description }}">
      <meta name="keywords" content="{{ $setting->meta_keywords }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"
        integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @if ($setting->header)
        {!! $setting->header !!}
    @endif
</head>

<body>
    <header class="header-section">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="bi bi-calendar-check"></i> RCSS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endguest

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                        @endauth

                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="booking-container">
            <div class="booking-header">
                <h2><i class="bi bi-calendar-check"></i> Room Cleaning Appointment </h2>
                <p class="mb-0">Book your appointment in a few simple steps</p>
            </div>

            <div class="booking-steps position-relative">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-title">Category</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-title">Service</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-title">Staff</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-title">Date & Time</div>
                </div>
                <div class="step" data-step="5">
                    <div class="step-number">5</div>
                    <div class="step-title">Confirm</div>
                </div>
                <div class="progress-bar-steps">
                    <div class="progress"></div>
                </div>
            </div>

            <div class="booking-content">
                <!-- Step 1: Category Selection -->
                <div class="booking-step active" id="step1">
                    <h3 class="mb-4">Select a Category</h3>
                    <div class="row row-cols-1 row-cols-md-3 g-4" id="categories-container">
                        <!-- Categories will be inserted here by jQuery -->
                    </div>
                </div>

                <!-- Step 2: Service Selection -->
                <div class="booking-step" id="step2">
                    <h3 class="mb-4">Select a Service</h3>
                    <div class="selected-category-name mb-3 fw-bold"></div>
                    <div class="row row-cols-1 row-cols-md-3 g-4" id="services-container">
                        <!-- Services will be loaded dynamically based on category -->
                    </div>
                </div>

                <!-- Step 3: Employee Selection -->
                <div class="booking-step" id="step3">
                    <h3 class="mb-4">Select a Staff Member</h3>
                    <div class="selected-service-name mb-3 fw-bold"></div>
                    <div class="row row-cols-1 row-cols-md-3 g-4" id="employees-container">
                        <!-- Employees will be loaded dynamically based on service -->
                    </div>
                </div>

                <!-- Step 4: Date and Time Selection -->
                <div class="booking-step" id="step4">
                    <h3 class="mb-4">Select Date & Time</h3>
                    <div class="selected-employee-name mb-3 fw-bold"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <button class="btn btn-sm btn-outline-secondary" id="prev-month" type="button">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <h5 class="mb-0" id="current-month">March 2023</h5>
                                    <button class="btn btn-sm btn-outline-secondary" id="next-month" type="button">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table class="table table-calendar">
                                        <thead>
                                            <tr>
                                                <th>Sun</th>
                                                <th>Mon</th>
                                                <th>Tue</th>
                                                <th>Wed</th>
                                                <th>Thu</th>
                                                <th>Fri</th>
                                                <th>Sat</th>
                                            </tr>
                                        </thead>
                                        <tbody id="calendar-body">
                                            <!-- Calendar will be generated dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Available Time Slots</h5>
                                    <div id="selected-date-display" class="text-muted small"></div>
                                </div>
                                <div class="card-body">
                                    <div id="time-slots-container" class="d-flex flex-wrap">
                                        <!-- Time slots will be loaded dynamically -->
                                        <div class="text-center text-muted w-100 py-4">
                                            Please select a date to view available times
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Confirmation -->
                <div class="booking-step" id="step5">
                    <h3 class="mb-4">Confirm Your Booking</h3>
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Booking Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-item">
                                <div class="row">
                                    <div class="col-md-4 text-muted">Category:</div>
                                    <div class="col-md-8" id="summary-category"></div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="row">
                                    <div class="col-md-4 text-muted">Service:</div>
                                    <div class="col-md-8" id="summary-service"></div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="row">
                                    <div class="col-md-4 text-muted">Staff Member:</div>
                                    <div class="col-md-8" id="summary-employee"></div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="row">
                                    <div class="col-md-4 text-muted">Date & Time:</div>
                                    <div class="col-md-8" id="summary-datetime"></div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="row">
                                    <div class="col-md-4 text-muted">Duration:</div>
                                    <div class="col-md-8" id="summary-duration"></div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="row">
                                    <div class="col-md-4 text-muted">Price:</div>
                                    <div class="col-md-8" id="summary-price"></div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="row">
                                    <div class="col-md-4 text-muted">Notes:</div>
                                    <div class="col-md-8" id="summary-notes"></div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5>Your Information</h5>
                                <form id="customer-info-form" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="customer-name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="customer-name" required>
                                            <div class="invalid-feedback">
                                                Please provide your full name.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="customer-email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="customer-email" required>
                                            <div class="invalid-feedback">
                                                Please provide a valid email address.
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="customer-phone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" id="customer-phone" required>
                                            <div class="invalid-feedback">
                                                Please provide your phone number.
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="customer-notes" class="form-label">Notes (Optional)</label>
                                            <textarea class="form-control" id="customer-notes" rows="3" style="white-space: pre-wrap; min-height: 100px;"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-footer">
                <button class="btn btn-outline-secondary" id="prev-step" disabled type="button">
                    <i class="bi bi-arrow-left"></i> Previous
                </button>
                <button class="btn btn-primary" id="next-step" type="button">
                    Next <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <footer>
        <div class="container pb-2">
            <div class="row text-center">
            <span>© Copyright 2025 RCSS. All rights reserved.Version 1.0.1</a></span>
            </div>
        </div>
    </footer>

    <!-- Loading Spinner -->
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="mb-0">Processing your request...</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Booking Successful!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <p class="text-center">Your appointment has been successfully booked. We've sent a confirmation email to your registered email address.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <p class="text-center" id="error-message">An error occurred while processing your request. Please try again.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/booking.js') }}"></script>

    @if ($setting->footer)
        {!! $setting->footer !!}
    @endif
</body>

</html>
