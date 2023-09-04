<tr class="bg-white border-b hover:bg-gray-50">
    <td class="px-2 py-2">
        <button type="button" title="Remove row" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" onclick="removeRow($(this))">
            <i class="fa-solid fa-trash-can text-lg"></i>
        </button>
    </td>
    <td scope="row" class="px-2 py-2 text-center font-normal flex flex-col justify-start gap-2">
        <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50 relative">
            <select name="item_type[]" class="text-center bg-gray-50 border border-gray-300 text-gray-900 text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                <option selected disabled>Choose item type</option>             
            @foreach($createInvoiceFrom as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
            </select>
        </div>
        <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50 relative">
            <select name="target_id[]" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5 searchableDropdowns target_id_dropdowns">
                <option selected disabled>Choose item from lis</option>                  
            @foreach($customersList as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
            </select>
            <div class="text-sm h-full font-medium text-sm focus:ring-1 flex-1 p-2.5 bg-gray-200 border-none pl-3 rounded-r-lg">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>
    </td>
    <td scope="row" class="px-2 py-2 font-normal text-gray-900 whitespace-nowrap">
        <textarea type="text" name="description[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-1.5 text-center resize-none min-h-[100px]"></textarea>
    </td>
    <td scope="row" class="px-2 py-2 font-normal">
        <input type="number" min="0" step="0.01" name="price[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 text-center min-h-[100px]"/>
    </td>
    <td scope="row" class="px-2 py-2 font-normal">
        <input type="number" min="0" name="quantity[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 text-center min-h-[100px]">
    </td>
    <td scope="row" class="px-2 py-2 font-normal">
        <input type="number" min="0" step="0.01" name="amount[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 text-center min-h-[100px]">
    </td>
</tr>

<script>
    
    $(document).ready(function() {
        $('.searchableDropdowns').select2();
        $('.target_id_dropdown').parent().find('.select2-container').removeClass('rounded-r-lg');
        setUpSearchableDropdowns()
    })

    // Override default style for Select2 dropdowns
    function setUpSearchableDropdowns()
    {
        // Set outter container's styling
        $('.target_id_dropdown').parent().find('.select2-container').addClass("bg-white text-gray-600 text-[12px] w-full h-fit p-1.5 rounded-r-lg rounded-l-lg");
        // Set inner div for dropdown
        $('.select2-selection').addClass("bg-transparent border-0");
        // Hide the default ugly arrow
        $('.select2-selection__arrow').addClass("hidden");
    }

    // remove row
    function removeRow(src)
    {
        src.parent().parent().remove();
    }
</script>