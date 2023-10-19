@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Add new invoice</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.invoice.store') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <input name="create_invoice_from" type="hidden" value="{{ InvoiceEnum::TARGET_MANUAL }}" />
            <input name="staff_id" type="hidden" value="{{ $user->staff->id }}" />
            <!-- INVOICE DETAILS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Invoice Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_search" class="mb-2 text-sm font-medium text-gray-900">Customer Owner</label>
                    <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50 relative">
                        <select id="customer_id" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5 searchableDropdowns">
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
                        <div class="text-sm h-full font-medium text-sm focus:ring-1 flex-1 p-2.5 bg-gray-200 border-none pl-3 rounded-r-lg">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="due_date" class="mb-2 text-sm font-medium text-gray-900">Due Date</label>
                    <input type="date" name="due_date" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['due_date'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="reference" class="mb-2 text-sm font-medium text-gray-900">Reference</label>
                    <input id="reference" type="text" name="reference" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Invoice Reference" value="{{ $request['reference'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="unit" class="mb-2 text-sm font-medium text-gray-900">Invoice Currency</label>
                    <select id="unit" name="unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['unit']))
                        <option selected disabled>Choose a currency</option>
                        @endif
                    @foreach($currencies as $key => $value)
                        @if (!empty($request['unit']) && $request['unit'] == $key)
                        <option selected value="{{ $value }}">{{ $value }}</option>
                        @else
                        <option value="{{ $value }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Invoice Status</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['status']))
                        <option selected disabled>Choose a status</option>
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
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea id="note" name="note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 resize-none" placeholder="Extra note" value="{{ $request['note'] ?? '' }}"></textarea>
                </div>
            </div>
            <!-- INVOICE ITEMS -->
            <p class="text-lg font-medium text-blue-600 mt-1">
                Invoice Items
            </p>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-500 min-width">
                    <thead class="text-sm  text-gray-500 font-semibold bg-gray-200"  style="text-align: center !important;">
                        <tr>
                            <th scope="col" class="sm:py-4 py-2 px-1 w-5 border-solid border-white border-r-[2px] w-5">
                                
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-25">
                                Item Target
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-55">
                                Description
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white resize-none border-r-[2px] w-5">
                                Price
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 border-solid border-white border-r-[2px] w-5">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 sm:py-4 py-2 w-5">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center !important;" id="add_new_row_container">
                    </tbody>
                    <tfoot>
                        <tr class="font-normal text-gray-900">
                            <td colspan="2" class="pt-3">
                                <div class="row flex flex-col gap-2 px-2.5">
                                    <button type="button" class="px-2.5 py-1.5 rounded-[5px] text-[12px] bg-gray-200 text-gray-900 font-medium w-fit min-w-[150px] hover:bg-blue-800 hover:text-white flex items-center gap-2 w-fit add_new_row" data-target="{{ InvoiceEnum::TARGET_FULFILLMENT }}">
                                        <i class="fa-solid fa-plus"></i>
                                        Add Fulfillment Row
                                    </button>
                                </div>
                            </td>
                            <td class="p-2.5"></td>
                            <td class="p-2.5">Items</td>
                            <td class="p-2.5 text-center">x</td>
                            <td class="p-2.5 text-center" id="total-item-count">0</td>
                        </tr>
                        <tr class="font-normal text-gray-900">
                            <td colspan="2" class="pt-3">
                                <div class="row flex flex-col gap-2 px-2.5">
                                    <button type="button" class="px-2.5 py-1.5 rounded-[5px] text-[12px] bg-gray-200 text-gray-900 hover:text-white font-medium w-fit min-w-[150px] hover:bg-blue-800 flex items-center gap-2 w-fit add_new_row" data-target="{{ InvoiceEnum::TARGET_MANUAL }}">
                                        <i class="fa-solid fa-plus"></i>
                                        Add Manual Row
                                    </button>
                                </div>
                            </td>
                            <td class="p-2.5"></td>
                            <td class="p-2.5">Sub-total</td>
                            <td class="p-2.5 text-center"><span class="total-currency"></span></td>
                            <td class="p-2.5 text-center"><span class="total-amount">0.00</span></td>
                        </tr>
                        <tr class="font-semibold text-gray-900 text-lg">
                            <td colspan="2" class="pt-3">
                                {{-- <div class="row flex flex-col gap-2 px-2.5">
                                    <button type="button" class="px-2.5 py-1.5 rounded-[5px] text-[12px] bg-gray-200 text-gray-900 hover:text-white font-medium w-fit min-w-[150px] hover:bg-blue-800 flex items-center gap-2 w-fit add_new_row" data-target="{{ InvoiceEnum::TARGET_ORDER }}">
                                        <i class="fa-solid fa-plus"></i>
                                        Add Order Row
                                    </button>
                                </div> --}}
                            </td>
                            <td class="p-2.5"></td>
                            <th scope="row" class="p-2.5 border-t border-gray-500">Total</th>
                            <td class="p-2.5 border-t border-gray-500 text-center"><span class="total-currency"></span></td>
                            <td class="p-2.5 border-t border-gray-500 text-center"><span class="total-amount">0.00</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Create
                </button>
                <a href="{{ route('admin.invoice.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Add new row
    const addNewRowBtn = $('.add_new_row')
    const addNewRowContainer = $('#add_new_row_container')    

    $(document).ready(function() {
        // Set up searchable dropdowns
        $('.searchableDropdowns').select2();
        setUpSearchableDropdowns();
        // Don't round the border for this dropdown
        $('#customer_id').parent().find('.select2-container').removeClass('rounded-r-lg');
        $('.target_id_dropdowns').parent().find('.select2-container').removeClass('rounded-r-lg');

        // Add new row button click
        addNewRowBtn.on('click', function() {
            addNewRow($(this).attr('data-target'));
        })

        // Set currency on invoice currency change
        $('#unit').on('change', function() {
            $('.total-currency').html($(this).val())
        })
    })

    // Add new row
    function addNewRow(target)
    {
        let url = "";
        if (target == '{{ InvoiceEnum::TARGET_FULFILLMENT }}') {
            url = "{{ route('admin.small-elements.invoice-row', ['target' => 'fulfillment']) }}";
        } else if (target == '{{ InvoiceEnum::TARGET_ORDER }}') {
            url = "{{ route('admin.small-elements.invoice-row', ['target' => 'order']) }}";
        } else {
            url = "{{ route('admin.small-elements.invoice-row', ['target' => 'manual']) }}";
        }

        $.get(url, function(data) {
            addNewRowContainer.append(data)
        }).done(function() {
            // Set number of rows count
            let numberOfRows = $('.invoice-row').length;
            $('#total-item-count').html(numberOfRows);
        })
    }

    // Override default style for Select2 dropdowns
    function setUpSearchableDropdowns()
    {
        // Set outter container's styling
        $('#customer_id').parent().find('.select2-container').addClass("bg-gray-50 text-gray-600 text-[12px] rounded-r-lg rounded-l-lg focus:ring-blue-500 focus:border-blue-500 w-full h-fit p-1.5 rounded-lg");
        $('.target_id_dropdowns').parent().find('.select2-container').addClass("bg-white text-gray-600 text-[12px] w-full h-fit p-1.5 rounded-r-lg rounded-l-lg");
        $('.select2-container').attr('style', '');
        // Set inner div for dropdown
        $('.select2-selection').addClass("bg-transparent border-0");
        // Hide the default ugly arrow
        $('.select2-selection__arrow').addClass("hidden");
    }
</script>
@endsection