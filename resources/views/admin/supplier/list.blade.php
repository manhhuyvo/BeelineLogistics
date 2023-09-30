@extends('admin.layout.layout')
@section('content')

<div class="sm:rounded-lg">
    @include('admin.layout.confirm-delete')
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All suppliers</h2>
    <div class="w-full flex justify-between mb-3 items-center">
        <div class="sm:w-[40%]">
            <input type="text" name="supplier-search" placeholder="Search for supplier names" class="text-sm py-2 px-2 rounded-[5px] border-solid border-[1px] border-gray-200 bg-gray-100 text-gray-600 w-full focus:ring-blue-500 focus:border-blue-500">
        </div>
        <a href="{{ route('admin.supplier.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add supplier
        </a>
    </div>
    @include('admin.supplier.filter')

    @if (empty($suppliers['data']))
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
                    Full Name
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Phone
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Address
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Company
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Type
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Username
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Status
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Date Created
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Action
                </th>
            </tr>
        </thead>
        <tbody style="text-align: center !important;">
            @foreach($suppliers['data'] as $index => $supplier)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="col" class="pl-4 py-3">
                    {{ $index + 1 }}
                </th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    {{ $supplier['full_name'] }}
                </th>
                <td class="px-6 py-4">
                    {{ $supplier['phone'] }}
                </td>
                <td class="px-6 py-4">
                    {{ $supplier['address'] }}
                </td>
                <td class="px-6 py-4">
                    {{ $supplier['company'] }}
                </td>
                <td class="px-6 py-4">
                    {{ $supplierTypes[$supplier['type']] }}
                </td>
                <td class="px-6 py-4">
                    {{ $supplier['account']['username'] ?? "Not found" }}
                </td>
                <td class="px-6 py-4">
                    <span class="font-semibold text-{{ $supplierStatusColors[$supplier['status']] ?? 'red' }}-500">{{ $supplierStatuses[$supplier['status']] }}</span>
                </td>
                <td class="px-6 py-4">
                    {{ $supplier['created_at'] }}
                </td>
                <td class="px-6 py-4">
                    <div class="h-full flex gap-4">
                    <a href="{{ route('admin.supplier.show', ['supplier' => $supplier['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                    <a href="{{ route('admin.supplier.edit.form', ['supplier' => $supplier['id']]) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                    {{-- <button type="button" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" data-row-id="{{ $supplier['id'] }}" data-row-route="{{ route('admin.supplier.delete', ['supplier' => $supplier['id']]) }}" data-modal-toggle="deleteModal" >Delete</button> --}}
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