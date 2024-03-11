@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All Support Tickets</h2>
    <div class="w-full flex justify-end mb-3 items-center">
        <a href="{{ route('admin.ticket.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add ticket
        </a>
    </div>
    @include('admin.ticket.filter')    
    @if (empty($tickets['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('admin.layout.pagination')
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
                        Customner
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Ticket Title
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Created By
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        No. of Comments
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Note
                    </th>
                    <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                        Solved By
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
                        <a href="{{ route('admin.ticket.show', ['ticket' => $ticket['id']]) }}" target="_blank" class="flex items-center text-blue-700 justify-center hover:underline hover:text-blue-500">#{{ $ticket['id'] }}<i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-2"></i></a>
                    </th>
                    <th scope="row" class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.customer.show', ['customer' => $ticket['customer']['id']]) }}" class="underline hover:text-gray-400" target="_blank">
                            {{ $ticket['customer']['customer_id'] }} {{ $ticket['customer']['full_name'] }}
                        </a>
                    </th>
                    <th scope="row" class="px-6 py-4 whitespace-nowrap">
                        {{ $ticket['title'] ?? '' }}
                    </th>
                    <td scope="row" class="px-6 py-4 whitespace-nowrap font-medium">
                        @if ($ticket['user_created']['target'] == User::TARGET_CUSTOMER)
                            <a href="{{ route('admin.customer.show', ['customer' => $ticket['user_created']['owner']['id']]) }}" class="underline hover:text-gray-400" target="_blank">
                                <b>[CUSTOMER]</b> {{ $ticket['user_created']['owner']['customer_id'] ?? 'a' }} {{ $ticket['user_created']['owner']['full_name'] ?? 'a' }}
                            </a>
                        @elseif ($ticket['user_created']['target'] == User::TARGET_STAFF)
                            <a href="{{ route('admin.staff.show', ['staff' => $ticket['user_created']['owner']['id']]) }}" class="underline hover:text-gray-400" target="_blank">
                                <b>[STAFF]</b> {{ $ticket['user_created']['owner']['full_name'] ?? '' }} ({{ Staff::MAP_POSITIONS[$ticket['user_created']['owner']['position']] ?? '' }})
                            </a>                            
                        @else
                            <a href="{{ route('admin.supplier.show', ['supplier' => $ticket['user_created']['owner']['id']]) }}" class="underline hover:text-gray-400" target="_blank">
                                <b>[SUPPLIER]</b> {{ $ticket['user_created']['owner']['full_name'] ?? '' }}
                            </a>
                        @endif
                    </td>
                    <td scope="row" class="px-6 py-4 whitespace-nowrap ">
                        {{ count($ticket['comments']) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap ">
                        {{ e($ticket['note'] ?? '') }}
                    </td>
                    <td scope="row" class="px-6 py-4 whitespace-nowrap">
                        @if (!empty($ticket['user_solved']))
                            @if ($ticket['user_solved']['target'] == User::TARGET_STAFF)
                                <a href="{{ route('admin.staff.show', ['staff' => $ticket['user_created']['owner']['id']]) }}" class="underline hover:text-gray-400" target="_blank">
                                    <b>[STAFF]</b> {{ $ticket['user_solved']['owner']['full_name'] ?? '' }} ({{ Staff::MAP_POSITIONS[$ticket['user_solved']['owner']['position']] ?? '' }})
                                </a>
                            @else
                                <a href="{{ route('admin.supplier.show', ['supplier' => $ticket['user_created']['owner']['id']]) }}" class="underline hover:text-gray-400" target="_blank">
                                    <b>[SUPPLIER]</b> {{ $ticket['user_solved']['owner']['full_name'] ?? '' }}
                                </a>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{ $ticket['solved_date'] ?? '' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $ticket['created_at'] }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="h-full flex gap-4">
                            <a href="{{ route('admin.ticket.show', ['ticket' => $ticket['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
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