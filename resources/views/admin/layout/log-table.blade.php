@if (empty($logData['data']))
<p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
@else

@include('admin.layout.pagination')
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
    <tbody style="text-align: center !important;">
        @foreach ($logData['data'] as $entry)
        <tr class="bg-white border-b hover:bg-gray-50">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                #{{ $entry['target']['id'] }} {{ $entry['target']['full_name'] ?? '' }} ({{ StaffEnum::MAP_POSITIONS[$entry['target']['position']] ?? '' }})
            </th>
            <td class="px-6 py-4 whitespace-nowrap">
                {{ $entry['description'] ?? '' }}
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                {{ UserEnum::MAP_TARGETS[$entry['actionUser']['target']] }} #{{ $entry['actionUser']['owner']['id'] ?? '' }} ({{ $entry['actionUser']['owner']['full_name'] ?? '' }})
            </th>
            <td class="px-6 py-4 whitespace-nowrap">
                {{ $entry['created_at'] }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif