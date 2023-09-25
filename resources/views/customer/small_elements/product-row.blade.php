<div class="flex sm:flex-row sm:items-end flex-col gap-2 flex-1 bg-gray-50 border-solid border-[1px] border-gray-500 rounded-lg py-3 m-0 sm:gap-0 gap-3">
    <div class="flex flex-col flex-1">
        <label class="mb-2 text-sm font-medium text-gray-900">Product Name</label>
        <select name="selected_products[]" class="select_products bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
        @if (empty($product))
            <option selected value=''>Add a product to list...</option>
        @endif
        @foreach($productsList as $key => $value)
            @if (!empty($product['product_id']) && $product['product_id'] == $key)
                <option selected value="{{ $key }}">{{ $value }}</option>
            @else
                <option value="{{ $key }}">{{ $value }}</option>
            @endif
        @endforeach
        </select>
    </div>
    <div class="flex flex-col flex-2">
        <label class="mb-2 text-sm font-medium text-gray-900">Quantity</label>
        <input type="text" name="selected_quantities[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Quantity" value="{{ $product['quantity'] ?? '' }}">
    </div>
    <button type="button" id="remove-row" class="px-2.5 py-1.5 rounded-[5px] text-[12px] bg-red-600 text-white font-medium hover:bg-red-500 flex items-center gap-2 w-fit h-fit" onclick="removeRow($(this))">
        <i class="fa-solid fa-trash"></i>
        Remove
    </button>
</div>

<script>
    
    $(document).ready(function() {
        $('.select_products').select2();
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