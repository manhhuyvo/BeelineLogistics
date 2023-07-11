@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <div class="w-full flex md:justify-between md:flex-row flex-col items-start">
        <h2 class="text-2xl font-medium mt-2 md:mb-0 md:w-auto w-full">View staff details</h2> 
    </div>
    <div class="w-full bg-white flex md:flex-row flex-col md:gap-4 gap-3 p-0 m-0">
        <div class="flex-1 mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 pt-2 pb-4 px-3">
            <p class="text-lg py-1 font-medium text-blue-600 mb-2">
                Personal Details
            </p>
            <div class="row flex flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['phone'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Date of Birth</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['dob'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-2">
                    <label class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['address'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]">{{ !empty($staff['note']) ? $staff['note'] : 'Not provided' }}</div>
                </div>
            </div>        
        </div>
        <div class="flex-1 mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 pt-2 pb-4 px-3">
            <p class="text-lg py-1 font-medium text-blue-600 mb-2">
                Salary Configurations
            </p>
            <div class="row flex flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Position</label>
                    <div class="bg-gray-50 font-semibold text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffPositions[$staff['position']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ $staffStatusColors[$staff['status']] ?? 'red' }}-500 text-sm w-full py-2.5 px-2">{{ $staffStatuses[$staff['status']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffTypes[$staff['type']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="base_salary" class="mb-2 text-sm font-medium text-gray-900">Base Salary</label>
                    <div class="w-full flex items-center text-sm bg-gray-50">
                        <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['salary_configs']['base_salary'] ?? 'Not provided' }}</div>
                        <p class="font-medium px-3 h-full py-2.5 flex-1 bg-gray-200 flex items-center">VND/month</p>
                    </div>
                </div>
                @if (!empty($staff['salary_configs']['commission']))
                <div class="flex flex-col flex-1">
                    <label class="mb-2 text-sm font-medium text-gray-900">Commission Amount</label>
                    <div class="w-full flex items-center text-sm bg-gray-50">
                        <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $staff['salary_configs']['commission']['commission_amount'] ?? 'Not provided' }}</div>
                        <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-200 border-none pl-3">{{ $staffCommissionUnits[$staff['salary_configs']['commission']['commission_unit']] }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="base_salary" class="mb-2 text-sm font-medium text-gray-900">Commission Type</label>
                    <div class="w-full flex items-center text-sm bg-gray-50">
                        <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffCommissionTypes[$staff['salary_configs']['commission']['commission_type']] ?? 'Not provided' }}</div>
                    </div>
                </div>                    
                @else
                <div class="flex flex-col flex-1">
                    <p class="mb-2 mt-3 text-sm font-medium text-red-600">Commission is not applied to this staff.</p>
                </div>
                @endif
            </div>
        </div>
    </div>       
    <div class="row flex md:justify-end justify-center md:px-3 gap-2 w-full">
        <a href="{{ route('admin.staff.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.staff.edit.form', ['staff' => $staff['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>
  @endsection