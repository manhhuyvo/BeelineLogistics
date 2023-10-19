@extends('admin.layout.layout')
@section('content')

@php    
    $request = session()->get('request');
@endphp

<div class="relative sm:rounded-lg">
    @include('admin.layout.response')
    <h2 class="text-2xl font-medium mt-2 mb-3">View product group details</h2>
    <div class="w-full mt-4 mb-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 py-1">
        <p class="text-lg px-3 py-1 font-medium text-blue-600">
            Group Details
        </p>
        <div class="w-full flex flex-col gap-3 px-3 py-2 justify-center">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-1">
                    <label for="name" class="mb-2 text-sm font-medium text-gray-900">Group Name <span class="text-red-500">*</span></label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $productGroup['name'] ?? 'Not provided' }}</div>
                </div>
                <div class="flex flex-col flex-1">
                    <label for="description" class="mb-2 text-sm font-medium text-gray-900">Description</label> 
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2">{{ $productGroup['description'] ?? 'Not provided' }}</div>
                </div>
            </div>
            <div class="row flex sm:flex-row flex-col gap-2">
                <div class="flex flex-col flex-2">
                    <label for="note" class="mb-2 text-sm font-medium text-gray-900">Note</label>
                    <div class="bg-gray-50 text-gray-600 text-sm w-full py-2.5 px-2 min-h-[100px]">{{ $productGroup['note'] ?? 'Not provided' }}</div>
                </div>
            </div>    
        </div>
    </div>               
    <div class="row flex md:justify-end justify-center gap-2 w-full">
        <a href="{{ route('admin.product-group.list') }}" class="px-3 py-2 rounded-[5px] text-sm bg-gray-600 text-white font-medium w-auto hover:bg-gray-500 flex items-center gap-2 w-[20%]">
            <i class="fa-solid fa-arrow-left"></i>
            Back To List
        </a>
        <a href="{{ route('admin.product-group.edit.form', ['group' => $productGroup['id']]) }}" class="px-3 py-2 rounded-[5px] text-sm bg-blue-600 text-white font-medium w-auto hover:bg-blue-500 flex items-center gap-2  w-[20%]">
            Edit Details
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>

@endsection