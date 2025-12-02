<?php
/**
 * Arta Prescription Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle prescription generation
 */
class Arta_Prescription {

    /**
     * Constructor
     */
    public function __construct() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Add fields to product edit page
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_prescription_fields'));
        
        // Save prescription fields
        add_action('woocommerce_process_product_meta', array($this, 'save_prescription_fields'));
        
        // Enqueue admin scripts for media uploader
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Add download button in admin order page
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'add_download_button_admin'));
        
        // Add download button in frontend order page
        add_action('woocommerce_order_details_after_order_table', array($this, 'add_download_button_frontend'));
        
        // Handle PDF download
        add_action('init', array($this, 'handle_pdf_download'));
    }

    /**
     * Enqueue admin scripts for media uploader
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;

        if (($hook == 'post.php' || $hook == 'post-new.php') && $post_type == 'product') {
            wp_enqueue_media();
            wp_enqueue_script(
                'arta-prescription-admin',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/prescription-admin.js',
                array('jquery'),
                ARTA_CONSULT_RX_VERSION,
                true
            );
            
            // Localize script for translations
            wp_localize_script('arta-prescription-admin', 'artaPrescription', array(
                'selectStamp' => __('انتخاب مهر پزشک', 'arta-consult-rx'),
                'useThisImage' => __('استفاده از این تصویر', 'arta-consult-rx'),
                'removeImage' => __('حذف تصویر', 'arta-consult-rx'),
            ));
        }
    }

    /**
     * Add prescription fields to product edit page
     */
    public function add_prescription_fields() {
        global $post;

        // Get current values
        $prescription_template = get_post_meta($post->ID, '_arta_prescription_template', true);
        $doctor_stamp = get_post_meta($post->ID, '_arta_doctor_stamp', true);

        // Get available fields for placeholders
        $available_fields = $this->get_available_fields();

        echo '<div class="options_group">';
        
        // Prescription template textarea
        woocommerce_wp_textarea_input(array(
            'id' => '_arta_prescription_template',
            'label' => __('متن نسخه', 'arta-consult-rx'),
            'placeholder' => __('متن نسخه را وارد کنید...', 'arta-consult-rx'),
            'description' => __('متن نسخه را وارد کنید. می‌توانید از فیلدهای داینامیک استفاده کنید. برای مشاهده فیلدهای موجود، به راهنمای زیر مراجعه کنید.', 'arta-consult-rx'),
            'value' => $prescription_template,
            'rows' => 10,
            'style' => 'width: 100%; min-height: 200px;'
        ));

        // Available fields helper
        echo '<div class="arta-prescription-fields-helper" style="margin-top: 10px; padding: 10px; background: #f5f5f5; border-radius: 4px;">';
        echo '<p style="margin: 0 0 10px 0; font-weight: bold;">' . __('فیلدهای داینامیک موجود:', 'arta-consult-rx') . '</p>';
        echo '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; font-size: 12px;">';
        foreach ($available_fields as $field_key => $field_label) {
            echo '<div style="padding: 5px;">';
            echo '<code style="background: #fff; padding: 2px 5px; border-radius: 3px;">{' . esc_html($field_key) . '}</code>';
            echo ' <span style="color: #666;">' . esc_html($field_label) . '</span>';
            echo '</div>';
        }
        echo '</div>';
        echo '<p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">';
        echo __('برای استفاده از فیلدها، نام فیلد را داخل آکولاد قرار دهید، مثلاً: {billing_first_name}', 'arta-consult-rx');
        echo '</p>';
        echo '</div>';

        // Doctor stamp image upload
        echo '<div class="options_group">';
        echo '<p class="form-field">';
        echo '<label for="_arta_doctor_stamp">' . __('مهر پزشک', 'arta-consult-rx') . '</label>';
        
        // Image preview
        $image_url = '';
        if ($doctor_stamp) {
            $image_url = wp_get_attachment_image_url($doctor_stamp, 'medium');
        }
        
        echo '<div class="arta-stamp-upload-wrapper" style="margin-top: 10px;">';
        if ($image_url) {
            echo '<div class="arta-stamp-preview" style="margin-bottom: 10px;">';
            echo '<img src="' . esc_url($image_url) . '" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px; background: #fff;" />';
            echo '</div>';
        }
        
        echo '<input type="hidden" name="_arta_doctor_stamp" id="_arta_doctor_stamp" value="' . esc_attr($doctor_stamp) . '" />';
        echo '<button type="button" class="button arta-upload-stamp-button" style="margin-right: 5px;">' . __('انتخاب تصویر', 'arta-consult-rx') . '</button>';
        if ($doctor_stamp) {
            echo '<button type="button" class="button arta-remove-stamp-button" style="margin-right: 5px;">' . __('حذف تصویر', 'arta-consult-rx') . '</button>';
        }
        echo '</div>';
        echo '<span class="description">' . __('تصویر مهر پزشک را انتخاب کنید', 'arta-consult-rx') . '</span>';
        echo '</p>';
        echo '</div>';

        echo '</div>';
    }

    /**
     * Get available fields for placeholders
     *
     * @return array
     */
    private function get_available_fields() {
        return array(
            // Default WooCommerce billing fields
            'billing_first_name' => __('نام', 'arta-consult-rx'),
            'billing_last_name' => __('نام خانوادگی', 'arta-consult-rx'),
            'billing_company' => __('نام شرکت', 'arta-consult-rx'),
            'billing_country' => __('کشور', 'arta-consult-rx'),
            'billing_address_1' => __('آدرس', 'arta-consult-rx'),
            'billing_address_2' => __('آدرس (خط دوم)', 'arta-consult-rx'),
            'billing_city' => __('شهر', 'arta-consult-rx'),
            'billing_state' => __('استان', 'arta-consult-rx'),
            'billing_postcode' => __('کد پستی', 'arta-consult-rx'),
            'billing_phone' => __('شماره تماس', 'arta-consult-rx'),
            'billing_email' => __('ایمیل', 'arta-consult-rx'),
            
            // Custom medical fields
            'arta_gender' => __('جنسیت', 'arta-consult-rx'),
            'arta_birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
            'arta_height' => __('قد (سانتی‌متر)', 'arta-consult-rx'),
            'arta_weight' => __('وزن (کیلوگرم)', 'arta-consult-rx'),
            'arta_chronic_diseases' => __('بیماری‌های مزمن', 'arta-consult-rx'),
            'arta_current_medications' => __('داروهای مصرفی فعلی', 'arta-consult-rx'),
            'arta_medical_history' => __('سوابق پزشکی', 'arta-consult-rx'),
            'arta_program_goal' => __('هدف از برنامه', 'arta-consult-rx'),
            'arta_allergies' => __('آلرژی‌ها', 'arta-consult-rx'),
        );
    }

    /**
     * Save prescription fields
     *
     * @param int $post_id
     */
    public function save_prescription_fields($post_id) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save prescription template
        if (isset($_POST['_arta_prescription_template'])) {
            $prescription_template = wp_kses_post($_POST['_arta_prescription_template']);
            update_post_meta($post_id, '_arta_prescription_template', $prescription_template);
        } else {
            delete_post_meta($post_id, '_arta_prescription_template');
        }

        // Save doctor stamp
        if (isset($_POST['_arta_doctor_stamp'])) {
            $doctor_stamp = intval($_POST['_arta_doctor_stamp']);
            if ($doctor_stamp > 0) {
                update_post_meta($post_id, '_arta_doctor_stamp', $doctor_stamp);
            } else {
                delete_post_meta($post_id, '_arta_doctor_stamp');
            }
        }
    }

    /**
     * Generate prescription from order
     *
     * @param int $order_id Order ID
     * @param int $product_id Product ID
     * @return string Generated prescription text
     */
    public function generate_prescription($order_id, $product_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return '';
        }

        // Get prescription template from product
        $template = get_post_meta($product_id, '_arta_prescription_template', true);
        if (empty($template)) {
            return '';
        }

        // Get all available field values from order
        $field_values = $this->get_order_field_values($order);

        // Replace placeholders in template
        $prescription = $template;
        foreach ($field_values as $field_key => $field_value) {
            $placeholder = '{' . $field_key . '}';
            $prescription = str_replace($placeholder, $field_value, $prescription);
        }

        return $prescription;
    }

    /**
     * Get all field values from order
     *
     * @param WC_Order $order
     * @return array
     */
    private function get_order_field_values($order) {
        $values = array();

        // Get billing fields
        $billing_fields = array(
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_country',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_postcode',
            'billing_phone',
            'billing_email',
        );

        // Map billing fields to WooCommerce methods
        $billing_methods = array(
            'billing_first_name' => 'get_billing_first_name',
            'billing_last_name' => 'get_billing_last_name',
            'billing_company' => 'get_billing_company',
            'billing_country' => 'get_billing_country',
            'billing_address_1' => 'get_billing_address_1',
            'billing_address_2' => 'get_billing_address_2',
            'billing_city' => 'get_billing_city',
            'billing_state' => 'get_billing_state',
            'billing_postcode' => 'get_billing_postcode',
            'billing_phone' => 'get_billing_phone',
            'billing_email' => 'get_billing_email',
        );

        foreach ($billing_fields as $field) {
            $value = $order->get_meta($field);
            if (empty($value) && isset($billing_methods[$field])) {
                $method = $billing_methods[$field];
                if (method_exists($order, $method)) {
                    $value = $order->$method();
                }
            }
            $values[$field] = $value ? $value : '';
        }

        // Handle country and state names
        $country_code = $values['billing_country'];
        if (!empty($country_code) && class_exists('WooCommerce')) {
            $countries = WC()->countries->get_countries();
            if (isset($countries[$country_code])) {
                $values['billing_country'] = $countries[$country_code];
            }
        }

        if (!empty($values['billing_state']) && !empty($country_code) && class_exists('WooCommerce')) {
            $states = WC()->countries->get_states($country_code);
            if ($states && isset($states[$values['billing_state']])) {
                $values['billing_state'] = $states[$values['billing_state']];
            }
        }

        // Get custom medical fields
        $custom_fields = array(
            'arta_gender',
            'arta_birth_date',
            'arta_height',
            'arta_weight',
            'arta_chronic_diseases',
            'arta_current_medications',
            'arta_medical_history',
            'arta_program_goal',
            'arta_allergies',
        );

        foreach ($custom_fields as $field) {
            $value = $order->get_meta($field);
            $values[$field] = $value ? $value : '';
        }

        // Handle gender field
        if (!empty($values['arta_gender'])) {
            $gender_options = array(
                'male' => __('مرد', 'arta-consult-rx'),
                'female' => __('زن', 'arta-consult-rx'),
            );
            if (isset($gender_options[$values['arta_gender']])) {
                $values['arta_gender'] = $gender_options[$values['arta_gender']];
            }
        }

        return $values;
    }

    /**
     * Get doctor stamp image URL
     *
     * @param int $product_id Product ID
     * @return string|false Image URL or false if not found
     */
    public function get_doctor_stamp_url($product_id) {
        $stamp_id = get_post_meta($product_id, '_arta_doctor_stamp', true);
        if ($stamp_id) {
            return wp_get_attachment_image_url($stamp_id, 'full');
        }
        return false;
    }

    /**
     * Add download button in admin order page
     *
     * @param WC_Order $order
     */
    public function add_download_button_admin($order) {
        $this->render_download_button($order, true);
    }

    /**
     * Add download button in frontend order page
     *
     * @param WC_Order $order
     */
    public function add_download_button_frontend($order) {
        $this->render_download_button($order, false);
    }

    /**
     * Render download button
     *
     * @param WC_Order $order
     * @param bool $is_admin
     */
    private function render_download_button($order, $is_admin = false) {
        $order_id = $order->get_id();
        $items = $order->get_items();
        
        if (empty($items)) {
            return;
        }

        // Check if any product has prescription template
        $has_prescription = false;
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $template = get_post_meta($product_id, '_arta_prescription_template', true);
            if (!empty($template)) {
                $has_prescription = true;
                break;
            }
        }

        if (!$has_prescription) {
            return;
        }

        $download_url = add_query_arg(array(
            'arta_download_prescription' => $order_id,
            'nonce' => wp_create_nonce('arta_download_prescription_' . $order_id)
        ), home_url('/'));

        $button_class = $is_admin ? 'button' : 'button alt';
        $button_style = $is_admin ? 'margin-top: 10px;' : 'margin-top: 20px;';

        echo '<div class="arta-prescription-download" style="' . esc_attr($button_style) . '">';
        echo '<a href="' . esc_url($download_url) . '" class="' . esc_attr($button_class) . '" target="_blank">';
        echo '<span class="dashicons dashicons-media-document" style="vertical-align: middle; margin-left: 5px;"></span>';
        echo __('دانلود نسخه PDF', 'arta-consult-rx');
        echo '</a>';
        echo '</div>';
    }

    /**
     * Handle PDF download request
     */
    public function handle_pdf_download() {
        if (!isset($_GET['arta_download_prescription']) || !isset($_GET['nonce'])) {
            return;
        }

        $order_id = intval($_GET['arta_download_prescription']);
        $nonce = sanitize_text_field($_GET['nonce']);

        // Verify nonce
        if (!wp_verify_nonce($nonce, 'arta_download_prescription_' . $order_id)) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            wp_die(__('سفارش یافت نشد', 'arta-consult-rx'));
        }

        // Check permissions
        $user_id = get_current_user_id();
        $is_admin = current_user_can('manage_woocommerce');
        $is_customer = $order->get_user_id() == $user_id;

        if (!$is_admin && !$is_customer) {
            wp_die(__('شما مجوز دسترسی به این نسخه را ندارید', 'arta-consult-rx'));
        }

        // Get first product with prescription template
        $items = $order->get_items();
        $product_id = null;
        foreach ($items as $item) {
            $item_product_id = $item->get_product_id();
            $template = get_post_meta($item_product_id, '_arta_prescription_template', true);
            if (!empty($template)) {
                $product_id = $item_product_id;
                break;
            }
        }

        if (!$product_id) {
            wp_die(__('نسخه‌ای برای این محصول یافت نشد', 'arta-consult-rx'));
        }

        // Generate and download PDF
        $this->generate_pdf($order_id, $product_id);
        exit;
    }

    /**
     * Generate PDF file
     *
     * @param int $order_id Order ID
     * @param int $product_id Product ID
     */
    private function generate_pdf($order_id, $product_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        // Generate prescription text
        $prescription_text = $this->generate_prescription($order_id, $product_id);
        if (empty($prescription_text)) {
            wp_die(__('متن نسخه یافت نشد', 'arta-consult-rx'));
        }

        // Get doctor stamp
        $stamp_url = $this->get_doctor_stamp_url($product_id);
        $stamp_path = false;
        if ($stamp_url) {
            $stamp_path = $this->url_to_path($stamp_url);
        }

        // Check if TCPDF is available
        $tcpdf_path = ARTA_CONSULT_RX_ABSPATH . 'includes/libraries/tcpdf/tcpdf.php';
        if (file_exists($tcpdf_path)) {
            require_once $tcpdf_path;
            $this->generate_pdf_with_tcpdf($prescription_text, $stamp_path, $order_id, $product_id);
        } else {
            // Fallback: Use simple HTML to PDF conversion
            $this->generate_pdf_simple($prescription_text, $stamp_url, $order_id, $product_id);
        }
    }

    /**
     * Generate PDF using TCPDF
     *
     * @param string $prescription_text
     * @param string|false $stamp_path
     * @param int $order_id
     * @param int $product_id
     */
    private function generate_pdf_with_tcpdf($prescription_text, $stamp_path, $order_id, $product_id) {
        $order = wc_get_order($order_id);
        $order_date = $order ? $order->get_date_created()->date_i18n(get_option('date_format')) : '';
        $order_time = $order ? $order->get_date_created()->date_i18n(get_option('time_format')) : '';
        
        // Detect text direction (RTL for Persian/Arabic, LTR for others)
        $is_rtl = $this->is_rtl_text($prescription_text);
        
        // Create PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Arta Consult RX');
        $pdf->SetAuthor('Arta Consult RX');
        $pdf->SetTitle(__('نسخه پزشکی', 'arta-consult-rx'));
        $pdf->SetSubject(__('نسخه پزشکی', 'arta-consult-rx'));

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins - professional spacing
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 20);

        // Add a page
        $pdf->AddPage();

        // Draw single border around the page (like real prescription)
        $pdf->SetLineWidth(0.8);
        $pdf->SetDrawColor(50, 50, 50);
        $pdf->Rect(12, 12, 186, 273); // Single border for A4 page with margins

        // Add header section
        $pdf->SetLineWidth(0.3);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(12, 30, 198, 30); // Header separator line
        
        // Header text
        $pdf->SetFont('dejavusans', 'B', 16);
        $pdf->SetTextColor(50, 50, 50);
        $header_text = __('نسخه پزشکی', 'arta-consult-rx');
        if ($is_rtl) {
            $pdf->SetXY(12, 18);
            $pdf->Cell(186, 10, $header_text, 0, 0, 'C');
        } else {
            $pdf->SetXY(12, 18);
            $pdf->Cell(186, 10, __('Medical Prescription', 'arta-consult-rx'), 0, 0, 'C');
        }

        // Set font for Persian text - better size
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        
        // Set text direction
        $pdf->setRTL($is_rtl);

        // Starting position - inside border with proper padding (after header)
        $start_y = 35;
        $pdf->SetY($start_y);
        
        // Prescription text with professional formatting
        $prescription_html = '<div style="text-align: ' . ($is_rtl ? 'right' : 'left') . '; line-height: 2.2; padding: 8px 12px; font-size: 12pt;">';
        $prescription_html .= nl2br(esc_html($prescription_text));
        $prescription_html .= '</div>';

        // Add prescription text
        $pdf->WriteHTML($prescription_html, true, 0, true, 0);

        // Get current Y position
        $current_y = $pdf->GetY();
        
        // Add signature section at bottom (professional positioning)
        $signature_y = 255; // Fixed position near bottom
        
        // Draw line for signature (professional length)
        $pdf->SetLineWidth(0.4);
        $pdf->SetDrawColor(0, 0, 0);
        if ($is_rtl) {
            $pdf->Line(30, $signature_y, 110, $signature_y); // Right side for RTL
        } else {
            $pdf->Line(100, $signature_y, 180, $signature_y); // Left side for LTR
        }
        
        // Add date and time below signature line
        $pdf->SetFont('dejavusans', '', 10);
        if ($is_rtl) {
            $pdf->SetXY(30, $signature_y + 4);
            $pdf->Cell(80, 6, __('تاریخ:', 'arta-consult-rx') . ' ' . $order_date . ' - ' . __('ساعت:', 'arta-consult-rx') . ' ' . $order_time, 0, 0, 'R');
        } else {
            $pdf->SetXY(100, $signature_y + 4);
            $pdf->Cell(80, 6, __('Date:', 'arta-consult-rx') . ' ' . $order_date . ' - ' . __('Time:', 'arta-consult-rx') . ' ' . $order_time, 0, 0, 'L');
        }

        // Add doctor stamp if exists (larger size with graphic design, opposite side of signature)
        if ($stamp_path && file_exists($stamp_path)) {
            $image_info = getimagesize($stamp_path);
            if ($image_info) {
                // Larger stamp size with graphic design
                $max_width = 45; // Max width in mm (larger)
                $max_height = 45; // Max height in mm (larger)
                
                $width = $image_info[0];
                $height = $image_info[1];
                
                // Calculate dimensions to fit
                $ratio = min($max_width / ($width * 0.264583), $max_height / ($height * 0.264583));
                $display_width = $width * 0.264583 * $ratio;
                $display_height = $height * 0.264583 * $ratio;
                
                // Position stamp opposite to signature (left for RTL, right for LTR)
                if ($is_rtl) {
                    $stamp_x = 115; // Left side (opposite to signature)
                } else {
                    $stamp_x = 180 - $display_width; // Right side (opposite to signature)
                }
                
                $stamp_y = $signature_y - 50; // Higher position for larger stamp
                
                // Add stamp image (no border, no background, no shadow)
                $pdf->Image($stamp_path, $stamp_x, $stamp_y, $display_width, $display_height, '', '', '', false, 300, '', false, false, 0);
            }
        }

        // Output PDF
        $filename = sprintf(__('prescription-%d-%d.pdf', 'arta-consult-rx'), $order_id, $product_id);
        $pdf->Output($filename, 'D'); // D = Download
    }
    
    /**
     * Check if text is RTL (Persian/Arabic)
     *
     * @param string $text
     * @return bool
     */
    private function is_rtl_text($text) {
        // Check for Persian/Arabic characters
        $rtl_chars = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
        return preg_match($rtl_chars, $text) > 0;
    }

    /**
     * Generate PDF using simple method (HTML to PDF via browser print)
     *
     * @param string $prescription_text
     * @param string|false $stamp_url
     * @param int $order_id
     * @param int $product_id
     */
    private function generate_pdf_simple($prescription_text, $stamp_url, $order_id, $product_id) {
        $order = wc_get_order($order_id);
        $order_date = $order ? $order->get_date_created()->date_i18n(get_option('date_format')) : '';
        $order_time = $order ? $order->get_date_created()->date_i18n(get_option('time_format')) : '';
        
        // Detect text direction
        $is_rtl = $this->is_rtl_text($prescription_text);
        $dir = $is_rtl ? 'rtl' : 'ltr';
        $text_align = $is_rtl ? 'right' : 'left';
        $lang = $is_rtl ? 'fa' : 'en';
        
        // Create HTML content
        $html = '<!DOCTYPE html>
<html dir="' . $dir . '" lang="' . $lang . '">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . __('نسخه پزشکی', 'arta-consult-rx') . '</title>
    <style>
        @page {
            margin: 12mm;
            size: A4;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, Tahoma, "DejaVu Sans", sans-serif;
            font-size: 12pt;
            line-height: 2.2;
            direction: ' . $dir . ';
            text-align: ' . $text_align . ';
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
        }
        .prescription-header {
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 0;
            margin-bottom: 20px;
            text-align: center;
        }
        .prescription-header h1 {
            font-size: 18pt;
            font-weight: bold;
            color: #323232;
            margin: 0;
            padding: 0;
        }
        .prescription-container {
            border: 0.8px solid #323232;
            border-radius: 0;
            padding: 20px 15px;
            margin: 0;
            width: 100%;
            min-height: 273mm;
            position: relative;
            background: #fff;
            box-sizing: border-box;
        }
        .prescription-text {
            white-space: pre-wrap;
            margin-bottom: 50px;
            padding: 8px 12px;
            text-align: ' . $text_align . ';
            line-height: 2.2;
            font-size: 12pt;
            color: #000;
        }
        .signature-section {
            position: absolute;
            bottom: 35px;
            ' . ($is_rtl ? 'right' : 'left') . ': 30px;
            width: 180px;
        }
        .signature-line {
            border-top: 0.4px solid #000;
            margin-bottom: 6px;
            width: 100%;
        }
        .signature-date {
            font-size: 10pt;
            color: #333;
            margin-top: 4px;
            line-height: 1.5;
        }
        .stamp-container {
            position: absolute;
            bottom: 60px;
            ' . ($is_rtl ? 'left' : 'right') . ': 30px;
            text-align: center;
            padding: 0;
            border: none;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }
        .stamp-inner {
            border: none;
            border-radius: 0;
            padding: 0;
            background: transparent;
        }
        .stamp-image {
            max-width: 45px;
            max-height: 45px;
            width: auto;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .prescription-container {
                border: 0.8px solid #323232;
                margin: 0;
                page-break-after: avoid;
            }
            @page {
                margin: 12mm;
            }
        }
    </style>
</head>
<body>
    <div class="prescription-container">
        <div class="prescription-header">
            <h1>' . ($is_rtl ? __('نسخه پزشکی', 'arta-consult-rx') : __('Medical Prescription', 'arta-consult-rx')) . '</h1>
        </div>
        
        <div class="prescription-text">' . nl2br(esc_html($prescription_text)) . '</div>
        
        <div class="signature-section">
            <div class="signature-line"></div>
            <div class="signature-date">' . __('تاریخ:', 'arta-consult-rx') . ' ' . esc_html($order_date) . '<br>' . __('ساعت:', 'arta-consult-rx') . ' ' . esc_html($order_time) . '</div>
        </div>';
        
        if ($stamp_url) {
            $html .= '<div class="stamp-container">
            <div class="stamp-inner">
                <img src="' . esc_url($stamp_url) . '" alt="' . __('مهر پزشک', 'arta-consult-rx') . '" class="stamp-image" />
            </div>
        </div>';
        }
        
        $html .= '    </div>
</body>
</html>';

        // Output HTML with print dialog
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        echo '<script>window.onload = function() { window.print(); }</script>';
        exit;
    }

    /**
     * Convert URL to file path
     *
     * @param string $url
     * @return string|false
     */
    private function url_to_path($url) {
        $upload_dir = wp_upload_dir();
        $base_url = $upload_dir['baseurl'];
        $base_path = $upload_dir['basedir'];
        
        if (strpos($url, $base_url) === 0) {
            $relative_path = str_replace($base_url, '', $url);
            $file_path = $base_path . $relative_path;
            return $file_path;
        }
        
        // Try to get attachment ID from URL
        $attachment_id = attachment_url_to_postid($url);
        if ($attachment_id) {
            $file_path = get_attached_file($attachment_id);
            return $file_path;
        }
        
        return false;
    }
}

