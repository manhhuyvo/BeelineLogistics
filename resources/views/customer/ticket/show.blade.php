@extends('customer.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('customer.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View Ticket Details</h2>
    <h2 class="text-xl font-bold absolute top-0 right-0 text-{{ $supportTicketStatusColors[$ticket['status']] }}-500 px-3 py-2 border-solid border-{{ $supportTicketStatusColors[$ticket['status']] }}-500 border-[3px] rounded-lg">{{ Str::upper($supportTicketStatuses[$ticket['status']]) }}</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('customer.ticket.store') }}" method="POST" enctype="multipart/form-data">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Ticket Details
            </p>
            <div class="row flex md:flex-row flex-col gap-2 flex-1">
                <div class="flex flex-col flex-1">
                    <label for="customer" class="mb-2 text-sm font-medium text-gray-900">Customer</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $ticket['customer']['customer_id'] ?? '' }} - {{ $ticket['customer']['full_name'] ?? '' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="created_at" class="mb-2 text-sm font-medium text-gray-900">Date Created</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $ticket['created_at'] }}</div>
                </div>
            </div>
            <div class="row flex flex-col gap-2 flex-1 justify-start">
                <div class="flex flex-col">
                    <label for="title" class="mb-2 text-sm font-medium text-gray-900">Title</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ e($ticket['title'] ?? '') }}</div>
                </div>
                <div class="flex flex-col">
                    <label for="content" class="mb-2 text-sm font-medium text-gray-900">Content</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2 min-h-[200px] max-h-[500px] overflow-y-auto">{!! nl2br(e($ticket['content'])) !!}</div>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Additional Attachment
            </p>
            <div class="row flex flex-col gap-2 flex-1 justify-start">
                @php
                    $ticketAttachment = $ticket['attachments'];
                @endphp
                <a href="{{ url("ticket_attachments/$ticketAttachment") }}" target="_blank" class="flex flex-row items-center gap-2 text-sm text-blue-500 font-semibold hover:underline text-right">View Attachment<i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                <img src="{{ url("ticket_attachments/$ticketAttachment") }}" class="w-auto md:max-w-[50%] max-w-full border border-gray-300">
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Belongs To Orders / Fulfillments
            </p>
            @if (empty($ticket['fulfillments']) && empty($ticket['orders']))
                Nothing
            @else
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-500 min-width">
                    <thead class="text-sm text-gray-500 font-semibold bg-gray-200"  style="text-align: center !important;">
                        <tr>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-5">
                                Index
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-25">
                                Target Type
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-70">
                                Target Brief Details
                            </th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center !important;" id="add_new_row_container">
                        @php
                            $currentIndex = 0;
                        @endphp
                        @if (!empty($ticket['fulfillments']))
                            @foreach ($ticket['fulfillments'] as $index => $fulfillment)
                                @php
                                    $currentIndex++;
                                @endphp 
                            <th scope="col" class="py-3">
                                {{ $currentIndex }}
                            </th>                  
                            <td scope="row" class="px-2 py-2 font-semibold text-gray-900">
                                Fulfillment
                            </td>
                            <td scope="row" class="px-2 py-2 whitespace-nowrap font-semibold text-gray-900">
                                <a target="_blank" href="{{ route('customer.fulfillment.show', ['fulfillment' => $fulfillment['id']]) }}" class="hover:underline hover:text-gray-500">Fulfillment #{{ $fulfillment['id'] }} - {{ e($fulfillment['name']) }} (<span class="text-{{ FulfillmentEnum::MAP_SHIPPING_COLORS[$fulfillment['shipping_status']] }}-600">{{ FulfillmentEnum::MAP_SHIPPING_STATUSES[$fulfillment['shipping_status']] ?? 'Waiting' }}</span>)</a>
                            </td>
                            @endforeach
                        @endif
                        @if (!empty($ticket['fulfillments']))
                            @foreach ($ticket['orders'] as $index => $order)
                            @php
                                $currentIndex++;
                            @endphp 
                            <th scope="col" class="py-3">
                                {{ $currentIndex }}
                            </th>                  
                            <td scope="row" class="px-2 py-2 font-semibold text-gray-900">
                                Order
                            </td>
                            <td scope="row" class="px-2 py-2 whitespace-nowrap font-semibold text-gray-900">
                                Order #{{ $order['id'] }}
                            </td>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection