@extends('admin.auth.layout')
@section('content')

<section class="bg-gray-50">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
            <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
            Beeline Logisitcs    
        </a>
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Sign in to your account
                </h1>
                <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('admin.login') }}">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">Username</label>
                        <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" placeholder="beegroup_nguyenvana" required>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 " required="">
                    </div>
                    <div class="flex items-center justify-end">
                        <a href="#" id="forgot_password_btn" class="text-sm font-medium text-blue-600 hover:underline ">Forgot password?</a>
                    </div>
                    <div class="flex items-center justify-center border-solid border-red-500 border-[2px] py-1 rounded-lg text-sm opacity-60 hidden" id="forgot_password_message">
                        <p class="text-center font-semibold text-red-500">Please contact your supervisor to manually reset your password.</p>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign in</button>
                </form>
            </div>
        </div>
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