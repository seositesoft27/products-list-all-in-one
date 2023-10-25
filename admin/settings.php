<?php
/**
 * Products List All in One
 * Php version 7.2
 * 
 * @category  WooCommerce
 * @package   WooCommerce
 * @author    SeoSiteSoft <info@seositesoft.com>
 * @copyright 2023 SeoSiteSoft
 * @license   GPL v2 or later
 * @link      https://seositesoft.com
 */
if (isset($_POST["save-settings"])) {
    if (isset($_POST['no-products']) && empty($_POST['no-products'])) {
        $_POST['no-products'] = 1;
    }
    update_option("plao-plugin-settings", $_POST);
}
$get_settings = get_option("plao-plugin-settings", true);
?>
<h1> <?php esc_html_e('Product List Settings', 'products-list-all-in-one'); ?> </h1>
<form action="" method="Post">
    <table class="form-table" id="fieldset-billing">
        <tbody>
            <tr>
                <th>
                    <label for="no-products"><?php esc_html_e('Show Number of Products','products-list-all-in-one') ?>:</label>
                </th>
                <td>
                    <input 
                    type="number" 
                    name="no-products" 
                    id="no-products" 
                    value="<?php 
                    echo isset($get_settings['no-products']) ? 
                    $get_settings['no-products'] : 5; 
                    ?>"
                    >
                </td>
            </tr>
            <tr>
                <th>
                    <label for="filter-enable"><?php esc_html_e('Enable Filters','products-list-all-in-one') ?>:</label>
                </th>
                <td>
                    <input 
                    type="checkbox" 
                    name="filter-enable" 
                    id="filter-enable" 
                    value="1" 
                    <?php echo isset($get_settings['filter-enable']) 
                    && $get_settings['filter-enable'] ? 
                    'checked' : 
                    '';  ?>
                    >
                </td>
            </tr>
            <tr>
                <th>
                    <label for="product-type"><?php esc_html_e('Display Product Type','products-list-all-in-one') ?>:</label>
                </th>
                <td>
                    <label for="product-type-simple"><?php esc_html_e('Simple','products-list-all-in-one') ?> 
                        <input type="checkbox" 
                        name="product-type[]" 
                        id="product-type-simple" value="simple" 
                        <?php echo isset($get_settings['product-type']) 
                        && in_array('simple', $get_settings['product-type']) 
                        ? 'checked' 
                        : '';  ?>>
                    </label>
                    <label for="product-type-variable">Variable 
                        <input type="checkbox" 
                        name="product-type[]" 
                        id="product-type-variable" value="variable" 
                        <?php echo isset($get_settings['product-type']) 
                        && in_array('variable', $get_settings['product-type']) 
                        ? 'checked' 
                        : '';  ?>>
                    </label>
                </td>
            </tr>
            <tr>
                <th colspan="2">
                    <button type="submit" name="save-settings" class="button">
                        <?php esc_html_e('Save','products-list-all-in-one') ?>
                    </button>
                </th>
            </tr>
        </tbody>
    </table>
</form>