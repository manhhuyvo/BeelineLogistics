@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    <h2 class="text-2xl font-medium mt-2 mb-3">All staff logs</h2>
    @include('admin.staff.log.filter')
    @include ('admin.layout.log-table')
<div>
@endsection