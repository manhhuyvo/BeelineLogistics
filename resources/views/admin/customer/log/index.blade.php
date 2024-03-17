@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    <h2 class="text-2xl font-medium mt-2 mb-3">All customer logs</h2>
    @include('admin.customer.log.filter')
    @include ('admin.customer.components.log-table')
<div>
@endsection