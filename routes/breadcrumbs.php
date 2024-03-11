<?php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Auth;

/** Breadcrumbs for DASHBOARD */
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push('Home', route('admin.dashboard'));
});

/** Breadcrumbs for STAFF */
Breadcrumbs::for('admin.staff.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Staff', route('admin.staff.list'));
});
Breadcrumbs::for('admin.staff.log', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Staff Logs', route('admin.staff.log'));
});
Breadcrumbs::for('admin.staff.show', function (BreadcrumbTrail $trail, $staff): void {
    $trail->parent('admin.staff.list');
    $trail->push("{$staff->full_name}", route('admin.staff.show', ['staff' => $staff->id]));
});
Breadcrumbs::for('admin.staff.edit.form', function (BreadcrumbTrail $trail, $staff): void {
    $trail->parent('admin.staff.list');
    $trail->push("Edit {$staff->full_name}", route('admin.staff.edit.form', ['staff' => $staff->id]));
});
Breadcrumbs::for('admin.staff.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.staff.list');
    $trail->push('Add New Staff', route('admin.staff.create.form'));
});

/** Breadcrumbs for CUSTOMER */
Breadcrumbs::for('admin.customer.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Customer', route('admin.customer.list'));
});
Breadcrumbs::for('admin.customer.log', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Customer Logs', route('admin.customer.log'));
});
Breadcrumbs::for('admin.customer.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.customer.list');
    $trail->push('Add New Customer', route('admin.customer.create.form'));
});
Breadcrumbs::for('admin.customer.show', function (BreadcrumbTrail $trail, $customer): void {
    $trail->parent('admin.customer.list');
    $trail->push("{$customer->full_name}", route('admin.customer.show', ['customer' => $customer->id]));
});
Breadcrumbs::for('admin.customer.edit.form', function (BreadcrumbTrail $trail, $customer): void {
    $trail->parent('admin.customer.list');
    $trail->push("Edit {$customer->full_name}", route('admin.customer.edit.form', ['customer' => $customer->id]));
});
Breadcrumbs::for('admin.customer.price-configs.edit.form', function (BreadcrumbTrail $trail, $customer): void {
    $trail->parent('admin.customer.show', $customer);
    $trail->push("Price Configuration", route('admin.customer.price-configs.edit.form', ['customer' => $customer->id]));
});

/** Breadcrumbs for SUPPLIER */
Breadcrumbs::for('admin.supplier.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Supplier', route('admin.supplier.list'));
});
Breadcrumbs::for('admin.supplier.log', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Supplier Logs', route('admin.supplier.log'));
});
Breadcrumbs::for('admin.supplier.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.supplier.list');
    $trail->push('Add New Supplier', route('admin.supplier.create.form'));
});
Breadcrumbs::for('admin.supplier.show', function (BreadcrumbTrail $trail, $supplier): void {
    $trail->parent('admin.supplier.list');
    $trail->push("{$supplier->full_name}", route('admin.supplier.show', ['supplier' => $supplier->id]));
});
Breadcrumbs::for('admin.supplier.edit.form', function (BreadcrumbTrail $trail, $supplier): void {
    $trail->parent('admin.supplier.list');
    $trail->push("Edit {$supplier->full_name}", route('admin.supplier.edit.form', ['supplier' => $supplier->id]));
});

/** Breadcrumbs for USER */
Breadcrumbs::for('admin.user.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('User', route('admin.user.list'));
});
Breadcrumbs::for('admin.user.log', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('User Logs', route('admin.user.log'));
});
Breadcrumbs::for('admin.user.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.user.list');
    $trail->push('Add New User', route('admin.user.create.form'));
});
Breadcrumbs::for('admin.user.show', function (BreadcrumbTrail $trail, $user): void {
    $trail->parent('admin.user.list');
    $trail->push("{$user->username}", route('admin.user.show', ['user' => $user->id]));
});
Breadcrumbs::for('admin.user.edit.form', function (BreadcrumbTrail $trail, $user): void {
    $trail->parent('admin.user.list');
    $trail->push("Edit {$user->username}", route('admin.user.edit.form', ['user' => $user->id]));
});

/** Breadcrumbs for USER PROFILE */
Breadcrumbs::for('admin.user.profile.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Profile', route('admin.user.profile.form'));
    $user = Auth::user();
    $trail->push("{$user->username}", route('admin.user.profile.form'));
});

/** Breadcrumbs for PRODUCT GROUP */
Breadcrumbs::for('admin.product-group.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Product Group', route('admin.product-group.list'));
});
Breadcrumbs::for('admin.product-group.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.product-group.list');
    $trail->push('Add New Product Group', route('admin.product-group.create.form'));
});
Breadcrumbs::for('admin.product-group.show', function (BreadcrumbTrail $trail, $productGroup): void {
    $trail->parent('admin.product-group.list');
    $trail->push("{$productGroup->name}", route('admin.product-group.show', ['group' => $productGroup->id]));
});
Breadcrumbs::for('admin.product-group.edit.form', function (BreadcrumbTrail $trail, $productGroup): void {
    $trail->parent('admin.product-group.list');
    $trail->push("Edit {$productGroup->name}", route('admin.product-group.edit.form', ['group' => $productGroup->id]));
});

/** Breadcrumbs for PRODUCT */
Breadcrumbs::for('admin.product.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Product', route('admin.product.list'));
});
Breadcrumbs::for('admin.product.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.product.list');
    $trail->push('Add New Product', route('admin.product.create.form'));
});
Breadcrumbs::for('admin.product.show', function (BreadcrumbTrail $trail, $product): void {
    $trail->parent('admin.product.list');
    $trail->push("{$product->name}", route('admin.product.show', ['product' => $product->id]));
});
Breadcrumbs::for('admin.product.edit.form', function (BreadcrumbTrail $trail, $product): void {
    $trail->parent('admin.product.list');
    $trail->push("Edit {$product->name}", route('admin.product.edit.form', ['product' => $product->id]));
});

/** Breadcrumbs for FULFILLMENT */
Breadcrumbs::for('admin.fulfillment.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Fulfillment', route('admin.fulfillment.list'));
});
Breadcrumbs::for('admin.fulfillment.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.fulfillment.list');
    $trail->push('Add New Fulfillment', route('admin.fulfillment.create.form'));
});
Breadcrumbs::for('admin.fulfillment.show', function (BreadcrumbTrail $trail, $fulfillment): void {
    $trail->parent('admin.fulfillment.list');
    $trail->push("Fulfillment #{$fulfillment->id} ({$fulfillment->name})", route('admin.fulfillment.show', ['fulfillment' => $fulfillment->id]));
});
Breadcrumbs::for('admin.fulfillment.edit.form', function (BreadcrumbTrail $trail, $fulfillment): void {
    $trail->parent('admin.fulfillment.list');
    $trail->push("Edit Fulfillment #{$fulfillment->id} ({$fulfillment->name})", route('admin.fulfillment.edit.form', ['fulfillment' => $fulfillment->id]));
});

/** Breadcrumbs for INVOICE */
Breadcrumbs::for('admin.invoice.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Invoice', route('admin.invoice.list'));
});
Breadcrumbs::for('admin.invoice.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.invoice.list');
    $trail->push('Add New Invoice', route('admin.invoice.create.form'));
});
Breadcrumbs::for('admin.invoice.show', function (BreadcrumbTrail $trail, $invoice): void {
    $trail->parent('admin.invoice.list');
    $trail->push("Invoice #{$invoice->id}", route('admin.invoice.show', ['invoice' => $invoice->id]));
});
Breadcrumbs::for('admin.invoice.edit.form', function (BreadcrumbTrail $trail, $invoice): void {
    $trail->parent('admin.invoice.list');
    $trail->push("Edit Invoice #{$invoice->id}", route('admin.invoice.edit.form', ['invoice' => $invoice->id]));
});

/** Admin Tickets */
Breadcrumbs::for('admin.ticket.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Support Ticket', route('admin.ticket.list'));
});
Breadcrumbs::for('admin.ticket.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.ticket.list');
    $trail->push('Add New Ticket', route('admin.ticket.create.form'));
});
Breadcrumbs::for('admin.ticket.show', function (BreadcrumbTrail $trail, $ticket): void {
    $trail->parent('admin.ticket.list');
    $trail->push("Ticket #{$ticket->id}", route('admin.ticket.show', ['ticket' => $ticket->id]));
});

/** Country Service Configuration */
Breadcrumbs::for('admin.country-service-configuration.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Country And Service', route('admin.country-service-configuration.show'));
});
Breadcrumbs::for('admin.country-service-configuration.update', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.country-service-configuration.show');
    $trail->push('Default Country And Service Configurations', route('admin.country-service-configuration.update'));
});

/**
 * 
 * CUSTOMER BREADCRUMBS
 * 
 */

 /** CUSTOMER DASHBOARD */
Breadcrumbs::for('customer.dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push('Home', route('customer.dashboard'));
});

/** Breadcrumbs for USER PROFILE */
Breadcrumbs::for('customer.user.profile.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('customer.dashboard');
    $trail->push('Profile', route('customer.user.profile.form'));
    $user = Auth::user();
    $trail->push("{$user->username}", route('customer.user.profile.form'));
});

/** CUSTOMER FULFILLMENTS */
Breadcrumbs::for('customer.fulfillment.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('customer.dashboard');
    $trail->push('Fulfillment', route('customer.fulfillment.list'));
});
Breadcrumbs::for('customer.fulfillment.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('customer.fulfillment.list');
    $trail->push('Add New Fulfillment', route('customer.fulfillment.create.form'));
});
Breadcrumbs::for('customer.fulfillment.show', function (BreadcrumbTrail $trail, $fulfillment): void {
    $trail->parent('customer.fulfillment.list');
    $trail->push("Fulfillment #{$fulfillment->id} ({$fulfillment->name})", route('customer.fulfillment.show', ['fulfillment' => $fulfillment->id]));
});
Breadcrumbs::for('customer.fulfillment.edit.form', function (BreadcrumbTrail $trail, $fulfillment): void {
    $trail->parent('customer.fulfillment.list');
    $trail->push("Edit Fulfillment #{$fulfillment->id} ({$fulfillment->name})", route('customer.fulfillment.edit.form', ['fulfillment' => $fulfillment->id]));
});

/** CUSTOMER INVOICE */
Breadcrumbs::for('customer.invoice.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('customer.dashboard');
    $trail->push('Invoice', route('customer.invoice.list'));
});
Breadcrumbs::for('customer.invoice.show', function (BreadcrumbTrail $trail, $invoice): void {
    $trail->parent('customer.invoice.list');
    $trail->push("Invoice #{$invoice->id}", route('customer.invoice.show', ['invoice' => $invoice->id]));
});

/** CUSTOMER SUPPORT TICKET */
Breadcrumbs::for('customer.ticket.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('customer.dashboard');
    $trail->push('Support Ticket', route('customer.ticket.list'));
});
Breadcrumbs::for('customer.ticket.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('customer.ticket.list');
    $trail->push('Add New Ticket', route('customer.ticket.create.form'));
});
Breadcrumbs::for('customer.ticket.show', function (BreadcrumbTrail $trail, $ticket): void {
    $trail->parent('customer.ticket.list');
    $trail->push("Ticket #{$ticket->id}", route('customer.ticket.show', ['ticket' => $ticket->id]));
});

/**
 * 
 * SUPPLIER BREADCRUMBS
 * 
 */

Breadcrumbs::for('supplier.dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push('Home', route('supplier.dashboard'));
});

/** Breadcrumbs for USER PROFILE */
Breadcrumbs::for('supplier.user.profile.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('supplier.dashboard');
    $trail->push('Profile', route('supplier.user.profile.form'));
    $user = Auth::user();
    $trail->push("{$user->username}", route('supplier.user.profile.form'));
});

/** Breadcrumbs for FULFILLMENT */
Breadcrumbs::for('supplier.fulfillment.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('supplier.dashboard');
    $trail->push('Fulfillment', route('supplier.fulfillment.list'));
});
// Breadcrumbs::for('supplier.fulfillment.create.form', function (BreadcrumbTrail $trail): void {
//     $trail->parent('supplier.fulfillment.list');
//     $trail->push('Add New Fulfillment', route('supplier.fulfillment.create.form'));
// });
Breadcrumbs::for('supplier.fulfillment.show', function (BreadcrumbTrail $trail, $fulfillment): void {
    $trail->parent('supplier.fulfillment.list');
    $trail->push("Fulfillment #{$fulfillment->id} ({$fulfillment->name})", route('supplier.fulfillment.show', ['fulfillment' => $fulfillment->id]));
});
Breadcrumbs::for('supplier.fulfillment.edit.form', function (BreadcrumbTrail $trail, $fulfillment): void {
    $trail->parent('supplier.fulfillment.list');
    $trail->push("Edit Fulfillment #{$fulfillment->id} ({$fulfillment->name})", route('supplier.fulfillment.edit.form', ['fulfillment' => $fulfillment->id]));
});

/** SUPPLIER SUPPORT TICKET */
Breadcrumbs::for('supplier.ticket.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('supplier.dashboard');
    $trail->push('Support Ticket', route('supplier.ticket.list'));
});
Breadcrumbs::for('supplier.ticket.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('supplier.ticket.list');
    $trail->push('Add New Ticket', route('supplier.ticket.create.form'));
});
Breadcrumbs::for('supplier.ticket.show', function (BreadcrumbTrail $trail, $ticket): void {
    $trail->parent('supplier.ticket.list');
    $trail->push("Ticket #{$ticket->id}", route('supplier.ticket.show', ['ticket' => $ticket->id]));
});