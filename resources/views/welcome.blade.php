<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CHED-eTrack</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Styles via Vite (or your build tool) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        /*********************************************
         * THEME VARIABLES & GRADIENTS
         *********************************************/
        :root {
            /* Light Mode Colors */
            --clr-bg: #f8fafc;
            --clr-bg-alt: #f0f2f5;
            --clr-text: #1e293b;
            --clr-text-muted: #64748b;
            --clr-primary: #0c3a87;
            --clr-primary-light: #2563eb;
            --clr-secondary: #e11d48;
            --clr-secondary-light: #fb7185;
            --clr-accent: #fbbf24;
            --clr-accent-light: #fde68a;
            --clr-success: #16a34a;
            --clr-processing: #f59e0b;

            --clr-card: #ffffff;
            --clr-border: #e2e8f0;
            --clr-input-bg: #ffffff;
            --clr-input-border: #cbd5e1;
            --clr-input-focus: #3b82f6;

            /* Gradient for Navbar (Light) */
            --gradient-navbar: linear-gradient(135deg, #0c3a87 0%, #1d4ed8 100%);
            
            /* Hero Gradient (Light) */
            --gradient-hero: linear-gradient(135deg, #4338ca 0%, #3b82f6 100%);
            
            /* Card Gradients (Light) */
            --gradient-card: linear-gradient(135deg, rgba(255,255,255,0.7), rgba(255,255,255,0.3));
            
            /* Box Shadow (Light) */
            --box-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --box-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

            /* Glassmorphism */
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: 1px solid rgba(255, 255, 255, 0.18);
            --glass-backdrop: blur(12px) saturate(180%);
        }

        [data-theme="dark"] {
            /* Dark Mode Colors */
            --clr-bg: #0f172a;
            --clr-bg-alt: #1e293b;
            --clr-text: #f1f5f9;
            --clr-text-muted: #94a3b8;
            --clr-primary: #3b82f6;
            --clr-primary-light: #60a5fa;
            --clr-secondary: #f43f5e;
            --clr-secondary-light: #fb7185;
            --clr-accent: #facc15;
            --clr-accent-light: #fde68a;
            --clr-success: #22c55e;
            --clr-processing: #fbbf24;

            --clr-card: #1e293b;
            --clr-border: #334155;
            --clr-input-bg: #1e293b;
            --clr-input-border: #475569;
            --clr-input-focus: #60a5fa;

            /* Gradient for Navbar (Dark) */
            --gradient-navbar: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            
            /* Hero Gradient (Dark) */
            --gradient-hero: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            
            /* Card Gradients (Dark) */
            --gradient-card: linear-gradient(135deg, rgba(30,41,59,0.7), rgba(30,41,59,0.3));
            
            /* Box Shadow (Dark) */
            --box-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.15);
            --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.15);
            --box-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.15);

            /* Glassmorphism */
            --glass-bg: rgba(15, 23, 42, 0.25);
            --glass-border: 1px solid rgba(30, 41, 59, 0.18);
            --glass-backdrop: blur(12px) saturate(180%);
        }

        /********************************
         * GLOBAL BASE STYLES
         ********************************/
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--clr-bg);
            color: var(--clr-text);
            transition: background-color 0.4s ease, color 0.4s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            scroll-behavior: smooth;
        }
        
        a {
            text-decoration: none;
            color: var(--clr-primary);
            transition: color 0.2s ease;
        }
        
        a:hover {
            color: var(--clr-primary-light);
        }
        
        .container {
            max-width: 1280px;
            padding: 0 1.5rem;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        h1 {
            font-size: 3.5rem;
        }
        
        h2 {
            font-size: 2.5rem;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }
            
            h2 {
                font-size: 2rem;
            }
        }
        
        .text-gradient {
            background: linear-gradient(to right, var(--clr-primary), var(--clr-primary-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2.5rem;
            font-weight: 700;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -0.5rem;
            height: 4px;
            width: 60px;
            background: var(--clr-primary);
            border-radius: 2px;
        }
        
        .text-center .section-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        /********************************
         * NAVBAR
         ********************************/
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--gradient-navbar);
            padding: 1rem 0;
            transition: padding 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
        }
        
        .navbar.scrolled {
            padding: 0.75rem 0;
            box-shadow: var(--box-shadow);
        }
        
        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
            color: #fff;
        }
        
        .navbar-brand img {
            height: 40px;
            width: auto;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link .icon {
            font-size: 1.1rem;
        }
        
        .theme-toggle-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        .theme-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        @media (max-width: 991.98px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-links {
                position: fixed;
                top: 0;
                left: -100%;
                width: 80%;
                max-width: 300px;
                height: 100vh;
                background: var(--clr-card);
                flex-direction: column;
                align-items: flex-start;
                padding: 2rem 1.5rem;
                gap: 1.5rem;
                transition: left 0.3s ease;
                z-index: 1001;
                overflow-y: auto;
                box-shadow: var(--box-shadow-xl);
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .nav-link {
                color: var(--clr-text);
                width: 100%;
                padding: 0.75rem 1rem;
            }
            
            .nav-link:hover, .nav-link.active {
                color: var(--clr-primary);
                background: var(--clr-bg-alt);
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(3px);
                z-index: 1000;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }
            
            .overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .close-menu {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: transparent;
                border: none;
                color: var(--clr-text);
                font-size: 1.5rem;
                cursor: pointer;
            }
        }

        /********************************
         * HERO SECTION
         ********************************/
        .hero-section {
            position: relative;
            padding: 8rem 0 6rem;
            background: var(--gradient-hero);
            overflow: hidden;
            margin-top: 0;
        }
        
        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .hero-shape {
            position: absolute;
            bottom: -5rem;
            left: 0;
            width: 100%;
            height: 10rem;
            background-color: var(--clr-bg);
            clip-path: polygon(0 100%, 100% 100%, 100% 25%, 0 75%);
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            align-items: center;
            gap: 3rem;
        }
        
        .hero-text {
            color: #fff;
        }
        
        .hero-text h1 {
            margin-bottom: 1.5rem;
            font-size: 3.5rem;
            line-height: 1.1;
        }
        
        .hero-text p {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .hero-text strong {
            color: var(--clr-accent);
            font-weight: 600;
        }
        
        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .hero-image img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 0 20px rgba(0, 0, 0, 0.2));
            transition: transform 0.5s ease;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .btn-hero {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .btn-hero:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            color: #fff;
        }
        
        .btn-hero .icon {
            font-size: 1.25rem;
        }
        
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2.5rem;
        }
        
        .stat-item {
            display: flex;
            flex-direction: column;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        @media (max-width: 991.98px) {
            .hero-section {
                padding: 7rem 0 5rem;
            }
            
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .hero-text h1 {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                justify-content: center;
            }
            
            .section-title::after {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        /********************************
         * FEATURES SECTION
         ********************************/
        .features-section {
            padding: 5rem 0;
            background-color: var(--clr-bg);
            position: relative;
            overflow: hidden;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .feature-card {
            background: var(--clr-card);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--box-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 1px solid var(--clr-border);
        }
        
        .feature-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-card);
            opacity: 0.05;
            pointer-events: none;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--box-shadow-lg);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--clr-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--clr-text);
            font-size: 2rem;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
            background: var(--clr-primary);
            color: #fff;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--clr-text);
        }
        
        .feature-desc {
            color: var(--clr-text-muted);
            line-height: 1.6;
        }
        
        .features-scroller {
            overflow-x: auto;
            padding: 1rem 0;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--clr-primary) var(--clr-border);
        }
        
        .features-scroller::-webkit-scrollbar {
            height: 6px;
        }
        
        .features-scroller::-webkit-scrollbar-track {
            background: var(--clr-border);
            border-radius: 3px;
        }
        
        .features-scroller::-webkit-scrollbar-thumb {
            background-color: var(--clr-primary);
            border-radius: 3px;
        }
        
        .features-scroller .features-grid {
            width: max-content;
            display: flex;
            gap: 2rem;
        }
        
        .features-scroller .feature-card {
            width: 280px;
            flex: 0 0 auto;
        }
        
        @media (min-width: 992px) {
            .features-scroller {
                overflow: visible;
            }
            
            .features-scroller .features-grid {
                width: auto;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /********************************
         * UPLOAD TUTORIAL SECTION
         ********************************/
        .tutorial-section {
            padding: 5rem 0;
            background-color: var(--clr-bg-alt);
            position: relative;
        }
        
        .steps-container {
            margin-top: 3rem;
        }
        
        .step-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1.5rem;
            padding: 2rem;
            background: var(--clr-card);
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--clr-border);
            position: relative;
        }
        
        .step-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-lg);
        }
        
        .step-item::after {
            content: '';
            position: absolute;
            left: 3rem;
            bottom: -2rem;
            width: 2px;
            height: 2rem;
            background: var(--clr-primary);
            display: none;
        }
        
        .step-item:not(:last-child)::after {
            display: block;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--clr-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        
        .step-content {
            display: flex;
            flex-direction: column;
        }
        
        .step-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--clr-text);
        }
        
        .step-desc {
            color: var(--clr-text-muted);
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .step-item {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .step-number {
                margin: 0 auto 1rem;
            }
            
            .step-item::after {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        /********************************
         * COMMON COMPONENTS
         ********************************/
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: var(--clr-primary);
            color: #fff;
        }
        
        .btn-primary:hover {
            background: var(--clr-primary-light);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background: var(--clr-success);
            color: #fff;
        }
        
        .btn-success:hover {
            background: var(--clr-success);
            filter: brightness(1.1);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.125rem;
        }
        
        .shadow-container {
            background: var(--clr-card);
            border-radius: 1rem;
            box-shadow: var(--box-shadow);
            padding: 2rem;
            border: 1px solid var(--clr-border);
            position: relative;
            overflow: hidden;
        }
        
        .shadow-container::after {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--gradient-card);
            opacity: 0.05;
            pointer-events: none;
        }
        
        .glass-card {
            background: var(--glass-bg);
            border-radius: 1rem;
            border: var(--glass-border);
            backdrop-filter: var(--glass-backdrop);
            -webkit-backdrop-filter: var(--glass-backdrop);
            padding: 2rem;
            box-shadow: var(--box-shadow);
        }
        
        .divider {
            width: 100%;
            height: 1px;
            background: var(--clr-border);
            margin: 2rem 0;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-primary {
            background: rgba(59, 130, 246, 0.1);
            color: var(--clr-primary);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .badge-success {
            background: rgba(22, 163, 74, 0.1);
            color: var(--clr-success);
            border: 1px solid rgba(22, 163, 74, 0.2);
        }
        
        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--clr-processing);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .badge-danger {
            background: rgba(225, 29, 72, 0.1);
            color: var(--clr-secondary);
            border: 1px solid rgba(225, 29, 72, 0.2);
        }

        /********************************
         * FORM ELEMENTS
         ********************************/
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--clr-text);
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            color: var(--clr-text);
            background-color: var(--clr-input-bg);
            border: 1px solid var(--clr-input-border);
            border-radius: 0.5rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--clr-input-focus);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        
        .form-text {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--clr-text-muted);
        }
        
        .form-check {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.25rem;
            appearance: none;
            background-color: var(--clr-input-bg);
            border: 1px solid var(--clr-input-border);
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        
        .form-check-input:checked {
            background-color: var(--clr-primary);
            border-color: var(--clr-primary);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 14px;
        }
        
        .form-check-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        
        .form-check-label {
            flex: 1;
            font-size: 1rem;
            color: var(--clr-text);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        /* FilePond customization */
        .filepond--panel-root {
            background-color: var(--clr-input-bg) !important;
            border: 1px dashed var(--clr-input-border) !important;
        }
        
        .filepond--drop-label {
            color: var(--clr-text-muted) !important;
        }
        
        .filepond--label-action {
            color: var(--clr-primary) !important;
            text-decoration: underline;
        }
        
        .filepond--item-panel {
            background-color: var(--clr-primary) !important;
        }
        
        .filepond--root {
            margin-bottom: 0 !important;
        }
        
        /********************************
         * UPLOAD SECTION
         ********************************/
        #upload-section-container {
            display: none; /* hidden by default */
            padding: 6rem 0;
        }
        
        .upload-form {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .upload-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .upload-header h2 {
            color: var(--clr-text);
            margin-bottom: 1rem;
        }
        
        .upload-header p {
            color: var(--clr-text-muted);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-container {
            padding: 2rem;
            background: var(--clr-card);
            border-radius: 1rem;
            box-shadow: var(--box-shadow-lg);
            border: 1px solid var(--clr-border);
        }
        
        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--clr-border);
            color: var(--clr-text);
        }
        
        .filepond-wrapper {
            background-color: var(--clr-input-bg);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .upload-guidelines {
            background-color: var(--clr-bg-alt);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .upload-guidelines ul {
            margin: 0.5rem 0 0 1rem;
            padding: 0;
        }
        
        .upload-guidelines li {
            margin-bottom: 0.5rem;
            color: var(--clr-text-muted);
            font-size: 0.875rem;
        }
        
        /********************************
         * TRACK SECTION
         ********************************/
        #track-section-container {
            display: none; /* hidden by default */
            padding: 6rem 0;
        }
        
        .track-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .track-form {
            max-width: 500px;
            margin: 0 auto 3rem;
        }
        
        .tracking-result {
            margin-top: 2rem;
        }
        
        .status-banner {
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .status-banner.pending {
            background-color: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .status-banner.approved {
            background-color: rgba(22, 163, 74, 0.1);
            border: 1px solid rgba(22, 163, 74, 0.2);
        }
        
        .status-banner.rejected {
            background-color: rgba(225, 29, 72, 0.1);
            border: 1px solid rgba(225, 29, 72, 0.2);
        }
        
        .status-banner h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .status-banner p {
            margin: 0;
            color: var(--clr-text-muted);
        }
        
        /* Status Stepper */
        .stepper-container {
            margin: 3rem 0;
            position: relative;
        }
        
        .stepper {
            display: flex;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }
        
        .stepper::before {
            content: '';
            position: absolute;
            top: 2rem;
            left: 0;
            right: 0;
            height: 3px;
            background-color: var(--clr-border);
            z-index: -1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        
        .step-number {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            background-color: var(--clr-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 3px solid var(--clr-card);
            position: relative;
            z-index: 2;
        }
        
        .step-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--clr-text);
            text-align: center;
            max-width: 120px;
        }
        
        .bg-submitted {
            background-color: var(--clr-primary);
        }
        
        .bg-processing {
            background-color: var(--clr-processing);
            color: #fff;
        }
        
        .bg-approved {
            background-color: var(--clr-success);
        }
        
        .bg-rejected {
            background-color: var(--clr-secondary);
        }
        
        .active-step .step-number {
            transform: scale(1.1);
            box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.2);
            animation: pulse 1.5s infinite alternate;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            100% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        }
        
        .ongoing-animation {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translate(-50%, -120%);
            color: var(--clr-accent);
            font-weight: 600;
            font-size: 0.8rem;
            background: rgba(250, 204, 21, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            white-space: nowrap;
            display: none;
        }
        
        .ongoing-animation.show {
            display: block;
            animation: fadeInOut 2s infinite;
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }
        
        .completed-step .step-number::after {
            content: '✓';
            position: absolute;
            font-size: 1.5rem;
        }
        
        .status-details {
            background-color: var(--clr-card);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 2rem;
            border: 1px solid var(--clr-border);
            box-shadow: var(--box-shadow);
            opacity: 0;
            display: none;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        
        .status-details.show {
            opacity: 1;
            display: block;
            transform: translateY(0);
        }
        
        .status-details h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--clr-text);
        }
        
        .status-details p {
            color: var(--clr-text-muted);
            margin-bottom: 0.5rem;
        }
        
        .file-list {
            margin-top: 1rem;
            padding-left: 1.5rem;
        }
        
        .file-list li {
            margin-bottom: 0.5rem;
            color: var(--clr-text-muted);
            position: relative;
        }
        
        .file-list li::before {
            content: '•';
            position: absolute;
            left: -1rem;
            color: var(--clr-primary);
        }
        
        @media (max-width: 768px) {
            .stepper {
                flex-direction: column;
                align-items: flex-start;
                gap: 2rem;
            }
            
            .stepper::before {
                top: 0;
                bottom: 0;
                left: 2rem;
                right: auto;
                width: 3px;
                height: auto;
            }
            
            .step {
                flex-direction: row;
                align-items: center;
                gap: 1.5rem;
                width: 100%;
            }
            
            .step-title {
                margin-bottom: 0;
                text-align: left;
                max-width: none;
            }
            
            .ongoing-animation {
                left: auto;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
            }
        }
        
        /********************************
         * PRIVACY & CONTACT SECTIONS
         ********************************/
        #privacy-section-container,
        #contact-section-container {
            display: none; /* hidden by default */
            padding: 6rem 0;
        }
        
        .privacy-content,
        .contact-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .privacy-header,
        .contact-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .privacy-body h3,
        .contact-body h3 {
            color: var(--clr-text);
            margin: 2rem 0 1rem;
            font-size: 1.5rem;
        }
        
        .privacy-body p,
        .contact-body p {
            color: var(--clr-text-muted);
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        
        .contact-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--clr-border);
        }
        
        .contact-item:last-child {
            border-bottom: none;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--clr-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .contact-info h4 {
            margin: 0 0 0.5rem;
            color: var(--clr-text);
            font-size: 1.125rem;
        }
        
        .contact-info p {
            margin: 0;
            color: var(--clr-text-muted);
        }
        
        /********************************
         * MODALS
         ********************************/
        .modal-backdrop {
            background-color: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
        }
        
        .modal-content {
            background-color: var(--clr-card);
            border-radius: 1rem;
            border: 1px solid var(--clr-border);
            box-shadow: var(--box-shadow-xl);
            color: var(--clr-text);
            overflow: hidden;
        }
        
        .modal-header {
            border-bottom: 1px solid var(--clr-border);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--clr-text);
            margin: 0;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 1px solid var(--clr-border);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
        }
        
        .btn-close {
            background: transparent;
            border: none;
            color: var(--clr-text-muted);
            font-size: 1.5rem;
            line-height: 1;
            padding: 0.25rem;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .btn-close:hover {
            color: var(--clr-secondary);
        }
        
        /* SweetAlert2 Customization */
        .swal2-popup {
            background-color: var(--clr-card) !important;
            color: var(--clr-text) !important;
            border-radius: 1rem !important;
            padding: 2rem !important;
        }
        
        .swal2-title {
            color: var(--clr-text) !important;
        }
        
        .swal2-html-container {
            color: var(--clr-text-muted) !important;
        }
        
        .swal2-confirm {
            background-color: var(--clr-primary) !important;
        }
        
        .swal2-deny, .swal2-cancel {
            background-color: var(--clr-secondary) !important;
        }
        
        /********************************
         * FOOTER
         ********************************/
        .footer {
            background-color: var(--clr-primary);
            color: #fff;
            padding: 3rem 0;
            margin-top: auto;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .footer-brand {
            display: flex;
            flex-direction: column;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .footer-logo img {
            height: 40px;
            width: auto;
        }
        
        .footer-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }
        
        .footer-description {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .footer-social {
            display: flex;
            gap: 1rem;
        }
        
        .social-link {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background-color: #fff;
            color: var(--clr-primary);
            transform: translateY(-3px);
        }
        
        .footer-links h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 1.25rem;
            position: relative;
            padding-bottom: 0.75rem;
        }
        
        .footer-links h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 50px;
            background-color: var(--clr-accent);
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.2s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-links a:hover {
            color: #fff;
            transform: translateX(5px);
        }
        
        .footer-contact {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .contact-icon {
            font-size: 1.25rem;
            color: var(--clr-accent);
            flex-shrink: 0;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
        }
        
        .footer-bottom p {
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .footer-logo {
                justify-content: center;
            }
            
            .footer-social {
                justify-content: center;
            }
            
            .footer-links h4::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .footer-links a:hover {
                transform: none;
            }
            
            .contact-item {
                justify-content: center;
            }
        }
        
        /********************************
         * ANIMATIONS & TRANSITIONS
         ********************************/
        [data-aos] {
            opacity: 0;
            transition-property: transform, opacity;
            transition-duration: 0.6s;
            transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        [data-aos="fade-up"] {
            transform: translateY(50px);
        }
        
        [data-aos="fade-down"] {
            transform: translateY(-50px);
        }
        
        [data-aos="fade-right"] {
            transform: translateX(-50px);
        }
        
        [data-aos="fade-left"] {
            transform: translateX(50px);
        }
        
        [data-aos="zoom-in"] {
            transform: scale(0.9);
        }
        
        [data-aos="fade-up-right"] {
            transform: translate(-30px, 30px);
        }
        
        [data-aos="fade-up-left"] {
            transform: translate(30px, 30px);
        }
        
        [data-aos="fade-down-right"] {
            transform: translate(-30px, -30px);
        }
        
        [data-aos="fade-down-left"] {
            transform: translate(30px, -30px);
        }
        
        [data-aos].aos-animate {
            opacity: 1;
            transform: translate(0) scale(1);
        }
        
        .page-transition {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .page-transition.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="#" class="navbar-brand" id="nav-home">
                <img src="{{ asset('images/logo.png') }}" alt="CHED-eTrack Logo">
                <span>CHED-eTrack</span>
            </a>
            
            <button class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="overlay" id="overlay"></div>
            
            <ul class="nav-links" id="nav-links">
                <button class="close-menu" id="close-menu" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
                <li>
                    <a href="#" class="nav-link active" id="nav-features">
                        <i class="fas fa-star icon"></i>
                        <span>Features</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" id="nav-upload">
                        <i class="fas fa-cloud-upload-alt icon"></i>
                        <span>Upload</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" id="nav-track">
                        <i class="fas fa-search-location icon"></i>
                        <span>Track</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" id="nav-privacy">
                        <i class="fas fa-user-shield icon"></i>
                        <span>Privacy</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" id="nav-contact">
                        <i class="fas fa-address-book icon"></i>
                        <span>Contact</span>
                    </a>
                </li>
            </ul>
            
            <button class="theme-toggle-btn" id="theme-toggle" aria-label="Toggle Theme">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </nav>

    <!-- HOMEPAGE SECTION -->
    <div id="homepage-section" class="page-transition show">
        <!-- HERO SECTION -->
        <section class="hero-section">
            <div class="hero-particles" id="hero-particles"></div>
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text" data-aos="fade-right">
                        <h1>Welcome to <span class="text-gradient">CHED-eTrack</span></h1>
                        <p>
                            A comprehensive toolkit for managing and tracking your documents efficiently.
                            Seamlessly upload your files and monitor their status through every stage.
                        </p>
                        <p>
                            <strong>Once a document is successfully uploaded, you will receive an email notification confirming receipt and details of your submission.</strong>
                        </p>
                        <div class="d-flex gap-3 mt-4">
                            <a href="#" class="btn-hero" id="hero-upload-btn">
                                <i class="fas fa-cloud-upload-alt icon"></i>
                                Upload Document
                            </a>
                            <a href="#" class="btn-hero" id="hero-track-btn">
                                <i class="fas fa-search icon"></i>
                                Track Document
                            </a>
                        </div>
                        
                        <div class="hero-stats">
                            <div class="stat-item">
                                <span class="stat-value">95%</span>
                                <span class="stat-label">Success Rate</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">2500+</span>
                                <span class="stat-label">Monthly Users</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">24/7</span>
                                <span class="stat-label">Support</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hero-image" data-aos="fade-left">
                        <img src="{{ asset('images/logo.png') }}" alt="CHED-eTrack Logo">
                    </div>
                </div>
            </div>
            <div class="hero-shape"></div>
        </section>

        <!-- FEATURES SECTION -->
        <section class="features-section" id="features">
            <div class="container">
                <div class="text-center" data-aos="fade-up">
                    <h2 class="section-title">Key Features</h2>
                    <p class="text-muted mb-5">Everything you need to manage your documents in one place</p>
                </div>
                
                <div class="features-grid" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="150">
                        <div class="feature-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <h3 class="feature-title">Upload Documents</h3>
                        <p class="feature-desc">Effortlessly upload PDF, DOC, and DOCX files from anywhere, anytime.</p>
                    </div>
                    
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="feature-title">Track Status</h3>
                        <p class="feature-desc">Monitor documents through submission, processing, and approval in real-time.</p>
                    </div>
                    
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="250">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Secure System</h3>
                        <p class="feature-desc">Data privacy and secure document handling are our top priority.</p>
                    </div>
                    
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="feature-title">Real-Time Alerts</h3>
                        <p class="feature-desc">Receive email notifications for every important update and status change.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- TUTORIAL SECTION -->
        <section class="tutorial-section" id="tutorial">
            <div class="container">
                <div class="text-center" data-aos="fade-up">
                    <h2 class="section-title">How to Upload Your Document</h2>
                    <p class="text-muted mb-5">Follow these simple steps to upload and track your documents</p>
                </div>
                
                <div class="steps-container">
                    <div class="step-item" data-aos="fade-up">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3 class="step-title">Prepare Your Files</h3>
                            <p class="step-desc">Gather all files you plan to upload. Make sure they are in PDF, DOC, or DOCX format and under 5MB in size.</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3 class="step-title">Open the Upload Form</h3>
                            <p class="step-desc">Click "Upload" in the navigation bar. Fill out your contact information accurately for seamless communication.</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3 class="step-title">Attach Files & Add Details</h3>
                            <p class="step-desc">Select or drag-and-drop your files into the upload area. Provide details such as document type and submission purpose.</p>
                        </div>
                    </div>
                    
                    <div class="step-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3 class="step-title">Submit & Verify</h3>
                            <p class="step-desc">Review the Terms & Conditions, then submit. Check your phone for an OTP verification code to finalize. An email will confirm your successful upload!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- UPLOAD SECTION -->
    <div class="container page-transition" id="upload-section-container">
        <div class="upload-header" data-aos="fade-up">
            <h2 class="section-title">Upload a Document</h2>
            <p>Submit your documents for processing with our secure and efficient system</p>
        </div>
        
        <div class="upload-form" data-aos="fade-up" data-aos-delay="100">
            <div class="shadow-container form-container">
                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data" id="upload-form">
                    @csrf
                    <h4 class="form-section-title">Personal Information</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
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
                        
                        <div class="col-md-6 form-group">
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
                        
                        <div class="col-md-6 form-group">
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
                                Enter a valid **Philippine** phone number in the format: <strong>+63 912 345 6789</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-6 form-group">
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
                                <option value="Foreign Travel">Foreign Travel</option>
                                <option value="Justification">Justification</option>
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
                                <option value="Request for Dissemination">Request for Dissemination</option>
                                <option value="Request for Quotation">Request for Quotation</option>
                                <option value="SUC Travel (assessment)">SUC Travel (assessment)</option>
                                <option value="Vetting">Vetting (assessment)</option>
                            </select>
                        </div>
                    </div>
                    
                    <h4 class="form-section-title mt-4">Document Information</h4>
                    <div class="form-group">
                        <label for="details" class="form-label">Document Details</label>
                        <textarea 
                            class="form-control sanitize-input"
                            id="details"
                            name="details"
                            placeholder="Please provide specific details about your document"
                            rows="3"
                            required
                        ></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="purpose" class="form-label">Purpose of Submission</label>
                        <textarea 
                            class="form-control sanitize-input" 
                            id="purpose"
                            name="purpose"
                            placeholder="Explain why you are submitting this document"
                            rows="3"
                            required
                        ></textarea>
                    </div>
                    
                    <h4 class="form-section-title mt-4">File Upload</h4>
                    <div class="form-group">
                        <label for="document" class="form-label">Upload Document(s)</label>
                        <div class="filepond-wrapper">
                            <input 
                                type="file"
                                name="document[]"
                                id="document"
                                class="filepond"
                                multiple
                                required
                            />
                        </div>
                        
                        <div class="upload-guidelines mt-3">
                            <p class="mb-2"><i class="fas fa-info-circle me-2"></i><strong>Upload Guidelines:</strong></p>
                            <ul>
                                <li>Allowed formats: PDF, DOC, DOCX</li>
                                <li>Maximum file size: 5MB per file</li>
                                <li>Use correct file extensions (e.g., <code>sample.pdf</code>, not <code>sample.pdf.pdf</code>)</li>
                                <li>Files will show a green check once processed successfully</li>
                                <li><strong>Note:</strong> You will receive an email notification once your submission is received</li>
                            </ul>
                        </div>
                    </div>
                    
                    <input type="hidden" name="approval_status" value="Pending">
                    
                    <div class="form-check mt-4">
                        <input 
                            id="agree_terms"
                            name="agree_terms"
                            type="checkbox"
                            class="form-check-input"
                            required
                        >
                        <label for="agree_terms" class="form-check-label">
                            I agree to the <a href="#terms-modal" id="view-terms">terms and conditions</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2 mt-4">
                        <i class="fas fa-paper-plane me-2"></i> Submit Document
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TRACK SECTION -->
    <div class="container page-transition" id="track-section-container">
        <div class="track-header" data-aos="fade-up">
            <h2 class="section-title">Track Your Document</h2>
            <p>Monitor the status of your submitted documents in real-time</p>
        </div>
        
        <div class="track-form shadow-container" data-aos="fade-up" data-aos-delay="100">
            <form method="GET" action="{{ route('documents.track') }}" id="track-form">
                @csrf
                <div class="form-group">
                    <label for="tracking_number" class="form-label">Tracking Number</label>
                    <div class="input-group">
                        <input 
                            type="text"
                            class="form-control sanitize-input"
                            id="tracking_number"
                            name="tracking_number"
                            placeholder="Enter your tracking number"
                            required
                        >
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search me-2"></i> Track
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Pending Status Message -->
        <div id="pending-status-message" class="status-banner pending mt-4" style="display: none;">
            <div class="badge badge-warning">
                <i class="fas fa-clock me-1"></i> Pending
            </div>
            <h3>Your document is currently pending approval</h3>
            <p>We are reviewing your submission. Please check back later for updates.</p>
        </div>
        
        <!-- Status Stepper -->
        <div id="stepper-container" class="stepper-container" style="display: none;">
            <div class="stepper">
                <!-- Step 1: Submitted -->
                <div class="step" id="stage-1-container">
                    <div class="step-number bg-submitted" id="stage-1">1</div>
                    <div class="step-title">Submitted</div>
                    <div class="ongoing-animation" id="ongoing-animation-1">Processing</div>
                </div>
                
                <!-- Step 2: Approved/Rejected -->
                <div class="step" id="stage-2-container">
                    <div class="step-number bg-submitted" id="stage-2">2</div>
                    <div class="step-title">Approved / Rejected</div>
                    <div class="ongoing-animation" id="ongoing-animation-2">Processing</div>
                </div>
                
                <!-- Step 3: Redirected -->
                <div class="step" id="stage-3-container">
                    <div class="step-number bg-submitted" id="stage-3">3</div>
                    <div class="step-title">Redirected</div>
                    <div class="ongoing-animation" id="ongoing-animation-3">Processing</div>
                </div>
                
                <!-- Step 4: Released -->
                <div class="step" id="stage-4-container">
                    <div class="step-number bg-submitted" id="stage-4">4</div>
                    <div class="step-title">Released</div>
                </div>
            </div>
        </div>
        
        <!-- Status Details -->
        <div id="status-details" class="status-details">
            <h3><i class="fas fa-info-circle me-2"></i> Status Details</h3>
            <p id="status-message" class="mb-3"></p>
            <p id="status-timestamp" class="text-muted"><i class="fas fa-clock me-2"></i></p>
            
            <div id="file-names" style="display: none;">
                <h4 class="mt-4 mb-2">File(s) Associated:</h4>
                <ul id="file-list" class="file-list"></ul>
            </div>
        </div>
    </div>

    <!-- PRIVACY POLICY SECTION -->
    <div class="container page-transition" id="privacy-section-container">
        <div class="privacy-content">
            <div class="privacy-header" data-aos="fade-up">
                <h2 class="section-title">Privacy Policy</h2>
                <p>We value your privacy and protect your personal information</p>
            </div>
            
            <div class="shadow-container" data-aos="fade-up" data-aos-delay="100">
                <div class="privacy-body">
                    <h3>Our Commitment to Privacy</h3>
                    <p>We value your privacy in accordance with the Data Privacy Act of 2012. Your personal information is protected and will be used solely for processing and tracking your document submissions.</p>
                    
                    <h3>Information We Collect</h3>
                    <p><strong>Personal Information:</strong> Name, Email, Phone Number</p>
                    <p><strong>Document Information:</strong> Upload Documents, Document Details, Purpose of Submission</p>
                    
                    <h3>How We Use Your Information</h3>
                    <p>The information we collect is used solely for the following purposes:</p>
                    <ul>
                        <li>To process and manage your document submissions</li>
                        <li>To provide updates on the status of your submissions</li>
                        <li>To communicate with you regarding your documents</li>
                        <li>To maintain and improve our platform</li>
                    </ul>
                    
                    <h3>Data Security</h3>
                    <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>
                    
                    <h3>Contact Us</h3>
                    <p>If you have any questions or concerns about our privacy practices, please use the "Contact" tab to reach out to us.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTACT SECTION -->
    <div class="container page-transition" id="contact-section-container">
        <div class="contact-content">
            <div class="contact-header" data-aos="fade-up">
                <h2 class="section-title">Contact Us</h2>
                <p>Get in touch with our team for assistance</p>
            </div>
            
            <div class="shadow-container" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-body">
                    <h3>Regional Office Contact Information</h3>
                    <p>Please reach out to your respective CHED Regional Office for assistance:</p>
                    
                    <ul class="contact-list">
                        <li class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="contact-info">
                                <h4>CHEDRO I</h4>
                                <p><i class="fas fa-envelope me-2"></i> chedro1@ched.gov.ph</p>
                                <p><i class="fas fa-phone me-2"></i> (072) 242-0238</p>
                            </div>
                        </li>
                        
                        <li class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="contact-info">
                                <h4>CHEDRO II</h4>
                                <p><i class="fas fa-envelope me-2"></i> chedro2@ched.gov.ph</p>
                                <p><i class="fas fa-phone me-2"></i> (078) 396-0651</p>
                            </div>
                        </li>
                        
                        <!-- You can add more regional offices as needed -->
                        <li class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="contact-info">
                                <h4>Customer Support</h4>
                                <p><i class="fas fa-envelope me-2"></i> support@ched.gov.ph</p>
                                <p><i class="fas fa-phone me-2"></i> (02) 8888-1234</p>
                                <p><i class="fas fa-clock me-2"></i> Monday to Friday, 8:00 AM - 5:00 PM</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- TERMS MODAL -->
    <div id="terms-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>By uploading a document, you agree to the following terms and conditions:</p>
                    <ul>
                        <li>All documents submitted must be accurate and complete</li>
                        <li>You must have the legal right to submit these documents</li>
                        <li>You acknowledge that false information may result in rejection</li>
                        <li>You consent to the storage and processing of your data</li>
                        <li>You understand that submissions are subject to review</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" id="agree-button" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i> I Agree
                    </button>
                    <button type="button" id="disagree-button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Disagree
                    </button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                        <h4 class="mt-3">OTP Verification</h4>
                        <p>Please enter the OTP sent to your phone to complete the document submission.</p>
                    </div>
                    <div class="form-group">
                        <label for="otp-input" class="form-label">Enter OTP</label>
                        <input type="number" id="otp-input" class="form-control sanitize-input" placeholder="Enter 6-digit OTP" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="verify-otp-btn">
                        <i class="fas fa-check-circle me-2"></i> Verify OTP
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="CHED-eTrack Logo">
                        <span class="footer-logo-text">CHED-eTrack</span>
                    </div>
                    <p class="footer-description">
                        A comprehensive toolkit for managing and tracking your documents efficiently through every stage.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#" id="footer-home"><i class="fas fa-home me-2"></i> Home</a></li>
                        <li><a href="#" id="footer-upload"><i class="fas fa-cloud-upload-alt me-2"></i> Upload</a></li>
                        <li><a href="#" id="footer-track"><i class="fas fa-search me-2"></i> Track</a></li>
                        <li><a href="#" id="footer-privacy"><i class="fas fa-user-shield me-2"></i> Privacy</a></li>
                        <li><a href="#" id="footer-contact"><i class="fas fa-envelope me-2"></i> Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact Information</h4>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <span>W375+PGP, Baliwasan Chico Road, Zamboanga, Zamboanga del Sur</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock contact-icon"></i>
                        <span>Open Monday to Friday, 8:00 AM - 5:00 PM</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone-alt contact-icon"></i>
                        <span>(062) 991 7084</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} CHED-eTrack. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.v3.site_key') }}"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    
    <!-- Particles JS -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /****************************************************
             * INITIALIZE ANIMATIONS
             ****************************************************/
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });
            
            /****************************************************
             * INITIALIZE PARTICLES
             ****************************************************/
            if (document.getElementById('hero-particles')) {
                particlesJS('hero-particles', {
                    "particles": {
                        "number": {
                            "value": 50,
                            "density": {
                                "enable": true,
                                "value_area": 800
                            }
                        },
                        "color": {
                            "value": "#ffffff"
                        },
                        "shape": {
                            "type": "circle",
                            "stroke": {
                                "width": 0,
                                "color": "#000000"
                            },
                            "polygon": {
                                "nb_sides": 5
                            }
                        },
                        "opacity": {
                            "value": 0.2,
                            "random": false,
                            "value": 0.2,
                            "random": false,
                            "anim": {
                                "enable": false,
                                "speed": 1,
                                "opacity_min": 0.1,
                                "sync": false
                            }
                        },
                        "size": {
                            "value": 3,
                            "random": true,
                            "anim": {
                                "enable": false,
                                "speed": 40,
                                "size_min": 0.1,
                                "sync": false
                            }
                        },
                        "line_linked": {
                            "enable": true,
                            "distance": 150,
                            "color": "#ffffff",
                            "opacity": 0.2,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": 2,
                            "direction": "none",
                            "random": false,
                            "straight": false,
                            "out_mode": "out",
                            "bounce": false,
                            "attract": {
                                "enable": false,
                                "rotateX": 600,
                                "rotateY": 1200
                            }
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": true,
                                "mode": "grab"
                            },
                            "onclick": {
                                "enable": true,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "grab": {
                                "distance": 140,
                                "line_linked": {
                                    "opacity": 1
                                }
                            },
                            "bubble": {
                                "distance": 400,
                                "size": 40,
                                "duration": 2,
                                "opacity": 8,
                                "speed": 3
                            },
                            "repulse": {
                                "distance": 200,
                                "duration": 0.4
                            },
                            "push": {
                                "particles_nb": 4
                            },
                            "remove": {
                                "particles_nb": 2
                            }
                        }
                    },
                    "retina_detect": true
                });
            }
            
            /****************************************************
             * THEME TOGGLE (Dark/Light)
             ****************************************************/
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIcon = themeToggleBtn.querySelector('i');
            const currentTheme = localStorage.getItem('chedetrack-theme') || 'light';
            
            // Set initial theme
            document.documentElement.setAttribute('data-theme', currentTheme);
            updateThemeIcon(currentTheme);
            
            themeToggleBtn.addEventListener('click', () => {
                const theme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('chedetrack-theme', theme);
                updateThemeIcon(theme);
            });
            
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
            }
            
            /****************************************************
             * NAVIGATION & NAVBAR
             ****************************************************/
            const navbar = document.getElementById('navbar');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeMenuBtn = document.getElementById('close-menu');
            const overlay = document.getElementById('overlay');
            const navLinks = document.getElementById('nav-links');
            
            // Toggle mobile menu
            mobileMenuBtn.addEventListener('click', () => {
                navLinks.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            closeMenuBtn.addEventListener('click', () => {
                closeMenu();
            });
            
            overlay.addEventListener('click', () => {
                closeMenu();
            });
            
            function closeMenu() {
                navLinks.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
            
            // Navbar scroll effect
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
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
                        el.style.display = 'block';
                        setTimeout(() => {
                            el.classList.add('show');
                            AOS.refresh();
                        }, 50);
                    } else {
                        el.classList.remove('show');
                        setTimeout(() => {
                            el.style.display = 'none';
                        }, 300);
                    }
                });
                
                window.scrollTo({ top: 0, behavior: 'smooth' });
                closeMenu();
                updateActiveNavLink(sectionId);
            }
            
            function updateActiveNavLink(sectionId) {
                const navItems = document.querySelectorAll('.nav-link');
                navItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                let activeLink;
                switch(sectionId) {
                    case 'homepage-section':
                        activeLink = document.getElementById('nav-features');
                        break;
                    case 'upload-section-container':
                        activeLink = document.getElementById('nav-upload');
                        break;
                    case 'track-section-container':
                        activeLink = document.getElementById('nav-track');
                        break;
                    case 'privacy-section-container':
                        activeLink = document.getElementById('nav-privacy');
                        break;
                    case 'contact-section-container':
                        activeLink = document.getElementById('nav-contact');
                        break;
                }
                
                if (activeLink) {
                    activeLink.classList.add('active');
                }
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
            
            // Footer links
            document.getElementById('footer-home').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('homepage-section');
            });
            
            document.getElementById('footer-upload').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('upload-section-container');
            });
            
            document.getElementById('footer-track').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('track-section-container');
            });
            
            document.getElementById('footer-privacy').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('privacy-section-container');
            });
            
            document.getElementById('footer-contact').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('contact-section-container');
            });
            
            // Hero buttons
            document.getElementById('hero-upload-btn').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('upload-section-container');
            });
            
            document.getElementById('hero-track-btn').addEventListener('click', (e) => {
                e.preventDefault();
                showSection('track-section-container');
            });
            
            /****************************************************
             * PHONE NUMBER FORMATTING
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
                labelIdle: 'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
                labelFileTypeNotAllowed: 'Invalid file type. Only PDF, DOC, and DOCX are allowed.',
                fileValidateTypeLabelExpectedTypes: 'Accepts PDF, DOC, and DOCX files only',
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
             * MODALS: TERMS & CONDITIONS
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
                Swal.fire({
                    title: 'Thank You!',
                    text: 'You have agreed to the terms and conditions.',
                    icon: 'success',
                    confirmButtonColor: '#0c3a87'
                });
            });
            
            disagreeButton.addEventListener('click', function() {
                document.getElementById('agree_terms').checked = false;
                termsModal.hide();
                Swal.fire({
                    title: 'Agreement Required',
                    text: 'You must agree to the terms and conditions to upload documents.',
                    icon: 'warning',
                    confirmButtonColor: '#0c3a87'
                });
            });
            
            /****************************************************
             * UPLOAD FORM SUBMISSION
             ****************************************************/
            const uploadForm = document.getElementById('upload-form');
            
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const termsAgreed = document.getElementById('agree_terms').checked;
                
                if (!termsAgreed) {
                    Swal.fire({
                        title: 'Terms Not Agreed',
                        text: 'Please agree to the terms and conditions before uploading.',
                        icon: 'warning',
                        confirmButtonColor: '#0c3a87'
                    });
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
                        html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x mb-3"></i><p>Please wait while your document(s) are being uploaded.</p></div>',
                        allowOutsideClick: false,
                        showConfirmButton: false
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
                                htmlContent += '<p class="mt-3 mb-3">Here are your tracking numbers:</p>';
                                body.documents.forEach(doc => {
                                    // If a tracking number exists, display it; otherwise show "Pending verification"
                                    const trackingDisplay = doc.tracking_number && doc.tracking_number.trim() !== ''
                                        ? doc.tracking_number
                                        : 'Pending OTP verification';
                                    htmlContent += `
                                        <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                                            <div class="flex-grow-1">
                                                <strong>Tracking Number:</strong> ${trackingDisplay}
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary copy-btn ms-2"
                                                    data-clipboard-text="${trackingDisplay !== 'Pending OTP verification' ? doc.tracking_number : ''}">
                                                <i class="fas fa-copy"></i> Copy
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
                                        <div class="mb-3 text-center">
                                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                            <p>Your document(s) have been received.<br>
                                            Please wait while we complete the OTP verification process.</p>
                                        </div>
                                        ${htmlContent}
                                    </div>
                                `,
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonColor: '#0c3a87',
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
                                    errorHtml += `<p class="mb-2"><i class="fas fa-exclamation-circle text-danger me-2"></i><strong>${field}:</strong> ${message}</p>`;
                                });
                            }
                            errorHtml += '</div>';
                            
                            Swal.fire({
                                title: 'Validation Error',
                                html: errorHtml,
                                icon: 'error',
                                confirmButtonColor: '#0c3a87'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: body.message || 'There was an error uploading your document(s).',
                                icon: 'error',
                                confirmButtonColor: '#0c3a87'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an unexpected error.',
                            icon: 'error',
                            confirmButtonColor: '#0c3a87'
                        });
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
                    Swal.fire({
                        title: 'Input Required',
                        text: 'Please enter a Tracking Number.',
                        icon: 'warning',
                        confirmButtonColor: '#0c3a87'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Tracking...',
                    html: '<div class="text-center"><i class="fas fa-search fa-spin fa-3x mb-3"></i><p>Please wait while we retrieve the status of your document.</p></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
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
                                <div class="text-center mb-3">
                                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                </div>
                                <p>Status for Tracking Number: <br>
                                <strong class="d-block mb-2">${trackingNumber}</strong>
                                <span class="badge p-2 ${getStatusBadgeClass(body.status)}">${body.status}</span></p>
                                <button class="btn btn-sm btn-outline-primary copy-btn mt-3"
                                        data-clipboard-text="${trackingNumber}">
                                    <i class="fas fa-copy me-1"></i> Copy Tracking Number
                                </button>
                            `,
                            icon: 'success',
                            confirmButtonColor: '#0c3a87',
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
                                errorHtml += `<p class="mb-2"><i class="fas fa-exclamation-circle text-danger me-2"></i><strong>${field}:</strong> ${message}</p>`;
                            });
                        }
                        errorHtml += '</div>';
                        
                        Swal.fire({
                            title: 'Validation Error',
                            html: errorHtml,
                            icon: 'error',
                            confirmButtonColor: '#0c3a87'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: body.message || 'Unable to track your document.',
                            icon: 'error',
                            confirmButtonColor: '#0c3a87'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an unexpected error.',
                        icon: 'error',
                        confirmButtonColor: '#0c3a87'
                    });
                    console.error('Track Error:', error);
                });
            });
            
            function getStatusBadgeClass(status) {
                switch(status) {
                    case 'Pending':
                        return 'bg-warning text-dark';
                    case 'Processing':
                        return 'bg-primary text-white';
                    case 'Approved':
                        return 'bg-success text-white';
                    case 'Rejected':
                        return 'bg-danger text-white';
                    case 'Redirected':
                        return 'bg-info text-white';
                    case 'Released':
                        return 'bg-success text-white';
                    default:
                        return 'bg-secondary text-white';
                }
            }
            
            /****************************************************
             * OTP VERIFICATION
             ****************************************************/
            document.getElementById('verify-otp-btn').addEventListener('click', function() {
                const otp = document.getElementById('otp-input').value.trim();
                
                if (!otp) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Please enter the OTP.',
                        icon: 'error',
                        confirmButtonColor: '#0c3a87'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Verifying...',
                    html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x mb-3"></i><p>Please wait while we verify your OTP.</p></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
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
                        Swal.fire({
                            title: 'Success!',
                            html: '<div class="text-center"><i class="fas fa-check-circle text-success fa-3x mb-3"></i><p>Your document has been uploaded successfully!</p></div>',
                            icon: 'success',
                            confirmButtonColor: '#0c3a87'
                        });
                        const otpModalElement = document.getElementById('otp-modal');
                        const otpModal = bootstrap.Modal.getInstance(otpModalElement);
                        otpModal.hide();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonColor: '#0c3a87'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an unexpected error.',
                        icon: 'error',
                        confirmButtonColor: '#0c3a87'
                    });
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
                document.getElementById('stepper-container').style.display = 'none';
                
                const statusDetails = document.getElementById('status-details');
                if (statusDetails) {
                    statusDetails.classList.remove('show');
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
                    stepperContainer.style.display = 'none';
                } else {
                    pendingMessage.style.display = 'none';
                    stepperContainer.style.display = 'block';
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
                        fileNamesContainer.style.display = 'block';
                    } else {
                        fileNamesContainer.style.display = 'none';
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
                        // unknown status or "Submitted" – handle accordingly
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