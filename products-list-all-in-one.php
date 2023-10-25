<?php

/**
 * Products List All in One
 *
 * Php version 7.4
 * 
 * @category  WooCommerce
 * @package   WooCommerce
 * @author    SeoSiteSoft <info@seositesoft.com>
 * @copyright 2023 SeoSiteSoft
 * @license   GPL v2 or later
 * @link      https://seositesoft.com
 *
 * @wordpress-plugin
 * Plugin Name:       Products List All in One
 * Plugin URI:        https://seositesoft.com
 * Description:       Display all products in a list with shortcode.
 * Version:           1.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * WC requires at least: 5.6
 * WC tested up to: 8.2
 * Author:            SeoSiteSoft27
 * Author URI:        https://seositesoft.com
 * Text Domain:       products-list-all-in-one
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://seositesoft.com
 */

defined('ABSPATH') or die('Keep Silent');

define('PLAO_VERSION', '1.0');

register_activation_hook(__FILE__, 'Plao_Importer_activate');
register_deactivation_hook(__FILE__, 'Plao_Importer_deactivate');

/**
 * Functions on plugin activation
 * 
 * @return Plao_Importer_activate
 */
function Plao_Importer_activate()
{
}

/**
 * Function on plugin deactivation
 * 
 * @return Plao_Importer_deactivate
 */
function Plao_Importer_deactivate()
{
}

if (!defined('PLAO_PLUGIN_FILE')) {
    define('PLAO_PLUGIN_FILE', __FILE__);
}

if (!defined('PLAO_PLUGIN_URL')) {
    define('PLAO_PLUGIN_URL', plugin_dir_url(__FILE__));
}

/** 
 * Enqueue style and script
 * 
 * @return Efpp_Enqueuing_Front_scripts
 */
function Efpp_Enqueuing_Front_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_style(
        'plao-front-style', 
        PLAO_PLUGIN_URL . '/assets/style.css?' . time()
    );
    wp_enqueue_script(
        'plao-front-script', 
        PLAO_PLUGIN_URL . '/assets/script.js?' . time(), 
        array(), 
        '', 
        true
    );
    wp_localize_script(
        'plao-front-script', 
        'plao_ajax', 
        array('ajax_url' => admin_url('admin-ajax.php'))
    );
}
add_action('wp_enqueue_scripts', 'Efpp_Enqueuing_Front_scripts');

/** 
 * Add menu for Settings
 * 
 * @return Pla_Setting_Admin_menu
 */
function Pla_Setting_Admin_menu()
{
    add_menu_page(
        __('Product List Settings', 'my-textdomain'),
        __('Product List Settings', 'my-textdomain'),
        'manage_options',
        'product-list-settings',
        'Product_List_Page_Settings_callback',
        'dashicons-schedule',
        3
    );
}
add_action('admin_menu', 'Pla_Setting_Admin_menu');

/** 
 * Setting page function
 * 
 * @return Product_List_Page_Settings_callback
 */
function Product_List_Page_Settings_callback()
{
    include_once dirname(PLAO_PLUGIN_FILE) . '/admin/settings.php';
}

/** 
 * Ajax add to cart
 * 
 * @return Plao_Shortcode_callback
 */
function Plao_Shortcode_callback()
{
    ob_start();
    include_once dirname(PLAO_PLUGIN_FILE) . '/inc/product-list-page.php';
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('ALL_PRODUCTS_LIST', 'Plao_Shortcode_callback');

require_once dirname(PLAO_PLUGIN_FILE) . '/inc/product-list-backend.php';

/** 
 * Ajax add to cart
 * 
 * @return Plao_Add_To_cart
 */
function Plao_Add_To_cart()
{
    if (isset($_POST['p_id']) 
        && !empty($_POST['p_id']) 
        && isset($_POST['p_qty']) 
        && $_POST['p_qty'] > 0 
        && $_POST['action'] == 'add_to_cart_plao'
    ) {
        $product_id = $_POST['p_id'];
        $quantity = $_POST['p_qty'];
        WC()->cart->add_to_cart($product_id, $quantity);
        $mini_cart = ob_get_clean();
        $data = array(
            'fragments' => apply_filters(
                'woocommerce_add_to_cart_fragments',
                array(
                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                )
            ),
            'cart_hash' => WC()->cart->get_cart_hash(),
        );
        wp_send_json($data);
    }
    die();
}
add_action('wp_ajax_nopriv_add_to_cart_plao', 'Plao_Add_To_cart');
add_action('wp_ajax_add_to_cart_plao', 'Plao_Add_To_cart');
