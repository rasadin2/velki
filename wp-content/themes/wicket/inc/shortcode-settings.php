<?php
/**
 * Wicket Theme - Shortcode Settings Page
 *
 * @package wicket
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Shortcode Settings menu to WordPress admin
 */
function wicket_shortcode_settings_menu() {
    add_theme_page(
        'Shortcode Documentation',
        'Shortcode Docs',
        'manage_options',
        'wicket-shortcode-docs',
        'wicket_shortcode_settings_page'
    );
}
add_action( 'admin_menu', 'wicket_shortcode_settings_menu' );

/**
 * Display Shortcode Settings Page
 */
function wicket_shortcode_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <p>Below is a comprehensive list of all shortcodes available in the Wicket theme with their descriptions and usage examples.</p>

        <style>
            .shortcode-section {
                background: #fff;
                border: 1px solid #ccd0d4;
                padding: 20px;
                margin: 20px 0;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            .shortcode-section h2 {
                margin-top: 0;
                color: #1d2327;
                border-bottom: 2px solid #2271b1;
                padding-bottom: 10px;
            }
            .shortcode-code {
                background: #f6f7f7;
                border-left: 4px solid #2271b1;
                padding: 15px;
                margin: 15px 0;
                font-family: 'Courier New', monospace;
                font-size: 14px;
            }
            .shortcode-attributes {
                background: #fff9e5;
                border-left: 4px solid #dba617;
                padding: 15px;
                margin: 10px 0;
            }
            .shortcode-example {
                background: #e7f5e7;
                border-left: 4px solid #46b450;
                padding: 15px;
                margin: 10px 0;
            }
            .attribute-table {
                width: 100%;
                border-collapse: collapse;
                margin: 10px 0;
            }
            .attribute-table th,
            .attribute-table td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }
            .attribute-table th {
                background: #f0f0f1;
                font-weight: 600;
            }
        </style>

        <!-- User List Shortcodes Section -->
        <div class="shortcode-section">
            <h2>1. Custom User List Shortcode</h2>
            <p><strong>Purpose:</strong> Displays a list of user posts from the 'user-list' custom post type with category filtering capabilities.</p>

            <div class="shortcode-code">
                [custom_user_list category="Category Name"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Required</th>
                    </tr>
                    <tr>
                        <td><code>category</code></td>
                        <td>String</td>
                        <td>Filter posts by category name. Supports multiple categories separated by commas.</td>
                        <td>No</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Examples:</h3>
                <p><strong>Single category:</strong></p>
                <code>[custom_user_list category="Customer Service"]</code>

                <p><strong>Multiple categories:</strong></p>
                <code>[custom_user_list category="Admin, Sub Admin, Super Agent"]</code>

                <p><strong>All users (no filter):</strong></p>
                <code>[custom_user_list]</code>
            </div>

            <p><strong>Displays:</strong> User ID, thumbnail, title, category (translated to Bengali), WhatsApp Primary/Secondary, and Messenger contact information.</p>
        </div>

        <!-- Complain Admin List 2 -->
        <div class="shortcode-section">
            <h2>2. Complain Admin List Layout 2</h2>
            <p><strong>Purpose:</strong> Displays users marked as complaint agents in a specialized layout format.</p>

            <div class="shortcode-code">
                [complain_admin_list_2 category="Category Name"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Required</th>
                    </tr>
                    <tr>
                        <td><code>category</code></td>
                        <td>String</td>
                        <td>Filter posts by category name.</td>
                        <td>No</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[complain_admin_list_2 category="Admin"]</code>
            </div>

            <p><strong>Note:</strong> Only displays users with 'complain_agent' meta field set to '1'.</p>
        </div>

        <!-- Complain Admin List -->
        <div class="shortcode-section">
            <h2>3. Complain Admin List</h2>
            <p><strong>Purpose:</strong> Displays users marked as complaint agents in standard list layout.</p>

            <div class="shortcode-code">
                [complain_admin_list category="Category Name"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Required</th>
                    </tr>
                    <tr>
                        <td><code>category</code></td>
                        <td>String</td>
                        <td>Filter posts by multiple categories (comma-separated).</td>
                        <td>No</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[complain_admin_list category="Admin, Sub Admin"]</code>
            </div>

            <p><strong>Note:</strong> Only displays users with 'complain_agent' meta field set to '1'. Social connections are hidden by default.</p>
        </div>

        <!-- New Account List 2 -->
        <div class="shortcode-section">
            <h2>4. New Account List Layout 2</h2>
            <p><strong>Purpose:</strong> Displays users marked as new account agents in a specialized layout.</p>

            <div class="shortcode-code">
                [new_account_list_2 category="Category Name"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Required</th>
                    </tr>
                    <tr>
                        <td><code>category</code></td>
                        <td>String</td>
                        <td>Filter posts by category name.</td>
                        <td>No</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[new_account_list_2 category="Master Agent"]</code>
            </div>

            <p><strong>Note:</strong> Only displays users with 'new_account_agent' meta field set to '1'.</p>
        </div>

        <!-- New Account List -->
        <div class="shortcode-section">
            <h2>5. New Account List (Ordered)</h2>
            <p><strong>Purpose:</strong> Displays users marked as new account agents ordered by serial number.</p>

            <div class="shortcode-code">
                [new_account_list category="Category Name"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Required</th>
                    </tr>
                    <tr>
                        <td><code>category</code></td>
                        <td>String</td>
                        <td>Filter posts by multiple categories (comma-separated).</td>
                        <td>No</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[new_account_list category="Super Agent, Master Agent"]</code>
            </div>

            <p><strong>Special Feature:</strong> Results are automatically ordered by 'serial_number_for_new_account_agent' meta field in ascending order.</p>
            <p><strong>Note:</strong> Only displays users with 'new_account_agent' meta field set to '1'. Social connections are hidden by default.</p>
        </div>

        <!-- Quiz Post List (Old) -->
        <div class="shortcode-section">
            <h2>6. Quiz Post List (Legacy)</h2>
            <p><strong>Purpose:</strong> Displays a list of quiz posts from 'ninequiz' custom post type with detailed information (legacy version).</p>

            <div class="shortcode-code">
                [quiz_post_list_pld]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <p>This shortcode does not accept any attributes.</p>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[quiz_post_list_pld]</code>
            </div>

            <p><strong>Displays:</strong> Post thumbnail, title, quiz date, form ID, and countdown time (hours, minutes, seconds).</p>
            <p><strong>Note:</strong> This is a legacy version. Consider using [quiz_post_list] instead.</p>
        </div>

        <!-- Quiz Timer Card (Old) -->
        <div class="shortcode-section">
            <h2>7. Quiz Timer Card (Legacy)</h2>
            <p><strong>Purpose:</strong> Displays quiz countdown timer cards with live countdown functionality (legacy version).</p>

            <div class="shortcode-code">
                [quiz_timer_card_old]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <p>This shortcode does not accept any attributes.</p>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[quiz_timer_card_old]</code>
            </div>

            <p><strong>Features:</strong></p>
            <ul>
                <li>Live countdown timer with automatic status updates</li>
                <li>Three states: "Coming Soon", "Live Countdown", "Quiz Closed"</li>
                <li>Timezone: Asia/Dhaka (hardcoded)</li>
                <li>Displays quiz thumbnail and title with permalink</li>
            </ul>
            <p><strong>Note:</strong> This is a legacy version. Consider using [quiz_timer_card] instead.</p>
        </div>

        <!-- Quiz Post List -->
        <div class="shortcode-section">
            <h2>8. Quiz Post List (Current)</h2>
            <p><strong>Purpose:</strong> Displays a list of quiz posts from 'ninequiz' custom post type (current version).</p>

            <div class="shortcode-code">
                [quiz_post_list]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <p>This shortcode does not accept any attributes.</p>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[quiz_post_list]</code>
            </div>

            <p><strong>Displays:</strong> Post thumbnail, title, quiz date, form ID, and countdown time (hours, minutes, seconds).</p>
            <p><strong>Requirement:</strong> Posts must have 'quiz_date' meta field.</p>
        </div>

        <!-- Quiz Timer Card -->
        <div class="shortcode-section">
            <h2>9. Quiz Timer Card (Current)</h2>
            <p><strong>Purpose:</strong> Displays quiz countdown timer cards with enhanced functionality (current version).</p>

            <div class="shortcode-code">
                [quiz_timer_card]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <p>This shortcode does not accept any attributes.</p>
            </div>

            <div class="shortcode-example">
                <h3>Usage Example:</h3>
                <code>[quiz_timer_card]</code>
            </div>

            <p><strong>Features:</strong></p>
            <ul>
                <li>Improved countdown timer with separate start and end times</li>
                <li>Three states: "Coming Soon", "Live Countdown", "Quiz Closed"</li>
                <li>Timezone: Asia/Dhaka</li>
                <li>Unique ID system for multiple quiz cards</li>
                <li>Uses 'quiz_date' and 'end_time' meta fields for accurate timing</li>
                <li>Real-time JavaScript countdown with automatic updates</li>
            </ul>
            <p><strong>Recommended:</strong> This is the current and recommended version over the legacy [quiz_timer_card_old].</p>
        </div>

        <!-- Additional Information -->
        <div class="shortcode-section">
            <h2>Additional Information</h2>

            <h3>Common Categories (User List Shortcodes):</h3>
            <ul>
                <li><strong>Customer Service</strong> - কাস্টমার সার্ভিস</li>
                <li><strong>Sub Admin</strong> - সাব-এডমিন</li>
                <li><strong>Admin</strong> - এডমিন</li>
                <li><strong>Super Agent</strong> - সুপার এজেন্ট</li>
                <li><strong>Master Agent</strong> - মাস্টার এজেন্ট</li>
            </ul>

            <h3>Custom Post Types Used:</h3>
            <ul>
                <li><strong>user-list</strong> - Used for custom user listings with categories</li>
                <li><strong>ninequiz</strong> - Used for quiz post management and countdown timers</li>
            </ul>

            <h3>Required Meta Fields for Quiz Shortcodes:</h3>
            <ul>
                <li><code>quiz_date</code> - Quiz start date and time</li>
                <li><code>end_time</code> - Quiz end date and time (for [quiz_timer_card])</li>
                <li><code>quiz_form_id</code> - Associated form ID</li>
                <li><code>quiz_count_time_hour</code> - Countdown hours (legacy)</li>
                <li><code>quiz_count_time_minute</code> - Countdown minutes (legacy)</li>
                <li><code>quiz_count_time_second</code> - Countdown seconds (legacy)</li>
            </ul>

            <h3>Required Meta Fields for User List Shortcodes:</h3>
            <ul>
                <li><code>user_id</code> - User identification number</li>
                <li><code>whatsapp_primary</code> - Primary WhatsApp number</li>
                <li><code>whatsapp_primary_link</code> - Primary WhatsApp chat link</li>
                <li><code>whatsapp_secondary</code> - Secondary WhatsApp number</li>
                <li><code>whatsapp_secondary_link</code> - Secondary WhatsApp chat link</li>
                <li><code>messenger</code> - Messenger contact link</li>
                <li><code>complain_agent</code> - Flag (1) for complaint handling agents</li>
                <li><code>new_account_agent</code> - Flag (1) for new account agents</li>
                <li><code>serial_number_for_new_account_agent</code> - Sort order for new account list</li>
            </ul>
        </div>

        <!-- Offer List Shortcode -->
        <div class="shortcode-section">
            <h2>10. Offer List Display</h2>
            <p><strong>Purpose:</strong> Displays a beautiful grid of promotional offers from the 'offer-list' custom post type.</p>

            <div class="shortcode-code">
                [offer_list limit="3" order="DESC" orderby="date"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Default</th>
                    </tr>
                    <tr>
                        <td><code>limit</code></td>
                        <td>Number</td>
                        <td>Number of offers to display (-1 for all)</td>
                        <td>-1</td>
                    </tr>
                    <tr>
                        <td><code>order</code></td>
                        <td>String</td>
                        <td>Sort order: ASC or DESC</td>
                        <td>DESC</td>
                    </tr>
                    <tr>
                        <td><code>orderby</code></td>
                        <td>String</td>
                        <td>Sort by: date, title, menu_order, etc.</td>
                        <td>date</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Examples:</h3>
                <p><strong>Display 3 latest offers:</strong></p>
                <code>[offer_list limit="3"]</code>

                <p><strong>Display all offers ordered by title:</strong></p>
                <code>[offer_list orderby="title" order="ASC"]</code>

                <p><strong>Display 6 offers in custom order:</strong></p>
                <code>[offer_list limit="6" orderby="menu_order"]</code>
            </div>

            <p><strong>Features:</strong></p>
            <ul>
                <li>Responsive grid layout (3 columns on desktop, 1 on mobile)</li>
                <li>Card-based design with hover effects</li>
                <li>Featured image header with offer title badge</li>
                <li>Customizable icon with background color</li>
                <li>Prize amount display section</li>
                <li>Customizable CTA button with gradient colors</li>
                <li>Bengali language support for all text</li>
            </ul>

            <h3>Required Meta Fields:</h3>
            <ul>
                <li><code>_offer_title</code> - Offer title in Bengali (e.g., দৈনিক বোনাস)</li>
                <li><code>_offer_badge</code> - Badge text displayed on card header (e.g., আজকের অফার, নতুন) - Optional, uses title if empty</li>
                <li><code>_offer_icon</code> - Icon image URL (recommended: 100x100px)</li>
                <li><code>_offer_icon_bg_color</code> - Icon container background color (hex)</li>
                <li><code>_offer_description</code> - Brief offer description in Bengali</li>
                <li><code>_offer_prize</code> - Prize amount in Bengali (e.g., ১০,০০০ টাকা)</li>
                <li><code>_offer_link</code> - Target URL for the offer</li>
                <li><code>_offer_button_text</code> - Button text in Bengali (e.g., এখনই যুক্ত হন)</li>
                <li><code>_offer_button_color</code> - Button gradient color (hex)</li>
            </ul>

            <p><strong>Custom Post Type:</strong> Navigate to <strong>WordPress Admin → Offer List</strong> to manage offers.</p>
        </div>

        <!-- Offer List Slider Shortcode -->
        <div class="shortcode-section">
            <h2>11. Offer List Slider</h2>
            <p><strong>Purpose:</strong> Displays promotional offers in an interactive slider with customizable desktop/mobile item counts and navigation arrows.</p>

            <div class="shortcode-code">
                [offer_list_slider desktop_items="3" mobile_items="1" autoplay="true" autoplay_speed="3000"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Default</th>
                    </tr>
                    <tr>
                        <td><code>desktop_items</code></td>
                        <td>Number</td>
                        <td>Number of slides visible on desktop (screen width > 768px)</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td><code>mobile_items</code></td>
                        <td>Number</td>
                        <td>Number of slides visible on mobile (screen width ≤ 768px)</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td><code>autoplay</code></td>
                        <td>Boolean</td>
                        <td>Enable/disable automatic sliding (true or false)</td>
                        <td>true</td>
                    </tr>
                    <tr>
                        <td><code>autoplay_speed</code></td>
                        <td>Number</td>
                        <td>Autoplay interval in milliseconds</td>
                        <td>3000</td>
                    </tr>
                    <tr>
                        <td><code>limit</code></td>
                        <td>Number</td>
                        <td>Number of offers to display (-1 for all)</td>
                        <td>-1</td>
                    </tr>
                    <tr>
                        <td><code>order</code></td>
                        <td>String</td>
                        <td>Sort order: ASC or DESC</td>
                        <td>DESC</td>
                    </tr>
                    <tr>
                        <td><code>orderby</code></td>
                        <td>String</td>
                        <td>Sort by: date, title, menu_order, etc.</td>
                        <td>date</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Examples:</h3>
                <p><strong>Basic slider with 3 items on desktop, 1 on mobile:</strong></p>
                <code>[offer_list_slider]</code>

                <p><strong>Show 4 items on desktop, 2 on mobile with slower autoplay:</strong></p>
                <code>[offer_list_slider desktop_items="4" mobile_items="2" autoplay_speed="5000"]</code>

                <p><strong>Display 2 items on desktop without autoplay:</strong></p>
                <code>[offer_list_slider desktop_items="2" mobile_items="1" autoplay="false"]</code>

                <p><strong>Limit to 6 offers, show 3 on desktop:</strong></p>
                <code>[offer_list_slider limit="6" desktop_items="3" mobile_items="1"]</code>
            </div>

            <p><strong>Features:</strong></p>
            <ul>
                <li>Responsive slider with separate desktop/mobile item counts</li>
                <li>Smooth slide transitions with CSS transforms</li>
                <li>Previous/Next navigation arrows</li>
                <li>Optional autoplay with customizable speed</li>
                <li>Pause autoplay on hover</li>
                <li>Disabled button states at slider boundaries</li>
                <li>Automatic loop when autoplay reaches the end</li>
                <li>Window resize handling with smooth transitions</li>
                <li>Unique ID system for multiple sliders on same page</li>
                <li>Same card design as [offer_list] shortcode</li>
                <li>Bengali language support throughout</li>
            </ul>

            <p><strong>Slider Controls:</strong></p>
            <ul>
                <li><strong>Left Arrow:</strong> Navigate to previous slide(s)</li>
                <li><strong>Right Arrow:</strong> Navigate to next slide(s)</li>
                <li><strong>Hover:</strong> Pauses autoplay automatically</li>
                <li><strong>Mouse Leave:</strong> Resumes autoplay (if enabled)</li>
            </ul>

            <p><strong>Responsive Behavior:</strong></p>
            <ul>
                <li><strong>Desktop (>768px):</strong> Shows number of items specified in <code>desktop_items</code></li>
                <li><strong>Mobile (≤768px):</strong> Shows number of items specified in <code>mobile_items</code></li>
                <li>Slider automatically adjusts on window resize</li>
                <li>Navigation arrows resize appropriately for mobile</li>
            </ul>

            <h3>Design Details:</h3>
            <p>The slider follows the same visual design as the static offer list with:</p>
            <ul>
                <li>Header title "সাইন করুন" (customizable in code)</li>
                <li>Circular navigation buttons with hover effects</li>
                <li>30px gap between slides</li>
                <li>Smooth 0.5s slide transitions</li>
                <li>Equal height cards in flexbox layout</li>
            </ul>

            <p><strong>Custom Post Type:</strong> Navigate to <strong>WordPress Admin → Offer List</strong> to manage offers.</p>
            <p><strong>Note:</strong> Uses the same meta fields as the [offer_list] shortcode. See section #10 for field requirements.</p>
        </div>

        <!-- Velki FAQ Featured -->
        <div class="shortcode-section">
            <h2>12. Velki FAQ Featured</h2>
            <p><strong>Purpose:</strong> Displays featured FAQs in an interactive accordion design with dark theme.</p>

            <div class="shortcode-code">
                [velki_faq_featured limit="-1"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Default</th>
                    </tr>
                    <tr>
                        <td><code>limit</code></td>
                        <td>Number</td>
                        <td>Number of FAQs to display (-1 for all)</td>
                        <td>-1</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Examples:</h3>
                <p><strong>Display all featured FAQs:</strong></p>
                <code>[velki_faq_featured]</code>

                <p><strong>Display 5 featured FAQs:</strong></p>
                <code>[velki_faq_featured limit="5"]</code>
            </div>

            <p><strong>Features:</strong></p>
            <ul>
                <li>Dark gradient background (navy blue)</li>
                <li>Smooth accordion expand/collapse animation</li>
                <li>Yellow question mark icon with circular border</li>
                <li>Plus/minus toggle indicator</li>
                <li>Only one FAQ open at a time</li>
                <li>Hover effects on cards</li>
                <li>Responsive design for mobile devices</li>
                <li>HTML content support in answers</li>
            </ul>

            <h3>Design Details:</h3>
            <ul>
                <li>Background: Dark gradient (#1a1f35 to #252b45)</li>
                <li>Question text: White, 16px, bold</li>
                <li>Answer text: Light gray (#d1d5db), 15px</li>
                <li>Icon color: Yellow (#fbbf24)</li>
                <li>Border radius: 12px for smooth corners</li>
                <li>Gap between items: 12px</li>
            </ul>

            <p><strong>Note:</strong> Only displays FAQs marked as "Featured" in the admin panel.</p>
        </div>

        <!-- Velki FAQ All -->
        <div class="shortcode-section">
            <h2>13. Velki FAQ All</h2>
            <p><strong>Purpose:</strong> Displays all published FAQs in an interactive accordion design.</p>

            <div class="shortcode-code">
                [velki_faq_all limit="-1"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Default</th>
                    </tr>
                    <tr>
                        <td><code>limit</code></td>
                        <td>Number</td>
                        <td>Number of FAQs to display (-1 for all)</td>
                        <td>-1</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Examples:</h3>
                <p><strong>Display all FAQs:</strong></p>
                <code>[velki_faq_all]</code>

                <p><strong>Display 10 FAQs:</strong></p>
                <code>[velki_faq_all limit="10"]</code>
            </div>

            <p><strong>Features:</strong></p>
            <ul>
                <li>Same design as featured FAQs</li>
                <li>Displays all published FAQs (featured and non-featured)</li>
                <li>Accordion functionality with smooth animations</li>
                <li>HTML content support in FAQ answers</li>
                <li>Responsive mobile layout</li>
            </ul>

            <h3>Managing FAQs:</h3>
            <p>Navigate to <strong>WordPress Admin → Velki FAQs</strong> to manage FAQ items.</p>

            <h4>Creating an FAQ:</h4>
            <ol>
                <li>Go to <strong>Velki FAQs → Add New</strong></li>
                <li>Enter the <strong>Question</strong> in the title field</li>
                <li>Enter the <strong>Answer</strong> in the content editor (supports HTML)</li>
                <li>Check <strong>"Featured FAQ"</strong> checkbox in the sidebar if you want it to appear in featured section</li>
                <li>Click <strong>Publish</strong></li>
            </ol>

            <h4>FAQ Meta Fields:</h4>
            <ul>
                <li><strong>Title:</strong> The FAQ question text</li>
                <li><strong>Content:</strong> The FAQ answer (HTML editor)</li>
                <li><strong>Featured:</strong> Checkbox to mark as featured</li>
            </ul>

            <h3>Difference Between Shortcodes:</h3>
            <table class="attribute-table">
                <tr>
                    <th>Shortcode</th>
                    <th>Displays</th>
                    <th>Use Case</th>
                </tr>
                <tr>
                    <td><code>[velki_faq_featured]</code></td>
                    <td>Only featured FAQs</td>
                    <td>Homepage, important questions section</td>
                </tr>
                <tr>
                    <td><code>[velki_faq_all]</code></td>
                    <td>All FAQs</td>
                    <td>Dedicated FAQ page, help center</td>
                </tr>
            </table>
        </div>

        <!-- Velki Agent List -->
        <div class="shortcode-section">
            <h2>14. Velki Agent List</h2>
            <p><strong>Purpose:</strong> Displays agents from the velki-agent custom post type with modern card design, contact information, and copy functionality.</p>

            <div class="shortcode-code">
                [velki_agent_list group="মাস্টার এজেন্ট"]
            </div>

            <div class="shortcode-attributes">
                <h3>Attributes:</h3>
                <table class="attribute-table">
                    <tr>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Default</th>
                    </tr>
                    <tr>
                        <td><code>group</code></td>
                        <td>String</td>
                        <td>Agent group(s) to filter by (comma-separated for multiple groups)</td>
                        <td>Empty (all agents)</td>
                    </tr>
                    <tr>
                        <td><code>limit</code></td>
                        <td>Number</td>
                        <td>Number of agents to display (-1 for all)</td>
                        <td>-1</td>
                    </tr>
                </table>
            </div>

            <div class="shortcode-example">
                <h3>Usage Examples:</h3>
                <p><strong>Display all Master Agents:</strong></p>
                <code>[velki_agent_list group="মাস্টার এজেন্ট"]</code>

                <p><strong>Display multiple agent groups:</strong></p>
                <code>[velki_agent_list group="মাস্টার এজেন্ট,সুপার এজেন্ট,সাব এ্যাডমিন"]</code>

                <p><strong>Display all agents (no filter):</strong></p>
                <code>[velki_agent_list]</code>

                <p><strong>Limit to 10 agents:</strong></p>
                <code>[velki_agent_list group="এ্যাডমিন" limit="10"]</code>
            </div>

            <p><strong>Features:</strong></p>
            <ul>
                <li>Modern dark gradient card design</li>
                <li>Agent photo with verified badge and premium crown icons</li>
                <li>Agent name, group, rating (stars), and ID display</li>
                <li>WhatsApp contact with 2 numbers support</li>
                <li>Messenger contact information</li>
                <li>One-click copy functionality for phone numbers and usernames</li>
                <li>Direct message buttons linking to WhatsApp and Messenger</li>
                <li>Fully responsive design (desktop, tablet, mobile)</li>
                <li>Hover effects and smooth transitions</li>
            </ul>

            <h3>Design Details:</h3>
            <ul>
                <li>Dark gradient background (#1e293b to #334155)</li>
                <li>Rounded corners (16px) with shadow effects</li>
                <li>WhatsApp sections in green (#10b981)</li>
                <li>Messenger sections in blue (#3b82f6)</li>
                <li>Copy button with visual feedback (changes to checkmark for 2 seconds)</li>
                <li>Verified badge in yellow (#fbbf24) with checkmark icon</li>
                <li>Premium crown emoji for premium agents</li>
            </ul>

            <h3>Required Meta Fields:</h3>
            <ul>
                <li><code>_agent_id</code> - Agent identification number</li>
                <li><code>_agent_rating</code> - Agent rating (1-5 stars)</li>
                <li><code>_agent_verified</code> - Verified status (1 = verified, 0 = not verified)</li>
                <li><code>_agent_premium</code> - Premium status (1 = premium, 0 = not premium)</li>
                <li><code>_agent_whatsapp_url_1</code> - Primary WhatsApp message URL (e.g., https://wa.me/18049722549)</li>
                <li><code>_agent_whatsapp_url_2</code> - Secondary WhatsApp message URL (optional)</li>
                <li><code>_agent_messenger_url</code> - Messenger message URL (e.g., https://m.me/velkiagents.pro)</li>
            </ul>

            <h3>Available Agent Groups:</h3>
            <ul>
                <li><strong>মাস্টার এজেন্ট</strong> - Master Agent</li>
                <li><strong>সুপার এজেন্ট</strong> - Super Agent</li>
                <li><strong>সাব এ্যাডমিন</strong> - Sub Admin</li>
                <li><strong>এ্যাডমিন</strong> - Admin</li>
            </ul>

            <p><strong>Custom Post Type:</strong> Navigate to <strong>WordPress Admin → Velki Agents</strong> to manage agents.</p>
        </div>

        <!-- Support Section -->
        <div class="shortcode-section">
            <h2>Need Help?</h2>
            <p>If you need assistance implementing these shortcodes or have questions about their functionality, please contact the theme developer.</p>
            <p><strong>Theme:</strong> Wicket</p>
            <p><strong>Version:</strong> Current</p>
        </div>
    </div>
    <?php
}
