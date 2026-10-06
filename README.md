# Barangay Maruing Web-Based Information System

A modern, responsive web-based information portal for Barangay Maruing built with HTML5, CSS3, and JavaScript.

## Technology Stack

* **Structure**: Semantic HTML5
* **Styling**: Pure Vanilla CSS3 (Custom responsive grid, flexbox & design system)
* **Animations**: AOS (Animate on Scroll) Library
* **Icons**: Bootstrap Icons
* **Interactivity**: Vanilla JavaScript (`main.js`, `spotmap.js`)
* **Fonts**: Google Fonts (Montserrat & Dancing Script)

## Features

* **Home (`index.php`)**: Hero section, quick access cards, citizen portal overview, and recent announcements preview.
* **About (`about.php`)**: Community statistics, history, and geographic profile.
* **Vision & Mission (`vision.php`, `mission.php`)**: Core values and guiding principles.
* **Officials (`officials.php`, `SKofficial.php`)**: Barangay Council and Sangguniang Kabataan directory.
* **Spot Map (`spot-map.php`)**: Interactive map with Purok filtering, dynamic map image toggling, and animated entrance.
* **Gallery (`gallery.php`)**: Beautiful grid showcasing community life and events.
* **Services (`services.php`)**: Catalog of citizen services (Clearance, Indigency, etc.).
* **Announcements (`announcements.php`)**: Community advisories and upcoming events.
* **Contact (`contact.php`)**: Office hours, contact details, and an interactive inquiry form.
* **Resident Portal (`login.php` & `register.php`)**: Account login and new resident registration interfaces.

## How to Run

### Run via XAMPP Local Web Server
Ensure Apache is running in the XAMPP Control Panel. Since the files are in `htdocs`:
```text
C:\xampp\htdocs\Barangay Maruing Web-Based Information System\
```
Open your browser and navigate to:
```text
http://localhost/Barangay%20Maruing%20Web-Based%20Information%20System/index.php
```

## File Structure

```text
Barangay Maruing Web-Based Information System/
├── index.php              # Homepage
├── about.php              # About Barangay Maruing
├── vision.php             # Vision Statement
├── mission.php            # Mission Statement
├── officials.php          # Barangay Officials Directory
├── SKofficial.php         # SK Officials Directory
├── spot-map.php           # Interactive Barangay Map
├── gallery.php            # Photo Gallery
├── services.php           # Barangay Services
├── announcements.php      # News and Announcements
├── contact.php            # Contact Details & Form
├── login.php              # Citizen Portal Login
├── register.php           # Resident Registration
├── README.md              # Documentation
└── public/
    └── assets/
        ├── css/           # Page-specific stylesheets and main style.css
        └── js/
            ├── main.js    # Global interactions
            └── spotmap.js # Map switching and Purok filtering logic
```
