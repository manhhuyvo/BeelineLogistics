<!-- Main modal -->
<div id="generalActionModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed md:top-0 top-25 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
            <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="generalActionModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <i class="fa-solid fa-circle-exclamation mb-[20px] mx-auto text-[70px] general-confirm-icon"></i>
            <p class="mb-4 text-gray-600 font-semibold">Are you sure you want to <span id="general-confirm-action-name"></span>?</p>
            <p class="mb-4 text-red-500 font-semibold">This action cannot be undone.</p>
            <div class="flex justify-center items-center space-x-4">                
                <button type="button" onclick="performGeneralAction($(this))" class="py-2 px-3 text-sm font-medium text-center text-white rounded-lg" id="final-confirm-btn">
                    Yes, Submit
                </button>
                <button data-modal-toggle="generalActionModal" type="button" class="py-2 px-3 text-sm font-medium text-white bg-gray-600 rounded-lg border border-gray-200 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-primary-300 focus:z-10">
                    No, Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    let generalConfirmModalInitiateBtn = $('.general-confirm-modal-initiate-btn');
    let generalFinalConfirmBtn = $('#final-confirm-btn');
    let generalConfirmActionName = $('#general-confirm-action-name');
    let generalConfirmIcon = $('.general-confirm-icon');

    // Event when trhe buttons which triggers the general action confirm modal
    generalConfirmModalInitiateBtn.on('click', function() {
        // Get the name of the action
        let eventName = $(this).attr('data-event-name');
        // Assign that name to the mesage inside the modal confirm box
        generalConfirmActionName.text(eventName);

        // Set color for the button and icon
        let eventColor = $(this).attr('data-event-level');
        generalFinalConfirmBtn.removeClass('bg-red-500 hover:bg-red-700 bg-blue-500 hover:bg-blue-700 bg-green-500 hover:bg-green500 bg-yellow-500 hover:bg-yellow-700');
        generalConfirmIcon.removeClass('text-red-500 text-blue-500 text-green-500 text-yellow-500');
        generalFinalConfirmBtn.addClass(`bg-${eventColor}-500 hover:bg-${eventColor}-700`);
        generalConfirmIcon.addClass(`text-${eventColor}-500`);

        // Get the name of function that would handle the actual action's logics
        let eventHandler = $(this).attr('data-event-handler');

        // Assign that function name to the attribute of "YES, submit" button
        generalFinalConfirmBtn.attr('data-final-event-handler', eventHandler);
    })

    // Event handler for that FINAL YES confirm button
    function performGeneralAction(src) {
        // Get that attribute again
        let eventHandlerName = src.attr('data-final-event-handler');
    
        console.log(eventHandlerName)
        // Call that event handler function for that specific action
        window[eventHandlerName].call();
    }
</script>
@endpush