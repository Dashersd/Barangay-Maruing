# Barangay Maruing Web-Based Information System

A modern, responsive web-based information portal for Barangay Maruing built with HTML5, CSS3, and JavaScript.

## Technology Stack

* **Structure**: Semantic HTML5
* **Styling**: Pure Vanilla CSS3 (Custom responsive grid, flexbox & design system)
* **Icons**: Bootstrap Icons
* **Interactivity**: Vanilla JavaScript (`main.js`)
* **Fonts**: Google Fonts (Montserrat & Dancing Script)

## Features

* **Home (`index.php`)**: Hero section, quick access cards, citizen portal overview, and recent announcements preview.
* **About (`about.php`)**: Vision, mission, community statistics (population, puroks, households), history, and geographic profile.
* **Officials (`officials.php`)**: Barangay Council directory featuring the Punong Barangay, Kagawads with committee assignments, SK Chairperson, and appointed administrative officers.
* **Services (`services.php`)**: Catalog of citizen services (Barangay Clearance, Residency, Indigency, Business Clearance, First-Time Jobseeker, Lupon Mediation) with requirements, fees, processing times, category filters, and an interactive online request modal.
* **Announcements (`announcements.php`)**: Filterable community advisories, public assembly notifications, clean-up drives, and health missions.
* **Contact (`contact.php`)**: Barangay Hall address, office hours, contact numbers, 24/7 emergency hotlines, FAQ accordion, and an interactive inquiry form.
* **Resident Portal (`login.php` & `register.php`)**: Account login and new resident registration interfaces with client-side validation and password toggle.

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
├── officials.php          # Barangay Officials Directory
├── spot-map.php           # Barangay Spot Map
├── contact.php            # Contact Details & Form
├── login.php              # Citizen Portal Login
├── register.php           # Resident Registration
├── README.md              # Documentation
└── public/
    └── assets/
        ├── css/
        │   └── style.css   # Custom CSS styling
        └── js/
            └── main.js     # Client-side JavaScript logic
```
