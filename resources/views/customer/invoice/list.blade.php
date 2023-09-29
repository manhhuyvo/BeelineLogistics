@extends('customer.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    {{-- @include('admin.layout.confirm-window') --}}
    @include('customer.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All invoices</h2>
    @include('customer.invoice.filter')
    
    @if (empty($invoices['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('customer.layout.pagination')
    <div class="w-full overflow-x-auto mb-2" id="main-page-form">
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
                        Date_Created
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
                        <a href="{{ route('customer.invoice.show', ['invoice' => $invoice['id']]) }}" class="text-blue-500 hover:underline hover:text-blue-700" title="View Invoice">#{{ $invoice['id'] }}</a>
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
                        {{ $invoice['created_at'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('customer.layout.main-page-action')
    @endif
</div>
@endsection