@if (empty($logData['data']))
<p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
@else

@include('admin.layout.pagination')
<div class="w-full overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg">
        <thead class="text-xs text-white font-semibold uppercase bg-indigo-950">
            <tr>
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Staff
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
                    <a href="{{ route('admin.staff.show', ['staff' => $entry['target']['id']]) }}" class="underline text-blue-700 hover:text-blue-500" target="_blank">
                        #{{ $entry['target']['id'] }} {{ $entry['target']['full_name'] ?? '' }} ({{ StaffEnum::MAP_POSITIONS[$entry['target']['position']] ?? '' }})
                    </a>
                </th>
                <td class="px-6 py-4 whitespace-nowrap">
                    {!! nl2br(e($entry['description'] ?? '')) !!}
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    @if (!empty($entry['action_user']))
                        <a href="{{ route('admin.staff.show', ['staff' => $entry['action_user']['staff']['id']]) }}" class="underline text-blue-700 hover:text-blue-500" target="_blank">
                            {{ UserEnum::MAP_TARGETS[$entry['action_user']['target']] }} #{{ $entry['action_user']['staff']['id'] ?? '' }} ({{ $entry['action_user']['staff']['full_name'] ?? '' }})
                        </a>
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