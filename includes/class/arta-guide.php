<?php
/**
 * Guide Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle guide page functionality
 */
class Arta_Guide {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_guide_menu'), 99); // Priority 99 to ensure it comes after main menu
    }

    /**
     * Add guide submenu
     */
    public function add_guide_menu() {
        add_submenu_page(
            'arta-consult-rx',
            __('راهنمای استفاده', 'arta-consult-rx'),
            __('راهنمای استفاده', 'arta-consult-rx'),
            'manage_options', // Capability required
            'arta-guide',
            array($this, 'render_guide_page')
        );
    }

    /**
     * Render guide page
     */
    public function render_guide_page() {
        ?>
        <div class="wrap arta-guide-page" dir="rtl">
            <h1><?php _e('راهنمای استفاده از سیستم مشاوره پزشکی', 'arta-consult-rx'); ?></h1>
            
            <style>
                .arta-guide-page {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                    max-width: 1200px;
                    margin: 20px auto;
                }
                .arta-guide-card {
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    margin-bottom: 24px;
                    overflow: hidden;
                    transition: box-shadow 0.3s ease;
                }
                .arta-guide-card:hover {
                    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
                }
                .arta-guide-header {
                    background: #f8f9fa;
                    padding: 20px 24px;
                    border-bottom: 1px solid #eee;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }
                .arta-guide-header h2 {
                    margin: 0;
                    font-size: 1.2rem;
                    color: #333;
                    font-weight: 600;
                }
                .arta-guide-icon {
                    font-size: 24px;
                }
                .arta-guide-content {
                    padding: 24px;
                    color: #555;
                    line-height: 1.8;
                }
                .arta-guide-code {
                    background: #f1f3f5;
                    padding: 12px;
                    border-radius: 6px;
                    font-family: monospace;
                    direction: ltr;
                    display: block;
                    margin: 10px 0;
                    border-left: 4px solid #2271b1;
                    color: #2c3338;
                }
                .arta-guide-step {
                    display: flex;
                    gap: 16px;
                    margin-bottom: 20px;
                }
                .arta-step-number {
                    background: #2271b1;
                    color: #fff;
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    flex-shrink: 0;
                }
                .arta-step-text h3 {
                    margin-top: 0;
                    font-size: 1.1rem;
                    color: #333;
                }
                .arta-badge {
                    display: inline-block;
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 12px;
                    font-weight: 500;
                    background: #e3f2fd;
                    color: #0d47a1;
                }
                .arta-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                }
                .arta-note {
                    background-color: #fff8e1;
                    border-right: 4px solid #ffc107;
                    padding: 16px;
                    margin: 16px 0;
                    border-radius: 4px;
                }
                .arta-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                }
                .arta-table th, .arta-table td {
                    text-align: right;
                    padding: 12px;
                    border-bottom: 1px solid #eee;
                }
                .arta-table th {
                    background-color: #f9f9f9;
                    font-weight: 600;
                }
            </style>

            <div class="arta-grid">
                <!-- Introduction -->
                <div class="arta-guide-card" style="grid-column: 1 / -1;">
                    <div class="arta-guide-header">
                        <span class="arta-guide-icon">👋</span>
                        <h2><?php _e('معرفی افزونه', 'arta-consult-rx'); ?></h2>
                    </div>
                    <div class="arta-guide-content">
                        <p><?php _e('به سیستم جامع مشاوره پزشکی و نوبت‌دهی خوش آمدید. این افزونه ابزاری قدرتمند برای مدیریت نوبت‌ها، پزشکان و بیماران است که با رابط کاربری ساده و کاربردی طراحی شده است.', 'arta-consult-rx'); ?></p>
                        <div class="arta-note">
                            <?php _e('این افزونه با ووکامرس کاملاً سازگار است و امکان پرداخت هزینه مشاوره را از طریق درگاه‌های پرداخت ووکامرس فراهم می‌کند.', 'arta-consult-rx'); ?>
                        </div>
                    </div>
                </div>

                <!-- Setup Guide -->
                <div class="arta-guide-card">
                    <div class="arta-guide-header">
                        <span class="arta-guide-icon">⚙️</span>
                        <h2><?php _e('راه‌اندازی اولیه', 'arta-consult-rx'); ?></h2>
                    </div>
                    <div class="arta-guide-content">
                        <div class="arta-guide-step">
                            <div class="arta-step-number">1</div>
                            <div class="arta-step-text">
                                <h3><?php _e('تعریف پزشکان', 'arta-consult-rx'); ?></h3>
                                <p><?php _e('ابتدا از منوی "لیست پزشکان"، پزشکان متخصص خود را تعریف کنید. برای هر پزشک می‌توانید نام کاربری، رمز عبور و تخصص مشخص کنید.', 'arta-consult-rx'); ?></p>
                            </div>
                        </div>
                        <div class="arta-guide-step">
                            <div class="arta-step-number">2</div>
                            <div class="arta-step-text">
                                <h3><?php _e('ایجاد برنامه نوبت‌دهی', 'arta-consult-rx'); ?></h3>
                                <p><?php _e('از منوی "تعریف نوبت"، برای پزشکان در بازه‌های زمانی مشخص (مثلاً یک ماه) نوبت ایجاد کنید. می‌توانید فاصله بین نوبت‌ها را تعیین کنید.', 'arta-consult-rx'); ?></p>
                            </div>
                        </div>
                        <div class="arta-guide-step">
                            <div class="arta-step-number">3</div>
                            <div class="arta-step-text">
                                <h3><?php _e('نمایش در سایت', 'arta-consult-rx'); ?></h3>
                                <p><?php _e('با استفاده از شورتکدها، فرم نوبت‌دهی را در صفحات سایت خود نمایش دهید تا بیماران بتوانند نوبت رزرو کنند.', 'arta-consult-rx'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WooCommerce Integration -->
                <div class="arta-guide-card">
                    <div class="arta-guide-header">
                        <span class="arta-guide-icon">🛒</span>
                        <h2><?php _e('یکپارچگی با ووکامرس', 'arta-consult-rx'); ?></h2>
                    </div>
                    <div class="arta-guide-content">
                        <p><?php _e('این افزونه قابلیت‌های ویژه‌ای به صفحه پرداخت ووکامرس اضافه می‌کند:', 'arta-consult-rx'); ?></p>
                        
                        <h3><?php _e('فیلدهای اضافی پزشکی', 'arta-consult-rx'); ?></h3>
                        <p><?php _e('در صفحه پرداخت (Checkout)، فیلدهای زیر برای دریافت اطلاعات تکمیلی بیمار اضافه شده‌اند:', 'arta-consult-rx'); ?></p>
                        <ul style="list-style-type: disc; padding-right: 20px; margin-bottom: 20px;">
                            <li><strong><?php _e('اطلاعات فردی:', 'arta-consult-rx'); ?></strong> <?php _e('جنسیت، تاریخ تولد، قد، وزن (اجباری)', 'arta-consult-rx'); ?></li>
                            <li><strong><?php _e('سوابق پزشکی:', 'arta-consult-rx'); ?></strong> <?php _e('بیماری‌های مزمن، سوابق پزشکی، آلرژی‌ها (اختیاری)', 'arta-consult-rx'); ?></li>
                            <li><strong><?php _e('دارو و درمان:', 'arta-consult-rx'); ?></strong> <?php _e('داروهای مصرفی فعلی، هدف از برنامه (اختیاری)', 'arta-consult-rx'); ?></li>
                        </ul>
                        <p><small><?php _e('این اطلاعات در جزئیات سفارش، ایمیل‌های ارسالی و پروفایل کاربری ذخیره و نمایش داده می‌شوند.', 'arta-consult-rx'); ?></small></p>
                    </div>
                </div>

                <!-- Shortcodes -->
                <div class="arta-guide-card" style="grid-column: 1 / -1;">
                    <div class="arta-guide-header">
                        <span class="arta-guide-icon">🔌</span>
                        <h2><?php _e('شورتکدها (Shortcodes)', 'arta-consult-rx'); ?></h2>
                    </div>
                    <div class="arta-guide-content">
                        <p><?php _e('برای نمایش فرم‌های مختلف در سایت از کدهای کوتاه زیر استفاده کنید:', 'arta-consult-rx'); ?></p>
                        
                        <div style="margin-bottom: 30px;">
                            <strong><?php _e('1. دکمه خرید مستقیم (اتصال به ووکامرس)', 'arta-consult-rx'); ?></strong>
                            <code class="arta-guide-code">[arta_buy_button product_id="456" text="خرید برنامه"]</code>
                            <p><?php _e('این دکمه سبد خرید کاربر را خالی کرده و محصول انتخابی را به آن اضافه می‌کند و کاربر را مستقیماً به صفحه پرداخت هدایت می‌کند.', 'arta-consult-rx'); ?></p>
                            <table class="arta-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('پارامتر', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('توضیحات', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('مثال', 'arta-consult-rx'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>product_id</code></td>
                                        <td><?php _e('شناسه محصول ووکامرس (اجباری اگر program_id نباشد)', 'arta-consult-rx'); ?></td>
                                        <td><code>product_id="123"</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>program_id</code></td>
                                        <td><?php _e('شناسه پست برنامه (محصول مرتبط را پیدا می‌کند)', 'arta-consult-rx'); ?></td>
                                        <td><code>program_id="45"</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>text</code></td>
                                        <td><?php _e('متن روی دکمه', 'arta-consult-rx'); ?></td>
                                        <td><code>text="ثبت نام در دوره"</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <strong><?php _e('2. دکمه مشاوره واتساپ (مخصوص صفحه پرداخت)', 'arta-consult-rx'); ?></strong>
                            <code class="arta-guide-code">[arta_whatsapp_consultation_button phone="989123456789"]</code>
                            <p><?php _e('این دکمه معمولاً در صفحه پرداخت نمایش داده می‌شود تا کاربر بتواند قبل از خرید مشاوره بگیرد.', 'arta-consult-rx'); ?></p>
                            <table class="arta-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('پارامتر', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('توضیحات', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('پیش‌فرض', 'arta-consult-rx'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>phone</code></td>
                                        <td><?php _e('شماره موبایل گیرنده پیام (با کد کشور)', 'arta-consult-rx'); ?></td>
                                        <td><code>989045605166</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>text</code></td>
                                        <td><?php _e('متن پیش‌فرض پیام', 'arta-consult-rx'); ?></td>
                                        <td><?php _e('درخواست مشاوره دارم', 'arta-consult-rx'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><code>button_text</code></td>
                                        <td><?php _e('متن روی دکمه', 'arta-consult-rx'); ?></td>
                                        <td><?php _e('شروع مشاوره', 'arta-consult-rx'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <strong><?php _e('3. فرم رزرو نوبت', 'arta-consult-rx'); ?></strong>
                            <code class="arta-guide-code">[arta_appointment_form doctor_id="123"]</code>
                            <p><?php _e('نمایش تقویم و فرم رزرو نوبت برای پزشک خاص.', 'arta-consult-rx'); ?></p>
                        </div>

                        <div>
                            <strong><?php _e('4. لیست نوبت‌های من', 'arta-consult-rx'); ?></strong>
                            <code class="arta-guide-code">[arta_my_appointments]</code>
                            <p><?php _e('نمایش نوبت‌های رزرو شده کاربر جاری در پنل کاربری.', 'arta-consult-rx'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Troubleshooting -->
                <div class="arta-guide-card" style="grid-column: 1 / -1;">
                    <div class="arta-guide-header">
                        <span class="arta-guide-icon">❓</span>
                        <h2><?php _e('سوالات متداول و رفع اشکال', 'arta-consult-rx'); ?></h2>
                    </div>
                    <div class="arta-guide-content">
                        <div style="margin-bottom: 16px;">
                            <strong><?php _e('چرا دکمه خرید کار نمی‌کند؟', 'arta-consult-rx'); ?></strong>
                            <p><?php _e('مطمئن شوید که ووکامرس نصب و فعال است و شناسه محصول (product_id) وارد شده صحیح است و محصول قابلیت خرید دارد.', 'arta-consult-rx'); ?></p>
                        </div>
                        
                        <div style="margin-bottom: 16px;">
                            <strong><?php _e('چرا فیلدهای پزشکی در صفحه پرداخت دیده نمی‌شوند؟', 'arta-consult-rx'); ?></strong>
                            <p><?php _e('این فیلدها به صورت خودکار توسط افزونه به صفحه Checkout ووکامرس اضافه می‌شوند. اگر از قالب‌های غیرستاندارد یا افزونه‌های ویرایشگر صفحه پرداخت استفاده می‌کنید، ممکن است تداخلی وجود داشته باشد.', 'arta-consult-rx'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 40px; color: #666;">
                <p><?php printf(__('نسخه افزونه: %s', 'arta-consult-rx'), ARTA_CONSULT_RX_VERSION); ?> | <?php _e('طراحی و توسعه توسط تیم آرتا', 'arta-consult-rx'); ?></p>
            </div>
        </div>
        <?php
    }
}
