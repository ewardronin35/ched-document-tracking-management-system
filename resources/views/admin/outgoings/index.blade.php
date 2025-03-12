@extends('layouts.app')

@section('content')

@push('styles')
  <!-- Fonts, Handsontable, and FilePond CSS -->

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
 /* Modern Excel Theme Variables */
:root {
  /* Core Excel Colors - Updated for Microsoft 365 look */
  --excel-primary: #0078d4;        /* Primary accent color */
  --excel-background: #ffffff;      /* Clean white background */
  --excel-header-bg: #f3f2f1;       /* Soft gray header background */
  --excel-border-color: #e1e1e1;    /* Lighter, more subtle borders */
  --excel-text-primary: #323130;    /* Dark gray text for readability */
  --excel-hover-bg: #f5f5f5;        /* Subtle hover state */

  --ched-primary: #234584;        /* Dark blue for header */
  --ched-secondary: #f8f9fa;      /* Light background */
  --ched-accent: #0078d4;         /* Accent blue for buttons/links */
  --ched-text: #ffffff;           /* White text for dark backgrounds */
  --ched-border: #d0d0d0;         /* Border color */
  
  /* Excel Ribbon - Updated with Office 365 palette */
  --excel-ribbon-bg: #f3f2f1;
  --excel-ribbon-border: #e1dfdd;
  --excel-ribbon-active: #0078d4;
  --excel-ribbon-text: #323130;
  --excel-ribbon-text-active: #ffffff;
  
  /* Excel Toolbar */
  --excel-toolbar-bg: #f3f2f1;
  --excel-toolbar-border: #e1dfdd;
  --excel-toolbar-button: #0078d4;
  --excel-toolbar-button-hover: #106ebe;
  
  /* Status Colors */
  --status-green: #e6f5d0;
  --status-yellow: #fff8d0;
  --status-red: #fbdfdf;
  --status-gray: #f0f0f0;
  
  /* Status Text Colors */
  --status-green-text: #107c10;
  --status-yellow-text: #986f0b;
  --status-red-text: #a80000;
  
  /* Font - Updated to match Excel more closely */
  --excel-font: 'Segoe UI', 'Calibri', sans-serif;
  --excel-font-size: 12px;

  /* Shadows */
  --excel-shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
  --excel-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.excel-grid {
  border: 1px solid var(--excel-border-color);
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  border-radius: 4px;
}

.excel-grid-header {
  background: linear-gradient(to bottom, #ffffff, var(--excel-header-bg));
  border-bottom: 1px solid var(--excel-border-color);
  font-weight: 600;
}

.excel-grid-row:hover {
  background-color: var(--excel-hover-bg);
  transition: background-color 0.2s ease;
}
body {
  font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  font-size: 14px;
  color: var(--excel-text-primary);
  line-height: 1.5;
}
/* Excel Workbook Container */
.excel-workbook {
  background-color: var(--excel-bg);
  border-radius: 2px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  margin-bottom: 20px;
  overflow: hidden;
  border: 1px solid #d0d0d0;
  display: flex;
    flex-direction: column;
    flex-grow: 1;
    overflow: hidden;
}
.excel-ribbon, .excel-subtabs {
    flex-shrink: 0;
}

/* Toolbar - Fixed height */
.excel-toolbar {
    flex-shrink: 0;
}

/* Excel Ribbon (Tabs) - Updated to match Office 365 */
.excel-ribbon {
  display: flex;
  background-color: var(--excel-ribbon-bg);
  border-bottom: 1px solid var(--excel-ribbon-border);
  padding: 0;
  overflow-x: auto;
  white-space: nowrap;
  scrollbar-width: thin;
  height: 36px;
}

.excel-ribbon::-webkit-scrollbar {
  height: 4px;
}

.excel-ribbon::-webkit-scrollbar-thumb {
  background-color: #c1c1c1;
  border-radius: 2px;
}

.excel-ribbon .nav-item {
  margin: 0;
}

.excel-ribbon .nav-link {
  color: var(--excel-ribbon-text);
  padding: 8px 14px;
  border: none;
  border-bottom: 2px solid transparent;
  border-radius: 0;
  font-size: 13px;
  font-weight: 500;
  transition: all 0.2s ease;
  position: relative;
  margin: 0;
  display: flex;
  align-items: center;
  height: 36px;
}

.excel-ribbon .nav-link i {
  margin-right: 6px;
  font-size: 14px;
}

.excel-ribbon .nav-link.active {
  color: var(--excel-ribbon-active);
  background-color: var(--excel-bg);
  border-bottom: 2px solid var(--excel-ribbon-active);
}

.excel-ribbon .nav-link:hover:not(.active) {
  background-color: var(--excel-header-hover);
}

/* Excel Subtabs */
.excel-subtabs {
  background-color: var(--excel-toolbar-bg);
  border-bottom: 1px solid var(--excel-toolbar-border);
  padding: 0;
  overflow-x: auto;
  white-space: nowrap;
  scrollbar-width: thin;
  margin-bottom: 0;
  height: 32px;
}

.excel-subtabs::-webkit-scrollbar {
  height: 4px;
}

.excel-subtabs::-webkit-scrollbar-thumb {
  background-color: #c1c1c1;
  border-radius: 2px;
}

.excel-subtabs .nav-link {
  color: #505050;
  padding: 6px 12px;
  font-size: 12px;
  border: none;
  border-bottom: 2px solid transparent;
  border-radius: 0;
  transition: all 0.2s ease;
  height: 32px;
}

.excel-subtabs .nav-link.active {
  color: var(--excel-ribbon-active);
  border-bottom: 2px solid var(--excel-ribbon-active);
  background-color: transparent;
  font-weight: 500;
}

.excel-subtabs .nav-link:hover:not(.active) {
  background-color: var(--excel-header-hover);
}

.excel-subtabs .nav-link i {
  margin-right: 4px;
  font-size: 12px;
}

/* Excel Toolbar - Updated to look more like Excel's toolbar */
.excel-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: var(--excel-toolbar-bg);
  border-bottom: 1px solid var(--excel-toolbar-border);
  padding: 6px 12px;
  flex-wrap: wrap;
  gap: 8px;
  position: sticky;
  top: 0;
  z-index: 10;
  min-height: 40px;
}

.toolbar-title {
  display: flex;
  align-items: center;
}

.toolbar-title h6 {
  font-size: 14px;
  font-weight: 600;
  color: #323130;
  margin: 0;
  white-space: nowrap;
}

.toolbar-title i {
  color: #0078d4;
  margin-right: 8px;
}

.toolbar-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

/* Search Box - Improved to match Excel's search box */
.excel-search-wrapper {
  position: relative;
  flex-grow: 1;
  max-width: 320px;
  border: 1px solid var(--excel-header-border);
  border-radius: 2px;
  background-color: white;
  transition: all 0.2s ease;
}

.excel-search-wrapper:focus-within {
  border-color: var(--excel-ribbon-active);
  box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.2);
}

.excel-search-wrapper i {
  position: absolute;
  left: 8px;
  top: 50%;
  transform: translateY(-50%);
  color: #666;
  font-size: 14px;
}

.excel-search {
  padding: 6px 8px 6px 28px;
  border: none;
  font-size: 12px;
  width: 100%;
  transition: all 0.2s ease;
  background-color: transparent;
}

.excel-search:focus {
  outline: none;
}

/* Filter Dropdown - Updated to match Excel's filter dropdowns */
.excel-filter {
  padding: 6px 28px 6px 8px;
  border: 1px solid var(--excel-header-border);
  border-radius: 2px;
  font-size: 12px;
  background-color: white;
  min-width: 140px;
  transition: all 0.2s ease;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 8px center;
  background-size: 12px;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  cursor: pointer;
}

.excel-filter:focus {
  border-color: var(--excel-ribbon-active);
  box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.2);
  outline: none;
}

/* Excel Table Container */
.excel-table-container {
  position: relative;
  height: 700px;
  max-height: 700px;
  overflow: auto;
  background-color: #fff;
  border-radius: 0 0 2px 2px;
  border-top: none;
  box-shadow: var(--excel-shadow-sm);
  flex-grow: 1;
    overflow-y: auto;
    overflow-x: auto;
    position: relative;
}

/* HandsOnTable Styling - Enhanced to look more like Excel */
.handsontable .ht_master .htCore {
  border-collapse: separate;
  border-spacing: 0;
}

.handsontable .ht_master .htCore td {
  border-right: 1px solid var(--excel-cell-border);
  border-bottom: 1px solid var(--excel-cell-border);
  padding: 1px 4px;
  font-family: var(--excel-font) !important;
  font-size: var(--excel-font-size);
  height: 21px; /* Excel's default row height */
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  vertical-align: middle;
  background-clip: padding-box;
}

.handsontable .ht_master .htCore tr th {
  background-color: var(--excel-header-bg);
  background-image: linear-gradient(to bottom, #f8f8f8, #f0f0f0);
  border-right: 1px solid var(--excel-header-border);
  border-bottom: 1px solid var(--excel-header-border);
  padding: 4px 6px;
  font-weight: 600;
  font-size: 12px;
  color: #444;
  position: relative;
  text-align: left;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  user-select: none;
  height: 24px;
}

.handsontable .ht_master .htCore th:hover {
  background-image: linear-gradient(to bottom, #f0f0f0, #e8e8e8);
}

/* Excel-like column headers with filter icons */
.handsontable .ht_master .htCore th .columnSorting {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.handsontable .ht_master .htCore th .columnSorting::after {
  content: '';
  display: inline-block;
  width: 14px;
  height: 14px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23777' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3'%3E%3C/polygon%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-size: contain;
  opacity: 0.7;
  margin-left: 4px;
}

.handsontable .ht_master .htCore th .columnSorting.ascending::after {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%230078d4' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3'%3E%3C/polygon%3E%3C/svg%3E");
  opacity: 1;
}

.handsontable .ht_master .htCore th .columnSorting.descending::after {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%230078d4' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3'%3E%3C/polygon%3E%3C/svg%3E");
  transform: rotate(180deg);
  opacity: 1;
}

/* Row striping - Very subtle like in Excel */
.handsontable .ht_master .htCore tbody tr:nth-child(even) {
  background-color: rgba(245, 245, 245, 0.2);
}

/* Hover effect on rows */
.handsontable .ht_master .htCore tbody tr:hover {
  background-color: var(--excel-hover-bg);
}

/* Selected cells - Updated to match Excel's selection style */
.handsontable .ht_master .htCore td.current {
  background-color: var(--excel-selected-bg) !important;
  border: 1px solid var(--excel-selected-border) !important;
}

/* Selection handle (the little blue square in the corner) */
.handsontable .ht_master .htCore td.current .selection-handle {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 6px;
  height: 6px;
  background-color: var(--excel-selected-handle);
  border: 1px solid white;
}

/* Selection area */
.handsontable .area.fill {
  background-color: rgba(0, 120, 212, 0.1);
  border: 1px dashed rgba(0, 120, 212, 0.5);
}

/* Status Row Colors */
.green-row, .released-row {
  background-color: var(--status-green) !important;
}

.yellow-row {
  background-color: var(--status-yellow) !important;
}

.red-row {
  background-color: var(--status-red) !important;
}

.gray-row {
  background-color: var(--status-gray) !important;
}

/* Highlighted Row */
.highlightedRow {
  background-color: var(--excel-selected-bg) !important;
  font-weight: 500;
}

/* Quarter Label Cell */
.htQuarterLabel {
  font-weight: 600;
  background-color: rgba(0, 120, 212, 0.08);
  color: #0078d4;
}

/* Excel Action Buttons - Styled more like Excel's buttons */
.excel-button {
  background-color: var(--excel-primary);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  transition: background-color 0.2s ease;
}

.excel-container {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 100px); /* Adjust based on your header/navbar height */
    max-height: 100vh;
    overflow: hidden;
}


.excel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 15px;
  background-color: var(--excel-header-bg);
  border-bottom: 1px solid var(--excel-border-color);
}
.excel-button:hover {
  background-color: #f0f0f0;
}

.excel-button:active {
  background-color: #e0e0e0;
}

.excel-button i {
  margin-right: 4px;
  font-size: 12px;
}

.excel-button-primary {
  background-color: var(--excel-toolbar-button);
  border-color: var(--excel-toolbar-button);
  color: white;
}

.excel-button-primary:hover {
  background-color: var(--excel-toolbar-button-hover);
  border-color: var(--excel-toolbar-button-hover);
}

.excel-button-primary:active {
  background-color: #005a9e;
  border-color: #005a9e;
}

.release-btn {
  padding: 2px 6px;
  font-size: 11px;
  background-color: var(--excel-toolbar-button);
  border-color: var(--excel-toolbar-button);
  color: white;
  border-radius: 2px;
  transition: all 0.2s ease;
}

.release-btn:hover {
  background-color: var(--excel-toolbar-button-hover);
  border-color: var(--excel-toolbar-button-hover);
}

/* Excel Card Design */
.excel-card {
  background-color: #fff;
  border-radius: 2px;
  box-shadow: var(--excel-shadow);
  overflow: hidden;
  margin-bottom: 16px;
  border: 1px solid var(--excel-header-border);
}

.excel-card-header {
  background-color: var(--excel-header-bg);
  background-image: linear-gradient(to bottom, #f8f8f8, #f0f0f0);
  padding: 10px 14px;
  border-bottom: 1px solid var(--excel-header-border);
}

.excel-card-header h6 {
  margin: 0;
  font-weight: 600;
  font-size: 13px;
  color: #333;
}

.excel-card-body {
  padding: 14px;
}

/* FilePond Styling */
.filepond--root {
  font-family: var(--excel-font);
  margin: 16px 0;
}

.filepond--panel-root {
  border-radius: 2px;
  background-color: #f9f9f9;
  border: 1px dashed #ccc;
}

.filepond--drop-label {
  color: #555;
}

/* Excel Column Header with Sort Indicators */
.excel-column-header {
  position: relative;
  padding-right: 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  height: 100%;
}

.excel-column-header-text {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.excel-column-header-icons {
  display: flex;
  align-items: center;
}

.excel-column-header-sort {
  width: 8px;
  height: 12px;
  position: relative;
  margin-right: 4px;
}

.excel-column-header-sort::before,
.excel-column-header-sort::after {
  content: '';
  position: absolute;
  left: 0;
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
}

.excel-column-header-sort::before {
  top: 0;
  border-bottom: 4px solid #bbb;
}

.excel-column-header-sort::after {
  bottom: 0;
  border-top: 4px solid #bbb;
}

.excel-column-header-sort.sort-asc::before {
  border-bottom-color: #0078d4;
}

.excel-column-header-sort.sort-desc::after {
  border-top-color: #0078d4;
}

.excel-column-header-filter {
  width: 12px;
  height: 12px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23777' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3'%3E%3C/polygon%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
  background-size: contain;
  opacity: 0.7;
}

.excel-column-header-filter.filtered {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%230078d4' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3'%3E%3C/polygon%3E%3C/svg%3E");
  opacity: 1;
}

/* Excel Scrollbars - Updated to look more like Excel's scrollbars */
.excel-table-container::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

.excel-table-container::-webkit-scrollbar-track {
  background: #f7f7f7;
  border-left: 1px solid #e0e0e0;
  border-top: 1px solid #e0e0e0;
}

.excel-table-container::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border: 2px solid #f7f7f7;
  border-radius: 5px;
}

.excel-table-container::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}

.excel-table-container::-webkit-scrollbar-corner {
  background: #f7f7f7;
  border-left: 1px solid #e0e0e0;
  border-top: 1px solid #e0e0e0;
}

/* Excel Status Bar - New component to match Excel's status bar */
.excel-status-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: var(--excel-toolbar-bg);
  border-top: 1px solid var(--excel-toolbar-border);
  padding: 2px 8px;
  font-size: 11px;
  color: #666;
  height: 22px;
  flex-shrink: 0;

}

.excel-status-bar-left {
  display: flex;
  align-items: center;
}

.excel-status-bar-right {
  display: flex;
  align-items: center;
}

.excel-status-bar-item {
  padding: 0 8px;
  border-right: 1px solid #e0e0e0;
}

.excel-status-bar-item:last-child {
  border-right: none;
}

.excel-zoom-controls {
  display: flex;
  align-items: center;
}

.excel-zoom-btn {
  width: 18px;
  height: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: transparent;
  border: none;
  cursor: pointer;
  color: #666;
  font-size: 11px;
}

.excel-zoom-btn:hover {
  background-color: #e0e0e0;
}

.excel-zoom-value {
  margin: 0 4px;
}

/* Excel Tooltip */
.excel-tooltip {
  position: absolute;
  background-color: #f5f5f5;
  color: #333;
  border: 1px solid #d0d0d0;
  border-radius: 2px;
  padding: 4px 8px;
  font-size: 11px;
  z-index: 9999;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  pointer-events: none;
  max-width: 300px;
  white-space: normal;
}

/* Excel Status Indicators - Updated to look more like Excel's conditional formatting */
.excel-status {
  display: inline-block;
  padding: 1px 4px;
  border-radius: 2px;
  font-size: 11px;
  font-weight: 500;
}

.excel-status-green {
  background-color: var(--status-green);
  color: var(--status-green-text);
}

.excel-status-yellow {
  background-color: var(--status-yellow);
  color: var(--status-yellow-text);
}

.excel-status-red {
  background-color: var(--status-red);
  color: var(--status-red-text);
}

/* Excel Sheet Tabs - New component to simulate Excel's sheet tabs */
.excel-sheet-tabs {
  display: flex;
  align-items: center;
  background-color: #e9e9e9;
  border-top: 1px solid #d0d0d0;
  height: 24px;
  overflow-x: auto;
  white-space: nowrap;
  scrollbar-width: none;
}

.excel-sheet-tabs::-webkit-scrollbar {
  display: none;
}

.excel-sheet-tab {
  display: flex;
  align-items: center;
  height: 22px;
  padding: 0 12px;
  background-color: #e3e3e3;
  border-right: 1px solid #d0d0d0;
  font-size: 11px;
  color: #333;
  cursor: pointer;
  min-width: 80px;
  max-width: 120px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.excel-sheet-tab.active {
  background-color: #fff;
  font-weight: 600;
  border-bottom: 2px solid #0078d4;
}

.excel-sheet-tab:hover:not(.active) {
  background-color: #d7d7d7;
}

.excel-sheet-tab i {
  margin-right: 4px;
  font-size: 11px;
  color: #555;
}

/* Excel Formula Bar - New component to simulate Excel's formula bar */
.excel-formula-bar {
  display: flex;
  align-items: center;
  background-color: #f9f9f9;
  border-bottom: 1px solid #d0d0d0;
  height: 25px;
  padding: 0 8px;
}

.excel-name-box {
  width: 80px;
  height: 20px;
  border: 1px solid #c1c1c1;
  font-size: 11px;
  padding: 0 4px;
  margin-right: 8px;
  display: flex;
  align-items: center;
  background-color: white;
}

.excel-formula-input {
  flex-grow: 1;
  height: 20px;
  border: 1px solid #c1c1c1;
  font-size: 11px;
  padding: 0 4px;
  display: flex;
  align-items: center;
  background-color: white;
}

.excel-formula-label {
  margin-right: 4px;
  font-size: 12px;
  color: #666;
  font-weight: 500;
}

/* Loading indicator */
.excel-loading {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  flex-direction: column;
}

.excel-loading-spinner {
  width: 32px;
  height: 32px;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #0078d4;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 8px;
}

.excel-loading-text {
  font-size: 13px;
  color: #333;
  font-weight: 500;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .excel-toolbar {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .toolbar-actions {
    width: 100%;
    margin-top: 8px;
  }
  
  .excel-search-wrapper {
    max-width: 100%;
    width: 100%;
  }
  
  .excel-filter {
    width: 100%;
  }
  
  .excel-ribbon .nav-link {
    padding: 6px 10px;
    font-size: 12px;
  }
  
  .excel-ribbon .nav-link i {
    margin-right: 4px;
  }
}

/* Performance optimization */
.handsontable {
  will-change: transform;
}

.excel-table-container {
  contain: content;
}
/* Add to your existing style section */
.search-highlight {
  background-color: rgba(255, 240, 105, 0.5) !important;
  font-weight: bold;
}

/* Add a search results counter near your search inputs */
.search-counter {
  font-size: 12px;
  color: #555;
  margin-left: 8px;
}
/* Reports Section Styles */

/* Report Card */
.excel-card {
  transition: all 0.3s ease;
  overflow: hidden;
}

.excel-card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Filter Form */
#report-filter-form .form-label {
  font-weight: 600;
  color: #444;
  font-size: 0.9rem;
}

#report-filter-form .form-select,
#report-filter-form .form-control {
  border-color: #d0d0d0;
  border-radius: 4px;
  transition: all 0.2s ease;
}

#report-filter-form .form-select:focus,
#report-filter-form .form-control:focus {
  border-color: var(--excel-ribbon-active);
  box-shadow: 0 0 0 3px rgba(0, 120, 212, 0.1);
}

/* Report Buttons */
#generate-report-btn,
#export-report-btn {
  transition: all 0.2s ease;
  border-radius: 4px;
  font-weight: 500;
}

#generate-report-btn {
  background-color: var(--excel-toolbar-button);
  border-color: var(--excel-toolbar-button);
}

#generate-report-btn:hover {
  background-color: var(--excel-toolbar-button-hover);
  border-color: var(--excel-toolbar-button-hover);
}

#export-report-btn {
  background-color: #6c757d;
  border-color: #6c757d;
}

#export-report-btn:hover {
  background-color: #5a6268;
  border-color: #545b62;
}

/* Chart Container */
#quarterly-chart-container {
  background-color: #fff;
  border-radius: 4px;
  transition: all 0.3s ease;
}

/* Report Wrapper */
.report-wrapper {
  padding: 15px;
}

/* Chart Tabs */
.report-wrapper .nav-tabs {
  border-bottom: 1px solid #dee2e6;
}

  </style>
@endpush

@section('content')
<div class="excel-container">
  <div class="excel-workbook">
    <!-- Main Excel Ribbon Tabs -->
  
    <ul class="nav nav-tabs excel-ribbon" id="documentTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="incomings-tab" data-bs-toggle="tab" href="#incomings" role="tab" aria-controls="incomings" aria-selected="true">
          <i class="fas fa-inbox"></i> Incomings
        </a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="outgoings-tab" data-bs-toggle="tab" href="#outgoings" role="tab" aria-controls="outgoings" aria-selected="false">
          <i class="fas fa-paper-plane"></i> Outgoings
        </a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="imports-tab" data-bs-toggle="tab" href="#imports" role="tab" aria-controls="imports" aria-selected="false">
          <i class="fas fa-upload"></i> Imports
        </a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="reports-tab" data-bs-toggle="tab" href="#reports" role="tab" aria-controls="reports" aria-selected="false">
          <i class="fas fa-chart-line"></i> Reports
        </a>
      </li>
    </ul>

    <div class="tab-content" id="documentTabsContent">
      <!-- Incomings Tab -->
      <div class="tab-pane fade show active" id="incomings" role="tabpanel" aria-labelledby="incomings-tab">
        
      <div class="excel-toolbar">
        <div class="toolbar-title">
          <i class="fas fa-inbox"></i>
          <h6 class="mb-0">Incoming Documents</h6>
        </div>
        <div class="toolbar-actions">
          <div class="excel-search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search-incomings" class="excel-search" placeholder="Search incomings...">
          </div>
          <select class="excel-filter" id="filter-incomings-status">
            <option value="all">All Status</option>
            <option value="released">Released</option>
            <option value="pending">Pending</option>
          </select>
          <button class="excel-button">
            <i class="fas fa-sort"></i> Sort
          </button>
          <button class="excel-button" id="advanced-filter-btn">
          <i class="fas fa-filter"></i> Advanced Filter
          </button>
        </div>
      </div>
      
      <div class="excel-table-container">
        <div id="handsontable-incomings" wire:ignore></div>
      </div>
      
      <!-- Excel Status Bar -->
      <div class="excel-status-bar">
        <div class="excel-status-bar-left">
          <div class="excel-status-bar-item">
            <span id="selection-count">0</span> of <span id="total-rows">0</span> records selected
          </div>
          <div class="excel-status-bar-item">
            <span id="filtered-count">0</span> records filtered
          </div>
        </div>
        <div class="excel-status-bar-right">
          <div class="excel-status-bar-item excel-zoom-controls">
            <button class="excel-zoom-btn" id="zoom-out"><i class="fas fa-minus"></i></button>
            <span class="excel-zoom-value">100%</span>
            <button class="excel-zoom-btn" id="zoom-in"><i class="fas fa-plus"></i></button>
          </div>
        </div>
      </div>
    </div>

      <!-- Outgoings Tab -->
      <div class="tab-pane fade" id="outgoings" role="tabpanel" aria-labelledby="outgoings-tab">
        <!-- Sub-tabs as navigation tabs with Excel styling -->
        <ul class="nav nav-tabs excel-subtabs" id="outgoingsSubTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="all-outgoings-tab" data-bs-toggle="pill" href="#all-outgoings" role="tab" aria-controls="all-outgoings" aria-selected="true">
              <i class="fas fa-list"></i> All Outgoings
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="travel-memo-tab" data-bs-toggle="pill" href="#travel-memo" role="tab" aria-controls="travel-memo" aria-selected="false">
              <i class="fas fa-plane"></i> Travel Memo
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="ono-tab" data-bs-toggle="pill" href="#ono" role="tab" aria-controls="ono" aria-selected="false">
              <i class="fas fa-file-alt"></i> O No. DATE OF RELEASED
            </a>
          </li>
        </ul>
        
        <div class="tab-content" id="outgoingsSubTabsContent">
          <!-- All Outgoings Sub-tab -->
          <div class="tab-pane fade show active" id="all-outgoings" role="tabpanel" aria-labelledby="all-outgoings-tab">
            <div class="excel-toolbar">
              <div class="toolbar-title">
                <i class="fas fa-paper-plane"></i>
                <h6 class="mb-0">Outgoing Documents</h6>
              </div>
              <div class="toolbar-actions">
                <div class="excel-search-wrapper">
                  <i class="fas fa-search"></i>
                  <input type="text" id="search-outgoings" class="excel-search" placeholder="Search outgoings...">
                </div>
                <select class="excel-filter" id="filter-outgoings-category">
                  <option value="all">All Categories</option>
                  <option value="TRAVEL ORDER">Travel Order</option>
                  <option value="ONO">O No.</option>
                  <option value="RMO">RMO</option>
                  <option value="MEMO-ORD">MEMO-ORD</option>
                </select>
                <button class="excel-button excel-button-primary">
                  <i class="fas fa-sort"></i> Sort
                </button>
              </div>
            </div>
            <div class="excel-table-container" id="handsontable-outgoings" wire:ignore></div>
          </div>
          
          <!-- Travel Memo Sub-tab -->
          <div class="tab-pane fade" id="travel-memo" role="tabpanel" aria-labelledby="travel-memo-tab">
            <div class="excel-toolbar">
              <div class="toolbar-title">
                <i class="fas fa-plane"></i>
                <h6 class="mb-0">Travel Memo</h6>
              </div>
              <div class="toolbar-actions">
                <div class="excel-search-wrapper">
                  <i class="fas fa-search"></i>
                  <input type="text" id="search-travel-memo" class="excel-search" placeholder="Search travel memo...">
                </div>
                <button class="excel-button">
                  <i class="fas fa-print"></i> Print
                </button>
              </div>
            </div>
            <div class="excel-table-container" id="handsontable-travel-memo" wire:ignore></div>
          </div>
          
          <!-- O No. Sub-tab -->
          <div class="tab-pane fade" id="ono" role="tabpanel" aria-labelledby="ono-tab">
            <div class="excel-toolbar">
              <div class="toolbar-title">
                <i class="fas fa-file-alt"></i>
                <h6 class="mb-0">O No. DATE OF RELEASED</h6>
              </div>
              <div class="toolbar-actions">
                <div class="excel-search-wrapper">
                  <i class="fas fa-search"></i>
                  <input type="text" id="search-ono" class="excel-search" placeholder="Search O No....">
                </div>
                <button class="excel-button">
                  <i class="fas fa-download"></i> Export
                </button>
              </div>
            </div>
            <div class="excel-table-container" id="handsontable-ono" wire:ignore></div>
          </div>
        </div>
      </div>

      <!-- Imports Tab -->
      <div class="tab-pane fade" id="imports" role="tabpanel" aria-labelledby="imports-tab">
        <ul class="nav nav-tabs excel-subtabs" id="importsSubTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="incoming-import-tab" data-bs-toggle="tab" href="#incoming-import" role="tab" aria-controls="incoming-import" aria-selected="true">
              <i class="fas fa-file-import"></i> Incoming Import
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="outgoing-import-tab" data-bs-toggle="tab" href="#outgoing-import" role="tab" aria-controls="outgoing-import" aria-selected="false">
              <i class="fas fa-file-export"></i> Outgoing Import
            </a>
          </li>
        </ul>
        
        <div class="tab-content" id="importsSubTabsContent">
          <!-- Incoming Import Subtab -->
          <div class="tab-pane fade show active p-3" id="incoming-import" role="tabpanel" aria-labelledby="incoming-import-tab">
            <div class="excel-card">
              <div class="excel-card-header">
                <h6><i class="fas fa-file-import me-2"></i> Import Incoming Excel Files</h6>
              </div>
              <div class="excel-card-body">
                <p class="text-muted small mb-3">Supported formats: CSV, XLS, XLSX</p>
                <input type="file" class="filepond" name="incoming_filepond" id="incoming-filepond" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
              </div>
            </div>
          </div>
          
          <!-- Outgoing Import Subtab -->
          <div class="tab-pane fade p-3" id="outgoing-import" role="tabpanel" aria-labelledby="outgoing-import-tab">
            <div class="excel-card">
              <div class="excel-card-header">
                <h6><i class="fas fa-file-export me-2"></i> Import Outgoing Excel Files</h6>
              </div>
              <div class="excel-card-body">
                <p class="text-muted small mb-3">Supported formats: CSV, XLS, XLSX</p>
                <input type="file" class="filepond" name="outgoing_filepond" id="outgoing-filepond" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
              </div>
            </div>
          </div>
        </div>
      </div>
<!-- Reports Tab -->
<!-- Replace the Reports Tab HTML with this -->
<div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
  <div class="excel-toolbar">
    <div class="toolbar-title">
      <i class="fas fa-chart-line"></i>
      <h6 class="mb-0">Outgoing and Incoming Reports</h6>
    </div>
  </div>
  
  <div class="excel-card mb-4">
    <div class="excel-card-header">
      <h6><i class="fas fa-filter me-2"></i> Report Filters</h6>
    </div>
    <div class="excel-card-body">
      <form id="report-filter-form" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Quarter</label>
          <select class="form-select" id="report-quarter">
            <option value="all">All Quarters</option>
            <option value="1">Q1 (Jan-Mar)</option>
            <option value="2">Q2 (Apr-Jun)</option>
            <option value="3">Q3 (Jul-Sep)</option>
            <option value="4">Q4 (Oct-Dec)</option>
          </select>
        </div>
        
        <div class="col-md-6">
          <label class="form-label">Document Type</label>
          <select class="form-select" id="report-doc-type">
            <option value="all">All Documents</option>
            <option value="incoming">Incoming Only</option>
            <option value="outgoing">Outgoing Only</option>
          </select>
        </div>
        
        <div class="col-md-6">
          <label class="form-label">Chart View</label>
          <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="chart-view" id="chart-view-quarter" value="quarter" checked>
            <label class="btn btn-outline-primary" for="chart-view-quarter">Quarterly</label>
            
            <input type="radio" class="btn-check" name="chart-view" id="chart-view-month" value="month">
            <label class="btn btn-outline-primary" for="chart-view-month">Monthly</label>
          </div>
        </div>


        <div class="col-md-6">
          <label class="form-label">Export Format</label>
          <div class="d-flex gap-3">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="export-format" id="export-pdf" value="pdf" checked>
              <label class="form-check-label" for="export-pdf">PDF</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="export-format" id="export-excel" value="excel">
              <label class="form-check-label" for="export-excel">Excel</label>
            </div>
          </div>
        </div>
        
        <div class="col-12 mt-4">
  <button type="button" id="generate-report-btn" class="btn btn-primary">
    <i class="fas fa-sync me-2"></i>Generate Report
  </button>
  <button type="button" id="export-report-btn" class="btn btn-secondary ms-2">
    <i class="fas fa-download me-2"></i>Export Report
  </button>
</div>
      </form>
    </div>
  </div>
  
  
    <div class="excel-card">
    <div class="excel-card-header d-flex justify-content-between align-items-center">
      <h6><i class="fas fa-chart-pie me-2"></i> Document Distribution</h6>
      <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-outline-primary active" id="chart-type-bar">
          <i class="fas fa-chart-bar"></i>
        </button>
        <button type="button" class="btn btn-outline-primary" id="chart-type-pie">
          <i class="fas fa-chart-pie"></i>
        </button>
        <button type="button" class="btn btn-outline-primary" id="chart-type-line">
          <i class="fas fa-chart-line"></i>
        </button>
      </div>
    </div>
    <div class="excel-card-body">
      <div id="quarterly-chart-container" style="height: 400px; width: 100%;">
        <!-- Chart will be rendered here -->
      </div>
    </div>
  </div>
  <!-- Chart Container -->
  <div class="row mt-4">
    <div class="col-md-4">
      <div class="excel-card">
        <div class="excel-card-header bg-primary text-white">
          <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i> Total Documents</h6>
        </div>
        <div class="excel-card-body text-center">
          <h2 id="total-docs-count">-</h2>
          <p class="text-muted">All processed documents</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="excel-card">
        <div class="excel-card-header bg-warning text-dark">
          <h6 class="mb-0"><i class="fas fa-inbox me-2"></i> Incoming Documents</h6>
        </div>
        <div class="excel-card-body text-center">
          <h2 id="incoming-docs-count">-</h2>
          <p class="text-muted">Documents received</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="excel-card">
        <div class="excel-card-header bg-success text-white">
          <h6 class="mb-0"><i class="fas fa-paper-plane me-2"></i> Outgoing Documents</h6>
        </div>
        <div class="excel-card-body text-center">
          <h2 id="outgoing-docs-count">-</h2>
          <p class="text-muted">Documents sent</p>
        </div>
      </div>
    </div>
  </div>

  <div class="excel-card mt-4">
    <div class="excel-card-header">
      <h6><i class="fas fa-tags me-2"></i> Document Categories</h6>
    </div>
    <div class="excel-card-body">
      <div class="table-responsive">
        <table class="table table-sm table-striped">
          <thead>
            <tr>
              <th>Category</th>
              <th class="text-end">Count</th>
              <th class="text-end">Percentage</th>
              <th>Distribution</th>
            </tr>
          </thead>
          <tbody id="categories-table-body">
            <tr>
              <td colspan="4" class="text-center">Generate a report to see category breakdown</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Loading Indicator -->
  <div class="excel-loading" id="report-loading-indicator" style="display: none;">
    <div class="excel-loading-spinner"></div>
    <div class="excel-loading-text">Generating report...</div>
  </div>
</div>
  <!-- Advanced Filter Modal -->
  <div class="modal fade" id="advancedFilterModal" tabindex="-1" aria-labelledby="advancedFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="advancedFilterModalLabel">Advanced Filter</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="advanced-filter-form">
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Document Type</label>
                <select class="form-select" id="filter-doc-type" multiple>
                  <option value="TRAVEL ORDER">Travel Order</option>
                  <option value="ONO">O No.</option>
                  <option value="RMO">RMO</option>
                  <option value="MEMO-ORD">MEMO-ORD</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-select" id="filter-status" multiple>
                  <option value="Released">Released</option>
                  <option value="Pending">Pending</option>
                  <option value="Canceled">Canceled</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label class="form-label">Date Range</label>
                <div class="d-flex gap-2">
                  <input type="date" class="form-control" id="filter-start-date">
                  <span class="align-self-center">to</span>
                  <input type="date" class="form-control" id="filter-end-date">
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Keyword Search</label>
                <input type="text" class="form-control" id="filter-keyword" placeholder="Search in subjects, remarks...">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="apply-advanced-filter">Apply Filter</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="excel-loading" id="loading-indicator" style="display: none;">
  <div class="excel-loading-spinner"></div>
  <div class="excel-loading-text">Loading data...</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


 <!-- FilePond JS -->
 <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
  <!-- FilePond plugin for file type validation -->
  <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
<script>
  // Add this snippet to your JavaScript after the createEnhancedReportVisualization function
document.addEventListener('DOMContentLoaded', function() {
  // Chart type toggle buttons
  const chartTypeBar = document.getElementById('chart-type-bar');
  const chartTypePie = document.getElementById('chart-type-pie');
  const chartTypeLine = document.getElementById('chart-type-line');
  
  let currentChartInstance = null;
  
  if (chartTypeBar && chartTypePie && chartTypeLine) {
    // Bar chart (default)
    chartTypeBar.addEventListener('click', function() {
      setActiveChartButton(this);
      if (currentChartInstance) {
        currentChartInstance.destroy();
      }
      
      const quarterlyChartContainer = document.getElementById('quarterly-chart-container');
      quarterlyChartContainer.innerHTML = '<canvas id="quarterlyBarChart"></canvas>';
      
      const ctx = document.getElementById('quarterlyBarChart').getContext('2d');
      currentChartInstance = new Chart(ctx, {
        type: 'bar',
        data: getCurrentChartData(),
        options: getBarChartOptions()
      });
    });
    
    // Pie chart
    chartTypePie.addEventListener('click', function() {
      setActiveChartButton(this);
      if (currentChartInstance) {
        currentChartInstance.destroy();
      }
      
      const quarterlyChartContainer = document.getElementById('quarterly-chart-container');
      quarterlyChartContainer.innerHTML = '<canvas id="quarterlyPieChart"></canvas>';
      
      const ctx = document.getElementById('quarterlyPieChart').getContext('2d');
      currentChartInstance = new Chart(ctx, {
        type: 'pie',
        data: getPieChartData(),
        options: getPieChartOptions()
      });
    });
    
    // Line chart
    chartTypeLine.addEventListener('click', function() {
      setActiveChartButton(this);
      if (currentChartInstance) {
        currentChartInstance.destroy();
      }
      
      const quarterlyChartContainer = document.getElementById('quarterly-chart-container');
      quarterlyChartContainer.innerHTML = '<canvas id="quarterlyLineChart"></canvas>';
      
      const ctx = document.getElementById('quarterlyLineChart').getContext('2d');
      currentChartInstance = new Chart(ctx, {
        type: 'line',
        data: getCurrentChartData(),
        options: getLineChartOptions()
      });
    });
  }
  
  // Helper functions
  function setActiveChartButton(activeButton) {
    [chartTypeBar, chartTypePie, chartTypeLine].forEach(btn => {
      if (btn) btn.classList.remove('active');
    });
    activeButton.classList.add('active');
  }
  
  function getCurrentChartData() {
    // Get data from the global report data or use placeholder data
    const incomingCounts = window.reportData?.incomingCounts || [10, 15, 20, 25];
    const outgoingCounts = window.reportData?.outgoingCounts || [8, 12, 18, 22];
    const labels = window.reportData?.byQuarter ? ['Q1', 'Q2', 'Q3', 'Q4'] : 
                  ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    return {
      labels: labels,
      datasets: [
        {
          label: 'Incoming Documents',
          backgroundColor: 'rgba(0, 120, 212, 0.6)',
          borderColor: 'rgb(0, 120, 212)',
          borderWidth: 1,
          data: incomingCounts
        },
        {
          label: 'Outgoing Documents',
          backgroundColor: 'rgba(232, 113, 15, 0.6)',
          borderColor: 'rgb(232, 113, 15)',
          borderWidth: 1,
          data: outgoingCounts
        }
      ]
    };
  }
  
  // Replace the getPieChartData function with this improved version
function getPieChartData() {
  // Ensure we have valid reportData
  if (!window.reportData) {
    console.warn('No report data available');
    return {
      labels: ['No Data'],
      datasets: [{
        data: [1],
        backgroundColor: ['#cccccc']
      }]
    };
  }

  // Get total counts for incoming and outgoing
  const incomingTotal = Array.isArray(window.reportData.incomingCounts) ? 
    window.reportData.incomingCounts.reduce((sum, val) => sum + val, 0) : 0;
  
  const outgoingTotal = Array.isArray(window.reportData.outgoingCounts) ?
    window.reportData.outgoingCounts.reduce((sum, val) => sum + val, 0) : 0;
  
  // Ensure we have data to display
  if (incomingTotal === 0 && outgoingTotal === 0) {
    return {
      labels: ['No Data'],
      datasets: [{
        data: [1],
        backgroundColor: ['#cccccc']
      }]
    };
  }

  // For pie charts, we need a different data structure
  return {
    labels: ['Incoming Documents', 'Outgoing Documents'],
    datasets: [{
      data: [incomingTotal, outgoingTotal],
      backgroundColor: [
        'rgba(0, 120, 212, 0.7)',  // Blue for incoming
        'rgba(232, 113, 15, 0.7)'  // Orange for outgoing
      ],
      borderColor: [
        'rgb(0, 120, 212)',
        'rgb(232, 113, 15)'
      ],
      borderWidth: 1
    }]
  };
}
  
  function getBarChartOptions() {
    return {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Documents'
          }
        },
        x: {
          title: {
            display: true,
            text: window.reportData?.byQuarter ? 'Quarter' : 'Month'
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: `Document Distribution (${window.reportData?.year || new Date().getFullYear()})`
        }
      }
    };
  }
  
  function getLineChartOptions() {
    return {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Documents'
          }
        },
        x: {
          title: {
            display: true,
            text: window.reportData?.byQuarter ? 'Quarter' : 'Month'
          }
        }
      },
      plugins: {
        legend: {
          position: 'top'
        },
        title: {
          display: true,
          text: `Document Trends (${window.reportData?.year || new Date().getFullYear()})`
        }
      }
    };
  }
  
  function getPieChartOptions() {
    return {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: {
            boxWidth: 15,
            padding: 20
          }
        },
        title: {
          display: true,
          text: `Document Distribution (${window.reportData?.year || new Date().getFullYear()})`
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.parsed;
              const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
              const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
              return `${context.label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    };
  }
  
  // Store report data in a global variable
  window.reportData = null;
  
  // Modify the existing generateFilteredReport function to save the data
  const originalGenerateFilteredReport = window.generateFilteredReport;
  if (typeof originalGenerateFilteredReport === 'function') {
    window.generateFilteredReport = function(quarter, docType) {
      // Show loading indicator
      const reportLoading = document.getElementById('report-loading-indicator');
      if (reportLoading) reportLoading.style.display = 'flex';
      
      // Build query parameters
      const params = new URLSearchParams();
      if (quarter !== 'all') params.append('quarter', quarter);
      if (docType !== 'all') params.append('doc_type', docType);
      
      // Fetch data from backend
      fetch(`/admin/reports/quarterly-data?${params.toString()}`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          // Save the data globally
          window.reportData = data;
          
          // Update the chart
          if (chartTypeBar.classList.contains('active')) {
            chartTypeBar.click();
          } else if (chartTypePie.classList.contains('active')) {
            chartTypePie.click();
          } else if (chartTypeLine.classList.contains('active')) {
            chartTypeLine.click();
          } else {
            chartTypeBar.click(); // Default to bar chart
          }
          
          if (reportLoading) reportLoading.style.display = 'none';
        })
        .catch(error => {
          console.error('Error fetching quarterly data:', error);
          if (reportLoading) reportLoading.style.display = 'none';
          toastr.error('Error generating quarterly report: ' + error.message);
        });
    };
  }
  
  // Initialize first chart when page loads
  if (document.getElementById('reports-tab')) {
    document.getElementById('reports-tab').addEventListener('shown.bs.tab', function() {
      // Default to bar chart on first load
      if (chartTypeBar && !currentChartInstance) {
        chartTypeBar.click();
      }
    });
  }
});
</script>
<script>
  // Add this to your existing JavaScript code to make the reports section functional

document.addEventListener('DOMContentLoaded', function() {
  // References to report elements
  const generateReportBtn = document.getElementById('generate-report-btn');
  const exportReportBtn = document.getElementById('export-report-btn');
  const reportQuarter = document.getElementById('report-quarter');
  const reportDocType = document.getElementById('report-doc-type');
  const reportLoading = document.getElementById('report-loading-indicator');
  const exportPdf = document.getElementById('export-pdf');
  const exportExcel = document.getElementById('export-excel');
  
  // Report tab event listener
  const reportsTab = document.getElementById('reports-tab');
  if (reportsTab) {
    reportsTab.addEventListener('shown.bs.tab', function() {
      // Generate initial chart when tab is first shown
      generateQuarterlyReport();
    });
  }
  
  // Generate report button click handler
  if (generateReportBtn) {
    generateReportBtn.addEventListener('click', function() {
      const quarter = reportQuarter.value;
      const docType = reportDocType.value;
      generateFilteredReport(quarter, docType);
    });
  }
  
  // Export report button click handler
  if (exportReportBtn) {
    exportReportBtn.addEventListener('click', function() {
      const quarter = reportQuarter.value;
      const docType = reportDocType.value;
      const format = document.querySelector('input[name="export-format"]:checked').value;
      exportReport(quarter, docType, format);
    });
  }
  
  // Function to generate filtered quarterly report
  function generateFilteredReport(quarter, docType) {
    // Show loading indicator
    if (reportLoading) reportLoading.style.display = 'flex';
    
    // Build query parameters
    const params = new URLSearchParams();
    if (quarter !== 'all') params.append('quarter', quarter);
    if (docType !== 'all') params.append('doc_type', docType);
    
    // Fetch data from backend
    fetch(`/admin/reports/quarterly-data?${params.toString()}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        createQuarterlyReportChart(data);
        if (reportLoading) reportLoading.style.display = 'none';
      })
      .catch(error => {
        console.error('Error fetching quarterly data:', error);
        if (reportLoading) reportLoading.style.display = 'none';
        toastr.error('Error generating quarterly report: ' + error.message);
      });
  }
  
  // Function to export report
  // Replace or modify the exportReport function in your script
  function exportReport(quarter, docType, format) {
  // Build query parameters
  const params = new URLSearchParams();
  if (quarter !== 'all') params.append('quarter', quarter);
  if (docType !== 'all') params.append('doc_type', docType);
  params.append('format', format);
  
  // Show loading indicator
  const reportLoading = document.getElementById('report-loading-indicator');
  if (reportLoading) reportLoading.style.display = 'flex';
  
  // Log for debugging
  console.log(`Attempting to export report with format: ${format}, quarter: ${quarter}, docType: ${docType}`);
  
  // Use the correct route
  const exportUrl = `/admin/reports/export?${params.toString()}`;
  console.log(`Export URL: ${exportUrl}`);
  
  // Create and trigger download
  fetch(exportUrl)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Server responded with status: ${response.status}`);
      }
      return response.blob();
    })
    .then(blob => {
      const fileExt = format === 'excel' ? 'xlsx' : 'pdf';
      const filename = `report_${new Date().toISOString().slice(0, 10)}.${fileExt}`;
      
      // Create download link
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = url;
      a.download = filename;
      document.body.appendChild(a);
      a.click();
      
      // Clean up
      window.URL.revokeObjectURL(url);
      if (reportLoading) reportLoading.style.display = 'none';
    })
    .catch(error => {
      console.error('Error downloading report:', error);
      if (reportLoading) reportLoading.style.display = 'none';
      toastr.error(`Error exporting report: ${error.message}`);
    });
}
  // Function to create the quarterly report chart
  function createQuarterlyReportChart(data) {
    const chartContainer = document.getElementById('quarterly-chart-container');
    if (!chartContainer) return;
    
    // Clear existing chart
    chartContainer.innerHTML = '';
    
    // Create a canvas for the chart
    const canvas = document.createElement('canvas');
    canvas.id = 'quarterlyReportChart';
    chartContainer.appendChild(canvas);
    
    // Get the canvas context
    const ctx = canvas.getContext('2d');
    
    // Define chart data
    let labels, incomingData, outgoingData;
    
    if (data.byQuarter) {
      // Quarterly data
      labels = ['Q1', 'Q2', 'Q3', 'Q4'];
      incomingData = data.incomingCounts || [0, 0, 0, 0];
      outgoingData = data.outgoingCounts || [0, 0, 0, 0];
    } else if (data.byMonth) {
      // Monthly data
      labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      incomingData = data.incomingCounts || Array(12).fill(0);
      outgoingData = data.outgoingCounts || Array(12).fill(0);
    } else {
      // Default to quarterly if structure is unknown
      labels = ['Q1', 'Q2', 'Q3', 'Q4'];
      incomingData = [0, 0, 0, 0];
      outgoingData = [0, 0, 0, 0];
    }
    
    // Create the chart
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Incoming Documents',
            backgroundColor: 'rgba(0, 120, 212, 0.6)',
            borderColor: 'rgb(0, 120, 212)',
            borderWidth: 1,
            data: incomingData
          },
          {
            label: 'Outgoing Documents',
            backgroundColor: 'rgba(232, 113, 15, 0.6)',
            borderColor: 'rgb(232, 113, 15)',
            borderWidth: 1,
            data: outgoingData
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Documents'
            }
          },
          x: {
            title: {
              display: true,
              text: data.byQuarter ? 'Quarter' : 'Month'
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: `Document Summary (${data.year || new Date().getFullYear()})`
          },
          tooltip: {
            callbacks: {
              footer: function(tooltipItems) {
                // Calculate the total for this label
                const datasetIndex = tooltipItems[0].datasetIndex;
                const index = tooltipItems[0].dataIndex;
                const total = incomingData[index] + outgoingData[index];
                return `Total: ${total} documents`;
              }
            }
          }
        }
      }
    });
    
    // Add summary statistics below the chart
    const totalIncoming = incomingData.reduce((sum, val) => sum + val, 0);
    const totalOutgoing = outgoingData.reduce((sum, val) => sum + val, 0);
    const totalDocs = totalIncoming + totalOutgoing;
    
    const summaryDiv = document.createElement('div');
    summaryDiv.className = 'mt-4 p-3 border rounded bg-light';
    summaryDiv.innerHTML = `
      <h6 class="mb-3">Summary Statistics</h6>
      <div class="row">
        <div class="col-md-4">
          <div class="card bg-primary text-white mb-2">
            <div class="card-body py-2">
              <h3 class="card-title mb-0">${totalIncoming}</h3>
              <p class="card-text small mb-0">Total Incoming</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-warning text-dark mb-2">
            <div class="card-body py-2">
              <h3 class="card-title mb-0">${totalOutgoing}</h3>
              <p class="card-text small mb-0">Total Outgoing</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-success text-white mb-2">
            <div class="card-body py-2">
              <h3 class="card-title mb-0">${totalDocs}</h3>
              <p class="card-text small mb-0">Total Documents</p>
            </div>
          </div>
        </div>
      </div>
    `;
    
    chartContainer.appendChild(summaryDiv);
  }
  
  // Quarterly report generation function
  function generateQuarterlyReport() {
    // Show loading indicator
    if (reportLoading) reportLoading.style.display = 'flex';
    
    // Fetch data from backend
    fetch('/admin/reports/quarterly-data')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        createQuarterlyReportChart(data);
        if (reportLoading) reportLoading.style.display = 'none';
      })
      .catch(error => {
        console.error('Error fetching quarterly data:', error);
        if (reportLoading) reportLoading.style.display = 'none';
        toastr.error('Error generating quarterly report: ' + error.message);
      });
  }
  // This function creates an enhanced report visualization with multiple chart types
function createEnhancedReportVisualization(data) {
  const chartContainer = document.getElementById('quarterly-chart-container');
  if (!chartContainer) return;
  
  // Clear existing content
  chartContainer.innerHTML = '';
  
  // Create report wrapper
  const reportWrapper = document.createElement('div');
  reportWrapper.className = 'report-wrapper';
  chartContainer.appendChild(reportWrapper);
  
  // Create report header with title and period
  const reportHeader = document.createElement('div');
  reportHeader.className = 'mb-4 d-flex justify-content-between align-items-center';
  
  const periodText = data.quarterFilter ? 
    `Q${data.quarterFilter} ${data.year || new Date().getFullYear()}` : 
    `Year ${data.year || new Date().getFullYear()}`;
  
  reportHeader.innerHTML = `
    <h5 class="m-0"><i class="fas fa-chart-bar me-2"></i>Document Distribution Report</h5>
    <span class="badge bg-secondary">${periodText}</span>
  `;
  reportWrapper.appendChild(reportHeader);
  
  // Create tab navigation for different chart types
  const chartTabs = document.createElement('ul');
  chartTabs.className = 'nav nav-tabs mb-3';
  chartTabs.innerHTML = `
    <li class="nav-item">
      <a class="nav-link active" id="bar-chart-tab" data-bs-toggle="tab" href="#bar-chart-content">Bar Chart</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="pie-chart-tab" data-bs-toggle="tab" href="#pie-chart-content">Distribution</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="line-chart-tab" data-bs-toggle="tab" href="#line-chart-content">Trend</a>
    </li>
  `;
  reportWrapper.appendChild(chartTabs);
  
  // Create tab content container
  const tabContent = document.createElement('div');
  tabContent.className = 'tab-content';
  reportWrapper.appendChild(tabContent);
  
  // 1. Bar Chart Tab Content
  const barChartTab = document.createElement('div');
  barChartTab.className = 'tab-pane fade show active';
  barChartTab.id = 'bar-chart-content';
  tabContent.appendChild(barChartTab);
  
  const barChartCanvas = document.createElement('canvas');
  barChartCanvas.id = 'barChart';
  barChartCanvas.style.height = '350px';
  barChartTab.appendChild(barChartCanvas);
  
  // 2. Pie Chart Tab Content
  const pieChartTab = document.createElement('div');
  pieChartTab.className = 'tab-pane fade';
  pieChartTab.id = 'pie-chart-content';
  tabContent.appendChild(pieChartTab);
  
  // Create a row for two pie charts
  const pieChartsRow = document.createElement('div');
  pieChartsRow.className = 'row';
  pieChartTab.appendChild(pieChartsRow);
  
  // Left column for incoming distribution
  const leftPieCol = document.createElement('div');
  leftPieCol.className = 'col-md-6';
  pieChartsRow.appendChild(leftPieCol);
  
  const incomingPieContainer = document.createElement('div');
  incomingPieContainer.className = 'card h-100';
  incomingPieContainer.innerHTML = `
    <div class="card-header">
      <h6 class="m-0">Incoming Documents by Category</h6>
    </div>
    <div class="card-body">
      <canvas id="incomingPieChart" style="height: 250px;"></canvas>
    </div>
  `;
  leftPieCol.appendChild(incomingPieContainer);
  
  // Right column for outgoing distribution
  const rightPieCol = document.createElement('div');
  rightPieCol.className = 'col-md-6';
  pieChartsRow.appendChild(rightPieCol);
  
  const outgoingPieContainer = document.createElement('div');
  outgoingPieContainer.className = 'card h-100';
  outgoingPieContainer.innerHTML = `
    <div class="card-header">
      <h6 class="m-0">Outgoing Documents by Category</h6>
    </div>
    <div class="card-body">
      <canvas id="outgoingPieChart" style="height: 250px;"></canvas>
    </div>
  `;
  rightPieCol.appendChild(outgoingPieContainer);
  
  // 3. Line Chart Tab Content
  const lineChartTab = document.createElement('div');
  lineChartTab.className = 'tab-pane fade';
  lineChartTab.id = 'line-chart-content';
  tabContent.appendChild(lineChartTab);
  
  const lineChartCanvas = document.createElement('canvas');
  lineChartCanvas.id = 'lineChart';
  lineChartCanvas.style.height = '350px';
  lineChartTab.appendChild(lineChartCanvas);
  
  // Add summary statistics below the charts
  const summaryStats = document.createElement('div');
  summaryStats.className = 'row mt-4 stats-summary';
  
  // Calculate totals
  const incomingTotal = data.incomingCounts ? data.incomingCounts.reduce((sum, val) => sum + val, 0) : 0;
  const outgoingTotal = data.outgoingCounts ? data.outgoingCounts.reduce((sum, val) => sum + val, 0) : 0;
  const totalDocs = incomingTotal + outgoingTotal;
  
  // Create summary cards
  summaryStats.innerHTML = `
    <div class="col-md-3">
      <div class="card border-0 bg-light">
        <div class="card-body text-center">
          <h3 class="text-primary mb-1">${totalDocs}</h3>
          <p class="text-muted mb-0 small">Total Documents</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 bg-light">
        <div class="card-body text-center">
          <h3 class="text-info mb-1">${incomingTotal}</h3>
          <p class="text-muted mb-0 small">Incoming Documents</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 bg-light">
        <div class="card-body text-center">
          <h3 class="text-warning mb-1">${outgoingTotal}</h3>
          <p class="text-muted mb-0 small">Outgoing Documents</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 bg-light">
        <div class="card-body text-center">
          <h3 class="text-success mb-1">${Math.round((outgoingTotal / (incomingTotal || 1)) * 100)}%</h3>
          <p class="text-muted mb-0 small">Processing Rate</p>
        </div>
      </div>
    </div>
  `;
  
  reportWrapper.appendChild(summaryStats);
  
  // Function to determine chart labels based on data
  function getChartLabels() {
    if (data.quarterFilter) {
      // If filtering by quarter, show months within that quarter
      const quarterMonths = {
        1: ['January', 'February', 'March'],
        2: ['April', 'May', 'June'],
        3: ['July', 'August', 'September'],
        4: ['October', 'November', 'December']
      };
      return quarterMonths[data.quarterFilter] || [];
    } else if (data.byMonth) {
      return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    } else {
      return ['Q1', 'Q2', 'Q3', 'Q4'];
    }
  }
  
  // Get labels for the charts
  const chartLabels = getChartLabels();
  
  // Get data for the charts
  const incomingData = data.incomingCounts || Array(chartLabels.length).fill(0);
  const outgoingData = data.outgoingCounts || Array(chartLabels.length).fill(0);
  
  // 1. Initialize Bar Chart
  const barCtx = document.getElementById('barChart').getContext('2d');
  const barChart = new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: chartLabels,
      datasets: [
        {
          label: 'Incoming Documents',
          backgroundColor: 'rgba(0, 120, 212, 0.6)',
          borderColor: 'rgb(0, 120, 212)',
          borderWidth: 1,
          data: incomingData
        },
        {
          label: 'Outgoing Documents',
          backgroundColor: 'rgba(232, 113, 15, 0.6)',
          borderColor: 'rgb(232, 113, 15)',
          borderWidth: 1,
          data: outgoingData
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Documents'
          }
        },
        x: {
          title: {
            display: true,
            text: data.quarterFilter ? 'Month' : (data.byMonth ? 'Month' : 'Quarter')
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            footer: function(tooltipItems) {
              const total = incomingData[tooltipItems[0].dataIndex] + outgoingData[tooltipItems[0].dataIndex];
              return `Total: ${total} documents`;
            }
          }
        }
      }
    }
  });
  
  // 2. Initialize Pie Charts
  // Sample category data (this would come from your backend in a real implementation)
  const incomingCategories = {
    'TRAVEL ORDER': incomingTotal * 0.25,
    'RMO': incomingTotal * 0.15,
    'MEMO-ORD': incomingTotal * 0.2,
    'ONO': incomingTotal * 0.1,
    'Others': incomingTotal * 0.3
  };
  
  const outgoingCategories = {
    'TRAVEL ORDER': outgoingTotal * 0.3,
    'RMO': outgoingTotal * 0.2,
    'MEMO-ORD': outgoingTotal * 0.15,
    'ONO': outgoingTotal * 0.25,
    'Others': outgoingTotal * 0.1
  };
  
  const incomingPieCtx = document.getElementById('incomingPieChart').getContext('2d');
  const incomingPieChart = new Chart(incomingPieCtx, {
    type: 'pie',
    data: {
      labels: Object.keys(incomingCategories),
      datasets: [{
        data: Object.values(incomingCategories),
        backgroundColor: [
          'rgba(0, 123, 255, 0.7)',
          'rgba(40, 167, 69, 0.7)',
          'rgba(255, 193, 7, 0.7)',
          'rgba(23, 162, 184, 0.7)',
          'rgba(108, 117, 125, 0.7)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: {
            boxWidth: 15
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              const percentage = Math.round((value / incomingTotal) * 100);
              return `${context.label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });
  
  const outgoingPieCtx = document.getElementById('outgoingPieChart').getContext('2d');
  const outgoingPieChart = new Chart(outgoingPieCtx, {
    type: 'pie',
    data: {
      labels: Object.keys(outgoingCategories),
      datasets: [{
        data: Object.values(outgoingCategories),
        backgroundColor: [
          'rgba(255, 99, 132, 0.7)',
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 206, 86, 0.7)',
          'rgba(75, 192, 192, 0.7)',
          'rgba(153, 102, 255, 0.7)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: {
            boxWidth: 15
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              const percentage = Math.round((value / outgoingTotal) * 100);
              return `${context.label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });
  
  // 3. Initialize Line Chart
  // Generate monthly data if we have quarterly data
  const lineChartLabels = data.byMonth ? 
    ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] : 
    chartLabels;
  
  // Transform quarterly data to monthly if needed
  let incomingLineData = data.byMonth ? incomingData : [];
  let outgoingLineData = data.byMonth ? outgoingData : [];
  
  if (!data.byMonth && !data.quarterFilter) {
    // Generate monthly data from quarterly data (sample distribution)
    incomingLineData = [];
    outgoingLineData = [];
    for (let q = 0; q < 4; q++) {
      const qVal = incomingData[q] || 0;
      const qOutVal = outgoingData[q] || 0;
      // Distribute quarterly value across months (with some variation)
      for (let m = 0; m < 3; m++) {
        const monthIndex = q * 3 + m;
        const monthVariation = 0.8 + Math.random() * 0.4; // 80-120% of average
        incomingLineData[monthIndex] = Math.round(qVal / 3 * monthVariation);
        outgoingLineData[monthIndex] = Math.round(qOutVal / 3 * monthVariation);
      }
    }
  }
  
  const lineCtx = document.getElementById('lineChart').getContext('2d');
  const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: lineChartLabels,
      datasets: [
        {
          label: 'Incoming Documents',
          data: incomingLineData,
          borderColor: 'rgb(0, 123, 255)',
          backgroundColor: 'rgba(0, 123, 255, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.3
        },
        {
          label: 'Outgoing Documents',
          data: outgoingLineData,
          borderColor: 'rgb(255, 99, 132)',
          backgroundColor: 'rgba(255, 99, 132, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Documents'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Month'
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
        }
      }
    }
  });
  
  // Return the chart instances in case they need to be updated later
  return {
    barChart,
    incomingPieChart,
    outgoingPieChart,
    lineChart
  };
}

// Make the function globally available
window.createEnhancedReportVisualization = createEnhancedReportVisualization;
});
</script>

  <script>
    
  // Define the function in the global scope
  function autoFillSpareRow(hotInstance) {
    const rowCount = hotInstance.countRows();
    if (rowCount > 0) {
      const lastRowIndex = rowCount - 1;
      const rowData = hotInstance.getSourceDataAtRow(lastRowIndex) || {};
      // Adjust the keys you check according to your data structure.
      const isBlank = !rowData.id && !rowData.reference_number && !rowData.No;
      if (isBlank) {
        const currentMonth = new Date().getMonth() + 1;
        const quarter = Math.floor((currentMonth - 1) / 3) + 1;
        hotInstance.setDataAtRowProp(lastRowIndex, 'quarter', quarter, 'internal');
        hotInstance.setDataAtRowProp(lastRowIndex, 'chedrix_2025', 'CHEDRIX-2025', 'internal');

        // Auto-increment the "No" value.
        const allNoValues = hotInstance.getDataAtProp('No')
          .filter(val => !!val)
          .map(val => parseInt(val, 10))
          .filter(num => !isNaN(num));
        const maxNo = allNoValues.length ? Math.max(...allNoValues) : 0;
        const nextNo = String(maxNo + 1).padStart(4, '0');
        hotInstance.setDataAtRowProp(lastRowIndex, 'No', nextNo, 'internal');
      }
    }
  }
  
  // Optionally attach it to the window object for clarity:
  window.autoFillSpareRow = autoFillSpareRow;
  
</script>

<script>
  /**
 * Add this to your JavaScript file to ensure search works across all Handsontable versions
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize search functionality for each table
  addSearchFunctionality('search-incomings', 'handsontable-incomings', window.hotIncomings);
  addSearchFunctionality('search-outgoings', 'handsontable-outgoings', window.hotOutgoings);
  addSearchFunctionality('search-travel-memo', 'handsontable-travel-memo', window.hotTravelMemo);
  addSearchFunctionality('search-ono', 'handsontable-ono', window.hotOno);
  
  // Add the search highlight style
  const style = document.createElement('style');
  style.textContent = `
    .ht__highlight {
      background-color: rgba(255, 237, 51, 0.3) !important;
    }
    .ht__active_highlight {
      background-color: rgba(255, 237, 51, 0.7) !important;
    }
  `;
  document.head.appendChild(style);
  
  /**
   * Attach search functionality to a table using the search input
   */
  function addSearchFunctionality(searchInputId, tableContainerId, hotInstance) {
    const searchInput = document.getElementById(searchInputId);
    const tableContainer = document.getElementById(tableContainerId);
    
    if (!searchInput || !tableContainer || !hotInstance) return;
    
    let searchTimeout;
    let currentHighlight = null;
    let searchMatches = [];
    let currentMatchIndex = 0;
    
    // Function to highlight all matches
    function highlightMatches(query) {
      // Clear previous highlights first
      clearHighlights();
      
      if (!query) return;
      
      // Convert query to lowercase for case-insensitive search
      const queryLower = query.toLowerCase();
      searchMatches = [];
      
      // Search through all data
      const data = hotInstance.getData();
      if (!data) return;
      
      for (let row = 0; row < data.length; row++) {
        for (let col = 0; col < data[row].length; col++) {
          const cellValue = String(data[row][col] || '').toLowerCase();
          if (cellValue.includes(queryLower)) {
            searchMatches.push({row, col});
          }
        }
      }
      
      // Apply highlight classes to all matches
      searchMatches.forEach(({row, col}) => {
        const meta = hotInstance.getCellMeta(row, col);
        const className = meta.className || '';
        meta.className = `${className} ht__highlight`.trim();
        hotInstance.setCellMeta(row, col, 'className', meta.className);
      });
      
      // Highlight the first match with active highlight
      if (searchMatches.length > 0) {
        currentMatchIndex = 0;
        highlightActiveMatch();
        
        // Report the number of matches found
        toastr.info(`Found ${searchMatches.length} matches`, null, {timeOut: 2000});
      } else {
        toastr.warning('No matches found', null, {timeOut: 2000});
      }
      
      hotInstance.render();
    }
    
    // Function to highlight the currently active match
    function highlightActiveMatch() {
      if (searchMatches.length === 0) return;
      
      const {row, col} = searchMatches[currentMatchIndex];
      
      // Add active highlight class
      const meta = hotInstance.getCellMeta(row, col);
      const baseClassName = meta.className.replace('ht__active_highlight', '').trim();
      meta.className = `${baseClassName} ht__active_highlight`;
      hotInstance.setCellMeta(row, col, 'className', meta.className);
      
      // Select and scroll to the cell
      hotInstance.selectCell(row, col);
      hotInstance.scrollViewportTo(row, col);
      
      hotInstance.render();
    }
    
    // Function to clear all highlights
    function clearHighlights() {
      const data = hotInstance.getData();
      if (!data) return;
      
      for (let row = 0; row < hotInstance.countRows(); row++) {
        for (let col = 0; col < hotInstance.countCols(); col++) {
          const meta = hotInstance.getCellMeta(row, col);
          if (meta.className) {
            meta.className = meta.className
              .replace('ht__highlight', '')
              .replace('ht__active_highlight', '')
              .trim();
            
            hotInstance.setCellMeta(row, col, 'className', meta.className || null);
          }
        }
      }
      
      searchMatches = [];
      currentMatchIndex = 0;
      hotInstance.render();
    }
    
    // Search input handler with debounce
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      
      const query = this.value.trim();
      
      searchTimeout = setTimeout(() => {
        if (query) {
          highlightMatches(query);
        } else {
          clearHighlights();
        }
      }, 300);
    });
    
    // Add "Next" button next to search
    const nextButton = document.createElement('button');
    nextButton.className = 'excel-button';
    nextButton.innerHTML = '<i class="fas fa-arrow-down"></i>';
    nextButton.title = 'Find next (F3)';
    nextButton.style.marginLeft = '8px';
    nextButton.onclick = findNext;
    
    // Insert the button after the search input
    searchInput.parentNode.insertAdjacentElement('afterend', nextButton);
    
    // Find next match function
    function findNext() {
      if (searchMatches.length === 0) return;
      
      // Move to the next match
      currentMatchIndex = (currentMatchIndex + 1) % searchMatches.length;
      highlightActiveMatch();
    }
    
    // Find previous match function
    function findPrevious() {
      if (searchMatches.length === 0) return;
      
      // Move to the previous match
      currentMatchIndex = (currentMatchIndex - 1 + searchMatches.length) % searchMatches.length;
      highlightActiveMatch();
    }
    
    // Add keyboard shortcut for F3 (find next)
    document.addEventListener('keydown', function(e) {
      if (e.key === 'F3') {
        e.preventDefault();
        findNext();
      } else if (e.key === 'F3' && e.shiftKey) {
        e.preventDefault();
        findPrevious();
      }
    });
    
    // Add a "Previous" button
    const prevButton = document.createElement('button');
    prevButton.className = 'excel-button';
    prevButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    prevButton.title = 'Find previous (Shift+F3)';
    prevButton.onclick = findPrevious;
    
    // Insert the button after the next button
    nextButton.insertAdjacentElement('afterend', prevButton);
  }
});
  </script>
<script> function isRowEmpty(rowData) {
  if (!rowData) return true;
  // Only check key fields that a user should fill manually
  const fieldsToCheck = ['reference_number', 'date_received', 'sender_name', 'sender_email', 'subject', 'remarks'];
  for (let field of fieldsToCheck) {
    if (rowData[field] && rowData[field].toString().trim() !== '') {
      return false;
    }
  }
  return true;
}

  </script>
<script>
  // Declare the variable in the global scope
var updatingRowColors = false;

function updateRowColors(hotInstance) {
  if (updatingRowColors) return;
  updatingRowColors = true;

  const totalRows = hotInstance.countRows();
  const totalCols = hotInstance.countCols();

  for (let row = 0; row < totalRows; row++) {
    // Clear previous classes for each cell in this row.
    for (let col = 0; col < totalCols; col++) {
      let meta = hotInstance.getCellMeta(row, col);
      if (meta.className) {
        meta.className = meta.className.replace(/\b(gray-row|green-row|yellow-row|red-row)\b/g, '').trim();
        hotInstance.setCellMeta(row, col, 'className', meta.className);
      }
    }

    // Determine the new class based on the row data.
    const rowData = hotInstance.getSourceDataAtRow(row);
    let newClass = "";
    if (isRowEmpty(rowData)) {
      newClass = "gray-row";
    } else if (rowData.date_received && !rowData.date_released) {
      const receivedDate = new Date(rowData.date_received);
      const currentDate = new Date();
      const diffDays = Math.floor((currentDate - receivedDate) / (1000 * 60 * 60 * 24));
      if (diffDays >= 7) {
        newClass = "red-row";
      } else if (diffDays >= 3) {
        newClass = "yellow-row";
      } else if (diffDays >= 1) {
        newClass = "green-row";
      }
    }

    // Apply the new class to every cell in the row.
    if (newClass) {
      for (let col = 0; col < totalCols; col++) {
        let meta = hotInstance.getCellMeta(row, col);
        meta.className = meta.className ? meta.className + " " + newClass : newClass;
        hotInstance.setCellMeta(row, col, "className", meta.className);
      }
    }
  }
  updatingRowColors = false;
  // Note: Do not call hotInstance.render() here to avoid re-triggering afterRender.
}

  </script>
  <script>
  // Register the FilePond plugin
  FilePond.registerPlugin(FilePondPluginFileValidateType);

  // Initialize FilePond for Incoming Import
  FilePond.create(document.querySelector('input#incoming-filepond'), {
    name: 'incoming_filepond',
    instantUpload: true,
    allowMultiple: true,
    maxFiles: 2,
    acceptedFileTypes: ['.csv', '.xls', '.xlsx'],
    labelFileTypeNotAllowed: 'Only Excel or CSV files are allowed.',
    fileValidateTypeLabelExpectedTypes: 'Expects {allButLastType} or {lastType}',
    server: {
      process: {
        url: '{{ route("admin.incomings.import") }}',
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        onload: (response) => {
          toastr.success('Incoming Import successful!');
          // Fetch updated incomings data
          const incomingsDataUrl = "{{ route('admin.incomings.data') }}?_=" + new Date().getTime();
          fetch(incomingsDataUrl + '?_=' + new Date().getTime())
          .then(res => res.json())
          .then(data => {
            // In case your endpoint wraps the data (e.g., data.data), adjust accordingly:
            const newData = data.data ? data.data : data;
            if (window.hotIncomings) {
              window.hotIncomings.loadData(newData);
autoFillSpareRow(window.hotIncomings);
updateRowColors(window.hotIncomings);
setTimeout(() => {
  window.dispatchEvent(new Event('resize'));
  window.hotIncomings.render();
}, 100);
            }
          })
          .catch(err => console.error('Error fetching updated incomings:', err));
        return response;
      },
        onerror: (response) => {
          toastr.error('Error importing incoming file.');
        }
      }
    }
  });

  // Initialize FilePond for Outgoing Import
  FilePond.create(document.querySelector('input#outgoing-filepond'), {
    name: 'outgoing_filepond',
    instantUpload: true,
    allowMultiple: true,
    maxFiles: 2,
    acceptedFileTypes: ['.csv', '.xls', '.xlsx'],
    labelFileTypeNotAllowed: 'Only Excel or CSV files are allowed.',
    fileValidateTypeLabelExpectedTypes: 'Expects {allButLastType} or {lastType}',
    server: {
      process: {
        url: '{{ route("admin.outgoings.import") }}',
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        onload: (response) => {
          toastr.success('Outgoing Import successful!');
          // Fetch updated outgoings data
          const outgoingsDataUrl = "{{ route('admin.outgoings.data') }}";
          fetch(outgoingsDataUrl + '?_=' + new Date().getTime())
          .then(res => res.json())
            .then(data => {
              const newData = data.data ? data.data : data;
              if (window.hotOutgoings) {
                window.hotOutgoings.loadData(newData);
              }
            })
            .catch(err => console.error('Error fetching updated outgoings:', err));
          return response;
        },
        onerror: (response) => {
          toastr.error('Error importing outgoing file.');
        }
      }
    }
  });

document.addEventListener("DOMContentLoaded", function() {
  function debugLog(msg) {
    console.log("[Handsontable Debug]", msg);
  }

  function debounce(func, wait) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), wait);
  };
}
const debouncedUpdateRowColors = debounce(updateRowColors, 200);

  var outgoingsSubTabs = document.getElementById('outgoingsSubTabs');
  if (outgoingsSubTabs) {
    outgoingsSubTabs.addEventListener('shown.bs.tab', function(e) {
      var targetId = e.target.getAttribute("data-bs-target");
      // Depending on which sub-tab was activated, re-render the Handsontable instance.
      if (targetId === "#all-outgoings" && window.hotOutgoings) {
        window.hotOutgoings.render();
      } else if (targetId === "#travel-memo" && window.hotTravelMemo) {
        window.hotTravelMemo.render();
      } else if (targetId === "#ono" && window.hotOno) {
        window.hotOno.render();
      }
    });
  }
  /* ---------------------------------------------------------------------
   * FETCH DATA from Blade
   * ------------------------------------------------------------------- */
  const outgoingsData   = @json($outgoings ?? []);
  const incomingsData   = @json($incomings ?? []);
  const travelMemoData  = @json($travelMemos ?? []);
  const onoData         = @json($onoOutgoings ?? []);
  function combinedTimeRenderer(instance, td, row, col, prop, value, cellProperties) {
  // 1. Determine and set the background color using your incoming logic.
  const rowData = instance.getSourceDataAtRow(row);
  let bgColor = "";
  if (rowData) {
    // Check if row is empty (based on key fields)
    const fieldsToCheck = ['reference_number', 'date_received', 'sender_name', 'sender_email', 'subject', 'remarks'];
    const isEmpty = fieldsToCheck.every(field => !rowData[field] || rowData[field].toString().trim() === "");
    if(isEmpty) {
      bgColor = "#e0e0e0"; // Light gray for completely empty rows.
    } else if (rowData.date_received && !rowData.date_released) {
      const receivedDate = new Date(rowData.date_received);
      const now = new Date();
      const diffDays = Math.floor((now - receivedDate) / (1000 * 60 * 60 * 24));
      // New thresholds:
      // 0 to 1 day -> green
      // 2 to 5 days -> yellow
      // 6 days and up -> red
      if(diffDays <= 1) {
        bgColor = "#ccffcc"; // green
      } else if(diffDays >= 2 && diffDays <= 5) {
        bgColor = "#ffffcc"; // yellow
      } else if(diffDays >= 6) {
        bgColor = "#ffcccc"; // red
      }
    }
  }
  if (bgColor) {
    td.style.backgroundColor = bgColor;
  }

  // 2. Format the time value into 12-hour format.
  let formattedValue = value;
  if (value) {
    // Assume the stored value is in 24-hour format (e.g. "14:30" or "14:30:00")
    const time = moment(value, ["HH:mm:ss", "HH:mm"]);
    if (time.isValid()) {
      formattedValue = time.format("h:mm A");
    }
  }
  
  // 3. Render the cell text.
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  td.innerText = formattedValue;
}

function updateRowColors(hotInstance) {
  if (updatingRowColors) return;
  updatingRowColors = true;

  const totalRows = hotInstance.countRows();
  const totalCols = hotInstance.countCols();

  for (let row = 0; row < totalRows; row++) {
    // Clear previous classes for each cell in this row.
    for (let col = 0; col < totalCols; col++) {
      let meta = hotInstance.getCellMeta(row, col);
      if (meta.className) {
        meta.className = meta.className.replace(/\b(gray-row|green-row|yellow-row|red-row)\b/g, '').trim();
        hotInstance.setCellMeta(row, col, 'className', meta.className);
      }
    }

    // Determine the new class based on the row data.
    const rowData = hotInstance.getSourceDataAtRow(row);
    let newClass = "";
    if (isRowEmpty(rowData)) {
      newClass = "gray-row";
    } else if (rowData.date_received && !rowData.date_released) {
      const receivedDate = new Date(rowData.date_received);
      const currentDate = new Date();
      const diffDays = Math.floor((currentDate - receivedDate) / (1000 * 60 * 60 * 24));
      if (diffDays >= 7) {
        newClass = "red-row";
      } else if (diffDays >= 3) {
        newClass = "yellow-row";
      } else if (diffDays >= 1) {
        newClass = "green-row";
      }
    }

    // Apply the new class to every cell in the row.
    if (newClass) {
      for (let col = 0; col < totalCols; col++) {
        let meta = hotInstance.getCellMeta(row, col);
        meta.className = meta.className ? meta.className + " " + newClass : newClass;
        hotInstance.setCellMeta(row, col, "className", meta.className);
      }
    }
  }
  updatingRowColors = false;
  // DO NOT call hotInstance.render() here to avoid triggering another afterRender cycle.
}
// Renderer for incoming rows (for all columns except special ones)
function incomingRowRenderer(instance, td, row, col, prop, value, cellProperties) {
  // Call the default text renderer first.
  Handsontable.renderers.TextRenderer.apply(this, arguments);

  // Get the entire row's data.
  const rowData = instance.getSourceDataAtRow(row);
  if (!rowData) return;

  let bgColor = "";
  
  // Check if the row is "empty" based on key fields.
  const fieldsToCheck = ['reference_number', 'date_received', 'sender_name', 'sender_email', 'subject', 'remarks'];
  const isEmpty = fieldsToCheck.every(field => !rowData[field] || rowData[field].toString().trim() === "");
  
  if(isEmpty) {
    bgColor = "#e0e0e0"; // Light gray if the row is completely empty.
  } else if (rowData.date_received && !rowData.date_released) {
    // Calculate days since received.
    const receivedDate = new Date(rowData.date_received);
    const now = new Date();
    const diffDays = Math.floor((now - receivedDate) / (1000 * 60 * 60 * 24));
    // New thresholds:
    // 0 to 1 day   -> green
    // 2 to 5 days  -> yellow
    // 6 days and up -> red
    if(diffDays <= 1) {
      bgColor = "#ccffcc"; // green
    } else if(diffDays >= 2 && diffDays <= 5) {
      bgColor = "#ffffcc"; // yellow
    } else if(diffDays >= 6) {
      bgColor = "#ffcccc"; // red
    }
  }
  
  if(bgColor) {
    td.style.backgroundColor = bgColor;
  }
}


function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
  if (value) {
    // Use moment to format the value to just the date portion
    value = moment(value).format('YYYY-MM-DD');
  }
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  td.innerText = value;
}
// Custom time renderer to convert 24h time to 12h time with AM/PM.
// Fix the time rendering function
function timeRenderer(instance, td, row, col, prop, value, cellProperties) {
  if (value) {
    try {
      // Try to parse the value as a valid time
      const time = moment(value, ['HH:mm:ss', 'HH:mm', 'h:mm A']);
      if (time.isValid()) {
        value = time.format('h:mm A');
      }
    } catch (e) {
      // If parsing fails, keep the original value
      console.warn('Failed to parse time:', value);
    }
  }
  
  // Apply the standard text renderer with our formatted value
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  td.innerText = value || '';
}
  /* ---------------------------------------------------------------------
   * Common helpers
   * ------------------------------------------------------------------- */
  function hyperlinkRenderer(instance, td, row, col, prop, value) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if (value) {
      td.style.color = 'blue';
      td.style.textDecoration = 'underline';
      td.style.cursor = 'pointer';
      td.innerHTML = `<a href="#" class="outgoing-link" data-id="${value}">${value}</a>`;
    }
  }

  function quarterLabelRenderer(instance, td, row, col, prop, value) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if (value) {
      td.innerText = 'Q' + value;
      td.classList.add('htQuarterLabel');
    }
  }

  function statusHighlightRenderer(instance, td, row, col, prop, value) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if (value === 'Released') {
      td.classList.add('released-row');
      td.title = 'This has been released.';
    } else {
      td.classList.remove('released-row');
      td.title = '';
    }
  }
// Quarterly report generation function
function generateQuarterlyReport() {
  // Show loading indicator
  document.getElementById('loading-indicator').style.display = 'flex';
  
  // Fetch data from backend
  fetch('/admin/reports/quarterly-data')
    .then(response => response.json())
    .then(data => {
      createQuarterlyReportChart(data);
      document.getElementById('loading-indicator').style.display = 'none';
    })
    .catch(error => {
      console.error('Error fetching quarterly data:', error);
      document.getElementById('loading-indicator').style.display = 'none';
      toastr.error('Error generating quarterly report.');
    });
}

// Function to create the chart
function createQuarterlyReportChart(data) {
  const chartContainer = document.getElementById('quarterly-chart-container');
  chartContainer.innerHTML = '';
  
  // Create the chart
  const chartWrapper = document.createElement('div');
  chartWrapper.className = 'excel-card';
  chartWrapper.innerHTML = `
    <div class="excel-card-header">
      <h6><i class="fas fa-chart-bar me-2"></i> Quarterly Document Report (${data.year})</h6>
    </div>
    <div class="excel-card-body">
      <canvas id="quarterlyReportChart" width="100%" height="50"></canvas>
    </div>
    <div class="excel-card-footer">
      <button class="excel-button" id="download-report-excel">
        <i class="fas fa-file-excel"></i> Download Excel
      </button>
      <button class="excel-button" id="download-report-pdf">
        <i class="fas fa-file-pdf"></i> Download PDF
      </button>
    </div>
  `;
  
  chartContainer.appendChild(chartWrapper);
  
  // Use Chart.js to create the chart
  const ctx = document.getElementById('quarterlyReportChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Q1', 'Q2', 'Q3', 'Q4'],
      datasets: [
        {
          label: 'Incoming Documents',
          backgroundColor: 'rgba(0, 120, 212, 0.6)',
          borderColor: 'rgb(0, 120, 212)',
          borderWidth: 1,
          data: data.incomingCounts
        },
        {
          label: 'Outgoing Documents',
          backgroundColor: 'rgba(232, 113, 15, 0.6)',
          borderColor: 'rgb(232, 113, 15)',
          borderWidth: 1,
          data: data.outgoingCounts
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Documents'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Quarter'
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: `Quarterly Document Summary (${data.year})`
        }
      }
    }
  });
  
  // Add event listeners for export buttons
  document.getElementById('download-report-excel').addEventListener('click', function() {
    window.location.href = `/admin/reports/quarterly-export?type=excel&year=${data.year}`;
  });
  
  document.getElementById('download-report-pdf').addEventListener('click', function() {
    window.location.href = `/admin/reports/quarterly-export?type=pdf&year=${data.year}`;
  });
}
  function clearHighlights(hotInstance) {
    const rowCount = hotInstance.countRows();
    const colCount = hotInstance.countCols();
    for (let row = 0; row < rowCount; row++) {
      for (let col = 0; col < colCount; col++) {
        const meta = hotInstance.getCellMeta(row, col);
        if (meta.className && meta.className.includes('highlightedRow')) {
          const newClass = meta.className.replace(/\bhighlightedRow\b/g, '').trim();
          hotInstance.setCellMeta(row, col, 'className', newClass);
        }
      }
    }
    hotInstance.render();
  }
  function autoFillSpareRow(hotInstance) {
  const rowCount = hotInstance.countRows();
  if (rowCount > 0) {
    const lastRowIndex = rowCount - 1;
    const rowData = hotInstance.getSourceDataAtRow(lastRowIndex) || {};
    // Adjust the keys you check according to your data structure.
    const isBlank = !rowData.id && !rowData.reference_number && !rowData.No;
    if (isBlank) {
      const currentMonth = new Date().getMonth() + 1;
      const quarter = Math.floor((currentMonth - 1) / 3) + 1;
      hotInstance.setDataAtRowProp(lastRowIndex, 'quarter', quarter, 'internal');
      hotInstance.setDataAtRowProp(lastRowIndex, 'chedrix_2025', 'CHEDRIX-2025', 'internal');

      // Auto-increment the "No" value.
      const allNoValues = hotInstance.getDataAtProp('No')
        .filter(val => !!val)
        .map(val => parseInt(val, 10))
        .filter(num => !isNaN(num));
      const maxNo = allNoValues.length ? Math.max(...allNoValues) : 0;
      const nextNo = String(maxNo + 1).padStart(4, '0');
      hotInstance.setDataAtRowProp(lastRowIndex, 'No', nextNo, 'internal');
    }
  }
}
 

  function highlightRow(hotInstance, rowIndex) {
  // Clear existing highlights without triggering render each time.
  clearHighlights(hotInstance);
  const colCount = hotInstance.countCols();
  for (let col = 0; col < colCount; col++) {
    let meta = hotInstance.getCellMeta(rowIndex, col);
    const existingClass = meta.className || '';
    if (!existingClass.includes('highlightedRow')) {
      hotInstance.setCellMeta(rowIndex, col, 'className', existingClass + ' highlightedRow');
    }
  }
  // Call render only once after all cells are updated.
  hotInstance.render();
}



  /* ---------------------------------------------------------------------
   * 1) INCOMINGS TABLE
   * ------------------------------------------------------------------- */
  const containerIncomings = document.getElementById('handsontable-incomings');
  let hotIncomings = null;
  const outgoingsSubTabsLinks = document.querySelectorAll('#outgoingsSubTabs .nav-link');
    outgoingsSubTabsLinks.forEach(link => {
      link.addEventListener('shown.bs.tab', function(e) {
        const target = e.target.getAttribute('href'); // e.g., "#travel-memo"
        if (target === "#all-outgoings" && window.hotOutgoings) {
          window.hotOutgoings.render();
        } else if (target === "#travel-memo" && window.hotTravelMemo) {
          window.hotTravelMemo.render();
        } else if (target === "#ono" && window.hotOno) {
          window.hotOno.render();
        }
      });
    });
  // Release Button Renderer
  function releaseButtonRenderer(instance, td, row, col, prop, value) {
    Handsontable.dom.empty(td);
    const rowData = instance.getSourceDataAtRow(row);
    if (!rowData || !rowData.id) return;

    const btn = document.createElement('button');
    btn.className = 'btn btn-sm btn-primary release-btn';
    btn.innerText = 'Release';
    btn.dataset.row = row;
    td.appendChild(btn);
  }

  function handleRowRelease(event) {
  if (!event.target.classList.contains('release-btn')) return;
  const row = parseInt(event.target.dataset.row, 10);
  const rowData = hotIncomings.getSourceDataAtRow(row);
  if (!rowData || !rowData.id) {
    toastr.error('Cannot release a record without an ID.');
    return;
  }
  if (rowData.date_released) {
    toastr.info('This incoming record is already released.');
    return;
  }
  
  // Use SweetAlert2 instead of the native confirm
  Swal.fire({
    title: 'Are you sure?',
    text: "Do you really want to release this incoming record?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, release it!'
  }).then((result) => {
    if (result.isConfirmed) {
      releaseIncoming(row, rowData);
    }
  });
}

let updatingRowColors = false;



function releaseIncoming(row, rowData) {
  const incomingId = rowData.id;
  const today = new Date().toISOString().split('T')[0];
  const releaseUrl = `/admin/incomings/${incomingId}/release`;
  const button = containerIncomings.querySelector(`.release-btn[data-row="${row}"]`);
  if (button) button.disabled = true;

  fetch(releaseUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ date_released: today })
  })
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => { throw err; });
      }
      return response.json();
    })
    .then(updated => {
      toastr.success('Incoming record released successfully.');
      // Update the Incomings table with the new release date and highlight the row.
      hotIncomings.setDataAtRowProp(row, 'date_released', updated.data.date_released, 'internal');
      updateRowColors(hotIncomings);


      // Process the new outgoing record, if provided.
      if (updated.data.outgoing) {
        const newOutgoing = updated.data.outgoing;
        console.log("New outgoing record from server:", newOutgoing);

        // Add the new outgoing record to your global outgoingsData array.
        outgoingsData.push({
          ...newOutgoing,
          no: String(newOutgoing.id).padStart(4, '0')
        });

        const outgoingTabTrigger = document.getElementById('outgoings-tab');
        if (outgoingTabTrigger) {
          new bootstrap.Tab(outgoingTabTrigger).show();
          console.log("Switched to Outgoings tab.");
        } else {
          console.warn("Outgoings tab element not found.");
        }
        
        // Also update sub-tables if the category is "travel order" or "ono"
        if ((newOutgoing.category || '').toLowerCase() === 'travel order') {
          travelMemoData.push({
            ...newOutgoing,
            no: String(newOutgoing.id).padStart(4, '0')
          });
          if (window.hotTravelMemo) {
            window.hotTravelMemo.loadData(travelMemoData);
          }
        } else if ((newOutgoing.category || '').toLowerCase() === 'ono') {
          onoData.push({
            ...newOutgoing,
            no: String(newOutgoing.id).padStart(4, '0')
          });
          if (window.hotOno) {
            window.hotOno.loadData(onoData);
          }
        }

        // Now update the Outgoings table.
        if (window.hotOutgoings) {
          // Reload the table data from the global outgoingsData array.
          window.hotOutgoings.loadData(outgoingsData);
          window.hotOutgoings.render();

          // Wait briefly to ensure the table is rendered.
          setTimeout(() => {
            const newOutgoingId = newOutgoing.id;
            console.log("New outgoing ID:", newOutgoingId);
            // Find the index of the new outgoing record in outgoingsData.
            const newRowIndex = outgoingsData.findIndex(item =>
              parseInt(item.id, 10) === parseInt(newOutgoingId, 10)
            );
            console.log("New row index found:", newRowIndex);
            if (newRowIndex !== -1) {
              window.hotOutgoings.selectCell(newRowIndex, 0);
              highlightRow(window.hotOutgoings, newRowIndex);
            } else {
              console.warn("New outgoing record not found in outgoingsData.");
            }
          }, 1500); // Delay (in milliseconds) to allow the table to render
        } else {
          console.warn("Outgoings table instance (hotOutgoings) is not available.");
        }
      }
    })
    .catch(error => {
      console.error("Error releasing incoming record:", error);
      let errorMsg = 'Error releasing incoming record.';
      if (error.errors) {
        const firstKey = Object.keys(error.errors)[0];
        errorMsg += " " + error.errors[firstKey].join(' ');
      } else if (error.message) {
        errorMsg += " " + error.message;
      }
      toastr.error(errorMsg);
    })
    .finally(() => {
      if (button) {
        button.disabled = false;
      }
    });
}

  if (containerIncomings) {
    const incomingsArray = incomingsData.map(item => ({
      id:               item.id,
      quarter:          item.quarter,
      No:               item.No,
      location:         item.location,
      chedrix_2025:     item.chedrix_2025,
      reference_number: item.reference_number,
      date_received:    item.date_received,
      time_emailed:     item.time_emailed,
      sender_name:      item.sender_name,
      sender_email:     item.sender_email,
      subject:          item.subject,
      remarks:          item.remarks,
      date_time_routed: item.date_time_routed,
      routed_to:        item.routed_to,
      date_acted_by_es: item.date_acted_by_es,
      outgoing_details: item.outgoing_details,
      year:             item.year,
      outgoing_id:      item.outgoing_id,
      date_released:    item.date_released
    }));
    if (incomingsArray.length === 0) {
  incomingsArray.push({});
}

// The "spare" row is the *last* row
const lastIncomingIndex = incomingsArray.length - 1;
const lastIncomingRow = incomingsArray[lastIncomingIndex];

// If that row is empty (or you want to forcibly fill it):
// Check if it has any data. If it's truly blank => fill with defaults.
const isBlank = !lastIncomingRow.id && !lastIncomingRow.reference_number && !lastIncomingRow.No;
if (isBlank) {
  // 1) Quarter
  const currentMonth = new Date().getMonth() + 1;
  const quarter = Math.floor((currentMonth - 1) / 3) + 1;
  lastIncomingRow.quarter = quarter;

  // 2) CHEDRIX
  lastIncomingRow.chedrix_2025 = 'CHEDRIX-2025';

  // 3) No (auto-increment from existing)
  const allNoValues = incomingsArray
    .map(r => parseInt(r.No, 10))
    .filter(n => !isNaN(n));
  const maxNo = allNoValues.length ? Math.max(...allNoValues) : 0;
  lastIncomingRow.No = String(maxNo + 1).padStart(4, '0');
}
const incomingsColumns = [
  { 
    data: 'quarter',          
    title: 'Quarter', 
    renderer: function(instance, td, row, col, prop, value, cellProperties) {
      incomingRowRenderer(instance, td, row, col, prop, value, cellProperties);
      quarterLabelRenderer(instance, td, row, col, prop, value, cellProperties);
    }, 
    readOnly: true 
  },
  { data: 'chedrix_2025', title: 'CHEDRIX 2025', renderer: incomingRowRenderer },
  { 
    data: 'location',         
    title: 'Location', 
    type: 'dropdown', 
    source: ['e', 'm/zc', 'm/pag'],
    renderer: incomingRowRenderer 
  },
  { data: 'No', title: 'No.', renderer: incomingRowRenderer },
  { data: 'reference_number', title: 'Reference #', renderer: incomingRowRenderer },
  { 
    data: 'date_received',    
    title: 'Date Received', 
    type: 'date',           // Use the built-in date editor
    dateFormat: 'YYYY-MM-DD',
    correctFormat: true,
    renderer: incomingRowRenderer 
  },
  { 
  data: 'time_emailed', 
  title: 'Time Emailed', 
  type: 'text',
  renderer: combinedTimeRenderer,
  validator: function(value, callback) {
    // Validate time in 12-hour format (e.g., "2:30 PM")
    var time = moment(value, "h:mm A", true);
    callback(time.isValid());
  }
},

  { data: 'sender_name', title: 'Sender Name', renderer: incomingRowRenderer },
  { data: 'sender_email', title: 'Sender Email', renderer: incomingRowRenderer },
  { data: 'subject', title: 'Subject', renderer: incomingRowRenderer },
  { data: 'remarks', title: 'Remarks', renderer: incomingRowRenderer },
  { data: 'date_time_routed', title: 'Date Routed', type: 'date', dateFormat: 'YYYY-MM-DD', correctFormat: true, renderer: incomingRowRenderer },
  { data: 'routed_to', title: 'Routed To', renderer: incomingRowRenderer },
  { data: 'date_acted_by_es', title: 'Date Acted by ES', type: 'date', dateFormat: 'YYYY-MM-DD', correctFormat: true, renderer: incomingRowRenderer },
  { data: 'outgoing_details', title: 'Outgoing Details', renderer: incomingRowRenderer },
  { data: 'year', title: 'Year', renderer: incomingRowRenderer },
  {
    data: 'outgoing_id',
    title: 'Outgoing ID',
    renderer: hyperlinkRenderer,
    readOnly: true
  },
  { 
    data: 'date_released',    
    title: 'Date Released', 
    type: 'date', 
    dateFormat: 'YYYY-MM-DD', 
    correctFormat: true, 
    renderer: dateRenderer, incomingRowRenderer
  },
  {
    data: null,
    title: 'Actions',
    renderer: releaseButtonRenderer
  }
];


hotIncomings = new Handsontable(containerIncomings, {
  data: incomingsArray,
  colHeaders: incomingsColumns.map(col => col.title),
  rowHeaders: true,
  dropdownMenu: true,
  filters: true,
  columnSorting: true,
  contextMenu: true,
  licenseKey: 'non-commercial-and-evaluation',
  search: true,
  height: 750,
  minSpareRows: 1,
  columns: incomingsColumns,
  afterRender: function() {
    debouncedUpdateRowColors(this);
    updateRowColors(this);
    setTimeout(() => {
      updateRowColors(this);
      this.render();
    }, 0);
  },
      afterChange: function(changes, source) {
        console.log("Outgoings afterChange called. Source:", source, "Changes:", changes);
        if (!changes) return;
        // 1) Skip if source is loadData or internal (to avoid repeated calls after autofill)
        if (source === 'loadData' || source === 'internal') {
          return;
        }
        // 2) Only proceed for user edits, paste, autofill, etc.
        if (!['edit','Autofill','Paste','Undo'].includes(source)) {
          return;
        }

        debugLog("Incomings afterChange => source=" + source + ", changes=", changes);

        // Auto-fill for last row
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
          const lastRowIndex = this.countRows() - 1;
          if (rowIndex === lastRowIndex) {
            const rowData = this.getSourceDataAtRow(rowIndex) || {};

            // quarter
            if (!rowData.quarter) {
              const currentMonth = new Date().getMonth() + 1;
              const quarter = Math.floor((currentMonth - 1) / 3) + 1;
              this.setDataAtRowProp(rowIndex, 'quarter', quarter, 'internal');
            }

            // chedrix_2025
            if (!rowData.chedrix_2025) {
              this.setDataAtRowProp(rowIndex, 'chedrix_2025', 'CHEDRIX-2025', 'internal');
            }

            // No
            if (!rowData.No) {
              const allNoValues = this.getDataAtProp('No')
                .filter(val => !!val)
                .map(val => parseInt(val, 10))
                .filter(num => !isNaN(num));
              const maxNo = allNoValues.length ? Math.max(...allNoValues) : 0;
              const nextNo = String(maxNo + 1).padStart(4, '0');
              this.setDataAtRowProp(rowIndex, 'No', nextNo, 'internal');
            }
          }
        });

        // Now do the usual server save - one fetch per row changed
        let rowMap = {};
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
          if (oldVal !== newVal) {
            if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
            rowMap[rowIndex][prop] = newVal;
          }
        });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          debugLog("Saving row to server => rowIndex=" + rowIndex, rowData);

          const payload = {
            quarter:          rowData.quarter,
            chedrix_2025:     rowData.chedrix_2025,
            location:         rowData.location,
            No:               rowData.No,
            reference_number: rowData.reference_number,
            date_received:    rowData.date_received,
            time_emailed:     rowData.time_emailed,
            sender_name:      rowData.sender_name,
            sender_email:     rowData.sender_email,
            subject:          rowData.subject,
            remarks:          rowData.remarks,
            date_time_routed: rowData.date_time_routed,
            routed_to:        rowData.routed_to,
            date_acted_by_es: rowData.date_acted_by_es,
            outgoing_details: rowData.outgoing_details,
            year:             rowData.year,
            outgoing_id:      rowData.outgoing_id,
            date_released:    rowData.date_released
          };
          console.log("Payload for row " + rowIndex + ":", payload);

const incomingId = rowData.id;
const hasValidId = incomingId && incomingId !== 'undefined';
console.log("Row data being saved:", rowData);

const url = hasValidId ? `/admin/incomings/${incomingId}` : `/admin/incomings`;
const method = hasValidId ? 'PUT' : 'POST';


          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('Incoming record saved successfully.');
              
              // If newly created => update local ID
              if (!incomingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
              }
              // If server returned a "no", override local
              if (data?.data?.no) {
                this.setDataAtRowProp(rowIndex, 'No', data.data.no, 'internal');
              }
            })
            .catch(err => {
              toastr.error('Error saving incoming record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/incomings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('Incoming record deleted successfully.');
              })
              .catch(() => {
                toastr.error('Error deleting incoming record.');
              });
          }
        });
      },

      afterOnCellMouseDown: function(event, coords) {
        const outgoingIdColIndex = incomingsColumns.findIndex(col => col.data === 'outgoing_id');
        if (coords.col === outgoingIdColIndex) {
          const rowData = this.getSourceDataAtRow(coords.row);
          if (!rowData || !rowData.outgoing_id) return;
          const outgoingId = rowData.outgoing_id;
          if (!window.hotOutgoings) return;

          // Switch to Outgoings tab
          const outgoingTabTrigger = document.getElementById('outgoings-tab');
          if (outgoingTabTrigger) {
            const outgoingTab = new bootstrap.Tab(outgoingTabTrigger);
            outgoingTab.show();
            console.log("Switched to Outgoings tab.");

            setTimeout(() => {
              const rowIndex = outgoingsData.findIndex(item => parseInt(item.id, 10) === parseInt(outgoingId, 10));
              if (rowIndex !== -1) {
                window.hotOutgoings.selectCell(rowIndex, 0);
                window.hotOutgoings.scrollViewportTo(rowIndex, 0);
                highlightRow(window.hotOutgoings, rowIndex);
              } else {
                toastr.warning('Outgoing record not found.');
              }
            }, 300);
          }
        }
      },

      afterRender() {
        // Re-bind release button events
        const buttons = containerIncomings.querySelectorAll('.release-btn');
        buttons.forEach(btn => {
          btn.removeEventListener('click', handleRowRelease);
          btn.addEventListener('click', handleRowRelease);
        });
      }
    });

    // Searching
    const searchIncomings = document.getElementById('search-incomings');
    if (searchIncomings) {
      const searchPlugin = hotIncomings.getPlugin('search');
      searchIncomings.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotIncomings.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotIncomings = hotIncomings;
  }
  function isRowEmpty(rowData) {
  if (!rowData) return true;
  // Only check key fields that a user should fill manually
  const fieldsToCheck = ['reference_number', 'date_received', 'sender_name', 'sender_email', 'subject', 'remarks'];
  for (let field of fieldsToCheck) {
    if (rowData[field] && rowData[field].toString().trim() !== '') {
      return false;
    }
  }
  return true;
}



  /* ---------------------------------------------------------------------
   * 2) OUTGOINGS TABLE
   * ------------------------------------------------------------------- */
  const containerOutgoings = document.getElementById('handsontable-outgoings');
  let hotOutgoings = null;

  if (containerOutgoings) {
    const outgoingsArray = outgoingsData.map(item => ({
      No: item.No, // use "No" to match what the controller returned
      chedrix_2025:      item.chedrix_2025,
      o:                 item.o,
      date_released:     item.date_released,
      category:          item.category,
      addressed_to:      item.addressed_to,
      email:             item.email,
      subject_of_letter: item.subject_of_letter,
      remarks:           item.remarks,
      libcap_no:         item.libcap_no,
      status:            item.status,
      incoming_id:       item.incoming_id,
      travel_date:       item.travel_date,
      es_in_charge:      item.es_in_charge,
      quarter_label:     item.quarter_label
    }));
    if (outgoingsArray.length === 0) {
  outgoingsArray.push({});
}

const lastOutgoingIndex = outgoingsArray.length - 1;
const lastOutgoingRow = outgoingsArray[lastOutgoingIndex];

// If it’s blank => fill with defaults
const blankOutgoing = !lastOutgoingRow.id && !lastOutgoingRow.no;
if (blankOutgoing) {
  lastOutgoingRow.chedrix_2025 = 'CHEDRIX-2025';
  lastOutgoingRow.o = 'O';

  // Auto-increment "no"
  const existingNos = outgoingsArray
    .map(r => parseInt(r.no, 10))
    .filter(n => !isNaN(n));
  const maxNo = existingNos.length ? Math.max(...existingNos) : 0;
  lastOutgoingRow.no = String(maxNo + 1).padStart(4, '0');
}
    const outgoingsColumns = [
      { data: 'quarter_label',  title: 'Quarter Label', readOnly: true }, // e.g. "Q1 JAN-FEB-MAR"
      { data: 'No',                title: 'No.' },
      { data: 'chedrix_2025',      title: 'CHEDRIX 2025' },
      { data: 'o',                 title: 'O' },
      { data: 'date_released',     title: 'Date Released' },
      { data: 'category',          title: 'Category',
        type: 'dropdown',
        source: [
    "RMO", "MEMO-ORD", "OM / CSO", "LETTER TO HEIS", "TRAVEL ORDER", "M&E", "T.A.",
    "R9 RQAT", "R9 HEIS", "R9 STAFF", "COMPLAINTS / 888", "UNIFAST", "BARMM HEIS", 
    "CHAIR", "ED", "OPSD", "OSDS", "OPRKM", "AFMS", "CAV-OSDS-isad", "CAV-KUWAIT", 
    "CAV-DFA", "CAV-OTHERS", "OIQAG", "IAS", "RLA", "LGSO", "SCHOLARSHIP", 
    "Personal/Private", "NSTP/CWTS", "EQUIVALENCY"
  ]

        
       },
      { data: 'addressed_to',      title: 'Addressed To' },
      { data: 'email',             title: 'Email' },
      { data: 'subject_of_letter', title: 'Subject' },
      { data: 'remarks',           title: 'Remarks' },
      { data: 'libcap_no',         title: 'LIBCAP #' },
      { data: 'status',            title: 'Status', renderer: statusHighlightRenderer },
      {
        data: 'incoming_id',
        title: 'Incoming ID',
        renderer: hyperlinkRenderer,
        readOnly: true
      },
      { data: 'travel_date',       title: 'Travel Date' },
      { data: 'es_in_charge',      title: 'ES-In Charge' }
    ];

    hotOutgoings = new Handsontable(containerOutgoings, {
      data: outgoingsArray,
      colHeaders: outgoingsColumns.map(col => col.title),
      rowHeaders: true,
      dropdownMenu: true,
      filters: true,
      columnSorting: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation',
      search: true, // enable search plugin

      height: 700,
      maxHeight: 700,
      minSpareRows: 1,

      columns: outgoingsColumns,
      afterRender: function() {
        debouncedUpdateRowColors(this);

  updateRowColors(this);
  setTimeout(() => {
    updateRowColors(this);
    this.render();
  }, 0);
},
      afterChange: function(changes, source) {
        if (!changes) return;
  if (source === 'loadData' || source === 'internal') return;
  if (!['edit','Autofill','Paste','Undo'].includes(source)) return;

        debugLog("Outgoings afterChange => source=" + source + ", changes=", changes);


        changes.forEach(function(change) {
    const [rowIndex, property, oldVal, newVal] = change;
    if (property === 'date_released' && newVal) {
      const date = new Date(newVal);
      // Compute quarter: month 0-2 => Q1, 3-5 => Q2, etc.
      const quarter = Math.floor(date.getMonth() / 3) + 1;
      // Set the display quarter label (e.g., "Q2")
      this.setDataAtRowProp(rowIndex, 'quarter_label', 'Q' + quarter, 'internal');
      // Set the hidden numeric quarter field
      this.setDataAtRowProp(rowIndex, 'quarter', quarter, 'internal');
    }
  }, this);


        // Auto-fill in last row
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
    const lastRowIndex = this.countRows() - 1;
    if (rowIndex === lastRowIndex) {
        const rowData = this.getSourceDataAtRow(rowIndex) || {};
        // If date_released is present but quarter_label is missing, compute it.
        if (rowData.date_released && !rowData.quarter_label) {
            const date = new Date(rowData.date_released);
            // Compute quarter: months 0-2 = Q1, 3-5 = Q2, etc.
            const quarter = Math.floor(date.getMonth() / 3) + 1;
            this.setDataAtRowProp(rowIndex, 'quarter_label', 'Q' + quarter, 'internal');
        }
        // Set defaults for chedrix_2025, o and no as before:
        if (!rowData.chedrix_2025) {
            this.setDataAtRowProp(rowIndex, 'chedrix_2025', 'CHEDRIX-2025', 'internal');
        }
        if (!rowData.o) {
            this.setDataAtRowProp(rowIndex, 'o', 'O', 'internal');
        }
        if (!rowData.no) {
            const existingNos = this.getDataAtProp('no')
                .filter(val => !!val)
                .map(val => parseInt(val, 10))
                .filter(num => !isNaN(num));
            const maxNo = existingNos.length ? Math.max(...existingNos) : 0;
            const nextNo = String(maxNo + 1).padStart(4, '0');
            this.setDataAtRowProp(rowIndex, 'no', nextNo, 'internal');
        }
    }
});

        // Save to server
        let rowMap = {};
  changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
    if (oldVal !== newVal) {
      if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
      rowMap[rowIndex][prop] = newVal;
    }
  });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          debugLog("Saving outgoings => rowIndex=" + rowIndex, rowData);

          const payload = {
            quarter: rowData.quarter, // now included!
            chedrix_2025:      rowData.chedrix_2025 || 'CHEDRIX-2025',
            o:                 rowData.o || 'O',
            date_released:     rowData.date_released,
            category:          rowData.category,
            addressed_to:      rowData.addressed_to,
            email:             rowData.email,
            subject_of_letter: rowData.subject_of_letter,
            remarks:           rowData.remarks,
            libcap_no:         rowData.libcap_no,
            status:            rowData.status || 'Pending',
            incoming_id:       rowData.incoming_id,
            travel_date:       rowData.travel_date,
            es_in_charge:      rowData.es_in_charge
          };

          const outgoingId = rowData.id;
          const url    = outgoingId ? `/admin/outgoings/${outgoingId}` : `/admin/outgoings`;
          const method = outgoingId ? 'PUT' : 'POST';

          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('Outgoing record saved successfully.');

              // If newly created => store new ID => update "no"
              if (!outgoingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
                this.setDataAtRowProp(rowIndex, 'no', String(data.data.id).padStart(4, '0'), 'internal');
              }
              if (data.data.date_released) {
        this.setDataAtRowProp(rowIndex, 'date_released', data.data.date_released, 'internal');
    }
    if (data.data.quarter_label) {
        this.setDataAtRowProp(rowIndex, 'quarter_label', data.data.quarter_label, 'internal');
    }
              // Sync sub-tables if category changed
              const updatedCat = (data.data?.category || '').toLowerCase();

              // TRAVEL ORDER
              if (updatedCat === 'travel order') {
                const tmIndex = travelMemoData.findIndex(x => x.id == data.data.id);
                if (tmIndex === -1) {
                  travelMemoData.push({
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  });
                } else {
                  travelMemoData[tmIndex] = {
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  };
                }
                if (window.hotTravelMemo) {
                  window.hotTravelMemo.loadData(travelMemoData);
                }
              } else {
                // remove from Travel Memo if not travel order
                const tmIndex = travelMemoData.findIndex(x => x.id == data.data.id);
                if (tmIndex !== -1 && updatedCat !== 'travel order') {
                  travelMemoData.splice(tmIndex, 1);
                  if (window.hotTravelMemo) {
                    window.hotTravelMemo.loadData(travelMemoData);
                  }
                }
              }

              // ONO
              if (updatedCat === 'ono') {
                const onoIndex = onoData.findIndex(x => x.id == data.data.id);
                if (onoIndex === -1) {
                  onoData.push({
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  });
                } else {
                  onoData[onoIndex] = {
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  };
                }
                if (window.hotOno) {
                  window.hotOno.loadData(onoData);
                }
              } else {
                // remove from ONO if not ono
                const onoIndex = onoData.findIndex(x => x.id == data.data.id);
                if (onoIndex !== -1 && updatedCat !== 'ono') {
                  onoData.splice(onoIndex, 1);
                  if (window.hotOno) {
                    window.hotOno.loadData(onoData);
                  }
                }
              }
            })
            .catch(err => {
              toastr.error('Error saving outgoing record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/outgoings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('Outgoing record deleted successfully.');

                // Also remove from sub-categories if present
                const tmIndex = travelMemoData.findIndex(x => x.id === rowData.id);
                if (tmIndex !== -1) {
                  travelMemoData.splice(tmIndex, 1);
                  if (window.hotTravelMemo) {
                    window.hotTravelMemo.loadData(travelMemoData);
                  }
                }
                const onoIndex = onoData.findIndex(x => x.id === rowData.id);
                if (onoIndex !== -1) {
                  onoData.splice(onoIndex, 1);
                  if (window.hotOno) {
                    window.hotOno.loadData(onoData);
                  }
                }
              })
              .catch(() => {
                toastr.error('Error deleting outgoing record.');
              });
          }
        });
      },

      afterOnCellMouseDown: function(event, coords) {
        const incomingIdColIndex = outgoingsColumns.findIndex(col => col.data === 'incoming_id');
        if (coords.col === incomingIdColIndex) {
          const rowData = this.getSourceDataAtRow(coords.row);
          if (!rowData || !rowData.incoming_id) return;

          const incomingId = rowData.incoming_id;
          if (!window.hotIncomings) return;

          // Switch to Incomings tab
          const incomingTabTrigger = document.getElementById('incomings-tab');
          if (incomingTabTrigger) {
            const incomingTab = new bootstrap.Tab(incomingTabTrigger);
            incomingTab.show();

            setTimeout(() => {
              const rowIndex = incomingsData.findIndex(item => parseInt(item.id, 10) === parseInt(incomingId, 10));
              if (rowIndex !== -1) {
                window.hotIncomings.selectCell(rowIndex, 0);
                window.hotIncomings.scrollViewportTo(rowIndex, 0);
                highlightRow(window.hotIncomings, rowIndex);
              } else {
                toastr.warning('Incoming record not found.');
              }
            }, 300);
          }
        }
      }
    });

    // Search
    const searchOutgoings = document.getElementById('search-outgoings');
    if (searchOutgoings) {
      const searchPlugin = hotOutgoings.getPlugin('search');
      searchOutgoings.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotOutgoings.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotOutgoings = hotOutgoings;
  }


  /* ---------------------------------------------------------------------
   * 3) TRAVEL MEMO TABLE (category='TRAVEL ORDER')
   * ------------------------------------------------------------------- */
  const containerTravelMemo = document.getElementById('handsontable-travel-memo');
  let hotTravelMemo = null;

  if (containerTravelMemo) {
    const travelMemoArray = travelMemoData.map(item => ({
      id:           item.id,
      no:           item.no,
      quarter_label:item.quarter_label,
      o:            item.o,
      date_released:item.date_released,
      addressed_to: item.addressed_to,
      email:        item.email,
      travel_date:  item.travel_date,
      es_in_charge: item.es_in_charge
    }));

    const travelMemoColumns = [
      { data: 'quarter_label', title: 'QTR',  renderer: quarterLabelRenderer, readOnly: true },
      { data: 'No',            title: 'No.',  readOnly: true },
      { data: 'o',             title: 'O' },
      { data: 'date_released', title: 'DATE OF RELEASED' },
      { data: 'addressed_to',  title: 'ADDRESSED TO' },
      { data: 'email',         title: 'EMAIL' },
      { data: 'travel_date',   title: 'TRAVEL DATE' },
      { data: 'es_in_charge',  title: 'ES-INCHARGE' }
    ];

    hotTravelMemo = new Handsontable(containerTravelMemo, {
      data: travelMemoArray,
      colHeaders: travelMemoColumns.map(col => col.title),
      rowHeaders: true,
      dropdownMenu: true,
      filters: true,
      columnSorting: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation',

      height: 650,
      maxHeight: 650,
      minSpareRows: 1,

      columns: travelMemoColumns,

      afterChange: function(changes, source) {
        if (!changes) return;
        // we only save on direct user edits
        if (!['edit','Paste','Autofill','Undo'].includes(source)) {
          return;
        }

        let rowMap = {};
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
          if (oldVal !== newVal) {
            if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
            rowMap[rowIndex][prop] = newVal;
          }
        });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          const payload = {
            category:      'TRAVEL ORDER',
            o:             rowData.o,
            date_released: rowData.date_released,
            addressed_to:  rowData.addressed_to,
            email:         rowData.email,
            travel_date:   rowData.travel_date,
            es_in_charge:  rowData.es_in_charge
          };

          const outgoingId = rowData.id;
          const url    = outgoingId ? `/admin/outgoings/${outgoingId}` : `/admin/outgoings`;
          const method = outgoingId ? 'PUT' : 'POST';

          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('Travel Memo record saved successfully.');
              if (!outgoingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
                this.setDataAtRowProp(rowIndex, 'No', String(data.data.id).padStart(4, '0'), 'internal');
              }
              // sync in travelMemoData
              const idx = travelMemoData.findIndex(x => x.id == data.data.id);
              if (idx === -1) {
                travelMemoData.push({
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                });
              } else {
                travelMemoData[idx] = {
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                };
              }
              this.loadData(travelMemoData);
            })
            .catch(err => {
              toastr.error('Error saving Travel Memo record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/outgoings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('Travel Memo record deleted successfully.');
              })
              .catch(() => {
                toastr.error('Error deleting Travel Memo record.');
              });
          }
        });
      }
    });

    const searchTravelMemo = document.getElementById('search-travel-memo');
    if (searchTravelMemo) {
      const searchPlugin = hotTravelMemo.getPlugin('search');
      searchTravelMemo.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotTravelMemo.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotTravelMemo = hotTravelMemo;
  }


  /* ---------------------------------------------------------------------
   * 4) O NO. TABLE (category='ONO')
   * ------------------------------------------------------------------- */
  const containerOno = document.getElementById('handsontable-ono');
  let hotOno = null;

  if (containerOno) {
    const onoArray = onoData.map(item => ({
      id:            item.id,
      no:            item.no,
      o:             item.o,
      date_released: item.date_released,
      addressed_to:  item.addressed_to,
      subject:       item.subject,
      remarks:       item.remarks,
      libcap_no:     item.libcap_no,
      status:        item.status
    }));

    const onoColumns = [
      { data: 'No',            title: 'No.', readOnly: true },
      { data: 'o',             title: 'O' },
      { data: 'date_released', title: 'DATE OF RELEASED' },
      { data: 'addressed_to',  title: 'ADDRESSED TO' },
      { data: 'subject',       title: 'SUBJECT' },
      { data: 'remarks',       title: 'REMARKS' },
      { data: 'libcap_no',     title: 'LIBCAP #' },
      { data: 'status',        title: 'STATUS', renderer: statusHighlightRenderer }
    ];

    hotOno = new Handsontable(containerOno, {
      data: onoArray,
      colHeaders: onoColumns.map(col => col.title),
      rowHeaders: true,
      dropdownMenu: true,
      filters: true,
      columnSorting: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation',

      height: 500,
      maxHeight: 500,
      minSpareRows: 1,

      columns: onoColumns,

      afterChange: function(changes, source) {
        if (!changes) return;
        if (!['edit','Paste','Autofill','Undo'].includes(source)) {
          return;
        }

        let rowMap = {};
        changes.forEach(([rowIndex, prop, oldValue, newValue]) => {
          if (oldValue !== newValue) {
            if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
            rowMap[rowIndex][prop] = newValue;
          }
        });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          const payload = {
            category:          'ONO',
            o:                 rowData.o,
            date_released:     rowData.date_released,
            addressed_to:      rowData.addressed_to,
            subject_of_letter: rowData.subject,
            remarks:           rowData.remarks,
            libcap_no:         rowData.libcap_no,
            status:            rowData.status
          };

          const outgoingId = rowData.id;
          const url    = outgoingId ? `/admin/outgoings/${outgoingId}` : `/admin/outgoings`;
          const method = outgoingId ? 'PUT' : 'POST';

          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('O No. record saved successfully.');
              if (!outgoingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
                this.setDataAtRowProp(rowIndex, 'no', String(data.data.id).padStart(4, '0'), 'internal');
              }
              // re-sync in onoData
              const idx = onoData.findIndex(x => x.id == data.data.id);
              if (idx === -1) {
                onoData.push({
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                });
              } else {
                onoData[idx] = {
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                };
              }
              this.loadData(onoData);
            })
            .catch(err => {
              toastr.error('Error saving O No. record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/outgoings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('O No. record deleted successfully.');
              })
              .catch(() => {
                toastr.error('Error deleting O No. record.');
              });
          }
        });
      }
    });

    // Search
    const searchOno = document.getElementById('search-ono');
    if (searchOno) {
      const searchPlugin = hotOno.getPlugin('search');
      searchOno.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotOno.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotOno = hotOno;
  }
  function customDateRenderer(instance, td, row, col, prop, value, cellProperties) {
  // Use the standard TextRenderer as a base.
  Handsontable.renderers.TextRenderer.apply(this, arguments);

  // Get the entire row data.
  const rowData = instance.getSourceDataAtRow(row);
  
  // For an empty row (example: no "No" value) then mark it red.
  if (!rowData || !rowData.No) {
    td.style.backgroundColor = "#ffcccc"; // light red background
    return;
  }

  // If the record is not yet released, apply a date-based coloring.
  // (Assume that if date_released is empty, then it is not released.)
  if (!rowData.date_released && rowData.date_received) {
    const received = new Date(rowData.date_received);
    const now = new Date();
    const diffDays = Math.floor((now - received) / (1000 * 60 * 60 * 24));

    // Apply colors based on the number of days since the record was received.
    if (diffDays < 1) {
      td.style.backgroundColor = "#ccffcc"; // green
    } else if (diffDays < 3) {
      td.style.backgroundColor = "#ffffcc"; // light yellow
    } else if (diffDays >= 7) {
      td.style.backgroundColor = "#ffcccc"; // red
    } else {
      td.style.backgroundColor = ""; // no special color
    }
  }
}


  /* ---------------------------------------------------------------------
   * 5) CLICK HANDLER FOR OUTGOING LINKS (to jump from Incoming -> Outgoing)
   * ------------------------------------------------------------------- */
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('outgoing-link')) {
      e.preventDefault();
      const outgoingId = e.target.dataset.id;
      const outgoingTabTrigger = document.getElementById('outgoings-tab');
      if (outgoingTabTrigger) {
        const outgoingTab = new bootstrap.Tab(outgoingTabTrigger);
        outgoingTab.show();
      }
      setTimeout(() => {
        const rowIndex = outgoingsData.findIndex(item => parseInt(item.id, 10) === parseInt(outgoingId, 10));
        if (rowIndex !== -1 && window.hotOutgoings) {
          window.hotOutgoings.selectCell(rowIndex, 0);
          window.hotOutgoings.scrollViewportTo(rowIndex, 0);
          highlightRow(window.hotOutgoings, rowIndex);
        } else {
          toastr.warning('Outgoing record not found.');
        }
      }, 300);
    }
  });

});
</script>
<script>
  /**
 * Add this to your JavaScript file to ensure search works across all Handsontable versions
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize search functionality for each table
  addSearchFunctionality('search-incomings', 'handsontable-incomings', window.hotIncomings);
  addSearchFunctionality('search-outgoings', 'handsontable-outgoings', window.hotOutgoings);
  addSearchFunctionality('search-travel-memo', 'handsontable-travel-memo', window.hotTravelMemo);
  addSearchFunctionality('search-ono', 'handsontable-ono', window.hotOno);
  
  // Add the search highlight style
  const style = document.createElement('style');
  style.textContent = `
    .ht__highlight {
      background-color: rgba(255, 237, 51, 0.3) !important;
    }
    .ht__active_highlight {
      background-color: rgba(255, 237, 51, 0.7) !important;
    }
  `;
  document.head.appendChild(style);
  
  /**
   * Attach search functionality to a table using the search input
   */
  function addSearchFunctionality(searchInputId, tableContainerId, hotInstance) {
    const searchInput = document.getElementById(searchInputId);
    const tableContainer = document.getElementById(tableContainerId);
    
    if (!searchInput || !tableContainer || !hotInstance) return;
    
    let searchTimeout;
    let currentHighlight = null;
    let searchMatches = [];
    let currentMatchIndex = 0;
    
    // Function to highlight all matches
    function highlightMatches(query) {
      // Clear previous highlights first
      clearHighlights();
      
      if (!query) return;
      
      // Convert query to lowercase for case-insensitive search
      const queryLower = query.toLowerCase();
      searchMatches = [];
      
      // Search through all data
      const data = hotInstance.getData();
      if (!data) return;
      
      for (let row = 0; row < data.length; row++) {
        for (let col = 0; col < data[row].length; col++) {
          const cellValue = String(data[row][col] || '').toLowerCase();
          if (cellValue.includes(queryLower)) {
            searchMatches.push({row, col});
          }
        }
      }
      
      // Apply highlight classes to all matches
      searchMatches.forEach(({row, col}) => {
        const meta = hotInstance.getCellMeta(row, col);
        const className = meta.className || '';
        meta.className = `${className} ht__highlight`.trim();
        hotInstance.setCellMeta(row, col, 'className', meta.className);
      });
      
      // Highlight the first match with active highlight
      if (searchMatches.length > 0) {
        currentMatchIndex = 0;
        highlightActiveMatch();
        
        // Report the number of matches found
        toastr.info(`Found ${searchMatches.length} matches`, null, {timeOut: 2000});
      } else {
        toastr.warning('No matches found', null, {timeOut: 2000});
      }
      
      hotInstance.render();
    }
    
    // Function to highlight the currently active match
    function highlightActiveMatch() {
      if (searchMatches.length === 0) return;
      
      const {row, col} = searchMatches[currentMatchIndex];
      
      // Add active highlight class
      const meta = hotInstance.getCellMeta(row, col);
      const baseClassName = meta.className.replace('ht__active_highlight', '').trim();
      meta.className = `${baseClassName} ht__active_highlight`;
      hotInstance.setCellMeta(row, col, 'className', meta.className);
      
      // Select and scroll to the cell
      hotInstance.selectCell(row, col);
      hotInstance.scrollViewportTo(row, col);
      
      hotInstance.render();
    }
    
    // Function to clear all highlights
    function clearHighlights() {
      const data = hotInstance.getData();
      if (!data) return;
      
      for (let row = 0; row < hotInstance.countRows(); row++) {
        for (let col = 0; col < hotInstance.countCols(); col++) {
          const meta = hotInstance.getCellMeta(row, col);
          if (meta.className) {
            meta.className = meta.className
              .replace('ht__highlight', '')
              .replace('ht__active_highlight', '')
              .trim();
            
            hotInstance.setCellMeta(row, col, 'className', meta.className || null);
          }
        }
      }
      
      searchMatches = [];
      currentMatchIndex = 0;
      hotInstance.render();
    }
    
    // Search input handler with debounce
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      
      const query = this.value.trim();
      
      searchTimeout = setTimeout(() => {
        if (query) {
          highlightMatches(query);
        } else {
          clearHighlights();
        }
      }, 300);
    });
    
    // Add "Next" button next to search
    const nextButton = document.createElement('button');
    nextButton.className = 'excel-button';
    nextButton.innerHTML = '<i class="fas fa-arrow-down"></i>';
    nextButton.title = 'Find next (F3)';
    nextButton.style.marginLeft = '8px';
    nextButton.onclick = findNext;
    
    // Insert the button after the search input
    searchInput.parentNode.insertAdjacentElement('afterend', nextButton);
    
    // Find next match function
    function findNext() {
      if (searchMatches.length === 0) return;
      
      // Move to the next match
      currentMatchIndex = (currentMatchIndex + 1) % searchMatches.length;
      highlightActiveMatch();
    }
    
    // Find previous match function
    function findPrevious() {
      if (searchMatches.length === 0) return;
      
      // Move to the previous match
      currentMatchIndex = (currentMatchIndex - 1 + searchMatches.length) % searchMatches.length;
      highlightActiveMatch();
    }
    
    // Add keyboard shortcut for F3 (find next)
    document.addEventListener('keydown', function(e) {
      if (e.key === 'F3') {
        e.preventDefault();
        findNext();
      } else if (e.key === 'F3' && e.shiftKey) {
        e.preventDefault();
        findPrevious();
      }
    });
    
    // Add a "Previous" button
    const prevButton = document.createElement('button');
    prevButton.className = 'excel-button';
    prevButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    prevButton.title = 'Find previous (Shift+F3)';
    prevButton.onclick = findPrevious;
    
    // Insert the button after the next button
    nextButton.insertAdjacentElement('afterend', prevButton);
  }
  
});

</script>
@endpush
