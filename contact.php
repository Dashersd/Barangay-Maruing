<?php
session_start();
unset($_SESSION['chat_user_name']);
if (!isset($_SESSION['chat_thread_id'])) {
    $_SESSION['chat_thread_id'] = uniqid('inquiry_');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Barangay Maruing office, hotlines, and public assistance.">
    <title>Contact Us | Barangay Maruing Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat, Dancing Script & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Playfair+Display:ital,wght@1,600;1,700;1,800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=12">
    <link rel="stylesheet" href="public/assets/css/contact.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top">
        <div class="navbar-container">
            <div class="navbar-brand">
                <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 8px;">
                <img src="public/image/Lapuyan Logo.png" alt="Municipality of Lapuyan Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 12px;">
                <div class="brand-text">
                    <span class="brand-title">Barangay Maruing</span>
                    <span class="brand-tagline">Serbisyong Tapat, Para sa Lahat</span>
                </div>
            </div>
            <div class="navbar-menu">
                <ul class="navbar-nav nav-links">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="about.php">About <i class="bi bi-chevron-down" style="font-size: 0.75rem; margin-left: 2px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="about.php#about-us-section">History</a></li>
                            <li><a class="dropdown-item" href="vision.php">Vision</a></li>
                            <li><a class="dropdown-item" href="mission.php">Mission</a></li>
                            <li><a class="dropdown-item" href="officials.php">Barangay Officials</a></li>
                            <li><a class="dropdown-item" href="SKofficial.php">SK Officials</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announcements.php">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="spot-map.php">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                </ul>
                
            </div>
        </div>
    </nav>

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-script-lead" data-aos="fade-down">Contact</span>
                    <span class="hero-title-main" data-aos="zoom-in" data-aos-delay="100">BARANGAY MARUING</span>
                </h1>
                <p class="hero-lead" data-aos="fade-up" data-aos-delay="200">We are here to serve. Reach out to our barangay office for public inquiries, government assistance, and community concerns.</p>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <style>
        .contact-form-section {
            padding: 6rem 0;
            background-color: #f8faf9;
        }
        .contact-form-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 4rem;
            background: white;
            padding: 4rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }
        .contact-info-col h2, .contact-form-col h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-dark, #093d25);
            margin-bottom: 1rem;
        }
        .contact-info-item {
            display: flex;
            gap: 1.2rem;
            margin-bottom: 1.5rem;
        }
        .contact-info-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-color, #137547);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .contact-info-text h4 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.2rem;
            font-size: 1.1rem;
        }
        .contact-info-text p {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.5;
        }
        .social-links {
            display: flex;
            gap: 0.8rem;
        }
        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color, #137547);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .social-link:hover {
            background-color: var(--primary-dark, #093d25);
            color: #ffc107;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 0.8rem 1.2rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background-color: #f8faf9;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.3s ease, background-color 0.3s ease;
            font-family: inherit;
        }
        .form-control:focus {
            border-color: var(--primary-color, #137547);
            background-color: white;
        }
        select.form-control {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 1.2rem center;
        }
        .btn-submit {
            width: 100%;
            padding: 1rem;
            border-radius: 50px;
            background-color: var(--primary-color, #137547);
            color: white;
            border: none;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 0.5rem;
        }
        .btn-submit:hover {
            background-color: var(--primary-dark, #093d25);
            transform: translateY(-2px);
        }
        
        @media (max-width: 992px) {
            .contact-form-wrapper {
                grid-template-columns: 1fr;
                padding: 2rem;
                gap: 3rem;
            }
        }
        @media (max-width: 576px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <main class="contact-form-section">
        <div class="container">
            <div class="contact-form-wrapper">
                
                <!-- Left Column: Contact Info -->
                <div class="contact-info-col" data-aos="fade-right">
                    <h2>Get in touch</h2>
                    <p style="color: #64748b; margin-bottom: 2.5rem; line-height: 1.6;">Reach out to our barangay office for public inquiries, government assistance, and community concerns.</p>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Head Office</h4>
                            <p>Barangay Maruing Hall<br>Maruing, Municipality</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Email Us</h4>
                            <p>support@maruing.gov.ph<br>info@maruing.gov.ph</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item" style="margin-bottom: 2.5rem;">
                        <div class="contact-info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Call Us</h4>
                            <p>Phone: 09XX-XXX-XXXX<br>Tanod: 09XX-XXX-XXXX</p>
                        </div>
                    </div>
    
                </div>
    
                <!-- Right Column: Inquiry / Contact Form -->
                <div class="contact-form-col" data-aos="fade-left" id="inquiry-widget-container">
                    
                    <!-- Inquiry Input Form -->
                    <div id="inquiry-form-card" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:2rem; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                        <h2 style="font-size:1.5rem; font-weight:700; color:#093d25; margin-bottom:0.4rem;">Send Us an Inquiry</h2>
                        <p style="color:#64748b; font-size:0.88rem; line-height:1.5; margin-bottom:1.5rem;">
                            Have concerns, questions, or document requests? Fill out the form below. The Barangay Administration will review your message and <strong>reply directly to your Gmail / email address</strong>.
                        </p>

                        <form id="resident-inquiry-form">
                            <div class="form-group" style="margin-bottom:1.1rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Your Name <span style="color:#ef4444;">*</span></label>
                                <input type="text" id="inquiry-name" class="form-control" placeholder="Enter your full name" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem;">
                            </div>
                            <div class="form-group" style="margin-bottom:1.1rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Gmail / Email Address <span style="color:#ef4444;">*</span></label>
                                <input type="email" id="inquiry-email" class="form-control" placeholder="e.g. yourname@gmail.com" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem;">
                                <small style="display:block; color:#059669; font-size:0.78rem; margin-top:0.3rem;"><i class="bi bi-info-circle-fill"></i> Admin will send their official reply to this Gmail address.</small>
                            </div>
                            <div class="form-group" style="margin-bottom:1.1rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Service Type / Subject <span style="color:#ef4444;">*</span></label>
                                <select id="inquiry-service-type" class="form-control" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem;">
                                    <option value="" disabled selected>Select service or category</option>
                                    <option value="Barangay Clearance">Barangay Clearance</option>
                                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                                    <option value="Business Clearance">Business Clearance</option>
                                    <option value="Certificate of Residency">Certificate of Residency</option>
                                    <option value="General Inquiry / Feedback">General Inquiry / Feedback</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom:1.4rem;">
                                <label style="display:block; font-weight:600; font-size:0.86rem; color:#1e293b; margin-bottom:0.35rem;">Your Message / Request Details <span style="color:#ef4444;">*</span></label>
                                <textarea id="inquiry-message" class="form-control" placeholder="Write your questions, requirements, or concern details here..." rows="4" required style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem; resize:vertical;"></textarea>
                            </div>
                            <button type="submit" id="btn-submit-inquiry" class="btn-submit" style="width:100%; padding:0.85rem; border-radius:8px; font-weight:700; font-size:0.95rem; display:flex; align-items:center; justify-content:center; gap:8px;">
                                <i class="bi bi-send-fill" id="submit-icon"></i>
                                <span id="submit-label">Send Inquiry</span>
                            </button>
                        </form>
                    </div>

                    <!-- Clean Confirmation State (After Sending) -->
                    <div id="inquiry-success-card" style="display:none; text-align:center; padding:2.5rem 1.75rem; background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04);">
                        <div style="width:68px; height:68px; border-radius:50%; background:#dcfce7; color:#16a34a; font-size:2.2rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem auto;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h3 style="font-size:1.35rem; color:#093d25; font-weight:700; margin-bottom:0.5rem;">Inquiry Sent Successfully!</h3>
                        <p style="color:#475569; font-size:0.92rem; line-height:1.6; max-width:440px; margin:0 auto 1.25rem auto;">
                            Thank you, <strong id="success-user-name">Resident</strong>! Your inquiry has been verified and received by Barangay Maruing. Our officers will review your message and reply directly to your Gmail address at:
                            <br><span style="display:inline-block; margin-top:8px; background:#eff6ff; color:#1d4ed8; font-weight:700; padding:4px 14px; border-radius:20px; border:1px solid #bfdbfe;" id="success-user-email">email@gmail.com</span>
                        </p>
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; font-size:0.84rem; color:#64748b; line-height:1.45;">
                            <i class="bi bi-envelope-check-fill" style="color:#ea4335; margin-right:5px;"></i>
                            Please check your Gmail inbox and Spam/Junk folder for our official response.
                        </div>
                        <button type="button" id="btn-send-another" class="btn-submit" style="width:auto; padding:0.75rem 1.8rem; margin:0 auto; display:inline-flex; align-items:center; gap:8px;">
                            <i class="bi bi-pencil-square"></i> Send Another Inquiry
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </main>

        <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top row">
                <!-- Column 1: Logo -->
                <div class="col-md-4 footer-brand" style="display: flex; align-items: center; gap: 15px;">
                    <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" class="footer-logo-img" style="width: 70px;">
                    <div>
                        <h2 class="footer-logo" style="margin-bottom: 0;">BARANGAY MARUING</h2>
                        <p class="footer-slogan" style="margin-bottom: 0;">SERBISYONG TAPAT, PARA SA LAHAT</p>
                    </div>
                </div>
                <!-- Column 2 -->
                <div class="col-md-2 col-6 footer-links">
                    <a href="index.php">HOME</a>
                    <a href="about.php">ABOUT US</a>
                    <a href="vision.php">VISION</a>
                </div>
                <!-- Column 3 -->
                <div class="col-md-3 col-6 footer-links">
                    <a href="mission.php">MISSION</a>
                    <a href="officials.php">BARANGAY OFFICIALS</a>
                    <a href="SKofficial.php">SK OFFICIALS</a>
                </div>
                <!-- Column 4 -->
                <div class="col-md-3 col-6 footer-links">
                    <a href="spot-map.php">SPOT MAP</a>
                    <a href="gallery.php">GALLERY</a>
                    <a href="contact.php">CONTACT US</a>
                </div>
            </div>
            
            <hr class="footer-divider">
            
            <div class="footer-bottom">
                <p class="footer-copy">&copy; Copyright. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script src="public/assets/js/main.js?v=2"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
          duration: 800, 
          once: true,
          offset: 100
        });
      });
    </script>
    <!-- Exact Pop-Up OTP Modal (from design) -->
    <div class="gmail-otp-modal-backdrop" id="gmail-otp-modal-backdrop" style="display:none !important;">
        <div class="gmail-otp-modal-box">
            <!-- Close 'X' Button -->
            <button type="button" class="gmail-otp-close-btn" id="btn-modal-otp-close" aria-label="Close">&times;</button>

            <!-- Shield Icon in Circle -->
            <div class="gmail-otp-shield-icon-circle">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="#000000">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                </svg>
            </div>

            <!-- Italic Serif Title -->
            <h2 class="gmail-otp-title">Gmail OTP Verification</h2>

            <!-- Subtitle Description -->
            <p class="gmail-otp-desc">
                We sent a 6-digit verification OTP to <strong id="modal-otp-target-email">user@gmail.com</strong>.
                Please check your Inbox or Spam folder, then enter the OTP below to confirm your request:
            </p>

            <!-- 6 Individual Input Boxes -->
            <div class="gmail-otp-boxes-group" id="gmail-otp-boxes-group">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="0" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="2" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="3" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="4" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="gmail-otp-digit-box" maxlength="1" data-index="5" inputmode="numeric" pattern="[0-9]*">
            </div>

            <!-- Expiration & Resend Pill Bar -->
            <div class="gmail-otp-pill-bar">
                <span class="gmail-otp-pill-timer">
                    <i class="bi bi-clock"></i> Expires in: <strong id="modal-otp-timer-text">03:00</strong>
                </span>
                <button type="button" class="gmail-otp-pill-resend-btn" id="btn-modal-resend-otp" disabled>
                    <i class="bi bi-arrow-clockwise"></i> Resend (<span id="modal-resend-sec-text">30s</span>)
                </button>
            </div>

            <!-- Full-Width Black Verification Button -->
            <button type="button" class="gmail-otp-submit-btn" id="btn-modal-submit-verify">
                <i class="bi bi-check-circle-fill" id="modal-submit-verify-icon"></i>
                <span id="modal-submit-verify-label">Verify &amp; Submit Request</span>
            </button>

            <!-- Error Message Alert -->
            <div id="modal-otp-error-alert" class="gmail-otp-error-alert" style="display:none;"></div>
        </div>
    </div>

    <!-- Pop-Up Modal Styling -->
    <style>
        .gmail-otp-modal-backdrop {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(15, 23, 42, 0.72) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            z-index: 9999999 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            animation: otpBackdropFadeIn 0.2s ease-out;
        }

        @keyframes otpBackdropFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .gmail-otp-modal-box {
            position: relative !important;
            background: #ffffff !important;
            border-radius: 28px !important;
            padding: 38px 32px 34px 32px !important;
            max-width: 440px !important;
            width: 100% !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(0,0,0,0.06) !important;
            text-align: center !important;
            animation: otpModalScaleIn 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 10000000 !important;
        }

        @keyframes otpModalScaleIn {
            from { transform: scale(0.92); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .gmail-otp-close-btn {
            position: absolute !important;
            top: 20px !important;
            right: 20px !important;
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            background: #f1f5f9 !important;
            color: #64748b !important;
            border: none !important;
            cursor: pointer !important;
            font-size: 16px !important;
            line-height: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
        }

        .gmail-otp-close-btn:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }

        .gmail-otp-shield-icon-circle {
            width: 68px !important;
            height: 68px !important;
            border-radius: 50% !important;
            background: #edf4fb !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto 16px auto !important;
        }

        .gmail-otp-title {
            font-family: 'Playfair Display', Georgia, serif !important;
            font-style: italic !important;
            font-weight: 700 !important;
            font-size: 28px !important;
            color: #000000 !important;
            margin: 0 0 10px 0 !important;
            letter-spacing: -0.3px !important;
        }

        .gmail-otp-desc {
            font-size: 13.5px !important;
            line-height: 1.55 !important;
            color: #4b5563 !important;
            margin: 0 0 24px 0 !important;
        }

        .gmail-otp-desc strong {
            color: #000000 !important;
            font-weight: 700 !important;
            word-break: break-all !important;
        }

        .gmail-otp-boxes-group {
            display: flex !important;
            justify-content: center !important;
            gap: 10px !important;
            margin-bottom: 20px !important;
        }

        .gmail-otp-digit-box {
            width: 48px !important;
            height: 56px !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 12px !important;
            text-align: center !important;
            font-size: 24px !important;
            font-weight: 700 !important;
            color: #000000 !important;
            background: #ffffff !important;
            outline: none !important;
            transition: all 0.15s ease !important;
            -webkit-appearance: none !important;
            margin: 0 !important;
            font-family: 'Courier New', monospace !important;
        }

        .gmail-otp-digit-box:focus {
            border-color: #000000 !important;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.12) !important;
        }

        .gmail-otp-pill-bar {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-bottom: 22px !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
        }

        .gmail-otp-pill-timer {
            color: #000000 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
        }

        .gmail-otp-pill-resend-btn {
            background: none !important;
            border: none !important;
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
            cursor: not-allowed !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            transition: all 0.2s ease !important;
            padding: 0 !important;
        }

        .gmail-otp-pill-resend-btn.active {
            color: #000000 !important;
            cursor: pointer !important;
            text-decoration: underline !important;
        }

        .gmail-otp-submit-btn {
            width: 100% !important;
            padding: 14px 20px !important;
            border-radius: 30px !important;
            background: #000000 !important;
            color: #ffffff !important;
            font-size: 14.5px !important;
            font-weight: 700 !important;
            border: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2) !important;
            transition: all 0.2s ease !important;
        }

        .gmail-otp-submit-btn:hover {
            background: #1f2937 !important;
            transform: translateY(-1px) !important;
        }

        .gmail-otp-submit-btn:disabled {
            opacity: 0.65 !important;
            cursor: not-allowed !important;
            transform: none !important;
        }

        .gmail-otp-error-alert {
            color: #dc2626 !important;
            background: #fef2f2 !important;
            border: 1px solid #fecaca !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            margin-top: 14px !important;
        }

        /* Mobile Screen Adjustments for OTP Modal */
        @media (max-width: 480px) {
            .gmail-otp-modal-box {
                padding: 28px 16px 24px 16px !important;
                border-radius: 20px !important;
                max-width: calc(100vw - 24px) !important;
            }
            .gmail-otp-boxes-group {
                gap: 6px !important;
                margin-bottom: 16px !important;
            }
            .gmail-otp-digit-box {
                width: 40px !important;
                height: 48px !important;
                font-size: 20px !important;
                border-radius: 8px !important;
            }
            .gmail-otp-title {
                font-size: 22px !important;
            }
            .gmail-otp-desc {
                font-size: 12.5px !important;
                margin-bottom: 16px !important;
            }
            .gmail-otp-pill-bar {
                font-size: 12px !important;
                padding: 8px 12px !important;
                margin-bottom: 16px !important;
            }
            .gmail-otp-submit-btn {
                padding: 12px 16px !important;
                font-size: 13.5px !important;
            }
        }
        @media (max-width: 360px) {
            .gmail-otp-modal-box {
                padding: 22px 12px 20px 12px !important;
            }
            .gmail-otp-boxes-group {
                gap: 4px !important;
            }
            .gmail-otp-digit-box {
                width: 35px !important;
                height: 44px !important;
                font-size: 17px !important;
            }
            .gmail-otp-title {
                font-size: 20px !important;
            }
        }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const inquiryForm = document.getElementById('resident-inquiry-form');
        const formCard = document.getElementById('inquiry-form-card');
        const successCard = document.getElementById('inquiry-success-card');
        const submitBtn = document.getElementById('btn-submit-inquiry');
        const submitIcon = document.getElementById('submit-icon');
        const submitLabel = document.getElementById('submit-label');
        const btnSendAnother = document.getElementById('btn-send-another');

        // Modal Elements
        const modalBackdrop = document.getElementById('gmail-otp-modal-backdrop');
        const modalCloseBtn = document.getElementById('btn-modal-otp-close');
        const modalEmailDisplay = document.getElementById('modal-otp-target-email');
        const otpBoxes = document.querySelectorAll('.gmail-otp-digit-box');
        const modalTimerText = document.getElementById('modal-otp-timer-text');
        const modalResendBtn = document.getElementById('btn-modal-resend-otp');
        const modalVerifyBtn = document.getElementById('btn-modal-submit-verify');
        const modalVerifyIcon = document.getElementById('modal-submit-verify-icon');
        const modalVerifyLabel = document.getElementById('modal-submit-verify-label');
        const modalErrorAlert = document.getElementById('modal-otp-error-alert');

        let pendingInquiryData = null;
        let expiryTimerInterval = null;
        let resendTimerInterval = null;
        let expirySecondsLeft = 180;
        let resendSecondsLeft = 30;

        // Open Pop-Up Modal
        function openOtpModal(email) {
            modalEmailDisplay.textContent = email;
            modalErrorAlert.style.display = 'none';
            otpBoxes.forEach(box => box.value = '');
            modalBackdrop.style.setProperty('display', 'flex', 'important');
            setTimeout(() => {
                if (otpBoxes[0]) otpBoxes[0].focus();
            }, 100);

            startExpiryCountdown(180);
            startResendCountdown(30);
        }

        // Close Pop-Up Modal
        function closeOtpModal() {
            modalBackdrop.style.setProperty('display', 'none', 'important');
            clearInterval(expiryTimerInterval);
            clearInterval(resendTimerInterval);
        }

        modalCloseBtn.addEventListener('click', closeOtpModal);
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) closeOtpModal();
        });

        // 180-second Expiration Countdown
        function startExpiryCountdown(sec) {
            clearInterval(expiryTimerInterval);
            expirySecondsLeft = sec;
            function updateExpiry() {
                const m = String(Math.floor(expirySecondsLeft / 60)).padStart(2, '0');
                const s = String(expirySecondsLeft % 60).padStart(2, '0');
                modalTimerText.textContent = `${m}:${s}`;
                if (expirySecondsLeft <= 0) {
                    clearInterval(expiryTimerInterval);
                    modalTimerText.textContent = 'Expired';
                    showOtpError('Your OTP code has expired. Please click "Resend" to get a new code.');
                } else {
                    expirySecondsLeft--;
                }
            }
            updateExpiry();
            expiryTimerInterval = setInterval(updateExpiry, 1000);
        }

        // 30-second Resend Timer
        function startResendCountdown(sec) {
            clearInterval(resendTimerInterval);
            resendSecondsLeft = sec;
            modalResendBtn.disabled = true;
            modalResendBtn.classList.remove('active');
            modalResendBtn.innerHTML = `<i class="bi bi-arrow-clockwise"></i> Resend (<span id="modal-resend-sec-text">${resendSecondsLeft}s</span>)`;

            resendTimerInterval = setInterval(() => {
                resendSecondsLeft--;
                const span = document.getElementById('modal-resend-sec-text');
                if (span) span.textContent = `${resendSecondsLeft}s`;

                if (resendSecondsLeft <= 0) {
                    clearInterval(resendTimerInterval);
                    modalResendBtn.disabled = false;
                    modalResendBtn.classList.add('active');
                    modalResendBtn.innerHTML = `<i class="bi bi-arrow-clockwise"></i> Resend Code`;
                }
            }, 1000);
        }

        function showOtpError(msg) {
            modalErrorAlert.textContent = msg;
            modalErrorAlert.style.display = 'block';
        }

        // 6-Digit Box Navigation (Auto-advance & Backspace)
        otpBoxes.forEach((box, index) => {
            box.addEventListener('input', (e) => {
                const val = box.value.replace(/[^0-9]/g, '');
                box.value = val ? val.charAt(0) : '';

                if (box.value && index < otpBoxes.length - 1) {
                    otpBoxes[index + 1].focus();
                }
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && index > 0) {
                    otpBoxes[index - 1].focus();
                }
                if (e.key === 'Enter') {
                    e.preventDefault();
                    modalVerifyBtn.click();
                }
            });

            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
                if (digits.length > 0) {
                    digits.split('').forEach((d, i) => {
                        if (otpBoxes[i]) otpBoxes[i].value = d;
                    });
                    const focusIdx = Math.min(digits.length, otpBoxes.length - 1);
                    otpBoxes[focusIdx].focus();
                }
            });
        });

        // Send OTP Request to API
        function dispatchOtp() {
            if (!pendingInquiryData) return;

            submitBtn.disabled = true;
            submitIcon.className = 'bi bi-arrow-repeat';
            submitIcon.style.animation = 'spin 1s linear infinite';
            submitLabel.textContent = 'Sending OTP to Gmail...';
            modalErrorAlert.style.display = 'none';

            const formData = new URLSearchParams();
            formData.append('fullName', pendingInquiryData.name);
            formData.append('email', pendingInquiryData.email);
            formData.append('service_type', pendingInquiryData.serviceType);
            formData.append('message', pendingInquiryData.message);

            fetch('api/send_otp.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res && res.status === 'success') {
                    openOtpModal(pendingInquiryData.email);
                    if (res.dev_otp) {
                        console.log('DEV OTP Fallback:', res.dev_otp);
                    }
                } else {
                    alert(res.message || 'Could not send verification code to your Gmail.');
                }
            })
            .catch(() => {
                alert('Network connection error. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitIcon.className = 'bi bi-send-fill';
                submitIcon.style.animation = 'none';
                submitLabel.textContent = 'Send Inquiry';
            });
        }

        // 1. Submit Inquiry Form -> Triggers Pop-Up OTP
        if (inquiryForm) {
            inquiryForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const name = document.getElementById('inquiry-name').value.trim();
                const email = document.getElementById('inquiry-email').value.trim();
                const serviceSelect = document.getElementById('inquiry-service-type');
                const serviceType = serviceSelect.options[serviceSelect.selectedIndex].text;
                const message = document.getElementById('inquiry-message').value.trim();

                if (!name || !email || !message) return;

                pendingInquiryData = { name, email, serviceType, message };
                dispatchOtp();
            });
        }

        // 2. Resend Code
        modalResendBtn.addEventListener('click', () => {
            if (modalResendBtn.disabled) return;
            dispatchOtp();
        });

        // 3. Verify OTP & Submit Request
        modalVerifyBtn.addEventListener('click', () => {
            let enteredCode = '';
            otpBoxes.forEach(box => enteredCode += box.value.trim());

            if (enteredCode.length !== 6) {
                showOtpError('Please enter all 6 digits of your OTP code.');
                return;
            }

            modalVerifyBtn.disabled = true;
            modalVerifyIcon.className = 'bi bi-arrow-repeat';
            modalVerifyIcon.style.animation = 'spin 1s linear infinite';
            modalVerifyLabel.textContent = 'Verifying...';
            modalErrorAlert.style.display = 'none';

            const formData = new URLSearchParams();
            formData.append('email', pendingInquiryData.email);
            formData.append('otp_code', enteredCode);

            fetch('api/verify_otp.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res && res.status === 'success') {
                    closeOtpModal();
                    document.getElementById('success-user-name').textContent = pendingInquiryData.name;
                    document.getElementById('success-user-email').textContent = pendingInquiryData.email;
                    formCard.style.display = 'none';
                    successCard.style.display = 'block';
                    inquiryForm.reset();
                    pendingInquiryData = null;
                } else {
                    showOtpError(res.message || 'Invalid verification code. Please check your Gmail.');
                    otpBoxes.forEach(b => b.value = '');
                    otpBoxes[0].focus();
                }
            })
            .catch(() => {
                showOtpError('Verification request failed. Please check your internet connection.');
            })
            .finally(() => {
                modalVerifyBtn.disabled = false;
                modalVerifyIcon.className = 'bi bi-check-circle-fill';
                modalVerifyIcon.style.animation = 'none';
                modalVerifyLabel.textContent = 'Verify & Submit Request';
            });
        });

        // Send Another Inquiry Button
        if (btnSendAnother) {
            btnSendAnother.addEventListener('click', function() {
                inquiryForm.reset();
                successCard.style.display = 'none';
                formCard.style.display = 'block';
                document.getElementById('inquiry-name').focus();
            });
        }
    });
    </script>
</body>
</html>
