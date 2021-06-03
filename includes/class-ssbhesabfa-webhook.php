<?php

/*
 * @package    ssbhesabfa
 * @subpackage ssbhesabfa/includes
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 * @author     HamidReza Gharahzadeh <hamidprime@gmail.com>
 */

class Ssbhesabfa_Webhook
{
    public $invoicesObjectId = array();
    public $invoiceItemsCode = array();
    public $itemsObjectId = array();
    public $contactsObjectId = array();

    public function __construct()
    {
        Ssbhesabfa_Admin_Functions::logDebugStr("===== Webhook Called =====");

        $hesabfaApi = new Ssbhesabfa_Api();
        $lastChange = get_option('ssbhesabfa_last_log_check_id');
        $changes = $hesabfaApi->settingGetChanges($lastChange + 1);
        if ($changes->Success) {
            foreach ($changes->Result as $item) {
                if (!$item->API) {
                    switch ($item->ObjectType) {
                        case 'Invoice':
                            $this->invoicesObjectId[] = $item->ObjectId;
                            foreach (explode(',', $item->Extra) as $invoiceItem) {
                                if ($invoiceItem != '') {
                                    $this->invoiceItemsCode[] = $invoiceItem;
                                }
                            }
                            break;
                        case 'Product':
                            //if Action was deleted
                            if ($item->Action == 53) {
                                $id_obj = Ssbhesabfa_Admin_Functions::getObjectIdByCode('product', $item->Extra);
                                global $wpdb;
                                $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id' => $id_obj));
                                break;
                            }
                            $this->itemsObjectId[] = $item->ObjectId;
                            break;
                        case 'Contact':
                            //if Action was deleted
                            if ($item->Action == 33) {
                                $id_obj = Ssbhesabfa_Admin_Functions::getObjectIdByCode('customer', $item->Extra);
                                global $wpdb;
                                $wpdb->delete($wpdb->prefix . 'ssbhesabfa', array('id' => $id_obj));
                                break;
                            }

                            $this->contactsObjectId[] = $item->ObjectId;
                            break;
                    }

//                    switch ($item->Action) {
//                        case '101':
//                            $func = new Ssbhesabfa_Admin_Functions();
//                            $func->syncProducts();
//                    }
                }
            }

            //remove duplicate values
            $this->invoiceItemsCode = array_unique($this->invoiceItemsCode);
            $this->contactsObjectId = array_unique($this->contactsObjectId);
            $this->itemsObjectId = array_unique($this->itemsObjectId);
            $this->invoicesObjectId = array_unique($this->invoicesObjectId);

            $this->setChanges();

            //set LastChange ID
            $lastChange = end($changes->Result);
            if (is_object($lastChange)) {
                update_option('ssbhesabfa_last_log_check_id', $lastChange->Id);
            }
        } else {
            Ssbhesabfa_Admin_Functions::log(array("ssbhesabfa - Cannot check last changes. Error Message: " . (string)$changes->ErrorMessage . ". Error Code: " . (string)$changes->ErrorCode));

            return false;
        }

        return true;
    }

    public function setChanges()
    {
        //Invoices
        if (!empty($this->invoicesObjectId)) {
            $invoices = $this->getObjectsByIdList($this->invoicesObjectId, 'invoice');
            if ($invoices != false) {
                foreach ($invoices as $invoice) {
                    $this->setInvoiceChanges($invoice);
                }
            }
        }

        //Contacts
        if (!empty($this->contactsObjectId)) {
            $contacts = $this->getObjectsByIdList(array_unique($this->contactsObjectId), 'contact');
            if ($contacts != false) {
                foreach ($contacts as $contact) {
                    $this->setContactChanges($contact);
                }
            }
        }

        //Items
        $items = array();

        if (!empty($this->itemsObjectId)) {
            $objects = $this->getObjectsByIdList($this->itemsObjectId, 'item');
            if ($objects != false) {
                foreach ($objects as $object) {
                    array_push($items, $object);
                }
            }
        }

        if (!empty($this->invoiceItemsCode)) {
            $objects = $this->getObjectsByCodeList($this->invoiceItemsCode);

            if ($objects != false) {
                foreach ($objects as $object) {
                    array_push($items, $object);
                }
            }
        }

        if (!empty($items)) {
            foreach ($items as $item) {
                $this->setItemChanges($item);
            }
        }

        return true;
    }

    // use in webhook call when invoice change
    public function setInvoiceChanges($invoice)
    {
        if (!is_object($invoice)) {
            return false;
        }

        //1.set new Hesabfa Invoice Code if changes
        $number = $invoice->Number;
        $json = json_decode($invoice->Tag);
        if (is_object($json)) {
            $id_order = $json->id_order;
        } else {
            $id_order = 0;
        }

        if ($invoice->InvoiceType == 0) {
            //check if Tag not set in hesabfa
            if ($id_order == 0) {
                Ssbhesabfa_Admin_Functions::log(array("This invoice is not define in OnlineStore. Order Number: " . $number));
            } else {
                //check if order exist in wooCommerce
                $id_obj = Ssbhesabfa_Admin_Functions::getObjectId('order', $id_order);
                if ($id_obj != false) {
                    global $wpdb;
                    $row = $wpdb->get_row("SELECT `id_hesabfa` FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id` = $id_obj");
                    if (is_object($row) && $row->id_hesabfa != $number) {
                        $id_hesabfa_old = $row->id_hesabfa;
                        //ToDo: number must int in hesabfa, what can i do
                        $wpdb->update($wpdb->prefix . 'ssbhesabfa', array('id_hesabfa' => $number), array('id' => $id_obj));

                        Ssbhesabfa_Admin_Functions::log(array("Invoice Number changed. Old Number: $id_hesabfa_old. New ID: $number"));
                    }
                }
            }
        }
    }

    // use in webhook call when contact change
    public function setContactChanges($contact)
    {
        if (!is_object($contact)) {
            return false;
        }

        //1.set new Hesabfa Contact Code if changes
        $code = $contact->Code;

        $json = json_decode($contact->Tag);
        if (is_object($json)) {
            $id_customer = $json->id_customer;
        } else {
            $id_customer = 0;
        }

        //check if Tag not set in hesabfa
        if ($id_customer == 0) {
            Ssbhesabfa_Admin_Functions::log(array("This Customer is not define in OnlineStore. Customer code: $code"));

            return false;
        }

        //check if customer exist in prestashop
        $id_obj = Ssbhesabfa_Admin_Functions::getObjectId('customer', $id_customer);
        if ($id_obj != false) {
            global $wpdb;
            $row = $wpdb->get_row("SELECT `id_hesabfa` FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id` = $id_obj");

            if (is_object($row) && $row->id_hesabfa != $code) {
                $id_hesabfa_old = $row->id_hesabfa;
                $wpdb->update($wpdb->prefix . 'ssbhesabfa', array('id_hesabfa' => (int)$code), array('id' => $id_obj));

                Ssbhesabfa_Admin_Functions::log(array("Contact Code changed. Old ID: $id_hesabfa_old. New ID: $code"));
            }
        }

        return true;
    }

    public static function setItemChanges($item)
    {
        if (!is_object($item)) {
            return false;
        }

        $id_product = 0;
        $id_attribute = 0;

        $jsonObj = json_decode($item->Tag);
        if (is_object($jsonObj)) {
            $id_product = $jsonObj->id_product;
            if (isset($jsonObj->id_attribute)) {
                $id_attribute = $jsonObj->id_attribute;
            }
        } else return false;

        //check if Tag not set in hesabfa
        if ($id_product == 0) {
            Ssbhesabfa_Admin_Functions::log(array("Item with code: $item->Code is not define in OnlineStore"));
            return false;
        }

        //check if product exist in woocommerce
        $id_obj = Ssbhesabfa_Admin_Functions::getObjectId('product', $id_product, $id_attribute);
        if ($id_obj) {
            global $wpdb;
            $found = $wpdb->get_var("SELECT COUNT(*) FROM `" . $wpdb->prefix . "posts`                                                                
                                WHERE ID = $id_product AND post_status IN('publish','private')");

            if(!$found) {
                Ssbhesabfa_Admin_Functions::logDebugStr("product not found in woocommerce. product id: $id_product, code in Hesabfa: $item->Code");
                $wpdb->delete($wpdb->prefix.'ssbhesabfa', array('id_hesabfa' => $item->Code, 'obj_type' => 'product'));
                return false;
            }

            $product = new WC_Product($id_product);

            //1.set new Hesabfa Item Code if changes
            $row = $wpdb->get_row("SELECT `id_hesabfa` FROM `" . $wpdb->prefix . "ssbhesabfa` WHERE `id` = $id_obj");

            if (is_object($row) && $row->id_hesabfa != $item->Code) {
                $id_hesabfa_old = $row->id_hesabfa;
                //update all variation
                $wpdb->update($wpdb->prefix . 'ssbhesabfa', array('id_hesabfa' => (int)$item->Code), array('id_ps' => $id_product, 'obj_type' => 'product'));
                Ssbhesabfa_Admin_Functions::log(array("Item Code changed. Old ID: $id_hesabfa_old. New ID: $item->Code"));
            }

            //2.set new Price
            if (get_option('ssbhesabfa_item_update_price') == 'yes') {
                if ($id_attribute != 0) {
                    $variation = new WC_Product_Variation($id_attribute);
                    $price = Ssbhesabfa_Admin_Functions::getPriceInHesabfaDefaultCurrency($variation->get_regular_price());
                    if ($item->SellPrice != $price) {
                        $old_price = $variation->get_regular_price();
                        $new_price = Ssbhesabfa_Admin_Functions::getPriceInWooCommerceDefaultCurrency($item->SellPrice);
                        update_post_meta($id_attribute, '_price', $new_price);
                        update_post_meta($id_attribute, '_regular_price', $new_price);

                        Ssbhesabfa_Admin_Functions::log(array("product ID $id_product-$id_attribute Price changed. Old Price: $old_price. New Price: $item->SellPrice"));
                    }
                } else {
                    $price = Ssbhesabfa_Admin_Functions::getPriceInHesabfaDefaultCurrency($product->get_regular_price());
                    if ($item->SellPrice != $price) {
                        $old_price = $product->get_regular_price();
                        $new_price = Ssbhesabfa_Admin_Functions::getPriceInWooCommerceDefaultCurrency($item->SellPrice);
                        update_post_meta($id_product, '_price', $new_price);
                        update_post_meta($id_product, '_regular_price', $new_price);

                        Ssbhesabfa_Admin_Functions::log(array("product ID $id_product Price changed. Old Price: $old_price. New Price: $item->SellPrice"));
                    }
                }
            }

            //3.set new Quantity
            if (get_option('ssbhesabfa_item_update_quantity') == 'yes') {
                if ($id_attribute != 0) {
                    $variation = new WC_Product_Variation($id_attribute);
                    if ($item->Stock != $variation->get_stock_quantity()) {
                        $old_quantity = $variation->get_stock_quantity();
                        $new_quantity = $item->Stock;

                        $new_stock_status = ($new_quantity > 0) ? "instock" : "outofstock";
                        update_post_meta($id_attribute, '_stock', $new_quantity);
                        wc_update_product_stock_status($id_attribute, $new_stock_status);

                        Ssbhesabfa_Admin_Functions::log(array("product ID $id_product-$id_attribute quantity changed. Old qty: $old_quantity. New qty: $item->Stock"));
                    }
                } else {
                    if ($item->Stock != $product->get_stock_quantity()) {
                        $old_quantity = $product->get_stock_quantity();
                        $new_quantity = $item->Stock;

                        $new_stock_status = ($new_quantity > 0) ? "instock" : "outofstock";
                        update_post_meta($id_product, '_stock', $new_quantity);
                        wc_update_product_stock_status($id_product, $new_stock_status);

                        Ssbhesabfa_Admin_Functions::log(array("product ID $id_product quantity changed. Old qty: $old_quantity. New qty: $item->Stock"));
                    }
                }
            }
        }
    }

    public function getObjectsByIdList($idList, $type)
    {
        $hesabfaApi = new Ssbhesabfa_Api();
        switch ($type) {
            case 'item':
                $result = $hesabfaApi->itemGetById($idList);
                break;
            case 'contact':
                $result = $hesabfaApi->contactGetById($idList);
                break;
            case 'invoice':
                $result = $hesabfaApi->invoiceGetByIdList($idList);
                break;
            default:
                return false;
        }

        if (is_object($result) && $result->Success) {
            return $result->Result;
        }

        return false;
    }

    public function getObjectsByCodeList($codeList)
    {

        $filters = array(array("Property" => "Code", "Operator" => "in", "Value" => $codeList));
        $hesabfaApi = new Ssbhesabfa_Api();

        $result = $hesabfaApi->itemGetItems(array('Take' => 100000, 'Filters' => $filters));

        //$result = $hesabfaApi->itemGetItems($queryInfo);

        if (is_object($result) && $result->Success) {
            return $result->Result->List;
        }

        return false;
    }
}
