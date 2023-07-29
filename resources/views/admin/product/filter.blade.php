<div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-md border-solid border-[1px] border-gray-200 py-1">
    <p class="text-lg px-3 py-1 font-medium text-blue-600">
        Filter table
    </p>
    <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.product.list') }}" method="get">
        <div class="row flex gap-2">
            <div class="flex flex-col flex-1">
                <label for="name" class="mb-2 text-sm font-medium text-gray-900">Product Name</label>
                <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Product Name" value="{{ $request['name'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="group_id" class="mb-2 text-sm font-medium text-gray-900">Product Group</label>
                <select id="group_id" name="group_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                    @if (empty($request['type']))
                    <option selected disabled>Choose a type</option>
                    @endif
                @foreach($productGroups as $key => $value)
                    @if (!empty($request['group_id']) && $request['group_id'] == $key)
                    <option selected value="{{ $key }}">{{ $value }}</option>
                    @else
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endif
                @endforeach
                </select>
            </div>
        </div>
        <div class="row flex gap-2">
            <div class="flex flex-col flex-1">
                <label for="stock" class="mb-2 text-sm font-medium text-gray-900">Current Stock</label>
                <input id="stock" type="number" name="stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Current Stock" value="{{ $request['stock'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                    @if (empty($request['status']))
                    <option selected disabled>Choose a status</option>
                    @endif
                @foreach($productStatuses as $key => $value)
                    @if (!empty($request['status']) && $request['status'] == $key)
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
            <a href="{{ route('admin.product.list') }}" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
                <i class="fa-solid fa-xmark sm:text-[12px] text-[10px]"></i>
                Clear
            </a>
        </div>
    </form>
</div>