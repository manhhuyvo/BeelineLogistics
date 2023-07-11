<?php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

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
    $trail->push('Edit Staff', route('admin.staff.edit.form', ['staff' => $staff->id]));
});
Breadcrumbs::for('admin.staff.create.form', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.staff.list');
    $trail->push('Add New Staff', route('admin.staff.create.form'));
});
