@extends('customer.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('customer.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3 ml-1">Edit profile</h2>
    <form class="w-full" action="{{ route('customer.user.profile.update') }}" method="POST">
        <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 p-3 flex flex-col gap-2">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <p class="text-lg font-medium text-blue-600">
                Personal Details
            </p>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="full_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="full_name" type="text" name="full_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Full Name" value="{{ $customer['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="phone" type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Phone" value="{{ $customer['phone'] ?? '' }}">
                </div>
            </div>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="address" type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Address" value="{{ $customer['address'] ?? '' }}">
                </div>
            </div>
        </div>

        <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 p-3 flex flex-col gap-2">
            <p class="text-lg font-medium text-blue-600">
                Default Sender
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="default_sender_name" type="text" name="default_sender_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Full Name" value="{{ $customer['default_sender']['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_sender_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="default_sender_phone" type="text" name="default_sender_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Phone" value="{{ $customer['default_sender']['phone'] ?? '' }}">
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="default_sender_address" type="text" name="default_sender_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Address" value="{{ $customer['default_sender']['address'] ?? '' }}">
                </div>
            </div>
        </div>
        
        <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 p-3 flex flex-col gap-2">
            <p class="text-lg font-medium text-blue-600">
                Default Receiver
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <input id="default_receiver_name" type="text" name="default_receiver_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Full Name" value="{{ $customer['default_receiver']['full_name'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input id="default_receiver_phone" type="text" name="default_receiver_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Sender Phone" value="{{ $customer['default_receiver']['phone'] ?? '' }}">
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_zone" class="mb-2 text-sm font-medium text-gray-900">Zone</label>
                    <select id="default_receiver_zone" name="default_receiver_zone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                        @if (empty($customer['default_receiver']['zone']))
                        <option selected disabled>Choose a zone</option>
                        @endif
                    @foreach($receiverZones as $key => $value)
                        @if (!empty($customer['default_receiver']['zone']) && $customer['default_receiver']['zone'] == $key)
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
                    <label for="default_receiver_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input id="default_receiver_address" type="text" name="default_receiver_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Receiver Address" value="{{ $customer['default_receiver']['address'] ?? '' }}">
                </div>
            </div>
        </div>        
        <div class="row flex justify-center px-3 gap-2 m2-4">
            <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                Update Details
            </button>
            <a href="{{ route('customer.dashboard') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                Cancel
            </a>
        </div>
    </form>

    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Change Password
        </p>
        <form class="w-full flex flex-col gap-3 px-3 py-2 justify-center" action="{{ route('customer.user.profile.change-password') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row flex md:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="username" class="mb-2 text-sm font-medium text-gray-900">Username</label>
                    <input id="username" type="text" name="username" class="bg-gray-50 border border-gray-300 text-red-500 font-semibold text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" value="{{ $user['username'] ?? '' }}" disabled>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="new_password" class="mb-2 text-sm font-medium text-gray-900">New Password</label>
                    <input id="new_password" type="password" name="new_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="New Password" value="{{ $request['password'] ?? '' }}" required>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="confirm_password" class="mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                    <input id="confirm_password" type="password" name="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5" placeholder="Confirm Password" value="{{ $request['password2'] ?? '' }}" required>
                </div>
            </div>
            <div class="row flex justify-center px-3 gap-2 mt-2">
                <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-red-600 text-white font-medium w-auto hover:bg-red-500 flex items-center gap-2">
                    Change Password
                </button>
                <a href="{{ route('customer.dashboard') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @if (!empty($customer['price_configs']))
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 p-3 flex flex-col gap-2">
        @if (!empty($customer['price_configs']['fulfillment_pricing']))            
        <p class="text-[16px] font-medium text-blue-600 mt-1">
            Fulfillment Pricing
        </p>
        <div class="row flex sm:flex-row flex-col gap-2">
            @if (!empty($customer['price_configs']['fulfillment_pricing']['fulfillment_per_order']))
            <div class="flex flex-col flex-1 sm:max-w-[50%]">
                <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Per Order</label>
                <div class="w-full flex items-center text-sm bg-gray-100">
                    <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['price_configs']['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_amount'] ?? '0' }}</div>
                    <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-200 border-none pl-3">{{ $customer['price_configs']['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_unit'] ?? 'Not Provided' }}
                    </p>
                </div>
            </div>
            @endif

            @if (!empty($customer['price_configs']['fulfillment_pricing']['fulfillment_percentage']))
            <div class="flex flex-col flex-1 sm:max-w-[50%]">
                <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Percentage</label>
                <div class="w-full flex items-center text-sm bg-gray-100">
                    <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['price_configs']['fulfillment_pricing']['fulfillment_percentage']['fulfillment_percentage_amount'] ?? '0' }}</div>
                    <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-200 border-none pl-3">
                        %
                    </p>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif
</div>
@endsection