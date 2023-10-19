<div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
    <p class="text-lg px-3 py-1 font-medium text-blue-600">
        Country Configurations
    </p>
    <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.supplier.country-config', ['supplier' => $supplier['id']]) }}" method="POST">
        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
        <p class="text-sm font-semibold">Please select countries available for this supplier</p>
        <div class="row md:grid md:grid-cols-3 gap-2 flex flex-col px-2.5">
            @foreach ($countries as $key => $value)
            <div class="h-[50px] p-0">
                <label class="inline-flex gap-2 items-center cursor-pointer relative w-full h-full border-solid border-[2px] border-gray-300 flex justify-center items-center text-gray-500 rounded-[3px]">
                    <input type="checkbox" name="countries[]" class="countries_checkbox border-transparent bg-transparent outline-none focus:ring-0 absolute hidden left-0" value="{{ $key }}"
                        @if (!empty($currentCountriesMeta) && in_array($key, $currentCountriesMeta))
                            checked = true
                        @endif
                    >
                    <span class="font-semibold md:text-[14px] text-sm">{{ Str::upper($value) }}</span>
                </label>
            </div>
            @endforeach
        </div>
        <div class="row flex justify-center px-3 gap-2 mt-2">
            <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                Update
            </button>
            <a href="{{ route('admin.supplier.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                Cancel
            </a>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.countries_checkbox').each(function(index, item) {
            if($(item).is(':checked')) {
                $(item).parent().addClass(selected)
                $(item).parent().removeClass(notSelected)
            } else {
                $(item).parent().addClass(notSelected)
                $(item).parent().removeClass(selected)
            }
        });

        $('.countries_checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).parent().addClass(selected)
                $(this).parent().removeClass(notSelected)
            } else {
                $(this).parent().addClass(notSelected)
                $(this).parent().removeClass(selected)
            }
        })
    })
</script>