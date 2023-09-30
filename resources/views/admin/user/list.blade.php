@extends('admin.layout.layout')
@section('content')

<div class="sm:rounded-lg">
    @include('admin.layout.confirm-delete')
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">All users</h2>
    <div class="w-full flex justify-between mb-3 items-center">
        <div class="sm:w-[40%]">
            <input type="text" name="user-search" placeholder="Search for usernames" class="text-sm py-2 px-2 rounded-[5px] border-solid border-[1px] border-gray-200 bg-gray-100 text-gray-600 w-full focus:ring-blue-500 focus:border-blue-500">
        </div>
        <a href="{{ route('admin.user.create.form') }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Add user
        </a>
    </div>
    @include('admin.user.filter')

    @if (empty($users['data']))
        <p class="w-full text-center text-red-600 font-semibold text-lg">Unable to find any records.</p>
    @else
    @include('admin.layout.pagination')
    <div class="w-full overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg">
        <thead class="text-xs text-white font-semibold uppercase bg-indigo-950 text-center">
            <tr>
                <th scope="col" class="pl-4 sm:py-3 py-2">
                    Index
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Username
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Owner
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Target
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Level
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Status
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Note
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Date Created
                </th>
                <th scope="col" class="px-6 sm:py-3 py-2">
                    Action
                </th>
            </tr>
        </thead>
        <tbody style="text-align: center !important;">
            @foreach($users['data'] as $index => $user)
            <tr class="bg-white border-b hover:bg-gray-50">
                <th scope="col" class="pl-4 py-3">
                    {{ $index + 1 }}
                </th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    {{ $user['username'] }}
                </th>
                <td class="px-6 py-4">
                    @if (!empty($owners[$user['id']]))
                        {{ $owners[$user['id']]['full_name'] ?? 'Name Unknown' }}
                        <strong>
                        @if ($user['target'] == User::TARGET_STAFF)
                            ({{ $owners[$user['id']]['position'] ?? 'Position Unknown' }})
                        @elseif ($user['target'] == User::TARGET_CUSTOMER)
                            ({{ $owners[$user['id']]['customer_id'] ?? 'Customer ID Unknown' }})
                        @elseif ($user['target'] == User::TARGET_SUPPLIER)
                            ({{ $owners[$user['id']]['type'] ?? 'Type Unknown' }})
                        @endif
                        </strong>
                    @else
                    Not provided
                    @endif
                </td>
                <td class="px-6 py-4">
                    {{ $user['target'] }}
                </td>
                <td class="px-6 py-4">
                    @if ($user['target'] == User::TARGET_STAFF)
                        {{ $userStaffLevels[$user['level']] ?? 'Not Provided' }}
                    @elseif ($user['target'] == User::TARGET_CUSTOMER)
                        Customer
                    @elseif ($user['target'] == User::TARGET_SUPPLIER)
                        Supplier
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="font-semibold text-{{ $userStatusColors[$user['status']] ?? 'red' }}-500">{{ $userStatuses[$user['status']] }}</span>
                </td>
                <td class="px-6 py-4">
                    {{ $user['note'] ?? "" }}
                </td>
                <td class="px-6 py-4">
                    {{ $user['created_at'] }}
                </td>
                <td class="px-6 py-4">
                    <div class="h-full flex gap-4">
                    <a href="{{ route('admin.user.show', ['user' => $user['id']]) }}" class="font-medium text-blue-600 hover:underline">View</a>
                    <a href="{{ route('admin.user.edit.form', ['user' => $user['id']]) }}" class="font-medium text-yellow-600 hover:underline">Edit</a>
                    {{-- <button type="button" class="font-medium text-red-600 hover:underline confirm-modal-initiate-btn" data-row-id="{{ $user['id'] }}" data-row-route="{{ route('admin.user.delete', ['user' => $user['id']]) }}" data-modal-toggle="deleteModal" >Delete</button> --}}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @endif
</div>
@endsection