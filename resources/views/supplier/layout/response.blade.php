
@php
    $response = session()->get('response');
@endphp
@if (!empty($response))
    @if($response['status'] == 'error')
        <div class="w-full flex gap-1 z-100" id="response-error-div">
            <div class="mt-2 mb-2 cursor-pointer w-full px-3 py-2.5 text-sm bg-red-500 text-white font-medium flex justify-between gap-3 items-start text-center" id="response-error">
                <div class="flex-1 text-center">{{ $response['message'] }}</div>
                <i class="fa-solid fa-xmark text-lg"></i>
            </div>
        @if (!empty($response['data']))
            <div class="mt-2 mb-2 px-3 py-2.5 text-sm bg-red-500 text-white font-bold felx justify-center text-center items-center cursor-pointer" id="error-dropdown-btn" info="Show errors">
                <i class="fa-solid fa-caret-down"></i>
            </div>
            <div class="flex flex-col sm:w-[40%] w-full mt-2 mb-3 cursor-pointer items-end gap-1 absolute top-[52px] right-0 bg-white z-50" id="response-error-list">
                @foreach($response['data'] as $key => $message)
                    <div class="cursor-pointer w-full px-3 py-2.5 text-sm bg-red-500 text-white font-medium flex justify-between gap-3 items-start text-center">
                        <div class="flex-1 text-center">{{ $message[0] }}</div>
                    </div>
                @endforeach
            </div>
        @endif
        </div>
    @else
        <div class="mt-2 mb-2 cursor-pointer w-full px-3 py-2.5 text-sm bg-green-600 text-white font-medium flex justify-between gap-3 items-start text-center z-100" id="response-success">
            <div class="flex-1 text-center">{{ $response['message'] }}</div>
            <i class="fa-solid fa-xmark text-lg"></i>
        </div>
    @endif
@endif

{{-- We put the jquery codes in here --}}
<script>
    const errorResponse = $('#response-error');
    const errorResponseDiv = $('#response-error-div');
    const errorDropdownBtn = $('#error-dropdown-btn');
    const responseErrorList = $('#response-error-list');
    
    const successResponse = $('#response-success');

    responseErrorList.hide();

    $(document).ready(function() {
        errorResponse.on('click', function() {
            errorResponseDiv.hide();
        });

        errorDropdownBtn.on('click', function() {
            if (responseErrorList.is(':hidden')) {
                errorDropdownBtn.addClass('bg-red-700');
                errorDropdownBtn.html('<i class="fa-solid fa-caret-up"></i>');
                responseErrorList.show();
            } else if (responseErrorList.is(':visible')) {
                errorDropdownBtn.removeClass('bg-red-700');
                errorDropdownBtn.html('<i class="fa-solid fa-caret-down"></i>')
                responseErrorList.hide();
            }
        })

        successResponse.on('click', function() {
            successResponse.hide();
        });
    })
</script>