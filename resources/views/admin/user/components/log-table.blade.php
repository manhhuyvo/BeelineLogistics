@if (empty($logData['data']))
<p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
@else

@include('admin.layout.pagination')
<div class="w-full overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg">
        <thead class="text-xs text-white font-semibold uppercase bg-indigo-950">
            <tr>
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Target
                </th>            
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Description
                </th>
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Action By
                </th>
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Date
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logData['data'] as $entry)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900">
                    <a href="{{ route('admin.user.show', ['user' => $entry['target']['id']]) }}" class="underline text-blue-700 hover:text-blue-500" target="_blank">
                        #{{ $entry['target']['id'] }} {{ $entry['target']['username'] ?? '' }}
                    </a>
                </th>
                <td class="px-6 py-4 whitespace-nowrap">
                    {!! nl2br(e($entry['description'] ?? '')) !!}
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    @if (!empty($entry['action_user']))
                        @switch($entry['action_user']['target'] ?? '')
                            @case (UserEnum::TARGET_STAFF)
                                <a href="{{ route('admin.staff.show', ['staff' => $entry['action_user']['staff']['id']]) }}" class="underline text-blue-700 hover:text-blue-500" target="_blank">
                                    {{ UserEnum::MAP_TARGETS[$entry['action_user']['target']] }} #{{ $entry['action_user']['staff']['id'] ?? '' }} ({{ $entry['action_user']['staff']['full_name'] ?? '' }})
                                </a>
                                @break
                            @case (UserEnum::TARGET_CUSTOMER)
                                <a href="{{ route('admin.customer.show', ['customer' => $entry['action_user']['customer']['id']]) }}" class="underline text-blue-700 hover:text-blue-500" target="_blank">
                                    {{ UserEnum::MAP_TARGETS[$entry['action_user']['target']] }} {{ $entry['action_user']['customer']['customer_id'] ?? '' }} ({{ $entry['action_user']['customer']['full_name'] ?? '' }})
                                </a>
                                @break
                            @case (UserEnum::TARGET_SUPPLIER)
                                <a href="{{ route('admin.supplier.show', ['staff' => $entry['action_user']['supplier']['id']]) }}" class="underline text-blue-700 hover:text-blue-500" target="_blank">
                                    {{ UserEnum::MAP_TARGETS[$entry['action_user']['target']] }} #{{ $entry['action_user']['supplier']['id'] ?? '' }} ({{ $entry['action_user']['supplier']['full_name'] ?? '' }})
                                </a>
                                @break
                            @default
                                Not known
                                @break
                        @endswitch
                    @endif
                </th>
                <td class="px-6 py-4">
                    {{ $entry['created_at'] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif