@if (!empty($pagination))
<nav class="flex flex-col items-start justify-between pb-2 px-[2px]" aria-label="Table navigation">
    <div class="text-sm sm:flex-1 w-full inline-flex justify-end sm:my-0 my-2 relative">
        @if (count($pagination['links']) >= 7)
        <button id="paginate-btn-dropdown" class="cursor-pointer bg-gray-100 hover:bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 flex gap-2 items-center justify-center py-2 text-gray-600 w-[80px]">
            Page {{ $pagination['current_page'] }}
            <i class="fa-solid fa-caret-down"></i>
        </button>
        <div id="paginate-dropdown-list" class="cursor-pointer bg-gray-100 border border-gray-300 text-[13px] rounded-lg flex flex-col items-center text-gray-600 max-h-[250px] overflow-y-auto absolute top-100 mt-0.5">
            @foreach($pagination['links'] as $button)
                @if ($button['active'])
                <a href="{{ $button['url'] }}" aria-current="page" class="flex items-center justify-center py-2 ml-0 text-blue-500 border-y border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 font-medium w-[80px]">Page {{ html_entity_decode($button['label']) }}</a>
                @elseif ($button['url'])
                <a href="{{ $button['url'] }}" class="flex items-center justify-center py-2 ml-0 leading-tight text-gray-600 hover:bg-blue-100 hover:text-blue-700 font-medium w-[75px]">Page {{ html_entity_decode($button['label']) }}</a>
                @endif
            @endforeach
        </div>
        @else
        <div class="text-sm sm:w-auto w-full flex justify-end sm:mt-0 mt-2">
            @foreach($pagination['links'] as $button)
            <div>
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
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <span class="text-sm font-normal text-gray-500 w-full flex justify-end gap-1">
        Showing <span class="font-semibold text-gray-900">{{ $pagination['from'] }} - {{ $pagination['to']}}</span> of total <span class="font-semibold text-gray-900">{{ $pagination['total'] }}</span> records
    </span>
</nav>
<script>
    let paginateBtnDropdown = $('#paginate-btn-dropdown');
    let paginateDropdownList = $('#paginate-dropdown-list');

    paginateDropdownList.hide();

    $(document).ready(function() {
        paginateBtnDropdown.on('click', function() {
            if (paginateDropdownList.is(':hidden')) {
                paginateDropdownList.show();
            } else if (paginateDropdownList.is(':visible')) {
                paginateDropdownList.hide();
            }
        })
    })
</script>
@endif