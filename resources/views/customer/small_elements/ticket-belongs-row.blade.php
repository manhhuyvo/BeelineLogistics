<div class="md:w-[70%] w-full flex sm:flex-row items-end flex-col gap-2 flex-1 bg-gray-50 border-solid border-[1px] border-gray-500 rounded-lg py-3 m-0 sm:gap-0 gap-3">
    <div class="flex flex-col flex-1 md:w-auto w-full">
        <input type="hidden" name="item_type[]" value="{{ $target }}"/>
        <label class="mb-2 text-sm font-medium text-gray-900">{{ Str::upper($target ?? 'Target') }}</label>
        <select name="item_id[]" class="select_items bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
        @if (empty($item))
            <option selected value=''>Select a {{ $target ?? 'target' }} to assign...</option>
        @endif
        @foreach($targetsList as $key => $value)
            @if (!empty($item['item_id']) && $product['item_id'] == $key)
                <option selected value="{{ $key }}">{{ $value }}</option>
            @else
                <option value="{{ $key }}">{{ $value }}</option>
            @endif
        @endforeach
        </select>
    </div>
    <button type="button" id="remove-row" class="px-2.5 py-1.5 rounded-[5px] text-[12px] bg-red-600 text-white font-medium hover:bg-red-500 flex items-center gap-2 w-fit h-fit" onclick="removeRow($(this))">
        <i class="fa-solid fa-trash"></i>
        Remove
    </button>
</div>

<script>
    
    $(document).ready(function() {
        $('.select_items').select2();
        setUpSearchableDropdowns()
    })

    // Override default style for Select2 dropdowns
    function setUpSearchableDropdowns()
    {
        // Set outter container's styling
        $('.select2-container').addClass("bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full h-fit p-1.5");
        $('.select2-container').attr('style', '');
        // Set inner div for dropdown
        $('.select2-selection').addClass("bg-transparent border-0");
        // Hide the default ugly arrow
        $('.select2-selection__arrow').addClass("hidden");
    }

    // remove row
    function removeRow(src)
    {
        src.parent().remove();
    }
</script>