<!-- Main modal -->
<div id="deleteModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed md:top-0 top-25 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
            <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="deleteModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <svg class="text-red-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <p class="mb-4 text-gray-600 font-semibold">Are you sure you want to delete this record?</p>
            <p class="mb-4 text-red-500 font-semibold">This action cannot be undone.</p>
            <div class="flex justify-center items-center space-x-4">
                <form id="delete-confirm-form" action="" method="post">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    @method('delete')
                    <button type="submit" class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300" id="delete-final-confirm-btn">
                        Yes, Delete
                    </button>
                </form>
                <button data-modal-toggle="deleteModal" type="button" class="py-2 px-3 text-sm font-medium text-white bg-blue-600 rounded-lg border border-gray-200 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 focus:z-10">
                    No, Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    // Delete buttons of the table
    let confirmModalInitiateBtn = $('.confirm-modal-initiate-btn');
    let deleteConfirmForm = $('#delete-confirm-form');
    let deleteFinalConfirmBtn = $('#delete-final-confirm-btn');

    confirmModalInitiateBtn.on('click', function() {
        // Retrieve current details of the row
        let targetId = $(this).attr('data-row-id');
        let route = $(this).attr('data-row-route');

        // Assign this route to the form action
        deleteConfirmForm.attr('action', route)
    })
</script>
@endpush