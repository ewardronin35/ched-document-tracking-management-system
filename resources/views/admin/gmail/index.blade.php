@extends('layouts.app')

@section('title', 'MyChed-Email')

@push('styles')
<!-- Google Fonts & Bootstrap CSS -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- FilePond CSS and Plugins -->
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<!-- Optional: Animate.css for transitions -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Custom Styles -->
<style>
    /* Global Styles */
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f6f7f9;
      color: #202124;
      margin: 0;
      padding: 0;
      transition: background-color 0.3s, color 0.3s;
    }
    /* Dark Mode */
    body.dark-mode {
      background-color: #121212;
      color: #e0e0e0;
    }
    /* Top Navbar */
    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #e0e0e0;
      box-shadow: 0 1px 3px rgba(60,64,67,0.15);
      padding: 0.5rem 1rem;
      transition: background-color 0.3s, border-color 0.3s;
    }
    body.dark-mode .navbar {
      background-color: #1f1f1f;
      border-bottom-color: #333;
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: #202124;
    }
    /* Profile Dropdown in Navbar */
    .profile-picture {
      width: 32px;
      height: 32px;
      object-fit: cover;
      border: 2px solid #ddd;
    }
    body.dark-mode .profile-picture {
      border-color: #555;
    }
    /* Dark mode toggle button */
    #dark-mode-toggle {
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    /* Sidebar */
    .gmail-sidebar {
      background-color: #ffffff;
      border-right: 1px solid #e0e0e0;
      min-height: 100vh;
      padding: 20px 10px;
      transition: background-color 0.3s, border-color 0.3s;
    }
    body.dark-mode .gmail-sidebar {
      background-color: #1f1f1f;
      border-right-color: #333;
    }
    .gmail-sidebar .nav-link {
      color: #5f6368;
      font-size: 0.95rem;
      padding: 10px 15px;
      border-radius: 0 20px 20px 0;
      transition: background 0.3s, color 0.3s;
      display: block;
    }
    .gmail-sidebar .nav-link.active,
    .gmail-sidebar .nav-link:hover {
      background-color: #e8f0fe;
      color: #1967d2;
      font-weight: 500;
    }
    body.dark-mode .gmail-sidebar .nav-link.active,
    body.dark-mode .gmail-sidebar .nav-link:hover {
      background-color: #333;
      color: #90caf9;
    }
    .gmail-sidebar .section-title {
      font-size: 0.75rem;
      text-transform: uppercase;
      color: #80868b;
      margin: 15px 15px 5px;
    }
    /* Main Content Area */
    .email-container {
      background-color: #ffffff;
      border-radius: 4px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(60,64,67,0.15);
      margin-bottom: 20px;
      transition: background-color 0.3s;
    }
    body.dark-mode .email-container {
      background-color: #1e1e1e;
    }
    /* Email List (Inbox) with dark mode background fix */
    .email-list {
      max-height: 600px;
      overflow-y: auto;
      border-right: 1px solid #e0e0e0;
      background-color: #ffffff;
    }
    body.dark-mode .email-list {
      border-right-color: #333;
      background-color: #1e1e1e;
    }
    .email-item {
      padding: 15px;
      border-bottom: 1px solid #f1f3f4;
      cursor: pointer;
      transition: background 0.3s;
    }
    .email-item:hover,
    .email-item.active {
      background-color: #e8f0fe;
    }
    body.dark-mode .email-item:hover,
    body.dark-mode .email-item.active {
      background-color: #333;
    }
    .email-item h6 {
      margin: 0;
      font-size: 16px;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .email-item p {
      margin: 0;
      font-size: 14px;
      color: #5f6368;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .email-details {
      padding: 20px;
      min-height: 500px;
      overflow-y: auto;
    }
    /* Compose Modal (Floating at bottom right) */
    .compose-modal {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 400px;
      max-width: 90%;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.2);
      z-index: 1100;
      display: none;
      flex-direction: column;
      animation: fadeInUp 0.4s ease-out;
    }
    body.dark-mode .compose-modal {
      background-color: #2c2c2c;
    }
    @keyframes fadeInUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .compose-modal-header {
      padding: 10px 15px;
      background-color: #f1f3f4;
      border-bottom: 1px solid #e0e0e0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    body.dark-mode .compose-modal-header {
      background-color: #3a3a3a;
      border-bottom-color: #555;
    }
    .compose-modal-body {
      padding: 15px;
    }
    .compose-modal-close {
      cursor: pointer;
      font-size: 1.2rem;
      color: #5f6368;
    }
    /* Gmail-style Search Box */
    .search-box {
      max-width: 400px;
      position: relative;
    }
    .search-box input {
      border-radius: 50px;
      padding: 8px 20px;
      border: 1px solid #dfe1e5;
      width: 100%;
      transition: box-shadow 0.3s;
    }
    .search-box input:focus {
      box-shadow: 0 1px 6px rgba(32,33,36,0.28);
      border-color: #4285f4;
    }
    /* Responsive adjustments */
    @media (max-width: 991px) {
      .gmail-sidebar { min-height: auto; }
    }
</style>
@endpush

@section('content')
<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('admin.gmail.emails') }}">
      <i class="fa fa-envelope text-danger me-2"></i>MyChed-Email
    </a>
    <div class="d-flex align-items-center">
      <!-- Dark Mode Toggle -->
      <button id="dark-mode-toggle" class="btn btn-sm btn-outline-secondary me-2">
        <i class="fa fa-moon"></i>
      </button>
      <!-- Profile Dropdown -->
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="rounded-circle me-2" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" width="32" height="32">
          <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="{{ route('logout') }}">Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="container-fluid mt-3">
  <div class="row">
    <!-- Gmail-like Sidebar -->
    <div class="col-lg-2 gmail-sidebar">
      <div class="section-title">Primary</div>
      <ul class="nav flex-column" id="email-categories">
        <li class="nav-item">
          <a class="nav-link active" href="#" data-category="inbox">
            <i class="fa fa-inbox me-2"></i>Inbox
          </a>
        </li>
      </ul>
      <div class="section-title mt-4">More</div>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" href="#" data-category="sent">
            <i class="fa fa-paper-plane me-2"></i>Sent
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-category="drafts">
            <i class="fa fa-file-alt me-2"></i>Drafts
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-category="spam">
            <i class="fa fa-exclamation-triangle me-2"></i>Spam
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-category="trash">
            <i class="fa fa-trash me-2"></i>Trash
          </a>
        </li>
      </ul>
      <div class="mt-4">
          <a href="{{ route('admin.gmail.import') }}" class="btn btn-outline-secondary w-100">
            <i class="fa fa-upload me-2"></i> Import Emails
          </a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-10">
      <!-- Toolbar with Search and Compose -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 id="current-category-title">Inbox</h4>
        <div class="d-flex align-items-center">
          <div class="search-box me-3">
            <input type="text" id="email-search" placeholder="Search mail">
          </div>
          <button class="btn btn-primary" id="compose-btn">
            <i class="fa fa-plus me-1"></i> Compose
          </button>
        </div>
      </div>

      <div class="row">
        <!-- Email List (10 per page) -->
        <div class="col-md-4">
          <div class="list-group email-list" id="email-list">
            <p class="text-center text-muted">Loading emails…</p>
          </div>
          <!-- Pagination Controls -->
          <nav aria-label="Email pagination" class="mt-3">
            <ul class="pagination justify-content-center" id="pagination">
              <!-- Pagination items will be generated dynamically -->
            </ul>
          </nav>
        </div>

        <!-- Email Details -->
        <div class="col-md-8">
          <div class="email-details" id="email-details">
            <p class="text-center text-muted">Select an email to view its details.</p>
          </div>
        </div>
      </div><!-- /.row -->
    </div>
  </div>
</div>

<!-- Compose Modal (Floating at bottom right) -->
<div class="compose-modal" id="compose-modal">
  <div class="compose-modal-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">New Message</h6>
    <span class="compose-modal-close" id="compose-modal-close">&times;</span>
  </div>
  <div class="compose-modal-body">
    <form action="{{ route('admin.sendEmail') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-2">
        <!-- Added datalist for auto-complete suggestions -->
        <input type="email" name="to" id="compose-to" class="form-control" placeholder="To" required autocomplete="off" list="contactList">
        <datalist id="contactList">
          <!-- Options will be appended via AJAX -->
        </datalist>
      </div>
      <div class="mb-2">
        <input type="text" name="subject" id="compose-subject" class="form-control" placeholder="Subject" required>
      </div>
      <div class="mb-2">
        <textarea name="body" id="compose-body" class="form-control" rows="4" placeholder="Message" required></textarea>
      </div>
      <div class="mb-2">
        <!-- File uploads with FilePond -->
        <input type="file" name="attachments[]" id="compose-attachments" class="filepond" multiple>
        <small class="text-muted">Attach files (jpg, png, pdf, doc, etc.)</small>
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success me-2">
          <i class="fa fa-paper-plane me-1"></i> Send
        </button>
        <button type="button" class="btn btn-secondary" id="compose-cancel-btn">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<!-- jQuery, Bootstrap Bundle, and FilePond JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>

<script>
$(document).ready(function(){

  // Initialize FilePond
  FilePond.registerPlugin(
      FilePondPluginImagePreview,
      FilePondPluginFileValidateType,
      FilePondPluginFileValidateSize
  );
  FilePond.create(document.querySelector('input.filepond'), {
      acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword', 'application/vnd.ms-excel'],
      maxFileSize: '2MB'
  });

  // Dark mode toggle logic
  $('#dark-mode-toggle').click(function(){
    $('body').toggleClass('dark-mode');
    if($('body').hasClass('dark-mode')){
      localStorage.setItem('darkMode', 'true');
      $(this).html('<i class="fa fa-sun"></i>');
    } else {
      localStorage.setItem('darkMode', 'false');
      $(this).html('<i class="fa fa-moon"></i>');
    }
  });
  if(localStorage.getItem('darkMode') === 'true'){
    $('body').addClass('dark-mode');
    $('#dark-mode-toggle').html('<i class="fa fa-sun"></i>');
  }

  // Variables for AJAX email loading and pagination
  var currentCategory = 'inbox';
  var currentPage = 1;
  var totalPages = 1;
  var emailsPerPage = 10;

  // Load emails via AJAX
  function loadEmails(category, page = 1) {
    $.ajax({
      url: '{{ route("admin.gmail.getEmails") }}',
      type: 'GET',
      data: { category: category, page: page, per_page: emailsPerPage },
      success: function(response) {
        var emailList = $('#email-list');
        emailList.empty();
        if(response.data && response.data.length) {
          $.each(response.data, function(i, email){
            var item = `
              <a href="#" class="list-group-item list-group-item-action email-item" data-email-id="${email.id}">
                <h6 class="text-truncate">${email.subject}</h6>
                <p class="mb-0 text-truncate">${email.snippet}</p>
                <small class="text-muted">From: ${email.from}</small>
              </a>`;
            emailList.append(item);
          });
        } else {
          emailList.html('<p class="text-center text-muted">No emails found.</p>');
        }
        currentPage = response.current_page || 1;
        totalPages = response.last_page || 1;
        updatePagination(totalPages, currentPage);
      },
      error: function(xhr) {
        console.error('Error loading emails', xhr);
      }
    });
  }

  function updatePagination(total, current) {
    var pagination = $('#pagination');
    pagination.empty();
    if(total <= 1) return;
    var prevClass = current === 1 ? 'disabled' : '';
    pagination.append(`<li class="page-item ${prevClass}">
        <a class="page-link" href="#" data-page="${current - 1}">Previous</a>
    </li>`);
    for(var i = 1; i <= total; i++){
      var activeClass = i === current ? 'active' : '';
      pagination.append(`<li class="page-item ${activeClass}">
          <a class="page-link" href="#" data-page="${i}">${i}</a>
      </li>`);
    }
    var nextClass = current === total ? 'disabled' : '';
    pagination.append(`<li class="page-item ${nextClass}">
        <a class="page-link" href="#" data-page="${current + 1}">Next</a>
    </li>`);
  }

  // Initially load emails
  loadEmails(currentCategory);

  // Category click handler
  $('a.nav-link[data-category]').click(function(e){
    e.preventDefault();
    $('a.nav-link[data-category]').removeClass('active');
    $(this).addClass('active');
    currentCategory = $(this).data('category');
    $('#current-category-title').text(currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1));
    loadEmails(currentCategory);
    $('#email-details').html('<p class="text-center text-muted">Select an email to view its details.</p>');
  });

  // Pagination click handler
  $('#pagination').on('click', '.page-link', function(e){
    e.preventDefault();
    var page = $(this).data('page');
    if(page < 1 || page > totalPages) return;
    currentPage = page;
    loadEmails(currentCategory, currentPage);
  });

  // Email item click handler
  $('#email-list').on('click', '.email-item', function(e){
    e.preventDefault();
    var emailId = $(this).data('email-id');
    $('.email-item').removeClass('active');
    $(this).addClass('active');
    $.ajax({
      url: '{{ route("admin.gmail.getEmailDetails") }}',
      type: 'GET',
      data: { id: emailId },
      success: function(response){
        var content = `<h5>${response.subject}</h5>
                       <p><strong>From:</strong> ${response.from}</p>
                       <p><strong>Date:</strong> ${response.date}</p>
                       <hr>
                       <div>${response.bodyHtml ? response.bodyHtml : response.bodyText}</div>`;
        if(response.attachments && response.attachments.length){
          content += `<hr><h6>Attachments:</h6>`;
          $.each(response.attachments, function(i, att){
            if(att.isImage && att.url){
              content += `<img src="${att.url}" alt="${att.filename}" class="img-thumbnail me-2 mb-2" style="max-width:200px;">`;
            } else if(att.url){
              content += `<a href="${att.url}" target="_blank" download>${att.filename}</a><br>`;
            }
          });
        }
        $('#email-details').html(content);
      },
      error: function(xhr){
        console.error('Error fetching email details', xhr);
      }
    });
  });

  // Gmail-like search filtering on the email list
  $('#email-search').on('input', function(){
    var query = $(this).val().toLowerCase();
    $('#email-list .email-item').each(function(){
      var subject = $(this).find('h6').text().toLowerCase();
      var snippet = $(this).find('p').text().toLowerCase();
      $(this).toggle(subject.includes(query) || snippet.includes(query));
    });
  });

  // Compose Modal Toggle
  $('#compose-btn').click(function(){
    $('#compose-modal').fadeIn(300);
  });
  $('#compose-modal-close, #compose-cancel-btn').click(function(){
    $('#compose-modal').fadeOut(300);
  });

  // Autofill for Compose "To:" field using AJAX and datalist
  $('#compose-to').on('input', function(){
    var query = $(this).val();
    if(query.length >= 2) { // trigger after at least 2 characters
      $.ajax({
        url: '{{ route("admin.gmail.getContacts") }}', // Implement this endpoint to return contacts as JSON: [{name, email}, ...]
        type: 'GET',
        data: { query: query },
        success: function(response){
          var dataList = $('#contactList');
          dataList.empty();
          $.each(response, function(i, contact){
            dataList.append(`<option value="${contact.email}">${contact.name}</option>`);
          });
        },
        error: function(xhr){
          console.error('Error fetching contacts', xhr);
        }
      });
    }
  });
});
</script>
@endpush
