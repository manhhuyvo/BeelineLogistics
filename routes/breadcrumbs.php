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

/** Breadcrumbs for SUPPLIER */
Breadcrumbs::for('admin.supplier.list', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Supplier', route('admin.supplier.list'));
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
