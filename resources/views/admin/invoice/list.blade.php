@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.confirm-delete')
    @include('admin.layout.confirm-window')
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All invoices</h2>
    <div class="w-full flex justify-between mb-3 items-center">
        <div class="sm:w-[40%]">
            <input type="text" name="invoice-search" placeholder="Search for invoice ID" class="text-sm py-2 px-2 rounded-[5px] border-solid border-[1px] border-gray-200 bg-gray-100 text-gray-600 w-full focus:ring-blue-500 focus:border-blue-500">
        </div>
        <a href="{{ route('admin.invoice.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add invoice
        </a>
    </div>
    @include('admin.invoice.filter')
    
    @if (empty($invoices['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('admin.layout.pagination')
    <form class="w-full overflow-x-auto mb-2" method="POST" action="{{ route('admin.fulfillment.bulk') }}" id="main-page-form">
        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
        <input type="hidden" id="bulk_action" name="bulk_action" value=""/>
        <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg min-width">
            <thead class="text-xs text-white font-semibold bg-indigo-950"  style="text-align: center !important;">
                <tr>
                    <th scope="col" class="p-4">
                        <div class="flex items-center">
                            <input id="select_all_rows" name="select_all_rows" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-50 border-gray-300 rounded">
                            <label for="select_all_rows" class="sr-only">checkbox</label>
                        </div>
                    </th>
                    <th scope="col" class="pl-4 sm:py-3 py-2">
                        Index
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Invoice_ID
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Customer_Name
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Reference
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Total_Amount
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Outstanding
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Due Date
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Status
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Payment
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Items
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Staff_Created
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2">
                        Extra_Note
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
                @foreach($invoices['data'] as $index => $invoice)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input name="selected_rows[]" type="checkbox" class="selected_rows w-4 h-4 text-blue-600 bg-gray-50 border-gray-300 rounded" value="{{ $invoice['id'] }}">
                            <label class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <th scope="col" class="pl-4 py-3">
                        {{ $index + 1 }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $invoice['id'] }}
                    </th>
                    <th scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ $invoice['customer']['customer_id'] }} {{ $invoice['customer']['full_name'] }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $invoice['reference'] ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        @if (!empty($invoice['total_amount']))
                        {{ $invoice['total_amount'] ?? '' }} {{ $invoice['unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4">
                        @if (!empty($invoice['outstanding_amount']))
                        {{ $invoice['outstanding_amount'] ?? '' }} {{ $invoice['unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice['due_date'] }}
                    </td>
                    <td scope="row" class="py-4 font-medium whitespace-nowrap text-[12px]">
                        <span class="bg-{{ $invoiceStatusColors[$invoice['status']] }}-500 py-2 px-3 rounded-lg text-white">{{ Str::upper($invoiceStatuses[$invoice['status']]) }}</span>
                    </td>
                    <td scope="row" class="py-4 font-medium whitespace-nowrap text-[12px]">
                        <span class="bg-{{ $paymentStatusColors[$invoice['payment_status']] }}-500 py-2 px-3 rounded-lg text-white">{{ Str::upper($paymentStatuses[$invoice['payment_status']]) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        {{ count($invoice['items']) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice['staff']['full_name'] }} ({{ Str::upper(Staff::MAP_POSITIONS[$invoice['staff']['position']]) }})
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice['note'] ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $invoice['created_at'] }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="h-full flex gap-4">
                        <a href="{{ route('admin.invoice.show', ['invoice' => $invoice['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                        <a href="{{ route('admin.invoice.edit.form', ['invoice' => $invoice['id']]) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                        <button type="button" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" data-row-id="{{ $invoice['id'] }}" data-row-route="{{ route('admin.invoice.delete', ['invoice' => $invoice['id']]) }}" data-modal-toggle="deleteModal" >Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    {{-- @include('admin.layout.main-page-action') --}}
    @endif
</div>
@endsection