@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Dashboard</h2>
    <div class="w-full gap-3 flex md:flex-row flex-col p-0 my-4">
        <div class="rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-2 px-3 flex flex-col gap-2 flex-1">            
            <p class="text-lg font-medium text-blue-600 mt-1">
                FULFILLMENTS REPORT
            </p>
            <p class="text-sm italic text-gray-500">{{ $startOfWeek }} - {{ $endOfWeek }}</p>
            @include('admin.dashboard.fulfillments-report')
        </div>
        <div class="rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-2 px-3 flex flex-col gap-2 flex-1">            
            <p class="text-lg font-medium text-blue-600 mt-1">
                PRODUCT PAYMENTS REPORT
            </p>
            <p class="text-sm italic text-gray-500">{{ $startOfWeek }} - {{ $endOfWeek }}</p>
            @include('admin.dashboard.fulfillments-product-payments-report')
        </div>
    </div>
    <div class="w-full gap-2 flex md:flex-row flex-col p-0 my-4">
        <div class="rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-2 px-3 flex flex-col gap-2 flex-1">            
            <p class="text-lg font-medium text-blue-600 mt-1">
                INVOICES REPORT
            </p>
            <p class="text-sm italic text-gray-500">{{ $startOfWeek }} - {{ $endOfWeek }}</p>
            @include('admin.dashboard.invoices-report')
        </div>
    </div>
</div>
@endsection