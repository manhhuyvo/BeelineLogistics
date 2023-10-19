@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">Edit Default Configurations</h2>
    <div class="w-full my-2 py-1">
        @foreach ($countries as $countryCode => $countryName)
        <form method="POST" action="{{ route('admin.country-service-configuration.update') }}">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="mb-4 px-3 py-3 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200">
                <p class="text-xl font-medium text-blue-600 mb-2">
                    {{ $countryName }} <span class="ml-1 text-[15px] font-normal text-gray-600 italic"> (Default Suppliers)</span>
                </p>
                <div class="w-full md:grid md:grid-cols-2 gap-3 flex flex-col">
                    <input type="hidden" name="country" value="{{ $countryCode }}" readonly />
                    @foreach ($services as $serviceCode => $serviceName)
                    <div class="flex flex-col">
                        <label for="{{ $serviceCode }}" class="mb-1 text-sm font-medium text-gray-900">{{ $serviceName }}</label>
                        <select id="{{ $serviceCode }}" name="{{ $serviceCode }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 ">
                            @if ($configsList[$countryCode][$serviceCode] == 0)
                            <option selected disabled>Choose a default supplier</option>
                            @endif
                        @foreach($suppliersList as $key => $value)
                            @if (!empty($configsList[$countryCode][$serviceCode]) && $configsList[$countryCode][$serviceCode] == $key)
                            <option selected value="{{ $key }}">{!! nl2br(e($value)) !!}</option>
                            @else
                            <option value="{{ $key }}">{!! nl2br(e($value)) !!}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                    @endforeach
                </div>
                <div class="row flex md:justify-end justify-center px-3 gap-2 mt-4">
                    <button type="submit" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2">
                        Update
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
        @endforeach
    </div>
</div>

@endsection