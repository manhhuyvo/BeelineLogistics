@if (!empty($pagination))
<nav class="flex items-end justify-between py-2 px-[2px] bg-white" aria-label="Table navigation">
    <span class="text-sm font-normal text-gray-500">Showing <span class="font-semibold text-gray-900">{{ $pagination['from'] }} - {{ $pagination['to']}}</span> of total <span class="font-semibold text-gray-900">{{ $pagination['total'] }}</span> records</span>
    <ul class="inline-flex -space-x-px text-sm h-8">
        @foreach($pagination['links'] as $button)
        <li>
            @if ($button['active'])
                <a href="{{ $button['url'] }}" aria-current="page" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-blue-500 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 font-medium">
                    {{ html_entity_decode($button['label']) }}
                </a>
            @elseif ($button['url'])
                <a href="{{ $button['url'] }}" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-blue-500 hover:text-gray-700">
                    {{ html_entity_decode($button['label']) }}
                </a>
            @else            
                <a class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-blue-500 hover:text-gray-700">
                    {{ html_entity_decode($button['label']) }}
                </a>
            @endif
        </li>
        @endforeach
    </ul>
</nav>
@endif