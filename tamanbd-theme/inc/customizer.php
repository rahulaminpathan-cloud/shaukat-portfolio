<?php
/**
 * TamanBD Theme Customizer Controls
 *
 * Allows managing announcement bar, hero slides, banners, trust pillars,
 * and footer contact details natively inside Appearance -> Customize.
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Customizer panels, sections, and settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function tamanbd_customize_register( WP_Customize_Manager $wp_customize ): void {
    $wp_customize->add_panel(
        'tamanbd_storefront_panel',
        array(
            'title'       => __( 'TamanBD Storefront Settings', 'tamanbd' ),
            'description' => __( 'Manage homepage banners, announcement bar, service highlights, and store contact details.', 'tamanbd' ),
            'priority'    => 30,
        )
    );

    // 1. Announcement Bar Section.
    $wp_customize->add_section(
        'tamanbd_announcement_section',
        array(
            'title' => __( 'Announcement Bar', 'tamanbd' ),
            'panel' => 'tamanbd_storefront_panel',
        )
    );

    $wp_customize->add_setting(
        'tamanbd_announcement_enable',
        array(
            'default'           => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        )
    );
    $wp_customize->add_control(
        'tamanbd_announcement_enable',
        array(
            'label'   => __( 'Show Announcement Bar', 'tamanbd' ),
            'section' => 'tamanbd_announcement_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'tamanbd_announcement_text',
        array(
            'default'           => __( 'Free Dhaka delivery on orders above ৳2,500 · Cash on Delivery, bKash & Nagad accepted', 'tamanbd' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'tamanbd_announcement_text',
        array(
            'label'   => __( 'Announcement Text', 'tamanbd' ),
            'section' => 'tamanbd_announcement_section',
            'type'    => 'text',
        )
    );

    // 2. Hero Slider (2 Slides).
    $wp_customize->add_section(
        'tamanbd_hero_section',
        array(
            'title' => __( 'Homepage Hero Slider', 'tamanbd' ),
            'panel' => 'tamanbd_storefront_panel',
        )
    );

    for ( $i = 1; $i <= 2; $i++ ) {
        $default_kicker   = 1 === $i ? __( 'Dhaka Living Edit', 'tamanbd' ) : __( 'Artisan & Botanical Capsule', 'tamanbd' );
        $default_title    = 1 === $i ? __( 'Warm Teak & Handcrafted Home Essentials', 'tamanbd' ) : __( 'Handloom Textiles Meets Pure Botanical Care', 'tamanbd' );
        $default_subtitle = 1 === $i
            ? __( 'Architectural shelving, brushed brass lighting, and artisanal stoneware crafted for modern Bangladeshi homes.', 'tamanbd' )
            : __( 'Small-batch botanical serums, handwoven cottons, and solid acacia pantry ware delivered nationwide.', 'tamanbd' );
        $default_cta      = 1 === $i ? __( 'Explore Store Catalog', 'tamanbd' ) : __( 'Shop Featured Goods', 'tamanbd' );

        $wp_customize->add_setting( "tamanbd_hero_{$i}_kicker", array( 'default' => $default_kicker, 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "tamanbd_hero_{$i}_kicker", array( 'label' => sprintf( __( 'Slide %d Kicker', 'tamanbd' ), $i ), 'section' => 'tamanbd_hero_section', 'type' => 'text' ) );

        $wp_customize->add_setting( "tamanbd_hero_{$i}_title", array( 'default' => $default_title, 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "tamanbd_hero_{$i}_title", array( 'label' => sprintf( __( 'Slide %d Headline', 'tamanbd' ), $i ), 'section' => 'tamanbd_hero_section', 'type' => 'text' ) );

        $wp_customize->add_setting( "tamanbd_hero_{$i}_subtitle", array( 'default' => $default_subtitle, 'sanitize_callback' => 'sanitize_textarea_field' ) );
        $wp_customize->add_control( "tamanbd_hero_{$i}_subtitle", array( 'label' => sprintf( __( 'Slide %d Description', 'tamanbd' ), $i ), 'section' => 'tamanbd_hero_section', 'type' => 'textarea' ) );

        $wp_customize->add_setting( "tamanbd_hero_{$i}_cta_text", array( 'default' => $default_cta, 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "tamanbd_hero_{$i}_cta_text", array( 'label' => sprintf( __( 'Slide %d Button Label', 'tamanbd' ), $i ), 'section' => 'tamanbd_hero_section', 'type' => 'text' ) );

        $wp_customize->add_setting( "tamanbd_hero_{$i}_cta_url", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( "tamanbd_hero_{$i}_cta_url", array( 'label' => sprintf( __( 'Slide %d Button URL (defaults to Shop)', 'tamanbd' ), $i ), 'section' => 'tamanbd_hero_section', 'type' => 'url' ) );

        $wp_customize->add_setting( "tamanbd_hero_{$i}_image", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                "tamanbd_hero_{$i}_image",
                array(
                    'label'   => sprintf( __( 'Slide %d Image (16:9 recommended)', 'tamanbd' ), $i ),
                    'section' => 'tamanbd_hero_section',
                )
            )
        );
    }

    // 3. Promotional Banners & Offer Strip.
    $wp_customize->add_section(
        'tamanbd_promo_section',
        array(
            'title' => __( 'Promotional Banners & Offer Strip', 'tamanbd' ),
            'panel' => 'tamanbd_storefront_panel',
        )
    );

    $wp_customize->add_setting( 'tamanbd_offer_kicker', array( 'default' => __( 'Seasonal Privilege · Nationwide Bangladesh', 'tamanbd' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'tamanbd_offer_kicker', array( 'label' => __( 'Offer Band Kicker', 'tamanbd' ), 'section' => 'tamanbd_promo_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'tamanbd_offer_title', array( 'default' => __( 'Complimentary Dhaka Metro Delivery Above ৳2,500', 'tamanbd' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'tamanbd_offer_title', array( 'label' => __( 'Offer Band Title', 'tamanbd' ), 'section' => 'tamanbd_promo_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'tamanbd_offer_desc', array( 'default' => __( 'Pay securely with Cash on Delivery upon parcel inspection, or settle instantly via bKash and Nagad.', 'tamanbd' ), 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'tamanbd_offer_desc', array( 'label' => __( 'Offer Band Description', 'tamanbd' ), 'section' => 'tamanbd_promo_section', 'type' => 'textarea' ) );

    // 4. Footer & Contact Details.
    $wp_customize->add_section(
        'tamanbd_contact_section',
        array(
            'title' => __( 'Store Contact & Footer', 'tamanbd' ),
            'panel' => 'tamanbd_storefront_panel',
        )
    );

    $wp_customize->add_setting( 'tamanbd_store_address', array( 'default' => __( 'House #2, Road #12, Merul Badda, DIT Project, Dhaka-1212', 'tamanbd' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'tamanbd_store_address', array( 'label' => __( 'Store Address', 'tamanbd' ), 'section' => 'tamanbd_contact_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'tamanbd_store_phone', array( 'default' => '+880 1823-920184', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'tamanbd_store_phone', array( 'label' => __( 'Support Phone', 'tamanbd' ), 'section' => 'tamanbd_contact_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'tamanbd_store_email', array( 'default' => 'info@tamanbd.com', 'sanitize_callback' => 'sanitize_email' ) );
    $wp_customize->add_control( 'tamanbd_store_email', array( 'label' => __( 'Support Email', 'tamanbd' ), 'section' => 'tamanbd_contact_section', 'type' => 'email' ) );

    // 5. Elementor Global Header & Footer Assignment (Free Alternative to Elementor Pro Theme Builder).
    $wp_customize->add_section(
        'tamanbd_elementor_layout_section',
        array(
            'title'       => __( 'Elementor Header & Footer (Free)', 'tamanbd' ),
            'description' => __( 'Select Elementor layouts created under TamanBD Layouts to replace the default Header and Footer without Elementor Pro.', 'tamanbd' ),
            'panel'       => 'tamanbd_storefront_panel',
        )
    );

    $layout_choices = array( 0 => __( '— Use Default Theme Header/Footer —', 'tamanbd' ) );
    $layouts        = get_posts(
        array(
            'post_type'      => 'tmb_template',
            'posts_per_page' => 50,
            'post_status'    => 'publish',
        )
    );
    if ( is_array( $layouts ) ) {
        foreach ( $layouts as $layout_post ) {
            $layout_choices[ $layout_post->ID ] = $layout_post->post_title;
        }
    }

    $wp_customize->add_setting( 'tamanbd_elementor_header_id', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control(
        'tamanbd_elementor_header_id',
        array(
            'label'   => __( 'Global Elementor Header Layout', 'tamanbd' ),
            'section' => 'tamanbd_elementor_layout_section',
            'type'    => 'select',
            'choices' => $layout_choices,
        )
    );

    $wp_customize->add_setting( 'tamanbd_elementor_footer_id', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control(
        'tamanbd_elementor_footer_id',
        array(
            'label'   => __( 'Global Elementor Footer Layout', 'tamanbd' ),
            'section' => 'tamanbd_elementor_layout_section',
            'type'    => 'select',
            'choices' => $layout_choices,
        )
    );
}
add_action( 'customize_register', 'tamanbd_customize_register' );
