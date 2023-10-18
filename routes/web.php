<?php

use Illuminate\Support\Facades\Route;

// ADMIN INCLUDES
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductGroupController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\FulfillmentController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\SmallElementsLoader;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\FulfillmentProductPaymentController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\SupportTicketCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CountryServiceConfigurationController;
use App\Models\Staff;

// CUSTOMER INCLUDES
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\FulfillmentController as CustomerFulfillmentController;
use App\Http\Controllers\Customer\SmallElementsLoader as CustomerSmallElementsLoader;
use App\Http\Controllers\Customer\FulfillmentProductPaymentController as CustomerFulfillmentProductPaymentController;
use App\Http\Controllers\Customer\SupportTicketController as CustomerSupportTicketController;
use App\Http\Controllers\Customer\SupportTicketCommentController as CustomerSupporTicketCommentController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

// SUPPLIER INCLUDES
use App\Http\Controllers\Supplier\AuthController as SupplierAuthController;
use App\Http\Controllers\Supplier\DashboardController as SupplierDashboardController;
use App\Http\Controllers\Supplier\ProfileController as SupplierProfileController;
use App\Http\Controllers\Supplier\FulfillmentController as SupplierFulfillmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['staff.login.redirect']], function() {
        /* [ADMIN AUTHENTICATION] */
        Route::get('/', [AuthController::class,'index'])->name('admin.index');

        /* [ADMIN LOGIN ROUTES] */
        Route::get('/login', [AuthController::class, 'loginView'])->name('admin.login.form');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
    });

    Route::group(['middleware' => 'staff.permission:'. Staff::POSITION_DIRECTOR], function () {
        /* [TEMPORARY ADMIN REGISTER ROUTE] */
        Route::get('/register', [AuthController::class, 'registerView'])->name('admin.register.form');
        Route::post('/register', [AuthController::class, 'register'])->name('admin.register');

        /* [STAFF ROUTES] */
        Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff.list');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('admin.staff.create.form');
        Route::post('/staff', [StaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('admin.staff.show');
        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit.form');
        Route::post('/staff/{staff}', [StaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('admin.staff.delete');

        /** [SUPPLIER ROUTES] */
        Route::get('/supplier', [SupplierController::class, 'index'])->name('admin.supplier.list');
        Route::get('/supplier/create', [SupplierController::class, 'create'])->name('admin.supplier.create.form');
        Route::post('/supplier', [SupplierController::class, 'store'])->name('admin.supplier.store');
        Route::post('/supplier/{supplier}/country-config', [SupplierController::class, 'countryConfig'])->name('admin.supplier.country-config');
        Route::post('/supplier/{supplier}/service-config', [SupplierController::class, 'serviceConfig'])->name('admin.supplier.service-config');
        Route::get('/supplier/{supplier}', [SupplierController::class, 'show'])->name('admin.supplier.show');
        Route::get('/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('admin.supplier.edit.form');
        Route::post('/supplier/{supplier}', [SupplierController::class, 'update'])->name('admin.supplier.update');
        Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('admin.supplier.delete');
    });

    Route::group(['middleware' => 'staff.permission:' . Staff::POSITION_DIRECTOR . '|' . Staff::POSITION_ACCOUNTANT], function () {
        /* [USER MANAGEMENT] */
        Route::get('/user', [UserController::class, 'index'])->name('admin.user.list');
        Route::get('/user/create', [UserController::class, 'create'])->name('admin.user.create.form');
        Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('/user/{user}', [UserController::class, 'show'])->name('admin.user.show');
        Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit.form');
        Route::post('/user/{user}', [UserController::class, 'update'])->name('admin.user.update');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('admin.user.delete');

        /** [COUNTRY SERVICE CONFIGURATION] */
        Route::get('/country-service-configuration', [CountryServiceConfigurationController::class, 'index'])->name('admin.country-service-configuration.show');
        Route::post('/country-service-configuration', [CountryServiceConfigurationController::class, 'update'])->name('admin.country-service-configuration.update');

        /* [AJAX USER OWNER SEARCH] */
        Route::post('/ajax/search-user-owner', [AjaxController::class, 'searchUserOwner'])->name('admin.ajax.search-user-owner');

        /** [INVOICE ROUTES] */
        Route::post('/invoice', [InvoiceController::class, 'store'])->name('admin.invoice.store');
        Route::get('/invoice', [InvoiceController::class, 'index'])->name('admin.invoice.list');
        Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('admin.invoice.create.form');
        Route::post('/invoice/{invoice}/add-payment', [InvoiceController::class, 'addPayment'])->name('admin.invoice.add-payment');
        Route::post('/invoice', [InvoiceController::class, 'store'])->name('admin.invoice.store');
        Route::post('/invoice/bulk', [InvoiceController::class, 'bulk'])->name('admin.invoice.bulk');
        Route::post('/invoice/export', [InvoiceController::class, 'export'])->name('admin.invoice.export');
        Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('admin.invoice.show');
        Route::get('/invoice/{invoice}/edit', [InvoiceController::class, 'edit'])->name('admin.invoice.edit.form');
        Route::post('/invoice/{invoice}', [InvoiceController::class, 'update'])->name('admin.invoice.update');
        Route::delete('/invoice/{invoice}', [InvoiceController::class, 'destroy'])->name('admin.invoice.delete');
        Route::get('/small-elements/invoice-row/{target}', [SmallElementsLoader::class, 'getNewInvoiceRow'])->name('admin.small-elements.invoice-row');

        /** [PRODUCT GROUP ROUTES] */
        Route::get('/product-group', [ProductGroupController::class, 'index'])->name('admin.product-group.list');
        Route::get('/product-group/create', [ProductGroupController::class, 'create'])->name('admin.product-group.create.form');
        Route::post('/product-group', [ProductGroupController::class, 'store'])->name('admin.product-group.store');
        Route::get('/product-group/{group}', [ProductGroupController::class, 'show'])->name('admin.product-group.show');
        Route::get('/product-group/{group}/edit', [ProductGroupController::class, 'edit'])->name('admin.product-group.edit.form');
        Route::post('/product-group/{group}', [ProductGroupController::class, 'update'])->name('admin.product-group.update');
        Route::delete('/product-group/{group}', [ProductGroupController::class, 'destroy'])->name('admin.product-group.delete');

        /** [PRODUCT ROUTES] */
        Route::get('/product', [ProductController::class, 'index'])->name('admin.product.list');
        Route::get('/product/create', [ProductController::class, 'create'])->name('admin.product.create.form');
        Route::post('/product', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('/product/{product}', [ProductController::class, 'show'])->name('admin.product.show');
        Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('admin.product.edit.form');
        Route::post('/product/{product}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('admin.product.delete');

        /** [CUSTOMER PRICING ROUTES] */
        Route::get('/customer/price-configs/{customer}/edit', [CustomerController::class, 'editPriceConfigsPage'])->name('admin.customer.price-configs.edit.form');
        Route::post('/customer/price-configs/{customer}/edit', [CustomerController::class, 'updatePriceConfigs'])->name('admin.customer.price-configs.update');
    });

    Route::group(['middleware' => 'staff.permission:all'], function () {
        /* [ADMIN LOGOUT ROUTE] */
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        /** [SMALL ELEMENTS LOADER] */
        Route::get('/small-elements/product-row', [SmallElementsLoader::class, 'getNewProductRow'])->name('admin.small-elements.product-row');
        Route::get('/small-elements/ticket-belongs-row/{target}', [SmallElementsLoader::class, 'getNewTicketBelongsRow'])->name('admin.small-elements.ticket-belongs-row');

        /** [AJAX SEARCH USER ROUTE] */        
        Route::post('/ajax/search-customer', [AjaxController::class, 'searchCustomer'])->name('admin.ajax.search-customer');

        /** [DASHBOARD ADMIN ROUTES] */
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        /** [USER PROFILE ROUTES] */
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.user.profile.form');
        Route::post('/profile', [ProfileController::class, 'update'])->name('admin.user.profile.update');
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('admin.user.profile.change-password');

        /** [CUSTOMER ROUTES] */
        Route::get('/customer', [CustomerController::class, 'index'])->name('admin.customer.list');
        Route::get('/customer/create', [CustomerController::class, 'create'])->name('admin.customer.create.form');
        Route::post('/customer', [CustomerController::class, 'store'])->name('admin.customer.store');
        Route::get('/customer/{customer}', [CustomerController::class, 'show'])->name('admin.customer.show');
        Route::get('/customer/{customer}/edit', [CustomerController::class, 'edit'])->name('admin.customer.edit.form');
        Route::post('/customer/{customer}', [CustomerController::class, 'update'])->name('admin.customer.update');
        Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('admin.customer.delete');
        Route::get('/customer/price-configs/create', [CustomerController::class, 'createPriceConfigsPage'])->name('admin.customer.price-configs.create.form');
        Route::post('/customer/price-configs', [CustomerController::class, 'storePriceConfigs'])->name('admin.customer.price-configs.store');

        /** [CUSTOMER META ROUTES] */
        Route::post('/customer/{customer}/country-config', [CustomerController::class, 'countryConfig'])->name('admin.customer.country-config');
        Route::post('/customer/{customer}/service-config', [CustomerController::class, 'serviceConfig'])->name('admin.customer.service-config');

        /** [FULFILLMENT ROUTES] */
        Route::get('/fulfillment', [FulfillmentController::class, 'index'])->name('admin.fulfillment.list');
        Route::get('/fulfillment/create', [FulfillmentController::class, 'create'])->name('admin.fulfillment.create.form');
        Route::post('/fulfillment', [FulfillmentController::class, 'store'])->name('admin.fulfillment.store');
        Route::post('/fulfillment/{fulfillment}/add-payment', [FulfillmentProductPaymentController::class, 'addPayment'])->name('admin.fulfillment.add-payment');
        Route::post('/fulfillment/{fulfillment}/update-payment', [FulfillmentProductPaymentController::class, 'updatePayment'])->name('admin.fulfillment.update-payment');
        Route::post('/fulfillment/bulk', [FulfillmentController::class, 'bulk'])->name('admin.fulfillment.bulk');
        Route::post('/fulfillment/export', [FulfillmentController::class, 'export'])->name('admin.fulfillment.export');
        Route::get('/fulfillment/{fulfillment}', [FulfillmentController::class, 'show'])->name('admin.fulfillment.show');
        Route::get('/fulfillment/{fulfillment}/edit', [FulfillmentController::class, 'edit'])->name('admin.fulfillment.edit.form');
        Route::post('/fulfillment/{fulfillment}', [FulfillmentController::class, 'update'])->name('admin.fulfillment.update');
        Route::delete('/fulfillment/{fulfillment}', [FulfillmentController::class, 'destroy'])->name('admin.fulfillment.delete');

        /** [SUPPORT TICKET ROUTES] */
        Route::get('/ticket', [SupportTicketController::class, 'index'])->name('admin.ticket.list');
        Route::get('/ticket/export', [SupportTicketController::class, 'export'])->name('admin.ticket.export');
        Route::get('/ticket/create', [SupportTicketController::class, 'create'])->name('admin.ticket.create.form');
        Route::get('/ticket/{ticket}', [SupportTicketController::class, 'show'])->name('admin.ticket.show');
        Route::post('/ticket', [SupportTicketController::class, 'store'])->name('admin.ticket.store');
        Route::post('/ticket/{ticket}/solve', [SupportTicketController::class, 'solve'])->name('admin.ticket.solve');
        Route::post('/ticket/{ticket}/active', [SupportTicketController::class, 'active'])->name('admin.ticket.active');
        Route::post('/ticket/{ticket}/delete', [SupportTicketController::class, 'delete'])->name('admin.ticket.delete');
        /** [SUPPORT TICKET COMMENT ROUTES] */
        Route::post('/ticket/{ticket}/comment', [SupportTicketCommentController::class, 'store'])->name('admin.ticket.comment.store');
    });

});

Route::prefix('customer')->group(function () {
    Route::group(['middleware' => 'customer.permission:all'], function () {
        /** [SMALL ELEMENTS LOADER] */
        Route::get('/small-elements/product-row', [CustomerSmallElementsLoader::class, 'getNewProductRow'])->name('customer.small-elements.product-row');
        Route::get('/small-elements/ticket-belongs-row/{target}', [CustomerSmallElementsLoader::class, 'getNewTicketBelongsRow'])->name('customer.small-elements.ticket-belongs-row');

        /** [DASHBOARD CUSTOMER ROUTES] */
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');    

        /** [USER PROFILE ROUTES] */
        Route::get('/profile', [CustomerProfileController::class, 'index'])->name('customer.user.profile.form');
        Route::post('/profile', [CustomerProfileController::class, 'update'])->name('customer.user.profile.update');
        Route::post('/profile/change-password', [CustomerProfileController::class, 'changePassword'])->name('customer.user.profile.change-password');

        /** [CUSTOMER INVOICE ROUTES] */        
        Route::get('/invoice', [CustomerInvoiceController::class, 'index'])->name('customer.invoice.list');
        Route::get('/invoice/{invoice}', [CustomerInvoiceController::class, 'show'])->name('customer.invoice.show');
        Route::post('/invoice/bulk', [CustomerInvoiceController::class, 'bulk'])->name('customer.invoice.bulk');
        Route::post('/invoice/export', [CustomerInvoiceController::class, 'export'])->name('customer.invoice.export');

        /** [FULFILLMENT ROUTES] */
        Route::get('/fulfillment', [CustomerFulfillmentController::class, 'index'])->name('customer.fulfillment.list');
        Route::post('/fulfillment/{fulfillment}/add-payment', [CustomerFulfillmentProductPaymentController::class, 'addPayment'])->name('customer.fulfillment.add-payment');
        Route::post('/fulfillment/bulk', [CustomerFulfillmentController::class, 'bulk'])->name('customer.fulfillment.bulk');
        Route::post('/fulfillment/export', [CustomerFulfillmentController::class, 'export'])->name('customer.fulfillment.export');
        Route::get('/fulfillment/create', [CustomerFulfillmentController::class, 'create'])->name('customer.fulfillment.create.form');
        Route::get('/fulfillment/{fulfillment}', [CustomerFulfillmentController::class, 'show'])->name('customer.fulfillment.show');
        Route::post('/fulfillment', [CustomerFulfillmentController::class, 'store'])->name('customer.fulfillment.store');
        Route::get('/fulfillment/{fulfillment}/edit', [CustomerFulfillmentController::class, 'edit'])->name('customer.fulfillment.edit.form');
        Route::post('/fulfillment/{fulfillment}', [CustomerFulfillmentController::class, 'update'])->name('customer.fulfillment.update');

        /** [SUPPORT TICKET ROUTES] */
        Route::get('/ticket', [CustomerSupportTicketController::class, 'index'])->name('customer.ticket.list');
        Route::get('/ticket/export', [CustomerSupportTicketController::class, 'export'])->name('customer.ticket.export');
        Route::get('/ticket/create', [CustomerSupportTicketController::class, 'create'])->name('customer.ticket.create.form');
        Route::get('/ticket/{ticket}', [CustomerSupportTicketController::class, 'show'])->name('customer.ticket.show');
        Route::post('/ticket', [CustomerSupportTicketController::class, 'store'])->name('customer.ticket.store');
        /** [SUPPORT TICKET COMMENT ROUTES] */
        Route::post('/ticket/{ticket}/comment', [CustomerSupporTicketCommentController::class, 'store'])->name('customer.ticket.comment.store');
    }); 

    Route::group(['middleware' => ['customer.login.redirect']], function() {
        /* [CUSTOMER AUTHENTICATION] */
        Route::get('/', [CustomerAuthController::class,'index'])->name('customer.index');

        /* [CUSTOMER LOGIN ROUTES] */
        Route::get('/login', [CustomerAuthController::class, 'loginView'])->name('customer.login.form');
        Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login');
    });

    /* [CUSTOMER LOGOUT ROUTE] */
    Route::get('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
});

Route::prefix('supplier')->group(function() {
    Route::group(['middleware' => 'supplier.permission:all'], function () {        
        /** [DASHBOARD SUPPLIER ROUTES] */
        Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('supplier.dashboard');

        /** [FULFILLMENT ROUTES] */
        Route::get('/fulfillment', [SupplierFulfillmentController::class, 'index'])->name('supplier.fulfillment.list');
        Route::post('/fulfillment/{fulfillment}/add-payment', [SupplierFulfillmentController::class, 'addPayment'])->name('supplier.fulfillment.add-payment');
        Route::post('/fulfillment/bulk', [SupplierFulfillmentController::class, 'bulk'])->name('supplier.fulfillment.bulk');
        Route::post('/fulfillment/export', [SupplierFulfillmentController::class, 'export'])->name('supplier.fulfillment.export');
        Route::get('/fulfillment/create', [SupplierFulfillmentController::class, 'create'])->name('supplier.fulfillment.create.form');
        Route::get('/fulfillment/{fulfillment}', [SupplierFulfillmentController::class, 'show'])->name('supplier.fulfillment.show');
        Route::post('/fulfillment', [SupplierFulfillmentController::class, 'store'])->name('supplier.fulfillment.store');
        Route::get('/fulfillment/{fulfillment}/edit', [SupplierFulfillmentController::class, 'edit'])->name('supplier.fulfillment.edit.form');
        Route::post('/fulfillment/{fulfillment}', [SupplierFulfillmentController::class, 'update'])->name('supplier.fulfillment.update'); 
    });    

    /** [USER PROFILE ROUTES] */
    Route::get('/profile', [SupplierProfileController::class, 'index'])->name('supplier.user.profile.form');
    Route::post('/profile', [SupplierProfileController::class, 'update'])->name('supplier.user.profile.update');
    Route::post('/profile/change-password', [SupplierProfileController::class, 'changePassword'])->name('supplier.user.profile.change-password');

    Route::group(['middleware' => ['supplier.login.redirect']], function() {
        /* [SUPPLIER AUTHENTICATION] */
        Route::get('/', [SupplierAuthController::class,'index'])->name('supplier.index');

        /* [SUPPLIER LOGIN ROUTES] */
        Route::get('/login', [SupplierAuthController::class, 'loginView'])->name('supplier.login.form');
        Route::post('/login', [SupplierAuthController::class, 'login'])->name('supplier.login');
    });

    /* [SUPPLIER LOGOUT ROUTE] */
    Route::get('/logout', [SupplierAuthController::class, 'logout'])->name('supplier.logout');
});
