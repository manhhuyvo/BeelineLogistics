@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Vew supplier details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Personal Details
        </p>
        <div class="w-full flex flex-col gap-3 px-3 py-2 justify-center">
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="full_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $supplier['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $supplier['phone'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="company" class="mb-2 text-sm font-medium text-gray-900">Company</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $supplier['company'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $supplier['address'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]">{{ $supplier['note'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $supplierTypes[$supplier['type']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ $supplierStatusColors[$supplier['status']] ?? 'red' }}-500 text-sm w-full py-2.5 px-2">{{ $supplierStatuses[$supplier['status']] ?? 'Not provided' }}</div>
                </div>
            </div>
        </div>
        <div class="w-full px-3 flex flex-col gap-2 my-1 mb-3">
            <p class="text-lg font-medium text-blue-600 mt-1">
                Country Configurations
            </p>
            <div class="w-full flex flex-col gap-2">
                <p class="text-sm font-semibold">Countries available for this supplier</p>
                <div class="w-full md:grid md:grid-cols-3 gap-2 flex flex-col">
                    @foreach ($currentCountriesMeta as $country)
                    <div class="h-[50px] p-0">
                        <div class="inline-flex gap-2 items-center w-full h-full border-solid border-[2px] border-gray-300 flex justify-center items-center bg-gray-300 text-gray-800 rounded-[3px]">
                            <span class="font-semibold md:text-[14px] text-sm">{{ Str::upper($countries[$country] ?? 'Unknown') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Service Configurations
            </p>
            <div class="w-full flex flex-col gap-2">
                <p class="text-sm font-semibold">Services available for this supplier</p>
                <div class="w-full md:grid md:grid-cols-3 gap-2 flex flex-col">
                    @foreach ($currentServicesMeta as $service)
                    <div class="h-[50px] p-0">
                        <div class="inline-flex gap-2 items-center w-full h-full border-solid border-[2px] border-gray-300 flex justify-center items-center bg-gray-300 text-gray-800 rounded-[3px]">
                            <span class="font-semibold md:text-[14px] text-sm">{{ Str::upper($services[$service] ?? 'Unknown') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>       
    <div class="row flex md:justify-end justify-center gap-2 w-full">
        <a href="{{ route('admin.supplier.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.supplier.edit.form', ['supplier' => $supplier['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

@endsection