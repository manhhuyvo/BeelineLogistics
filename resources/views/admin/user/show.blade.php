@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View user details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1 pb-3">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Account Details
        </p>
        <div class="w-full flex flex-col gap-3 px-3 py-2 justify-center">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" id="csrfToken"/>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="target" class="mb-2 text-sm font-medium text-gray-900">User Target</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $userTypes[$user['target']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <div class="flex flex-col flex-1">
                        <label for="username" class="mb-2 text-sm font-medium text-gray-900">Username</label>
                        <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $user['username'] ?? 'Not provided' }}</div>
                    </div>
                </div>
            </div>
            <!-- Display selected owner from the search -->
            <div class="row flex justify-center bg-blue-50 py-3 px-2 mx-2 border-blue-300 border-[2px] rounded-lg" id="selected-user-owner">
                @include('admin.user.selected-owner-show')
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]" value="{{ $user['note'] ?? '' }}">{{ $user['note'] ?? '' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="level" class="mb-2 text-sm font-medium text-gray-900">Level</label>
                    @if ($user['target'] == User::TARGET_STAFF)
                        <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $userStaffLevels[$user['level']] ?? 'Not provided' }}</div>
                    @elseif ($user['target'] == User::TARGET_CUSTOMER)
                        <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">Customer</div>
                    @elseif ($user['target'] == User::TARGET_SUPPLIER)
                        <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">Supplier</div>
                    @endif
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $userStatuses[$user['status']] ?? 'Not provided' }}</div>
                </div>
            </div>
        </div>
    </div>       
    <div class="row flex md:justify-end justify-center gap-2 w-full">
        <a href="{{ route('admin.user.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.user.edit.form', ['user' => $user['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

@endsection