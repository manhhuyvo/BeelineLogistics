@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Add new user</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Personal Details
        </p>
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.user.store') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="target" class="mb-2 text-sm font-medium text-gray-900">User Target</label>
                    <select id="target" name="target" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['target']))
                        <option selected disabled>Choose a target</option>
                        @endif
                    @foreach($userTypes as $key => $value)
                        @if (!empty($request['target']) && $request['target'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="owner_search" class="mb-2 text-sm font-medium text-gray-900">User Owner</label>
                    <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50 relative">
                        <input type="text" name="owner_search" id="owner_search" class="text-sm bg-transparent w-full p-2.5 border-none focus:ring-0" placeholder="Search Owner Name...">
                        <div class="text-sm h-full font-medium text-sm focus:ring-1 flex-1 p-2.5 bg-gray-200 border-none pl-3 rounded-r-lg">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <!-- Ajax search result for user owner -->
                        <div id="ajax-owner-search-result" class="min-h-[50px] w-full bg-gray-50 absolute top-[100%] border-solid border-x-[1px] border-y-[1px] border-gray-300 rounded-b-lg flex flex-col items-center gap-2 max-h-[200px] overflow-y-auto"></div>
                    </div>
                </div>
            </div>
            <!-- Display selected owner from the search -->
            <div class="row flex justify-center bg-yellow-50 py-3 px-2 mx-2 border-yellow-400 border-[2px] rounded-lg" id="selected-user-owner">
                @include('admin.user.selected-owner')
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="username" class="mb-2 text-sm font-medium text-gray-900">Username</label>
                    <input id="username" type="text" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Username" value="{{ $request['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="password" class="mb-2 text-sm font-medium text-gray-900">Password</label>
                    <input id="password" type="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Password" value="{{ $request['password'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="confirm_password" class="mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                    <input id="confirm_password" type="password" name="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Confirm Password" value="{{ $request['confirm_password'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea id="note" name="note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Extra note" value="{{ $request['note'] ?? '' }}"></textarea>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="level" class="mb-2 text-sm font-medium text-gray-900">Level</label>
                    <!-- For Customer Level -->
                    <select id="customer_level" name="level" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['level']))
                            <option selected disabled>Choose a level</option>
                        @endif

                        @if (!empty($request['level']) && $request['level'] == User::LEVEL_CUSTOMER)
                            <option selected value="{{ User::LEVEL_CUSTOMER }}">Customer</option>
                        @else
                            <option value="{{ User::LEVEL_CUSTOMER }}">Customer</option>
                        @endif
                    </select>
                    <!-- For Supplier Level -->
                    <select id="supplier_level" name="level" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['level']))
                            <option selected disabled>Choose a level</option>
                        @endif
                        
                        @if (!empty($request['level']) && $request['level'] == User::LEVEL_CUSTOMER)
                            <option selected value="{{ User::LEVEL_SUPPLIER }}">Supplier</option>
                        @else
                            <option value="{{ User::LEVEL_SUPPLIER }}">Supplier</option>
                        @endif
                    </select>
                    
                    <!-- For Staff Level -->
                    <select id="staff_level" name="level" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['level']))
                        <option selected disabled>Choose a level</option>
                        @endif
                    @foreach($userStaffLevels as $key => $value)
                        @if (!empty($request['level']) && $request['level'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach($userStatuses as $key => $value)
                        @if (!empty($request['status']) && $request['status'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Create
                </button>
                <a href="{{ route('admin.user.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const searchOwnerInput = $('#owner_search');
    const ownerTargetSelect = $('#target');
    const ajaxOwnerSearchResult = $('#ajax-owner-search-result');

    // Owner section variables
    const selectedUserOwner = $('#selected-user-owner')
    const staffOwnerDiv = $('#selected-owner-staff')
    const customerOwnerDiv = $('#selected-owner-customer')
    const supplierOwnerDiv = $('#selected-owner-supplier')

    // User Level dropdowns
    const staffLevelDropdown = $('#staff_level')
    const customerLevelDropdown = $('#customer_level')
    const supplierLevelDropdown = $('#supplier_level')

    // Hide all elements at first
    hideAllNeededElements();

    $(document).ready(function() {
        let csrfTokenValue = $('#csrfToken').val();
        // Search input keyup event
        searchOwnerInput.on('keyup', function() {
            // Get value search
            let searchTerm = $(this).val();
            let ownerTargetSelectValue = ownerTargetSelect.val();

            // Only send Ajax if search Term is not empty
            if (searchTerm != "") {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.ajax.search-user-owner') }}",
                    headers: {
                        'X-CSRF-Token': csrfTokenValue,
                    },
                    data: {
                        "target": ownerTargetSelectValue,
                        "searchTerm": searchTerm,
                    },
                    success: function(response) {
                        let html = response;
                        // Show and append the view from ajax to this div
                        ajaxOwnerSearchResult.show();
                        ajaxOwnerSearchResult.html(html);
                    },
                    dataType: 'html'
                })
            } else {
                // Otherwise we do something here
                ajaxOwnerSearchResult.hide();
            }
        })
    })

    // Show the owner section according to the user type selected
    ownerTargetSelect.on('change', function() {
        // Show section first
        selectedUserOwner.show();

        let selectedValueTarget = $(this).val();
        // We need to empty the value of search field
        searchOwnerInput.val('');

        // Show or hide the section based on User Type
        if (selectedValueTarget == "{{ User::TARGET_STAFF }}") {
            // Owner section
            staffOwnerDiv.show();
            clearCustomerDiv();
            clearSupplierDiv();

            // User Level dropdowns
            staffLevelDropdown.show();
            
            customerLevelDropdown.hide();
            customerLevelDropdown.get(0).selectedIndex = 0;

            supplierLevelDropdown.hide();
            supplierLevelDropdown.get(0).selectedIndex = 0;
        } else if (selectedValueTarget == "{{ User::TARGET_CUSTOMER }}") {
            // Owner section
            customerOwnerDiv.show();
            clearStaffDiv();
            clearSupplierDiv();

            // User Level dropdowns
            customerLevelDropdown.show();

            staffLevelDropdown.hide();
            staffLevelDropdown.get(0).selectedIndex = 0;

            supplierLevelDropdown.hide();
            supplierLevelDropdown.get(0).selectedIndex = 0;
        } else if (selectedValueTarget == "{{ User::TARGET_SUPPLIER }}") {
            // Owner section
            supplierOwnerDiv.show();
            clearCustomerDiv();
            clearStaffDiv();
            
            // User Level dropdowns
            supplierLevelDropdown.show();

            staffLevelDropdown.hide();
            staffLevelDropdown.get(0).selectedIndex = 0;

            customerLevelDropdown.hide();
            customerLevelDropdown.get(0).selectedIndex = 0;
        }
    })

    // Hide the ajax result when click outside
    $('body').on('mouseup', function(e) {
        // Hide the result 
        if (!$(e.target).is(searchOwnerInput)) {
            ajaxOwnerSearchResult.hide();
        }
    })

    function clearStaffDiv(){
        $('input[name="staff_id"]').val('')
        $('input[name="staff_full_name"]').val('')
        $('input[name="staff_position"]').val('')
        $('input[name="staff_status"]').val('') 
        staffOwnerDiv.hide();
    }

    function clearCustomerDiv() {
        $('input[name="customer_id"]').val('')
        $('input[name="customer_full_name"]').val('')
        $('input[name="customer_number"]').val('')
        $('input[name="customer_status"]').val('')    
        customerOwnerDiv.hide();
    }

    function clearSupplierDiv() {
        $('input[name="supplier_id"]').val('')
        $('input[name="supplier_full_name"]').val('')
        $('input[name="supplier_type"]').val('')
        $('input[name="supplier_status"]').val('')    
        supplierOwnerDiv.hide();
    }

    function hideAllNeededElements() {
        // Hide the normal fields, divs
        selectedUserOwner.hide();
        ajaxOwnerSearchResult.hide();
        staffOwnerDiv.hide();
        customerOwnerDiv.hide();
        supplierOwnerDiv.hide();

        // Hide the level dropdowns (we leave the staff level visible as default)
        supplierLevelDropdown.hide();
        customerLevelDropdown.hide();
    }
</script>
@endsection