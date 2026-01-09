<?php
/**
 * Plugin Name: WhatsApp Floating Button
 * Plugin URI: https://example.com
 * Description: A floating WhatsApp button that displays a popup with three customizable WhatsApp numbers when clicked
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: whatsapp-floating-button
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WFB_VERSION', '1.0.0');
define('WFB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WFB_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class WhatsApp_Floating_Button {

    /**
     * Constructor
     */
    public function __construct() {
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));

        // Frontend hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('wp_footer', array($this, 'render_whatsapp_button'));
    }

    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_menu_page(
            __('WhatsApp Button', 'whatsapp-floating-button'),
            __('WhatsApp Button', 'whatsapp-floating-button'),
            'manage_options',
            'whatsapp-floating-button',
            array($this, 'render_admin_page'),
            'dashicons-whatsapp',
            100
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('wfb_settings_group', 'wfb_settings');

        add_settings_section(
            'wfb_main_section',
            __('WhatsApp Numbers Configuration', 'whatsapp-floating-button'),
            array($this, 'section_callback'),
            'whatsapp-floating-button'
        );

        // Dynamic WhatsApp Numbers
        add_settings_field(
            'wfb_whatsapp_numbers',
            __('WhatsApp Numbers', 'whatsapp-floating-button'),
            array($this, 'whatsapp_numbers_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Button Position
        add_settings_field(
            'wfb_button_position',
            __('Button Position', 'whatsapp-floating-button'),
            array($this, 'position_field_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Button Color
        add_settings_field(
            'wfb_button_color',
            __('Button Color', 'whatsapp-floating-button'),
            array($this, 'color_field_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Enable Ringing Animation
        add_settings_field(
            'wfb_enable_ringing',
            __('Enable Ringing Animation', 'whatsapp-floating-button'),
            array($this, 'enable_ringing_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Enable Direct Link (NEW)
        add_settings_field(
            'wfb_enable_direct_link',
            __('Enable Direct Link', 'whatsapp-floating-button'),
            array($this, 'enable_direct_link_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Direct Link Phone Number (NEW)
        add_settings_field(
            'wfb_direct_link_number',
            __('Direct Link Phone Number', 'whatsapp-floating-button'),
            array($this, 'direct_link_number_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Popup Title
        add_settings_field(
            'wfb_popup_title',
            __('Popup Title', 'whatsapp-floating-button'),
            array($this, 'popup_title_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Popup Subtitle
        add_settings_field(
            'wfb_popup_subtitle',
            __('Popup Subtitle', 'whatsapp-floating-button'),
            array($this, 'popup_subtitle_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // Enable FAQ
        add_settings_field(
            'wfb_enable_faq',
            __('Enable FAQ Section', 'whatsapp-floating-button'),
            array($this, 'enable_faq_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // FAQ Heading
        add_settings_field(
            'wfb_faq_heading',
            __('FAQ Section Heading', 'whatsapp-floating-button'),
            array($this, 'faq_heading_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );

        // FAQ Items
        add_settings_field(
            'wfb_faq_items',
            __('FAQ Items', 'whatsapp-floating-button'),
            array($this, 'faq_items_callback'),
            'whatsapp-floating-button',
            'wfb_main_section'
        );
    }

    /**
     * Section callback
     */
    public function section_callback() {
        echo '<p>' . __('Configure your WhatsApp numbers and button settings below. Enter phone numbers in international format (e.g., 1234567890).', 'whatsapp-floating-button') . '</p>';
    }

    /**
     * Dynamic WhatsApp Numbers callback
     */
    public function whatsapp_numbers_callback() {
        $options = get_option('wfb_settings');
        $whatsapp_numbers = isset($options['whatsapp_numbers']) ? $options['whatsapp_numbers'] : array();

        // Default numbers if none exist
        if (empty($whatsapp_numbers)) {
            $whatsapp_numbers = array(
                array('number' => '', 'label' => '')
            );
        }
        ?>
        <div id="wfb-whatsapp-numbers-container">
            <?php foreach ($whatsapp_numbers as $index => $contact): ?>
                <div class="wfb-whatsapp-number-item" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <h4 style="margin-top: 0;"><?php printf(__('WhatsApp Contact #%d', 'whatsapp-floating-button'), $index + 1); ?></h4>

                    <p><strong><?php _e('Phone Number:', 'whatsapp-floating-button'); ?></strong></p>
                    <input
                        type="text"
                        name="wfb_settings[whatsapp_numbers][<?php echo $index; ?>][number]"
                        value="<?php echo esc_attr($contact['number']); ?>"
                        class="regular-text"
                        placeholder="<?php esc_attr_e('e.g., 1234567890', 'whatsapp-floating-button'); ?>"
                    />
                    <p class="description"><?php _e('Enter phone number in international format without + or spaces', 'whatsapp-floating-button'); ?></p>

                    <p><strong><?php _e('Label:', 'whatsapp-floating-button'); ?></strong></p>
                    <input
                        type="text"
                        name="wfb_settings[whatsapp_numbers][<?php echo $index; ?>][label]"
                        value="<?php echo esc_attr($contact['label']); ?>"
                        class="regular-text"
                        placeholder="<?php esc_attr_e('e.g., Sales Support', 'whatsapp-floating-button'); ?>"
                    />
                    <p class="description"><?php _e('Descriptive label for this contact', 'whatsapp-floating-button'); ?></p>

                    <?php if ($index > 0): ?>
                        <button type="button" class="button wfb-remove-number" style="margin-top: 10px; color: #dc3232;">
                            <?php _e('Remove Contact', 'whatsapp-floating-button'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" id="wfb-add-number" class="button button-secondary" style="margin-top: 10px;">
            <?php _e('+ Add Another WhatsApp Number', 'whatsapp-floating-button'); ?>
        </button>

        <script>
        jQuery(document).ready(function($) {
            var numberIndex = <?php echo count($whatsapp_numbers); ?>;

            // Add new WhatsApp number
            $('#wfb-add-number').on('click', function() {
                var numberHtml = '<div class="wfb-whatsapp-number-item" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">' +
                    '<h4 style="margin-top: 0;"><?php _e('WhatsApp Contact #', 'whatsapp-floating-button'); ?>' + (numberIndex + 1) + '</h4>' +
                    '<p><strong><?php _e('Phone Number:', 'whatsapp-floating-button'); ?></strong></p>' +
                    '<input type="text" name="wfb_settings[whatsapp_numbers][' + numberIndex + '][number]" class="regular-text" placeholder="<?php esc_attr_e('e.g., 1234567890', 'whatsapp-floating-button'); ?>" />' +
                    '<p class="description"><?php _e('Enter phone number in international format without + or spaces', 'whatsapp-floating-button'); ?></p>' +
                    '<p><strong><?php _e('Label:', 'whatsapp-floating-button'); ?></strong></p>' +
                    '<input type="text" name="wfb_settings[whatsapp_numbers][' + numberIndex + '][label]" class="regular-text" placeholder="<?php esc_attr_e('e.g., Sales Support', 'whatsapp-floating-button'); ?>" />' +
                    '<p class="description"><?php _e('Descriptive label for this contact', 'whatsapp-floating-button'); ?></p>' +
                    '<button type="button" class="button wfb-remove-number" style="margin-top: 10px; color: #dc3232;"><?php _e('Remove Contact', 'whatsapp-floating-button'); ?></button>' +
                    '</div>';

                $('#wfb-whatsapp-numbers-container').append(numberHtml);
                numberIndex++;
            });

            // Remove WhatsApp number
            $(document).on('click', '.wfb-remove-number', function() {
                $(this).closest('.wfb-whatsapp-number-item').remove();
            });
        });
        </script>
        <?php
    }

    /**
     * Position field callback
     */
    public function position_field_callback() {
        $options = get_option('wfb_settings');
        $position = isset($options['position']) ? $options['position'] : 'bottom-right';
        ?>
        <select name="wfb_settings[position]">
            <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>><?php _e('Bottom Right', 'whatsapp-floating-button'); ?></option>
            <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>><?php _e('Bottom Left', 'whatsapp-floating-button'); ?></option>
        </select>
        <?php
    }

    /**
     * Color field callback
     */
    public function color_field_callback() {
        $options = get_option('wfb_settings');
        $color = isset($options['color']) ? $options['color'] : '#25D366';
        ?>
        <input
            type="color"
            name="wfb_settings[color]"
            value="<?php echo esc_attr($color); ?>"
        />
        <p class="description"><?php _e('Choose button background color (default: WhatsApp green)', 'whatsapp-floating-button'); ?></p>
        <?php
    }

    /**
     * Enable Ringing Animation callback
     */
    public function enable_ringing_callback() {
        $options = get_option('wfb_settings');
        $enabled = isset($options['enable_ringing']) ? $options['enable_ringing'] : '0';
        ?>
        <label>
            <input
                type="checkbox"
                name="wfb_settings[enable_ringing]"
                value="1"
                <?php checked($enabled, '1'); ?>
            />
            <?php _e('Enable attention-grabbing animation (zoom, shake, and ripple effects)', 'whatsapp-floating-button'); ?>
        </label>
        <p class="description"><?php _e('The button will zoom in/out, shake, and display rippling circles around it every 2.5 seconds to attract visitor attention', 'whatsapp-floating-button'); ?></p>
        <?php
    }

    /**
     * Enable Direct Link callback
     */
    public function enable_direct_link_callback() {
        $options = get_option('wfb_settings');
        $enabled = isset($options['enable_direct_link']) ? $options['enable_direct_link'] : '0';
        ?>
        <label>
            <input
                type="checkbox"
                name="wfb_settings[enable_direct_link]"
                value="1"
                <?php checked($enabled, '1'); ?>
                id="wfb_enable_direct_link"
            />
            <?php _e('Enable direct WhatsApp link (skip popup/modal)', 'whatsapp-floating-button'); ?>
        </label>
        <p class="description"><?php _e('When enabled, clicking the button will directly open WhatsApp with the number specified below, skipping the popup modal.', 'whatsapp-floating-button'); ?></p>
        <?php
    }

    /**
     * Direct Link Phone Number callback
     */
    public function direct_link_number_callback() {
        $options = get_option('wfb_settings');
        $number = isset($options['direct_link_number']) ? $options['direct_link_number'] : '';
        ?>
        <input
            type="text"
            name="wfb_settings[direct_link_number]"
            value="<?php echo esc_attr($number); ?>"
            class="regular-text"
            placeholder="<?php esc_attr_e('e.g., 1234567890', 'whatsapp-floating-button'); ?>"
            id="wfb_direct_link_number"
        />
        <p class="description"><?php _e('Enter phone number in international format without + or spaces (e.g., 8801712345678). This number will be used when Direct Link is enabled.', 'whatsapp-floating-button'); ?></p>
        <script>
        jQuery(document).ready(function($) {
            // Show/hide direct link number field based on checkbox
            function toggleDirectLinkField() {
                if ($('#wfb_enable_direct_link').is(':checked')) {
                    $('#wfb_direct_link_number').closest('tr').show();
                } else {
                    $('#wfb_direct_link_number').closest('tr').hide();
                }
            }

            // Initial state
            toggleDirectLinkField();

            // On checkbox change
            $('#wfb_enable_direct_link').on('change', function() {
                toggleDirectLinkField();
            });
        });
        </script>
        <?php
    }

    /**
     * Popup Title callback
     */
    public function popup_title_callback() {
        $options = get_option('wfb_settings');
        $title = isset($options['popup_title']) ? $options['popup_title'] : __('Contact Us on WhatsApp', 'whatsapp-floating-button');
        ?>
        <input
            type="text"
            name="wfb_settings[popup_title]"
            value="<?php echo esc_attr($title); ?>"
            class="regular-text"
            placeholder="<?php esc_attr_e('Contact Us on WhatsApp', 'whatsapp-floating-button'); ?>"
        />
        <p class="description"><?php _e('Main heading text displayed at the top of the popup', 'whatsapp-floating-button'); ?></p>
        <?php
    }

    /**
     * Popup Subtitle callback
     */
    public function popup_subtitle_callback() {
        $options = get_option('wfb_settings');
        $subtitle = isset($options['popup_subtitle']) ? $options['popup_subtitle'] : __('Choose a contact below:', 'whatsapp-floating-button');
        ?>
        <input
            type="text"
            name="wfb_settings[popup_subtitle]"
            value="<?php echo esc_attr($subtitle); ?>"
            class="regular-text"
            placeholder="<?php esc_attr_e('Choose a contact below:', 'whatsapp-floating-button'); ?>"
        />
        <p class="description"><?php _e('Subtitle text displayed below the main heading', 'whatsapp-floating-button'); ?></p>
        <?php
    }

    /**
     * Enable FAQ callback
     */
    public function enable_faq_callback() {
        $options = get_option('wfb_settings');
        $enabled = isset($options['enable_faq']) ? $options['enable_faq'] : '0';
        ?>
        <label>
            <input
                type="checkbox"
                name="wfb_settings[enable_faq]"
                value="1"
                <?php checked($enabled, '1'); ?>
            />
            <?php _e('Show FAQ section in the popup', 'whatsapp-floating-button'); ?>
        </label>
        <?php
    }

    /**
     * FAQ Heading callback
     */
    public function faq_heading_callback() {
        $options = get_option('wfb_settings');
        $heading = isset($options['faq_heading']) ? $options['faq_heading'] : __('Frequently Asked Questions', 'whatsapp-floating-button');
        ?>
        <input
            type="text"
            name="wfb_settings[faq_heading]"
            value="<?php echo esc_attr($heading); ?>"
            class="regular-text"
            placeholder="<?php esc_attr_e('Frequently Asked Questions', 'whatsapp-floating-button'); ?>"
        />
        <p class="description"><?php _e('Heading text for the FAQ section in the popup', 'whatsapp-floating-button'); ?></p>
        <?php
    }

    /**
     * FAQ items callback
     */
    public function faq_items_callback() {
        $options = get_option('wfb_settings');
        $faq_items = isset($options['faq_items']) ? $options['faq_items'] : array();

        // Default FAQs if none exist
        if (empty($faq_items)) {
            $faq_items = array(
                array(
                    'question' => 'কিভাবে একাউন্ট খুলব?',
                    'answer' => 'নাইন উইকেটসে গ্রাহক নিজে কোন অ্যাকাউন্ট খুলতে পারেনা, এই কারণে আমাদের নিজস্ব মাস্টার এজেন্ট এর ব্যবস্থা আছে। যারা আপনাদেরকে অ্যাকাউন্ট খুলে দিবে এবং আপনারা তাদের মাধ্যমে লেনদেন করতে পারবেন। অ্যাকাউন্ট খোলার জন্য আমাদের যেকোনো অনলাইন মাস্টার এজেন্ট এর সাথে হোয়াটসঅ্যাপে অথবা মেসেঞ্জারে যোগাযোগ করুন।'
                ),
                array(
                    'question' => 'কিভাবে ডিপোজিট করব?',
                    'answer' => 'বিকাশ, নগদ, রকেট বা ব্যাংক ট্রান্সফারের মাধ্যমে আপনার মাস্টার এজেন্টকে টাকা পাঠান। তারপর তারা আপনার অ্যাকাউন্টে ব্যালেন্স যুক্ত করে দিবেন।'
                )
            );
        }
        ?>
        <div id="wfb-faq-container">
            <?php foreach ($faq_items as $index => $item): ?>
                <div class="wfb-faq-item" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <h4 style="margin-top: 0;"><?php printf(__('FAQ #%d', 'whatsapp-floating-button'), $index + 1); ?></h4>

                    <p><strong><?php _e('Question:', 'whatsapp-floating-button'); ?></strong></p>
                    <textarea
                        name="wfb_settings[faq_items][<?php echo $index; ?>][question]"
                        rows="2"
                        class="large-text"
                        placeholder="<?php esc_attr_e('Enter question', 'whatsapp-floating-button'); ?>"
                    ><?php echo esc_textarea($item['question']); ?></textarea>

                    <p><strong><?php _e('Answer:', 'whatsapp-floating-button'); ?></strong></p>
                    <textarea
                        name="wfb_settings[faq_items][<?php echo $index; ?>][answer]"
                        rows="4"
                        class="large-text"
                        placeholder="<?php esc_attr_e('Enter answer', 'whatsapp-floating-button'); ?>"
                    ><?php echo esc_textarea($item['answer']); ?></textarea>

                    <?php if ($index > 0): ?>
                        <button type="button" class="button wfb-remove-faq" style="margin-top: 10px; color: #dc3232;">
                            <?php _e('Remove FAQ', 'whatsapp-floating-button'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" id="wfb-add-faq" class="button button-secondary" style="margin-top: 10px;">
            <?php _e('+ Add Another FAQ', 'whatsapp-floating-button'); ?>
        </button>

        <script>
        jQuery(document).ready(function($) {
            var faqIndex = <?php echo count($faq_items); ?>;

            // Add new FAQ
            $('#wfb-add-faq').on('click', function() {
                var faqHtml = '<div class="wfb-faq-item" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">' +
                    '<h4 style="margin-top: 0;"><?php _e('FAQ #', 'whatsapp-floating-button'); ?>' + (faqIndex + 1) + '</h4>' +
                    '<p><strong><?php _e('Question:', 'whatsapp-floating-button'); ?></strong></p>' +
                    '<textarea name="wfb_settings[faq_items][' + faqIndex + '][question]" rows="2" class="large-text" placeholder="<?php esc_attr_e('Enter question', 'whatsapp-floating-button'); ?>"></textarea>' +
                    '<p><strong><?php _e('Answer:', 'whatsapp-floating-button'); ?></strong></p>' +
                    '<textarea name="wfb_settings[faq_items][' + faqIndex + '][answer]" rows="4" class="large-text" placeholder="<?php esc_attr_e('Enter answer', 'whatsapp-floating-button'); ?>"></textarea>' +
                    '<button type="button" class="button wfb-remove-faq" style="margin-top: 10px; color: #dc3232;"><?php _e('Remove FAQ', 'whatsapp-floating-button'); ?></button>' +
                    '</div>';

                $('#wfb-faq-container').append(faqHtml);
                faqIndex++;
            });

            // Remove FAQ
            $(document).on('click', '.wfb-remove-faq', function() {
                $(this).closest('.wfb-faq-item').remove();
            });
        });
        </script>
        <?php
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <form action="options.php" method="post">
                <?php
                settings_fields('wfb_settings_group');
                do_settings_sections('whatsapp-floating-button');
                submit_button(__('Save Settings', 'whatsapp-floating-button'));
                ?>
            </form>

            <div class="wfb-admin-preview" style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 8px;">
                <h2><?php _e('Preview', 'whatsapp-floating-button'); ?></h2>
                <p><?php _e('The floating WhatsApp button will appear on your website frontend after saving your settings.', 'whatsapp-floating-button'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'wfb-frontend-style',
            WFB_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            WFB_VERSION
        );

        // Enqueue JavaScript
        wp_enqueue_script(
            'wfb-frontend-script',
            WFB_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            WFB_VERSION,
            true
        );

        // Localize script with settings
        $options = get_option('wfb_settings');
        wp_localize_script('wfb-frontend-script', 'wfbSettings', array(
            'position' => isset($options['position']) ? $options['position'] : 'bottom-right',
            'color' => isset($options['color']) ? $options['color'] : '#25D366'
        ));
    }

    /**
     * Render WhatsApp button on frontend
     */
    public function render_whatsapp_button() {
        $options = get_option('wfb_settings');

        // Check if direct link mode is enabled
        $enable_direct_link = isset($options['enable_direct_link']) && $options['enable_direct_link'] == '1';
        $direct_link_number = isset($options['direct_link_number']) ? $options['direct_link_number'] : '';

        // If direct link is enabled but no number is set, don't show button
        if ($enable_direct_link && empty($direct_link_number)) {
            return;
        }

        // Get dynamic WhatsApp numbers (with fallback to old format for backward compatibility)
        $numbers = array();

        if (isset($options['whatsapp_numbers']) && !empty($options['whatsapp_numbers'])) {
            // New dynamic format
            foreach ($options['whatsapp_numbers'] as $index => $contact) {
                if (!empty($contact['number'])) {
                    $numbers[] = array(
                        'number' => $contact['number'],
                        'label' => !empty($contact['label']) ? $contact['label'] : sprintf(__('Contact %d', 'whatsapp-floating-button'), $index + 1)
                    );
                }
            }
        } else {
            // Fallback to old format for backward compatibility
            for ($i = 1; $i <= 3; $i++) {
                if (!empty($options['number_' . $i])) {
                    $numbers[] = array(
                        'number' => $options['number_' . $i],
                        'label' => !empty($options['label_' . $i]) ? $options['label_' . $i] : sprintf(__('Contact %d', 'whatsapp-floating-button'), $i)
                    );
                }
            }
        }

        // If not in direct link mode and no numbers configured, don't show button
        if (!$enable_direct_link && empty($numbers)) {
            return;
        }

        $position = isset($options['position']) ? $options['position'] : 'bottom-right';
        $color = isset($options['color']) ? $options['color'] : '#25D366';
        $enable_ringing = isset($options['enable_ringing']) && $options['enable_ringing'] == '1';

        // Get dynamic text values with defaults
        $popup_title = isset($options['popup_title']) && !empty($options['popup_title'])
            ? $options['popup_title']
            : __('Contact Us on WhatsApp', 'whatsapp-floating-button');

        $popup_subtitle = isset($options['popup_subtitle']) && !empty($options['popup_subtitle'])
            ? $options['popup_subtitle']
            : __('Choose a contact below:', 'whatsapp-floating-button');

        $faq_heading = isset($options['faq_heading']) && !empty($options['faq_heading'])
            ? $options['faq_heading']
            : __('Frequently Asked Questions', 'whatsapp-floating-button');
        ?>

        <!-- WhatsApp Floating Button -->
        <div class="wfb-container wfb-position-<?php echo esc_attr($position); ?>" style="--wfb-color: <?php echo esc_attr($color); ?>">
            <?php if (!$enable_direct_link): ?>
            <!-- Popup Box -->
            <div class="wfb-popup" id="wfb-popup">
                <div class="wfb-popup-header">
                    <h3><?php echo esc_html($popup_title); ?></h3>
                    <button class="wfb-close" id="wfb-close" aria-label="<?php esc_attr_e('Close', 'whatsapp-floating-button'); ?>">
                        &times;
                    </button>
                </div>
                <div class="wfb-popup-body">
                    <p class="wfb-popup-subtitle"><?php echo esc_html($popup_subtitle); ?></p>
                    <ul class="wfb-contact-list">
                        <?php foreach ($numbers as $contact): ?>
                            <li>
                                <a href="https://wa.me/<?php echo esc_attr($contact['number']); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="wfb-contact-item">
                                    <svg class="wfb-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span><?php echo esc_html($contact['label']); ?></span>
                                    <svg class="wfb-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 18l6-6-6-6"/>
                                    </svg>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php
                    // Display FAQ section if enabled
                    $enable_faq = isset($options['enable_faq']) && $options['enable_faq'] == '1';
                    $faq_items = isset($options['faq_items']) ? $options['faq_items'] : array();

                    if ($enable_faq && !empty($faq_items)):
                    ?>
                        <div class="wfb-faq-section">
                            <div class="wfb-faq-header">
                                <svg class="wfb-faq-header-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                    <line x1="9" y1="10" x2="15" y2="10"/>
                                    <line x1="12" y1="7" x2="12" y2="13"/>
                                </svg>
                                <h4 class="wfb-faq-title"><?php echo esc_html($faq_heading); ?></h4>
                            </div>
                            <div class="wfb-faq-grid">
                                <?php foreach ($faq_items as $index => $faq):
                                    if (empty($faq['question']) || empty($faq['answer'])) continue;
                                ?>
                                    <div class="wfb-faq-card">
                                        <button class="wfb-faq-question" type="button" aria-expanded="false">
                                            <div class="wfb-faq-question-content">
                                                <svg class="wfb-faq-q-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                                </svg>
                                                <span class="wfb-faq-question-text"><?php echo esc_html($faq['question']); ?></span>
                                            </div>
                                            <svg class="wfb-faq-toggle-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div class="wfb-faq-answer">
                                            <div class="wfb-faq-answer-content">
                                                <p><?php echo nl2br(esc_html($faq['answer'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Floating Button -->
            <?php if ($enable_direct_link): ?>
                <!-- Direct Link Button -->
                <a href="https://wa.me/<?php echo esc_attr($direct_link_number); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="wfb-button<?php echo $enable_ringing ? ' wfb-ringing' : ''; ?>"
                   aria-label="<?php esc_attr_e('Open WhatsApp', 'whatsapp-floating-button'); ?>">
                    <svg class="wfb-button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
            <?php else: ?>
                <!-- Popup Trigger Button -->
                <button class="wfb-button<?php echo $enable_ringing ? ' wfb-ringing' : ''; ?>" id="wfb-button" aria-label="<?php esc_attr_e('Open WhatsApp', 'whatsapp-floating-button'); ?>">
                    <svg class="wfb-button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </button>
            <?php endif; ?>
        </div>

        <?php
    }
}

// Initialize plugin
function wfb_init() {
    new WhatsApp_Floating_Button();
}
add_action('plugins_loaded', 'wfb_init');
