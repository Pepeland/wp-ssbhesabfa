jQuery(function ($) {
    $('#syncProductsProgress').hide();
    $('#exportProductsProgress').hide();
    $('#importProductsProgress').hide();
    $('#exportProductsOpeningQuantityProgress').hide();
    $('#updateProductsProgress').hide();
    'use strict';
    $(function () {
        // AJAX - Export Products
        $('#ssbhesabfa_export_products').submit(function () {
            // show processing status
            $('#ssbhesabfa-export-product-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-export-product-submit').removeClass('button-primary');
            $('#ssbhesabfa-export-product-submit').html('<i class="ofwc-spinner"></i> Exporting, please wait...');
            $('#ssbhesabfa-export-product-submit i.spinner').show();

            $('#exportProductsProgress').show();
            $('#exportProductsProgressBar').css('width', 0 + '%').attr('aria-valuenow', 0);

            exportProducts(1, 1, 1);

            return false;
        });
    });

    function exportProducts(batch, totalBatch, total) {
        const data = {
            'action': 'adminExportProducts',
            'batch': batch,
            'totalBatch': totalBatch,
            'total': total
        };
        $.post(ajaxurl, data, function (response) {
            if (response !== 'failed') {
                const res = JSON.parse(response);
                res.batch = parseInt(res.batch);
                if (res.batch < res.totalBatch) {
                    let progress = (res.batch * 100) / res.totalBatch;
                    progress = Math.round(progress);
                    $('#exportProductsProgressBar').css('width', progress + '%').attr('aria-valuenow', progress);
                    exportProducts(res.batch + 1, res.totalBatch, res.total);
                    return false;
                } else {
                    $('#exportProductsProgressBar').css('width', 100 + '%').attr('aria-valuenow', 100);
                    setTimeout(() => {
                        top.location.replace(res.redirectUrl);
                    }, 1000);
                    return false;
                }
            } else {
                alert('Error exporting products.');
                return false;
            }
        });
    }

    $(function () {
        // AJAX - Import Products
        $('#ssbhesabfa_import_products').submit(function () {
            // show processing status
            $('#ssbhesabfa-import-product-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-import-product-submit').removeClass('button-primary');
            $('#ssbhesabfa-import-product-submit').html('<i class="ofwc-spinner"></i> در حال ورود کالاها از حسابفا, لطفاً صبر کنید...');
            $('#ssbhesabfa-import-product-submit i.spinner').show();

            $('#importProductsProgress').show();
            $('#importProductsProgressBar').css('width', 0 + '%').attr('aria-valuenow', 0);

            importProducts(1, 1, 1);

            return false;
        });
    });

    function importProducts(batch, totalBatch, total) {
        var data = {
            'action': 'adminImportProducts',
            'batch': batch,
            'totalBatch': totalBatch,
            'total': total
        };
        $.post(ajaxurl, data, function (response) {
            if ('failed' !== response) {
                const res = JSON.parse(response);
                res.batch = parseInt(res.batch);
                if (res.batch < res.totalBatch) {
                    let progress = (res.batch * 100) / res.totalBatch;
                    progress = Math.round(progress);
                    $('#importProductsProgressBar').css('width', progress + '%').attr('aria-valuenow', progress);
                    //alert('batch: ' + res.batch + ', totalBatch: ' + res.totalBatch + ', total: ' + res.total);
                    importProducts(res.batch + 1, res.totalBatch, res.total);
                    return false;
                } else {
                    $('#importProductsProgressBar').css('width', 100 + '%').attr('aria-valuenow', 100);
                    setTimeout(() => {
                        top.location.replace(res.redirectUrl);
                    }, 1000);
                    return false;
                }
            } else {
                alert('Error importing products.');
                return false;
            }
        });
    }

    $(function () {
        // AJAX - Export Products opening quantity
        $('#ssbhesabfa_export_products_opening_quantity').submit(function () {
            // show processing status
            $('#ssbhesabfa-export-product-opening-quantity-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-export-product-opening-quantity-submit').removeClass('button-primary');
            $('#ssbhesabfa-export-product-opening-quantity-submit').html('<i class="ofwc-spinner"></i> Exporting, please wait...');
            $('#ssbhesabfa-export-product-opening-quantity-submit i.spinner').show();

            $('#exportProductsOpeningQuantityProgress').show();
            $('#exportProductsOpeningQuantityProgressBar').css('width', 0 + '%').attr('aria-valuenow', 0);

            exportProductsOpeningQuantity(1, 1, 1);

            return false;
        });
    });

    function exportProductsOpeningQuantity(batch, totalBatch, total) {
        var data = {
            'action': 'adminExportProductsOpeningQuantity',
            'batch': batch,
            'totalBatch': totalBatch,
            'total': total
        };
        $.post(ajaxurl, data, function (response) {
            if ('failed' !== response) {
                const res = JSON.parse(response);
                res.batch = parseInt(res.batch);
                if (res.batch < res.totalBatch) {
                    let progress = (res.batch * 100) / res.totalBatch;
                    progress = Math.round(progress);
                    $('#exportProductsOpeningQuantityProgressBar').css('width', progress + '%').attr('aria-valuenow', progress);
                    exportProductsOpeningQuantity(res.batch + 1, res.totalBatch, res.total);
                    return false;
                } else {
                    $('#exportProductsOpeningQuantityProgressBar').css('width', 100 + '%').attr('aria-valuenow', 100);
                    setTimeout(() => {
                        top.location.replace(res.redirectUrl);
                    }, 1000);
                    return false;
                }
            } else {
                alert('Error exporting products opening quantity.');
                return false;
            }
        });
    }

    $(function () {
        // AJAX - Export Customers
        $('#ssbhesabfa_export_customers').submit(function () {
            // show processing status
            $('#ssbhesabfa-export-customer-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-export-customer-submit').removeClass('button-primary');
            $('#ssbhesabfa-export-customer-submit').html('<i class="ofwc-spinner"></i> Exporting, please wait...');
            $('#ssbhesabfa-export-customer-submit i.spinner').show();

            var data = {
                'action': 'adminExportCustomers'
            };

            // post it
            $.post(ajaxurl, data, function (response) {
                if ('failed' !== response) {
                    var redirectUrl = response;

                    /** Debug **/
                    // console.log(redirectUrl);
                    // return false;

                    top.location.replace(redirectUrl);
                    return false;
                } else {
                    alert('Error exporting customers.');
                    return false;
                }
            });
            /*End Post*/
            return false;
        });
    });

    $(function () {
        // AJAX - Sync Changes
        $('#ssbhesabfa_sync_changes').submit(function () {
            // show processing status
            $('#ssbhesabfa-sync-changes-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-sync-changes-submit').removeClass('button-primary');
            $('#ssbhesabfa-sync-changes-submit').html('<i class="ofwc-spinner"></i> Syncing, please wait...');
            $('#ssbhesabfa-sync-changes-submit i.spinner').show();

            var data = {
                'action': 'adminSyncChanges'
            };

            // post it
            $.post(ajaxurl, data, function (response) {
                if ('failed' !== response) {
                    var redirectUrl = response;

                    /** Debug **/
                    // console.log(redirectUrl);
                    // return false;

                    top.location.replace(redirectUrl);
                    return false;
                } else {
                    alert('Error syncing changes.');
                    return false;
                }
            });
            /*End Post*/
            return false;
        });
    });

    $(function () {
        // AJAX - Sync Products
        $('#ssbhesabfa_sync_products').submit(function () {

            // show processing status
            $('#ssbhesabfa-sync-products-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-sync-products-submit').removeClass('button-primary');
            $('#ssbhesabfa-sync-products-submit').html('<i class="ofwc-spinner"></i> Syncing, please wait...');
            $('#ssbhesabfa-sync-products-submit i.spinner').show();

            $('#syncProductsProgress').show();
            $('#syncProductsProgressBar').css('width', 0 + '%').attr('aria-valuenow', 0);

            syncProducts(1, 1, 1);

            return false;
        });
    });

    function syncProducts(batch, totalBatch, total) {
        const data = {
            'action': 'adminSyncProducts',
            'batch': batch,
            'totalBatch': totalBatch,
            'total': total
        };
        $.post(ajaxurl, data, function (response) {
            if (response !== 'failed') {
                const res = JSON.parse(response);
                res.batch = parseInt(res.batch);
                if (res.batch < res.totalBatch) {
                    let progress = (res.batch * 100) / res.totalBatch;
                    progress = Math.round(progress);
                    $('#syncProductsProgressBar').css('width', progress + '%').attr('aria-valuenow', progress);
                    //alert('batch: ' + res.batch + ', totalBatch: ' + res.totalBatch + ', total: ' + res.total);
                    syncProducts(res.batch + 1, res.totalBatch, res.total);
                    return false;
                } else {
                    $('#syncProductsProgressBar').css('width', 100 + '%').attr('aria-valuenow', 100);
                    setTimeout(() => {
                        top.location.replace(res.redirectUrl);
                    }, 1000);
                    return false;
                }
            } else {
                alert('Error syncing products.');
                return false;
            }
        });
    }

    $(function () {
        // AJAX - Sync Orders
        $('#ssbhesabfa_sync_orders').submit(function () {
            // show processing status
            $('#ssbhesabfa-sync-orders-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-sync-orders-submit').removeClass('button-primary');
            $('#ssbhesabfa-sync-orders-submit').html('<i class="ofwc-spinner"></i> Syncing, please wait...');
            $('#ssbhesabfa-sync-orders-submit i.spinner').show();

            var date = $('#ssbhesabfa_sync_order_date').val();

            var data = {
                'action': 'adminSyncOrders',
                'date': date
            };

            // post it
            $.post(ajaxurl, data, function (response) {
                if ('failed' !== response) {
                    var redirectUrl = response;

                    /** Debug **/
                    // console.log(redirectUrl);
                    // return false;

                    top.location.replace(redirectUrl);
                    return false;
                } else {
                    alert('Error syncing products.');
                    return false;
                }
            });
            /*End Post*/
            return false;
        });
    });

    $(function () {
        // AJAX - Sync Products
        $('#ssbhesabfa_update_products').submit(function () {
            // show processing status
            $('#ssbhesabfa-update-products-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-update-products-submit').removeClass('button-primary');
            $('#ssbhesabfa-update-products-submit').html('<i class="ofwc-spinner"></i> Updating, please wait...');
            $('#ssbhesabfa-update-products-submit i.spinner').show();

            $('#updateProductsProgress').show();
            $('#updateProductsProgressBar').css('width', 0 + '%').attr('aria-valuenow', 0);

            updateProducts(1, 1, 1);

            return false;
        });
    });

    function updateProducts(batch, totalBatch, total) {
        var data = {
            'action': 'adminUpdateProducts',
            'batch': batch,
            'totalBatch': totalBatch,
            'total': total
        };
        $.post(ajaxurl, data, function (response) {
            if ('failed' !== response) {
                const res = JSON.parse(response);
                res.batch = parseInt(res.batch);
                if (res.batch < res.totalBatch) {
                    let progress = (res.batch * 100) / res.totalBatch;
                    progress = Math.round(progress);
                    $('#updateProductsProgressBar').css('width', progress + '%').attr('aria-valuenow', progress);
                    updateProducts(res.batch + 1, res.totalBatch, res.total);
                    return false;
                } else {
                    $('#updateProductsProgressBar').css('width', 100 + '%').attr('aria-valuenow', 100);
                    setTimeout(() => {
                        top.location.replace(res.redirectUrl);
                    }, 1000);
                    return false;
                }
            } else {
                alert('Error updating products.');
                return false;
            }
        });
    }

    $(function () {
        // AJAX - Clean log
        $('#ssbhesabfa_clean_log').submit(function () {
            // show processing status
            $('#ssbhesabfa-log-clean-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa-log-clean-submit').removeClass('button-primary');
            $('#ssbhesabfa-log-clean-submit').html('<i class="ofwc-spinner"></i> پاک کردن فایل لاگ، لطفاً صبر کنید...');
            $('#ssbhesabfa-log-clean-submit i.spinner').show();

            var data = {
                'action': 'adminCleanLogFile'
            };

            // post it
            $.post(ajaxurl, data, function (response) {
                if ('failed' !== response) {
                    var redirectUrl = response;

                    /** Debug **/
                    // console.log(redirectUrl);
                    // return false;

                    top.location.replace(redirectUrl);
                    return false;
                } else {
                    alert('Error cleaning log file.');
                    return false;
                }
            });
            /*End Post*/
            return false;
        });
    });

    $(function () {
        // AJAX - Sync Products Manually
        $('#ssbhesabfa_sync_products_manually').submit(function () {
            // show processing status
            $('#ssbhesabfa_sync_products_manually-submit').attr('disabled', 'disabled');
            $('#ssbhesabfa_sync_products_manually-submit').removeClass('button-primary');
            $('#ssbhesabfa_sync_products_manually-submit').html('<i class="ofwc-spinner"></i> Saving, please wait...');
            $('#ssbhesabfa_sync_products_manually i.spinner').show();

            const inputArray = [];
            const inputs = $('.code-input');
            console.log(inputs);
            for (var n = 0; n < inputs.length; n++) {
                var i = inputs[n];
                console.log(i);
                const obj = {
                    id: $(i).attr('id'),
                    hesabfa_id: $(i).val(),
                    parent_id: $(i).attr('data-parent-id')
                }
                inputArray.push(obj);
            }

            const page = $('#pageNumber').val();
            const rpp = $('#goToPage').attr('data-rpp');

            var data = {
                'action': 'adminSyncProductsManually',
                'data': JSON.stringify(inputArray),
                'page': page,
                'rpp': rpp
            };

            // post it
            $.post(ajaxurl, data, function (response) {
                if ('failed' !== response) {
                    var redirectUrl = response;

                    /** Debug **/
                    // console.log(redirectUrl);
                    // return false;

                    top.location.replace(redirectUrl);
                    return false;
                } else {
                    alert('Error saving data.');
                    return false;
                }
            });
            /*End Post*/
            return false;
        });

        $("#goToPage").click(function () {
            const page = $('#pageNumber').val();
            const rpp = $('#goToPage').attr('data-rpp');
            window.location.href = "?page=hesabfa-sync-products-manually&p=" + page + "&rpp=" + rpp;
        });

        $("#show-tips-btn").click(function () {
            $('#tips-alert').removeClass('d-none');
            $('#tips-alert').addClass('d-block');
        });

        $("#hide-tips-btn").click(function () {
            $('#tips-alert').removeClass('d-block');
            $('#tips-alert').addClass('d-none');
        });
    });

});