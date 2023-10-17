<div class="w-full flex flex-col gap-2 border-b border-x border-gray-200 py-2 px-2.5 tabContainers" id="comments">
    <form method="POST" action="{{ route('customer.ticket.comment.store', ['ticket' => $ticket['id']]) }}" enctype="multipart/form-data">
        <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
        <div class="w-full flex flex-col gap-1 items-end">
            <p class="text-lg font-medium text-blue-600 mt-1 text-left w-full">
                New Reply
            </p>
            <div class="w-full flex flex-col gap-1">            
                <div class="row flex md:flex-row flex-col gap-2 flex-1">
                    <div class="flex flex-col flex-1">
                        <textarea id="content" name="content" class="bg-gray-50 border border-gray-300 rounded-lg text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" rows="7" placeholder="Write a new reply here..." value=""></textarea>
                    </div>
                </div>
                <div class="flex flex-col flex-1">
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" aria-describedby="file_input_help" id="attachment" type="file" name="attachment">
                </div>
                <div class="flex md:flex-row flex-col flex-1 md:justify-between md:items-start items-end pl-1 py-1">
                    <p class="text-sm text-gray-500 text-left w-full" id="file_input_help">SVG, PNG, JPG</p>
                    <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-fit hover:bg-blue-500 flex items-center gap-2">
                        Send<i class="fa-solid fa-paper-plane text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <p class="text-lg font-medium text-blue-600 my-1">
        All Comments
    </p>
    @if (empty($comments))
        <p class="text-gray-600 font-semibold text-sm italic">There is no comments for this support ticket.</p>
    @else
        @foreach ($comments as $comment)
            <div class="w-full flex gap-3 items-start mb-4">
                <div class="w-7 h-7 rounded-full bg-black mt-1 cursor-pointer" title="{{ $comment['owner']['full_name'] ?? '' }}">
                    <img class="w-7 h-7 rounded-full" src="{{ url('assets/images/user-512.png') }}" alt="user photo">
                </div>
                <div class="w-full flex flex-col gap-1 text-[15px] py-2 px-3 rounded-lg border-solid border-gray-300 border-[1px] bg-gray-50">
                    <div class="w-full flex md:flex-row flex-col md:gap-2 gap-1 md:items-end">
                        @if ($comment['user']['target'] == User::TARGET_CUSTOMER)
                            <p class="font-bold">{{ $comment['user']['customer']['customer_id'] }} {{ $comment['owner']['full_name'] ?? '' }}</p>
                        @elseif ($comment['user']['target'] == User::TARGET_STAFF)
                            <p class="font-bold">({{ Staff::MAP_POSITIONS[$comment['user']['staff']['position']] }}) {{ $comment['owner']['full_name'] ?? '' }}</p>
                        @else
                            <p class="font-bold">(Staff) {{ $comment['owner']['full_name'] ?? '' }}</p>
                        @endif
                        <p class="text-sm italic text-gray-500">{{ $comment['created_at'] }}</p>
                    </div>
                    <div class="w-full mb-1 mt-2">
                        <div class="text-sm md:font-normal font-medium">{!! nl2br(e($comment['content'])) !!}</div>
                    </div>
                    @if (!empty($comment['attachment']))
                        <div class="flex flex-col">
                            @php
                                $commentAttachment = $comment['attachment'];
                                $noImage = url('assets/images/unavailable.png');
                            @endphp
                            <a href="{{ url("ticket_comments_attachments/$commentAttachment") }}" target="_blank" class="flex flex-row items-center p-0 w-fit gap-2 text-sm text-blue-500 font-semibold hover:underline">View Attachment<i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>