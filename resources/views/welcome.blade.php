<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CHED-eTrack</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Styles via Vite (or your build tool) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /*********************************************
         * THEME VARIABLES & GRADIENTS FOR LIGHT & DARK
         *********************************************/
        :root {
            /* Light Mode Colors */
            --clr-bg: #f7f8fa;
            --clr-text: #212529;
            --clr-primary: #133A86;
            --clr-secondary: #DA042A;
            --clr-accent: #FEE71B;

            --clr-card: #ffffff;
            --clr-border: #dee2e6;
            --clr-muted: #6c757d;
            --clr-link-hover: #0d6efd;

            /* Gradient for Navbar (Light) */
            --gradient-navbar: linear-gradient(to right, #133A86 0%, #2f54af 50%, #133A86 100%);

            /* Hero & Card Gradients (Light) */
            --gradient-hero: linear-gradient(135deg, #4F93FF 0%, #AABFFF 100%);
            --gradient-card: linear-gradient(135deg, rgba(255,255,255,0.7), rgba(255,255,255,0.3));

            /* Box Shadow (Light) */
            --box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            --background-glass: rgba(255, 255, 255, 0.2);
        }

        [data-theme="dark"] {
            /* Dark Mode Colors */
            --clr-bg: #0b0b0b;
            --clr-text: #ECECEC;
            --clr-primary: #3a60c9;
            --clr-secondary: #f82c2c;
            --clr-accent: #e1b800;

            --clr-card: #1b1b1b;
            --clr-border: #333333;
            --clr-muted: #aaaaaa;
            --clr-link-hover: #66a3ff;

            /* Gradient for Navbar (Dark) */
            --gradient-navbar: linear-gradient(to right, #3a60c9 0%, #557dd8 50%, #3a60c9 100%);

            /* Hero & Card Gradients (Dark) */
            --gradient-hero: linear-gradient(135deg, #1b1b1b 0%, #121212 100%);
            --gradient-card: linear-gradient(135deg, rgba(27,27,27,0.7), rgba(27,27,27,0.3));

            /* Box Shadow (Dark) */
            --box-shadow: 0 8px 24px rgba(0,0,0,0.5);
            --background-glass: rgba(27,27,27,0.2);
        }

        /********************************
         * GLOBAL BASE STYLES
         ********************************/
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Figtree', sans-serif;
            background-color: var(--clr-bg);
            color: var(--clr-text);
            transition: background-color 0.4s ease, color 0.4s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        a {
            text-decoration: none;
            color: var(--clr-text);
            transition: color 0.2s ease;
        }
        a:hover {
            color: var(--clr-link-hover);
        }
        hr {
            border: 0;
            height: 1px;
            background-color: var(--clr-border);
            margin: 0;
        }
        .container {
            max-width: 1140px;
        }

        /********************************
         * NAVBAR
         ********************************/
        .navbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: var(--gradient-navbar);
            transition: background 0.3s ease;
        }
        .navbar .navbar-brand {
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .navbar .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
            position: relative;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: transform 0.3s ease, color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            color: var(--clr-accent) !important;
            transform: translateY(-2px);
        }
        .theme-toggle-btn {
            color: #fff !important;
            font-size: 1.2rem;
            margin-left: 1rem;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .theme-toggle-btn:hover {
            transform: scale(1.1);
        }

        /********************************
         * HOMEPAGE SECTION (Hero + Features + Tutorial)
         ********************************/
        #homepage-section {
            /* This contains the hero, features, and tutorial */
        }

        /* HERO SECTION */
        .hero-section {
            position: relative;
            padding: 120px 0;
            background: var(--gradient-hero);
            overflow: hidden;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(4px);
        }
        .hero-content-container {
            position: relative;
            z-index: 2;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
        .hero-text {
            flex: 0 0 50%;
            max-width: 50%;
            color: #fff;
        }
        .hero-text h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        .hero-logo {
            flex: 0 0 50%;
            max-width: 50%;
            text-align: center;
        }
        .hero-logo img {
            max-width: 100%;
            height: auto;
            max-height: 320px;
        }
        @media (max-width: 991.98px) {
            .hero-content-container {
                flex-direction: column-reverse;
                text-align: center;
            }
            .hero-text, .hero-logo {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* FEATURE SECTION (SCROLLABLE) */
        .feature-section {
            padding: 60px 0;
            background-color: var(--clr-bg);
        }
        .features-scroll-container {
            display: flex;
            overflow-x: auto; /* Horizontal scroll */
            gap: 2rem;
            padding: 0 1rem;
            scroll-behavior: smooth;
        }
        .features-scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .features-scroll-container::-webkit-scrollbar-thumb {
            background-color: var(--clr-muted);
            border-radius: 4px;
        }
        .feature {
            min-width: 280px; /* Force each feature card to have a min width */
            flex: 0 0 auto;   /* Prevent from shrinking */
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1rem;
            background: var(--clr-card);
            border-radius: 8px;
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }
        .feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }
        .circle-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-bottom: 15px;
            background-color: var(--clr-accent);
            color: #333;
            font-size: 28px;
        }
        .feature h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--clr-text);
        }
        .feature p {
            font-size: 1rem;
            color: var(--clr-muted);
            margin: 0 auto;
            max-width: 250px;
        }

        /* STEP-BY-STEP UPLOAD TUTORIAL */
        .upload-tutorial-section {
            padding: 60px 0;
            background-color: var(--clr-bg);
        }
        .upload-tutorial-section h2 {
            text-align: center;
            margin-bottom: 3rem;
            font-weight: 700;
        }
        .step-tutorial {
            display: flex;
            margin-bottom: 2rem;
            gap: 1rem;
        }
        .step-number-tutorial {
            font-size: 2rem;
            font-weight: 800;
            min-width: 40px;
            color: var(--clr-primary);
        }
        .step-details h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .step-details p {
            margin: 0;
            color: var(--clr-muted);
        }
        @media (max-width: 767.98px) {
            .step-tutorial {
                flex-direction: column;
            }
        }

        /********************************
         * SHADOW / NEUMORPHIC CONTAINER
         ********************************/
        .shadow-container {
            background: var(--clr-card);
            border-radius: 12px;
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .shadow-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--gradient-card);
            opacity: 0.06;
            pointer-events: none;
        }

        /********************************
         * UPLOAD SECTION
         ********************************/
        #upload-section-container {
            display: none; /* hidden by default */
            padding: 60px 0;
        }
        #upload-section-container h2 {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: var(--clr-text);
        }
        .form-control, .form-select {
            background-color: #fdfdfd;
            border: 1px solid var(--clr-border);
            color: #333;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 0.2rem rgba(19, 58, 134, 0.25);
        }
        .filepond--panel-root {
            background-color: #fdfdfd;
        }
        .filepond--label-action {
            color: var(--clr-primary);
        }

        /********************************
         * TRACK SECTION
         ********************************/
        #track-section-container {
            display: none; /* hidden by default */
            padding: 60px 0;
        }
        .stepper {
            display: flex;
            align-items: center;
            position: relative;
            margin-top: 30px;
            flex-wrap: nowrap; /* Prevents wrapping */
            padding: 0 20px; /* Adds spacing */
        }
        .stepper::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 5%;
            width: 90%;
            height: 2px;
            background-color: var(--clr-border);
            transform: translateY(-50%);
            z-index: 0;
        }
        .step {
    position: relative;
    z-index: 1;
    text-align: center;
    flex: 1;
}
        .step-number {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 700;
            transition: background-color 0.3s ease, transform 0.3s ease;
            background-color: var(--clr-primary);

        }
        .step-title {
            margin-top: 10px;
            font-size: 0.95rem;
            color: var(--clr-text);
            font-weight: 500;
        }
        .bg-submitted {
            background-color: var(--clr-primary);
        }
        .bg-processing {
            background-color: var(--clr-accent);
            color: #333 !important;
        }
        .bg-approved {
            background-color: #16a34a;
        }
        .bg-rejected {
            background-color: var(--clr-secondary);
        }
        .bg-blue-step {
            background-color: var(--clr-primary);
        }
        .active-step {
            animation: pulse 1s infinite alternate;
        }
        @keyframes pulse {
            0% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .ongoing-animation {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -120%); /* Adjusts placement */
    color: var(--clr-accent);
    font-weight: bold;
    font-size: 0.9rem;
    display: none;
}
        .ongoing-animation.show {
            display: block;
        }
        .ongoing-animation span {
            position: absolute;
            left: 0;
            width: 100%;
            text-align: center;
            color: var(--clr-accent);
            font-weight: bold;
            font-size: 0.9rem;
            animation: slide 4s linear infinite;
        }
        @keyframes slide {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .completed-step {
            opacity: 0.8;
        }
        #status-details {
            transition: all 0.5s ease;
            opacity: 0;
            display: none;
        }
        #status-details.show {
            opacity: 1;
            display: block;
        }

        /* PENDING STATUS MESSAGE */
        #pending-status-message {
            display: none; /* hide by default */
        }

        /********************************
         * PRIVACY & CONTACT
         ********************************/
        #privacy-section-container,
        #contact-section-container {
            display: none; /* hidden by default */
            padding: 60px 0;
        }

        /********************************
         * MODALS
         ********************************/
        .modal-content {
            background: var(--clr-card);
            color: var(--clr-text);
            border: 1px solid var(--clr-border);
            border-radius: 10px;
            box-shadow: var(--box-shadow);
        }
        .modal-header, .modal-footer {
            border-color: var(--clr-border);
        }
        .modal-title {
            font-weight: 600;
        }
        .btn-close {
            filter: invert(0);
        }
        .swal2-popup .swal2-title,
        .swal2-popup .swal2-content {
            color: #000 !important;
        }

        /********************************
         * FOOTER
         ********************************/
        .footer {
            background-color: var(--clr-primary);
            color: #fff;
            padding: 2rem 0;
            margin-top: auto;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .footer p {
            margin: 0.5rem 0;
        }
        .footer p strong {
            color: var(--clr-accent);
        }
        #stage-1-container,
#stage-2-container,
#stage-3-container,
#stage-4-container {
    flex: 1; /* Ensures equal spacing */
    min-width: 100px;
}

.completed-step {
    opacity: 0.8;
}
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#" id="nav-home">
      <img src="{{ asset('images/logo.png') }}" alt="CDTMS Logo">
      <span>CHED-eTrack</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation"
            style="color: #fff; border: none;">
      <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item">
          <a id="nav-features" class="nav-link" href="#">
            <i class="fas fa-star"></i> Features
          </a>
        </li>
        <li class="nav-item">
          <a id="nav-upload" class="nav-link" href="#">
            <i class="fas fa-upload"></i> Upload
          </a>
        </li>
        <li class="nav-item">
          <a id="nav-track" class="nav-link" href="#">
            <i class="fas fa-search-location"></i> Track
          </a>
        </li>
        <li class="nav-item">
          <a id="nav-privacy" class="nav-link" href="#">
            <i class="fas fa-user-shield"></i> Privacy
          </a>
        </li>
        <li class="nav-item">
          <a id="nav-contact" class="nav-link" href="#">
            <i class="fas fa-address-book"></i> Contact
          </a>
        </li>
        <li class="nav-item">
          <button class="theme-toggle-btn" id="theme-toggle" aria-label="Toggle Theme">
            <i class="fas fa-adjust"></i>
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>
<hr>

<!-- HOMEPAGE SECTION (Hero + Features + Tutorial) -->
<div id="homepage-section">
    <!-- HERO SECTION -->
    <section class="hero-section" id="home">
      <div class="hero-overlay"></div>
      <div class="container hero-content-container">
          <div class="hero-text">
              <h1>Welcome to CHED-eTrack</h1>
              <p>
                A comprehensive toolkit for managing and tracking your documents efficiently.
                Seamlessly upload your files and monitor their status through every stage.
                <br><br>
                <strong>Once a document is successfully uploaded, you will receive an email notification confirming receipt and details of your submission.</strong>
              </p>
          </div>
          <div class="hero-logo">
          <img src="{{ asset('images/logo.png') }}" alt="CDTMS Logo">
          </div>
      </div>
    </section>

    <!-- FEATURE SECTION -->
    <section class="feature-section">
      <div class="container">
        <h2 class="text-center mb-4">Key Features</h2>
        
        <!-- Horizontal scroll container -->
        <div class="features-scroll-container">
          <!-- Feature 1 -->
          <div class="feature">
            <div class="circle-icon">
              <i class="fas fa-file-upload"></i>
            </div>
            <h3>Upload Documents</h3>
            <p>Effortlessly upload PDF, DOC, and DOCX files from anywhere.</p>
          </div>
          <!-- Feature 2 -->
          <div class="feature">
            <div class="circle-icon">
              <i class="fas fa-search"></i>
            </div>
            <h3>Track Status</h3>
            <p>Monitor documents through submission, processing, and approval.</p>
          </div>
          <!-- Feature 3 -->
          <div class="feature">
            <div class="circle-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Secure System</h3>
            <p>Data privacy and secure document handling are our top priority.</p>
          </div>
          <!-- Feature 4 -->
          <div class="feature">
            <div class="circle-icon">
              <i class="fas fa-bell"></i>
            </div>
            <h3>Real-Time Alerts</h3>
            <p>Receive email notifications for every important update.</p>
          </div>
          <!-- Add more feature "cards" here if desired -->
        </div>
      </div>
    </section>

    <!-- STEP-BY-STEP UPLOAD TUTORIAL -->
    <section class="upload-tutorial-section">
      <div class="container">
          <h2>How to Upload Your Document</h2>
          <!-- Step 1 -->
          <div class="step-tutorial">
            <div class="step-number-tutorial">1</div>
            <div class="step-details">
                <h5>Prepare Your Files</h5>
                <p>Gather all files you plan to upload. Make sure they are in PDF, DOC, or DOCX format.</p>
            </div>
          </div>
          <!-- Step 2 -->
          <div class="step-tutorial">
            <div class="step-number-tutorial">2</div>
            <div class="step-details">
                <h5>Open the Upload Form</h5>
                <p>Click “Upload” in the navigation bar. Fill out your contact information accurately.</p>
            </div>
          </div>
          <!-- Step 3 -->
          <div class="step-tutorial">
            <div class="step-number-tutorial">3</div>
            <div class="step-details">
                <h5>Attach Files & Add Details</h5>
                <p>Select or drag-and-drop your files into the upload area. Provide details such as document type and submission purpose.</p>
            </div>
          </div>
          <!-- Step 4 -->
          <div class="step-tutorial">
            <div class="step-number-tutorial">4</div>
            <div class="step-details">
                <h5>Submit & Verify</h5>
                <p>Review the Terms & Conditions, then submit. Check your phone for an OTP verification code to finalize. An email will confirm your successful upload!</p>
            </div>
          </div>
      </div>
    </section>
</div>
<!-- END of HOMEPAGE SECTION -->

<!-- UPLOAD SECTION (HIDDEN BY DEFAULT) -->
<div class="container" id="upload-section-container">
  <section class="shadow-container mt-5">
    <h2 class="mb-4">Upload a Document</h2>
    @if($errors->any())
      <div class="mb-4 text-danger">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data" id="upload-form">
      @csrf
      <div class="row">
        <!-- Email -->
        <div class="col-md-6 mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input 
            type="email"
            class="form-control sanitize-input"
            id="email"
            name="email"
            placeholder="you@example.com"
            required
          >
          <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        </div>
        <!-- Full Name -->
        <div class="col-md-6 mb-3">
          <label for="full_name" class="form-label">Full Name</label>
          <input 
            type="text" 
            class="form-control sanitize-input"
            id="full_name"
            name="full_name"
            placeholder="John Doe"
            required
          >
        </div>
        <!-- Phone -->
        <div class="col-md-6 mb-3">
          <label for="phone_number" class="form-label">Phone Number</label>
          <input 
    type="tel" 
    class="form-control sanitize-input"
    id="phone_number"
    name="phone_number"
    placeholder="+63 912 345 6789"
    required
    pattern="^(\+63\s?[89]\d{2}\s?\d{3}\s?\d{4})$"
    inputmode="numeric"
    maxlength="16"
  >
          <div class="form-text">
          Enter a valid **Philippine** phone number in the format: <strong>+63 912 345 6789</strong>.
          </div>
        </div>
        <!-- Document Type -->
        <div class="col-md-6 mb-3">
          <label for="document_type" class="form-label">Document Type</label>
          <select class="form-select sanitize-input" id="document_type" name="document_type" required>
            <option value="" selected disabled>Select Document Type</option>
            <option value="Application for Scholarship">Application for Scholarship</option>
            <option value="Audit Observation Memorandum">Audit Observation Memorandum</option>
<option value="Authority for Reimburse">Authority for Reimburse</option>
<option value="Authority to Hold Activity">Authority to Hold Activity</option>
<option value="Authority to Travel">Authority to Travel</option>
<option value="CEB Resolution">CEB Resolution</option>
<option value="Certificate">Certificate</option>
<option value="Certificate of Travel">Certificate of Travel</option>
<option value="Clearance">Clearance</option>
<option value="Courtesy Call">Courtesy Call</option>
<option value="Complete Staff Work (CSW) Document">Complete Staff Work (CSW) Document</option>
<option value="Documentary Requirements">Documentary Requirements</option>
<option value="Disbursement Voucher (DV)">Disbursement Voucher (DV)</option>
<option value="Endoresement">Endoresement</option>
<option value="Foreigb Travel">Foreigb Travel</option>
<option value="Justificatio">Justificatio</option>
<option value="Letter of Invitation">Letter of Invitation</option>
<option value="Meeting Minutes of the Meeting">Meeting Minutes of the Meeting</option>
<option value="Modification Advice Form">Modification Advice Form</option>
<option value="Nomination">Nomination</option>
<option value="Notice">Notice</option>
<option value="Notice of Meeting">Notice of Meeting</option>
<option value="Notice to Proceed">Notice to Proceed</option>
<option value="Obligation Request Slip (ORS)">Obligation Request Slip (ORS)</option>
<option value="Official Receipt (OR)">Official Receipt (OR)</option>
<option value="Outputs/ Deliverables">Outputs/ Deliverables</option>
<option value="Press Release">Press Release</option>
<option value="Project/ Program">Project/ Program</option>
<option value="Referral Sheet">Referral Sheet</option>
<option value="Reply">Reply</option>
<option value="Request for Assistance/Guidance">Request for Assistance/Guidance</option>
<option value="Request for Budget Approval">Request for Budget Approval</option>
<option value="Request for Data">Request for Data</option>
<option value="Request fog Dissemination">Request fog Dissemination</option>
<option value="Request for Quotation">Request for Quotation</option>
<option value="SUC Travel (assessment)">SUC Travel (assessment)</option>

            <!-- ... other document types ... -->
            <option value="Vetting">Vetting (assessment)</option>
          </select>
        </div>
        <!-- Details -->
        <div class="col-md-12 mb-3">
          <label for="details" class="form-label">Details</label>
          <textarea 
            class="form-control sanitize-input"
            id="details"
            name="details"
            placeholder="Details"
            rows="3"
            required
          ></textarea>
        </div>
      </div>
      <!-- Purpose -->
      <div class="mb-3">
        <label for="purpose" class="form-label">Purpose of Submission</label>
        <textarea 
          class="form-control sanitize-input" 
          id="purpose"
          name="purpose"
          placeholder="Purpose of Submission"
          rows="3"
          required
        ></textarea>
      </div>
      <!-- File Upload -->
      <div class="shadow-container p-4 rounded mb-3" style="box-shadow: none;">
        <label for="document" class="form-label">Upload Document(s)</label>
        <div class="filepond-wrapper mt-2">
          <input 
            type="file"
            name="document[]"
            id="document"
            class="filepond"
            multiple
            required
          />
        </div>
        <p class="mt-2" style="font-size: 0.9rem; color: var(--clr-muted);">
          Allowed: PDF, DOC, DOCX<br>
          Use correct extensions (<code>sample.pdf</code>, not <code>sample.pdf.pdf</code>).<br>
          Files show a green check once processed.<br>
          <strong>Note:</strong> You will receive an email notification once your submission is received.
        </p>
      </div>
      <input type="hidden" name="approval_status" value="Pending">

      <!-- Terms -->
      <div class="form-check mb-4">
        <input 
          id="agree_terms"
          name="agree_terms"
          type="checkbox"
          class="form-check-input"
          required
        >
        <label for="agree_terms" class="form-check-label">
          I agree to the <a href="#terms-modal" id="view-terms" style="color: var(--clr-primary); text-decoration: underline;">terms and conditions</a>.
        </label>
      </div>
      <!-- Submit -->
      <button type="submit" class="btn btn-primary w-100 py-2">
        Submit Document
      </button>
    </form>
  </section>
</div>

<!-- TRACK SECTION (HIDDEN BY DEFAULT) -->
<div class="container" id="track-section-container">
  <section class="shadow-container mt-5 mb-5">
    <h2 class="mb-4">Track Your Document</h2>
    <form method="GET" action="{{ route('documents.track') }}" id="track-form">
      @csrf
      <div class="mb-3">
        <label for="tracking_number" class="form-label">Tracking Number</label>
        <input 
          type="text"
          class="form-control sanitize-input"
          id="tracking_number"
          name="tracking_number"
          placeholder="Enter your Tracking Number"
          required
        >
      </div>
      <button type="submit" class="btn btn-success w-100 py-2">
        Track Document
      </button>
    </form>

    <!-- Pending status placeholder -->
    <div id="pending-status-message" class="text-center py-4 bg-warning text-dark rounded mb-4">
      <h1 class="display-6 fw-bold">Your document is currently pending approval.</h1>
      <p class="mt-3 fs-6">We are reviewing your submission. Please check back later for updates.</p>
    </div>

    <!-- Stepper -->
    <div id="stepper-container" class="d-none">
      <div class="mt-4">
        <div class="stepper">
          <!-- Step 1: Submitted -->
          <div class="step" id="stage-1-container">
            <div class="step-number bg-submitted" id="stage-1">1</div>
            <div class="step-title">Submitted</div>
          </div>
          <div class="ongoing-animation" id="ongoing-animation-1">
            <span>Ongoing</span>
          </div>
          <!-- Step 2: Processing -->
          <div class="step" id="stage-2-container">
    <div class="step-number bg-submitted" id="stage-2">2</div>
    <div class="step-title">Approved / Rejected</div>
</div>

<div class="ongoing-animation" id="ongoing-animation-2">
    <span>Ongoing</span>
</div>

<div class="step" id="stage-3-container">
    <div class="step-number bg-submitted" id="stage-3">3</div>
    <div class="step-title">Redirected</div>
</div>
          <div class="ongoing-animation" id="ongoing-animation-3">
            <span>Ongoing</span>
          </div>
          <!-- Step 4: Released -->
          <div class="step" id="stage-4-container">
            <div class="step-number bg-submitted" id="stage-4">4</div>
            <div class="step-title">Released</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Details -->
    <div id="status-details" class="mt-4">
      <h3 class="h5 fw-semibold" style="color: var(--clr-text);">Status Details</h3>
      <p class="mt-2" style="color: var(--clr-muted);" id="status-message"></p>
      <p class="mt-1" style="color: var(--clr-muted);" id="status-timestamp"></p>
      <div class="mt-4 d-none" id="file-names">
        <h4 class="h6 fw-semibold" style="color: var(--clr-text);">File(s) Associated:</h4>
        <ul id="file-list"></ul>
      </div>
    </div>
  </section>
</div>

<!-- PRIVACY SECTION (HIDDEN BY DEFAULT) -->
<div class="container" id="privacy-section-container">
  <section class="shadow-container mt-5 mb-5">
    <h2 class="mb-4">Privacy Policy</h2>
    <p>
      We value your privacy in accordance with the Data Privacy Act of 2012. Your personal information is protected and will be used solely for processing and tracking your document submissions.
    </p>
    <p>
      <strong>Information We Collect:</strong> Name, Email, Phone Number, and Uploaded Documents.<br>
      <strong>Usage:</strong> To manage your submissions, provide updates, and maintain the platform.
    </p>
    <p>
      Please see our full policy for more details. For questions, use the "Contact" tab.
    </p>
  </section>
</div>

<!-- CONTACT US SECTION (HIDDEN BY DEFAULT) -->
<div class="container" id="contact-section-container">
  <section class="shadow-container mt-5 mb-5">
    <h2 class="mb-4">Contact Us</h2>
    <p>Please reach out to your respective CHED Regional Office for assistance:</p>
    <ul style="list-style-type: none; padding: 0;">
      <li><strong>CHEDRO I:</strong> chedro1@ched.gov.ph | (072) 242-0238</li>
      <li><strong>CHEDRO II:</strong> chedro2@ched.gov.ph | (078) 396-0651</li>
      <!-- ...Add your other regions here... -->
    </ul>
  </section>
</div>

<!-- TERMS MODAL -->
<div id="terms-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Terms and Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>By uploading a document, you agree to our terms and conditions. Ensure that all documents are accurate and comply with our guidelines.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="agree-button" class="btn btn-primary">Agree</button>
        <button type="button" id="disagree-button" class="btn btn-secondary" data-bs-dismiss="modal">Disagree</button>
      </div>
    </div>
  </div>
</div>

<!-- OTP VERIFICATION MODAL -->
<div id="otp-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Phone Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Please enter the OTP sent to your phone to complete the document submission.</p>
        <input type="number" id="otp-input" class="form-control sanitize-input" placeholder="Enter OTP" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="verify-otp-btn">Verify</button>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
      <p><i class="fas fa-map-marker-alt"></i>
          <strong>Address:</strong> W375+PGP, Baliwasan Chico Road, Zamboanga, Zamboanga del Sur
      </p>
      <p><i class="fas fa-clock"></i>
          <strong>Hours:</strong> Open ⋅ Closes 5 PM
      </p>
      <p><i class="fas fa-phone"></i>
          <strong>Phone:</strong> (062) 991 7084
      </p>
  </div>
</footer>

<!-- SCRIPTS -->
<!-- Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.v3.site_key') }}"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- FilePond JS -->
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<!-- FilePond Plugins JS -->
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /****************************************************
     * THEME TOGGLE (Dark/Light)
     ****************************************************/
    const phoneInput = document.getElementById('phone_number');

phoneInput.addEventListener('input', function (e) {
    let inputVal = e.target.value.replace(/\D/g, ''); // Remove all non-numeric characters

    // Ensure it starts with "+63"
    if (!inputVal.startsWith("63")) {
        inputVal = "63" + inputVal;
    }
    inputVal = "+" + inputVal;

    // Format the number as +63 912 345 6789
    if (inputVal.length > 3) {
        inputVal = inputVal.slice(0, 3) + " " + inputVal.slice(3);
    }
    if (inputVal.length > 7) {
        inputVal = inputVal.slice(0, 7) + " " + inputVal.slice(7);
    }
    if (inputVal.length > 11) {
        inputVal = inputVal.slice(0, 11) + " " + inputVal.slice(11, 15);
    }

    e.target.value = inputVal;
});

// Ensure input always starts with +63 on focus
phoneInput.addEventListener('focus', function (e) {
    if (!e.target.value.startsWith("+63")) {
        e.target.value = "+63 ";
    }
});
    const themeToggleBtn = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('chedetrack-theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);

    themeToggleBtn.addEventListener('click', () => {
        const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('chedetrack-theme', theme);
    });

    /****************************************************
     * SECTION TOGGLING
     ****************************************************/
    const sections = [
      'homepage-section',
      'upload-section-container',
      'track-section-container',
      'privacy-section-container',
      'contact-section-container'
    ];

    function showSection(sectionId) {
    sections.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        if (id === sectionId) {
            el.style.opacity = '0';
            el.style.display = 'block';
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transition = 'opacity 0.4s ease-in-out';
            }, 50);
        } else {
            el.style.opacity = '0';
            setTimeout(() => {
                el.style.display = 'none';
            }, 400);
        }
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}


    // Show homepage by default
    showSection('homepage-section');

    // NAV LINKS
    document.getElementById('nav-home').addEventListener('click', (e) => {
      e.preventDefault();
      showSection('homepage-section');
    });
    document.getElementById('nav-features').addEventListener('click', (e) => {
      e.preventDefault();
      // Also show homepage (features are on the homepage)
      showSection('homepage-section');
    });
    document.getElementById('nav-upload').addEventListener('click', (e) => {
      e.preventDefault();
      showSection('upload-section-container');
    });
    document.getElementById('nav-track').addEventListener('click', (e) => {
      e.preventDefault();
      showSection('track-section-container');
    });
    document.getElementById('nav-privacy').addEventListener('click', (e) => {
      e.preventDefault();
      showSection('privacy-section-container');
    });
    document.getElementById('nav-contact').addEventListener('click', (e) => {
      e.preventDefault();
      showSection('contact-section-container');
    });

    /****************************************************
     * FILEPOND INITIALIZATION
     ****************************************************/
    FilePond.registerPlugin(
      FilePondPluginFileValidateSize,
      FilePondPluginFileValidateType,
      FilePondPluginImagePreview
    );
    const pond = FilePond.create(document.querySelector('input.filepond'), {
      allowDrop: true,
      dropOnPage: true,
      dropValidation: true,
      allowMultiple: true,
      allowReorder: true,
      maxFiles: 5,
      acceptedFileTypes: [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
      ],
      labelIdle: 'Drag & Drop your PDF/DOC/DOCX files or <span class="filepond--label-action">Browse</span>',
      maxFileSize: '5MB',
      instantUpload: true,
      allowFileTypeValidation: true,
      server: {
          process: (fieldName, file, metadata, load, error, progress, abort) => {
              // Simulate server processing for demonstration
              setTimeout(() => { load('fake-file-id'); }, 500);
          },
          revert: (uniqueFileId, load, error) => {
              load();
          }
      }
    });

    /****************************************************
     * MODAL: TERMS & CONDITIONS
     ****************************************************/
    const termsModalElement = document.getElementById('terms-modal');
    const termsModal = new bootstrap.Modal(termsModalElement, {
      backdrop: 'static',
      keyboard: false
    });
    const viewTerms = document.getElementById('view-terms');
    const agreeButton = document.getElementById('agree-button');
    const disagreeButton = document.getElementById('disagree-button');

    viewTerms.addEventListener('click', function(e) {
      e.preventDefault();
      termsModal.show();
    });
    agreeButton.addEventListener('click', function() {
      termsModal.hide();
      document.getElementById('agree_terms').checked = true;
      Swal.fire('Thank You!', 'You have agreed to the terms and conditions.', 'success');
    });
    disagreeButton.addEventListener('click', function() {
      document.getElementById('agree_terms').checked = false;
      Swal.fire('Agreement Required', 'You must agree to the terms and conditions to upload documents.', 'warning');
    });

    /****************************************************
     * UPLOAD FORM SUBMISSION
     ****************************************************/
    const uploadForm = document.getElementById('upload-form');
    uploadForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const termsAgreed = document.getElementById('agree_terms').checked;
      if (!termsAgreed) {
          Swal.fire('Terms Not Agreed', 'Please agree to the terms and conditions before uploading.', 'warning');
          return;
      }
      grecaptcha.execute('{{ config('services.recaptcha.v3.site_key') }}', { action: 'upload_form' })
    .then(function(token) {
      // Update (or create) the hidden reCAPTCHA input with the new token
      let recaptchaInput = document.getElementById('g-recaptcha-response');
      if (!recaptchaInput) {
        recaptchaInput = document.createElement('input');
        recaptchaInput.type = 'hidden';
        recaptchaInput.name = 'g-recaptcha-response';
        recaptchaInput.id = 'g-recaptcha-response';
        uploadForm.appendChild(recaptchaInput);
      }
      recaptchaInput.value = token;

      Swal.fire({
          title: 'Uploading...',
          html: 'Please wait while your document(s) are being uploaded.',
          allowOutsideClick: false,
          didOpen: () => {
              Swal.showLoading();
          }
      });

      const formData = new FormData(uploadForm);
      // Append FilePond files
      pond.getFiles().forEach(fileItem => {
          formData.append('document[]', fileItem.file, fileItem.file.name);
      });

      fetch(uploadForm.action, {
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'
          },
          body: formData
      })
      .then(response => response.json().then(data => ({ status: response.status, body: data })))
      .then(({ status, body }) => {
      Swal.close();
      if (status === 200 && body.success) {
          // Build HTML for the tracking numbers if available.
          let htmlContent = '<div style="text-align: left;">';
          if (body.documents && body.documents.length > 0) {
              body.documents.forEach(doc => {
                  // If a tracking number exists, display it; otherwise show "Pending verification"
                  const trackingDisplay = doc.tracking_number && doc.tracking_number.trim() !== ''
                      ? doc.tracking_number
                      : 'Pending OTP verification';
                  htmlContent += `
                    <div style="margin-bottom: 10px;">
                      <strong>Tracking Number:</strong> ${trackingDisplay}
                      <button class="btn btn-sm btn-secondary copy-btn"
                              data-clipboard-text="${trackingDisplay !== 'Pending OTP verification' ? doc.tracking_number : ''}"
                              style="margin-left: 10px;">
                        Copy
                      </button>
                    </div>
                  `;
              });
          } else {
              htmlContent += '<p>Tracking number will be provided after OTP verification.</p>';
          }
          htmlContent += '</div>';

          Swal.fire({
              title: 'Upload Received!',
              html: `
                <div style="text-align: left; line-height: 1.5;">
                  <p>Your document(s) have been received.<br>
                  Please wait while we complete the OTP verification process.</p>
                  ${htmlContent}
                </div>
              `,
              icon: 'success',
              showConfirmButton: true,
              didOpen: () => {
                  // Attach copy-to-clipboard functionality to each copy button.
                  const copyButtons = Swal.getHtmlContainer().querySelectorAll('.copy-btn');
                  copyButtons.forEach(button => {
                      button.addEventListener('click', () => {
                          const textToCopy = button.getAttribute('data-clipboard-text');
                          if (textToCopy) {
                              navigator.clipboard.writeText(textToCopy).then(() => {
                                  Swal.fire({
                                      title: 'Copied!',
                                      text: 'Tracking number has been copied to clipboard.',
                                      icon: 'success',
                                      timer: 1500,
                                      showConfirmButton: false
                                  });
                              }).catch(err => {
                                  Swal.fire({
                                      title: 'Error!',
                                      text: 'Failed to copy tracking number.',
                                      icon: 'error',
                                      timer: 1500,
                                      showConfirmButton: false
                                  });
                              });
                          }
                      });
                  });
              }
          }).then(() => {
              // Automatically show the OTP Modal after a short delay
              setTimeout(() => {
                  const otpModalElement = document.getElementById('otp-modal');
                  const otpModal = new bootstrap.Modal(otpModalElement);
                  otpModal.show();
              }, 300);
              uploadForm.reset();
              pond.removeFiles();
              resetStatusChart();
          });
        } else if (status === 422) {
    // Validation errors
    let errorHtml = '<div style="text-align: left;">';
    // Use an empty object if body.errors is null or undefined
    const errors = body.errors || {};
    for (const [field, messages] of Object.entries(errors)) {
        messages.forEach(message => {
            errorHtml += `<p><strong>${field}:</strong> ${message}</p>`;
        });
    }
    errorHtml += '</div>';

    Swal.fire({
        title: 'Validation Error',
        html: errorHtml,
        icon: 'error',
        showConfirmButton: true
    });
}
       else {
          Swal.fire('Error!', body.message || 'There was an error uploading your document(s).', 'error');
      }
  })
  .catch(error => {
      Swal.close();
      Swal.fire('Error!', 'There was an unexpected error.', 'error');
      console.error('Upload Error:', error);
  });
});
});
    /****************************************************
     * TRACK FORM SUBMISSION
     ****************************************************/
    const trackForm = document.getElementById('track-form');
    trackForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const trackingNumber = document.getElementById('tracking_number').value.trim();
        if (trackingNumber === '') {
            Swal.fire('Input Required', 'Please enter a Tracking Number.', 'warning');
            return;
        }
        Swal.fire({
            title: 'Tracking...',
            html: 'Please wait while we retrieve the status of your document.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/documents/track?tracking_number=${encodeURIComponent(trackingNumber)}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            Swal.close();
            if (status === 200 && body.success) {
                updateStatusChart(
                  body.status,
                  body.details,
                  body.file_names || [],
                  body.approval_status
                );

                Swal.fire({
                    title: 'Tracking Successful!',
                    html: `
                      <p>Status for Tracking Number <strong>${trackingNumber}</strong>: 
                         <strong>${body.status}</strong></p>
                      <button class="btn btn-sm btn-secondary copy-btn"
                              data-clipboard-text="${trackingNumber}"
                              style="margin-top: 10px;">
                        Copy Tracking Number
                      </button>
                    `,
                    icon: 'success',
                    showConfirmButton: true,
                    didOpen: () => {
                        const copyButton = Swal.getHtmlContainer().querySelector('.copy-btn');
                        if (copyButton) {
                            copyButton.addEventListener('click', () => {
                                const textToCopy = copyButton.getAttribute('data-clipboard-text');
                                navigator.clipboard.writeText(textToCopy).then(() => {
                                    Swal.fire({
                                        title: 'Copied!',
                                        text: 'Tracking number has been copied to clipboard.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }).catch(err => {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Failed to copy tracking number.',
                                        icon: 'error',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                });
                            });
                        }
                    }
                }).then(() => {
                    trackForm.reset();
                });
            } else if (status === 422) {
                // Validation errors
                let errorHtml = '<div style="text-align: left;">';
                for (const [field, messages] of Object.entries(body.errors)) {
                    messages.forEach(message => {
                        errorHtml += `<p><strong>${field}:</strong> ${message}</p>`;
                    });
                }
                errorHtml += '</div>';

                Swal.fire({
                    title: 'Validation Error',
                    html: errorHtml,
                    icon: 'error',
                    showConfirmButton: true
                });
            } else {
                Swal.fire('Error!', body.message || 'Unable to track your document.', 'error');
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire('Error!', 'There was an unexpected error.', 'error');
            console.error('Track Error:', error);
        });
    });

    /****************************************************
     * OTP VERIFICATION
     ****************************************************/
    document.getElementById('verify-otp-btn').addEventListener('click', function() {
        const otp = document.getElementById('otp-input').value.trim();
        if (!otp) {
            Swal.fire('Error', 'Please enter the OTP.', 'error');
            return;
        }
        Swal.fire({
            title: 'Verifying...',
            html: 'Please wait while we verify your OTP.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        fetch('/verify-otp', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ otp }),
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            if (data.success) {
                Swal.fire('Success', 'Your document has been uploaded successfully!', 'success');
                const otpModalElement = document.getElementById('otp-modal');
                const otpModal = bootstrap.Modal.getInstance(otpModalElement);
                otpModal.hide();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire('Error!', 'There was an unexpected error.', 'error');
            console.error('OTP Verification Error:', error);
        });
    });

    /****************************************************
     * GOOGLE reCAPTCHA
     ****************************************************/
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ config('services.recaptcha.v3.site_key') }}', {action: 'upload_form'})
        .then(function(token) {
            const form = document.getElementById('upload-form');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'g-recaptcha-response';
            input.value = token;
            form.appendChild(input);
        });
    });

    /****************************************************
     * STATUS CHART UTILS
     ****************************************************/
    function resetStatusChart() {
      for (let stepNumber = 1; stepNumber <= 4; stepNumber++) {
          const stepElement = document.getElementById(`stage-${stepNumber}`);
          if (stepElement) {
              stepElement.classList.remove(
                'bg-blue-step','bg-submitted','bg-processing','bg-approved','bg-rejected',
                'active-step','completed-step'
              );
              // Default to "bg-submitted"
              stepElement.classList.add('bg-submitted');
          }
      }
      document.getElementById('pending-status-message').style.display = 'none';
      document.getElementById('stepper-container').classList.add('d-none');
      const statusDetails = document.getElementById('status-details');
      if (statusDetails) {
          statusDetails.classList.remove('show');
          statusDetails.style.display = 'none';
      }
      hideAllOngoingAnimations();
    }

    function hideAllOngoingAnimations() {
      for (let i = 1; i <= 3; i++) {
        const ongoingAnim = document.getElementById(`ongoing-animation-${i}`);
        if (ongoingAnim) {
            ongoingAnim.classList.remove('show');
        }
      }
    }

    function showOngoingAnimation(stepNumber) {
      hideAllOngoingAnimations();
      const ongoingAnim = document.getElementById(`ongoing-animation-${stepNumber}`);
      if (ongoingAnim) {
          ongoingAnim.classList.add('show');
      }
    }

    function updateStatusChart(status, details = {}, fileNames = [], approvalStatus) {
      resetStatusChart();
      const pendingMessage = document.getElementById('pending-status-message');
      const stepperContainer = document.getElementById('stepper-container');

      // If approval_status is "Pending", just show the pending message
      if (approvalStatus === 'Pending') {
        pendingMessage.style.display = 'block';
        stepperContainer.classList.add('d-none');
      } else {
        pendingMessage.style.display = 'none';
        stepperContainer.classList.remove('d-none');
      }

      const statusDetails = document.getElementById('status-details');
      const statusMessage = document.getElementById('status-message');
      const statusTimestamp = document.getElementById('status-timestamp');
      const fileNamesContainer = document.getElementById('file-names');
      const fileList = document.getElementById('file-list');

      if (statusMessage) {
          statusMessage.textContent = details.message || '';
      }
      if (statusTimestamp) {
          statusTimestamp.textContent = details.timestamp
              ? `Last updated: ${new Date(details.timestamp).toLocaleString()}`
              : '';
      }
      if (fileNamesContainer && fileList) {
          if (fileNames.length > 0) {
              fileList.innerHTML = '';
              fileNames.forEach(name => {
                  const li = document.createElement('li');
                  li.textContent = name;
                  fileList.appendChild(li);
              });
              fileNamesContainer.classList.remove('d-none');
          } else {
              fileNamesContainer.classList.add('d-none');
          }
      }
      if (statusDetails) {
          statusDetails.style.display = 'block';
          setTimeout(() => {
              statusDetails.classList.add('show');
          }, 50);
      }

      switch (status) {
          case 'Processing':
              setStepColor(1, 'blue', false, true);
              setStepColor(2, 'processing', true);
              setStepColor(3, 'gray');
              setStepColor(4, 'gray');
              showOngoingAnimation(2);
              break;
              case 'Approved':
              case 'Rejected':
              setStepColor(1, 'blue', false, true);
              setStepColor(2, approvalStatus === 'Approved' ? 'approved' : 'rejected', true);
              setStepColor(3, 'gray');
              setStepColor(4, 'gray');
              showOngoingAnimation(2);
        break;
          case 'Redirected':
              setStepColor(1, 'blue', false, true);
              setStepColor(2, 'processing', false, true);
              setStepColor(3, 'rejected', true);
              setStepColor(4, 'gray');
              break;
          case 'Released':
              setStepColor(1, 'blue', false, true);
              setStepColor(2, 'processing', false, true);
              setStepColor(3, 'approved', false, true);
              setStepColor(4, 'approved', true);
              break;
          default:
              // unknown status or "Submitted"? – handle accordingly
              break;
      }
    }

    function setStepColor(stepNumber, color, active = false, completed = false) {
      const stepElement = document.getElementById(`stage-${stepNumber}`);
      if (!stepElement) return;
      stepElement.classList.remove(
        'bg-blue-step','bg-submitted','bg-processing','bg-approved','bg-rejected',
        'active-step','completed-step'
      );

      switch(color) {
          case 'blue':
              stepElement.classList.add('bg-blue-step');
              break;
          case 'gray':
              stepElement.classList.add('bg-submitted');
              break;
          case 'processing':
              stepElement.classList.add('bg-processing');
              break;
          case 'approved':
              stepElement.classList.add('bg-approved');
              break;
          case 'rejected':
              stepElement.classList.add('bg-rejected');
              break;
          default:
              stepElement.classList.add('bg-submitted');
              break;
      }

      if (active) {
          stepElement.classList.add('active-step');
      }
      if (completed) {
          stepElement.classList.add('completed-step');
      }
    }

    /****************************************************
     * BASIC SCRIPT SANITIZATION
     ****************************************************/
    const sanitizeInputValue = (value) => {
        // Remove < and > to help prevent script tags
        return value.replace(/[<>]/g, '');
    };
    const sanitizableFields = document.querySelectorAll('.sanitize-input');
    sanitizableFields.forEach(field => {
        field.addEventListener('input', (e) => {
            e.target.value = sanitizeInputValue(e.target.value);
        });
    });
});
</script>

</body>
</html>
