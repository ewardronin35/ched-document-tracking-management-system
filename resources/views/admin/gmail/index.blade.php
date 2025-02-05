@extends('layouts.app')

@section('title', 'Manage Emails')

@push('styles')
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f1f3f4;
            color: #202124;
            font-family: 'Montserrat', sans-serif;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .sidebar {
            height: calc(100vh - 56px); /* Full height minus navbar */
            overflow-y: auto;
            background-color: #ffffff;
            border-right: 1px solid #ddd;
            padding: 20px;
        }

        .sidebar .nav-link.active {
            background-color: #e0ecff;
            font-weight: 500;
        }

        .email-list {
            height: calc(100vh - 136px); /* Adjust based on padding and navbar */
            overflow-y: auto;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .email-preview {
            cursor: pointer;
        }

        .email-preview:hover {
            background-color: #f1f3f4;
        }

        .email-content {
            height: calc(100vh - 80px); /* Adjust based on padding and navbar */
            overflow-y: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .compose-email {
            height: calc(100vh - 560px); /* Adjust as needed */
            overflow-y: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                height: auto;
            }
            .email-list {
                height: auto;
            }
            .email-content, .compose-email {
                height: auto;
            }
        }

    </style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.gmail.emails') }}">
          <i class="fa fa-envelope text-danger me-2"></i>MyChed-Email
        </a>
        <span class="navbar-text d-none d-lg-inline">
          Logged in as: <strong>{{ session('email_user') ?? 'Unknown User' }}</strong>
        </span>
        <div class="ms-auto">
        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger">Log Out</a>
        </div>
      </div>
    </nav>

    <div class="row g-0">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-white border-end" style="min-height: 100vh;">
        <h5 class="fw-bold">Categories</h5>
            <ul class="nav nav-pills flex-column mb-3" id="email-categories">
                <li class="nav-item">
                    <a class="nav-link active" data-category="inbox" href="#">
                        <i class="fa fa-inbox me-2"></i> Inbox
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="sent" href="#">
                        <i class="fa fa-paper-plane me-2"></i> Sent
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="drafts" href="#">
                        <i class="fa fa-file-alt me-2"></i> Drafts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="spam" href="#">
                        <i class="fa fa-exclamation-triangle me-2"></i> Spam
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-category="trash" href="#">
                        <i class="fa fa-trash me-2"></i> Trash
                    </a>
                </li>
            </ul>

            <h5 class="fw-bold">Emails</h5>
            <!-- Email Previews List -->
            <div class="list-group email-list" id="email-previews">
                <!-- Email previews will be loaded here via AJAX -->
            </div>

            <!-- Pagination Controls -->
            <nav aria-label="Email list pagination">
                <ul class="pagination justify-content-center" id="email-pagination">
                    <!-- Pagination items will be generated dynamically -->
                </ul>
            </nav>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 px-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 id="current-category-title">Inbox</h4>
                <button class="btn btn-primary" id="show-compose-btn">
                    <i class="fa fa-plus me-1"></i> Compose
                </button>
            </div>

            <!-- Email Content Display -->
            <div class="email-content" id="email-content">
                <p class="text-center text-muted">Select an email to view its content.</p>
            </div>

            <!-- Compose Email Form -->
            <div class="compose-email d-none" id="compose-email-form">
                <h5>New Message</h5>
                <form action="{{ route('admin.sendEmail') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- "To" Field -->
                    <div class="mb-3 position-relative">
                        <label for="compose-to" class="form-label fw-bold">To</label>
                        <input type="email" class="form-control" id="compose-to" name="to" required 
                               placeholder="Recipient's email" autocomplete="off">
                        <div id="compose-to-suggestions" class="list-group position-absolute w-100" 
                             style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                    <!-- "Subject" Field -->
                    <div class="mb-3">
                        <label for="compose-subject" class="form-label fw-bold">Subject</label>
                        <input type="text" class="form-control" id="compose-subject" name="subject" 
                               required placeholder="Email subject here">
                    </div>
                    <!-- "Body" Field -->
                    <div class="mb-3">
                        <label for="compose-body" class="form-label fw-bold">Message</label>
                        <textarea class="form-control" id="compose-body" name="body" rows="6" required>
Hello,

This is from CHED. Please find the attached documents if any.

Regards,
CHED Team
                        </textarea>
                    </div>
                    <!-- Attachments -->
                    <div class="mb-3">
                        <label for="compose-attachments" class="form-label fw-bold">Attachments</label>
                        <input type="file" class="form-control" id="compose-attachments" name="attachments[]" multiple>
                        <small class="text-muted">You can add multiple files.</small>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fa fa-paper-plane me-1"></i> Send
                        </button>
                        <button type="button" class="btn btn-secondary" id="cancel-compose-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Bootstrap 5 Scripts (Popper + Bootstrap) -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom Scripts -->
    <script>
        $(document).ready(function(){
            var currentCategory = 'inbox';
            var currentPage = 1;
            var totalPages = 1;

            // Function to load email previews
            function loadEmailPreviews(category, page=1){
                $.ajax({
                    url: '{{ route("admin.gmail.getEmails") }}',
                    type: 'GET',
                    data: {
                        category: category,
                        page: page
                    },
                    success: function(response){
                        // Update the email list
                        var emailList = $('#email-previews');
                        emailList.empty();

                        if(response.data.length > 0){
                            response.data.forEach(function(email){
                                var emailItem = `
                                    <a href="#" class="list-group-item list-group-item-action email-preview" data-email-id="${email.id}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1 text-truncate">${email.subject}</h5>
                                            <small class="text-muted">${email.date}</small>
                                        </div>
                                        <p class="mb-1 text-truncate">${email.snippet}</p>
                                        <small class="text-muted">From: ${email.from}</small>
                                    </a>
                                `;
                                emailList.append(emailItem);
                            });
                        } else {
                            emailList.append('<p class="text-center text-muted">No emails found.</p>');
                        }

                        // Update pagination
                        currentPage = response.current_page;
                        totalPages = response.last_page;
                        updatePagination(totalPages, currentPage);
                    },
                    error: function(xhr){
                        console.error('Error fetching emails:', xhr);
                        alert('An error occurred while fetching emails.');
                    }
                });
            }

            // Function to update pagination controls
            function updatePagination(total, current){
                var pagination = $('#email-pagination');
                pagination.empty();

                if(total <=1 ){
                    return;
                }

                // Previous button
                var prevClass = current ===1 ? 'disabled' : '';
                pagination.append(`<li class="page-item ${prevClass}">
                    <a class="page-link" href="#" data-page="${current-1}">Previous</a>
                </li>`);

                // Page numbers
                for(var i=1; i<=total; i++){
                    var activeClass = i === current ? 'active' : '';
                    pagination.append(`<li class="page-item ${activeClass}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`);
                }

                // Next button
                var nextClass = current === total ? 'disabled' : '';
                pagination.append(`<li class="page-item ${nextClass}">
                    <a class="page-link" href="#" data-page="${current+1}">Next</a>
                </li>`);
            }

            // Initial load
            loadEmailPreviews(currentCategory, currentPage);

            // Handle category click
            $('#email-categories .nav-link').on('click', function(e){
                e.preventDefault();
                $('#email-categories .nav-link').removeClass('active');
                $(this).addClass('active');
                currentCategory = $(this).data('category');
                currentPage =1;
                $('#current-category-title').text(capitalizeFirstLetter(currentCategory));
                loadEmailPreviews(currentCategory, currentPage);
                // Clear email content
                $('#email-content').html('<p class="text-center text-muted">Select an email to view its content.</p>');
            });

            // Handle pagination click
            $('#email-pagination').on('click', '.page-link', function(e){
                e.preventDefault();
                var page = $(this).data('page');
                if(page <1 || page > totalPages){
                    return;
                }
                currentPage = page;
                loadEmailPreviews(currentCategory, currentPage);
            });

            // Handle email preview click
            $('#email-previews').on('click', '.email-preview', function(e){
                e.preventDefault();
                var emailId = $(this).data('email-id');

                // Highlight the selected email
                $('.email-preview').removeClass('active');
                $(this).addClass('active');

                // Fetch and display email content
                fetchEmailContent(emailId);
            });

            // Function to fetch email content
            function fetchEmailContent(emailId){
                $.ajax({
                    url: '{{ route("admin.gmail.getEmailDetails") }}',
                    type: 'GET',
                    data: {
                        id: emailId
                    },
                    success: function(response){
                        // Display email content
                        var emailContent = `
                            <h5>${response.subject}</h5>
                            <p><strong>From:</strong> ${response.from}</p>
                            <p><strong>Date:</strong> ${response.date}</p>
                            <hr>
                            <div>${response.bodyHtml ? response.bodyHtml : response.body}</div>
                        `;

                        // Handle attachments
                        if(response.attachments && response.attachments.length >0){
                            var attachmentsHtml = '<hr><h6>Attachments:</h6>';
                            response.attachments.forEach(function(att){
                                if(att.isImage && att.url){
                                    attachmentsHtml += `<img src="${att.url}" alt="${att.filename}" class="img-thumbnail me-2 mb-2" style="max-width: 200px;">`;
                                } else if(att.url){
                                    attachmentsHtml += `<a href="${att.url}" target="_blank" class="me-2 mb-2">${att.filename}</a><br>`;
                                }
                            });
                            emailContent += attachmentsHtml;
                        }

                        $('#email-content').html(emailContent);
                    },
                    error: function(xhr){
                        console.error('Error fetching email content:', xhr);
                        alert('An error occurred while fetching email content.');
                    }
                });
            }

            // Compose email form toggle
            $('#show-compose-btn').on('click', function(){
                $('#compose-email-form').removeClass('d-none');
                $('#email-content').addClass('d-none');
                $(this).addClass('d-none');
            });

            // Cancel compose email
            $('#cancel-compose-btn').on('click', function(){
                $('#compose-email-form').addClass('d-none');
                $('#email-content').removeClass('d-none');
                $('#show-compose-btn').removeClass('d-none');
            });

            // Helper function to capitalize first letter
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>
@endpush
