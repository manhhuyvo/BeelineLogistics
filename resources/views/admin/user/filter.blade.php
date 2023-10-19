<div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-md border-solid border-[1px] border-gray-200 py-1">
    <p class="text-lg px-3 py-1 font-medium text-blue-600">
        Filter table
    </p>
    <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.user.list') }}" method="GET">
        <div class="row flex gap-2">
            <div class="flex flex-col flex-1">
                <label for="countries" class="mb-2 text-sm font-medium text-gray-900">Username</label>
                <input type="text" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full px-2.5 sm:py-2.5 py-1.5" placeholder="Username" value="{{ $request['username'] ?? '' }}">
            </div>
            <div class="flex flex-col flex-1">
                <label for="target" class="mb-2 text-sm font-medium text-gray-900">Target</label>
                <select id="target" name="target" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['target']))
                    <option selected disabled>Choose a type</option>
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
        </div>
        <div class="row flex gap-2">
            <div class="flex flex-col flex-1">
                <label for="level" class="mb-2 text-sm font-medium text-gray-900">Level</label>
                <select id="level" name="level" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
                    @if (empty($request['level']))
                    <option selected disabled>Choose a type</option>
                    @endif
                @foreach($userLevels as $key => $value)
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
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm text-[12px] rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:p-2.5 p-1.5">
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
        <div class="row flex justify-end px-3 gap-2 sm:mt-0 mt-1">
            <button type="submit" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                <i class="fa-solid fa-filter sm:text-[11px] text-[9px]"></i>
                Filter
            </button>
            <a href="{{ route('admin.user.list') }}" class="px-3 py-2 rounded-[5px] sm:text-sm text-[12px] bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
                <i class="fa-solid fa-xmark sm:text-[12px] text-[10px]"></i>
                Clear
            </a>
        </div>
    </form>
</div>