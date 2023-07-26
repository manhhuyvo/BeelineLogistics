@foreach($data as $each)

<div class="w-full ajax-row bg-white cursor-pointer">
    <div class="w-full text-[12px] flex px-2 py-2 justify-center items-center hover:bg-blue-50 owner-row" data-owner-id="{{ $each['id'] }}" data-owner-position="{{ $each['position'] }}" data-owner-name="{{ $each['full_name'] }}" data-owner-status="{{ $each['status'] }}">
        <span class="mr-2 font-medium">{{ $each['position'] }}:</span>
        <span class="mr-2 font-medium text-blue-700">{{ $each['full_name'] }}</span>
        <span class="text-{{ $each['status_color'] }}-500">({{ $each['status'] }})</span>
    </div>
</div>

@endforeach

<script>
    $('.owner-row').on('click', function() {
        let id = $(this).attr('data-owner-id');
        let name = $(this).attr('data-owner-name');
        let position = $(this).attr('data-owner-position');
        let status = $(this).attr('data-owner-status');

        assignDataToMainView(id, name, position, status);
        $('#owner_search').val('')

        let container = $(this).parent().parent();
        container.hide()
    })

    function assignDataToMainView(id, name, position, status) {
        $('input[name="staff_id"]').val(id)
        $('input[name="staff_full_name"]').val(name)
        $('input[name="staff_position"]').val(position)
        $('input[name="staff_status"]').val(status)
    }
</script>