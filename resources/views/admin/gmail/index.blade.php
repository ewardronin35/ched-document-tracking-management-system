@extends('layouts.app')

@section('title', 'MyChed-Email')

@push('styles')
<!-- Google Fonts, Bootstrap, and Font Awesome -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- FilePond CSS and Plugins -->
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<!-- Animate.css for transitions -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Custom Gmail-Inspired Styles -->
<style>
  /* Global & Typography */
  body {
    font-family: 'Roboto', sans-serif;
    background-color: #f1f3f4;
    color: #202124;
    margin: 0;
    padding: 0;
  }
  body.dark-mode {
    background-color: #202124;
    color: #e8eaed;
  }
  /* Top Navbar */
  .top-navbar {
    background-color: #fff;
    border-bottom: 1px solid #e0e0e0;
    box-shadow: 0 1px 3px rgba(60,64,67,0.15);
    padding: 0.5rem 1rem;
  }
  .top-navbar .navbar-brand {
    font-weight: 500;
    font-size: 1.4rem;
    color: #202124;
  }
  .top-navbar .search-box {
    max-width: 400px;
    position: relative;
  }
  .top-navbar .search-box input {
    width: 100%;
    padding: 8px 16px;
    border: 1px solid #dfe1e5;
    border-radius: 24px;
  }
  .top-navbar .search-box .fa-search {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #5f6368;
  }
  .top-navbar .profile-dropdown img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #ddd;
  }
  body.dark-mode .top-navbar {
    background-color: #303134;
    border-bottom: 1px solid #5f6368;
  }
  body.dark-mode .top-navbar .navbar-brand {
    color: #e8eaed;
  }
  /* Sidebar */
  .gmail-sidebar {
    background-color: #fff;
    border-right: 1px solid #e0e0e0;
    min-height: 100vh;
    padding: 20px 10px;
  }
  .gmail-sidebar .section-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #5f6368;
    margin: 15px 0 5px 15px;
  }
  .gmail-sidebar .nav-link {
    display: block;
    color: #202124;
    font-size: 0.95rem;
    padding: 10px 15px;
    border-radius: 0 20px 20px 0;
    transition: background 0.3s;
  }
  .gmail-sidebar .nav-link:hover,
  .gmail-sidebar .nav-link.active {
    background-color: #e8f0fe;
    color: #1967d2;
    font-weight: 500;
  }
  body.dark-mode .gmail-sidebar {
    background-color: #303134;
    border-right-color: #5f6368;
  }
  body.dark-mode .gmail-sidebar .nav-link {
    color: #e8eaed;
  }
  body.dark-mode .gmail-sidebar .nav-link:hover,
  body.dark-mode .gmail-sidebar .nav-link.active {
    background-color: #5f6368;
    color: #e8eaed;
  }
  /* Main Content */
  .gmail-main {
    padding: 20px;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(60,64,67,0.15);
    margin-bottom: 20px;
  }
  .email-list {
    max-height: 600px;
    overflow-y: auto;
    border-right: 1px solid #e0e0e0;
    background-color: #fff;
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
  /* Compose Modal */
  .compose-modal {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 480px;
    max-width: 95%;
    background-color: #fff;
    border: 1px solid #dadce0;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    z-index: 1100;
    display: none;
    animation: slideUp 0.3s ease-out;
  }
  @keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }
  .compose-modal-header {
    padding: 12px 16px;
    border-bottom: 1px solid #dadce0;
    background-color: #f1f3f4;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .compose-modal-header h6 {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
  }
  .compose-modal-close {
    cursor: pointer;
    font-size: 20px;
    color: #5f6368;
  }
  .compose-modal-body {
    padding: 16px;
  }
  .compose-modal-body input,
  .compose-modal-body textarea {
    border: 1px solid #dadce0;
    border-radius: 4px;
    width: 100%;
    padding: 8px 12px;
    margin-bottom: 12px;
  }
  .compose-modal-body input:focus,
  .compose-modal-body textarea:focus {
    outline: none;
    border-color: #1967d2;
  }
  /* Responsive */
  @media (max-width: 991px) {
    .gmail-sidebar { min-height: auto; }
  }
</style>
@endpush

@section('content')
<!-- Top Navbar -->
<nav class="navbar top-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('admin.gmail.emails') }}">
      <i class="fa fa-envelope text-danger me-2"></i>MyChed-Email
    </a>
    <div class="d-flex align-items-center">
      <!-- Search Box -->
      <div class="search-box me-3">
        <input type="text" id="email-search" placeholder="Search mail">
        <i class="fa fa-search"></i>
      </div>
      <!-- Dark Mode Toggle -->
      <button id="dark-mode-toggle" class="btn btn-outline-secondary me-2">
        <i class="fa fa-moon"></i>
      </button>
      <!-- Profile Dropdown -->
      <div class="dropdown profile-dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="rounded-circle me-2" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
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
    <!-- Sidebar -->
    <div class="col-lg-2 gmail-sidebar">
      <div class="section-title">Mail</div>
      <ul class="nav flex-column" id="email-categories">
        <li class="nav-item">
          <a class="nav-link active" href="#" data-category="inbox">
            <i class="fa fa-inbox me-2"></i>Inbox
          </a>
        </li>
      </ul>
      <div class="section-title mt-4">Labels</div>
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
          <i class="fa fa-upload me-2"></i>Import Emails
        </a>
      </div>
    </div>
    <!-- Main Content -->
    <div class="col-lg-10">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 id="current-category-title">Inbox</h4>
        <button class="btn btn-primary" id="compose-btn">
          <i class="fa fa-plus me-1"></i> Compose
        </button>
      </div>
      <div class="row">
        <!-- Email List -->
        <div class="col-md-4">
          <div class="list-group email-list" id="email-list">
            <p class="text-center text-muted">Loading emails…</p>
          </div>
          <!-- Pagination -->
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
      </div>
    </div>
  </div>
</div>

<!-- Compose Modal -->
<div class="compose-modal" id="compose-modal">
  <div class="compose-modal-header d-flex justify-content-between align-items-center">
    <h6>New Message</h6>
    <span class="compose-modal-close" id="compose-modal-close">&times;</span>
  </div>
  <div class="compose-modal-body">
    <form action="{{ route('admin.sendEmail') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-2">
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
<!-- jQuery, Bootstrap, and FilePond JS -->
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

  // Dark Mode Toggle
  $('#dark-mode-toggle').click(function(){
    $('body').toggleClass('dark-mode');
    $(this).html($('body').hasClass('dark-mode') ? '<i class="fa fa-sun"></i>' : '<i class="fa fa-moon"></i>');
    localStorage.setItem('darkMode', $('body').hasClass('dark-mode') ? 'true' : 'false');
  });
  if(localStorage.getItem('darkMode') === 'true'){
    $('body').addClass('dark-mode');
    $('#dark-mode-toggle').html('<i class="fa fa-sun"></i>');
  }

  // Variables for email list
  var currentCategory = 'inbox';
  var currentPage = 1;
  var totalPages = 1;
  var emailsPerPage = 10;

  // Load Emails via AJAX
  function loadEmails(category, page = 1, searchQuery = ''){
    $.ajax({
      url: '{{ route("admin.gmail.getEmails") }}',
      type: 'GET',
      data: { category: category, page: page, per_page: emailsPerPage, q: searchQuery },
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

  // Update Pagination UI
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

  // Initial Email Load
  loadEmails(currentCategory);

  // Category Switch Handler
  $('a.nav-link[data-category]').click(function(e){
    e.preventDefault();
    $('a.nav-link[data-category]').removeClass('active');
    $(this).addClass('active');
    currentCategory = $(this).data('category');
    $('#current-category-title').text(currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1));
    loadEmails(currentCategory);
    $('#email-details').html('<p class="text-center text-muted">Select an email to view its details.</p>');
  });

  // Pagination Handler
  $('#pagination').on('click', '.page-link', function(e){
    e.preventDefault();
    var page = $(this).data('page');
    if(page < 1 || page > totalPages) return;
    currentPage = page;
    loadEmails(currentCategory, currentPage);
  });

  // Email Item Click Handler (load email details)
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

  // Search Functionality
  $('#email-search').on('input', function(){
    var query = $(this).val();
    loadEmails(currentCategory, 1, query);
  });

  // Compose Modal Toggle
  $('#compose-btn').click(function(){
    $('#compose-modal').fadeIn(300);
  });
  $('#compose-modal-close, #compose-cancel-btn').click(function(){
    $('#compose-modal').fadeOut(300);
  });

  // Autofill for Compose "To:" Field
  $('#compose-to').on('input', function(){
    var query = $(this).val();
    if(query.length >= 2) {
      $.ajax({
        url: '{{ route("admin.gmail.getContacts") }}',
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
