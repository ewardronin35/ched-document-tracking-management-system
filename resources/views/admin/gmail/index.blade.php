@extends('layouts.app')

@section('title', 'CHEDRO-9 Email History')

@push('styles')
<!-- Google Fonts, Material Icons, Bootstrap 5, and Font Awesome -->
<link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- FilePond CSS for file uploads -->
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

<style>
/* Base Styles with Lighter Color Scheme */
:root {
  --gmail-blue: #4285F4;
  --gmail-blue-hover: #5a95f5;
  --gmail-red: #ea4335;
  --gmail-yellow: #fbbc04;
  --gmail-green: #34a853;
  --gmail-lightblue: #e8f0fe;
  --gmail-lightblue-hover: #d2e3fc;
  --gmail-hover: #f8f9fa;
  --gmail-bg: #ffffff;
  --gmail-border: #e6e6e6;
  --gmail-text-primary: #202124;
  --gmail-text-secondary: #5f6368;
  --gmail-selected: #d2e3fc;
  --gmail-unread-bg: #f2f6fc;
  --sidebar-bg: #f5f7fb;
  --header-bg: #294a9b;
  --header-text: #ffffff;
  --spacing-sm: 4px;
  --spacing-md: 8px;
  --spacing-lg: 16px;
  --border-radius: 8px;
  --header-height: 64px;
  --sidebar-width: 256px;
  --sidebar-width-collapsed: 80px;
  --transition-speed: 0.15s;
}

html, body {
  height: 100%;
  margin: 0;
  font-family: 'Roboto', sans-serif;
  background-color: var(--gmail-bg);
  color: var(--gmail-text-primary);
  overflow: hidden;
}

/* Main Layout Structure */
.app-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}

.main-wrapper {
  display: flex;
  flex: 1;
  overflow: hidden;
}

/* Unified Header with Darker Blue Background */
.header {
  display: flex;
  align-items: center;
  padding: 0 var(--spacing-lg);
  height: var(--header-height);
  background-color: var(--header-bg);
  color: var(--header-text);
  box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 2px 6px 2px rgba(60,64,67,0.15);
  position: relative;
  z-index: 10;
}

.header-brand {
  display: flex;
  align-items: center;
  margin-right: var(--spacing-lg);
}

.menu-toggle {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  margin-right: var(--spacing-lg);
  cursor: pointer;
  color: white;
}

.menu-toggle:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.app-logo {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: white;
}

.logo-text {
  font-family: 'Google Sans', sans-serif;
  margin-left: var(--spacing-md);
  display: flex;
  flex-direction: column;
}

.logo-title {
  font-size: 22px;
  font-weight: 500;
  letter-spacing: -0.5px;
  color: white;
}

.logo-subtitle {
  font-size: 14px;
  font-weight: normal;
  color: rgba(255, 255, 255, 0.8);
}

.search-container {
  flex: 1;
  max-width: 720px;
  position: relative;
}

.search-wrapper {
  background-color: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  height: 48px;
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
  transition: background-color 0.15s, box-shadow 0.15s;
}

.search-wrapper:focus-within {
  background-color: white;
  box-shadow: 0 1px 1px 0 rgba(65,69,73,0.3), 0 1px 3px 1px rgba(65,69,73,0.15);
}

.search-wrapper:focus-within .search-button,
.search-wrapper:focus-within .search-filters {
  color: var(--gmail-text-secondary);
}

.search-button {
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
  border: none;
  width: 48px;
  height: 48px;
  color: rgba(255, 255, 255, 0.9);
  cursor: pointer;
}

.search-input {
  flex: 1;
  height: 100%;
  padding: 0;
  border: none;
  background: transparent;
  font-size: 16px;
  color: white;
}

.search-input::placeholder {
  color: rgba(255, 255, 255, 0.7);
}

.search-wrapper:focus-within .search-input::placeholder {
  color: var(--gmail-text-secondary);
}

.search-wrapper:focus-within .search-input {
  color: var(--gmail-text-primary);
}

.search-input:focus {
  outline: none;
}

.search-clear {
  display: none;
  align-items: center;
  justify-content: center;
  background: transparent;
  border: none;
  width: 48px;
  height: 48px;
  color: var(--gmail-text-secondary);
  cursor: pointer;
}

.search-filters {
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
  border: none;
  width: 48px;
  height: 48px;
  color: rgba(255, 255, 255, 0.9);
  cursor: pointer;
}

.header-actions {
  display: flex;
  align-items: center;
  margin-left: auto;
}

.header-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: white;
  margin-left: var(--spacing-lg);
  position: relative;
  cursor: pointer;
}

.header-icon:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.badge {
  position: absolute;
  top: 4px;
  right: 4px;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  background-color: var(--gmail-red);
  color: white;
  font-size: 12px;
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 var(--spacing-sm);
}

.user-profile {
  display: flex;
  align-items: center;
  margin-left: var(--spacing-lg);
  cursor: pointer;
  color: white;
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background-color: #5a95f5;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
  font-size: 14px;
  overflow: hidden;
}

.user-info {
  margin-left: var(--spacing-md);
  display: none;
}

@media (min-width: 768px) {
  .user-info {
    display: block;
  }
}

.user-name {
  font-size: 14px;
  font-weight: 500;
  color: white;
}

.user-role {
  font-size: 12px;
  color: rgba(255, 255, 255, 0.8);
}

/* Sidebar with Lighter Background */
.gmail-sidebar {
  width: var(--sidebar-width);
  background-color: var(--sidebar-bg);
  height: calc(100vh - var(--header-height));
  flex-shrink: 0;
  transition: width var(--transition-speed);
  overflow: hidden;
}

.gmail-sidebar.collapsed {
  width: var(--sidebar-width-collapsed);
}

.gmail-sidebar-content {
  width: var(--sidebar-width);
  height: 100%;
  overflow-y: auto;
  overflow-x: hidden;
  scrollbar-width: thin;
  scrollbar-color: var(--gmail-text-secondary) transparent;
  padding: var(--spacing-lg) 0;
}

.gmail-idebar-content::-webkit-scrollbar {
  width: 8px;
}

.gmail-sidebar-content::-webkit-scrollbar-track {
  background: transparent;
}

.gmail-sidebar-content::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.1);
  border-radius: 20px;
}

.compose-btn {
  display: flex;
  align-items: center;
  margin: 0 var(--spacing-md) var(--spacing-lg) var(--spacing-md);
  padding: 0 var(--spacing-lg);
  height: 56px;
  background-color: var(--gmail-lightblue);
  color: var(--gmail-blue);
  border: none;
  border-radius: 16px;
  font-family: 'Google Sans', sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: box-shadow 0.15s, background-color 0.15s;
}

.compose-btn:hover {
  background-color: var(--gmail-lightblue-hover);
  box-shadow: 0 1px 3px 0 rgba(60,64,67,0.3);
}

.compose-btn .material-icons {
  margin-right: var(--spacing-lg);
}

.gmail-.sidebar.collapsed .compose-btn {
  width: 56px;
  padding: 0;
  justify-content: center;
  margin-left: calc((var(--sidebar-width-collapsed) - 56px) / 2);
}

.gmail-sidebar.collapsed .compose-btn .material-icons {
  margin-right: 0;
}

.gmail-sidebar.collapsed .compose-btn span:not(.material-icons) {
  display: none;
}

.nav-section {
  list-style-type: none;
  padding: 0;
  margin: 0 0 var(--spacing-lg) 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 0 var(--spacing-lg) 0 var(--spacing-lg);
  height: 36px;
  margin: 0 var(--spacing-md) 0 0;
  border-radius: 0 18px 18px 0;
  cursor: pointer;
  color: var(--gmail-text-primary);
  transition: background-color var(--transition-speed);
  white-space: nowrap;
}

.nav-item:hover {
  background-color: var(--gmail-hover);
}

.nav-item.active {
  background-color: var(--gmail-selected);
  font-weight: 500;
  color: var(--gmail-blue);
}

.nav-icon {
  margin-right: var(--spacing-lg);
  width: 20px;
  text-align: center;
  font-size: 20px;
}

.nav-text {
  flex: 1;
  font-size: 14px;
  overflow: hidden;
  text-overflow: ellipsis;
}

.nav-count {
  font-size: 12px;
  color: var(--gmail-text-secondary);
  margin-left: var(--spacing-md);
}

.gmail-sidebar.collapsed .nav-text,
.gmail-sidebar.collapsed .nav-count {
  display: none;
}

.section-title {
  font-size: 11px;
  text-transform: uppercase;
  color: var(--gmail-text-secondary);
  padding: var(--spacing-lg) var(--spacing-lg) var(--spacing-md);
  font-weight: 500;
  letter-spacing: 0.5px;
}

.gmail-sidebar.collapsed .section-title {
  display: none;
}

/* Content Area */
.content-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background-color: white;
  position: relative;
}

/* Toolbar */
.email-toolbar {
  display: flex;
  align-items: center;
  padding: 0 var(--spacing-lg);
  height: 48px;
  border-bottom: 1px solid var(--gmail-border);
  background-color: white;
  z-index: 5;
}

.toolbar-left {
  display: flex;
  align-items: center;
}

.toolbar-right {
  display: flex;
  align-items: center;
  margin-left: auto;
}

.toolbar-action {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  margin-right: var(--spacing-md);
  cursor: pointer;
}

.toolbar-action:hover {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

.checkbox-container {
  display: flex;
  align-items: center;
  margin-right: var(--spacing-md);
}

.custom-checkbox {
  width: 18px;
  height: 18px;
  border: 2px solid var(--gmail-text-secondary);
  border-radius: 2px;
  position: relative;
  cursor: pointer;
  transition: background-color 0.15s, border-color 0.15s;
}

.custom-checkbox.checked {
  background-color: var(--gmail-blue);
  border-color: var(--gmail-blue);
}

.custom-checkbox.checked::after {
  content: "";
  position: absolute;
  top: 1px;
  left: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.dropdown-arrow {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  margin-left: var(--spacing-sm);
  cursor: pointer;
}

.dropdown-arrow:hover {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

.pagination {
  display: flex;
  align-items: center;
  color: var(--gmail-text-secondary);
  font-size: 12px;
}

.pagination-text {
  margin: 0 var(--spacing-md);
}

.pagination-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  cursor: pointer;
}

.pagination-btn:hover:not(.disabled) {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

.pagination-btn.disabled {
  opacity: 0.5;
  cursor: default;
}

/* Email Container */
.email-container {
  display: flex;
  flex: 1;
  overflow: hidden;
}

/* Email List */
.email-list-column {
  width: 400px;
  min-width: 280px;
  max-width: 450px;
  border-right: 1px solid var(--gmail-border);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  position: relative;
  transition: width 0.2s;
  flex-shrink: 0;
}

.email-list-column.collapsed {
  width: 0;
  min-width: 0;
  border-right: none;
}

/* Email Tabs */
.email-tabs {
  display: flex;
  border-bottom: 1px solid var(--gmail-border);
  background-color: white;
  overflow-x: auto;
  scrollbar-width: none;
}

.email-tabs::-webkit-scrollbar {
  display: none;
}

.email-tab {
  padding: var(--spacing-md) var(--spacing-lg);
  font-size: 14px;
  font-weight: 500;
  color: var(--gmail-text-secondary);
  border-bottom: 2px solid transparent;
  cursor: pointer;
  white-space: nowrap;
  text-align: center;
  flex: 1;
  min-width: 80px;
}

.email-tab.active {
  color: var(--gmail-blue);
  border-bottom-color: var(--gmail-blue);
}

.email-tab:hover:not(.active) {
  color: var(--gmail-text-primary);
  background-color: var(--gmail-hover);
}

/* Category Filter */
.category-filter {
  display: flex;
  padding: var(--spacing-md) var(--spacing-lg);
  border-bottom: 1px solid var(--gmail-border);
  background-color: white;
  overflow-x: auto;
  scrollbar-width: none;
}

.category-filter::-webkit-scrollbar {
  display: none;
}

.filter-chip {
  padding: 6px var(--spacing-lg);
  border-radius: 16px;
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
  font-size: 14px;
  margin-right: var(--spacing-md);
  cursor: pointer;
  white-space: nowrap;
  transition: background-color 0.15s;
}

.filter-chip.active {
  background-color: var(--gmail-selected);
  color: var(--gmail-blue);
  font-weight: 500;
}

.filter-chip:hover:not(.active) {
  background-color: #e8eaed;
}

/* Email List */
.email-list {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  scrollbar-width: thin;
  scrollbar-color: var(--gmail-text-secondary) transparent;
}

.email-list::-webkit-scrollbar {
  width: 8px;
}

.email-list::-webkit-scrollbar-track {
  background: transparent;
}

.email-list::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 20px;
}

.email-item {
  display: flex;
  align-items: center;
  padding: var(--spacing-md) var(--spacing-lg);
  border-bottom: 1px solid var(--gmail-border);
  cursor: pointer;
  position: relative;
  background-color: white;
  transition: box-shadow 0.15s, background-color 0.15s;
}

.email-item:hover {
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  z-index: 1;
}

.email-item.unread {
  background-color: var(--gmail-unread-bg);
  font-weight: 500;
}

.email-item.selected {
  background-color: var(--gmail-selected);
}

.email-checkbox {
  margin-right: var(--spacing-md);
  flex-shrink: 0;
}

.star-button {
  background: transparent;
  border: none;
  padding: 0;
  margin: 0 var(--spacing-md);
  color: var(--gmail-border);
  cursor: pointer;
  flex-shrink: 0;
}

.star-button.starred {
  color: var(--gmail-yellow);
}

.email-content-wrapper {
  flex: 1;
  min-width: 0;
  display: flex;
  align-items: flex-start;
}

.email-sender {
  width: 25%;
  min-width: 120px;
  max-width: 200px;
  padding-right: var(--spacing-md);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-weight: inherit;
  flex-shrink: 0;
}

.email-content {
  flex: 1;
  min-width: 0;
}

.email-subject {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-weight: inherit;
  margin-bottom: 2px;
}

.email-snippet {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: var(--gmail-text-secondary);
  font-weight: normal;
  font-size: 13px;
}

.email-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  margin-left: var(--spacing-md);
  flex-shrink: 0;
  min-width: 60px;
}

.email-time {
  font-size: 12px;
  color: var(--gmail-text-secondary);
  white-space: nowrap;
}

.email-item.unread .email-time {
  color: var(--gmail-blue);
  font-weight: bold;
}

.email-actions {
  display: none;
  margin-top: var(--spacing-sm);
}

.email-item:hover .email-actions {
  display: flex;
}

.email-item:hover .email-time {
  display: none;
}

.email-action {
  background: transparent;
  border: none;
  padding: 4px;
  color: var(--gmail-text-secondary);
  border-radius: 4px;
  cursor: pointer;
}

.email-action:hover {
  background-color: rgba(0,0,0,0.05);
  color: var(--gmail-text-primary);
}

/* Email Detail */
.email-detail-column {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background-color: white;
  transition: margin-left 0.2s;
}

.email-detail-toolbar {
  display: flex;
  align-items: center;
  height: 48px;
  padding: 0 var(--spacing-lg);
  border-bottom: 1px solid var(--gmail-border);
}

.detail-back {
  display: none;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  margin-right: var(--spacing-md);
  cursor: pointer;
}

.detail-back:hover {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

.detail-actions {
  display: flex;
  align-items: center;
}

.detail-action {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  margin-right: var(--spacing-md);
  cursor: pointer;
}

.detail-action:hover {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

.email-pagination {
  margin-left: auto;
  display: flex;
  align-items: center;
  color: var(--gmail-text-secondary);
  font-size: 12px;
}

.detail-nav {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  cursor: pointer;
}

.detail-nav:hover:not(.disabled) {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

.detail-nav.disabled {
  opacity: 0.5;
  cursor: default;
}

.email-detail {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: var(--spacing-lg);
  scrollbar-width: thin;
  scrollbar-color: var(--gmail-text-secondary) transparent;
}

.email-detail::-webkit-scrollbar {
  width: 8px;
}

.email-detail::-webkit-scrollbar-track {
  background: transparent;
}

.email-detail::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 20px;
}

.email-detail-header {
  margin-bottom: var(--spacing-lg);
}

.email-detail-subject {
  font-size: 22px;
  font-weight: normal;
  margin-bottom: var(--spacing-lg);
  color: var(--gmail-text-primary);
}

.email-detail-meta {
  display: flex;
  align-items: flex-start;
}

.avatar-large {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: var(--gmail-blue);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
  font-size: 16px;
  margin-right: var(--spacing-lg);
  flex-shrink: 0;
}

.email-detail-info {
  flex: 1;
  min-width: 0;
}

.email-detail-sender {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
}

.sender-name {
  font-weight: 500;
  margin-right: var(--spacing-sm);
}

.sender-email {
  color: var(--gmail-text-secondary);
  font-size: 14px;
  font-weight: normal;
}

.email-detail-time {
  color: var(--gmail-text-secondary);
  font-size: 12px;
  margin-left: auto;
}

.email-detail-recipients {
  color: var(--gmail-text-secondary);
  font-size: 13px;
  margin-top: 4px;
}

.email-detail-buttons {
  display: flex;
  margin-top: var(--spacing-lg);
}

.email-detail-body {
  padding: var(--spacing-lg) 0;
  border-top: 1px solid var(--gmail-border);
  color: var(--gmail-text-primary);
  line-height: 1.6;
  word-wrap: break-word;
}

.email-attachments {
  padding: var(--spacing-lg) 0;
  border-top: 1px solid var(--gmail-border);
}

.attachments-header {
  display: flex;
  align-items: center;
  margin-bottom: var(--spacing-lg);
}

.attachments-title {
  font-size: 14px;
  font-weight: 500;
  margin-right: var(--spacing-sm);
}

.attachments-count {
  color: var(--gmail-text-secondary);
  font-size: 14px;
  font-weight: normal;
}

.attachments-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: var(--spacing-lg);
}

.attachment-item {
  border: 1px solid var(--gmail-border);
  border-radius: var(--border-radius);
  overflow: hidden;
  transition: box-shadow 0.15s;
}

.attachment-item:hover {
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

.attachment-preview {
  height: 100px;
  background-color: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
}

.attachment-preview img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.attachment-icon {
  font-size: 48px;
  color: var(--gmail-text-secondary);
}

.attachment-info {
  padding: var(--spacing-md);
  border-top: 1px solid var(--gmail-border);
}

.attachment-name {
  font-size: 12px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 4px;
}

.attachment-actions {
  display: flex;
  justify-content: flex-end;
}

.attachment-button {
  background: transparent;
  border: none;
  padding: 4px;
  color: var(--gmail-text-secondary);
  border-radius: 4px;
  cursor: pointer;
}

.attachment-button:hover {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

/* Empty States */
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  padding: var(--spacing-lg);
  text-align: center;
}

.empty-icon {
  font-size: 64px;
  color: var(--gmail-text-secondary);
  margin-bottom: var(--spacing-lg);
}

.empty-title {
  font-size: 16px;
  font-weight: 500;
  margin-bottom: var(--spacing-md);
  color: var(--gmail-text-primary);
}

.empty-message {
  font-size: 14px;
  color: var(--gmail-text-secondary);
  max-width: 300px;
}

/* Loading Spinner */
.loading-spinner {
  display: inline-block;
  width: 40px;
  height: 40px;
  border: 3px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  border-top-color: var(--gmail-blue);
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Compose Modal */
.compose-modal {
  position: fixed;
  bottom: 0;
  right: var(--spacing-lg);
  width: 540px;
  background-color: white;
  border-radius: 8px 8px 0 0;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  z-index: 100;
  display: none;
  animation: slide-up 0.2s ease-out forwards;
}

@keyframes slide-up {
  from { transform: translateY(100%); }
  to { transform: translateY(0); }
}

.compose-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px var(--spacing-lg);
  background-color: #f2f6fc;
  border-radius: 8px 8px 0 0;
  cursor: move;
  user-select: none;
}

.compose-title {
  font-size: 14px;
  font-weight: 500;
}

.compose-actions {
  display: flex;
}

.compose-action {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  cursor: pointer;
}

.compose-action:hover {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

.compose-body {
  padding: var(--spacing-lg);
}

.compose-field {
  margin-bottom: var(--spacing-lg);
  border-bottom: 1px solid var(--gmail-border);
  transition: border-color 0.15s;
}

.compose-field:focus-within {
  border-color: var(--gmail-blue);
}

.compose-input {
  width: 100%;
  padding: var(--spacing-md) 0;
  border: none;
  background: transparent;
  font-size: 14px;
  color: var(--gmail-text-primary);
}

.compose-input:focus {
  outline: none;
}

.compose-textarea {
  width: 100%;
  min-height: 300px;
  padding: var(--spacing-md) 0;
  border: none;
  background: transparent;
  font-size: 14px;
  color: var(--gmail-text-primary);
  resize: none;
}

.compose-textarea:focus {
  outline: none;
}

.compose-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--spacing-lg);
  border-top: 1px solid var(--gmail-border);
}

.send-button {
  display: flex;
  align-items: center;
  padding: 0 var(--spacing-lg);
  height: 36px;
  background-color: var(--gmail-blue);
  color: white;
  border: none;
  border-radius: 4px;
  font-family: 'Google Sans', sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.15s;
}

.send-button:hover {
  background-color: var(--gmail-blue-hover);
}

.send-button .material-icons {
  font-size: 18px;
  margin-right: var(--spacing-md);
}

.compose-tools {
  display: flex;
}

.compose-tool {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 4px;
  background: transparent;
  border: none;
  color: var(--gmail-text-secondary);
  margin-left: var(--spacing-sm);
  cursor: pointer;
}

.compose-tool:hover {
  background-color: var(--gmail-hover);
  color: var(--gmail-text-primary);
}

/* User Menu */
.user-menu {
  position: absolute;
  top: calc(var(--header-height) - 10px);
  right: var(--spacing-lg);
  width: 320px;
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: 0 3px 14px rgba(0, 0, 0, 0.2);
  z-index: 100;
  display: none;
}

.user-menu.active {
  display: block;
  animation: fade-in 0.2s ease-out forwards;
}

@keyframes fade-in {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.user-menu-header {
  padding: var(--spacing-lg);
  border-bottom: 1px solid var(--gmail-border);
}

.user-menu-profile {
  display: flex;
  align-items: center;
}

.user-menu-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: var(--gmail-blue);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
  font-size: 16px;
  margin-right: var(--spacing-lg);
}

.user-menu-info {
  flex: 1;
}

.user-menu-name {
  font-weight: 500;
  margin-bottom: 2px;
}

.user-menu-email {
  font-size: 14px;
  color: var(--gmail-text-secondary);
}

.user-menu-items {
  padding: var(--spacing-md) 0;
}

.user-menu-item {
  display: flex;
  align-items: center;
  padding: var(--spacing-md) var(--spacing-lg);
  color: var(--gmail-text-primary);
  text-decoration: none;
  cursor: pointer;
}

.user-menu-item:hover {
  background-color: var(--gmail-hover);
}

.user-menu-icon {
  margin-right: var(--spacing-lg);
  color: var(--gmail-text-secondary);
  width: 24px;
  text-align: center;
}

/* Authentication Modal */
.auth-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 200;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s;
}

.auth-modal.active {
  opacity: 1;
  visibility: visible;
}

.auth-modal-content {
  width: 400px;
  max-width: 90%;
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.auth-modal-header {
  padding: var(--spacing-lg);
  background-color: var(--gmail-blue);
  color: white;
}

.auth-modal-title {
  font-size: 18px;
  font-weight: 500;
  margin: 0;
}

.auth-modal-body {
  padding: var(--spacing-lg);
}

.auth-modal-text {
  margin-bottom: var(--spacing-lg);
  line-height: 1.5;
}

.auth-modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: var(--spacing-md);
}

.auth-cancel-btn {
  padding: var(--spacing-md) var(--spacing-lg);
  background-color: #f1f3f4;
  color: var(--gmail-text-primary);
  border: none;
  border-radius: 4px;
  font-family: 'Google Sans', sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
}

.auth-confirm-btn {
  padding: var(--spacing-md) var(--spacing-lg);
  background-color: var(--gmail-blue);
  color: white;
  border: none;
  border-radius: 4px;
  font-family: 'Google Sans', sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
}

.auth-cancel-btn:hover {
  background-color: #e8eaed;
}

.auth-confirm-btn:hover {
  background-color: var(--gmail-blue-hover);
}

/* Responsive Design */
@media (max-width: 1024px) {
  .gmail-sidebar {
    width: var(--sidebar-width-collapsed);
  }
  
  .gmail-sidebar .compose-btn {
    width: 56px;
    padding: 0;
    justify-content: center;
    margin-left: calc((var(--sidebar-width-collapsed) - 56px) / 2);
  }
  
  .gmail-sidebar .compose-btn .material-icons {
    margin-right: 0;
  }
  
  .gmail-sidebar .compose-btn span:not(.material-icons) {
    display: none;
  }
  
  .gmail-sidebar .nav-text,
  .gmail-sidebar .nav-count,
  .gmail-sidebar .section-title {
    display: none;
  }
  
  .email-list-column {
    width: 320px;
  }
}

@media (max-width: 768px) {
  .search-container {
    max-width: none;
  }
  
  .header {
    padding: 0 var(--spacing-md);
  }
  
  .logo-text {
    display: none;
  }
  
  .email-list-column {
    width: 100%;
    max-width: none;
    border-right: none;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10;
  }
  
  .email-list-column.hidden {
    display: none;
  }
  
  .email-detail-column {
    width: 100%;
  }
  
  .detail-back {
    display: flex;
  }
  
  .compose-modal {
    width: 100%;
    right: 0;
  }
  
  .user-menu {
    width: 280px;
  }
}

@media (max-width: 480px) {
  .header-search {
    max-width: 200px;
  }
  
  .email-tabs {
    flex-wrap: nowrap;
  }
  
  .email-tab {
    flex: none;
    padding: var(--spacing-md) var(--spacing-md);
  }
  
  .category-filter {
    justify-content: flex-start;
  }
  
  .filter-chip {
    flex: none;
  }
  
  .email-sender {
    min-width: 100px;
  }
}

.mobile-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 90;
  display: none;
}

.mobile-overlay.active {
  display: block;
}

/* Sidebar Toggle on Mobile */
@media (max-width: 768px) {
  .gmail-sidebar {
    position: fixed;
    left: -260px;
    top: var(--header-height);
    bottom: 0;
    z-index: 100;
    width: 260px;
    transition: left 0.3s;
  }
  
  .gmail-sidebar.active {
    left: 0;
  }
}
/* Add these styles at the end of your stylesheet */
/* Fix the white space between headers */
.app-container {
  width: 100%;
  max-width: 100%;
  padding: 0;
  margin: 0;
  overflow-x: hidden;
}

/* Fix for nested container spacing */
body > .app-container {
  margin-top: 0; /* Important to remove space at top */
}

/* Set main wrapper to full width */
.main-wrapper {
  width: 100%;
  max-width: 100%;
  margin: 0;
  padding: 0;
}

/* Fix potential overflow issues */
html, body {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
}

/* Remove any hidden margins */
.header {
  margin: 0;
}

/* Ensure content area fills available space */
.content-area {
  flex: 1;
  width: 100%;
  min-width: 0;
}

/* Force proper display of content */
@media (min-width: 769px) {
  .content-area {
    width: calc(100% - var(--sidebar-width));
  }
  
  .gmail-sidebar.collapsed + .content-area {
    width: calc(100% - var(--sidebar-width-collapsed));
  }
}
</style>
@endpush

@section('content')
<div class="app-container">
  <!-- Header - Now using a unified design with darker blue background -->
  <header class="header">
    <div class="header-brand">
      <button id="menu-toggle" class="menu-toggle" aria-label="Toggle menu">
        <span class="material-icons">menu</span>
      </button>
      
      <a href="{{ route('admin.gmail.backToDashboard') }}" class="app-logo">
        <span class="material-icons" style="font-size: 28px;">mail</span>
        <div class="logo-text">
          <span class="logo-title">CHEDRO-9</span>
          <span class="logo-subtitle">Email History</span>
        </div>
      </a>
    </div>
    
    <div class="search-container">
      <div class="search-wrapper">
        <button class="search-button" aria-label="Search">
          <span class="material-icons">search</span>
        </button>
        <input type="text" id="search-input" class="search-input" placeholder="Search mail">
        <button class="search-clear" aria-label="Clear search">
          <span class="material-icons">close</span>
        </button>
        <button class="search-filters" aria-label="Show search filters">
          <span class="material-icons">tune</span>
        </button>
      </div>
    </div>
    
    <div class="header-actions">
      <button class="header-icon" id="help-btn" aria-label="Help">
        <span class="material-icons">help_outline</span>
      </button>
      <button class="header-icon" id="settings-btn" aria-label="Settings">
        <span class="material-icons">settings</span>
      </button>
      <button class="header-icon" id="notifications-btn" aria-label="Notifications">
        <span class="material-icons">notifications</span>
        <span class="badge">33</span>
      </button>
      
      <div class="user-profile" id="user-profile-btn">
        <div class="user-avatar">
          @if(Auth::user()->profile_photo_url)
            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
          @else
            {{ substr(Auth::user()->name, 0, 1) }}
          @endif
        </div>
        <div class="user-info">
          <div class="user-name">Eduard Roland Donor</div>
          <div class="user-role">admin</div>
        </div>
      </div>
    </div>
  </header>
  
  <div class="main-wrapper">
    <!-- Sidebar - Now with lighter background -->
    <aside class="gmail-sidebar" id="sidebar">
      <div class="gmail-sidebar-content">
        <button id="compose-button" class="compose-btn">
          <span class="material-icons">create</span>
          <span>Compose</span>
        </button>
        
        <ul class="nav-section">
          <li class="nav-item active" data-category="inbox">
            <span class="material-icons nav-icon">inbox</span>
            <span class="nav-text">Inbox</span>
            <span class="nav-count">14</span>
          </li>
          <li class="nav-item" data-category="starred">
            <span class="material-icons nav-icon">star</span>
            <span class="nav-text">Starred</span>
          </li>
          <li class="nav-item" data-category="snoozed">
            <span class="material-icons nav-icon">schedule</span>
            <span class="nav-text">Snoozed</span>
          </li>
          <li class="nav-item" data-category="sent">
            <span class="material-icons nav-icon">send</span>
            <span class="nav-text">Sent</span>
          </li>
          <li class="nav-item" data-category="drafts">
            <span class="material-icons nav-icon">insert_drive_file</span>
            <span class="nav-text">Drafts</span>
            <span class="nav-count">3</span>
          </li>
          <li class="nav-item" data-category="more">
            <span class="material-icons nav-icon">expand_more</span>
            <span class="nav-text">More</span>
          </li>
        </ul>
        
        <div class="section-title">Labels</div>
        <ul class="nav-section">
          <li class="nav-item" data-category="important">
            <span class="material-icons nav-icon">label_important</span>
            <span class="nav-text">Important</span>
          </li>
          <li class="nav-item" data-category="spam">
            <span class="material-icons nav-icon">report</span>
            <span class="nav-text">Spam</span>
          </li>
          <li class="nav-item" data-category="trash">
            <span class="material-icons nav-icon">delete</span>
            <span class="nav-text">Trash</span>
          </li>
          <li class="nav-item" data-category="manage-labels">
            <span class="material-icons nav-icon">label</span>
            <span class="nav-text">Manage labels</span>
          </li>
        </ul>
        
        <div class="section-title">Admin</div>
        <ul class="nav-section">
          <li class="nav-item" data-category="import">
            <span class="material-icons nav-icon">cloud_upload</span>
            <span class="nav-text">Import Emails</span>
          </li>
          <li class="nav-item" id="logout-gmail-btn">
            <span class="material-icons nav-icon">logout</span>
            <span class="nav-text">Logout Gmail</span>
          </li>
          <li class="nav-item" id="dashboard-btn">
            <span class="material-icons nav-icon">dashboard</span>
            <span class="nav-text">Back to Dashboard</span>
          </li>
        </ul>
      </div>
    </aside>
    
    <!-- Content Area -->
    <div class="content-area">
      <!-- Email Toolbar -->
      <div class="email-toolbar">
        <div class="toolbar-left">
          <div class="checkbox-container">
            <div class="custom-checkbox" id="select-all"></div>
            <button class="dropdown-arrow" aria-label="Select dropdown">
              <span class="material-icons">arrow_drop_down</span>
            </button>
          </div>
          
          <button class="toolbar-action" id="refresh-btn" aria-label="Refresh">
            <span class="material-icons">refresh</span>
          </button>
          <button class="toolbar-action" aria-label="More actions">
            <span class="material-icons">more_vert</span>
          </button>
        </div>
        
        <div class="toolbar-right">
          <div class="pagination">
            <span class="pagination-text">1-10 of 20</span>
            <button class="pagination-btn disabled" data-page="prev" aria-label="Previous page">
              <span class="material-icons">chevron_left</span>
            </button>
            <button class="pagination-btn" data-page="next" aria-label="Next page">
              <span class="material-icons">chevron_right</span>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Email Container -->
      <div class="email-container">
        <!-- Email List -->
        <div class="email-list-column" id="email-list-column">
          <!-- Email Tabs -->
          <div class="email-tabs">
            <div class="email-tab active">Primary</div>
            <div class="email-tab">Social</div>
            <div class="email-tab">Promotions</div>
            <div class="email-tab">Updates</div>
            <div class="email-tab">Forums</div>
          </div>
          
          <!-- Category Filter -->
          <div class="category-filter">
            <div class="filter-chip active">All</div>
            <div class="filter-chip">Unread</div>
            <div class="filter-chip">Flagged</div>
            <div class="filter-chip">Attachments</div>
            <div class="filter-chip">To me</div>
          </div>
          
          <!-- Email List -->
          <div class="email-list" id="email-list">
            <!-- Sample email items - These will be loaded via AJAX -->
            <div class="email-item unread" data-email-id="1">
              <div class="custom-checkbox email-checkbox"></div>
              <button class="star-button">
                <span class="material-icons">star_border</span>
              </button>
              <div class="email-content-wrapper">
                <div class="email-sender">LinkedIn Job Alerts</div>
                <div class="email-content">
                  <div class="email-subject">Software Engineer positions</div>
                  <div class="email-snippet">New job alerts based on your profile...</div>
                </div>
                <div class="email-meta">
                  <div class="email-time">Mar 09</div>
                  <div class="email-actions">
                    <button class="email-action" aria-label="Archive">
                      <span class="material-icons">archive</span>
                    </button>
                    <button class="email-action" aria-label="Delete">
                      <span class="material-icons">delete</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="email-item unread" data-email-id="2">
              <div class="custom-checkbox email-checkbox"></div>
              <button class="star-button">
                <span class="material-icons">star_border</span>
              </button>
              <div class="email-content-wrapper">
                <div class="email-sender">Sonny at Codédex</div>
                <div class="email-content">
                  <div class="email-subject">Your peers are building cool stuff 🔥</div>
                  <div class="email-snippet">Check out what your fellow learners have created...</div>
                </div>
                <div class="email-meta">
                  <div class="email-time">Mar 09</div>
                  <div class="email-actions">
                    <button class="email-action" aria-label="Archive">
                      <span class="material-icons">archive</span>
                    </button>
                    <button class="email-action" aria-label="Delete">
                      <span class="material-icons">delete</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="email-item" data-email-id="3">
              <div class="custom-checkbox email-checkbox"></div>
              <button class="star-button starred">
                <span class="material-icons">star</span>
              </button>
              <div class="email-content-wrapper">
                <div class="email-sender">Poe - Fast AI chat</div>
                <div class="email-content">
                  <div class="email-subject">Welcome to Poe! Try the latest AI models</div>
                  <div class="email-snippet">Get started with Claude, ChatGPT and more...</div>
                </div>
                <div class="email-meta">
                  <div class="email-time">Mar 07</div>
                  <div class="email-actions">
                    <button class="email-action" aria-label="Archive">
                      <span class="material-icons">archive</span>
                    </button>
                    <button class="email-action" aria-label="Delete">
                      <span class="material-icons">delete</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Email Detail -->
        <div class="email-detail-column" id="email-detail-column">
          <div class="email-detail-toolbar">
            <button class="detail-back" id="back-to-list">
              <span class="material-icons">arrow_back</span>
            </button>
            
            <div class="detail-actions">
              <button class="detail-action" aria-label="Archive">
                <span class="material-icons">archive</span>
              </button>
              <button class="detail-action" aria-label="Report spam">
                <span class="material-icons">report</span>
              </button>
              <button class="detail-action" aria-label="Delete">
                <span class="material-icons">delete</span>
              </button>
              <button class="detail-action" aria-label="Mark as unread">
                <span class="material-icons">mail</span>
              </button>
              <button class="detail-action" aria-label="Snooze">
                <span class="material-icons">schedule</span>
              </button>
              <button class="detail-action" aria-label="More actions">
                <span class="material-icons">more_vert</span>
              </button>
            </div>
            
            <div class="email-pagination">
              <button class="detail-nav" data-nav="prev">
                <span class="material-icons">chevron_left</span>
              </button>
              <button class="detail-nav" data-nav="next">
                <span class="material-icons">chevron_right</span>
              </button>
            </div>
          </div>
          
          <div class="email-detail" id="email-detail">
            <!-- Empty state -->
            <div class="empty-state">
              <span class="material-icons empty-icon">mail_outline</span>
              <div class="empty-title">Select an email to view its details</div>
              <div class="empty-message">No email is currently selected</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Mobile Overlay -->
  <div class="mobile-overlay" id="mobile-overlay"></div>
  
  <!-- Compose Modal -->
  <div id="compose-modal" class="compose-modal">
    <div class="compose-header">
      <div class="compose-title">New Message</div>
      <div class="compose-actions">
        <button id="minimize-compose" class="compose-action" aria-label="Minimize">
          <span class="material-icons">minimize</span>
        </button>
        <button id="maximize-compose" class="compose-action" aria-label="Full screen">
          <span class="material-icons">open_in_full</span>
        </button>
        <button id="close-compose" class="compose-action" aria-label="Close">
          <span class="material-icons">close</span>
        </button>
      </div>
    </div>
    
    <form id="compose-form" action="{{ route('admin.sendEmail') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="compose-body">
        <div class="compose-field">
          <input type="email" name="to" id="compose-to" class="compose-input" placeholder="Recipients" required autocomplete="off" list="contactList">
          <datalist id="contactList"></datalist>
        </div>
        
        <div class="compose-field">
          <input type="text" name="subject" id="compose-subject" class="compose-input" placeholder="Subject" required>
        </div>
        
        <textarea name="body" id="compose-body" class="compose-textarea" placeholder="Compose email" required></textarea>
        
        <div style="margin-top: 16px;">
          <input type="file" name="attachments[]" id="compose-attachments" class="filepond" multiple>
        </div>
      </div>
      
      <div class="compose-footer">
        <button type="submit" class="send-button">
          <span class="material-icons">send</span>
          Send
        </button>
        
        <div class="compose-tools">
          <button type="button" class="compose-tool" aria-label="Attach files">
            <span class="material-icons">attach_file</span>
          </button>
          <button type="button" class="compose-tool" aria-label="Insert link">
            <span class="material-icons">link</span>
          </button>
          <button type="button" class="compose-tool" aria-label="Insert emoji">
            <span class="material-icons">sentiment_satisfied_alt</span>
          </button>
          <button type="button" class="compose-tool" aria-label="Discard draft">
            <span class="material-icons">delete</span>
          </button>
        </div>
      </div>
    </form>
  </div>
  
  <!-- User Menu -->
  <div id="user-menu" class="user-menu">
    <div class="user-menu-header">
      <div class="user-menu-profile">
        <div class="user-menu-avatar">
          @if(Auth::user()->profile_photo_url)
            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
          @else
            {{ substr(Auth::user()->name, 0, 1) }}
          @endif
        </div>
        <div class="user-menu-info">
          <div class="user-menu-name">Eduard Roland Donor</div>
          <div class="user-menu-email">{{ Auth::user()->email }}</div>
        </div>
      </div>
    </div>
    <div class="user-menu-items">
      <a href="{{ route('profile.show') }}" class="user-menu-item">
        <span class="material-icons user-menu-icon">account_circle</span>
        Manage your Account
      </a>
      <a href="{{ route('admin.gmail.resetToken') }}" class="user-menu-item">
        <span class="material-icons user-menu-icon">refresh</span>
        Switch Google Account
      </a>
      <a href="{{ route('admin.gmail.backToDashboard') }}" class="user-menu-item">
        <span class="material-icons user-menu-icon">dashboard</span>
        Back to Dashboard
      </a>
      <form method="POST" action="{{ route('logout') }}" class="user-menu-item" id="logout-form">
        @csrf
        <span class="material-icons user-menu-icon">logout</span>
        Logout from CHED-eTrack
      </form>
    </div>
  </div>
  
  <!-- Authentication Modal -->
  <div id="auth-modal" class="auth-modal">
    <div class="auth-modal-content">
      <div class="auth-modal-header">
        <h3 class="auth-modal-title">Google Authentication</h3>
      </div>
      <div class="auth-modal-body">
        <p class="auth-modal-text">
          You need to authenticate with Google to access your emails. Would you like to proceed with authentication?
        </p>
        <div class="auth-modal-actions">
          <button id="auth-cancel" class="auth-cancel-btn">Cancel</button>
          <button id="auth-confirm" class="auth-confirm-btn">Authenticate</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>

<script>
$(document).ready(function() {
  // Initialize FilePond for file uploads
  FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
  );
  
  FilePond.create(document.querySelector('input.filepond'), {
    acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword', 'application/vnd.ms-excel'],
    maxFileSize: '2MB'
  });
  
  // Toggle sidebar on mobile
  $('#menu-toggle').click(function() {
    $('#gmail-sidebar').toggleClass('active');
    $('#mobile-overlay').toggleClass('active');
  });
  
  // Hide mobile menu when overlay is clicked
  $('#mobile-overlay').click(function() {
    $('#gmail-sidebar').removeClass('active');
    $('#mobile-overlay').removeClass('active');
  });
  
  // Toggle sidebar collapse on desktop
  $('#sidebar-collapse').click(function() {
    $('#gmail-sidebar').toggleClass('collapsed');
    $('.content-area').toggleClass('sidebar-collapsed');
  });
  
  // Show/hide user menu
  $('#user-profile-btn').click(function(e) {
    e.stopPropagation();
    $('#user-menu').toggleClass('active');
  });
  
  // Hide user menu when clicking elsewhere
  $(document).click(function(e) {
    if (!$(e.target).closest('#user-menu, #user-profile-btn').length) {
      $('#user-menu').removeClass('active');
    }
  });
  
  // Search functionality
  $('#search-input').on('input', function() {
    if ($(this).val()) {
      $('.search-clear').css('display', 'flex');
    } else {
      $('.search-clear').css('display', 'none');
    }
  });
  
  $('.search-clear').click(function() {
    $('#search-input').val('').focus();
    $(this).css('display', 'none');
  });
  
  // Show compose modal
  $('#compose-button').click(function() {
    $('#compose-modal').show();
  });
  
  // Close compose modal
  $('#close-compose').click(function() {
    $('#compose-modal').hide();
  });
  
  // Email list item checkbox
  $(document).on('click', '.email-checkbox', function(e) {
    e.stopPropagation();
    $(this).toggleClass('checked');
    $(this).closest('.email-item').toggleClass('selected');
    
    updateSelectAllCheckbox();
  });
  
  // Select all checkbox
  $('#select-all').click(function() {
    const isChecked = $(this).toggleClass('checked').hasClass('checked');
    
    $('.email-checkbox').each(function() {
      $(this).toggleClass('checked', isChecked);
      $(this).closest('.email-item').toggleClass('selected', isChecked);
    });
  });
  
  function updateSelectAllCheckbox() {
    const totalItems = $('.email-checkbox').length;
    const checkedItems = $('.email-checkbox.checked').length;
    
    if (checkedItems === 0) {
      $('#select-all').removeClass('checked');
    } else if (checkedItems === totalItems) {
      $('#select-all').addClass('checked');
    }
  }
  
  // Star buttons
  $(document).on('click', '.star-button', function(e) {
    e.stopPropagation();
    $(this).toggleClass('starred');
    
    const icon = $(this).find('.material-icons');
    icon.text($(this).hasClass('starred') ? 'star' : 'star_border');
    
    // Here you would make an AJAX call to update the star status
  });
  
  // Refresh button
  $('#refresh-btn').click(function() {
    // Show loading state
    $('#email-list').html(`
      <div class="empty-state">
        <div class="loading-spinner"></div>
        <div class="empty-title">Refreshing...</div>
      </div>
    `);
    
    // Load emails (dummy implementation - replace with actual AJAX)
    setTimeout(function() {
      loadEmails('inbox');
    }, 500);
  });
  
  // Email click - show detail
  $(document).on('click', '.email-item', function(e) {
    if ($(e.target).closest('.email-checkbox, .star-button, .email-actions').length) return;
    
    $('.email-item').removeClass('selected');
    $(this).addClass('selected');
    $(this).removeClass('unread');
    
    const emailId = $(this).data('email-id');
    
    // Show loading in detail pane
    $('#email-detail').html(`
      <div class="empty-state">
        <div class="loading-spinner"></div>
        <div class="empty-title">Loading message...</div>
      </div>
    `);
    
    // On mobile, show detail view and hide list
    if (window.innerWidth <= 768) {
      $('#email-list-column').addClass('hidden');
    }
    
    // Load email detail (dummy implementation - replace with AJAX)
    setTimeout(function() {
      loadEmailDetail(emailId);
    }, 300);
  });
  
  // Back button on mobile
  $('#back-to-list').click(function() {
    $('#email-list-column').removeClass('hidden');
  });
  
  // Navigation item click
  $('.nav-item[data-category]').click(function() {
    if ($(this).data('category') === 'more') return;
    
    $('.nav-item').removeClass('active');
    $(this).addClass('active');
    
    const category = $(this).data('category');
    
    // Load emails for the selected category
    loadEmails(category);
    
    // Reset detail view
    $('#email-detail').html(`
      <div class="empty-state">
        <span class="material-icons empty-icon">mail_outline</span>
        <div class="empty-title">Select an email to view its details</div>
        <div class="empty-message">No email is currently selected</div>
      </div>
    `);
    
    // On mobile, ensure we're showing the list
    $('#email-list-column').removeClass('hidden');
    
    // Hide mobile sidebar if open
    $('#gmail-sidebar').removeClass('active');
    $('#mobile-overlay').removeClass('active');
  });
  
  // Load emails (dummy implementation - replace with actual AJAX)
  function loadEmails(category) {
    $('#email-list').html(`
      <div class="empty-state">
        <div class="loading-spinner"></div>
        <div class="empty-title">Loading emails...</div>
      </div>
    `);
    
    // Simulate AJAX delay
    setTimeout(function() {
      $.ajax({
        url: '{{ route("admin.gmail.getEmails") }}',
        type: 'GET',
        data: { category: category, page: 1 },
        success: function(response) {
          const emailList = $('#email-list');
          emailList.empty();
          
          if(response.data && response.data.length) {
            $.each(response.data, function(i, email) {
              const item = `
                <div class="email-item${email.read ? '' : ' unread'}" data-email-id="${email.id}">
                  <div class="custom-checkbox email-checkbox"></div>
                  <button class="star-button${email.starred ? ' starred' : ''}">
                    <span class="material-icons">${email.starred ? 'star' : 'star_border'}</span>
                  </button>
                  <div class="email-content-wrapper">
                    <div class="email-sender">${email.from}</div>
                    <div class="email-content">
                      <div class="email-subject">${email.subject}</div>
                      <div class="email-snippet">${email.snippet}</div>
                    </div>
                    <div class="email-meta">
                      <div class="email-time">${email.date}</div>
                      <div class="email-actions">
                        <button class="email-action" aria-label="Archive">
                          <span class="material-icons">archive</span>
                        </button>
                        <button class="email-action" aria-label="Delete">
                          <span class="material-icons">delete</span>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              `;
              emailList.append(item);
            });
            
            // Update pagination info
            $('.pagination-text').text(`1-${response.data.length} of ${response.total}`);
          } else {
            emailList.html(`
              <div class="empty-state">
                <span class="material-icons empty-icon">inbox</span>
                <div class="empty-title">No emails found</div>
                <div class="empty-message">Your ${category} is empty</div>
              </div>
            `);
            
            $('.pagination-text').text('0-0 of 0');
          }
        },
        error: function(xhr) {
          console.error('Error loading emails:', xhr);
          
          // Check for auth error
          if(xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.auth_error) {
            showAuthModal();
          } else {
            $('#email-list').html(`
              <div class="empty-state">
                <span class="material-icons empty-icon">error_outline</span>
                <div class="empty-title">Something went wrong</div>
                <div class="empty-message">Please try again later</div>
              </div>
            `);
          }
        }
      });
    }, 300);
  }
  
  // Load email detail
  function loadEmailDetail(emailId) {
    $.ajax({
      url: '{{ route("admin.gmail.getEmailDetails") }}',
      type: 'GET',
      data: { id: emailId },
      success: function(response) {
        let content = `
          <div class="email-detail-header">
            <h1 class="email-detail-subject">${response.subject}</h1>
            <div class="email-detail-meta">
              <div class="avatar-large">${response.from.charAt(0).toUpperCase()}</div>
              <div class="email-detail-info">
                <div class="email-detail-sender">
                  <span class="sender-name">${response.from.split('@')[0]}</span>
                  <span class="sender-email">&lt;${response.from}&gt;</span>
                </div>
                <div class="email-detail-time">${response.date}</div>
                <div class="email-detail-recipients">to me</div>
              </div>
            </div>
            <div class="email-detail-buttons">
              <button class="detail-action" aria-label="Reply">
                <span class="material-icons">reply</span>
              </button>
              <button class="detail-action" aria-label="Forward">
                <span class="material-icons">forward</span>
              </button>
            </div>
          </div>
          <div class="email-detail-body">
            ${response.bodyHtml ? response.bodyHtml : response.bodyText}
          </div>`;
        
        // Add attachments if present
        if(response.attachments && response.attachments.length) {
          content += `
            <div class="email-attachments">
              <div class="attachments-header">
                <span class="attachments-title">Attachments</span>
                <span class="attachments-count">(${response.attachments.length})</span>
              </div>
              <div class="attachments-grid">`;
              
          $.each(response.attachments, function(i, att) {
            if(att.isImage && att.url) {
              content += `
                <div class="attachment-item">
                  <div class="attachment-preview">
                    <img src="${att.url}" alt="${att.filename}">
                  </div>
                  <div class="attachment-info">
                    <div class="attachment-name">${att.filename}</div>
                    <div class="attachment-actions">
                      <a href="${att.url}" download class="attachment-button" aria-label="Download">
                        <span class="material-icons">download</span>
                      </a>
                    </div>
                  </div>
                </div>`;
            } else {
              content += `
                <div class="attachment-item">
                  <div class="attachment-preview">
                    <span class="material-icons attachment-icon">description</span>
                  </div>
                  <div class="attachment-info">
                    <div class="attachment-name">${att.filename}</div>
                    <div class="attachment-actions">
                      <a href="${att.url}" download class="attachment-button" aria-label="Download">
                        <span class="material-icons">download</span>
                      </a>
                    </div>
                  </div>
                </div>`;
            }
          });
          
          content += `
              </div>
            </div>`;
        }
        
        $('#email-detail').html(content);
      },
      error: function(xhr) {
        console.error('Error loading email details:', xhr);
        
        // Check for auth error
        if(xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.auth_error) {
          showAuthModal();
        } else {
          $('#email-detail').html(`
            <div class="empty-state">
              <span class="material-icons empty-icon">error_outline</span>
              <div class="empty-title">Unable to load email</div>
              <div class="empty-message">Please try again later</div>
            </div>
          `);
        }
      }
    });
  }
  
  // Authentication Modal
  function showAuthModal() {
    $('#auth-modal').addClass('active');
  }
  
  $('#auth-cancel').click(function() {
    $('#auth-modal').removeClass('active');
  });
  
  $('#auth-confirm').click(function() {
    window.location.href = "{{ route('admin.gmail.resetToken') }}";
  });
  
  // Logout from Gmail
  $('#logout-gmail-btn').click(function() {
    window.location.href = "{{ route('admin.gmail.logout') }}";
  });
  
  // Back to dashboard button
  $('#dashboard-btn').click(function() {
    window.location.href = "{{ route('admin.gmail.backToDashboard') }}";
  });
  
  // Form logout
  $('#logout-form').click(function(e) {
    e.preventDefault();
    $(this).submit();
  });
  
  // Contact search for compose
  $('#compose-to').on('input', function() {
    const query = $(this).val();
    if(query.length >= 2) {
      $.ajax({
        url: '{{ route("admin.gmail.getContacts") }}',
        type: 'GET',
        data: { query: query },
        success: function(contacts) {
          const dataList = $('#contactList');
          dataList.empty();
          
          $.each(contacts, function(i, contact) {
            dataList.append(`<option value="${contact.email}">${contact.name}</option>`);
          });
        }
      });
    }
  });
  
  // Tab navigation
  $('.email-tab').click(function() {
    $('.email-tab').removeClass('active');
    $(this).addClass('active');
    
    // Here you would load emails for the selected tab
  });
  
  // Category filter
  $('.filter-chip').click(function() {
    $('.filter-chip').removeClass('active');
    $(this).addClass('active');
    
    // Here you would filter emails based on the selected category
  });
  
  // Pagination
  $('.pagination-btn').click(function() {
    if($(this).hasClass('disabled')) return;
    
    // Here you would handle pagination
  });
  
  // Load initial emails
  loadEmails('inbox');
  
  // Handle window resize
  $(window).resize(function() {
    if (window.innerWidth > 768) {
      // Reset mobile views
      $('#email-list-column').removeClass('hidden');
      $('#gmail-sidebar').removeClass('active');
      $('#mobile-overlay').removeClass('active');
    }
  });
});
</script>
@endpush