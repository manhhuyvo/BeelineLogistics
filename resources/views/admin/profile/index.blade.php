@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3 ml-1">Edit profile</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Personal Details
        </p>
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.user.profile.update') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="full_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="full_name" type="text" name="full_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Full Name" value="{{ $staff['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="phone" type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Phone" value="{{ $staff['phone'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="dob" class="mb-2 text-sm font-medium text-gray-900">Date of Birth</label>
                    <input id="dob" type="date" name="dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Date of Birth" value="{{ Carbon::createFromFormat('d/m/Y', $staff['dob'])->format('Y-m-d') ?? '' }}">
                </div>
            </div>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="address" type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address" value="{{ $staff['address'] ?? '' }}">
                </div>
            </div>
            <div class="row flex justify-center px-3 gap-2 m2-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Update Details
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Change Password
        </p>
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.user.profile.change-password') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="username" class="mb-2 text-sm font-medium text-gray-900">Username</label>
                    <input id="username" type="text" name="username" class="bg-gray-50 border border-gray-300 text-red-500 font-semibold text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" value="{{ $user['username'] ?? '' }}" disabled>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="new_password" class="mb-2 text-sm font-medium text-gray-900">New Password</label>
                    <input id="new_password" type="password" name="new_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="New Password" value="{{ $request['full_name'] ?? '' }}" required>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="confirm_password" class="mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                    <input id="confirm_password" type="password" name="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Confirm Password" value="{{ $request['phone'] ?? '' }}" required>
                </div>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-2">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
                    Change Password
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    
    <h2 class="text-2xl font-medium mt-2 mb-3 ml-1">Employment Details</h2>
    @if ($user->target == User::TYPE_STAFF)
    <div class="flex-1 mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 pt-2 pb-4 px-3">
        <p class="text-lg py-1 font-medium text-blue-600 mb-2">
            Salary Configurations
        </p>
        <div class="row flex md:flex-row flex-col gap-2 mb-2">
            <div class="flex flex-col flex-1">
                <label class="mb-2 text-sm font-medium text-gray-900">Position</label>
                <div class="bg-gray-100 font-semibold text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffPositions[$staff['position']] ?? 'Not provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label class="mb-2 text-sm font-medium text-gray-900">Status</label>
                <div class="bg-gray-100 font-semibold text-{{ $staffStatusColors[$staff['status']] ?? 'red' }}-500 text-sm w-full py-2.5 px-2">{{ $staffStatuses[$staff['status']] ?? 'Not provided' }}</div>
            </div>
        </div>
        <div class="row flex md:flex-row flex-col gap-2 mb-2">
            <div class="flex flex-col flex-1">
                <label for="type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                <div class="bg-gray-100 text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffTypes[$staff['type']] ?? 'Not provided' }}</div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="base_salary" class="mb-2 text-sm font-medium text-gray-900">Base Salary</label>
                <div class="w-full flex items-center text-sm bg-gray-100">
                    <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['salary_configs']['base_salary'] ?? 'Not provided' }}</div>
                    <p class="font-medium px-3 h-full py-2.5 flex-1 bg-gray-200 flex items-center">VND/month</p>
                </div>
            </div>
        </div>
        <div class="row flex md:flex-row flex-col gap-2 mb-2">
            @if (!empty($staff['salary_configs']['commission']))
            <div class="flex flex-col flex-1">
                <label class="mb-2 text-sm font-medium text-gray-900">Commission Amount</label>
                <div class="w-full flex items-center text-sm bg-gray-100">
                    <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['salary_configs']['commission']['commission_amount'] ?? 'Not provided' }}</div>
                    <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-200 border-none pl-3">{{ $staffCommissionUnits[$staff['salary_configs']['commission']['commission_unit']] }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col flex-1">
                <label for="base_salary" class="mb-2 text-sm font-medium text-gray-900">Commission Type</label>
                <div class="w-full flex items-center text-sm bg-gray-100">
                    <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffCommissionTypes[$staff['salary_configs']['commission']['commission_type']] ?? 'Not provided' }}</div>
                </div>
            </div>                    
            @else
            <div class="flex flex-col flex-1">
                <p class="mb-2 mt-3 text-sm font-medium text-red-600">Commission is not applied to your current employment.</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection