<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Official Web-Based Information System for Barangay Maruing. Access announcements, services, barangay officials, and community updates online.">
    <title>Home | Barangay Maruing Information System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS (Pure CSS Responsive) -->
    <link rel="stylesheet" href="public/assets/css/style.css?v=6">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar sticky-top ">
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
                        <a class="nav-link active" href="index.php">Home</a>
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
                        <a class="nav-link" href="contact.php">Contact</a>
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

    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container hero-container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        <span class="hero-script-lead">Welcome to</span>
                        <span class="hero-title-main">BARANGAY MARUING</span>
                    </h1>
                    <p class="hero-lead">Your trusted source for barangay governance, community information, and public service.</p>
                    <div class="hero-buttons">
                        <a href="about.php" class="btn-hero-primary">Explore Information <i
                                class="bi bi-arrow-right"></i></a>
                        <a href="officials.php" class="btn-hero-outline">Meet Officials <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-landing-section">
            <div class="container about-landing-container">
                <div class="about-landing-image">
                    <img src="public/image/barangay-maruing-cover.png" alt="Barangay Maruing History">
                </div>
                <div class="about-landing-content">
                    <h2 class="about-landing-title">About Barangay Maruing</h2>
                    <p class="about-landing-desc">
                        Discover the rich history and vibrant community of Barangay Maruing. Our barangay is dedicated to providing honest and dedicated service to all its residents, fostering growth, unity, and a better quality of life for everyone. 
                    </p>
                    <a href="about.php#about-us-section" class="btn-learn-more">
                        Learn more <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
        <section class="landing-officials-section" id="officials-section" style="padding: 5rem 0; background-color: var(--background-color, #f8faf9);">
            <div class="container">
                <div class="section-header" style="text-align: center; margin-bottom: 4rem;">
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary-color, #137547); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.8rem;">Barangay Officials</span>
                    <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--primary-dark, #093d25); margin-bottom: 1rem;">Meet Our <span style="color: var(--primary-color, #137547);">Leaders</span></h2>
                    <div style="width: 50px; height: 3px; background-color: var(--primary-color, #137547); margin: 0 auto 1.5rem;"></div>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color, #6b7280);">The dedicated officials working to serve the people of Barangay Maruing.</p>
                </div>
                
                <div class="officials-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; max-width: 900px; margin: 0 auto;">
                    
                    <!-- Official Card 1 -->
                    <div class="official-card" style="background: white; border-radius: 20px; padding: 3rem 2rem; box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,0.05)); border: 1px solid var(--border-color, #e5e7eb); text-align: center; transition: transform 0.3s ease;">
                        <div style="position: relative; width: 140px; height: 140px; margin: 0 auto 2.5rem; background-color: var(--primary-light, #e8f5ee); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 8px rgba(19, 117, 71, 0.05);">
                            <span style="font-size: 3.5rem; font-weight: 300; color: var(--primary-dark, #093d25); font-family: sans-serif;">PB</span>
                            <div style="position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); background-color: var(--primary-color, #137547); color: white; padding: 0.5rem 1.2rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; white-space: nowrap; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                Punong Barangay
                            </div>
                        </div>
                        <h4 style="color: var(--primary-dark, #093d25); margin-bottom: 0.5rem; font-weight: 800; font-size: 1.3rem;">Hon. Punong Barangay</h4>
                        <p style="color: var(--primary-color, #137547); font-size: 0.95rem; font-weight: 600; margin-bottom: 1.5rem;">Barangay Captain</p>
                        <p style="color: var(--muted-text-color, #6b7280); font-size: 0.95rem; font-style: italic; line-height: 1.6;">"Committed to providing transparent and dedicated public service for the betterment of every resident in Barangay Maruing."</p>
                        <a href="officials.php" class="btn-officials-outline" style="display: inline-block; margin-top: 1.5rem; padding: 0.6rem 1.5rem; background-color: white; border: 2px solid var(--primary-color, #137547); color: var(--primary-color, #137547) !important; border-radius: 8px; font-weight: 700; text-decoration: none; transition: all 0.3s ease;">
                            View Barangay Officials <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Official Card 2 -->
                    <div class="official-card" style="background: white; border-radius: 20px; padding: 3rem 2rem; box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,0.05)); border: 1px solid var(--border-color, #e5e7eb); text-align: center; transition: transform 0.3s ease;">
                        <div style="position: relative; width: 140px; height: 140px; margin: 0 auto 2.5rem; background-color: rgba(255, 193, 7, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 8px rgba(255, 193, 7, 0.05);">
                            <span style="font-size: 3.5rem; font-weight: 300; color: #b48600; font-family: sans-serif;">SK</span>
                            <div style="position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); background-color: var(--primary-color, #137547); color: white; padding: 0.5rem 1.2rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; white-space: nowrap; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                SK Chairman
                            </div>
                        </div>
                        <h4 style="color: var(--primary-dark, #093d25); margin-bottom: 0.5rem; font-weight: 800; font-size: 1.3rem;">Hon. SK Chairman</h4>
                        <p style="color: var(--primary-color, #137547); font-size: 0.95rem; font-weight: 600; margin-bottom: 1.5rem;">Sangguniang Kabataan Chairman</p>
                        <p style="color: var(--muted-text-color, #6b7280); font-size: 0.95rem; font-style: italic; line-height: 1.6;">"Empowering the youth of Barangay Maruing through active participation in sports, education, and community development."</p>
                        <a href="SKofficial.php" class="btn-officials-outline" style="display: inline-block; margin-top: 1.5rem; padding: 0.6rem 1.5rem; background-color: white; border: 2px solid var(--primary-color, #137547); color: var(--primary-color, #137547) !important; border-radius: 8px; font-weight: 700; text-decoration: none; transition: all 0.3s ease;">
                            View SK Officials <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>


            </div>
        </section>

        <!-- Services Section -->
        <section id="services-section" class="services-section" style="padding: 5rem 0;">
            <div class="container">
                <div class="section-header" style="text-align: center; margin-bottom: 4rem;">
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary-color, #10b981); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.8rem;">What We Offer</span>
                    <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--primary-dark, #1e293b); margin-bottom: 1rem;">Barangay <span style="color: var(--primary-color, #10b981);">Services</span></h2>
                    <div style="width: 50px; height: 3px; background-color: var(--primary-color, #10b981); margin: 0 auto 1.5rem;"></div>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color, #64748b);">Accessible and efficient services dedicated to the welfare of our residents.</p>
                </div>
                <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem;">
                    
                    <!-- Card 1 -->
                    <div class="service-card" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-file-earmark-text" style="font-size: 1.8rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Document Issuance</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">Get your Barangay Clearance, Certificate of Indigency, and other important documents with ease.</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="service-card" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-activity" style="font-size: 2rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Health Center</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">Free consultations, vaccinations, and maternal care services for every Maruing resident.</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="service-card" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-shield-check" style="font-size: 1.8rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Peace & Order</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">File a blotter or request assistance from our active Barangay Tanods who patrol 24/7.</p>
                    </div>

                    <!-- Card 4 -->
                    <div class="service-card" style="background: white; border-radius: 20px; padding: 2.5rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                        <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="bi bi-people" style="font-size: 1.8rem; color: var(--primary-color, #10b981);"></i>
                        </div>
                        <h4 style="color: var(--primary-dark, #1e293b); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Community Programs</h4>
                        <p style="color: var(--muted-text-color, #64748b); font-size: 0.95rem; line-height: 1.6;">Participate in livelihood seminars, youth sports leagues, and regular clean-up drives.</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Announcements Section -->
        <section id="announcements-section" class="announcements-section" style="padding: 5rem 0; background-color: var(--background-color, #f8faf9);">
            <div class="container">
                <div class="section-header" style="text-align: center; margin-bottom: 4rem;">
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary-color, #137547); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.8rem;">Stay Updated</span>
                    <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--primary-dark, #093d25); margin-bottom: 1rem;">Latest <span style="color: var(--primary-color, #137547);">Announcements</span></h2>
                    <div style="width: 50px; height: 3px; background-color: var(--primary-color, #137547); margin: 0 auto 1.5rem;"></div>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color, #6b7280);">Keep track of upcoming events, meetings, and important notices in our barangay.</p>
                </div>
                <div class="announcements-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
                    
                    <!-- Announcement 1 -->
                    <div class="announcement-card" style="background: white; border-radius: 15px; padding: 2rem; box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,0.05)); border: 1px solid var(--border-color, #e5e7eb); display: flex; gap: 1.5rem; transition: transform 0.3s ease;">
                        <div class="date-box" style="background-color: var(--primary-light, #e8f5ee); border-radius: 10px; min-width: 65px; height: 75px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--primary-color, #137547);">
                            <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Oct</span>
                            <span style="font-size: 1.5rem; font-weight: 800; line-height: 1;">15</span>
                        </div>
                        <div class="announcement-content">
                            <h4 style="color: var(--text-color, #1f2937); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">General Assembly Meeting</h4>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">What:</strong> General Assembly Meeting</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">When:</strong> 9:00 AM, October 15</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">Why:</strong> Discuss infrastructure projects & budgets.</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0;"><strong style="color: var(--text-color, #1f2937);">Who:</strong> All Barangay Residents</p>
                        </div>
                    </div>

                    <!-- Announcement 2 -->
                    <div class="announcement-card" style="background: white; border-radius: 15px; padding: 2rem; box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,0.05)); border: 1px solid var(--border-color, #e5e7eb); display: flex; gap: 1.5rem; transition: transform 0.3s ease;">
                        <div class="date-box" style="background-color: var(--primary-light, #e8f5ee); border-radius: 10px; min-width: 65px; height: 75px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--primary-color, #137547);">
                            <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Oct</span>
                            <span style="font-size: 1.5rem; font-weight: 800; line-height: 1;">22</span>
                        </div>
                        <div class="announcement-content">
                            <h4 style="color: var(--text-color, #1f2937); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Free Medical & Dental Mission</h4>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">What:</strong> Free Medical & Dental Mission</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">When:</strong> 8:00 AM, October 22</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">Why:</strong> Offer free checkups and medicines.</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0;"><strong style="color: var(--text-color, #1f2937);">Who:</strong> Senior Citizens and Children</p>
                        </div>
                    </div>

                    <!-- Announcement 3 -->
                    <div class="announcement-card" style="background: white; border-radius: 15px; padding: 2rem; box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,0.05)); border: 1px solid var(--border-color, #e5e7eb); display: flex; gap: 1.5rem; transition: transform 0.3s ease;">
                        <div class="date-box" style="background-color: var(--primary-light, #e8f5ee); border-radius: 10px; min-width: 65px; height: 75px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--primary-color, #137547);">
                            <span style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Nov</span>
                            <span style="font-size: 1.5rem; font-weight: 800; line-height: 1;">01</span>
                        </div>
                        <div class="announcement-content">
                            <h4 style="color: var(--text-color, #1f2937); margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem;">Undas 2025 Traffic Advisory</h4>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">What:</strong> Undas 2025 Traffic Advisory</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">When:</strong> All Day, November 01</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0.5rem;"><strong style="color: var(--text-color, #1f2937);">Why:</strong> Road closures and rerouting for Undas.</p>
                            <p style="color: var(--muted-text-color, #6b7280); font-size: 0.9rem; margin-bottom: 0;"><strong style="color: var(--text-color, #1f2937);">Who:</strong> All Motorists and Residents</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Spot Map Landing Section -->
        <section class="landing-spot-map-section">
            <div class="container">
                <div class="landing-spot-map-header" style="text-align: center; margin-bottom: 3rem;">
                    <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 1rem; text-align: center;">Spot Map</h2>
                    <p style="font-size: 1.1rem; color: var(--muted-text-color); text-align: center; margin-left: auto; margin-right: auto;">Geographical overview and key locations in Barangay Maruing</p>
                </div>
                
                <div class="landing-spot-map-container">
                <div class="spot-map-content">
                    <div class="spot-map-badge">
                        <i class="bi bi-geo-alt-fill"></i> EXPLORE
                    </div>
                    <h2 class="spot-map-title">Barangay Spot Map</h2>
                    <p class="spot-map-desc">
                        Navigate our community with ease. View important landmarks, territorial boundaries, purok zones, and key public facilities throughout Barangay Maruing.
                    </p>
                    <a href="spot-map.php" class="btn-view-map">
                        View Full Map <i class="bi bi-map-fill"></i>
                    </a>

                </div>
                <div class="spot-map-visual">
                    <!-- <iframe 
                        src="https://maps.google.com/maps?q=Barangay%20Maruing,%20Philippines&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe> -->
                    <img src="public/image/Maruing Map/download.png" alt="Spot Map of Barangay Maruing" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                </div>
            </div>
            </div>
        </section>

    </main>

    <!-- Gallery Section -->
    <style>
        .gallery-section-wrapper {
            padding: 5rem 0;
            background-color: #ffffff;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        @media (max-width: 992px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .gallery-grid {
                grid-template-columns: 1fr;
            }
        }
        .gallery-item {
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 4 / 3;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.05);
        }
    </style>
    <section class="gallery-section-wrapper">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 4rem;">
                <h2 style="font-size: 2.8rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; font-family: 'Montserrat', sans-serif;">
                    Our <span style="color: var(--primary-color); position: relative;">Gallery<span style="position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 70%; height: 4px; background-color: var(--primary-color); border-radius: 2px;"></span></span>
                </h2>
                <p style="font-size: 1.1rem; color: #64748b; max-width: 800px; margin: 2.5rem auto 0; line-height: 1.6; font-family: 'Montserrat', sans-serif;">
                    Take a look at the latest events, activities, and programs organized for the residents<br>of Barangay Maruing.
                </p>
            </div>
            
            <div class="gallery-grid">
                <!-- Image 1 -->
                <div class="gallery-item">
                    <img src="https://picsum.photos/seed/festival/800/600" alt="Festival">
                </div>
                <!-- Image 2 -->
                <div class="gallery-item">
                    <img src="https://picsum.photos/seed/cleanup/800/600" alt="Community Cleanup">
                </div>
                <!-- Image 3 -->
                <div class="gallery-item">
                    <img src="https://picsum.photos/seed/basketball/800/600" alt="Basketball Game">
                </div>
                <!-- Image 4 -->
                <div class="gallery-item">
                    <img src="https://picsum.photos/seed/hall/800/600" alt="Barangay Hall">
                </div>
            </div>
        </div>
    </section>

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
</body>

</html>



