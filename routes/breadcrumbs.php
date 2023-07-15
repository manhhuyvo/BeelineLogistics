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

/** Breadcrumbs for USER PROFILE */
Breadcrumbs::for('admin.user.profile.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.dashboard');
    $trail->push('Profile', route('admin.user.profile.form'));
    $user = Auth::user();
    $trail->push("{$user->username}", route('admin.user.profile.form'));
});
