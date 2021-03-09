<?php

/**
 * @class      Ssbhesabfa_Setting
 * @version    1.3.11
 * @since      1.0.0
 * @package    ssbhesabfa
 * @subpackage ssbhesabfa/admin/setting
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 */

class Ssbhesabfa_Setting
{

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init()
    {
        add_action('ssbhesabfa_home_setting', array(__CLASS__, 'ssbhesabfa_home_setting'));

        add_action('ssbhesabfa_catalog_setting', array(__CLASS__, 'ssbhesabfa_catalog_setting'));
        add_action('ssbhesabfa_catalog_setting_save_field', array(__CLASS__, 'ssbhesabfa_catalog_setting_save_field'));

        add_action('ssbhesabfa_customers_setting', array(__CLASS__, 'ssbhesabfa_customers_setting'));
        add_action('ssbhesabfa_customers_setting_save_field', array(__CLASS__, 'ssbhesabfa_customers_setting_save_field'));

        add_action('ssbhesabfa_invoice_setting', array(__CLASS__, 'ssbhesabfa_invoice_setting'));
        add_action('ssbhesabfa_invoice_setting_save_field', array(__CLASS__, 'ssbhesabfa_invoice_setting_save_field'));

        add_action('ssbhesabfa_payment_setting', array(__CLASS__, 'ssbhesabfa_payment_setting'));
        add_action('ssbhesabfa_payment_setting_save_field', array(__CLASS__, 'ssbhesabfa_payment_setting_save_field'));

        add_action('ssbhesabfa_api_setting', array(__CLASS__, 'ssbhesabfa_api_setting'));
        add_action('ssbhesabfa_api_setting_save_field', array(__CLASS__, 'ssbhesabfa_api_setting_save_field'));

        add_action('ssbhesabfa_export_setting', array(__CLASS__, 'ssbhesabfa_export_setting'));

        add_action('ssbhesabfa_sync_setting', array(__CLASS__, 'ssbhesabfa_sync_setting'));

        add_action('ssbhesabfa_log_setting', array(__CLASS__, 'ssbhesabfa_log_setting'));
    }

    public static function ssbhesabfa_home_setting()
    {
        ?>
        <h1><?php esc_attr_e('Hesabfa Accounting', 'ssbhesabfa'); ?></h1>
        <p><?php esc_attr_e('This module helps connect your (online) store to Hesabfa online accounting software. By using this module, saving products, contacts, and orders in your store will also save them automatically in your Hesabfa account. Besides that, just after a client pays a bill, the receipt document will be stored in Hesabfa as well. Of course, you have to register your account in Hesabfa first. To do so, visit Hesabfa at the link here www.hesabfa.com and sign up for free. After you signed up and entered your account, choose your business, then in the settings menu/API, you can find the API keys for the business and import them to the plugin’s settings. Now your module is ready to use.', 'ssbhesabfa'); ?></p>
        <p><?php esc_attr_e('For more information and a full guide to how to use Hesabfa and WooCommerce Plugin, visit Hesabfa’s website and go to the “Accounting School” menu.', 'ssbhesabfa'); ?></p>
        <?php
    }


    public static function ssbhesabfa_catalog_setting_fields()
    {

        $fields[] = array('title' => __('Catalog Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'catalog_options');

        $fields[] = array(
            'title' => __('Update Price', 'ssbhesabfa'),
            'desc' => __('Update Price after change in Hesabfa', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_item_update_price',
            'default' => 'no',
            'type' => 'checkbox'
        );

        $fields[] = array(
            'title' => __('Update Quantity', 'ssbhesabfa'),
            'desc' => __('Update Quantity after change in Hesabfa', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_item_update_quantity',
            'default' => 'no',
            'type' => 'checkbox'
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'catalog_options');

        return $fields;
    }

    public static function ssbhesabfa_catalog_setting()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_catalog_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        ?>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
        <?php
    }

    public static function ssbhesabfa_catalog_setting_save_field()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_catalog_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        $Html_output->save_fields($ssbhesabf_setting_fields);
    }


    public static function ssbhesabfa_customers_setting_fields()
    {

        $fields[] = array('title' => __('Customers Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'customer_options');

        $fields[] = array(
            'title' => __('Update Customer Address', 'ssbhesabfa'),
            'desc' => __('Choose when update Customer address in Hesabfa.', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_contact_address_status',
            'type' => 'select',
            'options' => array('1' => __('Use first customer address', 'ssbhesabfa'), '2' => __('update address with Invoice address', 'ssbhesabfa'), '3' => __('update address with Delivery address', 'ssbhesabfa')),
        );

        $fields[] = array(
            'title' => __('Customer\'s Group', 'ssbhesabfa'),
            'desc' => __('Enter a Customer\'s Group in Hesabfa', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_contact_node_family',
            'type' => 'text',
            'default' => 'مشتریان فروشگاه آن‌لاین'
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'customer_options');

        return $fields;
    }

    public static function ssbhesabfa_customers_setting()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_customers_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        ?>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
        <?php
    }

    public static function ssbhesabfa_customers_setting_save_field()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_customers_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        $Html_output->save_fields($ssbhesabf_setting_fields);
    }


    public static function ssbhesabfa_invoice_setting_fields()
    {
        $fields[] = array('title' => __('Invoice Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'invoice_options');

        $fields[] = array(
            'title' => __('Add invoice in which status', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_invoice_status',
            'type' => 'multiselect',
            'options' => array(
                'pending' => __('Pending payment', 'ssbhesabfa'),
                'processing' => __('Processing', 'ssbhesabfa'),
                'on-hold' => __('On hold', 'ssbhesabfa'),
                'completed' => __('Completed', 'ssbhesabfa'),
                'cancelled' => __('Cancelled', 'ssbhesabfa'),
                'refunded' => __('Refunded', 'ssbhesabfa'),
                'failed' => __('Failed', 'ssbhesabfa'),
                'checkout-draft' => __('Draft', 'ssbhesabfa'),
            ),
        );

        $fields[] = array(
            'title' => __('Return sale invoice status', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_invoice_return_status',
            'type' => 'multiselect',
            'options' => array(
                'pending' => __('Pending payment', 'ssbhesabfa'),
                'processing' => __('Processing', 'ssbhesabfa'),
                'on-hold' => __('On hold', 'ssbhesabfa'),
                'completed' => __('Completed', 'ssbhesabfa'),
                'cancelled' => __('Cancelled', 'ssbhesabfa'),
                'refunded' => __('Refunded', 'ssbhesabfa'),
                'failed' => __('Failed', 'ssbhesabfa'),
                'checkout-draft' => __('Draft', 'ssbhesabfa'),
            ),
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'invoice_options');

        return $fields;
    }

    public static function ssbhesabfa_invoice_setting()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_invoice_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        ?>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
        <?php
    }

    public static function ssbhesabfa_invoice_setting_save_field()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_invoice_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        $Html_output->save_fields($ssbhesabf_setting_fields);
    }


    public static function ssbhesabfa_payment_setting_fields()
    {
        $banks = Ssbhesabfa_Setting::ssbhesabfa_get_banks();

        $payment_gateways = new WC_Payment_Gateways;
        $available_payment_gateways = $payment_gateways->get_available_payment_gateways();

        $fields[] = array('title' => __('Payment methods Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'payment_options');

        $fields[] = array(
            'title' => __('Add payment in which status', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_payment_status',
            'type' => 'multiselect',
            'options' => array(
                'pending' => __('Pending payment', 'ssbhesabfa'),
                'processing' => __('Processing', 'ssbhesabfa'),
                'on-hold' => __('On hold', 'ssbhesabfa'),
                'completed' => __('Completed', 'ssbhesabfa'),
                'cancelled' => __('Cancelled', 'ssbhesabfa'),
                'refunded' => __('Refunded', 'ssbhesabfa'),
                'failed' => __('Failed', 'ssbhesabfa'),
                'checkout-draft' => __('Draft', 'ssbhesabfa'),
            ),
        );

        foreach ($available_payment_gateways as $gateway) {
            $fields[] = array(
                'title' => $gateway->title,
                'id' => 'ssbhesabfa_payment_method_' . $gateway->id,
                'type' => 'select',
                'options' => $banks,
            );
        }

        $fields[] = array('type' => 'sectionend', 'id' => 'payment_options');

        return $fields;
    }

    public static function ssbhesabfa_payment_setting()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_payment_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        ?>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
        <?php
    }

    public static function ssbhesabfa_payment_setting_save_field()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_payment_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        $Html_output->save_fields($ssbhesabf_setting_fields);
    }


    public static function ssbhesabfa_api_setting_fields()
    {

        $fields[] = array('title' => __('API Settings', 'ssbhesabfa'), 'type' => 'title', 'desc' => '', 'id' => 'api_options');

        $fields[] = array(
            'title' => __('Email', 'ssbhesabfa'),
            'desc' => __('Enter a Hesabfa email account', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_account_username',
            'type' => 'email',
        );

        $fields[] = array(
            'title' => __('Password', 'ssbhesabfa'),
            'desc' => __('Enter a Hesabfa password', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_account_password',
            'type' => 'password',
        );

        $fields[] = array(
            'title' => __('API Key', 'ssbhesabfa'),
            'desc' => __('Find API key in Setting->Financial Settings->API Menu', 'ssbhesabfa'),
            'id' => 'ssbhesabfa_account_api',
            'type' => 'text',
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'api_options');

        return $fields;
    }

    public static function ssbhesabfa_api_setting()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_api_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        ?>
        <form id="ssbhesabfa_form" enctype="multipart/form-data" action="" method="post">
            <?php $Html_output->init($ssbhesabf_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="ssbhesabfa_integration" class="button-primary"
                       value="<?php esc_attr_e('Save changes', 'ssbhesabfa'); ?>"/>
            </p>
        </form>
        <?php
    }

    public static function ssbhesabfa_api_setting_save_field()
    {
        $ssbhesabf_setting_fields = self::ssbhesabfa_api_setting_fields();
        $Html_output = new Ssbhesabfa_Html_output();
        $Html_output->save_fields($ssbhesabf_setting_fields);

        Ssbhesabfa_Setting::ssbhesabfa_set_webhook();
    }


    public static function ssbhesabfa_export_setting()
    {
        // Export - Bulk product export offers
        $productExportResult = (isset($_GET['productExportResult'])) ? wc_clean($_GET['productExportResult']) : null;
        $error = (isset($_GET['error'])) ? wc_clean($_GET['error']) : null;

        if (!is_null($productExportResult) && $productExportResult === 'true') {
            $processed = (isset($_GET['processed'])) ? wc_clean($_GET['processed']) : null;
            if ($processed == 0) {
                echo '<div class="updated">';
                echo '<p>' . __('No products were exported, All products were exported or there are no product', 'ssbhesabfa');
                echo '</div>';
            } else {
                echo '<div class="updated">';
                echo '<p>' . sprintf(__('Export products completed. %s products added/updated.', 'ssbhesabfa'), $processed);
                echo '</div>';
            }
        } elseif ($productExportResult === 'false') {
            if (!is_null($error) && $error === '-1') {
                echo '<div class="updated">';
                echo '<p>' . __('Export products fail. Hesabfa has already contained products.', 'ssbhesabfa');
                echo '</div>';
            } else {
                echo '<div class="updated">';
                echo '<p>' . __('Export products fail. Please check the log file.', 'ssbhesabfa');
                echo '</div>';
            }
        }

        // Export - Product opening quantity export offers
        $productOpeningQuantityExportResult = (isset($_GET['productOpeningQuantityExportResult'])) ? wc_clean($_GET['productOpeningQuantityExportResult']) : null;
        if (!is_null($productOpeningQuantityExportResult) && $productOpeningQuantityExportResult === 'true') {
            echo '<div class="updated">';
            echo '<p>' . __('Export product opening quantity completed.', 'ssbhesabfa');
            echo '</div>';
        } elseif (!is_null($productOpeningQuantityExportResult) && $productOpeningQuantityExportResult === 'false') {
            $shareholderError = (isset($_GET['shareholderError'])) ? wc_clean($_GET['shareholderError']) : null;
            $noProduct = (isset($_GET['noProduct'])) ? wc_clean($_GET['noProduct']) : null;
            if ($shareholderError == 'true') {
                echo '<div class="error">';
                echo '<p>' . __('Export product opening quantity fail. No Shareholder exists, Please define Shareholder in Hesabfa', 'ssbhesabfa');
                echo '</div>';
            } elseif ($noProduct == 'true') {
                echo '<div class="error">';
                echo '<p>' . __('No product available for Export product opening quantity.', 'ssbhesabfa');
                echo '</div>';
            } else {
                echo '<div class="error">';
                echo '<p>' . __('Export product opening quantity fail. Please check the log file.', 'ssbhesabfa');
                echo '</div>';
            }
        }

        // Export - Bulk customer export offers
        $customerExportResult = (isset($_GET['customerExportResult'])) ? wc_clean($_GET['customerExportResult']) : null;

        if (!is_null($customerExportResult) && $customerExportResult === 'true') {
            $processed = (isset($_GET['processed'])) ? wc_clean($_GET['processed']) : null;
            if ($processed == 0) {
                echo '<div class="updated">';
                echo '<p>' . __('No customers were exported, All customers were exported or there are no customer', 'ssbhesabfa');
                echo '</div>';
            } else {
                echo '<div class="updated">';
                echo '<p>' . sprintf(__('Export customers completed. %s customers added.', 'ssbhesabfa'), $processed);
                echo '</div>';
            }
        } elseif (!is_null($customerExportResult) && $customerExportResult === 'false') {
            if (!is_null($error) && $error === '-1') {
                echo '<div class="updated">';
                echo '<p>' . __('Export customers fail. Hesabfa has already contained customers.', 'ssbhesabfa');
                echo '</div>';
            } else {
                echo '<div class="updated">';
                echo '<p>' . __('Export customers fail. Please check the log file.', 'ssbhesabfa');
                echo '</div>';
            }
        }

        ?>
        <div class="notice notice-info">
            <p><?php echo __('Export can take several minutes.', 'ssbhesabfa') ?></p>
        </div>
        <br>
        <form id="ssbhesabfa_export_products" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=export'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-export-product-submit"></label>
                    <div>
                        <button class="button button-primary" id="ssbhesabfa-export-product-submit"
                                name="ssbhesabfa-export-product-submit"><?php echo __('Export Products', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p><?php echo __('Export and add all online store products to Hesabfa', 'ssbhesabfa'); ?></p>
            </div>
        </form>
        <br>
        <form id="ssbhesabfa_export_products_opening_quantity" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=export'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-export-product-opening-quantity-submit"></label>
                    <div>
                        <button class="button button-primary" id="ssbhesabfa-export-product-opening-quantity-submit"
                                name="ssbhesabfa-export-product-opening-quantity-submit"<?php if (get_option('ssbhesabfa_use_export_product_opening_quantity') == true) echo 'disabled'; ?>><?php echo __('Export Products opening quantity', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p><?php echo __('Export the products quantity and record the \'products opening quantity\' in the Hesabfa', 'ssbhesabfa'); ?></p>
            </div>
        </form>
        <br>
        <form id="ssbhesabfa_export_customers" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=export'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-export-customer-submit"></label>
                    <div>
                        <button class="button button-primary" id="ssbhesabfa-export-customer-submit"
                                name="ssbhesabfa-export-customer-submit"><?php echo __('Export Customers', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p><?php echo __('Export and add all online store customers to Hesabfa.', 'ssbhesabfa'); ?></p>
            </div>
        </form>
        <?php
    }


    public static function ssbhesabfa_sync_setting()
    {
        // Sync - Bulk changes sync offers
        $changesSyncResult = (isset($_GET['changesSyncResult'])) ? wc_clean($_GET['changesSyncResult']) : false;
        if (!is_null($changesSyncResult) && $changesSyncResult == 'true') {
            echo '<div class="updated">';
            echo '<p>' . __('Sync completed, All hesabfa changes synced successfully.', 'ssbhesabfa');
            echo '</div>';
        }

        // Sync - Bulk product sync offers
        $productSyncResult = (isset($_GET['productSyncResult'])) ? wc_clean($_GET['productSyncResult']) : null;
        if (!is_null($productSyncResult) && $productSyncResult == 'true') {
            echo '<div class="updated">';
            echo '<p>' . __('Sync completed, All products price/quantity synced successfully.', 'ssbhesabfa');
            echo '</div>';
        } elseif (!is_null($productSyncResult) && !$productSyncResult == 'false') {
            echo '<div class="error">';
            echo '<p>' . __('Sync products fail. Please check the log file.', 'ssbhesabfa');
            echo '</div>';
        }

        // Sync - Bulk invoice sync offers
        $orderSyncResult = (isset($_GET['orderSyncResult'])) ? wc_clean($_GET['orderSyncResult']) : null;

        if (!is_null($orderSyncResult) && $orderSyncResult === 'true') {
            $processed = (isset($_GET['processed'])) ? wc_clean($_GET['processed']) : null;
            echo '<div class="updated">';
            echo '<p>' . sprintf(__('Order sync completed. %s order added.', 'ssbhesabfa'), $processed);
            echo '</div>';
        } elseif (!is_null($orderSyncResult) && $orderSyncResult === 'false') {
            $fiscal = (isset($_GET['fiscal'])) ? wc_clean($_GET['fiscal']) : false;
            $activationDate = (isset($_GET['activationDate'])) ? wc_clean($_GET['activationDate']) : false;

            if ($fiscal === 'true') {
                echo '<div class="error">';
                echo '<p>' . __('The date entered is not within the fiscal year.', 'ssbhesabfa');
                echo '</div>';
            } elseif ($activationDate === 'true') {
                echo '<div class="error">';
                echo '<p>' . __('Invoices are not synced before installing the plugin.', 'ssbhesabfa');
                echo '</div>';
            } else {
                echo '<div class="error">';
                echo '<p>' . __('Cannot sync orders. Please enter valid Date format.', 'ssbhesabfa');
                echo '</div>';
            }
        }

        // Sync - Bulk product update
        $productUpdateResult = (isset($_GET['$productUpdateResult'])) ? wc_clean($_GET['$productUpdateResult']) : null;
        if (!is_null($productUpdateResult) && $productUpdateResult == 'true') {
            echo '<div class="updated">';
            echo '<p>' . __('Update completed successfully.', 'ssbhesabfa');
            echo '</div>';
        } elseif (!is_null($productUpdateResult) && !$productUpdateResult == 'false') {
            echo '<div class="error">';
            echo '<p>' . __('Update failed. Please check the log file.', 'ssbhesabfa');
            echo '</div>';
        }
        ?>

        <div class="notice notice-info">
            <p><?php echo __('Sync can take several minutes.', 'ssbhesabfa') ?></p>
        </div>

        <br>
        <form id="ssbhesabfa_sync_changes" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-sync-changes-submit"></label>
                    <div>
                        <button class="button button-primary" id="ssbhesabfa-sync-changes-submit"
                                name="ssbhesabfa-sync-changes-submit"><?php echo esc_attr_e('Sync Changes', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p><?php echo __('Sync all Hesabfa changes with Online Store.', 'ssbhesabfa'); ?></p>
            </div>
        </form>
        <br>
        <form id="ssbhesabfa_sync_products" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-sync-products-submit"></label>
                    <div>
                        <button class="button button-primary" id="ssbhesabfa-sync-products-submit"
                                name="ssbhesabfa-sync-products-submit"><?php echo __('Sync Products Quantity and Price', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p><?php echo __('Sync quantity and price of products in hesabfa with online store.', 'ssbhesabfa'); ?></p>
            </div>
        </form>
        <br>
        <form id="ssbhesabfa_sync_orders" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-sync-orders-submit"></label>
                    <div>
                        <input type="date" id="ssbhesabfa_sync_order_date" name="ssbhesabfa_sync_order_date" value=""
                               class="datepicker"/>
                        <button class="button button-primary" id="ssbhesabfa-sync-orders-submit"
                                name="ssbhesabfa-sync-orders-submit"><?php echo __('Sync Orders', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p><?php echo __('Sync/Add orders in online store with hesabfa from above date.', 'ssbhesabfa'); ?></p>
            </div>
        </form>
        <br>
        <form id="ssbhesabfa_update_products" autocomplete="off"
              action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=sync'); ?>"
              method="post">
            <div>
                <div>
                    <label for="ssbhesabfa-update-products-submit"></label>
                    <div>
                        <button class="button button-primary" id="ssbhesabfa-update-products-submit"
                                name="ssbhesabfa-update-products-submit"><?php echo __('Update Products in Hesabfa based on store', 'ssbhesabfa'); ?></button>
                    </div>
                </div>
                <p><?php echo __('Update products in hesabfa based on products definition in store.', 'ssbhesabfa'); ?></p>
            </div>
        </form>
        <?php
    }


    public static function ssbhesabfa_set_webhook()
    {
        $url = get_site_url() . '/index.php?ssbhesabfa_webhook=1&token=' . substr(wp_hash(AUTH_KEY . 'ssbhesabfa/webhook'), 0, 10);

        $hookPassword = get_option('ssbhesabfa_webhook_password');

        $ssbhesabfa_api = new Ssbhesabfa_Api();
        $response = $ssbhesabfa_api->settingSetChangeHook($url, $hookPassword);

        if (is_object($response)) {
            if ($response->Success) {
                update_option('ssbhesabfa_live_mode', 1);

                //set the last log ID if is not set
                $lastChanges = get_option('ssbhesabfa_last_log_check_id');
                $changes = $ssbhesabfa_api->settingGetChanges($lastChanges);
                if ($changes->Success) {
                    if (get_option('ssbhesabfa_last_log_check_id') == 0) {
                        $lastChange = end($changes->Result);
                        update_option('ssbhesabfa_last_log_check_id', $lastChange->Id);
                    }
                } else {
                    echo '<div class="error">';
                    echo '<p>' . __('Cannot check the last change ID. Error Message: ', 'ssbhesabfa') . $changes->ErrorMessage . '</p>';
                    echo '</div>';

                    Ssbhesabfa_Admin_Functions::log(array("Cannot get item changes. Error Message: $changes->ErrorMessage. Error Code: $changes->ErrorCode"));
                }


                //check if date in fiscalYear
                if (Ssbhesabfa_Admin_Functions::isDateInFiscalYear(date('Y-m-d H:i:s')) === 0) {
                    echo '<div class="error">';
                    echo '<p>' . __('The fiscal year has passed or not arrived. Please check the fiscal year settings in Hesabfa.', 'ssbhesabfa') . '</p>';
                    echo '</div>';

                    update_option('ssbhesabfa_live_mode', 0);
                }

                //check the Hesabfa default currency
                $default_currency = $ssbhesabfa_api->settingGetCurrency();
                if ($default_currency->Success) {
                    $woocommerce_currency = get_woocommerce_currency();
                    $hesabfa_currency = $default_currency->Result->Currency;
                    if ($hesabfa_currency == $woocommerce_currency || ($hesabfa_currency == 'IRR' && $woocommerce_currency == 'IRT') || ($hesabfa_currency == 'IRT' && $woocommerce_currency == 'IRR')) {
                        update_option('ssbhesabfa_hesabfa_default_currency', $hesabfa_currency);
                    } else {
                        update_option('ssbhesabfa_hesabfa_default_currency', 0);
                        update_option('ssbhesabfa_live_mode', 0);

                        echo '<div class="error">';
                        echo '<p>' . __('Hesabfa and WooCommerce default currency must be same.');
                        echo '</div>';
                    }
                } else {
                    echo '<div class="error">';
                    echo '<p>' . __('Cannot check the Hesabfa default currency. Error Message: ', 'ssbhesabfa') . $default_currency->ErrorMessage . '</p>';
                    echo '</div>';

                    Ssbhesabfa_Admin_Functions::log(array("Cannot check the Hesabfa default currency. Error Message: $default_currency->ErrorMessage. Error Code: $default_currency->ErrorCode"));
                }

                if (get_option('ssbhesabfa_live_mode')) {
                    echo '<div class="updated">';
                    echo '<p>' . __('API Setting updated. Test Successfully', 'ssbhesabfa') . '</p>';
                    echo '</div>';
                }
            } else {
                update_option('ssbhesabfa_live_mode', 0);

                echo '<div class="error">';
                echo '<p>' . __('Cannot set Hesabfa webHook. Error Message:', 'ssbhesabfa') . $response->ErrorMessage . '</p>';
                echo '</div>';

                Ssbhesabfa_Admin_Functions::log(array("Cannot set Hesabfa webHook. Error Message: $response->ErrorMessage. Error Code: $response->ErrorCode"));
            }
        } else {
            update_option('ssbhesabfa_live_mode', 0);

            echo '<div class="error">';
            echo '<p>' . __('Cannot connect to Hesabfa servers. Please check your Internet connection', 'ssbhesabfa') . '</p>';
            echo '</div>';

            Ssbhesabfa_Admin_Functions::log(array("Cannot connect to Hesabfa servers. Please check your Internet connection"));
        }

        return $response;
    }

    public static function ssbhesabfa_get_banks()
    {
        $ssbhesabfa_api = new Ssbhesabfa_Api();
        $banks = $ssbhesabfa_api->settingGetBanks();

        if (is_object($banks) && $banks->Success) {
            $available_banks = array();
            $available_banks[-1] = __('No need to set!', 'ssbhesabfa');
            foreach ($banks->Result as $bank) {
                if ($bank->Currency == get_woocommerce_currency() || (get_woocommerce_currency() == 'IRT' && $bank->Currency == 'IRR') || (get_woocommerce_currency() == 'IRR' && $bank->Currency == 'IRT')) {
                    $available_banks[$bank->Code] = $bank->Name . ' - ' . $bank->Branch . ' - ' . $bank->AccountNumber;
                }
            }

            if (empty($available_banks)) {
                $available_banks[0] = __('Define at least one bank in Hesabfa', 'ssbhesabfa');
            }

            return $available_banks;
        } else {
            update_option('ssbhesabfa_live_mode', 0);

            echo '<div class="error">';
            echo '<p>' . __('Cannot get Banks detail.', 'ssbhesabfa') . '</p>';
            echo '</div>';

            Ssbhesabfa_Admin_Functions::log(array("Cannot get banks detail. Error Code: $banks->ErrorCode. Error Message: $banks->ErrorMessage."));
            return array('0' => __('Cannot get Banks detail.', 'ssbhesabfa'));
        }
    }

    public static function ssbhesabfa_log_setting()
    {
        $cleanLogResult = (isset($_GET['cleanLogResult'])) ? wc_clean($_GET['cleanLogResult']) : null;

        if (!is_null($cleanLogResult) && $cleanLogResult === 'true') {
            echo '<div class="updated">';
            echo '<p>' . __('The log file was cleared.', 'ssbhesabfa') . '</p>';
            echo '</div>';
        } elseif ($cleanLogResult === 'false') {
            echo '<div class="updated">';
            echo '<p>' . __('Log file not found.', 'ssbhesabfa') . '</p>';
            echo '</div>';
        }

        self::ssbhesabfa_tab_log_html();
    }

    public static function ssbhesabfa_tab_log_html()
    {
        ?>
        <p><b><?php echo __('Events and bugs log', 'ssbhesabfa') ?></b></p>
        <br>
        <div class="flex">
            <div style="display: inline-block; ">
                <form id="ssbhesabfa_clean_log" autocomplete="off"
                      action="<?php echo admin_url('admin.php?page=ssbhesabfa-option&tab=log'); ?>"
                      method="post">
                    <div>
                        <label for="ssbhesabfa-log-clean-submit"></label>
                        <div>
                            <button class="button button-primary" id="ssbhesabfa-log-clean-submit"
                                    name="ssbhesabfa-log-clean-submit"><?php echo __('Clean log', 'ssbhesabfa'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div style="display: inline-block; margin-right: 10px;">
                <label for="ssbhesabfa-log-download-submit"></label>
                <div>
                    <a class="button button-secondary" target="_blank"
                        href="<?php echo WP_CONTENT_URL ?>/ssbhesabfa.log">
                        <?php echo __('Download log file', 'ssbhesabfa'); ?>
                    </a>
                </div>
            </div>
        </div>
        <br>
        <?php
        if (file_exists(WP_CONTENT_DIR . '/ssbhesabfa.log') &&
            (filesize(WP_CONTENT_DIR . '/ssbhesabfa.log') / 1000) > 1000) {

            $fileSizeInMb = ((filesize(WP_CONTENT_DIR . '/ssbhesabfa.log') / 1000) / 1000);
            $fileSizeInMb = round($fileSizeInMb, 2);

            $str = __('The log file size is large, clean log file.', 'ssbhesabfa');

            echo '<div class="notice notice-warning">' .
                '<p>' . $str . ' (' . $fileSizeInMb . 'MB)' . '</p>'
                . '</div>';

        } else if (file_exists(WP_CONTENT_DIR . '/ssbhesabfa.log')) {

            $logFileContent = file_get_contents(WP_CONTENT_DIR . '/ssbhesabfa.log');
            echo '<textarea rows="35"  style="width: 100%; box-sizing: border-box; direction: ltr">' . $logFileContent . '</textarea>';

        }
        ?>

        <?php
    }

}

Ssbhesabfa_Setting::init();
