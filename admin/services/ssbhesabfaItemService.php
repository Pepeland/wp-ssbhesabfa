<?php


class ssbhesabfaItemService
{
    public static function mapProduct($product, $id, $new = true) {
        $categories = $product->get_category_ids();
        $code = $new ? null : self::getItemCodeByProductId($id) ;
        $price = $product->get_regular_price() ? $product->get_regular_price() : $product->get_price();

        $hesabfaItem = array(
            'Code' => $code,
            'Name' => Ssbhesabfa_Validation::itemNameValidation($product->get_title()),
            'PurchasesTitle' => Ssbhesabfa_Validation::itemNameValidation($product->get_title()),
            'SalesTitle' => Ssbhesabfa_Validation::itemNameValidation($product->get_title()),
            'ItemType' => $product->is_virtual() == 1 ? 1 : 0,
            'Barcode' => Ssbhesabfa_Validation::itemBarcodeValidation($product->get_sku()),
            'Tag' => json_encode(array('id_product' => $id, 'id_attribute' => 0)),
            'NodeFamily' => self::getCategoryPath($categories[0]),
            'ProductCode' => $id,
            'SellPrice' => self::getPriceInHesabfaDefaultCurrency($price)
        );
        return $hesabfaItem;
    }

    public static function mapProductVariation($product, $variation, $id_product, $new = true) {
        $id_attribute = $variation->get_id();
        $categories = $product->get_category_ids();
        $code = $new ? null : self::getItemCodeByProductId($id_product, $id_attribute);

        $productName = $product->get_title();
        $variationName = $variation->get_attribute_summary();
        $fullName = Ssbhesabfa_Validation::itemNameValidation($productName . ' - ' . $variationName);
        $price = $variation->get_regular_price() ? $variation->get_regular_price() : $variation->get_price();

        $hesabfaItem = array(
            'Code' => $code,
            'Name' => $fullName,
            'PurchasesTitle' => $fullName,
            'SalesTitle' => $fullName,
            'ItemType' => $variation->is_virtual() == 1 ? 1 : 0,
            'Barcode' => Ssbhesabfa_Validation::itemBarcodeValidation($variation->get_sku()),
            'Tag' => json_encode(array(
                'id_product' => $id_product,
                'id_attribute' => $id_attribute
            )),
            'NodeFamily' => self::getCategoryPath($categories[0]),
            'ProductCode' => $id_attribute,
            'SellPrice' => self::getPriceInHesabfaDefaultCurrency($price)
        );

        return $hesabfaItem;
    }

    public static function getItemCodeByProductId($id_product, $id_attribute = 0)
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT `id_hesabfa` FROM " . $wpdb->prefix . "ssbhesabfa WHERE `id_ps` = $id_product AND `id_ps_attribute` = $id_attribute AND `obj_type` = 'product'");

        if (is_object($row)) {
            return (int)$row->id_hesabfa;
        } else {
            return null;
        }
    }

    public static function getPriceInHesabfaDefaultCurrency($price)
    {
        if (!isset($price)) {
            return false;
        }

        $woocommerce_currency = get_woocommerce_currency();
        $hesabfa_currency = get_option('ssbhesabfa_hesabfa_default_currency');

        if (!is_numeric($price)) {
            $price = intval($price);
        }

        if ($hesabfa_currency == 'IRR' && $woocommerce_currency == 'IRT') {
            $price *= 10;
        }

        if ($hesabfa_currency == 'IRT' && $woocommerce_currency == 'IRR') {
            $price /= 10;
        }

        return $price;
    }

    public static function getCategoryPath($id_category)
    {
        if (!isset($id_category))
            return '';

        $path = get_term_parents_list($id_category, 'product_cat', array(
            'format' => 'name',
            'separator' => ':',
            'link' => false,
            'inclusive' => true,
        ));

        return substr('products: ' . $path, 0, -1);
    }

}