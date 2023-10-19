@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View product details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Product Details
        </p>
        <div class="w-full flex flex-col gap-3 px-3 pt-2 pb-3 justify-center">
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="group_id" class="mb-2 text-sm font-medium text-gray-900">Product Group</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 font-semibold">{{ $productGroups[$product['group_id']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="name" class="mb-2 text-sm font-medium text-gray-900">Product Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 font-semibold">{{ $product['name'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="description" class="mb-2 text-sm font-medium text-gray-900">Customer</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $product['customer']['customer_id'] }} {{ $product['customer']['full_name'] }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="description" class="mb-2 text-sm font-medium text-gray-900">Description</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $product['description'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="price" class="mb-2 text-sm font-medium text-gray-900">Price</label>
                    <div class="w-full flex items-center text-gray-900 text-sm rounded-lg bg-gray-50">
                        <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $product['price_configs']['price'] ?? '0' }}</div>
                        <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-100 border-none pl-3">{{ $units[$product['price_configs']['unit']] ?? 'VND' }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="stock" class="mb-2 text-sm font-medium text-gray-900">Current Stock</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $product['stock'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <div class="bg-gray-50 text-{{ $productStatusColors[$product['status']] }}-600 text-sm w-full py-2.5 px-2">{{ $productStatuses[$product['status']] ?? 'Inactive' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $product['note'] ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>       
    <div class="row flex md:justify-end justify-center gap-2 w-full">
        <a href="{{ route('admin.product.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.product.edit.form', ['product' => $product['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

@endsection