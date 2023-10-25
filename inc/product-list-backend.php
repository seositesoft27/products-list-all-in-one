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


/**
 * Function get product on ajax request and on load
 * 
 * @param array $search filter.
 * 
 * @return Plao_Get_products
 */
function Plao_Get_products($search)
{
    $get_settings = get_option("plao-plugin-settings", true);
    $paged = isset($search['page']) ? $search['page'] : 1;
    $range = isset($get_settings['no-products']) ? $get_settings['no-products'] : 1;
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $range,
        'paged' => $paged
    );
    if (isset($search['fbn']) && !empty($search['fbn'])) {
        $args['s'] = $search['fbn'];
    }
    if (isset($search['fbc']) && !empty($search['fbc'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $search['fbc']
        );
    }
    $args['tax_query'][] = array(
        'taxonomy' => 'product_type',
        'field'    => 'name',
        'terms'    => $get_settings['product-type'] ? 
                        $get_settings['product-type'] 
                        : 
                        array('simple', 'variable')
    );
    $loop = new WP_Query($args);
    while ($loop->have_posts()) : $loop->the_post();
        global $product;
        ?>
        <div class="plao-product-list">
            <div class="plao-proudct-list-one">
                <?php
                if ($product->is_on_sale()) {
                    echo '<span class="plao-proudct-sale">Sale</span>';
                }
                ?>
                <div class="plao-proudct-list-img">
                    <?php echo woocommerce_get_product_thumbnail(); ?>
                </div>
            </div>
            <div class="plao-proudct-list-two">
                <a 
                    class="plao-proudct-list-title" 
                    href="<?php echo get_permalink() ?>"
                >
                <?php echo get_the_title(); ?>
                </a>
                <div class="plao-proudct-list-description">
                    <?php echo $product->get_short_description(); ?>
                </div>
            </div>
            <div class="plao-proudct-list-three">
                <span class="plao-proudct-list-price">
                    <?php echo $product->get_price_html(); ?>
                </span>
                <?php
                if ($product->is_type('variable')) {
                    woocommerce_variable_add_to_cart();
                }
                if ($product->is_type('simple')) {
                    woocommerce_simple_add_to_cart();
                }
                ?>
            </div>
        </div>
        <?php
    endwhile;
    if ($loop->max_num_pages > 1) {
        echo Wds_pagination($loop->max_num_pages, $range, $paged);
    }
    wp_reset_query();
}

/**
 * Function get product on ajax request and on load
 * 
 * @param int $pages maximum pages.
 * @param int $range products range.
 * @param int $paged current page.
 * 
 * @return Wds_pagination
 */
function Wds_pagination($pages = 0, $range = 1, $paged = 1)
{
    $showitems = ($range * 2) + 1; // links to show
    // if $pages more then one post
    if (1 != $pages) {
        echo '<div class="plao-pagination">
        <span>Page ' . $paged . ' of ' . $pages . '</span>';
        // First link
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
            echo '<span data-id="' . 1 . '" class="page-num"><< First</span>';
        }
        // Previous link
        if ($paged > 1 && $showitems < $pages ) {
            echo '<span data-id"' . $paged - 1 . '" class="page-num">
            < Previous
            </span>';
        }
        // Links of pages
        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages 
                && (!($i >= $paged + $range + 1 
                || $i <= $paged - $range - 1) 
                || $pages <= $showitems) 
            ) {
                echo ($paged == $i) ? 
                '<span class="current">' . $i . '</span>' 
                : 
                '<span data-id="' . $i . '" class="page-num">' . $i .'</span>';
            }
        }
        // Next link
        if ($paged < $pages && $showitems < $pages) {
            echo '<span data-id"' . $paged + 1 . '" class="page-num">Next ></span>';
        }
        // Last link
        if ($paged < $pages - 1 
            && $paged + $range - 1 < $pages 
            && $showitems < $pages
        ) {
            echo '<span data-id="' . $pages . '" class="page-num">Last >></span>';
        }
        echo '</div>';
    }
}

/**
 * Function get product on ajax request and on load
 * 
 * @return Search_Filter_Plao_products
 */
function Search_Filter_Plao_products()
{
    if ($_POST['action'] != 'search_filter_plao') {
        echo json_encode(false);
        die();
    }
    $filters = [];
    if (isset($_POST['fbn']) && !empty($_POST['fbn'])) {
        $filters['fbn'] = $_POST['fbn'];
    }
    if (isset($_POST['fbc'])  && !empty($_POST['fbc'])) {
        $filters['fbc'] = $_POST['fbc'];
    }

    if (isset($_POST['page'])  && !empty($_POST['page'])) {
        $filters['page'] = $_POST['page'];
    }
    // print_r($filters);
    Plao_Get_products($filters);
    die();
}
add_action('wp_ajax_nopriv_search_filter_plao', 'Search_Filter_Plao_products');
add_action('wp_ajax_search_filter_plao', 'Search_Filter_Plao_products');
