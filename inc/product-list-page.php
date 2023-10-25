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
$terms = get_terms(
    array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
    )
);
$get_settings = get_option("plao-plugin-settings", true);
?>
<div class="plao-wrapper">
    <div class="loader-plao">
        <div class="loader-plao-inner">
            <img 
            src="<?php echo PLAO_PLUGIN_URL ?>/assets/img/p-loader.gif" 
            class="plao-loader-img" 
            />
        </div>
    </div>
    <h2 align="center"><?php esc_html_e('Product List','products-list-all-in-one') ?></h2>
    <div class="plao-filters-wrapper">
    </div>
    <div class="plao-body-wrapper">
        <div class="plao-body-inner">
            <?php 
            if (isset($get_settings['filter-enable']) 
                && $get_settings['filter-enable'] == 1
            ) : ?>
                <div class="plao-filters">
                    <h2 align="center"><?php esc_html_e('Filters','products-list-all-in-one') ?></h2>
                    <form action="" method="POST" id="plaofilterform">
                        <div class="plao-filterbyname">
                            <label for="fbn"><?php esc_html_e('Search by Name','products-list-all-in-one') ?></label>
                            <input 
                            type="text" 
                            name="fbn" 
                            id="fbn" 
                            class="plao-fbn" 
                            placeholder="Search..."
                            />
                        </div>
                        <div class="plao-filterbycat">
                            <label for="fbc"><?php esc_html_e('Search by Category','products-list-all-in-one') ?></label>
                            <select name="fbc" id="fbc" class="plao-fbc">
                                <option value=""><?php esc_html_e('Category','products-list-all-in-one') ?></option>
                                <?php
                                if ($terms) {
                                    foreach ($terms as $term) {
                                        echo '<option value="' . $term->term_id . '">
                                        ' . $term->name . '
                                        </option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button class="plaofilterform" type="reset"><?php esc_html_e('Reset','products-list-all-in-one') ?></button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="plao-body-products">
                <?php
                Plao_Get_products('');
                ?>
            </div>
            <div class="plao-cart">
            </div>
        </div>
    </div>
</div>