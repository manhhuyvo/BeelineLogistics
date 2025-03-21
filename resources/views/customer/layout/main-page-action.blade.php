<div class="w-full flex flex-col gap-1 justify-start mt-3">
    <p class="text-[15px] font-normal text-red-500 ml-1 w-full font-bold" id="selected-row-count-message">
        <!-- Append the number of rows counted message here -->
    </p>
    <h2 class="text-2xl font-medium mt-2 mb-3">Bulk Actions</h2>
    <div class="flex md:flex-row flex-col md:items-center gap-3">
        @if (!empty($updateStatusAvailable))
        <div class="flex flex-col items-center gap-2 flex-1 rounded-lg border-solid border-[2px] border-blue-500 p-3 bg-blue-50 min-h-[120px]">    
            <p class="w-full text-sm font-semibold text-blue-500 min-w-[175px]">Update status for selected records</p>    
            <select id="bulk_action_dropdown" name="bulk_action" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full">
                <option selected disabled>Please choose a status</option>
                @foreach($bulkActions as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <button type="button" onclick="performAction($(this))" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-blue-600 text-white font-medium hover:bg-blue-500 flex items-center gap-2 flex gap-1 items-center justify-center w-full">
                Update Status
            </button>
        </div>
        @endif
        <form class="flex flex-col items-center gap-2 flex-1 rounded-lg border-solid border-[2px] border-yellow-500 p-3 bg-yellow-50 min-h-[121px] min-w-[175px] md:max-w-[50%] w-full" method="POST" action='{{ route("{$exportRoute}") }}' id="export-form">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <p class="w-full text-sm font-semibold text-yellow-500 min-w-[175px]">Export selected records into a file</p> 
            <select name="export_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-[13px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full pl-3 pr-4 py-2.5">
                <option selected disabled>Choose an export type</option>
                @foreach(GeneralEnum::MAP_EXPORT_TYPES as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <button type="button" onclick="exportAction($(this))" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-yellow-600 text-white font-medium hover:bg-yellow-500 flex justify-center items-center gap-1 w-full">
                Export Data
            </button>
        </form>
    </div>
</div>

<script>
    let selectAllRowsCheckbox = $('#select_all_rows');
    let selectedRowsInputs = $('.selected_rows');
    let countMessageContainer = $('#selected-row-count-message');

    // Forms for bulk actions
    let mainPageForm = $('#main-page-form');
    let exportForm = $('#export-form');
    let generateInvoiceForm = $('#generate-invoice-form');
    
    let bulkActionField = $('#bulk_action');
    let bulkActionDropdown = $('#bulk_action_dropdown');

    $(document).ready(function () {
        @if (!empty($bulkActions))
        bulkActionDropdown.select2();
        setUpSearchableDropdowns();
        @endif
        // Event for select all rows checkbox
        selectAllRowsCheckbox.on('change', function() {
            // If select_all checkbox is checked
            if ($(this).is(':checked')) {
                // Set all other single rows checkbox to checked
                selectedRowsInputs.prop('checked', true)
                // Trigger the single row checkbox event listener
                selectedRowsInputs.change();
            } else {
                // Set all other single rows checkbox to unchecked
                selectedRowsInputs.prop('checked', false)
                // Trigger the single row checkbox event listener
                selectedRowsInputs.change();
            }
        });

        // Event for a single row checkbox
        selectedRowsInputs.on('change', function() {
            var total = mainPageForm.find('input[name="selected_rows[]"]:checked').length;
            setCountMessage(total);
            if ($(this).is(':checked')) {

            } else {              
                // If a single checkbox is unchecked, then we uncheck the select_all_rows checkbox       
                selectAllRowsCheckbox.prop('checked', false)
                if (total == 0) {

                }
            }
        });

        // event for selecting bulk action
        bulkActionDropdown.on('change', function() {
            // Assign the selected action for the hidden field in the main form
            bulkActionField.val(bulkActionDropdown.val());
        });
    })

    // Set count rows message
    function setCountMessage(count = '')
    {
        if (count == '') {
            countMessageContainer.text('')
        } else {
            countMessageContainer.text(`${count} records selected.`)
        }
    }

    // Submit form for bulk action
    function performAction(src)
    {
        mainPageForm.submit();
    }

    // Submit form for bulk action
    function exportAction(src)
    {
        selectedRowsInputs.map(function() {
            if ($(this).is(':checked')) {
                exportForm.append("<input type='hidden' name='selected_rows[]' value='" + $(this).val() + "' />");
            }
        });
        exportForm.submit();
    }
</script>