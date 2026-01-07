@extends('booking_layout')

@section('title','Booking Portal - TITLE')

@section('head')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<script src="{{ asset('js/portal.js') }}" defer></script>
@endsection

@section('content')
<div class="portal-shell">

    <!-- Top Minimal Nav -->
    <header class="portal-topbar">
        <div class="topbar-inner">
            <div class="brand">
                <img src="{{ asset('images/Logo.png') }}" alt="Title" class="brand-mark">
            </div>
            <nav class="mini-nav">
                <a href="#services" class="mini-link">Services</a>
                <a href="#process" class="mini-link">Process</a>
                <a href="#why" class="mini-link">Why Us</a>
                <a href="#contact" class="mini-link">Contact</a>
                <button id="openBookingFormBtn" type="button" class="btn btn-primary top-cta">
                    <i class="fas fa-calendar-plus"></i> Book Now
                </button>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <section class="portal-hero expanded-hero" id="hero">
        <div class="hero-grid">
            <div class="hero-main">
                <img src="{{ asset('images/Logo.png') }}" alt="Full Logo" class="hero-logo">
                <h1 class="portal-title">[INSERT HEADING]</h1>
                <p class="portal-subtitle hero-sub">
                    [Inset text (paragraph, information, etc.)]
                </p>

                @if(session('success'))
                    <div class="alert alert-success hero-alert">{{ session('success') }}</div>
                @endif
                @if($errors->any() && old('_from')==='createBooking')
                    <div class="alert alert-danger hero-alert">
                        <ul class="m-0 ps-3" style="font-size:.7rem;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="hero-actions">
                    <button type="button"
                            id="openBookingFormBtnHero"
                            class="btn btn-primary hero-book-btn">
                        <i class="fas fa-calendar-plus"></i> Book Now
                    </button>
                    <a href="#process" class="btn btn-secondary hero-secondary">
                        <i class="fas fa-arrow-circle-down"></i> How It Works
                    </a>
                </div>

                <div class="trust-metrics">
                    <div class="metric">
                        <span class="metric-value">INSERT TEXT</span>
                        <span class="metric-label">INSERT TEXT</span>
                    </div>
                    <div class="metric">
                        <span class="metric-value">INSERT TEXT</span>
                        <span class="metric-label">INSERT TEXT</span>
                    </div>
                    <div class="metric">
                        <span class="metric-value">INSERT TEXT</span>
                        <span class="metric-label">INSERT TEXT</span>
                    </div>
                </div>
            </div>
            <div class="hero-side">
                <div class="side-card glass-tile">
                    <h3 class="side-heading">INSERT TEXT</h3>
                    <p class="side-desc">
                        INSERT PARAGRAPH
                    </p>
                    <ul class="side-list">
                        <li><i class="fas fa-check-circle"></i> INSERT TEXT</li>
                        <li><i class="fas fa-check-circle"></i> INSERT TEXT</li>
                        <li><i class="fas fa-check-circle"></i> INSERT TEXT</li>
                        <li><i class="fas fa-check-circle"></i> INSERT TEXT</li>
                    </ul>
                    <div class="hero-carousel" aria-label="Showcase Images">
                        <div class="carousel-track">
                            <div class="carousel-slide"><img src="{{ asset('images/Sample1.jpg') }}" alt="Sample 1"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample2.jpg') }}" alt="Sample 2"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample3.jpg') }}" alt="Sample 3"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample4.jpg') }}" alt="Sample 4"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample5.jpg') }}" alt="Sample 5"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample1.jpg') }}" alt="Sample 1"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample2.jpg') }}" alt="Sample 2"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample3.jpg') }}" alt="Sample 3"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample4.jpg') }}" alt="Sample 4"></div>
                            <div class="carousel-slide"><img src="{{ asset('images/Sample5.jpg') }}" alt="Sample 5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INLINE BOOKING FORM -->
    <div id="bookingFormWrapper"
         class="booking-form-wrapper"
         aria-hidden="true"
         data-auto-open="{{ ($errors->any() && old('_from')==='createBooking') ? '1':'0' }}">
        <form id="bookingInlineForm"
              action="{{ route('booking.portal.store') }}"
              method="POST"
              class="booking-form-panel"
              novalidate>
            @csrf
            <input type="hidden" name="_from" value="createBooking">

            <h2 class="booking-form-title">Booking Request</h2>

            <div id="formErrorSummary" class="portal-alert error" style="display:none;font-size:.65rem;"></div>

            <div class="form-row uniform">
                <div class="form-group" style="margin-right:25px;">
                    <label>Full Name *</label>
                    <input name="customer_name" class="form-input" required value="{{ old('customer_name') }}">
                    <div class="field-error" data-error-for="customer_name"></div>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input name="email" type="email" class="form-input" required value="{{ old('email') }}">
                    <div class="field-error" data-error-for="email"></div>
                </div>
            </div>

            <div class="form-row uniform">
                <div class="form-group" style="margin-right:25px;">
                    <label>Contact Number *</label>
                    <input name="contact_number" class="form-input" required value="{{ old('contact_number') }}">
                    <div class="field-error" data-error-for="contact_number"></div>
                </div>
                <div class="form-group">
                    <label>Service Type *</label>
                    <select name="service_type" class="form-input" required>
                        <option value="">-- select service --</option>
                        @foreach(($serviceTypes ?? []) as $st)
                            <option value="{{ $st }}" @selected(old('service_type') == $st)>{{ $st }}</option>
                        @endforeach
                        <option value="Other" @selected(old('service_type') === 'Other')>Other</option>
                    </select>
                    <div class="field-error" data-error-for="service_type"></div>
                </div>
            </div>

            <div class="form-row uniform">
                <div class="form-group" style="margin-right:25px;">
                    <label>Preferred Date *</label>
                    <input type="date"
                           name="preferred_date"
                           class="form-input"
                           required
                           value="{{ old('preferred_date', now()->format('Y-m-d')) }}">
                    <div class="field-error" data-error-for="preferred_date"></div>
                </div>
                <div class="form-group">
                    <label>Preferred Time *</label>
                    <input type="time"
                           name="preferred_time"
                           class="form-input"
                           required
                           value="{{ old('preferred_time') }}">
                    <div class="field-error" data-error-for="preferred_time"></div>
                </div>
            </div>

            <div class="form-row single">
                <div class="form-group">
                    <label>Additional Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="form-input" style="resize:vertical;">{{ old('notes') }}</textarea>
                    <div class="field-error" data-error-for="notes"></div>
                </div>
            </div>

            <div class="note" style="margin-top:6px;">
                We’ll review availability & confirm via your preferred contact method.
            </div>

            <div class="button-row" style="margin-top:18px;display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" id="cancelBookingFormBtn" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary" id="bookingSubmitBtn">Submit Booking</button>
            </div>
        </form>
    </div>

    <!-- SERVICES -->
    <section class="section-block" id="services">
        <div class="section-head">
            <h2 class="section-title">Core Services</h2>
            <p class="section-sub">
                INSERT PARAGRAPH
            </p>
        </div>
        <div class="card-grid services-grid">
            <div class="svc-card">
                <div class="svc-icon gradient"><i class="fas fa-volume-up"></i></div>
                <h3>INSERT TEXT</h3>
                <p>INSERT SENTENCE</p>
            </div>
            <div class="svc-card">
                <div class="svc-icon gradient"><i class="fas fa-music"></i></div>
                <h3>INSERT TEXT</h3>
                <p>INSERT SENTENCE</p>
            </div>
            <div class="svc-card">
                <div class="svc-icon gradient"><i class="fas fa-project-diagram"></i></div>
                <h3>INSERT TEXT</h3>
                <p>INSERT SENTENCE</p>
            </div>
            <div class="svc-card">
                <div class="svc-icon gradient"><i class="fas fa-sliders-h"></i></div>
                <h3>INSERT TEXT</h3>
                <p>INSERT SENTENCE</p>
            </div>
            <div class="svc-card">
                <div class="svc-icon gradient"><i class="fas fa-shield-alt"></i></div>
                <h3>INSERT TEXT</h3>
                <p>INSERT SENTENCE</p>
            </div>
            <div class="svc-card">
                <div class="svc-icon gradient"><i class="fas fa-layer-group"></i></div>
                <h3>INSERT TEXT</h3>
                <p>INSERT SENTENCE</p>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="section-block alt-surface" id="process">
        <div class="section-head">
            <h2 class="section-title">Process</h2>
            <p class="section-sub">INSERT PARAGRAPH</p>
        </div>
        <div class="process-timeline" style="display: flex; justify-content: center;">
            <div class="p-step">
                <div class="p-badge">1</div>
                <h4>INSERT TEXT</h4>
                <p>INSERT PROCESS</p>
            </div>
            <div class="p-step">
                <div class="p-badge">2</div>
                <h4>INSERT TEXT</h4>
                <p>INSERT PROCESS</p>
            </div>
            <div class="p-step">
                <div class="p-badge">3</div>
                <h4>INSERT TEXT</h4>
                <p>INSERT PROCESS</p>
            </div>
            <div class="p-step">
                <div class="p-badge">4</div>
                <h4>INSERT TEXT</h4>
                <p>INSERT PROCESS</p>
            </div>
            <div class="p-step">
                <div class="p-badge">5</div>
                <h4>INSERT TEXT</h4>
                <p>INSERT PROCESS</p>
            </div>
        </div>
        <div class="motto">
            <i class="fas fa-quote-left"></i>
            <span>"INSERT QUOTE"</span>
        </div>
    </section>

    <!-- WHY US -->
    <section class="section-block" id="why">
        <div class="section-head">
            <h2 class="section-title">Why choose us?</h2>
            <p class="section-sub">INSERT PARAGRAPH</p>
        </div>
        <div class="why-grid" style="display: flex; justify-content: center;">
            <div class="why-card">
                <i class="fas fa-award-fill"></i>
                <h3>INSERT TEXT</h3>
                <p>INSERT QUALITY</p>
            </div>
            <div class="why-card">
                <i class="fas fa-coins"></i>
                <h3>INSERT TEXT</h3>
                <p>INSERT QUALITY</p>
            </div>
            <div class="why-card">
                <i class="fas fa-thumbs-up"></i>
                <h3>INSERT TEXT</h3>
                <p>INSERT QUALITY</p>
            </div>
            <div class="why-card">
                <i class="fas fa-comments"></i>
                <h3>INSERT TEXT</h3>
                <p>INSERT QUALITY</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="final-cta" id="contact">
        <div class="cta-inner">
            <h2 class="cta-title">INSERT TEXT</h2>
            <p class="cta-sub">Schedule your [INSERT] consultation now.</p>
            <button type="button" id="openBookingFormBtnBottom" class="btn btn-primary cta-btn">
                <i class="fas fa-calendar-plus"></i> Start Booking
            </button>
        </div>
    </section>

    <footer class="portal-footer">
        <div class="footer-inner">
            <span>&copy; {{ date('Y') }} SYSTEM NAME. All rights reserved.</span>
            <span class="foot-meta">INSERT TEXT | INSERT TEXT | INSERT TEXT</span>
        </div>
    </footer>
</div>
@endsection