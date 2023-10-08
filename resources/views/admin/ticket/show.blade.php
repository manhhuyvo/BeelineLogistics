@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View Ticket Details</h2>
    <div class="relative w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <h2 class="md:text-xl text-[13px] font-bold absolute sm:top-3 top-2 sm:right-3 right-2 text-{{ $supportTicketStatusColors[$ticket['status']] }}-500 px-3 py-2 border-solid border-{{ $supportTicketStatusColors[$ticket['status']] }}-500 border-[3px] rounded-lg">{{ Str::upper($supportTicketStatuses[$ticket['status']]) }}</h2>
        <div class="w-full flex flex-col px-3 py-2 justify-center">
            <div class="flex sm:flex-row flex-col md:gap-3 gap-2 mb-1">
                <span class="text-lg font-medium text-blue-600 flex items-end h-full">Ticket Details</span>
                <div class="flex gap-2 w-fit items-center">
                    @if ($ticket['status'] == SupportTicketEnum::STATUS_SOLVED || $ticket['status'] == SupportTicketEnum::STATUS_DELETED)
                    <form method="POST" action="{{ route('admin.ticket.active', ['ticket' => $ticket['id']]) }}">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
                        <button type="submit" class="px-2.5 py-[3px] rounded-[5px] text-[12px] bg-red-600 text-white font-medium w-fit hover:bg-red-500 flex items-center gap-1">
                            Active<i class="fa-solid fa-xmark text-[9px]"></i>
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.ticket.solve', ['ticket' => $ticket['id']]) }}">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
                        <button type="submit" class="px-2.5 py-[3px] rounded-[5px] text-[12px] bg-green-600 text-white font-medium w-fit hover:bg-green-500 flex items-center gap-1">
                            Solve<i class="fa-solid fa-check text-[9px]"></i>
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.ticket.delete', ['ticket' => $ticket['id']]) }}">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
                        <button type="submit" class="px-2.5 py-[3px] rounded-[5px] text-[12px] bg-gray-600 text-white font-medium w-fit hover:bg-gray-500 flex items-center gap-1">
                            Delete<i class="fa-solid fa-trash text-[9px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="mt-1 mb-3 flex md:flex-row flex-col md:items-center gap-1">
                @if ($ticket['status'] == SupportTicketEnum::STATUS_SOLVED && !empty($ticket['user_solved']) && !empty($ticket['solved_date']))
                    <div class="text-[12px] font-medium text-green-500 h-full flex items-end w-fit">
                        Solved by
                        @if ($ticket['user_solved']['target'] == User::TARGET_CUSTOMER)
                        {{ $ticket['user_solved']['owner']['customer_id'] ?? '' }} - {{ $ticket['user_solved']['owner']['full_name'] ?? '' }}
                        @elseif (!empty($ticket['user_solved']) && $ticket['user_solved']['target'] == User::TARGET_STAFF)
                        ({{ Staff::MAP_POSITIONS[$ticket['user_solved']['owner']['position']] ?? '' }}) - {{ $ticket['user_solved']['owner']['full_name'] ?? '' }}
                        @else
                        {{ $ticket['user_solved']['owner']['full_name'] ?? 'Not Known' }}
                        @endif
                    </div>
                <span class="text-[12px] italic text-gray-500">- {{ Carbon::parse($ticket['solved_date'])->format('d M Y') }} at {{ Carbon::parse($ticket['solved_date'])->format('H:i:s') }}</span>
                @endif
            </div>
            <div class="row flex md:flex-row flex-col gap-2 flex-1 mb-3">
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Customer</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $ticket['customer']['customer_id'] ?? '' }} - {{ $ticket['customer']['full_name'] ?? '' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Created By</label>
                    @if (!empty($ticket['user_created']) && $ticket['user_created']['target'] == User::TARGET_CUSTOMER)
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $ticket['user_created']['owner']['customer_id'] ?? '' }} - {{ $ticket['user_created']['owner']['full_name'] ?? '' }}</div>
                    @elseif (!empty($ticket['user_created']) && $ticket['user_created']['target'] == User::TARGET_STAFF)
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">({{ Staff::MAP_POSITIONS[$ticket['user_created']['owner']['position']] ?? '' }}) - {{ $ticket['user_created']['owner']['full_name'] ?? '' }}</div>
                    @else
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $ticket['user_created']['owner']['full_name'] ?? '' }}</div>
                    @endif
                </div>
                <div class="flex flex-col flex-1">
                    <label for="created_at" class="mb-2 text-sm font-medium text-gray-900">Date Created</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ $ticket['created_at'] }}</div>
                </div>
            </div>
            <div class="row flex flex-col gap-2 flex-1 justify-start mb-3">
                <div class="flex flex-col">
                    <label for="title" class="mb-2 text-sm font-medium text-gray-900">Title</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2">{{ e($ticket['title'] ?? '') }}</div>
                </div>
                <div class="flex flex-col">
                    <label for="content" class="mb-2 text-sm font-medium text-gray-900">Content</label>
                    <div class="bg-gray-100 border-gray-300 text-gray-900 text-sm w-full py-2.5 px-2 min-h-[200px] max-h-[500px] overflow-y-auto">{!! nl2br(e($ticket['content'])) !!}</div>
                </div>
            </div>
            @if (!empty($ticket['attachments']))
            <p class="text-lg font-medium text-blue-600 mt-1 mb-3">
                Additional Attachment
            </p>
            <div class="row flex flex-col gap-2 flex-1 justify-start mb-3 px-3">
                @php
                    $ticketAttachment = $ticket['attachments'];
                    $noImage = url('assets/images/unavailable.png');
                @endphp
                <a href="{{ url("ticket_attachments/$ticketAttachment") }}" target="_blank" class="flex flex-row items-center p-0 w-fit gap-2 text-sm text-blue-500 font-semibold hover:underline">View Attachment<i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                <div class="bg-gray-50 px-3 py-3 w-auto md:max-w-[50%] max-w-full h-auto border border-gray-300 border-[3px]">
                    <img alt="attachment" onerror="this.onerror=null; this.src='{{ $noImage }}'" src="{{ url("ticket_attachments/$ticketAttachment") }}" class="max-w-full">
                </div>
            </div>
            @endif
            <div class="w-full flex items-center">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 w-full" id="tabs">
                    <li class="mr-2">
                        <a href="#comments" data-id="comments" aria-current="page" class="inline-block px-4 py-2 hover:text-gray-600 hover:bg-gray-50 text-blue-600 bg-gray-100 rounded-t-lg active">Comments</a>
                    </li>
                    <li class="mr-2">
                        <a href="#services" data-id="services" class="inline-block px-4 py-2 rounded-t-lg hover:text-gray-600 hover:bg-gray-50">Services</a>
                    </li>
                </ul>
            </div>
            <div class="w-full flex flex-col gap-3 border-b border-x border-gray-200 py-2 px-2.5 tabContainers" id="services">
                <p class="text-lg font-medium text-blue-600 mt-1">
                    Belongs To Orders / Fulfillments
                </p>
                @if (empty($ticket['fulfillments']) && empty($ticket['orders']))
                    <p class="text-gray-600 font-semibold text-sm italic">This support ticket is not under any fulfillments or orders.</p>
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
                                    <tr>
                                        <th scope="col" class="py-3">
                                            {{ $currentIndex }}
                                        </th>                  
                                        <td scope="row" class="px-2 py-2 font-semibold text-gray-900">
                                            Fulfillment
                                        </td>
                                        <td scope="row" class="px-2 py-2 whitespace-nowrap font-semibold text-gray-900">
                                            <a target="_blank" href="{{ route('admin.fulfillment.show', ['fulfillment' => $fulfillment['id']]) }}" class="hover:underline hover:text-gray-500">Fulfillment #{{ $fulfillment['id'] }} - {{ e($fulfillment['name']) }} (<span class="text-{{ FulfillmentEnum::MAP_SHIPPING_COLORS[$fulfillment['shipping_status']] }}-600">{{ FulfillmentEnum::MAP_SHIPPING_STATUSES[$fulfillment['shipping_status']] ?? 'Waiting' }}</span>)</a>
                                        </td>
                                    </tr>
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
            </div>
            @include('admin.ticket.comments')
        </div>
    </div>
</div>

<script>
    const servicesTabBtn = $('#servicesTabBtn');
    const commentsTabBtn = $('#commentsTabBtn');

    const servicesTab = $('#services');
    const commentsTab = $('#comments');

    servicesTab.hide();
    $(document).ready(function() {
        $('#tabs li a').click()

        $('#tabs li a').on('click', function() {
            const tab = $(this).attr('data-id');

            if (!$(this).hasClass('active')) {          
                // Remove active classes from all the links      
                $('#tabs li a').removeClass('active');
                $('#tabs li a').removeClass('bg-gray-100 text-blue-600');
                $('#tabs li a').addClass('hover:bg-gray-50 hover:text-gray-600');

                // Then add the active classes to this specific link
                $(this).addClass('active');
                $(this).addClass('bg-gray-100 text-blue-600');
                $(this).removeClass('hover:bg-gray-50 hover:text-gray-600');

                $('.tabContainers').hide();
                $('#' + tab).fadeIn();
            }
        })
    });
</script>
@endsection