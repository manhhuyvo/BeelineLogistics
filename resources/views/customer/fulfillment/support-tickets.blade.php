<div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 flex flex-col gap-3 px-3 py-3 justify-center" id="services">
    <p class="text-lg font-medium text-blue-600 mt-1">
        Support Tickets
    </p>
    @if (empty($fulfillment['support_tickets']))
        <p class="text-gray-600 font-semibold text-sm italic">This fulfillment doesn't have any support tickets attached.</p>
    @else
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left text-gray-500 min-width border-collapse">
            <thead class="text-sm text-gray-500 font-semibold bg-gray-200"  style="text-align: center !important;">
                <tr>
                    <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-5">
                        Index
                    </th>
                    <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-70">
                        Ticket Details
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentIndex = 0;
                @endphp
                    @foreach ($fulfillment['support_tickets'] as $index => $ticket)
                        @php
                            $currentIndex++;
                        @endphp 
                        <tr>
                            <th scope="col" class="border py-3 text-center">
                                {{ $currentIndex }}
                            </th>
                            <td scope="row" class="border px-2 py-2 whitespace-nowrap font-semibold text-gray-900">
                                <span class="font-normal">[{{ $ticket['created_at'] }}]</span>
                                <span class="text-{{ $supportTicketStatusColors[$ticket['status']] }}-600">{{ Str::upper($supportTicketStatuses[$ticket['status']] ?? '') }} - </span>
                                <a target="_blank" href="{{ route('customer.ticket.show', ['ticket' => $ticket['id']]) }}" class="hover:underline hover:text-blue-500">
                                    Ticket #{{ $ticket['id'] }} - {{ e($ticket['title']) }}</a>
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>