@extends('supplier.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('supplier.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Add New Ticket</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('supplier.ticket.store') }}" method="POST" enctype="multipart/form-data">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Ticket Details
            </p>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Customer</label>
                    <select id="customer_id" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                        @if (empty($request['customer_id']))
                        <option selected disabled>Choose a customer</option>
                        @else
                        <option value="">Choose a customer</option>
                        @endif                    
                    @foreach($allCustomers as $key => $value)
                        @if (!empty($request['customer_id']) && $request['customer_id'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="title" class="mb-2 text-sm font-medium text-gray-900">Title</label>
                    <input id="title" type="text" name="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" value="">
                </div>
            </div>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="content" class="mb-2 text-sm font-medium text-gray-900">Content</label>
                    <textarea id="content" name="content" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" rows="10" placeholder="Extra note" value="{{ $request['content'] ?? '' }}">{{ $request['content'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="attachments" class="mb-2 text-sm font-medium text-gray-900">Attachment</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" aria-describedby="file_input_help" id="attachments" type="file" name="attachments">
                <p class="mt-1 text-sm text-gray-500" id="file_input_help">SVG, PNG, JPG</p>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Belongs To Orders / Fulfillments
            </p>
            <div class="row flex flex-col gap-2 px-2.5 justify-center" id="add_new_row_container">
            </div>
            <div class="row flex flex-col gap-2 px-2.5">
                <button type="button" class="add_new_row px-2.5 py-1.5 rounded-[5px] text-[12px] bg-gray-200 text-gray-900 font-medium hover:text-gray-400 flex items-center gap-2 w-fit min-w-[150px]" data-target="fulfillment">
                    <i class="fa-solid fa-plus"></i>
                    Add Fulfillment Row
                </button>
                {{-- <button type="button" class="add_new_row px-2.5 py-1.5 rounded-[5px] text-[12px] bg-gray-200 text-gray-900 font-medium hover:text-gray-400 flex items-center gap-2 w-fit min-w-[150px]" data-target="order">
                    <i class="fa-solid fa-plus"></i>
                    Add Order Row
                </button> --}}
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Create
                </button>
                <a href="{{ route('supplier.ticket.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
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
        $('#customer_id').select2();
        setUpSearchableDropdowns();
        
        addNewRowBtn.on('click', function() {
            addNewRow($(this).attr('data-target'));
        })
    })

    // Add new row
    function addNewRow(target)
    {
        let url = "";
        if (target == '{{ InvoiceEnum::TARGET_FULFILLMENT }}') {
            url = "{{ route('supplier.small-elements.ticket-belongs-row', ['target' => 'fulfillment']) }}";
        } else if (target == '{{ InvoiceEnum::TARGET_ORDER }}') {
            url = "{{ route('supplier.small-elements.ticket-belongs-row', ['target' => 'order']) }}";
        }

        if (url != '') {
            $.get(url, function(data) {
                addNewRowContainer.append(data)
            })
        }
    }

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
@endsection