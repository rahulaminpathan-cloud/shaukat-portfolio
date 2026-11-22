<?php
/**
 * Shared Helper Trait for TamanBD Elementor Widgets
 *
 * Dynamically populates WooCommerce product categories, WordPress menus,
 * and products inside Elementor editor dropdowns.
 *
 * @package TamanBD_Elementor
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait TamanBD_Widget_Helpers {

    /**
     * Get WordPress navigation menus for Elementor Select2/Select controls.
     *
     * @return array<string, string>
     */
    protected function get_wp_menus_options(): array {
        $options = array( '' => esc_html__( '— Select Menu —', 'tamanbd-elementor' ) );
        $menus   = wp_get_nav_menus();
        if ( is_array( $menus ) ) {
            foreach ( $menus as $menu ) {
                $options[ (string) $menu->term_id ] = $menu->name;
            }
        }
        return $options;
    }

    /**
     * Get live WooCommerce product categories.
     *
     * @return array<string, string>
     */
    protected function get_product_categories_options(): array {
        $options = array();
        if ( ! taxonomy_exists( 'product_cat' ) ) {
            return $options;
        }
        $terms = get_terms(
            array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
                'number'     => 100,
            )
        );
        if ( ! is_wp_error( $terms ) && is_array( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name . ' (' . (int) $term->count . ')';
            }
        }
        return $options;
    }

    /**
     * Get blog post categories.
     *
     * @return array<string, string>
     */
    protected function get_blog_categories_options(): array {
        $options = array( '' => esc_html__( 'All Categories', 'tamanbd-elementor' ) );
        $terms   = get_terms(
            array(
                'taxonomy'   => 'category',
                'hide_empty' => false,
                'number'     => 100,
            )
        );
        if ( ! is_wp_error( $terms ) && is_array( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ (string) $term->term_id ] = $term->name;
            }
        }
        return $options;
    }

    /**
     * Get recent WooCommerce products for manual selection in Elementor.
     *
     * @return array<string, string>
     */
    protected function get_wc_products_options(): array {
        $options = array();
        if ( ! function_exists( 'wc_get_products' ) ) {
            return $options;
        }
        $products = wc_get_products(
            array(
                'status' => 'publish',
                'limit'  => 100,
                'return' => 'objects',
            )
        );
        if ( is_array( $products ) ) {
            foreach ( $products as $product ) {
                if ( is_a( $product, 'WC_Product' ) ) {
                    $options[ (string) $product->get_id() ] = $product->get_name();
                }
            }
        }
        return $options;
    }
}
