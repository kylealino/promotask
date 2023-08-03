<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();

//routes for promo discount
$routes->get('/', 'Home::index');
$routes->get('get-branch-list','MySearchData::companybranch_v');
$routes->get('get-comp-list','MySearchData::company');
$routes->get('me-dashboard', 'Promo_dashboard::index');
$routes->get('me-promo', 'Promo_discount::index');
$routes->get('me-promo-vw', 'Promo_discount::index');
$routes->add('me-promo-save', 'Promo_discount::promo_save');
$routes->add('me-promo-recs', 'Promo_discount::promo_recs');
$routes->add('me-promo-view', 'Promo_discount::promo_vw');
$routes->add('me-promo-print', 'Promo_discount::promo_print');
$routes->add('me-promo-appr', 'Promo_discount::promo_recs_appr');
$routes->add('me-promo-view-appr', 'Promo_discount::promo_vw_appr');
$routes->add('me-promo-appr-save', 'Promo_discount::promo_save_appr');
$routes->add('me-promo-barcode-dl', 'Promo_discount::promo_barcode_dl_proc');

//buy one take one
$routes->get('get-promo-itemc','MySearchData::mat_article'); //
$routes->get('me-buyxtakey','BuyXTakeY::index');
$routes->add('me-buyxtakey-save', 'BuyXTakeY::buyxtakey_save');
$routes->add('me-buyxtakey-view', 'BuyXTakeY::buyxtakey_vw');
$routes->add('me-buyxtakey-recs', 'BuyXTakeY::buyxtakey_recs');
$routes->add('me-buyxtakey-view-appr', 'BuyXTakeY::buyxtakey_vw_appr');
$routes->add('me-buyxtakey-appr', 'BuyXTakeY::buyxtakey_recs_appr');
$routes->add('me-buyxtakey-appr-save', 'BuyXTakeY::buyxtakey_save_appr');
$routes->add('me-buyxtakey-barcode-dl', 'BuyXTakeY::buyxtakey_dl_proc');
$routes->get('me-buyxtakey-vw', 'BuyXTakeY::index');

//deposit
$routes->get('me-deposit', 'Deposit::index');
$routes->get('get-deposit-no','MySearchData::deposit'); //
$routes->add('me-deposit-save', 'Deposit::deposit_save');

//voucher me-voucher
$routes->get('me-voucher', 'Voucher::index');
$routes->add('me-voucher-save', 'Voucher::voucher_save');

//upload
$routes->get('me-upload','Upload::index');
$routes->post('upload', 'Upload::upload');
$routes->get('display-image/(:segment)', 'Upload::displayImage/$1');

//upload v2
$routes->get('Meupload', 'Meupload::index');
$routes->post('/upload/do_upload', 'Meupload::do_upload');
$routes->post('/Meupload/delete_file', 'Meupload::delete_file');
$routes->post('/Meupload/view_file', 'Meupload::view_file');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
