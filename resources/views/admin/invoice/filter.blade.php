<div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-md border-solid border-[1px] border-gray-200 py-1">
    <div class="flex justify-between items-center">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Filter table
        </p>
        <p class="text-sm px-3 py-1 font-medium text-yellow-500 cursor-pointer hover:underline" id="toggle-filter">
            <i class="fa-solid fa-eye-slash mr-2"></i>
            Hidden
        </p>
    </div>
    <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.invoice.list') }}" method="get" id="filter-form">
        <div class="row flex gap-2 sm:flex-row flex-col">
            <div class="flex flex-col flex-1">
                <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Invoice ID</label>
                <input type="text" name="id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Invoice ID" value="{{ $request['id'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="total_amount_from" class="mb-2 text-sm font-medium text-gray-900">Total Amount From</label>
                <input type="number" step="0.01" name="total_amount_from" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['total_amount_from'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="total_amount_to" class="mb-2 text-sm font-medium text-gray-900">Total Amount To</label>
                <input type="number" step="0.01" name="total_amount_to" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['total_amount_to'] ?? '' }}">
            </div>
        </div>
        <div class="row flex gap-2 sm:flex-row flex-col">
            <div class="flex flex-col flex-1">
                <label for="reference" class="mb-2 text-sm font-medium text-gray-900">Reference</label>
                <input type="text" name="reference" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Invoice Reference" value="{{ $request['reference'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Customer</label>
                <select id="customer_id" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['customer_id']))
                    <option selected disabled>Choose a customer</option>
                    @else
                    <option disabled>Choose a customer</option>
                    @endif                    
                @foreach($customersList as $key => $value)
                    @if (!empty($request['customer_id']) && $request['customer_id'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1">
                <label for="staff_id" class="mb-2 text-sm font-medium text-gray-900">Staff Created</label>
                <select id="staff_id" name="staff_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['staff_id']))
                    <option selected disabled>Choose a staff</option>
                    @else
                    <option disabled>Choose a staff</option>
                    @endif                    
                @foreach($staffsList as $key => $value)
                    @if (!empty($request['staff_id']) && $request['staff_id'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
        </div>
        <div class="row flex gap-2 sm:flex-row flex-col">
            <div class="flex flex-col flex-1">
                <label for="status" class="mb-2 text-sm font-medium text-gray-900">Invoice Status</label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['status']))
                    <option selected disabled>Choose a status</option>
                    @else
                    <option disabled>Choose a status</option>
                    @endif                    
                @foreach($invoiceStatuses as $key => $value)
                    @if (!empty($request['status']) && $request['status'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1">
                <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Payment Status</label>
                <select id="product_payment_status" name="product_payment_status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['payment_status']))
                    <option selected disabled>Choose a status</option>
                    @else
                    <option disabled>Choose a status</option>
                    @endif                    
                @foreach($paymentStatuses as $key => $value)
                    @if (!empty($request['payment_status']) && $request['payment_status'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
        </div>
        <div class="row flex-col items-center px-2 gap-2">
            <p class="text-sm font-medium text-gray-900 p-0">Date Created</p>
            <div class="row flex gap-2 sm:flex-row flex-col px-2 py-4 rounded-lg border-solid border-[2px] border-gray-500 bg-gray-100">
                <div class="flex flex-col flex-1">
                    <label for="created_date_from" class="mb-2 text-sm font-medium text-gray-900">From</label>
                    <input type="date" name="created_date_from" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['created_date_from'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="created_date_to" class="mb-2 text-sm font-medium text-gray-900">To</label>
                    <input type="date" name="created_date_to" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['created_date_to'] ?? '' }}">
                </div>
            </div>
        </div>
        <div class="row flex-col items-center px-2 gap-2">
            <p class="text-sm font-medium text-gray-900 p-0">Due Date</p>
            <div class="row flex gap-2 sm:flex-row flex-col px-2 py-4 rounded-lg border-solid border-[2px] border-gray-500 bg-gray-100">
                <div class="flex flex-col flex-1">
                    <label for="due_date_from" class="mb-2 text-sm font-medium text-gray-900">From</label>
                    <input type="date" name="due_date_from" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['due_date_from'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="due_date_to" class="mb-2 text-sm font-medium text-gray-900">To</label>
                    <input type="date" name="due_date_to" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['due_date_to'] ?? '' }}">
                </div>
            </div>
        </div>
        <div class="row flex justify-end px-3 gap-2 sm:mt-0 mt-1">
            <button type="submit" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                <i class="fa-solid fa-filter sm:text-[11px] text-[9px]"></i>
                Filter
            </button>
            <a href="{{ route('admin.invoice.list') }}" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
                <i class="fa-solid fa-xmark sm:text-[12px] text-[10px]"></i>
                Clear
            </a>
        </div>
    </form>
</div>
<script>
    let filterForm = $('#filter-form');
    let toggleFilterBtn = $('#toggle-filter');

    filterForm.hide();
    $(document).ready(function () {
        $('#customer_id').select2();
        $('#staff_id').select2();
        setUpSearchableDropdowns();

        toggleFilterBtn.on('click', function() {
            if (filterForm.is(':visible')) {
                filterForm.hide();

                toggleFilterBtn.html("<i class='fa-solid fa-eye-slash mr-2'></i>Hidden")
            } else {
                filterForm.show();

                toggleFilterBtn.html("<i class='fa-solid fa-eye mr-2'></i>Visible")
            }
        })
    })

    // Override default style for Select2 dropdowns
    function setUpSearchableDropdowns()
    {
        // Set outter container's styling
        $('.select2-container').addClass("bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full h-fit p-1.5");
        $('.select2-container').attr('style', '');
        // Set inner div for dropdown
        $('.select2-selection').addClass("bg-transparent border-0");
        // Hide the default ugly arrow
        $('.select2-selection__arrow').addClass("hidden");
    }
</script>