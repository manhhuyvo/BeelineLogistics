@extends('welcome.layout')
@section('content')

<section class="bg-gray-50">
    @include('welcome.response')
    <div class="flex flex-col items-center justify-center px-6 py-8 pb-12 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex flex-col items-center gap-2 mb-10 text-2xl font-semibold text-gray-900">
            <div class="flex items-center">
                <img src="{{ asset('assets/images/logo_landscape.png') }}" class="h-14 mr-4" alt="FlowBite Logo" />
            </div>  
            <span class="self-center text-center font-medium sm:text-2xl text-lg">Welcome to Beeline Logistics Management System</span>
        </a>
        <div class="w-full flex md:flex-row flex-col gap-4 px-4 items-center justify-center">
            <a href="{{ route('customer.login.form') }}" class="md:flex-1 sm:w-[70%] w-full flex justify-center">
                <div class="w-full bg-white rounded-lg shadow-xl cursor-pointer px-5 py-5 hover:border hover:border-yellow-300 hover:border-[1px] hover:shadow-yellow-500 transition-shadow duration-300 transition-border">
                    <div class="w-full flex justify-center mb-3">
                        <i class="fa-solid fa-users text-[50px] text-yellow-500"></i>
                    </div>
                    <div class="my-2">
                        <h1 class="text-xl text-center font-semibold leading-tight tracking-tight  text-yellow-500 md:text-2xl">
                            CUSTOMER PORTAL
                        </h1>           
                    </div>
                </div>
            </a>
            <a href="{{ route('supplier.login.form') }}" class="md:flex-1 sm:w-[70%] w-full flex justify-center">
                <div class="w-full bg-white rounded-lg shadow-xl cursor-pointer px-5 py-5 hover:border hover:border-yellow-300 hover:border-[1px] hover:shadow-yellow-500 transition-shadow duration-300 transition-border">
                    <div class="w-full flex justify-center mb-3">
                        <i class="fa-solid fa-earth-oceania text-[50px] text-yellow-500"></i>
                    </div>
                    <div class="my-2">
                        <h1 class="text-xl text-center font-semibold leading-tight tracking-tight  text-yellow-500 md:text-2xl">
                            WAREHOUSE PORTAL
                        </h1>           
                    </div>
                </div>
            </a>
        </div>
        <a href="{{ route('admin.login.form') }}" class="w-full px-4 mt-4 flex justify-center">
            <div class="md:w-[50%] sm:w-[70%] w-full bg-white rounded-lg shadow-xl cursor-pointer px-5 py-5 hover:border hover:border-yellow-300 hover:border-[1px] hover:shadow-yellow-500 transition-shadow duration-300 transition-border">
                <div class="w-full flex justify-center mb-3">
                    <i class="fa-solid fa-toolbox text-[50px] text-yellow-500"></i>
                </div>
                <div class="my-2">
                    <h1 class="text-xl text-center font-semibold leading-tight tracking-tight  text-yellow-500 md:text-2xl">
                        ADMINISTRATIVE PORTAL
                    </h1>           
                </div>
            </div>
        </a>
    </div>
  </section>

  <script>
    const forgotPasswordMessage = $('#forgot_password_message');
    $(document).ready(() => {
        $('#forgot_password_btn').on('click', function () {
            if (forgotPasswordMessage.is(':hidden')) {
                forgotPasswordMessage.show();
            } else if (forgotPasswordMessage.is(':visible')) {
                forgotPasswordMessage.hide();
            }
        });
    })
  </script>

  @endsection