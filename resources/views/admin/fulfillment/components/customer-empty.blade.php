<div class="w-full ajax-row bg-white cursor-pointer">
    <div class="w-full text-[12px] flex px-2 py-2 justify-center items-center owner-row">
        <p class="text-red-500 font-semibold">{{ $message }}</p>
    </div>
</div>

<script>
    $('.owner-row').on('click', function() {
        $('#customer_search').val('')

        let container = $(this).parent().parent();
        container.hide()
    })
</script>