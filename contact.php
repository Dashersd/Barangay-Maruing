<?php
session_start();
// Reset chat after 1 day (86400 seconds)
if (isset($_SESSION['chat_start_time']) && (time() - $_SESSION['chat_start_time'] > 86400)) {
    unset($_SESSION['chat_thread_id']);
    unset($_SESSION['chat_user_name']);
    unset($_SESSION['chat_start_time']);
}

if (!isset($_SESSION['chat_thread_id'])) {
    $_SESSION['chat_thread_id'] = uniqid('thread_');
    $_SESSION['chat_start_time'] = time();
}
if (!isset($_SESSION['chat_user_name'])) {
    $_SESSION['chat_user_name'] = '';
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
    
                </div>
    
                <!-- Right Column: Chat Interface -->
                <div class="contact-form-col" data-aos="fade-left" id="chat-widget-container">
                    <?php $has_chat = !empty($_SESSION['chat_user_name']); ?>
                    <div id="start-chat-container" style="<?php echo $has_chat ? 'display:none;' : ''; ?>">
                        <h2>Start a Live Chat</h2>
                        <form id="start-chat-form">
                            <div class="form-group">
                                <label>Your Name</label>
                                <input type="text" id="chat-name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label>Service Type</label>
                                <select id="chat-service-type" class="form-control" required>
                                    <option value="" disabled selected>Select service</option>
                                    <option value="Barangay Clearance">Barangay Clearance</option>
                                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                                    <option value="Business Clearance">Business Clearance</option>
                                    <option value="Certificate of Residency">Certificate of Residency</option>
                                    <option value="Other Inquiry">Other Inquiry</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Initial Message</label>
                                <textarea id="chat-initial-message" class="form-control" placeholder="How can we help you?" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn-submit">Start Chat</button>
                        </form>
                    </div>

                    <div id="live-chat-container" style="<?php echo $has_chat ? '' : 'display:none;'; ?>">
                        <h2>Live Chat Support</h2>
                        <div id="live-chat-box" style="border: 1px solid #e2e8f0; border-radius: 10px; height: 400px; display: flex; flex-direction: column; background: #fff;">
                            <div id="chat-messages" style="flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: #f8faf9;">
                                <!-- Messages will be loaded here via AJAX -->
                            </div>
                            <div style="padding: 15px; border-top: 1px solid #e2e8f0; display: flex; gap: 10px;">
                                <input type="text" id="chat-reply-input" class="form-control" placeholder="Type a message..." style="margin-bottom:0;">
                                <button id="chat-reply-btn" class="btn-submit" style="width: auto; padding: 0.8rem 1.5rem; margin-top:0;">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <script>
                const threadId = "<?php echo $_SESSION['chat_thread_id']; ?>";
                let currentUserName = "<?php echo $_SESSION['chat_user_name'] ?? ''; ?>";

                document.addEventListener('DOMContentLoaded', () => {
                    const startForm = document.getElementById('start-chat-form');
                    
                    function bindChatEvents(name) {
                        currentUserName = name;
                        fetchMessages();
                        setInterval(fetchMessages, 3000);

                        const replyBtn = document.getElementById('chat-reply-btn');
                        if (replyBtn) {
                            replyBtn.addEventListener('click', () => {
                                const input = document.getElementById('chat-reply-input');
                                if (input.value.trim() !== '') {
                                    sendChatMessage(currentUserName, input.value.trim(), () => {
                                        input.value = '';
                                        fetchMessages();
                                    });
                                }
                            });
                        }
                    }

                    if (startForm) {
                        startForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const name = document.getElementById('chat-name').value;
                            const serviceTypeSelect = document.getElementById('chat-service-type');
                            const serviceType = serviceTypeSelect.options[serviceTypeSelect.selectedIndex].text;
                            const rawMessage = document.getElementById('chat-initial-message').value;
                            const message = `[Service: ${serviceType}]\n\n${rawMessage}`;

                            fetch('set_chat_session.php', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                body: 'name=' + encodeURIComponent(name)
                            }).then(() => {
                                sendChatMessage(name, message, () => {
                                    document.getElementById('start-chat-container').style.display = 'none';
                                    document.getElementById('live-chat-container').style.display = 'block';
                                    bindChatEvents(name);
                                });
                            });
                        });
                    }

                    if (currentUserName && document.getElementById('live-chat-container').style.display !== 'none') {
                        bindChatEvents(currentUserName);
                    }
                });

                function sendChatMessage(senderName, message, callback) {
                    const formData = new URLSearchParams();
                    formData.append('thread_id', threadId);
                    formData.append('sender_name', senderName);
                    formData.append('sender_type', 'user');
                    formData.append('message', message);

                    fetch('api/chat_send.php', {
                        method: 'POST',
                        body: formData
                    }).then(r => r.json()).then(res => {
                        if (res.status === 'success' && callback) callback();
                    });
                }

                function fetchMessages() {
                    fetch(`api/chat_fetch.php?thread_id=${threadId}&reader_type=user`)
                        .then(r => r.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const container = document.getElementById('chat-messages');
                                container.innerHTML = '';
                                data.messages.forEach(msg => {
                                    const isUser = msg.sender_type === 'user';
                                    const msgDiv = document.createElement('div');
                                    msgDiv.style.maxWidth = '75%';
                                    msgDiv.style.padding = '10px 15px';
                                    msgDiv.style.borderRadius = '10px';
                                    msgDiv.style.fontSize = '0.9rem';
                                    
                                    if (isUser) {
                                        msgDiv.style.alignSelf = 'flex-end';
                                        msgDiv.style.backgroundColor = '#137547';
                                        msgDiv.style.color = '#fff';
                                        msgDiv.style.borderBottomRightRadius = '0';
                                    } else {
                                        msgDiv.style.alignSelf = 'flex-start';
                                        msgDiv.style.backgroundColor = '#e2e8f0';
                                        msgDiv.style.color = '#1e293b';
                                        msgDiv.style.borderBottomLeftRadius = '0';
                                    }
                                    
                                    msgDiv.innerHTML = `<strong>${msg.sender_name}</strong><br>${msg.message}<div style="font-size:0.7rem; margin-top:5px; opacity:0.8; text-align:right;">${msg.created_at}</div>`;
                                    container.appendChild(msgDiv);
                                });
                                container.scrollTop = container.scrollHeight;
                            }
                        });
                }
                </script>
                
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
