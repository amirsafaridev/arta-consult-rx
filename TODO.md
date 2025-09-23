# Arta Consult RX Plugin - Development TODO

## Overview
This is a comprehensive medical consultation and appointment booking system for WordPress with product ordering capabilities. The plugin supports multilingual content (WPML) and RTL/LTR layouts.

## Development Phases

### Phase 1: Plugin Foundation & Setup
- [ ] **1.1** Update plugin header information and constants
- [ ] **1.2** Create plugin activation/deactivation hooks
- [ ] **1.3** Setup plugin directory structure:
  ```
  arta-consult-rx/
  ├── assets/
  │   ├── css/
  │   ├── js/
  │   └── images/
  ├── includes/
  │   ├── class/
  │   ├── admin/
  │   ├── frontend/
  │   └── ajax/
  ├── languages/
  ├── templates/
  │   ├── admin/
  │   └── frontend/
  └── database/
  ```
- [ ] **1.4** Create main plugin class with proper initialization
- [ ] **1.5** Setup internationalization (i18n) support
- [ ] **1.6** Create plugin settings page in WordPress admin

### Phase 2: WooCommerce Integration
- [ ] **2.1** Check WooCommerce dependency and activation
- [ ] **2.2** Configure WooCommerce products for medical products:
  - Add custom product types for medical products
  - Add prescription requirement meta field
  - Link products to programs via meta fields
  - Configure product visibility and availability
- [ ] **2.3** Use WooCommerce orders for consultation requests:
  - Create custom order statuses for consultations
  - Add consultation form data to order meta
  - Link orders to programs and doctors
- [ ] **2.4** Use WooCommerce orders for appointment bookings:
  - Create appointment-specific order types
  - Store appointment data in order meta
  - Link appointments to consultations
- [ ] **2.5** Create custom WooCommerce order statuses:
  - Consultation Pending
  - Consultation Approved
  - Consultation Rejected
  - Appointment Scheduled
  - Appointment Completed
  - Product Request Pending
  - Product Approved
  - Product Rejected

### Phase 3: Custom Post Types & Taxonomies (WordPress Native)
- [ ] **3.1** Create 'Program' custom post type with meta fields:
  - Program title and description
  - Associated doctor (meta field)
  - Program objectives and benefits
  - Related WooCommerce products (meta field)
  - Consultation form settings
- [ ] **3.2** Create 'Doctor' custom post type with meta fields:
  - Doctor name and credentials
  - Specialties
  - Available time slots (meta field)
  - Contact information
  - Profile image
  - Associated programs (meta field)
- [ ] **3.3** Create taxonomies:
  - Program categories
  - Doctor specialties
  - Medical conditions
- [ ] **3.4** Add custom meta boxes for post types
- [ ] **3.5** Configure WooCommerce product meta fields:
  - Link to programs
  - Prescription requirement flag
  - Medical product type
  - Doctor approval required

### Phase 4: Consultation Form System
- [ ] **4.1** Create consultation form with fields:
  - Personal Information:
    - First name, last name
    - Gender (male/female/other)
    - Date of birth
    - Height and weight
    - Email and phone number
  - Medical Information:
    - Chronic diseases
    - Current medications
    - Medical history
    - Program objectives
  - Consent checkbox for medical consultation
- [ ] **4.2** Add form validation (client-side and server-side)
- [ ] **4.3** Implement form submission handling
- [ ] **4.4** Add form progress indicator
- [ ] **4.5** Create form styling for RTL/LTR support
- [ ] **4.6** Add form field conditional logic
- [ ] **4.7** Implement form data sanitization and security

### Phase 5: Appointment Booking System & Bulk Scheduling
- [ ] **5.1** Create appointment calendar interface
- [ ] **5.2** Implement time slot management:
  - Doctor availability settings (stored in doctor meta)
  - Individual time slot creation and editing
  - Recurring appointments
  - Holiday/break time management
- [ ] **5.3** **BULK APPOINTMENT SCHEDULING** (New Feature):
  - Admin interface for bulk time slot creation
  - Date range selection (start date to end date)
  - Time range selection (start time to end time)
  - Interval selection (15min, 30min, 1hour, etc.)
  - Days of week selection (Monday-Sunday)
  - Doctor assignment for bulk slots
  - Bulk slot creation with one click
  - Bulk slot deletion and modification
- [ ] **5.4** Build appointment selection interface:
  - Date picker
  - Available time slots display
  - Time slot selection
  - Appointment confirmation
- [ ] **5.5** Create appointment status management (via WooCommerce orders):
  - Pending
  - Confirmed
  - Completed
  - Cancelled
  - No-show
- [ ] **5.6** Implement appointment conflict detection
- [ ] **5.7** Add appointment reminder system
- [ ] **5.8** Create appointment modification/cancellation

### Phase 6: Admin Dashboard & Management (WooCommerce Integration)
- [ ] **6.1** Create admin menu structure:
  - Consultations (WooCommerce Orders with consultation status)
  - Appointments (WooCommerce Orders with appointment status)
  - Product Requests (WooCommerce Orders with product request status)
  - Doctors
  - Programs
  - Bulk Appointment Scheduler (New)
  - Settings
- [ ] **6.2** Build consultation management interface (via WooCommerce orders):
  - List all consultation orders
  - View consultation details from order meta
  - Approve/reject consultations (change order status)
  - Add admin notes to orders
  - Export consultation data
- [ ] **6.3** Create appointment management (via WooCommerce orders):
  - Calendar view
  - List view
  - Appointment details from order meta
  - Status updates (order status changes)
  - Doctor assignment
- [ ] **6.4** Build product request management (via WooCommerce orders):
  - List product request orders
  - Approve/reject requests (order status changes)
  - Link to consultations
  - Status tracking
- [ ] **6.5** Create doctor management interface:
  - Add/edit doctors
  - Manage availability (meta fields)
  - Assign to programs
  - View doctor statistics
- [ ] **6.6** **BULK APPOINTMENT SCHEDULER** (New Admin Interface):
  - Date range picker
  - Time range selector
  - Interval selector (15min, 30min, 1hour)
  - Days of week selector
  - Doctor selection
  - Bulk slot creation interface
  - Preview before creation
  - Bulk slot management (edit/delete)
- [ ] **6.7** Add bulk actions for all management interfaces
- [ ] **6.8** Implement search and filtering
- [ ] **6.9** Create admin dashboard widgets

### Phase 7: Product Request System (WooCommerce Integration)
- [ ] **7.1** Create product display on program pages (WooCommerce products)
- [ ] **7.2** Implement product request workflow:
  - Product selection from WooCommerce
  - Consultation requirement check
  - Request submission (create WooCommerce order)
  - Admin approval process (order status change)
- [ ] **7.3** Build product request form (WooCommerce order form)
- [ ] **7.4** Create product request status tracking (via order status)
- [ ] **7.5** Link product requests to consultations (order meta)
- [ ] **7.6** Add prescription requirement handling (product meta)
- [ ] **7.7** Implement product availability management (WooCommerce stock)
- [ ] **7.8** Configure WooCommerce product types for medical products
- [ ] **7.9** Add custom WooCommerce product fields for medical data

### Phase 8: Frontend Templates & User Interface
- [ ] **8.1** Create program page template:
  - Program introduction
  - Doctor information
  - Program benefits
  - Consultation form
  - Related products
- [ ] **8.2** Build consultation form template
- [ ] **8.3** Create appointment booking template
- [ ] **8.4** Design product listing template
- [ ] **8.5** Create user dashboard:
  - Consultation history
  - Appointment history
  - Product requests
  - Profile management
- [ ] **8.6** Implement responsive design
- [ ] **8.7** Add loading states and animations
- [ ] **8.8** Create error handling pages

### Phase 9: Shortcodes & Widgets
- [ ] **9.1** Create shortcodes:
  - `[arta_programs]` - Display programs list
  - `[arta_consultation_form]` - Display consultation form
  - `[arta_appointment_booking]` - Display booking form
  - `[arta_products]` - Display products
  - `[arta_doctor_info]` - Display doctor information
  - `[arta_user_dashboard]` - Display user dashboard
- [ ] **9.2** Create widgets:
  - Recent programs
  - Upcoming appointments
  - Doctor availability
  - Quick consultation form
- [ ] **9.3** Add shortcode parameters and customization options
- [ ] **9.4** Create shortcode documentation

### Phase 10: AJAX & JavaScript Functionality
- [ ] **10.1** Implement AJAX form submissions
- [ ] **10.2** Create dynamic calendar loading
- [ ] **10.3** Add real-time form validation
- [ ] **10.4** Implement appointment slot checking
- [ ] **10.5** Create dynamic product filtering
- [ ] **10.6** Add search functionality
- [ ] **10.7** Implement pagination
- [ ] **10.8** Create loading indicators
- [ ] **10.9** Add error handling and user feedback

### Phase 11: WPML Integration & Multilingual Support (No Language Files)
- [ ] **11.1** Implement WPML compatibility:
  - Custom post types translation (Program, Doctor)
  - Custom taxonomies translation
  - Custom fields translation
  - WooCommerce products translation
  - WooCommerce orders meta translation
- [ ] **11.2** Create language-specific templates
- [ ] **11.3** Implement language-specific form validation messages
- [ ] **11.4** Add language switcher support
- [ ] **11.5** Test all functionality in multiple languages
- [ ] **11.6** Ensure WPML string translation compatibility
- [ ] **11.7** Configure WPML settings for custom post types

### Phase 12: RTL/LTR Layout Support (Based on Selected Language)
- [ ] **12.1** Create RTL-specific CSS files
- [ ] **12.2** Implement dynamic direction switching based on WPML language
- [ ] **12.3** Adjust form layouts for RTL
- [ ] **12.4** Modify calendar layout for RTL
- [ ] **12.5** Update admin interface for RTL
- [ ] **12.6** Test all components in both directions
- [ ] **12.7** Add direction-specific JavaScript handling
- [ ] **12.8** Auto-detect language direction from WPML settings
- [ ] **12.9** Apply appropriate CSS classes based on language

### Phase 13: Email & Notification System
- [ ] **13.1** Create email templates:
  - Consultation confirmation
  - Appointment confirmation
  - Appointment reminder
  - Status update notifications
  - Product request notifications
- [ ] **13.2** Implement email sending functionality
- [ ] **13.3** Add email customization options
- [ ] **13.4** Create notification preferences
- [ ] **13.5** Implement SMS notifications (optional)
- [ ] **13.6** Add email queue system
- [ ] **13.7** Create email logs and tracking

### Phase 14: Security & Data Protection
- [ ] **14.1** Implement nonce verification for all forms
- [ ] **14.2** Add capability checks for admin functions
- [ ] **14.3** Sanitize all user inputs
- [ ] **14.4** Escape all outputs
- [ ] **14.5** Implement rate limiting for form submissions
- [ ] **14.6** Add CSRF protection
- [ ] **14.7** Create data encryption for sensitive information
- [ ] **14.8** Implement GDPR compliance features
- [ ] **14.9** Add audit logging

### Phase 15: Performance & Optimization
- [ ] **15.1** Optimize database queries
- [ ] **15.2** Implement caching mechanisms
- [ ] **15.3** Minify CSS and JavaScript files
- [ ] **15.4** Optimize images and assets
- [ ] **15.5** Implement lazy loading
- [ ] **15.6** Add database indexing
- [ ] **15.7** Create performance monitoring
- [ ] **15.8** Optimize for mobile devices

### Phase 16: Testing & Quality Assurance
- [ ] **16.1** Unit testing for core functions
- [ ] **16.2** Integration testing for workflows
- [ ] **16.3** Cross-browser testing
- [ ] **16.4** Mobile responsiveness testing
- [ ] **16.5** Performance testing
- [ ] **16.6** Security testing
- [ ] **16.7** User acceptance testing
- [ ] **16.8** Load testing
- [ ] **16.9** Accessibility testing

### Phase 17: Elementor Widgets Integration
- [ ] **17.1** Check Elementor dependency and activation
- [ ] **17.2** Create Elementor widget base class
- [ ] **17.3** **Program Archive Widgets**:
  - Program Grid Widget (with filtering and pagination)
  - Program List Widget (with different layouts)
  - Program Categories Filter Widget
  - Program Search Widget
  - Featured Programs Widget
  - Program Statistics Widget
- [ ] **17.4** **Single Program Page Widgets**:
  - Program Title Widget
  - Program Description Widget
  - Program Benefits Widget
  - Associated Doctor Widget
  - Related Products Widget
  - Consultation Form Widget
  - Appointment Booking Widget
  - Program Gallery Widget
  - Program FAQ Widget
- [ ] **17.5** **Doctor Widgets**:
  - Doctor Profile Widget
  - Doctor Availability Widget
  - Doctor Specialties Widget
  - Doctor Contact Widget
- [ ] **17.6** **Product Widgets**:
  - Medical Product Display Widget
  - Product Request Button Widget
  - Prescription Requirement Widget
  - Related Programs Widget
- [ ] **17.7** **Form Widgets**:
  - Consultation Form Widget
  - Appointment Booking Form Widget
  - Product Request Form Widget
- [ ] **17.8** **Utility Widgets**:
  - Language Switcher Widget
  - RTL/LTR Toggle Widget
  - Breadcrumb Widget
  - Social Share Widget
- [ ] **17.9** Add widget styling and customization options
- [ ] **17.10** Implement widget responsive design
- [ ] **17.11** Add widget documentation and help text
- [ ] **17.12** Test all widgets with different themes

### Phase 18: Documentation & Deployment
- [ ] **18.1** Create user documentation
- [ ] **18.2** Write admin documentation
- [ ] **18.3** Create developer documentation
- [ ] **18.4** Write installation guide
- [ ] **18.5** Create troubleshooting guide
- [ ] **18.6** Prepare deployment package
- [ ] **18.7** Create backup/restore procedures
- [ ] **18.8** Set up monitoring and logging

## Technical Requirements

### Database Schema (Using WordPress & WooCommerce Native Tables)

### Custom Post Types
- **Programs** (Custom Post Type)
  - Meta fields: `_associated_doctor`, `_related_products`, `_consultation_settings`
- **Doctors** (Custom Post Type)  
  - Meta fields: `_specialties`, `_available_slots`, `_contact_info`, `_associated_programs`

### WooCommerce Integration
- **Products** (WooCommerce Products)
  - Meta fields: `_requires_prescription`, `_medical_product_type`, `_linked_programs`, `_doctor_approval_required`
- **Orders** (WooCommerce Orders)
  - Order types: Consultation, Appointment, Product Request
  - Meta fields: `_consultation_data`, `_appointment_data`, `_product_request_data`, `_linked_consultation`, `_doctor_id`, `_program_id`

### Custom Taxonomies
- **Program Categories** (Custom Taxonomy)
- **Doctor Specialties** (Custom Taxonomy)  
- **Medical Conditions** (Custom Taxonomy)

### Appointment Slots Storage
- **Doctor Meta Fields**: `_available_slots` (JSON format)
- **Bulk Slots**: Stored in doctor meta as structured data

### File Structure
```
arta-consult-rx/
├── arta-consult-rx.php (main plugin file)
├── includes/
│   ├── class/
│   │   ├── class-arta-main.php
│   │   ├── class-arta-post-types.php
│   │   ├── class-arta-taxonomies.php
│   │   ├── class-arta-woocommerce.php
│   │   ├── class-arta-forms.php
│   │   ├── class-arta-appointments.php
│   │   ├── class-arta-bulk-scheduler.php (NEW)
│   │   ├── class-arta-admin.php
│   │   ├── class-arta-frontend.php
│   │   ├── class-arta-ajax.php
│   │   ├── class-arta-email.php
│   │   ├── class-arta-wpml.php
│   │   └── class-arta-elementor.php (NEW)
│   ├── admin/
│   │   ├── admin-menus.php
│   │   ├── admin-pages.php
│   │   ├── admin-bulk-scheduler.php (NEW)
│   │   └── admin-assets.php
│   ├── frontend/
│   │   ├── frontend-templates.php
│   │   ├── frontend-assets.php
│   │   └── frontend-shortcodes.php
│   ├── ajax/
│   │   ├── ajax-forms.php
│   │   ├── ajax-calendar.php
│   │   ├── ajax-bulk-scheduler.php (NEW)
│   │   └── ajax-products.php
│   ├── elementor/
│   │   ├── widgets/
│   │   │   ├── program-archive/
│   │   │   │   ├── program-grid.php
│   │   │   │   ├── program-list.php
│   │   │   │   ├── program-categories-filter.php
│   │   │   │   ├── program-search.php
│   │   │   │   ├── featured-programs.php
│   │   │   │   └── program-statistics.php
│   │   │   ├── single-program/
│   │   │   │   ├── program-title.php
│   │   │   │   ├── program-description.php
│   │   │   │   ├── program-benefits.php
│   │   │   │   ├── associated-doctor.php
│   │   │   │   ├── related-products.php
│   │   │   │   ├── consultation-form.php
│   │   │   │   ├── appointment-booking.php
│   │   │   │   ├── program-gallery.php
│   │   │   │   └── program-faq.php
│   │   │   ├── doctor/
│   │   │   │   ├── doctor-profile.php
│   │   │   │   ├── doctor-availability.php
│   │   │   │   ├── doctor-specialties.php
│   │   │   │   └── doctor-contact.php
│   │   │   ├── product/
│   │   │   │   ├── medical-product-display.php
│   │   │   │   ├── product-request-button.php
│   │   │   │   ├── prescription-requirement.php
│   │   │   │   └── related-programs.php
│   │   │   ├── forms/
│   │   │   │   ├── consultation-form.php
│   │   │   │   ├── appointment-booking-form.php
│   │   │   │   └── product-request-form.php
│   │   │   └── utility/
│   │   │       ├── language-switcher.php
│   │   │       ├── rtl-ltr-toggle.php
│   │   │       ├── breadcrumb.php
│   │   │       └── social-share.php
│   │   └── class-arta-elementor-widgets.php
│   └── functions.php
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   ├── frontend.css
│   │   ├── rtl.css
│   │   ├── ltr.css
│   │   └── responsive.css
│   ├── js/
│   │   ├── admin.js
│   │   ├── frontend.js
│   │   ├── calendar.js
│   │   ├── forms.js
│   │   └── bulk-scheduler.js (NEW)
│   └── images/
├── templates/
│   ├── admin/
│   │   ├── consultations-list.php
│   │   ├── appointments-calendar.php
│   │   ├── product-requests.php
│   │   └── bulk-scheduler.php (NEW)
│   └── frontend/
│       ├── program-page.php
│       ├── consultation-form.php
│       ├── appointment-booking.php
│       └── user-dashboard.php
└── woocommerce/
    ├── single-product-medical.php
    ├── order-consultation.php
    └── order-appointment.php
```

## Key Features Implementation

### 1. Program Pages
- Custom post type with meta fields
- Doctor association (meta field)
- WooCommerce product relationships (meta field)
- Consultation form integration
- WPML multilingual content support

### 2. Consultation Form
- Multi-step form with validation
- Medical information collection
- Consent management
- Data sanitization and security
- AJAX submission
- Creates WooCommerce order with consultation data

### 3. Appointment Booking
- Calendar integration
- Time slot management (stored in doctor meta)
- Doctor availability
- Conflict detection
- Status tracking (via WooCommerce order status)
- **BULK APPOINTMENT SCHEDULING** (New Feature)

### 4. Product Request System (WooCommerce Integration)
- WooCommerce product display and selection
- Consultation requirement check
- Admin approval workflow (order status changes)
- Status notifications
- Prescription handling (product meta)
- Creates WooCommerce orders for product requests

### 5. Admin Management (WooCommerce Integration)
- Comprehensive dashboard
- Consultation management (via WooCommerce orders)
- Appointment scheduling with bulk scheduler
- Product request handling (via WooCommerce orders)
- Doctor management
- **BULK APPOINTMENT SCHEDULER** interface
- Reporting and analytics

### 6. Multilingual Support (WPML Only)
- WPML integration (no language files)
- Content translation
- Language-specific templates
- RTL/LTR support based on selected language
- Auto-direction detection

### 7. Bulk Appointment Scheduler (NEW FEATURE)
- Admin interface for bulk time slot creation
- Date range selection (start date to end date)
- Time range selection (start time to end time)
- Interval selection (15min, 30min, 1hour, etc.)
- Days of week selection (Monday-Sunday)
- Doctor assignment for bulk slots
- Bulk slot creation with one click
- Bulk slot deletion and modification
- Preview before creation
- Conflict detection for bulk slots
- Integration with existing appointment system

### 8. Elementor Widgets Integration (NEW FEATURE)
- Custom widgets for program archive pages
- Custom widgets for single program pages
- Doctor profile and availability widgets
- Medical product display widgets
- Form widgets for consultations and bookings
- Utility widgets for language and navigation
- Responsive design for all widgets
- Custom styling and customization options
- Integration with Elementor Pro features
- WPML compatibility for all widgets

## Security Considerations
- Nonce verification for all forms
- Capability checks for admin functions
- Input sanitization and validation
- Output escaping
- Rate limiting
- CSRF protection
- Data encryption
- GDPR compliance

## Performance Optimization
- Database query optimization
- Caching implementation
- Asset minification
- Lazy loading
- Mobile optimization
- CDN integration

## Testing Strategy
- Unit testing
- Integration testing
- Cross-browser testing
- Mobile testing
- Performance testing
- Security testing
- User acceptance testing

## Bulk Appointment Scheduler - Detailed Implementation

### Admin Interface Features
- [ ] **Date Range Picker**: Select start and end dates for bulk slot creation
- [ ] **Time Range Selector**: Choose start time and end time for daily slots
- [ ] **Interval Selector**: 15 minutes, 30 minutes, 1 hour, 2 hours options
- [ ] **Days of Week**: Checkboxes for Monday through Sunday
- [ ] **Doctor Selection**: Dropdown to assign slots to specific doctors
- [ ] **Preview Mode**: Show all slots before creation
- [ ] **Bulk Actions**: Create, edit, or delete multiple slots at once

### Technical Implementation
- [ ] **Slot Storage**: Store in doctor meta as JSON array
- [ ] **Conflict Detection**: Check for existing appointments
- [ ] **Validation**: Ensure no overlapping slots
- [ ] **AJAX Interface**: Real-time preview and creation
- [ ] **Batch Processing**: Handle large numbers of slots efficiently

### User Experience
- [ ] **Intuitive Interface**: Easy-to-use form with clear labels
- [ ] **Progress Indicators**: Show creation progress for large batches
- [ ] **Error Handling**: Clear error messages for conflicts or issues
- [ ] **Success Feedback**: Confirmation of created slots
- [ ] **Undo Functionality**: Ability to reverse bulk operations

## Elementor Widgets - Detailed Implementation

### Program Archive Widgets
- [ ] **Program Grid Widget**:
  - Grid layout with customizable columns (1-6 columns)
  - Card design with image, title, excerpt, and CTA button
  - Filtering by categories and tags
  - Pagination support
  - Load more functionality
  - Custom post meta display (duration, price, etc.)
  - Responsive breakpoints

- [ ] **Program List Widget**:
  - List layout with different styles (horizontal, vertical)
  - Featured image, title, excerpt, and metadata
  - Sorting options (date, title, popularity)
  - Category filtering
  - Search functionality
  - Custom styling options

- [ ] **Program Categories Filter Widget**:
  - Dropdown or checkbox filter options
  - Hierarchical category display
  - AJAX filtering without page reload
  - Custom styling for active/inactive states
  - Integration with other archive widgets

- [ ] **Program Search Widget**:
  - Search input with autocomplete
  - Advanced search filters (category, date, price)
  - Search results display
  - No results message customization
  - Search history functionality

- [ ] **Featured Programs Widget**:
  - Display selected featured programs
  - Carousel/slider functionality
  - Custom selection from admin
  - Different layout options
  - Auto-rotation settings

- [ ] **Program Statistics Widget**:
  - Total programs count
  - Category-wise statistics
  - Popular programs display
  - Recent programs list
  - Customizable display format

### Single Program Page Widgets
- [ ] **Program Title Widget**:
  - Display program title with custom styling
  - Subtitle support
  - Breadcrumb integration
  - Custom typography options
  - WPML translation support

- [ ] **Program Description Widget**:
  - Rich text content display
  - Custom styling options
  - Read more/less functionality
  - Custom excerpt length
  - HTML content support

- [ ] **Program Benefits Widget**:
  - List of program benefits
  - Icon support for each benefit
  - Custom styling for list items
  - Animation effects
  - Responsive design

- [ ] **Associated Doctor Widget**:
  - Doctor profile display
  - Doctor image, name, and credentials
  - Specialties and experience
  - Contact information
  - Link to doctor profile page
  - Custom styling options

- [ ] **Related Products Widget**:
  - Display WooCommerce products linked to program
  - Product grid/list layout
  - Product images, titles, and prices
  - Add to cart/request buttons
  - Prescription requirement indicators
  - Custom styling options

- [ ] **Consultation Form Widget**:
  - Multi-step consultation form
  - Form field customization
  - Validation and error handling
  - Success/error messages
  - AJAX form submission
  - Custom styling options

- [ ] **Appointment Booking Widget**:
  - Calendar interface for booking
  - Available time slots display
  - Doctor selection
  - Date and time picker
  - Booking confirmation
  - Integration with bulk scheduler

- [ ] **Program Gallery Widget**:
  - Image gallery display
  - Lightbox functionality
  - Different gallery layouts
  - Image captions
  - Responsive design
  - Custom styling options

- [ ] **Program FAQ Widget**:
  - FAQ accordion display
  - Expandable/collapsible items
  - Custom styling for questions/answers
  - Search within FAQ
  - Animation effects

### Doctor Widgets
- [ ] **Doctor Profile Widget**:
  - Complete doctor profile display
  - Professional photo and credentials
  - Specialties and experience
  - Education and certifications
  - Contact information
  - Social media links

- [ ] **Doctor Availability Widget**:
  - Available time slots display
  - Calendar view of availability
  - Next available appointment
  - Booking button integration
  - Real-time availability updates

- [ ] **Doctor Specialties Widget**:
  - List of medical specialties
  - Icon support for specialties
  - Custom styling options
  - Link to specialty pages
  - Responsive design

- [ ] **Doctor Contact Widget**:
  - Contact information display
  - Phone, email, and address
  - Contact form integration
  - Social media links
  - Office hours display

### Product Widgets
- [ ] **Medical Product Display Widget**:
  - WooCommerce product display
  - Medical product specific fields
  - Prescription requirement indicators
  - Product images and descriptions
  - Price display
  - Request button instead of add to cart

- [ ] **Product Request Button Widget**:
  - Custom request button
  - Consultation requirement check
  - Different button styles
  - Custom text and icons
  - Integration with consultation form

- [ ] **Prescription Requirement Widget**:
  - Display prescription requirements
  - Warning messages
  - Doctor approval indicators
  - Custom styling for warnings
  - Icon support

- [ ] **Related Programs Widget**:
  - Display programs related to product
  - Program grid/list layout
  - Link to program pages
  - Custom styling options
  - Responsive design

### Form Widgets
- [ ] **Consultation Form Widget**:
  - Complete consultation form
  - Multi-step form functionality
  - Field validation
  - File upload support
  - Custom styling options
  - AJAX submission

- [ ] **Appointment Booking Form Widget**:
  - Appointment booking form
  - Calendar integration
  - Time slot selection
  - Doctor selection
  - Form validation
  - Confirmation messages

- [ ] **Product Request Form Widget**:
  - Product request form
  - Consultation requirement check
  - Product selection
  - Custom fields
  - Form validation
  - Success messages

### Utility Widgets
- [ ] **Language Switcher Widget**:
  - WPML language switcher
  - Different display styles
  - Flag icons support
  - Custom styling options
  - Responsive design

- [ ] **RTL/LTR Toggle Widget**:
  - Direction toggle functionality
  - Custom styling for RTL/LTR
  - Icon support
  - Integration with WPML
  - Responsive design

- [ ] **Breadcrumb Widget**:
  - Custom breadcrumb display
  - Program hierarchy support
  - Custom styling options
  - Icon support
  - Responsive design

- [ ] **Social Share Widget**:
  - Social media sharing buttons
  - Program-specific sharing
  - Custom social networks
  - Share count display
  - Custom styling options

### Widget Features
- [ ] **Responsive Design**: All widgets work on mobile, tablet, and desktop
- [ ] **Custom Styling**: Extensive styling options for each widget
- [ ] **WPML Compatibility**: All widgets support multilingual content
- [ ] **RTL/LTR Support**: Automatic direction detection and styling
- [ ] **Animation Effects**: Smooth animations and transitions
- [ ] **Custom CSS**: Additional CSS customization options
- [ ] **Conditional Display**: Show/hide widgets based on conditions
- [ ] **Performance Optimization**: Optimized for fast loading
- [ ] **Accessibility**: WCAG compliance for all widgets
- [ ] **Documentation**: Help text and documentation for each widget

This TODO list provides a comprehensive roadmap for developing the Arta Consult RX plugin with all the required features and functionality, including the new bulk appointment scheduling system and Elementor widgets integration.
