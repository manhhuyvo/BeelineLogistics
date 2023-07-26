@foreach($data as $each)

<div class="w-full ajax-row bg-white cursor-pointer">
    <div class="w-full text-[12px] flex px-2 py-2 justify-center items-center hover:bg-blue-50 owner-row" data-owner-id="{{ $each['id'] }}" data-owner-type="{{ $each['type'] }}" data-owner-name="{{ $each['full_name'] }}" data-owner-status="{{ $each['status'] }}">
        <span class="mr-2 font-medium">{{ $each['type'] }}:</span>
        <span class="mr-2 font-medium text-blue-700">{{ $each['full_name'] }}</span>
        <span class="text-{{ $each['status_color'] }}-500">({{ $each['status'] }})</span>
    </div>
</div>

@endforeach

<script>
    $('.owner-row').on('click', function() {
        let id = $(this).attr('data-owner-id');
        let name = $(this).attr('data-owner-name');
        let type = $(this).attr('data-owner-type');
        let status = $(this).attr('data-owner-status');

        assignDataToMainView(id, name, type, status);
        $('#owner_search').val('')

        let container = $(this).parent().parent();
        container.hide()
    })

    function assignDataToMainView(id, name, type, status) {
        $('input[name="supplier_id"]').val(id)
        $('input[name="supplier_full_name"]').val(name)
        $('input[name="supplier_type"]').val(type)
        $('input[name="supplier_status"]').val(status)
    }
</script>