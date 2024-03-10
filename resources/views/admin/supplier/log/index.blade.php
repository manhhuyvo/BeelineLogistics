@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    <h2 class="text-2xl font-medium mt-2 mb-3">All supplier logs</h2>
    @include('admin.supplier.log.filter')
    @include ('admin.supplier.components.log-table')
<div>
@endsection