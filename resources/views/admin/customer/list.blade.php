@extends('admin.layout.layout')
@section('content')

<div class="sm:rounded-lg">
    @include('admin.layout.confirm-delete')
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All customers</h2>
    <div class="w-full flex justify-end mb-3 items-center">
        <a href="{{ route('admin.customer.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add customer
        </a>
    </div>
    @include('admin.customer.filter')

    @if (empty($customers['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('admin.layout.pagination')
    <div class="w-full overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg">
        <thead class="text-xs text-white font-semibold uppercase bg-indigo-950"  style="text-align: center !important;">
            <tr>
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Index
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Customer ID
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
                    Staff Manage
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
            @foreach($customers['data'] as $index => $customer)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="col" class="pl-4 py-3">
                    {{ $index + 1 }}
                </th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    {{ $customer['customer_id'] }}
                </th>
                <th scope="row" class="px-6 py-4 whitespace-nowrap">
                    {{ $customer['full_name'] }}
                </th>
                <td class="px-6 py-4">
                    {{ $customer['phone'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $customer['address'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $staffsList[$customer['staff_id']] }}
                </td>
                <td class="px-6 py-4">
                    {{ $customerTypes[$customer['type']] }}
                </td>
                <td class="px-6 py-4">
                    {{ $customer['account']['username'] ?? "Not found" }}
                </td>
                <td class="px-6 py-4">
                    <span class="font-semibold text-{{ $customerStatusColors[$customer['status']] ?? 'red' }}-500">{{ $customerStatuses[$customer['status']] }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $customer['created_at'] }}
                </td>
                <td class="px-6 py-4">
                    <div class="h-full flex gap-4">
                    <a href="{{ route('admin.customer.show', ['customer' => $customer['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                    <a href="{{ route('admin.customer.edit.form', ['customer' => $customer['id']]) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                    {{-- <button type="button" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" data-row-id="{{ $customer['id'] }}" data-row-route="{{ route('admin.customer.delete', ['customer' => $customer['id']]) }}" data-modal-toggle="deleteModal" >Delete</button> --}}
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