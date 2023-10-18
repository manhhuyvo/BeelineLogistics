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
    <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('supplier.ticket.list') }}" method="get" id="filter-form">
        <div class="row flex gap-2 sm:flex-row flex-col">
            <div class="flex flex-col flex-1">
                <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Customer Owner</label>
                <select id="customer_id" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['customer_id']))
                    <option selected disabled>Choose a customer</option>
                    @else
                    <option value = "">Choose a customer</option>
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
                <label for="id" class="mb-2 text-sm font-medium text-gray-900">Ticket ID</label>
                <input type="text" name="id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Ticket ID" value="{{ $request['id'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['status']))
                    <option selected disabled>Choose a status</option>
                    @else
                    <option value="">Choose a status</option>
                    @endif                    
                @foreach($ticketStatuses as $key => $value)
                    @if (!empty($request['status']) && $request['status'] == $key)
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
                <label for="title" class="mb-2 text-sm font-medium text-gray-900">Ticket Title</label>
                <input type="text" name="title" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['title'] ?? '' }}">
            </div>
        </div>
        <div class="row flex-col items-center px-2 gap-2">
            <p class="text-sm font-medium text-gray-900 p-0">Date Created</p>
            <div class="row flex gap-2 sm:flex-row flex-col px-2 py-4 rounded-lg border-solid border-[2px] border-gray-500 bg-gray-100">
                <div class="flex flex-col flex-1">
                    <label for="date_from" class="mb-2 text-sm font-medium text-gray-900">From</label>
                    <input type="date" name="date_from" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['date_from'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="date_to" class="mb-2 text-sm font-medium text-gray-900">To</label>
                    <input type="date" name="date_to" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['date_to'] ?? '' }}">
                </div>
            </div>
        </div>
        <div class="row flex-col items-center px-2 gap-2">
            <p class="text-sm font-medium text-gray-900 p-0">Date Solved</p>
            <div class="row flex gap-2 sm:flex-row flex-col px-2 py-4 rounded-lg border-solid border-[2px] border-gray-500 bg-gray-100">
                <div class="flex flex-col flex-1">
                    <label for="solved_from" class="mb-2 text-sm font-medium text-gray-900">From</label>
                    <input type="date" name="solved_from" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['solved_from'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="solved_to" class="mb-2 text-sm font-medium text-gray-900">To</label>
                    <input type="date" name="solved_to" class="bg-white border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" value="{{ $request['solved_to'] ?? '' }}">
                </div>
            </div>
        </div>
        <div class="row flex justify-end px-3 gap-2 sm:mt-0 mt-1">
            <button type="submit" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                <i class="fa-solid fa-filter sm:text-[11px] text-[9px]"></i>
                Filter
            </button>
            <a href="{{ route('supplier.ticket.list') }}" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
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