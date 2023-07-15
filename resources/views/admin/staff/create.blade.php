@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Add new staff</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Personal Details
        </p>
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('admin.staff.store') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="full_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="full_name" type="text" name="full_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Full Name" value="{{ $request['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="phone" type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Phone" value="{{ $request['phone'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="dob" class="mb-2 text-sm font-medium text-gray-900">Date of Birth</label>
                    <input id="dob" type="date" name="dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Date of Birth" value="{{ !empty($request['dob']) ? Carbon::createFromFormat('d/m/Y', $request['dob'])->format('Y-m-d') : '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="address" type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address" value="{{ $request['address'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea id="note" name="note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Extra note" value="{{ $request['note'] ?? '' }}"></textarea>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Salary Configurations
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="position" class="mb-2 text-sm font-medium text-gray-900">Position</label>
                    <select id="position" name="position" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['position']))
                        <option selected disabled>Choose a position</option>
                        @endif
                    @foreach($staffPositions as $key => $value)
                        @if (!empty($request['position']) && $request['position'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['status']))
                        <option selected disabled>Choose a status</option>
                        @endif
                    @foreach($staffStatuses as $key => $value)
                        @if (!empty($request['status']) && $request['status'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                    <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($request['type']))
                        <option selected disabled>Choose a type</option>
                        @endif
                    @foreach($staffTypes as $key => $value)
                        @if (!empty($request['type']) && $request['type'] == $key)
                        <option selected value="{{ $key }}">{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="base_salary" class="mb-2 text-sm font-medium text-gray-900">Base Salary</label>
                    <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50">
                        <input type="text" name="base_salary" id="base_salary" class="text-sm bg-transparent w-full p-2.5 border-none focus:ring-0" placeholder="Base Salary (Numbers only, Ex: 15000000)" value="">
                        <p class="font-medium px-3 h-full py-2.5 flex-1 bg-gray-200 flex items-center">VND/month</p>
                    </div>
                </div>
            </div>
            <div class="row flex gap-0.5 items-center px-3">
                <input id="apply_commission" type="checkbox" name="apply_commission" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-400 rounded focus:ring-blue-200">
                <label for="apply_commission" class="text-sm font-medium text-red-500 w-fit">Apply Commission?</label>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2" id="commission-div">
                <div class="flex flex-col flex-1">
                    <label for="commission_amount" class="mb-2 text-sm font-medium text-gray-900">Commission Amount</label>
                    <div class="w-full flex items-center border-solid border-[1px] border-gray-300 text-gray-900 text-sm rounded-lg bg-gray-50">
                        <input type="text" name="commission_amount" id="commission_amount" class="text-sm bg-transparent w-full p-2.5 border-none focus:ring-0" placeholder="Amount (Ex: 2.5)">
                        <select id="commission_unit" name="commission_unit" class="text-sm h-full font-medium text-sm focus:ring-1 flex-1 p-2.5 bg-gray-200 border-none pl-3 rounded-r-lg">
                        @foreach($staffCommissionUnits as $key => $value)
                            @if (!empty($request['commission']) && !empty($request['type']) && $request['type'] == $key)
                            <option selected value="{{ $key }}">{{ $value }}</option>
                            @else
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex flex-col flex-1">
                    <div class="flex flex-col flex-1">
                        <label for="commission_type" class="mb-2 text-sm font-medium text-gray-900">Commission Type</label>
                        <select id="commission_type" name="commission_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @foreach($staffCommissionTypes as $key => $value)
                            @if (!empty($request['type']) && $request['type'] == $key)
                            <option selected value="{{ $key }}">{{ $value }}</option>
                            @else
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-4">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                    Create
                </button>
                <a href="{{ route('admin.staff.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

  <script>
    // Commission related fields
    const commissionDiv = $('#commission-div');
    const applyCommission = $('#apply_commission');
    const commissionAmount = $('#commission_amount');
    const commissionUnit = $('#commission_unit');
    const commissionType = $('#commission_type');

    // Hide the commission div
    commissionDiv.hide();
    
    $(document).ready(function() {

        // Add event to show/hide commission div if checkbox is checked/unchecked
        applyCommission.on('click', function() {
            if (commissionDiv.is(':hidden')) {
                commissionDiv.show();
            } else if (commissionDiv.is(':visible')) {
                commissionDiv.hide();
                commissionAmount.val('');
                commissionUnit.val('1');
                commissionType.val('1');
            }
        })
    })
  </script>

  @endsection