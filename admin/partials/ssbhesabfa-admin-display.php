<?php

/**
 * @class      Ssbhesabfa_Admin_Display
 * @version    1.3.11
 * @since      1.0.0
 * @package    ssbhesabfa
 * @subpackage ssbhesabfa/admin/display
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 */

class Ssbhesabfa_Admin_Display {

    /**
    * Hook in methods
    * @since    1.0.0
    * @access   static
    */
    public function init() {
        //add_action('admin_menu', array(__CLASS__, 'hesabfa_add_settings_menu'));
        add_action('admin_menu', array(__CLASS__, 'hesabfa_add_menu'));
    }

    /**
    * @since    1.0.0
    * @access   public
    */
    public static function hesabfa_add_settings_menu() {
        //add_options_page(__('Hesabfa Options', 'ssbhesabfa'), __('Hesabfa', 'ssbhesabfa'), 'manage_options', 'ssbhesabfa-option', array(__CLASS__, 'ssbhesabfa_option'));
    }

    function hesabfa_add_menu() {
        $iconUrl = plugins_url( '/hesabfa-accounting/admin/img/menu-icon.png');
        add_menu_page( "حسابفا", "حسابفا", "manage_options", "ssbhesabfa-option", array(__CLASS__, 'hesabfa_plugin_page'), $iconUrl, null);
        add_submenu_page("ssbhesabfa-option", "همسان سازی دستی کالاها", "همسان سازی دستی کالاها", "manage_options", 'hesabfa-sync-products-manually', array(__CLASS__, 'hesabfa_plugin_sync_products_manually') );
    }

    function hesabfa_plugin_sync_products_manually() {
        $result = self::getProductsAndRelations();
        $i = 0;

        ?>
            <p class="mt-4">
                <b> همسان سازی دستی کالاهای فروشگاه با حسابفا </b>
            </p>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">نام کالا</th>
                <th scope="col">شناسه محصول</th>
                <th scope="col">کد حسابفا</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($result as $p):
                $i++; ?>
                <tr class="<?= $p->id_hesabfa ? 'table-success' : 'table-danger'; ?>">
                    <th scope="row"><?= $i; ?></th>
                    <td><?= $p->ID; ?></td>
                    <td><?= $p->post_title; ?></td>
                    <td><?= $p->sku; ?></td>
                    <td>
                        <input type="text" class="form-control" id="<?= $p->ID; ?>" value="<?= $p->id_hesabfa; ?>" style="width: 100px">
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php
    }

    public static function getProductsAndRelations() {
        global $wpdb;
        $row = $wpdb->get_results("SELECT post.ID,post.post_title,wc.sku FROM `".$wpdb->prefix."posts` as post
                                LEFT JOIN `".$wpdb->prefix."wc_product_meta_lookup` as wc
                                ON post.id =  wc.product_id                                
                                WHERE post.post_type IN('product','product_variation')");

        $links = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."ssbhesabfa`                              
                                WHERE obj_type ='product'");

        foreach ($links as $link) {
            foreach ($row as $r) {
                if($r->ID == $link->id_ps && $link->id_ps_attribute == 0) {
                    $r->id_hesabfa = $link->id_hesabfa;
                } else if($r->ID == $link->id_ps_attribute) {
                    $r->id_hesabfa = $link->id_hesabfa;
                }
            }
        }

        //Ssbhesabfa_Admin_Functions::logDebugStr("count: " . count($row));
        //Ssbhesabfa_Admin_Functions::logDebugObj($row);
        return $row;
    }


    /**
    * @since    1.0.0
    * @access   public
    */
    public static function hesabfa_plugin_page() {
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $setting_tabs = apply_filters('ssbhesabfa_setting_tab', array(
                'home' => __('Home', 'ssbhesabfa'),
                'api' => __('API', 'ssbhesabfa'),
                'catalog' => __('Catalog', 'ssbhesabfa'),
                'customers' => __('Customers', 'ssbhesabfa'),
                'invoice' => __('Invoice', 'ssbhesabfa'),
                'payment' => __('Payment Methods', 'ssbhesabfa'),
                'export' => __('Export', 'ssbhesabfa'),
                'sync' => __('Sync', 'ssbhesabfa'),
                'log' => __('Log', 'ssbhesabfa')
            ));
            $current_tab = (isset($_GET['tab'])) ? wc_clean($_GET['tab']) : 'home';
            ?>
            <h2 class="nav-tab-wrapper">
                <?php
                foreach ($setting_tabs as $name => $label)
                    echo '<a href="' . admin_url('admin.php?page=ssbhesabfa-option&tab=' . $name) . '" class="nav-tab ' . ($current_tab == $name ? 'nav-tab-active' : '') . '">' . $label . '</a>';
                ?>
            </h2>
            <?php
            foreach ($setting_tabs as $setting_tabkey => $setting_tabvalue) {
                switch ($setting_tabkey) {
                    case $current_tab:
                        do_action('ssbhesabfa_' . $setting_tabkey . '_setting_save_field');
                        do_action('ssbhesabfa_' . $setting_tabkey . '_setting');
                        break;
                }
            }
        } else {
            echo '<div class="wrap">' . __('Hesabfa Plugin requires the WooCommerce to work!, Please install/activate woocommerce and try again', 'ssbhesabfa') . '</div>';
        }
    }
}

Ssbhesabfa_Admin_Display::init();
