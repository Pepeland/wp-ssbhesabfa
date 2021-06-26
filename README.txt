=== Hesabfa Accounting ===
Contributors: saeedsb
Tags: accounting cloud hesabfa
Requires at least: 5.2
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: 1.77.32
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect Hesabfa Online Accounting to WooCommerce.

== Description ==
This plugin helps connect your (online) store to Hesabfa online accounting software. By using this plugin, saving products, contacts, and orders in your store will also save them automatically in your Hesabfa account. Besides that, just after a client pays a bill, the receipt document will be stored in Hesabfa as well. Of course, you have to register your account in Hesabfa first. To do so, visit Hesabfa at the link here www.hesabfa.com and sign up for free. After you signed up and entered your account, choose your business, then in the settings menu/API, you can find the API keys for the business and import them to the plugin settings. Now your module is ready to use.

For more information and a full guide to how to use Hesabfa and WooCommerce Plugin, visit Hesabfa’s website and go to the “Accounting School” menu.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/hesabfa-accounting` directory, or install the hesabfa plugin through the WordPress plugins screen directly.
2. Activate the plugin through the \'Plugins\' screen in WordPress
3. Use the Settings->Hesabfa screen to configure the plugin

== Screenshots ==
1. Catalog setting page
2. Customers setting page
3. Invoice setting page
4. Payment Methods setting page
5. API setting page
6. Export setting page
7. Sync setting page

== Changelog ==
= 1.0.0 - 07.03.2020 =
* Initial stable release.

= 1.0.1 - 07.17.2020 =
* Fix invoiceSavePayment date error.
* add select in which order status add Payment and Invoice.
* limit item name length to 100 character.

= 1.0.2 - 07.17.2020 =
* change some translation strings.

= 1.0.3 - 07.19.2020 =
* use getObjectId() function.
* fix API limit request.
* fix update item before add invoice.

= 1.0.4 - 07.22.2020 =
* change 'not set!' to translatable string.
* fix 100 character limit in item name.

= 1.0.5 - 08.01.2020 =
* add a payment method (No need to set) for COD payment.

= 1.0.6 - 08.08.2020 =
* set reference in ReturnSaleInvoice
* add FiscalYear check
* add itemUpdateOpeningQuantity method
* add Return Sale invoice on canceled order status and sync orders
* add Export product opening quantity
* add validEmail function
* delete item when product deleted
* delete contact when customer deleted
* change order reference to order ID
* fix notice messages

= 1.0.7 - 04.10.2020 =
* compatible with product variations
* add ssbhesabfa_db_version option
* fix getObjectId bug

= 1.0.8 - 10.10.2020 =
* fix fiscalYear checker
* fix empty customer name bug
* fix show notice
* add GuestCustomer function
* add getContactCodeByEmail function
* add DebugMode
* fix webhook quantity change bug

= 1.0.9 - 18.10.2020 =
* fix combination price in convert currency
* fix id_attribute define in webhook
* improve lastcheck id checker

= 1.1.1 - 30.10.2020 =
* improve performance (decrease api request)
* check invoiceItems after add/edit/delete invoices
* merge some functions
* add activation status for products and customers
* fix some bugs
* fix postal code character limit
* change API tab position

= 1.1.2 - 02.11.2020 =
* add return sign on SaleInvoice
* fix syncOrders bug
* fix setContact bug
* fix get_phone on Contact Shipping Address

= 1.1.3 - 03.11.2020 =
* add limit to sync order function
* check Shareholder available on ExportProductOpeningQuantity
* improve notices
* remove customer ip on payment description
* fix syncChanges Button

= 1.1.4 - 04.11.2020 =
* use exportOpeningQuantity only one time
* fix product category path
* fix some translations
* export published and private products

= 1.1.5 - 07.11.2020 =
* fix IRR and IRT currency difference
* add ValidationClass for validate Item/Contact/Invoice fields
* add Item code field in Product/Variation
* improve log descriptions
* change Hesabfa logo
* delete Product/Variations in hesabfa when delete in WooCommerce

= 1.1.6 - 08.01.2021 =
* fix set variation bug
* fix API bulk request, Splid to 1000 item per request
* add tax to Freight

= 1.1.7 - 26.02.2021 =
* bug fix: add new item in hesabfa by updating product hesabfa code relation

= 1.2.9 - 26.02.2021 =
* bug fix: product and product variations duplication
* add log tab to settings
* prevent export products and customers if done before

= 1.2.10 - 06.03.2021 =
* bug fix: setting variation full name in hesabfa when it has more than two attributes

= 1.3.11 - 09.03.2021 =
* add update products in hesabfa based on store
* bug fix: price replaced with regular price in product export

= 1.4.11 - 27.03.2021 =
* add sync products manually feature
* add statistics to sync tab page in settings
* new menu, menu moved to main menu bar
* add icon to plugin menu

= 1.5.11 - 05.04.2021 =
* add farsi font 'Iranyekan'
* add icon to settings tab pages
* add loginToken instead of username and password for authentication

= 1.5.12 - 05.04.2021 =
* loginToken bug fixed

= 1.5.13 - 07.04.2021 =
* invoice webhook bug fixed

= 1.6.14 - 12.04.2021 =
* add tips for every action in sync and import export tab pages
* bug fix: set price for some products

= 1.6.17 - 14.04.2021 =
* improve performance of three sections: export products, sync products and opening balance

= 1.6.18 - 17.04.2021 =
* bug fix: webhook call.

= 1.7.19 - 28.04.2021 =
* bug fix: contact country and state code instead of name.
* add progress bar to export, import and sync options.
* improve export, import and sync options by make them ajax and batch.

= 1.7.23 - 08.05.2021 =
* bug fix: converting IRR to IRT non numeric error.
* bug fix: multiple invoice payment receipts.
* bug fix: delete product hook call error.
* bug fix: purchase invoice web hook error.

= 1.7.27 - 19.05.2021 =
* bug fix: minor bug fixed in getProductVariations method.
* update plugin logo and menu logo.
* add some notes and guides to some pages.
* sync changes automatically

= 1.71.29 - 12.06.2021 =
* some bugs fixed.
* add Hesabfa invoice number in order list
* add Hesabfa invoice submit button in order list

= 1.72.29 - 21.06.2021 =
* add business info in api setting tab.
* add a page to show duplicate product codes.

= 1.75.29 - 22.06.2021 =
* Show business expire alert when business is expired.
* Show alert when trying to connect plugin to another business in Hesabfa.
* set Order Payment besides invoice when click on invoice button in order list.

= 1.75.30 - 22.06.2021 =
* Remove plugin activation date error during sync orders.

= 1.75.31 - 23.06.2021 =
* check order and payment status when syncing orders.

= 1.77.32 - 26.06.2021 =
* add progress bar to export customers and export orders.
* bug fix: export base product in variable product.

== Upgrade Notice ==
Automatic updates should work smoothly, but we still recommend you back up your site.
