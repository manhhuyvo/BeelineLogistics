<div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-md border-solid border-[1px] border-gray-200 py-1">
    <div class="flex justify-between items-center">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Filter table
        </p>
        <p class="text-sm px-3 py-1 font-medium text-yellow-500 cursor-pointer hover:underline" id="toggle-filter">
            <i class="fa-solid fa-eye mr-2"></i>
            Visible
        </p>
    </div>
    <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.fulfillment.list') }}" method="get" id="filter-form">
        <div class="row flex gap-2 sm:flex-row flex-col">
            <div class="flex flex-col flex-1">
                <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Fulfillment ID</label>
                <input type="text" name="id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Fulfillment ID" value="{{ $request['id'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="name" class="mb-2 text-sm font-medium text-gray-900">Receiver Name</label>
                <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Receiver Name" value="{{ $request['name'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Receiver Phone</label>
                <input type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Receiver Phone" value="{{ $request['phone'] ?? '' }}">
            </div>
        </div>
        <div class="row flex gap-2 sm:flex-row flex-col">
            <div class="flex flex-col flex-1">
                <label for="address" class="mb-2 text-sm font-medium text-gray-900">Receiver Address</label>
                <input type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Receiver Address" value="{{ $request['address'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="suburb" class="mb-2 text-sm font-medium text-gray-900">Receiver Suburb</label>
                <input type="text" name="suburb" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Receiver Suburb" value="{{ $request['suburb'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="country" class="mb-2 text-sm font-medium text-gray-900">Country</label>                
                <select id="country" name="country" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['country']))
                    <option selected disabled>Choose a country</option>
                    @else
                    <option disabled>Choose a country</option>
                    @endif                    
                @foreach(CurrencyAndCountryEnum::MAP_COUNTRIES as $key => $value)
                    @if (!empty($request['country']) && $request['country'] == $key)
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
                <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Customer Owner</label>
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
                <label for="staff_id" class="mb-2 text-sm font-medium text-gray-900">Staff Manage</label>
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
                <label for="shipping_type" class="mb-2 text-sm font-medium text-gray-900">Shipping Type</label>
                <select id="shipping_type" name="shipping_type" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['shipping_type']))
                    <option selected disabled>Choose a shipping type</option>
                    @else
                    <option disabled>Choose a shipping type</option>
                    @endif                    
                @foreach($shippingTypes as $key => $value)
                    @if (!empty($request['shipping_type']) && $request['shipping_type'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1">
                <label for="tracking_number" class="mb-2 text-sm font-medium text-gray-900">Tracking Number</label>
                <input type="text" name="tracking_number" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Tracking Number" value="{{ $request['tracking_number'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                <input type="text" name="note" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Extra Note" value="{{ $request['note'] ?? '' }}">
            </div>
        </div>
        <div class="row flex gap-2 sm:flex-row flex-col">
            <div class="flex flex-col flex-1">
                <label for="fulfillment_status" class="mb-2 text-sm font-medium text-gray-900">Fulfillment Status</label>
                <select id="fulfillment_status" name="fulfillment_status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['fulfillment_status']))
                    <option selected disabled>Choose a status</option>
                    @else
                    <option disabled>Choose a status</option>
                    @endif                    
                @foreach($fulfillmentStatuses as $key => $value)
                    @if (!empty($request['fulfillment_status']) && $request['fulfillment_status'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1">
                <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Product Payment Status</label>
                <select id="product_payment_status" name="product_payment_status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['product_payment_status']))
                    <option selected disabled>Choose a status</option>
                    @else
                    <option disabled>Choose a status</option>
                    @endif                    
                @foreach($paymentStatuses as $key => $value)
                    @if (!empty($request['product_payment_status']) && $request['product_payment_status'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1">
                <label for="address" class="mb-2 text-sm font-medium text-gray-900">Labour Payment Status</label>
                <select id="labour_payment_status" name="labour_payment_status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['labour_payment_status']))
                    <option selected disabled>Choose a status</option>
                    @else
                    <option disabled>Choose a status</option>
                    @endif                    
                @foreach($paymentStatuses as $key => $value)
                    @if (!empty($request['labour_payment_status']) && $request['labour_payment_status'] == $key)
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
                <label for="date_from" class="mb-2 text-sm font-medium text-gray-900">Date From</label>
                <input type="date" name="date_from" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['date_from'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="date_to" class="mb-2 text-sm font-medium text-gray-900">Date To</label>
                <input type="date" name="date_to" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['date_to'] ?? '' }}">
            </div>
        </div>
        <div class="row flex justify-end px-3 gap-2 sm:mt-0 mt-1">
            <button type="submit" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                <i class="fa-solid fa-filter sm:text-[11px] text-[9px]"></i>
                Filter
            </button>
            <a href="{{ route('admin.fulfillment.list') }}" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
                <i class="fa-solid fa-xmark sm:text-[12px] text-[10px]"></i>
                Clear
            </a>
        </div>
    </form>
</div>
<script>
    let filterForm = $('#filter-form');
    let toggleFilterBtn = $('#toggle-filter');

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