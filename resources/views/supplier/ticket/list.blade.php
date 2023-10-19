@extends('supplier.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('supplier.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All Support Tickets</h2>
    <div class="w-full flex justify-end mb-3 items-center">
        <a href="{{ route('supplier.ticket.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add ticket
        </a>
    </div>
    @include('supplier.ticket.filter')    
    @if (empty($tickets['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('supplier.layout.pagination')
    <div class="w-full overflow-x-auto mb-2">
        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
        <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg min-width">
            <thead class="text-xs text-white font-semibold bg-indigo-950"  style="text-align: center !important;">
                <tr>
                    <th scope="col" class="pl-4 sm:py-3 py-2">
                        Index
                    </th>
                    <th scope="col" class="px-4 sm:py-3 py-2 whitespace-nowrap">
                        <div class="flex items-center">
                            Current Status
                        </div>
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Ticket ID
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Customer Complaint
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Ticket Title
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        No. of Comments
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Note
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Date Solved
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
                @foreach($tickets['data'] as $index => $ticket)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <th scope="col" class="pl-4 py-3">
                        {{ $index + 1 }}
                    </th>
                    <th scope="row" class=" py-4 font-medium whitespace-nowrap text-[12px]">
                        <span class="bg-{{ $ticketStatusColors[$ticket['status']] }}-500 py-2 px-3 rounded-lg text-white">{{ Str::upper($ticketStatuses[$ticket['status']]) }}</span>
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <a href="{{ route('supplier.ticket.show', ['ticket' => $ticket['id']]) }}" target="_blank" class="flex items-center justify-center hover:underline hover:text-blue-500">#{{ $ticket['id'] }}<i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-2"></i></a>
                    </th>
                    <th scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ $ticket['customer']['customer_id'] ?? '' }} {{ $ticket['customer']['full_name'] }}
                    </th>
                    <th scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ e(Str::limit($ticket['title'] ?? '', 40, $end='...')) }}
                    </th>
                    <td scope="row" class="px-6 py-4 whitespace-nowrap ">
                        {{ count($ticket['comments']) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ e(Str::limit($ticket['note'] ?? '', 40, $end='...')) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $ticket['solved_date'] ?? '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $ticket['created_at'] }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="h-full flex gap-4">
                            <a href="{{ route('supplier.ticket.show', ['ticket' => $ticket['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
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