@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Price Configuration</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex w-auto gap-2">
                    <label for="apply_pricing_fulfillment" class="mb-2 text-sm font-medium text-gray-900">Fulfillment Custom Pricings?</label>
                    <input id="apply_pricing_fulfillment" type="checkbox" name="apply_pricing_fulfillment" class="bg-gray-200 border-solid border-[1px] border-gray-500 rounded-[5px] focus:ring-blue-500 focus:border-blue-500 mt-[2px]" value="on"
                        @if (!empty($priceConfigs['fulfillment_pricing']))
                            checked = true
                        @endif
                    >
                </div>
                <div class="flex w-auto gap-2 opacity-40">
                    <label for="apply_pricing_import" class="mb-2 text-sm font-medium text-gray-900">Import Custom Pricings?</label>
                    <input id="apply_pricing_import" type="checkbox" name="apply_pricing_import" class="bg-gray-200 border-solid border-[1px] border-gray-500 rounded-[5px] focus:ring-blue-500 focus:border-blue-500 mt-[2px]" value="on" disabled>
                </div>
                <div class="flex w-auto gap-2 opacity-40">
                    <label for="apply_pricing_export" class="mb-2 text-sm font-medium text-gray-900">Export Custom Pricings?</label>
                    <input id="apply_pricing_export" type="checkbox" name="apply_pricing_export" class="bg-gray-200 border-solid border-[1px] border-gray-500 rounded-[5px] focus:ring-blue-500 focus:border-blue-500 mt-[2px]" value="on" disabled>
                </div>
            </div>
            <div id="fulfillment-pricings-container" class="flex flex-col gap-2 hidden">
                <p class="text-lg font-medium text-blue-600">
                    Fulfillment Pricings
                </p>
                <div class="row flex sm:flex-row flex-col gap-2">
                    <div class="flex flex-1 gap-1">
                        <label for="fulfillment_per_order" class="mb-2 text-sm font-medium text-gray-900">Per Order</label>
                        <input id="fulfillment_per_order" type="checkbox" name="fulfillment_per_order" class="bg-gray-200 border-solid border-[1px] border-gray-500 rounded-[5px] focus:ring-blue-500 focus:border-blue-500 mt-[2px]" value="on"
                            @if (!empty($priceConfigs['fulfillment_pricing']) && !empty($priceConfigs['fulfillment_pricing']['fulfillment_per_order']))
                                checked = true
                            @endif
                        >
                    </div>
                    <div class="flex flex-1 gap-1">
                        <label for="fulfillment_percentage" class="mb-2 text-sm font-medium text-gray-900">Percentage of Order Value</label>
                        <input id="fulfillment_percentage" type="checkbox" name="fulfillment_percentage" class="bg-gray-200 border-solid border-[1px] border-gray-500 rounded-[5px] focus:ring-blue-500 focus:border-blue-500 mt-[2px]" value="on"
                            @if (!empty($priceConfigs['fulfillment_pricing']) && !empty($priceConfigs['fulfillment_pricing']['fulfillment_percentage']))
                                checked = true
                            @endif
                        >
                    </div>
                </div>
                <div class="row flex sm:flex-row flex-col gap-2">
                    <div class="flex flex-col flex-1 opacity-40" id="per_order_container">
                        <label for="fulfillment_per_order_amount" class="mb-2 text-sm font-medium text-gray-900">Amount Per Order</label>
                        <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50">
                            <input type="text" name="fulfillment_per_order_amount" id="fulfillment_per_order_amount" class="text-sm bg-transparent w-full p-2.5 border-none focus:ring-0" placeholder="Amount (Ex: 2.5)" disabled value="{{$priceConfigs['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_amount'] ?? ''}}">
                            <select id="fulfillment_per_order_unit" name="fulfillment_per_order_unit" class="text-sm h-full font-medium text-sm focus:ring-1 flex-1 p-2.5 bg-gray-200 border-none pl-3 rounded-r-lg">
                            @if (empty($priceConfigs['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_unit']))
                                <option selected disabled>Choose a type</option>
                            @else
                                <option value="">No value</option>
                            @endif
                                @foreach($units as $unit)
                                    @if (!empty($priceConfigs['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_unit']) && $priceConfigs['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_unit'] == $unit)
                                    <option selected value="{{ $unit }}">{{ $unit }}</option>
                                    @else
                                    <option value="{{ $unit }}">{{ $unit }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 opacity-40" id="percentage_container">
                        <label for="fulfillment_percentage_amount" class="mb-2 text-sm font-medium text-gray-900">Percentage Amount</label>
                        <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50">
                            <input type="text" name="fulfillment_percentage_amount" id="fulfillment_percentage_amount" class="text-sm bg-transparent w-full p-2.5 border-none focus:ring-0" placeholder="Amount (Ex: 50)" disabled value="{{$priceConfigs['fulfillment_pricing']['fulfillment_percentage']['fulfillment_percentage_amount'] ?? ''}}">
                            <div id="fulfillment_percentage_unit" class="text-sm h-full font-medium text-sm focus:ring-1 flex-1 p-2.5 bg-gray-200 border-none pl-3 rounded-r-lg">
                                %
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Save
                </button>
                <a href="{{ route('admin.customer.show', ['customer' => $customer['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

  <script>
    // Checkboxes
    let applyImportPricing = $('#apply_pricing_import');
    let applyExportPricing = $('#apply_pricing_export');
    let applyFulfillmentPricing = $('#apply_pricing_fulfillment');
    let fulfillmentPerOrder = $('#fulfillment_per_order:checkbox');
    let fulfillmentPercentage = $('#fulfillment_percentage');

    // Containers
    let fulfillmentPricingContainer = $('#fulfillment-pricings-container');
    let perOrderContainer = $('#per_order_container');
    let perCentageContainer = $('#percentage_container');

    // Inputs
    let perOrderAmountField = $('#fulfillment_per_order_amount');
    let perOrderUnitDropdown = $('#fulfillment_per_order_unit');
    let percentageAmountField = $('#fulfillment_percentage_amount');

    $(document).ready(function() {
        // At frist when page load, we trigger these events once
        applyFulfillmentPricingEvent();
        fulfillmentPerOrderEvent();
        fulfillmentPercentageEvent();

        // Show fulfillment container when apply fulfillment checkbox is checked
        applyFulfillmentPricing.on('change', function() {            
            if (applyFulfillmentPricing.is(':checked')) {
                fulfillmentPricingContainer.show();
            } else {
                fulfillmentPricingContainer.hide();
            }

            // Clear all fulfillment inputs when this div is shown/hidden
            fulfillmentPerOrder.prop('checked', false);
            fulfillmentPerOrder.change();
            fulfillmentPercentage.prop('checked', false);
            fulfillmentPercentage.change();
        })

        // Show fulfillment fields accordingly on checkboxes event
        /* Per Order Fulfillment */
        fulfillmentPerOrder.on('change', function() {
            if (fulfillmentPerOrder.is(':checked')) {
                // Set it clear again
                perOrderContainer.removeClass('opacity-40');
                perOrderAmountField.attr('disabled', false);
            } else {
                // Clear input value, set it disabled and blue it
                perOrderAmountField.val('')
                perOrderAmountField.attr('disabled', true);
            // Unset the per order unit
                $('#fulfillment_per_order_unit option:first').attr('selected', 'selected');
                perOrderContainer.addClass('opacity-40');
            }
        })

        /* Percentage Fulfillment */
        fulfillmentPercentage.on('change', function() {            
            if (fulfillmentPercentage.is(':checked')) {
                console.log(fulfillmentPercentage.attr('checked'))
                // Set it clear again
                perCentageContainer.removeClass('opacity-40');
                percentageAmountField.attr('disabled', false);
            } else {
                console.log(fulfillmentPercentage.attr('checked'))
                // Clear input value, set it disabled and blue it
                percentageAmountField.val('')
                percentageAmountField.attr('disabled', true);
                perCentageContainer.addClass('opacity-40');
            }
        })
    });

    /** Event listener for apply fulfillment pricing */
    function applyFulfillmentPricingEvent() {
        if (applyFulfillmentPricing.is(':checked')) {
            fulfillmentPricingContainer.show();
        } else {
            fulfillmentPricingContainer.hide();
        }

        // Clear all fulfillment inputs when this div is shown/hidden
        fulfillmentPerOrder.prop('checked', false);
        fulfillmentPerOrder.change();
        fulfillmentPercentage.prop('checked', false);
        fulfillmentPercentage.change();
    }

    /** Event listener for fulfillment per order event */
    function fulfillmentPerOrderEvent() {
        if (fulfillmentPerOrder.attr('checked') == "checked") {
            // Set it clear again
            perOrderContainer.removeClass('opacity-40');
            fulfillmentPerOrder.prop('checked', true); // make it checked
            perOrderAmountField.attr('disabled', false);
        } else {
            // Clear input value, set it disabled and blue it
            perOrderAmountField.val('')
            perOrderAmountField.attr('disabled', true);
            // Unset the per order unit
            $('#fulfillment_per_order_unit option:first').attr('selected', 'selected');
            perOrderContainer.addClass('opacity-40');
        }
    }

    /** Event listener for fulfillment percentage event */
    function fulfillmentPercentageEvent() {
        if (fulfillmentPercentage.attr('checked') == "checked") {
            // Set it clear again
            perCentageContainer.removeClass('opacity-40');
            fulfillmentPercentage.prop('checked', true); // make it checked
            percentageAmountField.attr('disabled', false);
        } else {
            // Clear input value, set it disabled and blue it
            percentageAmountField.val('')
            percentageAmountField.attr('disabled', true);
            perCentageContainer.addClass('opacity-40');
        }
    }
  </script>

  @endsection