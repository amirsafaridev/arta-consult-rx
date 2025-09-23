<?php
/**
 * Admin Dashboard Template
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap arta-dashboard-wrap">
    <div class="arta-dashboard-header">
        <div class="arta-header-content">
            <div class="arta-header-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 21 21V9M19 9H14V4H5V21H19V9Z" fill="#2196f3"/>
                </svg>
            </div>
            <div class="arta-header-text">
                <h1><?php _e('Arta Consult RX Dashboard', 'arta-consult-rx'); ?></h1>
                <p class="description"><?php _e('Welcome to your medical consultation dashboard. Manage all your consultations, appointments, and medical programs.', 'arta-consult-rx'); ?></p>
            </div>
        </div>
        <div class="arta-header-actions">
            <div class="arta-status-indicator">
                <span class="arta-status-dot arta-status-active"></span>
                <span class="arta-status-text"><?php _e('System Active', 'arta-consult-rx'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="arta-dashboard-stats">
        <div class="arta-stat-box">
            <div class="arta-stat-icon consultation-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H5.17L4 17.17V4H20V16ZM11 12H13V14H11V12ZM11 6H13V10H11V6Z" fill="#2196f3"/>
                </svg>
            </div>
            <div class="arta-stat-content">
                <h3><?php _e('Consultations', 'arta-consult-rx'); ?></h3>
                <div class="arta-stat-number"><?php echo esc_html($stats['total_consultations']); ?></div>
                <div class="arta-stat-detail">
                    <?php printf(__('%d pending', 'arta-consult-rx'), $stats['pending_consultations']); ?>
                </div>
            </div>
        </div>
        
        <div class="arta-stat-box">
            <div class="arta-stat-icon appointment-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3H18V1H16V3H8V1H6V3H5C3.89 3 3.01 3.9 3.01 5L3 19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V8H19V19ZM7 10H12V15H7V10Z" fill="#ff9800"/>
                </svg>
            </div>
            <div class="arta-stat-content">
                <h3><?php _e('Appointments', 'arta-consult-rx'); ?></h3>
                <div class="arta-stat-number"><?php echo esc_html($stats['total_appointments']); ?></div>
                <div class="arta-stat-detail">
                    <?php printf(__('%d upcoming', 'arta-consult-rx'), $stats['upcoming_appointments']); ?>
                </div>
            </div>
        </div>
        
        <div class="arta-stat-box">
            <div class="arta-stat-icon product-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3H14.82C14.4 1.84 13.3 1 12 1C10.7 1 9.6 1.84 9.18 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM12 3C12.55 3 13 3.45 13 4C13 4.55 12.55 5 12 5C11.45 5 11 4.55 11 4C11 3.45 11.45 3 12 3ZM19 19H5V5H7V8H17V5H19V19Z" fill="#4caf50"/>
                </svg>
            </div>
            <div class="arta-stat-content">
                <h3><?php _e('Product Requests', 'arta-consult-rx'); ?></h3>
                <div class="arta-stat-number"><?php echo esc_html($stats['total_product_requests']); ?></div>
                <div class="arta-stat-detail">
                    <?php printf(__('%d pending', 'arta-consult-rx'), $stats['pending_product_requests']); ?>
                </div>
            </div>
        </div>
        
        <div class="arta-stat-box">
            <div class="arta-stat-icon doctor-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 6C13.1 6 14 6.9 14 8C14 9.1 13.1 10 12 10C10.9 10 10 9.1 10 8C10 6.9 10.9 6 12 6ZM12 13C9.33 13 4 14.34 4 17V20H20V17C20 14.34 14.67 13 12 13ZM18 18H6V17.01C6.2 16.29 9.3 15 12 15C14.7 15 17.8 16.29 18 17V18Z" fill="#9c27b0"/>
                </svg>
            </div>
            <div class="arta-stat-content">
                <h3><?php _e('Doctors', 'arta-consult-rx'); ?></h3>
                <div class="arta-stat-number"><?php echo esc_html($stats['total_doctors']); ?></div>
                <div class="arta-stat-detail">
                    <?php _e('Active doctors', 'arta-consult-rx'); ?>
                </div>
            </div>
        </div>
        
        <div class="arta-stat-box">
            <div class="arta-stat-icon program-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2ZM18 20H6V4H13V9H18V20ZM8 13.01L9.41 14.42L11 12.84V18H13V12.84L14.59 14.42L16 13.01L12.01 9L8 13.01Z" fill="#f44336"/>
                </svg>
            </div>
            <div class="arta-stat-content">
                <h3><?php _e('Programs', 'arta-consult-rx'); ?></h3>
                <div class="arta-stat-number"><?php echo esc_html($stats['total_programs']); ?></div>
                <div class="arta-stat-detail">
                    <?php _e('Available programs', 'arta-consult-rx'); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="arta-dashboard-actions">
        <h2><?php _e('Quick Actions', 'arta-consult-rx'); ?></h2>
        <div class="arta-action-cards">
            <a href="<?php echo admin_url('admin.php?page=arta-consultations'); ?>" class="arta-action-card">
                <div class="arta-action-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H5.17L4 17.17V4H20V16ZM11 12H13V14H11V12ZM11 6H13V10H11V6Z" fill="#2196f3"/>
                    </svg>
                </div>
                <div class="arta-action-text">
                    <h3><?php _e('Manage Consultations', 'arta-consult-rx'); ?></h3>
                    <p><?php _e('View and manage all patient consultations', 'arta-consult-rx'); ?></p>
                </div>
            </a>
            
            <a href="<?php echo admin_url('admin.php?page=arta-appointments'); ?>" class="arta-action-card">
                <div class="arta-action-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 3H18V1H16V3H8V1H6V3H5C3.89 3 3.01 3.9 3.01 5L3 19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V8H19V19ZM7 10H12V15H7V10Z" fill="#ff9800"/>
                    </svg>
                </div>
                <div class="arta-action-text">
                    <h3><?php _e('Manage Appointments', 'arta-consult-rx'); ?></h3>
                    <p><?php _e('View and manage scheduled appointments', 'arta-consult-rx'); ?></p>
                </div>
            </a>
            
            <a href="<?php echo admin_url('admin.php?page=arta-bulk-scheduler'); ?>" class="arta-action-card">
                <div class="arta-action-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 10H18V15H13V10ZM6 10H11V15H6V10ZM19 3H18V1H16V3H8V1H6V3H5C3.89 3 3.01 3.9 3.01 5L3 19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V8H19V19Z" fill="#ff9800"/>
                    </svg>
                </div>
                <div class="arta-action-text">
                    <h3><?php _e('Bulk Scheduler', 'arta-consult-rx'); ?></h3>
                    <p><?php _e('Create or delete multiple appointment slots', 'arta-consult-rx'); ?></p>
                </div>
            </a>
            
            <a href="<?php echo admin_url('post-new.php?post_type=arta_program'); ?>" class="arta-action-card">
                <div class="arta-action-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2ZM18 20H6V4H13V9H18V20ZM8 13.01L9.41 14.42L11 12.84V18H13V12.84L14.59 14.42L16 13.01L12.01 9L8 13.01Z" fill="#f44336"/>
                    </svg>
                </div>
                <div class="arta-action-text">
                    <h3><?php _e('Add New Program', 'arta-consult-rx'); ?></h3>
                    <p><?php _e('Create a new medical program', 'arta-consult-rx'); ?></p>
                </div>
            </a>
            
            <a href="<?php echo admin_url('post-new.php?post_type=arta_doctor'); ?>" class="arta-action-card">
                <div class="arta-action-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 6C13.1 6 14 6.9 14 8C14 9.1 13.1 10 12 10C10.9 10 10 9.1 10 8C10 6.9 10.9 6 12 6ZM12 13C9.33 13 4 14.34 4 17V20H20V17C20 14.34 14.67 13 12 13ZM18 18H6V17.01C6.2 16.29 9.3 15 12 15C14.7 15 17.8 16.29 18 17V18Z" fill="#9c27b0"/>
                    </svg>
                </div>
                <div class="arta-action-text">
                    <h3><?php _e('Add New Doctor', 'arta-consult-rx'); ?></h3>
                    <p><?php _e('Add a new doctor to the system', 'arta-consult-rx'); ?></p>
                </div>
            </a>
        </div>
    </div>
    
    <div class="arta-dashboard-recent">
        <h2><?php _e('Recent Activity', 'arta-consult-rx'); ?></h2>
        <div class="arta-recent-activity">
            <div class="arta-activity-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 2.05V5.08C16.39 5.57 19 8.47 19 12C19 15.87 15.87 19 12 19C8.47 19 5.57 16.39 5.08 13H2.05C2.56 17.95 6.81 22 12 22C17.51 22 22 17.51 22 12C22 6.81 17.95 2.56 13 2.05ZM12 7C9.24 7 7 9.24 7 12C7 14.76 9.24 17 12 17C14.76 17 17 14.76 17 12C17 9.24 14.76 7 12 7ZM12 9C13.66 9 15 10.34 15 12C15 13.66 13.66 15 12 15C10.34 15 9 13.66 9 12C9 10.34 10.34 9 12 9ZM10 12C10 12.55 10.45 13 11 13C11.55 13 12 12.55 12 12C12 11.45 11.55 11 11 11C10.45 11 10 11.45 10 12Z" fill="#9e9e9e"/>
                </svg>
                <p><?php _e('No recent activity to display.', 'arta-consult-rx'); ?></p>
                <p class="arta-activity-hint"><?php _e('Recent consultations, appointments, and product requests will appear here.', 'arta-consult-rx'); ?></p>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Layout */
.arta-dashboard-wrap {
    max-width: 1600px;
    margin: 0 auto;
    padding: 20px 20px;
}

/* Header Styles */
.arta-dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.arta-header-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.arta-header-icon {
    background-color: #e3f2fd;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.arta-header-text h1 {
    margin: 0;
    padding: 0;
    font-size: 24px;
    font-weight: 500;
    color: #333;
}

.arta-header-text .description {
    margin: 5px 0 0;
    color: #666;
    font-size: 14px;
}

.arta-status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    background-color: #e8f5e9;
    padding: 8px 12px;
    border-radius: 20px;
}

.arta-status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.arta-status-active {
    background-color: #4caf50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

.arta-status-text {
    font-size: 12px;
    font-weight: 500;
    color: #2e7d32;
}

/* Stats Boxes */
.arta-dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin: 20px 0 40px;
}

.arta-stat-box {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
}

.arta-stat-box:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.arta-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
}

.consultation-icon {
    background-color: #e3f2fd;
}

.appointment-icon {
    background-color: #fff3e0;
}

.product-icon {
    background-color: #e8f5e9;
}

.doctor-icon {
    background-color: #f3e5f5;
}

.program-icon {
    background-color: #ffebee;
}

.arta-stat-content {
    flex: 1;
}

.arta-stat-content h3 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 14px;
    font-weight: 500;
}

.arta-stat-number {
    font-size: 28px;
    font-weight: 500;
    color: #333;
    margin: 5px 0;
}

.arta-stat-detail {
    color: #666;
    font-size: 12px;
}

/* Quick Action Cards */
.arta-dashboard-actions {
    margin: 40px 0;
}

.arta-dashboard-actions h2,
.arta-dashboard-recent h2 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #e0e0e0;
    color: #333;
    font-size: 18px;
    font-weight: 500;
}

.arta-action-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.arta-action-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    border: 1px solid #e0e0e0;
}

.arta-action-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-3px);
    border-color: #bbdefb;
}

.arta-action-icon {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    background-color: #f5f5f5;
}

.arta-action-text {
    flex: 1;
}

.arta-action-text h3 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 16px;
    font-weight: 500;
}

.arta-action-text p {
    margin: 0;
    color: #666;
    font-size: 13px;
}

/* Recent Activity */
.arta-dashboard-recent {
    margin: 40px 0;
}

.arta-recent-activity {
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: 1px solid #e0e0e0;
}

.arta-activity-empty {
    text-align: center;
    padding: 30px;
}

.arta-activity-empty svg {
    margin-bottom: 15px;
    opacity: 0.5;
}

.arta-activity-empty p {
    margin: 0 0 5px;
    color: #666;
}

.arta-activity-hint {
    font-size: 12px;
    color: #999;
}

/* Responsive Adjustments */
@media (max-width: 782px) {
    .arta-dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .arta-header-content {
        width: 100%;
    }
    
    .arta-stat-box,
    .arta-action-card {
        padding: 15px;
    }
    
    .arta-stat-number {
        font-size: 24px;
    }
    
    .arta-action-icon {
        width: 48px;
        height: 48px;
    }
}
</style>