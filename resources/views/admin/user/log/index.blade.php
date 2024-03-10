@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    <h2 class="text-2xl font-medium mt-2 mb-3">All user logs</h2>
    @include('admin.user.log.filter')
    @include ('admin.user.components.log-table')
<div>
@endsection