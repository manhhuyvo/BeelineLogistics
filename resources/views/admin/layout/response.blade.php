
@php
    $response = session()->get('response');
@endphp
@if (!empty($response))
    @if($response['status'] == 'error')
        <div class="mt-2 mb-3 cursor-pointer w-full px-3 py-2.5 text-sm bg-red-500 text-white font-medium flex justify-between items-center text-center" id="response-error">
            <div class="flex-1 text-center">{{ $response['message'] }}</div>
            <i class="fa-solid fa-xmark text-lg"></i>
        </div>
    @else
        <div class="mt-2 mb-3 cursor-pointer w-full px-3 py-2.5 text-sm bg-green-600 text-white font-medium flex justify-between items-center text-center" id="response-success">
            <div class="flex-1 text-center">{{ $response['message'] }}</div>
            <i class="fa-solid fa-xmark text-lg"></i>
        </div>
    @endif
@endif

{{-- We put the jquery codes in here --}}
<script>
    const errorResponse = $('#response-error');
    const successResponse = $('#response-success');

    $(document).ready(function() {
        errorResponse.on('click', function() {
            errorResponse.hide();
        });

        successResponse.on('click', function() {
            successResponse.hide();
        });
    })
</script>