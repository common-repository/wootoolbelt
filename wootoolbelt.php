<?php
/**
 *Plugin Name: WooToolbelt
 * Plugin URI: https://watwebdev.com/wootoolbelt/
 * Description: Helpful tweaks for WooCommerce, including cart redirect, button text, cart total, related products, ordered products and product images.
 * Version: 0.7
 * Author: Watkinson Website Development
 * Author URI: https://watwebdev.com/
 * Text Domain: WooToolbelt
 * License: GPL v3
 */

/**
 * WooToolbelt
 * Copyright (C) 2016, Perfect Synergie - info@perfectsynergie.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('WOOTOOLBELT_TEXT_DOMAIN', 'WooToolbelt');
define( 'WOOTOOLBELT_INCLUDES', dirname( __FILE__ ) . '/inc' );
/**
 * plugin base class
 */


class WooToolbelt
{
    public $WooToolbelt_Settings;
    public function __construct()
    {
        //create settings page
        add_action('admin_menu', array($this, 'WooToolbelt_admin_page'));

        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'WooToolbelt_add_settings_link') );

        $this->WooToolbelt_Settings  = get_option('WooToolbelt_Settings');
        if(is_serialized($this->WooToolbelt_Settings))
        {
            $this->WooToolbelt_Settings  =   unserialize($this->WooToolbelt_Settings);
        }
        if(isset($this->WooToolbelt_Settings['WooToolbelt_cr_activate']))
        {
            add_filter('woocommerce_add_to_cart_redirect', array($this, 'WooToolbelt_add_to_cart_redirect'));
        }
        if(isset($this->WooToolbelt_Settings['WooToolbelt_drp_activate']))
        {
            add_filter('woocommerce_related_products_args', array($this, 'WooToolbelt_remove_related_products'), 10);
        }
        if(isset($this->WooToolbelt_Settings['WooToolbelt_upi_activate']))
        {
            add_filter('woocommerce_single_product_image_html', array($this, 'WooToolbelt_remove_single_product_image_html'), 10, 2);
        }
        if(isset($this->WooToolbelt_Settings['WooToolbelt_bt_activate']))
        {
            if(isset($this->WooToolbelt_Settings['WooToolbelt_prod_desc_btn']) && $this->WooToolbelt_Settings['WooToolbelt_prod_desc_btn'] != '')
            {
                add_filter( 'woocommerce_product_single_add_to_cart_text', array($this, 'WooToolbelt_custom_cart_button_text'));    // 2.1 +
            }
            if(isset($this->WooToolbelt_Settings['WooToolbelt_other_btn']) && $this->WooToolbelt_Settings['WooToolbelt_other_btn'] != '')
            {
                add_filter( 'woocommerce_product_add_to_cart_text', array($this, 'WooToolbelt_archive_custom_cart_button_text'));    // 2.1 +
            }
        }
        if(isset($this->WooToolbelt_Settings['WooToolbelt_ct_activate']))
        {
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_font_awesome' ));
            add_filter('wp_nav_menu_items', array($this, 'WooToolbelt_sk_wcmenucart'), 10, 2);
        }
        if(isset($this->WooToolbelt_Settings['WooToolbelt_doi_activate']))
        {
            add_filter( 'manage_edit-shop_order_columns', 'WooToolbelt_show_product_order',15 );
            add_action( 'manage_shop_order_posts_custom_column' , 'WooToolbelt_snv_custom_shop_order_column', 10, 2 );
        }

    }
    public  function WooToolbelt_add_settings_link($links)
    {
        $WooToolbelt_links = array(
            '<a href="' . admin_url( 'admin.php?page=WooToolbelt%2FWooToolbelt.php' ) . '">Settings</a>',
        );
        return array_merge( $links, $WooToolbelt_links );
    }
    public  function enqueue_font_awesome() {
        wp_register_style( 'WooToolbelt-switch-button', plugins_url( '/assets/font-awesome/css/font-awesome.min.css', __FILE__ ), array(), '1.0', 'screen' );
    }
    public  function WooToolbelt_admin_includes( $hook ) {

        wp_register_style( 'WooToolbelt-admin-styles', plugins_url( '/assets/WooToolbelt-styles.css', __FILE__ ), array(), '1.0', 'screen' );
        wp_register_style( 'WooToolbelt-admin-styles-ui', plugins_url( '/assets/jquery-ui.css', __FILE__ ), array(), '1.0', 'screen' );

        wp_enqueue_style( 'WooToolbelt-admin-styles-ui' );
        wp_enqueue_style( 'WooToolbelt-admin-styles' );


        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('WooToolbelt-admin-script', plugins_url('/assets/WooToolbelt-script.js', __FILE__) );
    }
    public  function WooToolbelt_add_to_cart_redirect(){
        if(isset($this->WooToolbelt_Settings['WooToolbelt_cr_choice']) && $this->WooToolbelt_Settings['WooToolbelt_cr_choice'] == 'checkout')
        {
            return get_permalink(get_option('woocommerce_checkout_page_id'));
        }
        else if(isset($this->WooToolbelt_Settings['WooToolbelt_cr_choice']) && $this->WooToolbelt_Settings['WooToolbelt_cr_choice'] == 'cart')
        {
            return get_permalink(get_option('woocommerce_cart_page_id'));
        }
        else if(isset($this->WooToolbelt_Settings['WooToolbelt_cr_choice']) && $this->WooToolbelt_Settings['WooToolbelt_cr_choice'] == 'url')
        {
            if(isset($this->WooToolbelt_Settings['WooToolbelt_cr_url']))
            {
                return $this->WooToolbelt_Settings['WooToolbelt_cr_url'];
            }
            else
            {
                return get_permalink(get_option('woocommerce_cart_page_id'));
            }

        }
    }
    public  function WooToolbelt_remove_related_products( $args ) {
        return array();
    }
    public  function WooToolbelt_remove_single_product_image_html( $html, $post_id ) {
        return get_the_post_thumbnail( $post_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
    }
    public  function WooToolbelt_archive_custom_cart_button_text() {
        return __( $this->WooToolbelt_Settings['WooToolbelt_other_btn'], 'woocommerce' );
    }
    public  function WooToolbelt_custom_cart_button_text() {
        return __( $this->WooToolbelt_Settings['WooToolbelt_prod_desc_btn'], 'woocommerce' );
    }
    public  function WooToolbelt_sk_wcmenucart($menu, $args) {

        // Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || 'primary' !== $args->theme_location )
            return $menu;

        ob_start();
        global $woocommerce;
        $viewing_cart = __('View your shopping cart', 'your-theme-slug');
        $start_shopping = __('Start shopping', 'your-theme-slug');
        $cart_url = $woocommerce->cart->get_cart_url();
        $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
        $cart_contents_count = $woocommerce->cart->cart_contents_count;
        $cart_contents = sprintf(_n('%d item', '%d items', $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
        $cart_total = $woocommerce->cart->get_cart_total();
        // Uncomment the line below to hide nav menu cart item when there are no items in the cart
        // if ( $cart_contents_count > 0 ) {
        if ($cart_contents_count == 0) {
            $menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $shop_page_url .'" title="'. $start_shopping .'">';
        } else {
            $menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $cart_url .'" title="'. $viewing_cart .'">';
        }

        $menu_item .= '<i class="fa fa-shopping-cart"></i> ';

        $menu_item .= $cart_contents.' - '. $cart_total;
        $menu_item .= '</a></li>';
        // Uncomment the line below to hide nav menu cart item when there are no items in the cart
        // }
        echo $menu_item;
        $social = ob_get_clean();
        return $menu . $social;

    }
    public  function WooToolbelt_show_product_order($columns){

        //add column
        $columns['product-display'] = __( 'Products');

        return $columns;
    }
    public  function WooToolbelt_snv_custom_shop_order_column( $column ) {
        global $post, $woocommerce, $the_order;

        switch ( $column ) {

            case 'product-display' :
                $terms = $the_order->get_items();

                if ( is_array( $terms ) ) {
                    foreach($terms as $term)
                    {
                        echo $term['item_meta']['_qty'][0] .' x ' . $term['name'] .'
';
                    }
                } else {
                    _e( 'Unable get the products', 'woocommerce' );
                }
                break;

        }
    }
    public  function WooToolbelt_admin_page(){
        $WooToolbelt_page   =   add_menu_page('WooToolbelt', 'WooToolbelt', 'manage_options', __FILE__, array($this,'WooToolbelt_page_callback'));

        add_action( 'admin_print_scripts-'.$WooToolbelt_page, array( $this, 'WooToolbelt_admin_includes' ) );
    }
    public  function WooToolbelt_page_callback()
    {

        echo '<div class="wrap">';
            _e( '<h1>WooToolbelt Settings</h1>', WOOTOOLBELT_TEXT_DOMAIN );
            require_once WOOTOOLBELT_INCLUDES.'/WooToolbelt-Settings.php';
        echo '</div>';
    }


}


$WooToolbelt_obj =   new WooToolbelt;