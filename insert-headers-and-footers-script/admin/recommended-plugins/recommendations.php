<?php
/**
 * Constructor Parameters
 *
 * @param string    $text_domain your plugin text domain.
 * @param string    $parent_menu_slug the menu slug name where the "Recommendations" submenu will appear.
 * @param string    $submenu_label To change the submenu name.
 * @param string    $submenu_page_name an unique page name for the submenu.
 * @param int       $priority Submenu priority adjust.
 * @param string    $hook_suffix use it to load this library assets only to the recommedded plugins page. Not into the whol admin area.
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require(  __DIR__ .'/class.recommended-plugins.php' );

if( class_exists('Hasthemes\HTScript\HTRP_Recommended_Plugins') ){
    $get_instance = new Hasthemes\HTScript\HTRP_Recommended_Plugins(
        array(
            'text_domain'       => 'ihafs',
            'parent_menu_slug'  => 'edit.php?post_type=ihafs_script',
            'menu_capability'   => 'manage_options',
            'menu_page_slug'    => '',
            'priority'          => '999',
            'assets_url'        => IHAFS_URI.'/admin/recommended-plugins/assets',
            'hook_suffix'       => 'ihafs_script_page_ihafs_extensions',
        )
    );

    // Only recommend WooCommerce-only plugins (WooLentor) when WooCommerce is installed.
    $woocommerce_active = class_exists( 'WooCommerce' );

    $get_instance->add_new_tab( array(
        'title' => esc_html__( 'Recommended Plugins', 'ihafs' ),
        'active' => true,
        'plugins' => array_merge(
            $woocommerce_active ? array(
                array(
                    'slug'      => 'woolentor-addons',
                    'location'  => 'woolentor_addons_elementor.php',
                    'name'      => esc_html__( 'ShopLentor – All-in-One WooCommerce Growth & Store Enhancement Plugin', 'ihafs' )
                ),
            ) : array(),
            array(
                array(
                    'slug'      => 'ht-mega-for-elementor',
                    'location'  => 'htmega_addons_elementor.php',
                    'name'      => esc_html__( 'HT Mega Addons for Elementor – Elementor Widgets & Template Builder', 'ihafs' )
                ),
                array(
                    'slug'      => 'support-genix-lite',
                    'location'  => 'support-genix-lite.php',
                    'name'      => esc_html__( 'Support Genix – Helpdesk, AI Chatbot, Knowledge Base & Customer Support Ticketing System', 'ihafs' )
                ),
                array(
                    'slug'      => 'hashbar-wp-notification-bar',
                    'location'  => 'init.php',
                    'name'      => esc_html__( 'HashBar – Announcement, Notification Bar & Popup Campaign', 'ihafs' )
                ),
                array(
                    'slug'      => 'wp-plugin-manager',
                    'location'  => 'plugin-main.php',
                    'name'      => esc_html__( 'WP Plugin Manager – Deactivate plugins per page', 'ihafs' )
                ),
                array(
                    'slug'      => 'cookieray',
                    'location'  => 'cookieray.php',
                    'name'      => esc_html__( 'CookieRay – Cookie Banner for Cookie Consent (GDPR/CCPA Compliant)', 'ihafs' )
                ),
                array(
                    'slug'      => 'pixelavo',
                    'location'  => 'pixelavo.php',
                    'name'      => esc_html__( 'Pixelavo – Server Side Tracking & Pixel + AI Ads Tools', 'ihafs' )
                ),
            )
        )
    ) );

    $get_instance->add_new_tab( array(
        'title' => esc_html__( 'WooCommerce', 'ihafs' ),

        'plugins' => array(

            array(
                'slug'      => 'woolentor-addons',
                'location'  => 'woolentor_addons_elementor.php',
                'name'      => esc_html__( 'ShopLentor – All-in-One WooCommerce Growth & Store Enhancement Plugin', 'ihafs' )
            ),
            array(
                'slug'      => 'whols',
                'location'  => 'whols.php',
                'name'      => esc_html__( 'Whols – Wholesale Prices and B2B Store Solution for WooCommerce', 'ihafs' )
            ),
            array(
                'slug'      => 'recurio',
                'location'  => 'recurio.php',
                'name'      => esc_html__( 'Recurio – Ultimate Subscription for WooCommerce', 'ihafs' )
            ),

        )

    ) );

    $get_instance->add_new_tab( array(
        'title' => esc_html__( 'Other Plugins', 'ihafs' ),
        'plugins' => array(
            array(
                'slug'      => 'ht-slider-for-elementor',
                'location'  => 'ht-slider-for-elementor.php',
                'name'      => esc_html__( 'HT Slider For Elementor', 'ihafs' )
            ),
            array(
                'slug'      => 'kelune-crm',
                'location'  => 'kelune-crm.php',
                'name'      => esc_html__( 'Kelune CRM – Contact Management, Email Marketing, Newsletter & Marketing Automation', 'ihafs' )
            ),
            array(
                'slug'      => 'wp-plugin-manager',
                'location'  => 'plugin-main.php',
                'name'      => esc_html__( 'WP Plugin Manager – Deactivate plugins per page', 'ihafs' )
            ),
            array(
                'slug'      => 'ht-easy-google-analytics',
                'location'  => 'ht-easy-google-analytics.php',
                'name'      => esc_html__( 'HT Easy GA4 – Google Analytics WordPress Plugin', 'ihafs' )
            ),
            array(
                'slug'      => 'ht-contactform',
                'location'  => 'contact-form-widget-elementor.php',
                'name'      => esc_html__( 'HT Contact Form – Drag & Drop Form Builder for WordPress', 'ihafs' )
            ),
            array(
                'slug'      => 'cookieray',
                'location'  => 'cookieray.php',
                'name'      => esc_html__( 'CookieRay – Cookie Banner for Cookie Consent (GDPR/CCPA Compliant)', 'ihafs' )
            ),
            array(
                'slug'      => 'courseglade-lms',
                'location'  => 'courseglade-lms.php',
                'name'      => esc_html__( 'CourseGlade LMS – Online Course & eLearning Platform', 'ihafs' )
            )

        )
    ) );
}
