<div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-md border-solid border-[1px] border-gray-200 py-1">
    <p class="text-lg px-3 py-1 font-medium text-blue-600">
        Filter table
    </p>
    <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.customer.log') }}" method="get">
        <div class="row flex-col items-center px-2 gap-2 flex-1">
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
        <div class="row flex gap-2 flex-1">
            <div class="flex flex-col flex-1">
                <label for="description" class="mb-2 text-sm font-medium text-gray-900">Description</label>
                <textarea type="text" name="description" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5 resize-none" rows=5 placeholder="Log Description / Keywords">{{ $request['description'] ?? '' }}</textarea>
            </div>
        </div>
        <div class="row flex gap-2">
            <div class="flex flex-col flex-1">
                <label for="target_id" class="mb-2 text-sm font-medium text-gray-900">Customer Target</label>
                <select id="target_id" name="target_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['target_id']))
                    <option selected disabled>Choose a customer</option>
                    @else
                    <option value="">Choose a customer</option>
                    @endif                    
                @foreach($allCustomers as $key => $value)
                    @if (!empty($request['target_id']) && $request['target_id'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1">
                <label for="action_by_id" class="mb-2 text-sm font-medium text-gray-900">User Action</label>
                <select id="action_by_id" name="action_by_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['action_by_id']))
                    <option selected disabled>Choose a user</option>
                    @else
                    <option value="">Choose a user</option>
                    @endif                    
                @foreach($allUsers as $key => $value)
                    @if (!empty($request['action_by_id']) && $request['action_by_id'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
        </div>
        <div class="row flex justify-end px-3 gap-2 sm:mt-0 mt-1">
            <button type="submit" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                <i class="fa-solid fa-filter sm:text-[11px] text-[9px]"></i>
                Filter
            </button>
            <a href="{{ route('admin.customer.log') }}" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
                <i class="fa-solid fa-xmark sm:text-[12px] text-[10px]"></i>
                Clear
            </a>
        </div>
    </form>
</div>