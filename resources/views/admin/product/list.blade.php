@extends('admin.layout.layout')
@section('content')

<div class="sm:rounded-lg">
    @include('admin.layout.confirm-delete')
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All products</h2>
    <div class="w-full flex justify-end mb-3 items-center">
        {{-- <div class="sm:w-[40%]">
            <input type="text" name="product-search" placeholder="Search for product names" class="text-sm py-2 px-2 rounded-[5px] border-solid border-[1px] border-gray-200 bg-gray-100 text-gray-600 w-full focus:ring-blue-500 focus:border-blue-500">
        </div> --}}
        <a href="{{ route('admin.product.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add product
        </a>
    </div>
    @include('admin.product.filter')

    @if (empty($products['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('admin.layout.pagination')
    <div class="w-full overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg">
        <thead class="text-xs text-white font-semibold uppercase bg-indigo-950">
            <tr>
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Index
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Product Name
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Customer Name
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Product Group
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Description
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Price
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Stock
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Status
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Note
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Date_Created
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Action
                </th>
            </tr>
        </thead>
        <tbody style="text-align: center !important;">
            @foreach($products['data'] as $index => $product)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="col" class="pl-4 py-3">
                    {{ $index + 1 }}
                </th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    <a href="{{ route('admin.product.show', ['product' => $product['id']]) }}" class="underline text-blue-700 hover:text-blue-500" target="_blank">{{ $product['name'] ?? 'Not Provided' }}</a>
                </th>
                <th scope="row" class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('admin.customer.show', ['customer' => $product['customer_id']]) }}" class="underline hover:text-gray-400" target="_blank">{{ $product['customer']['customer_id'] }} {{ $product['customer']['full_name'] }}</a>
                </th>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $product['product_group']['name'] ?? 'Not Provided' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $product['description'] ?? 'Not Provided' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if (!empty($product['price_configs']['price']))
                        {{ $product['price_configs']['price'] ?? '' }} {{ $product['price_configs']['unit'] ?? '' }}
                    @else
                        Not Provided
                    @endif
                </td>
                <td class="px-6 py-4">
                    {{ $product['stock'] ?? 'Not Provided' }}
                </td>
                <td class="px-6 py-4">
                    <span class="font-semibold text-{{ $productStatusColors[$product['status']] ?? 'red' }}-500">{{ $productStatuses[$product['status']] }}</span>
                </td>
                <td class="px-6 py-4">
                    {{ $product['note'] ?? '' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $product['created_at'] }}
                </td>
                <td class="px-6 py-4">
                    <div class="h-full flex gap-4">
                    <a href="{{ route('admin.product.show', ['product' => $product['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                    <a href="{{ route('admin.product.edit.form', ['product' => $product['id']]) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                    {{-- <button type="button" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" data-row-id="{{ $product['id'] }}" data-row-route="{{ route('admin.product.delete', ['product' => $product['id']]) }}" data-modal-toggle="deleteModal" >Delete</button> --}}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @endif
</div>
@endsection