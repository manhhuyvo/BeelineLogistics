@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.confirm-delete')
    @include('admin.layout.confirm-window')
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All fulfillments</h2>
    <div class="w-full flex justify-end mb-3 items-center">
        <a href="{{ route('admin.fulfillment.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add fulfillment
        </a>
    </div>
    @include('admin.fulfillment.filter')
    
    @if (empty($fulfillments['data']))
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
                    <th scope="col" class="pl-4 sm:py-3 py-2 whitespace-nowrap">
                        Index
                    </th>
                    <th scope="col" class="px-4 sm:py-3 py-2 whitespace-nowrap">
                        <div class="flex items-center">
                            Current Stage
                        </div>
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Fulfillment Number
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Customer Owner
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Receiver Name
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Receiver Phone
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Receiver Address
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Staff Manage
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Shipping Type
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Tracking Number
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Postage Cost
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Fulfillment Status
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Product Amount
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Product Payment Status
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Labour Cost
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Labour Payment Status
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Extra Note
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Date Created
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody style="text-align: center !important;">
                @foreach($fulfillments['data'] as $index => $fulfillment)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input name="selected_rows[]" type="checkbox" class="selected_rows w-4 h-4 text-blue-600 bg-gray-50 border-gray-300 rounded" value="{{ $fulfillment['id'] }}">
                            <label class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <th scope="col" class="pl-4 py-3">
                        {{ $index + 1 }}
                    </th>
                    <th scope="row" class=" py-4 font-medium whitespace-nowrap text-[12px]">
                        <span class="bg-{{ FulfillmentEnum::MAP_SHIPPING_COLORS[$fulfillment['shipping_status']] }}-500 py-2 px-3 rounded-lg text-white">{{ Str::upper(FulfillmentEnum::MAP_SHIPPING_STATUSES[$fulfillment['shipping_status']]) }}</span>
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap relative">
                        @if (!empty($fulfillment['support_tickets']) && count($fulfillment['support_tickets']) > 0)
                            <span class="px-1.5 text-[10px] text-white rounded-lg bg-red-500 absolute top-25 right-0">{{ count($fulfillment['support_tickets']) }}</span>
                        @endif
                        <a href="{{ route('admin.fulfillment.show', ['fulfillment' => $fulfillment['id']]) }}" target="_blank" class="flex items-center justify-center hover:underline hover:text-blue-500">#{{ $fulfillment['id'] }} ({{ e($fulfillment['fulfillment_number'] ?: 'Unknown') }})<i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-2"></i></a>
                    </th>
                    <th scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ $fulfillment['customer']['customer_id'] }} {{ $fulfillment['customer']['full_name'] }}
                    </th>
                    <td scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ $fulfillment['name'] ?? '' }}
                    </td>
                    <td scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ $fulfillment['phone'] ?? '' }}
                    </td>
                    <td scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ $fulfillment['address'] }},
                        @if (!empty($fulfillment['address2']))
                        {{ $fulfillment['address2'] }},
                        @endif
                        {{ $fulfillment['suburb'] }}
                        {{ $fulfillment['state'] }}
                        {{ $fulfillment['postcode'] }},
                        {{ $countries[$fulfillment['country']] ?? '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $fulfillment['staff']['full_name'] }} ({{ Str::upper(Staff::MAP_POSITIONS[$fulfillment['staff']['position']]) }})
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $shippingTypes[$fulfillment['shipping_type']] ?? '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $fulfillment['tracking_number'] ?? '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if (!empty($fulfillment['postage']))
                        {{ $fulfillment['postage'] ?? '' }} {{ $fulfillment['postage_unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4 text-{{ $fulfillmentStatusColors[$fulfillment['fulfillment_status']] }}-500 font-semibold whitespace-nowrap">
                        {{ $fulfillmentStatuses[$fulfillment['fulfillment_status']] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if (!empty($fulfillment['total_product_amount']))
                        {{ $fulfillment['total_product_amount'] ?? '' }} {{ $fulfillment['product_unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4 text-{{ $paymentStatusColors[$fulfillment['product_payment_status']] }}-500 font-semibold whitespace-nowrap">
                        {{ $paymentStatuses[$fulfillment['product_payment_status']] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if (!empty($fulfillment['total_labour_amount']))
                        {{ $fulfillment['total_labour_amount'] ?? '' }} {{ $fulfillment['labour_unit'] }}
                        @endif 
                    </td>
                    <td class="px-6 py-4 text-{{ $paymentStatusColors[$fulfillment['labour_payment_status']] }}-500 font-semibold whitespace-nowrap">
                        {{ $paymentStatuses[$fulfillment['labour_payment_status']] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ e(Str::limit($fulfillment['note'] ?? '', 40, $end='...')) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $fulfillment['created_at'] }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="h-full flex gap-4">
                        <a href="{{ route('admin.fulfillment.show', ['fulfillment' => $fulfillment['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                        <a href="{{ route('admin.fulfillment.edit.form', ['fulfillment' => $fulfillment['id']]) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                        {{-- <button type="button" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" data-row-id="{{ $fulfillment['id'] }}" data-row-route="{{ route('admin.fulfillment.delete', ['fulfillment' => $fulfillment['id']]) }}" data-modal-toggle="deleteModal" >Delete</button> --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    @include('admin.layout.main-page-action')
    @endif
</div>
@endsection