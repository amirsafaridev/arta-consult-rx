# Arta Consult RX

<div align="center">

![WordPress](https://img.shields.io/badge/WordPress-6.8+-21759B?style=for-the-badge&logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![WooCommerce](https://img.shields.io/badge/WooCommerce-8.0+-96588A?style=for-the-badge&logo=woocommerce&logoColor=white)
![License](https://img.shields.io/badge/License-GPL%202.0-green?style=for-the-badge)

**A comprehensive WordPress plugin for managing medical consultations, appointments, and prescription generation integrated with WooCommerce**

[Features](#-features) • [Installation](#-installation) • [Documentation](#-documentation)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Architecture](#-architecture)
- [Screenshots](#-screenshots)
- [License](#-license)
- [Author](#-author)

## 🎯 Overview

**Arta Consult RX** is a powerful WordPress plugin designed for medical consultation platforms. It seamlessly integrates with WooCommerce to provide a complete solution for managing medical programs, appointments, patient consultations, and automated prescription generation.

The plugin enables healthcare providers to:
- Create and manage medical consultation programs as WooCommerce products
- Schedule and manage patient appointments
- Collect comprehensive medical information during checkout
- Automatically generate professional PDF prescriptions with doctor stamps
- Manage doctor assignments and user roles
- Integrate with Elementor for custom page building

## ✨ Features

### 🏥 Medical Consultation Management
- **Custom Post Types**: Dedicated post type for medical programs with rich metadata
- **Program Management**: Create programs with goals, benefits, descriptions, and assigned doctors
- **Doctor Role Management**: Custom user roles with specific capabilities for healthcare providers

### 📅 Appointment System
- **Appointment Scheduling**: Frontend appointment booking form with date/time selection
- **Appointment Management**: View and manage appointments in the admin dashboard
- **Elementor Integration**: Drag-and-drop appointment form widget for Elementor

### 💊 Prescription Generation
- **Dynamic Prescription Templates**: Create customizable prescription templates with dynamic placeholders
- **PDF Generation**: Professional PDF prescription generation using TCPDF library
- **Doctor Stamp Integration**: Upload and automatically include doctor stamps in prescriptions
- **RTL/LTR Support**: Full support for both right-to-left (Persian/Arabic) and left-to-right languages
- **Order Integration**: Automatic prescription generation from WooCommerce orders

### 🛒 WooCommerce Integration
- **Product Customization**: Add prescription templates and doctor stamps to products
- **Custom Checkout Fields**: Extended checkout form with medical information fields:
  - Gender, Birth Date, Height, Weight
  - Chronic Diseases, Current Medications
  - Medical History, Program Goals, Allergies
- **Single Item Cart**: Enforce single-item cart functionality for consultation orders
- **WhatsApp Checkout**: Optional WhatsApp integration for order completion

### 🎨 Elementor Widgets
- **Program Title Widget**: Display program titles with custom styling
- **Program Description Widget**: Show program descriptions
- **Program Goals Widget**: Display program goals in a structured format
- **Program Benefits Widget**: Showcase program benefits
- **Assigned Doctors Widget**: Display assigned doctors for each program
- **Appointment Form Widget**: Integrated appointment booking form
- **Related Products Widget**: Show related consultation programs

### 👤 User Account Features
- **My Account Integration**: Custom "My Requests" endpoint in WooCommerce My Account
- **Order History**: View consultation orders and download prescriptions
- **Patient Dashboard**: Easy access to appointment history and prescriptions

### 🔧 Admin Features
- **Comprehensive Admin Panel**: Dedicated admin interface for managing consultations
- **Database Management**: Custom database tables for appointments and related data
- **Guide System**: Built-in help and documentation system
- **Meta Boxes**: Rich meta boxes for program and appointment management

## 📦 Requirements

- **WordPress**: 5.5 or higher
- **PHP**: 7.4 or higher
- **WooCommerce**: 8.0 or higher (for full functionality)
- **Elementor**: 3.0 or higher (optional, for widget support)

## 🚀 Installation

### Method 1: Manual Installation

1. Download the plugin ZIP file
2. Navigate to **WordPress Admin → Plugins → Add New**
3. Click **Upload Plugin** and select the ZIP file
4. Click **Install Now** and then **Activate**

### Method 2: Via FTP

1. Extract the plugin ZIP file
2. Upload the `arta-consult-rx` folder to `/wp-content/plugins/`
3. Navigate to **WordPress Admin → Plugins**
4. Find **Arta Consult RX** and click **Activate**

### Post-Installation

After activation, the plugin will:
- Create necessary database tables
- Register custom post types
- Add custom user roles (Doctor role)
- Flush rewrite rules for proper URL structure

## ⚙️ Configuration

### Setting Up Medical Programs

1. Navigate to **Programs → Add New** in WordPress admin
2. Fill in program details:
   - Title and description
   - Program goals and benefits
   - Assign doctors
   - Set program metadata
3. Link the program to a WooCommerce product

### Configuring Prescription Templates

1. Edit a WooCommerce product
2. Scroll to the **Prescription Settings** section
3. Enter your prescription template using dynamic placeholders:
   ```
   Patient: {billing_first_name} {billing_last_name}
   Date of Birth: {arta_birth_date}
   Gender: {arta_gender}
   ...
   ```
4. Upload a doctor stamp image (optional)
5. Save the product

### Available Prescription Placeholders

- **Billing Fields**: `{billing_first_name}`, `{billing_last_name}`, `{billing_email}`, `{billing_phone}`, `{billing_address_1}`, `{billing_city}`, etc.
- **Medical Fields**: `{arta_gender}`, `{arta_birth_date}`, `{arta_height}`, `{arta_weight}`, `{arta_chronic_diseases}`, `{arta_current_medications}`, `{arta_medical_history}`, `{arta_program_goal}`, `{arta_allergies}`

### Setting Up Elementor Widgets

1. Edit a page with Elementor
2. Search for "Arta" widgets in the widget panel
3. Drag and drop widgets onto your page
4. Configure widget settings as needed

## 📖 Usage

### For Administrators

1. **Create Programs**: Add new medical consultation programs
2. **Manage Appointments**: View and manage patient appointments
3. **Assign Doctors**: Assign healthcare providers to programs
4. **Configure Products**: Set up WooCommerce products with prescription templates

### For Doctors

1. **View Assigned Programs**: Access programs assigned to you
2. **Review Patient Information**: View patient medical data from orders
3. **Download Prescriptions**: Access generated prescriptions for review

### For Patients/Customers

1. **Browse Programs**: View available consultation programs
2. **Book Appointments**: Schedule consultation appointments
3. **Complete Checkout**: Fill in medical information during checkout
4. **Download Prescriptions**: Download PDF prescriptions from order details

## 🏗️ Architecture

### Directory Structure

```
arta-consult-rx/
├── assets/
│   ├── css/          # Stylesheets
│   └── js/           # JavaScript files
├── includes/
│   ├── class/        # Core PHP classes
│   ├── Elementor/    # Elementor widget integrations
│   └── helpers/      # Helper functions
├── languages/        # Translation files
├── templates/        # Template files
├── arta-consult-rx.php  # Main plugin file
└── README.md
```

### Core Classes

- **Arta_Consult_RX**: Main plugin class and initialization
- **Arta_Prescription**: Handles prescription generation and PDF creation
- **Arta_Appointment_Form**: Manages appointment booking functionality
- **Arta_Checkout_Fields**: Extends WooCommerce checkout with medical fields
- **Arta_Post_Types**: Registers custom post types
- **Arta_User_Roles**: Manages custom user roles
- **Arta_Database**: Handles database operations
- **Arta_Admin**: Admin interface functionality

### Key Technologies

- **TCPDF**: PDF generation library for prescriptions
- **WordPress Hooks**: Extensive use of actions and filters
- **WooCommerce Hooks**: Deep integration with WooCommerce
- **Elementor API**: Custom widget development
- **WordPress Media Library**: Doctor stamp image management

## 📸 Screenshots

> _Screenshots coming soon. This section will showcase the plugin's interface, prescription generation, and admin panels._

## 📄 License

This project is licensed under the **GPL v2 or later** License.

```
Copyright (C) 2024 Amir Safari

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```

## 👨‍💻 Author

**Amir Safari**

- Portfolio: [https://amirsafaridev.github.io/](https://amirsafaridev.github.io/)
- GitHub: [@amirsafaridev](https://github.com/amirsafaridev)

---

<div align="center">

**Made with ❤️ for the Amir Safari**

⭐ Star this repo if you find it helpful!

</div>

