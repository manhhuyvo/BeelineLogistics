<tr class="bg-white border-b hover:bg-gray-50 invoice-row">
    <td class="px-2 py-2">
        <button type="button" title="Remove row" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" onclick="removeRow($(this))">
            <i class="fa-solid fa-trash-can text-lg"></i>
        </button>
    </td>
    <td scope="row" class="px-2 py-2 text-center font-normal flex flex-col justify-start gap-2">
        @if (isset($targetsList) && isset($target))
        <div class="w-full flex items-center text-gray-900 text-sm relative">
            <input type="hidden" name="item_type[]" value="{{ $target }}"/>
            <p class="font-semibold">{{ Str::upper($target) }}</p>
        </div>
        <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50 relative">
                <select name="target_id[]" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5 searchableDropdowns target_id_dropdowns">
                    <option selected disabled>Choose {{ $target }} from list</option>                  
                @foreach($targetsList as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
                </select>
                <div class="text-sm h-full font-medium text-sm focus:ring-1 flex-1 p-2.5 bg-gray-200 border-none pl-3 rounded-r-lg">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
        </div>
        @else
        <div class="w-full flex items-center text-gray-900 text-sm relative">
            <input type="hidden" name="item_type[]" value="{{ $target }}"/>
            <input type="hidden" name="target_id[]" value=""/>
            <p class="font-semibold">{{ Str::upper($target) }} DATA</p>
        </div>
        @endif
    </td>
    <td scope="row" class="px-2 py-2 font-normal text-gray-900 whitespace-nowrap">
        <textarea type="text" name="description[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-1.5 resize-none min-h-[100px]"></textarea>
    </td>
    <td scope="row" class="px-2 py-2 font-normal">
        <input type="number" min="0" step="0.01" name="price[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 text-center min-h-[100px] priceInput"/>
    </td>
    <td scope="row" class="px-2 py-2 font-normal">
        <input type="number" min="0" step="0.01" name="quantity[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 text-center min-h-[100px] quantityInput">
    </td>
    <td scope="row" class="px-2 py-2 font-normal">
        <input type="number" readonly min="0" step="0.01" name="amount[]" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 text-center min-h-[100px] amountInput">
    </td>
</tr>

<script>    
    $(document).ready(function() {
        $('.searchableDropdowns').select2();
        $('.target_id_dropdown').parent().find('.select2-container').removeClass('rounded-r-lg');
        setUpSearchableDropdowns()

        $('.priceInput').on('change', function() {
            let priceValue = $(this).val();
            let quantityValue = $(this).parent().parent().find('.quantityInput').val();
            calculateAmountRow(priceValue, quantityValue, $(this));
        });
        
        $('.quantityInput').on('change', function() {
            let priceValue = $(this).parent().parent().find('.priceInput').val();
            let quantityValue = $(this).val();
            calculateAmountRow(priceValue, quantityValue, $(this));
        });
        
        $('.amountInput').on('change', function() {
            let totalAmount = 0.00;
            $('input[name^="amount"]').each(function(index, object) {
                let value = object.value;
                totalAmount = totalAmount + parseFloat(value);
            })

            // Assign this total amount to the sub-total and Total
            $('.total-amount').html(totalAmount.toFixed(2))
        });
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
        // If we still have at least one row, then calculate the total amount
        if ($('.amountInput').length != 0) {
            $('.amountInput').change();
        } else {
            // Assign this total amount to the sub-total and Total
            $('.total-amount').html((0).toFixed(2))
        }
        
        // Set number of rows count
        let numberOfRows = $('.invoice-row').length;
        $('#total-item-count').html(numberOfRows);
    }

    function calculateAmountRow(price, quantity, src)
    {
        // If price is null, then set it as 0
        if (!price) {
            price = 0;
        }

        // If quantity is null, then set it as 0
        if (!quantity) {
            quantity = 0;
        }

        // Calculate the amount
        let amount = price * quantity;

        // Assign that amount into the input
        src.parent().parent().find('.amountInput').val(amount);
        $('.amountInput').change();
    }

    function showAccordingDropdown(src, type)
    {
        src.parent().parent().find(`.${type}`).find('select').attr('disabled', '');
        src.parent().parent().find(`.${type}`).show();
    }

    function hideAccordingDropdown(src, type)
    {
        src.parent().parent().find(`.${type}`).find('select').attr('disabled', 'disabled');
        src.parent().parent().find(`.${type}`).hide();
    }
</script>