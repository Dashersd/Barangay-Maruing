<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Barangay Maruing office, hotlines, and public assistance.">
    <title>Contact Us | Barangay Maruing Information System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=4">
    <link rel="stylesheet" href="public/assets/css/contact.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top">
        <div class="navbar-container">
            <a class="navbar-brand" href="index.php">
                <img src="public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo" style="width: 50px; height: 50px; object-fit: contain; margin-right: 10px;">
                <div class="brand-text">
                    <span class="brand-title">Barangay Maruing</span>
                    <span class="brand-tagline">Serbisyong Tapat, Para sa Lahat</span>
                </div>
            </a>
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
                        <a class="nav-link" href="spot-map.php">Spot Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announcements.php">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav nav-auth">
                    <li class="nav-item">
                        <a class="btn-admin" href="admin/login.php">Admin Login</a>
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
    
                    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 1.5rem;">
                    
                    <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 1rem; font-size: 1rem;">Follow our social media</h4>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
    
                <!-- Right Column: Form -->
                <div class="contact-form-col" data-aos="fade-left">
                    <h2>Send us a message</h2>
                    <form action="contact.php" method="POST">
                        <div class="form-grid">
                            <div>
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name" required>
                            </div>
                            <div>
                                <label>Service Type</label>
                                <select name="service_type" class="form-control" required>
                                    <option value="" disabled selected>Select service</option>
                                    <option value="clearance">Barangay Clearance</option>
                                    <option value="indigency">Certificate of Indigency</option>
                                    <option value="business">Business Clearance</option>
                                    <option value="residency">Certificate of Residency</option>
                                    <option value="blotter">File a Blotter</option>
                                    <option value="other">General Inquiry</option>
                                </select>
                            </div>
                            <div>
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" placeholder="Message" rows="4" style="resize: vertical;" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">Send</button>
                    </form>
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
                <div class="col-md-2 footer-links">
                    <a href="index.php">HOME</a>
                    <a href="about.php">ABOUT US</a>
                </div>
                <!-- Column 3 -->
                <div class="col-md-2 footer-links">
                    <a href="officials.php">OFFICIALS</a>
                    <a href="spot-map.php">SPOT MAP</a>
                </div>
                <!-- Column 4 -->
                <div class="col-md-2 footer-links">
                    <a href="gallery.php">GALLERY</a>
                    <a href="contact.php">CONTACT US</a>
                </div>
            </div>
            
            <hr class="footer-divider">
            
            <div class="footer-bottom">
                <div class="footer-socials">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-envelope-fill"></i></a>
                </div>
                <p class="footer-copy">&copy; Copyright. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script src="public/assets/js/main.js"></script>
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
</body>
</html>
