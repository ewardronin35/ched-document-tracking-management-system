<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CDTMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">

    <!-- Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- FilePond Image Preview Plugin CSS -->
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        /* Navbar Gradient Background */
        .navbar-gradient {
            background: linear-gradient(to right, #4f46e5, #3b82f6);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            background: url('{{ asset('images/Hero.png') }}') no-repeat center center;
            background-size: cover;
            color: #ffffff;
            padding: 100px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(59, 130, 246, 0.6); /* Semi-transparent blue overlay */
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 700px;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .hero-section button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .hero-section button {
            transition: all 0.3s ease;
        }

        /* Feature Circles Section */
        .feature-section {
            padding: 60px 0;
            text-align: center;
            transition: opacity 0.5s ease;
        }
        .feature-section.hide {
            opacity: 0;
            pointer-events: none;
        }
        .feature {
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        .feature:hover {
            transform: translateY(-3px);
        }
        .circle-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #3b82f6;
            margin-bottom: 15px;
        }
        .circle-icon svg {
            fill: #fff;
            width: 24px;
            height: 24px;
        }
        .feature-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .feature-section p {
            font-size: 1rem;
            color: #6b7280;
            max-width: 300px;
            margin: 0 auto;
        }

        /* Stepper Styles (For Track Document Section) */
        .stepper {
            display: flex;
            align-items: center;
            position: relative;
            margin-top: 30px;
        }
        .stepper::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #d1d5db;
            transform: translateY(-50%);
            z-index: 0;
        }
        .step {
            position: relative;
            z-index: 1;
            text-align: center;
            flex: 0 0 25%;
        }
        .step-number {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 9999px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            opacity: 1;
            transform: scale(1);
            transition: background-color 0.3s ease, transform 0.3s ease, opacity 0.3s ease;
            position: relative;
            top: 10px; /* Move circles down */
        }
        .step-title {
            margin-top: 10px;
            font-size: 1rem;
            color: #111827;
        }

        /* Colors for steps */
        .bg-submitted { background-color: #9ca3af; } /* Gray */
        .bg-processing { background-color: #facc15; } /* Yellow */
        .bg-approved { background-color: #16a34a; } /* Green */
        .bg-rejected { background-color: #dc2626; } /* Red */
        /* Add a custom blue for 'Submitted' active step */
        .bg-blue-step { background-color: #3b82f6; } /* Blue for first step when submitted */

        .active-step {
            animation: pulse 1s infinite alternate;
        }

        @keyframes pulse {
            0% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .completed-step {
            opacity: 0.8;
        }

        .shadow-container {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            border-radius: 0.5rem;
        }

        .filepond-wrapper {
            max-width: 100%;
            width: 100%;
        }

        .filepond {
            width: 100%;
        }

        /* Footer */
        .footer {
            background-color: #f8f9fa;
            padding: 1.5rem 0;
            text-align: center;
            margin-top: 2rem;
        }

        /* Smooth fade-in for hidden sections */
        #upload-section-container, #track-section-container {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        #upload-section-container.show, #track-section-container.show {
            opacity: 1;
        }

        /* Status details animation */
        #status-details {
            transition: all 0.5s ease;
            transform: translateY(20px);
            opacity: 0;
        }
        #status-details.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Ongoing Animation Styles */
        .ongoing-animation {
            display: none;
            flex: 1;
            height: 2px;
            position: relative;
            background-color: transparent;
            margin: 0 10px;
            z-index: 1;
        }
        .ongoing-animation.show {
            display: block;
        }
        .ongoing-animation span {
            position: absolute;
            left: 0;
            width: 100%;
            text-align: center;
            color: rgb(196, 245, 19); /* Yellow */
            font-weight: bold;
            font-size: 1rem;
            white-space: nowrap;
            animation: slide 4s linear infinite;
        }
        @keyframes slide {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .stepper {
                flex-direction: column;
                align-items: center;
            }
            .stepper::before {
                display: none;
            }
            .step {
                width: 100%;
                margin-bottom: 20px;
            }
            .hero-section h1 {
                font-size: 2rem;
            }
            .stepper .processing-label {
                display: none;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-gradient">
        <div class="container">
            <a class="navbar-brand text-white d-flex align-items-center" href="#home">
                <img src="{{ asset('images/Logo.png') }}" alt="CDTMS Logo" class="h-8 w-auto me-2">
                <img src="{{ asset('images/Logo2.png') }}" alt="CHED Logo" class="h-8 w-auto me-2">
                <span class="fw-bold">CDTMS</span>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section relative" id="home">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start hero-content">
                    <h1>Welcome to CDTMS</h1>
                    <p class="mb-4">A comprehensive toolkit for managing and tracking your documents efficiently. Seamlessly upload your files and monitor their status through every stage.</p>
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        <button id="show-upload" class="btn btn-lg btn-light me-2 mb-2 mb-lg-0">Upload Document</button>
                        <button id="show-track" class="btn btn-lg btn-outline-light ms-lg-2">Track Document</button>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Feature Circles Section -->
    <section class="feature-section" id="feature-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4 feature">
                    <div class="circle-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path d="M11 17h2v-6h3l-4-4-4 4h3v6zm-7 2h14v2h-14z"></path></svg>
                    </div>
                    <h3>Upload Documents</h3>
                    <p>Effortlessly upload PDF, DOC, and DOCX files.</p>
                </div>

                <div class="col-md-4 feature">
                    <div class="circle-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path d="M11 2v2c-3.859.026-7 3.167-7 7s3.141 6.974 7 7v2l3-3-3-3v2c-2.757-.026-5-2.269-5-5s2.243-4.974 5-5v2l3-3-3-3zm5 5c0-.552-.447-1-1-1h-4v2h4c.553 0 1-.448 1-1zm-1 6h-4v2h4c.553 0 1-.448 1-1s-.447-1-1-1z"/></svg>
                    </div>
                    <h3>Track Status</h3>
                    <p>Monitor documents through submission, processing, and approval stages.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden Upload Document Section -->
    <div class="container my-8" id="upload-section-container" style="display:none;">
        <section class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 shadow-container mt-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Upload a Document</h2>

            @if($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data" class="space-y-6" id="upload-form">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600 sm:text-sm"
                        placeholder="you@example.com">
                </div>

                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="full_name" id="full_name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600 sm:text-sm"
                        placeholder="John Doe">
                </div>

                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700">Document Type</label>
                    <select name="document_type" id="document_type" required
                        class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-blue-600 sm:text-sm">
                        <option value="" selected disabled>Select Document Type</option>
                        <option value="CAV">CAV</option>
                        <option value="SO">SO</option>
                        <option value="IP">IP</option>
                        <option value="GR">GR</option>
                        <option value="COPC">COPC</option>
                    </select>
                </div>

                <div class="shadow-container p-4 rounded-md">
                    <label for="document" class="block text-sm font-medium text-gray-700">Upload Document</label>
                    <div class="filepond-wrapper mt-2">
                        <input type="file" name="document[]" id="document" class="filepond" multiple required />
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Allowed: PDF, DOC, DOCX<br>
                        Use correct extensions (<code>sample.pdf</code>, not <code>sample.pdf.pdf</code>).<br>
                        Files show a green check once processed.
                    </p>
                </div>

                <div class="flex items-center">
                    <input id="agree_terms" name="agree_terms" type="checkbox" required
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="agree_terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the <a href="#" id="view-terms" class="text-blue-600 hover:underline">terms and conditions</a>.
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        Submit Document
                    </button>
                </div>
            </form>
        </section>
    </div>

    <!-- Hidden Track Document Section -->
    <div class="container" id="track-section-container" style="display:none;">
        <section class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 shadow-container mt-12 mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Track Your Document</h2>

            <form method="GET" action="{{ route('documents.track') }}" class="space-y-6" id="track-form">
                @csrf
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700">Tracking Number</label>
                    <input type="text" name="tracking_number" id="tracking_number" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600 sm:text-sm"
                        placeholder="Enter your Tracking Number">
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                        Track Document
                    </button>
                </div>
            </form>

            <div class="mt-8">
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
                        <div class="step-title">Processing</div>
                    </div>
                    <div class="ongoing-animation" id="ongoing-animation-2">
                        <span>Ongoing</span>
                    </div>

                    <!-- Step 3: Approved/Rejected -->
                    <div class="step" id="stage-3-container">
                        <div class="step-number bg-submitted" id="stage-3">3</div>
                        <div class="step-title">Approved/Rejected</div>
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

                <!-- Status Details -->
                <div class="mt-6" id="status-details" style="display: none;">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Status Details</h3>
                    <p class="mt-2 text-gray-600" id="status-message"></p>
                    <p class="mt-1 text-gray-500" id="status-timestamp"></p>
                    <div class="mt-4" id="file-names" style="display:none;">
                        <h4 class="text-lg font-semibold text-gray-800">File(s) Associated:</h4>
                        <ul class="list-disc list-inside text-gray-700" id="file-list"></ul>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Terms and Conditions Modal -->
    <div id="terms-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Terms and Conditions</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        By uploading a document, you agree to our terms and conditions. Ensure that all documents are accurate and comply with our guidelines.
                    </p>
                </div>
            </div>
            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="agree-button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Agree
                </button>
                <button type="button" id="disagree-button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Disagree
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="text-gray-600">
                &copy; {{ date('Y') }} CDTMS. All rights reserved.
            </p>
            <p class="text-gray-600">
                <strong>Address:</strong> W375+PGP, Baliwasan Chico Road, Zamboanga, Zamboanga del Sur<br>
                <strong>Hours:</strong> Open ⋅ Closes 5 PM<br>
                <strong>Phone:</strong> (062) 991 7084
            </p>
        </div>
    </footer>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <!-- FilePond Plugins JS -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

    <!-- Bootstrap 5 JS (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alpine.js (Optional) -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const featureSection = document.getElementById('feature-section');

            // Register FilePond plugins
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview
            );

            const pond = FilePond.create(document.querySelector('input.filepond'), {
                allowMultiple: true,
                allowReorder: true,
                maxFiles: 10,
                acceptedFileTypes: ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
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

            const uploadSectionContainer = document.getElementById('upload-section-container');
            const trackSectionContainer = document.getElementById('track-section-container');

            const showUploadBtn = document.getElementById('show-upload');
            const showTrackBtn = document.getElementById('show-track');

            showUploadBtn.addEventListener('click', () => {
                featureSection.classList.add('hide');
                setTimeout(() => {
                    featureSection.style.display = 'none';
                }, 500);

                uploadSectionContainer.style.display = 'block';
                trackSectionContainer.style.display = 'none';
                uploadSectionContainer.classList.add('show');
                trackSectionContainer.classList.remove('show');
                uploadSectionContainer.scrollIntoView({ behavior: 'smooth' });
            });

            showTrackBtn.addEventListener('click', () => {
                featureSection.classList.add('hide');
                setTimeout(() => {
                    featureSection.style.display = 'none';
                }, 500);

                trackSectionContainer.style.display = 'block';
                uploadSectionContainer.style.display = 'none';
                trackSectionContainer.classList.add('show');
                uploadSectionContainer.classList.remove('show');
                trackSectionContainer.scrollIntoView({ behavior: 'smooth' });
            });

            // Terms Modal
            const termsModal = document.getElementById('terms-modal');
            const viewTerms = document.getElementById('view-terms');
            const agreeButton = document.getElementById('agree-button');
            const disagreeButton = document.getElementById('disagree-button');

            viewTerms.addEventListener('click', function(e) {
                e.preventDefault();
                termsModal.classList.remove('hidden');
            });

            agreeButton.addEventListener('click', function() {
                termsModal.classList.add('hidden');
                document.getElementById('agree_terms').checked = true;
                Swal.fire('Thank You!', 'You have agreed to the terms and conditions.', 'success');
            });

            disagreeButton.addEventListener('click', function() {
                termsModal.classList.add('hidden');
                document.getElementById('agree_terms').checked = false;
                Swal.fire('Agreement Required', 'You must agree to the terms and conditions to upload documents.', 'warning');
            });

            const uploadForm = document.getElementById('upload-form');
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const termsAgreed = document.getElementById('agree_terms').checked;
                if (!termsAgreed) {
                    Swal.fire('Terms Not Agreed', 'Please agree to the terms and conditions before uploading.', 'warning');
                    return;
                }
                Swal.fire({
                    title: 'Uploading...',
                    html: 'Please wait while your document(s) are being uploaded.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData(uploadForm);
                pond.getFiles().forEach(fileItem => {
                    formData.append('document[]', fileItem.file, fileItem.file.name);
                });

                fetch(uploadForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    Swal.close();
                    if (status === 200 && body.success) {
                        let htmlContent = '<div style="text-align: left;">';
                        body.documents.forEach(doc => {
                            htmlContent += `
                                <div style="margin-bottom: 10px;">
                                    <strong>Document ID:</strong> ${doc.document_id}<br>
                                    <strong>Tracking Number:</strong> ${doc.tracking_number}
                                    <button class="copy-btn" data-clipboard-text="${doc.tracking_number}" style="margin-left: 10px; padding: 2px 6px; font-size: 0.8rem; cursor: pointer;">
                                        Copy
                                    </button>
                                </div>
                            `;
                        });
                        htmlContent += '</div>';

                        Swal.fire({
                            title: 'Success!',
                            html: `
                                <p>Your document(s) have been uploaded successfully.</p>
                                ${htmlContent}
                            `,
                            icon: 'success',
                            showConfirmButton: true,
                            didOpen: () => {
                                const copyButtons = Swal.getHtmlContainer().querySelectorAll('.copy-btn');
                                copyButtons.forEach(button => {
                                    button.addEventListener('click', () => {
                                        const textToCopy = button.getAttribute('data-clipboard-text');
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
                                });
                            }
                        }).then(() => {
                            uploadForm.reset();
                            pond.removeFiles();
                            resetStatusChart();
                        });
                    } else if (status === 422) {
                        // Display validation errors
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
                        Swal.fire('Error!', body.message || 'There was an error uploading your document(s).', 'error');
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire('Error!', 'There was an unexpected error.', 'error');
                    console.error('Upload Error:', error);
                });
            });

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

                fetch(trackForm.action + '?tracking_number=' + encodeURIComponent(trackingNumber), {
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
                        updateStatusChart(body.status, body.details, body.file_names || []);
                        Swal.fire({
                            title: 'Tracking Successful!',
                            html: `
                                <p>Status for Tracking Number <strong>${trackingNumber}</strong>: <strong>${body.status}</strong></p>
                            
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
                        // Display validation errors
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

            // Utility Functions

            function resetStatusChart() {
                setStepColor(1, 'gray');
                setStepColor(2, 'gray');
                setStepColor(3, 'gray');
                setStepColor(4, 'gray');
                document.getElementById('status-details').style.display = 'none';
                document.getElementById('status-details').classList.remove('show');
                // Hide all ongoing animations
                hideAllOngoingAnimations();
            }

            function hideAllOngoingAnimations() {
                for (let i = 1; i <=3; i++) {
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

            function updateStatusChart(status, details, fileNames) {
                resetStatusChart();
                // Show status details
                const statusDetails = document.getElementById('status-details');
                const statusMessage = document.getElementById('status-message');
                const statusTimestamp = document.getElementById('status-timestamp');
                const fileNamesContainer = document.getElementById('file-names');
                const fileList = document.getElementById('file-list');

                statusMessage.textContent = details && details.message ? details.message : '';
                statusTimestamp.textContent = details && details.timestamp ? `Last updated: ${new Date(details.timestamp).toLocaleString()}` : '';

                // Display file names if provided
                if (fileNames && fileNames.length > 0) {
                    fileList.innerHTML = '';
                    fileNames.forEach(name => {
                        const li = document.createElement('li');
                        li.textContent = name;
                        fileList.appendChild(li);
                    });
                    fileNamesContainer.style.display = 'block';
                } else {
                    fileNamesContainer.style.display = 'none';
                }

                statusDetails.style.display = 'block';
                setTimeout(() => {
                    statusDetails.classList.add('show');
                }, 50); // Slight delay for transition

                switch(status) {
                    case 'Submitted':
                        // Step 1 active, show ongoing-animation-1
                        setStepColor(1, 'blue', true);
                        setStepColor(2, 'gray');
                        setStepColor(3, 'gray');
                        setStepColor(4, 'gray');
                        showOngoingAnimation(1);
                        break;
                    case 'Processing':
                        // Step 1 completed, Step 2 active, show ongoing-animation-2
                        setStepColor(1, 'blue', false, true);
                        setStepColor(2, 'processing', true);
                        setStepColor(3, 'gray');
                        setStepColor(4, 'gray');
                        showOngoingAnimation(2);
                        break;
                    case 'Approved':
                        // Steps 1 & 2 completed, Step 3 active
                        setStepColor(1, 'blue', false, true);
                        setStepColor(2, 'processing', false, true);
                        setStepColor(3, 'approved', true);
                        setStepColor(4, 'gray');
                        // No ongoing animation needed
                        break;
                    case 'Rejected':
                        // Steps 1 & 2 completed, Step 3 active (rejected)
                        setStepColor(1, 'blue', false, true);
                        setStepColor(2, 'processing', false, true);
                        setStepColor(3, 'rejected', true);
                        setStepColor(4, 'gray');
                        // No ongoing animation needed
                        break;
                    case 'Released':
                        // All steps completed
                        setStepColor(1, 'blue', false, true);
                        setStepColor(2, 'processing', false, true);
                        setStepColor(3, 'approved', false, true);
                        setStepColor(4, 'approved', true);
                        // No ongoing animation needed
                        break;
                    default:
                        Swal.fire('Error!', 'Unknown status received.', 'error');
                        break;
                }
            }

            function setStepColor(stepNumber, color, active = false, completed = false) {
                const stepElement = document.getElementById(`stage-${stepNumber}`);
                if (!stepElement) return;

                // Remove all possible color classes
                stepElement.classList.remove('bg-blue-step', 'bg-submitted', 'bg-processing', 'bg-approved', 'bg-rejected');

                // Assign new color based on status
                switch(color) {
                    case 'blue':
                        stepElement.classList.add('bg-blue-step'); // Blue for active step
                        break;
                    case 'gray':
                        stepElement.classList.add('bg-submitted'); // Gray for inactive
                        break;
                    case 'processing':
                        stepElement.classList.add('bg-processing'); // Yellow for processing
                        break;
                    case 'approved':
                        stepElement.classList.add('bg-approved'); // Green for approved
                        break;
                    case 'rejected':
                        stepElement.classList.add('bg-rejected'); // Red for rejected
                        break;
                    default:
                        stepElement.classList.add('bg-submitted');
                        break;
                }

                // Add active or completed classes
                if (active) {
                    stepElement.classList.add('active-step');
                }
                if (completed) {
                    stepElement.classList.add('completed-step');
                }
            }
        });
    </script>

</body>
</html>
