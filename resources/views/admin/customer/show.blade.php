@extends('admin.layout.layout')
@section('content')

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2">View customer details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1 pb-3">
        <div class="w-full flex flex-col gap-3 px-3 py-1 justify-center">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Customer Details
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="customer_id" class="mb-2 text-sm font-medium text-gray-900">Customer ID</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['customer_id'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="full_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['phone'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['address'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="company" class="mb-2 text-sm font-medium text-gray-900">Company</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['company'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="staff_id" class="mb-2 text-sm font-medium text-gray-900">Staff Manage</label>
                    <div class="bg-gray-50 font-semibold text-gray-600 text-sm w-full py-2.5 px-2">{{ $staffsList[$customer['staff_id']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="type" class="mb-2 text-sm font-medium text-gray-900">Type</label>
                    <div class="bg-gray-50 font-semibold text-gray-600 text-sm w-full py-2.5 px-2">{{ $customerTypes[$customer['type']] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="status" class="mb-2 text-sm font-medium text-gray-900">Status</label>
                    <div class="bg-gray-50 font-semibold text-{{ $customerStatusColors[$customer['status']] ?? 'red' }}-500 text-sm w-full py-2.5 px-2">{{ $customerStatuses[$customer['status']] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]">{{ $customer['note'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Default Sender
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_sender']['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_sender_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_sender']['phone'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_sender_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_sender']['address'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Default Receiver
            </p>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Full Name</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_receiver']['full_name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_phone" class="mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_receiver']['phone'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_zone" class="mb-2 text-sm font-medium text-gray-900">Zone</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $receiverZones[$customer['default_receiver']['zone']] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="default_receiver_address" class="mb-2 text-sm font-medium text-gray-900">Address</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['default_receiver']['address'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Country Configurations
            </p>
            <div class="w-full flex flex-col gap-2">
                <p class="text-sm font-semibold">Countries available for this customer</p>
                <div class="w-full md:grid md:grid-cols-3 gap-2 flex flex-col">
                    @foreach ($currentCountriesMeta as $country)
                    <div class="h-[50px] p-0">
                        <div class="inline-flex gap-2 items-center w-full h-full border-solid border-[2px] border-gray-300 flex justify-center items-center bg-gray-300 text-gray-800 rounded-[3px]">
                            <span class="font-semibold md:text-[14px] text-sm">{{ Str::upper($countries[$country] ?? 'Unknown') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <p class="text-lg font-medium text-blue-600 mt-1">
                Service Configurations
            </p>
            <div class="w-full flex flex-col gap-2">
                <p class="text-sm font-semibold">Services available for this customer</p>
                <div class="w-full md:grid md:grid-cols-3 gap-2 flex flex-col">
                    @foreach ($currentServicesMeta as $service)
                    <div class="h-[50px] p-0">
                        <div class="inline-flex gap-2 items-center w-full h-full border-solid border-[2px] border-gray-300 flex justify-center items-center bg-gray-300 text-gray-800 rounded-[3px]">
                            <span class="font-semibold md:text-[14px] text-sm">{{ Str::upper($services[$service] ?? 'Unknown') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            @if (!empty($customer['price_configs']))            
            <div class="w-full flex  gap-3 mt-2 items-start">
                <p class="text-lg font-medium text-red-600">
                    Price Configuration
                </p>
                @if ($user->staff->isAdmin())
                <a href="{{ route('admin.customer.price-configs.edit.form', ['customer' => $customer['id']]) }}" class="w-auto px-3 py-1 rounded-[5px] text-sm bg-blue-600 text-white font-medium hover:shadow-lg hover:bg-blue-500 flex items-center gap-3">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Edit Price
                </a>
                @endif
            </div>
                @if (!empty($customer['price_configs']['fulfillment_pricing']))            
                <p class="text-[16px] font-medium text-blue-600 mt-1">
                    Fulfillment Pricing
                </p>
                <div class="row flex sm:flex-row flex-col gap-2">
                    @if (!empty($customer['price_configs']['fulfillment_pricing']['fulfillment_per_order']))
                    <div class="flex flex-col flex-1 sm:max-w-[50%]">
                        <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Per Order</label>
                        <div class="w-full flex items-center text-sm bg-gray-50">
                            <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['price_configs']['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_amount'] ?? '0' }}</div>
                            <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-100 border-none pl-3">{{ $customer['price_configs']['fulfillment_pricing']['fulfillment_per_order']['fulfillment_per_order_unit'] ?? 'Not Provided' }}
                            </p>
                        </div>
                    </div>
                    @endif

                    @if (!empty($customer['price_configs']['fulfillment_pricing']['fulfillment_percentage']))
                    <div class="flex flex-col flex-1 sm:max-w-[50%]">
                        <label for="default_receiver_name" class="mb-2 text-sm font-medium text-gray-900">Percentage</label>
                        <div class="w-full flex items-center text-sm bg-gray-50">
                            <div class="bg-transparent text-gray-600 text-sm w-full py-2.5 px-2">{{ $customer['price_configs']['fulfillment_pricing']['fulfillment_percentage']['fulfillment_percentage_amount'] ?? '0' }}</div>
                            <p class="text-sm h-full font-medium text-sm flex-1 p-2.5 bg-gray-100 border-none pl-3">
                                %
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            @else            
            <div class="w-full flex flex-col items-end mt-2 gap-2">
                @if ($user->staff->isAdmin())
                <a href="{{ route('admin.customer.price-configs.edit.form', ['customer' => $customer['id']]) }}" class="w-auto px-3 py-2 rounded-[5px] text-sm bg-green-600 text-white font-medium hover:shadow-lg hover:bg-green-500 flex items-center gap-3">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Add Price
                </a>
                @endif
                <p class="text-md font-medium text-orange-600 text-center w-full">
                    There is no price configurations for this customer
                </p>
            </div>
            @endif
        </div>
    </div>       
    <div class="row flex md:justify-end justify-center gap-2 w-full">
        <a href="{{ route('admin.customer.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.customer.edit.form', ['customer' => $customer['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div> 
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 pt-2 pb-4 px-3">
        <p class="text-lg font-medium text-blue-600">
            Action Logs
        </p>
            @include ('admin.layout.general-log-table')
        </div>        
    </div>
</div>

@endsection