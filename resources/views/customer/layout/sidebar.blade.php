@php
   $userLoggedIn = Auth::user();
   
   $customer = $user->customer;
   $staff = $user->staff;
   $supplier = $user->supplier;
@endphp
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-start">
          <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
              <span class="sr-only">Open sidebar</span>
              <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                 <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
              </svg>
           </button>
          <a href="{{ route('customer.index') }}" class="flex ml-2 md:mr-24">
            <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="FlowBite Logo" />
            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">Beeline Logistics</span>
          </a>
        </div>
        <div class="flex items-center">
            <div class="flex items-center ml-3">
              <div>
                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                  <span class="sr-only">Open user menu</span>
                  <img class="w-8 h-8 rounded-full" src="{{ asset('assets/images/user-512.png') }}" alt="user photo">
                </button>
              </div>
              <div class="z-50 hidden my-4 text-base list-none bg-gray-100 divide-y divide-gray-200 rounded shadow mr-3" id="dropdown-user">
                <div class="px-4 py-3" role="none">
                  <p class="text-sm text-gray-900" role="none">
                    {{ $userLoggedIn->username }}
                  </p>
                  <p class="text-sm font-medium text-gray-900 truncate mt-2" role="none">
                     @if (!empty($customer))
                        {{ $customer->full_name }}
                     @elseif (!empty($staff))
                     {{ $staff->full_name }}
                     @elseif (!empty($supplier))
                     {{ $supplier->full_name }}
                     @endif
                  </p>
                </div>
                <ul role="none">
                  <li>
                    <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 flex items-center" role="menuitem"><i class="fa-sharp fa-solid fa-house text-[12px] mr-2"></i>Dashboard</a>
                  </li>
                  <li>
                    <a href="{{ route('customer.user.profile.form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 flex items-center" role="menuitem"><i class="fa-solid fa-user text-[12px] mr-2"></i>Profile</a>
                  </li>
                  <li>
                    <a href="{{ route('customer.logout') }}" class="block px-4 py-2 font-medium text-sm text-red-500 hover:text-white hover:bg-red-500 flex items-center" role="menuitem"><i class="fa-solid fa-arrow-right-from-bracket text-[12.5px] mr-2"></i>Sign out</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
      </div>
    </div>
  </nav>
  
  <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-60 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
     <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">
           <li>
              <a href="{{ route('customer.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                 <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                    <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                 </svg>
                 <span class="ml-3">Dashboard</span>
              </a>
           </li>
           <li>
              <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="sidebar-user-dropdown" data-collapse-toggle="sidebar-user-dropdown">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                       <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                    </svg>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Orders</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                       <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
              </button>
              <ul id="sidebar-user-dropdown" class="hidden bg-gray-100 text-sm">
                    <li>
                       <a href="#" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Import Orders</a>
                    </li>
                    <li>
                       <a href="#" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Export Orders</a>
                    </li>
                    <li>
                       <a href="#" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Checkout Orders</a>
                    </li>
                    <li>
                       <a href="{{ route('customer.fulfillment.list') }}" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Fulfillment Orders</a>
                    </li>
              </ul>
           </li>
           <li>
              <a href="{{ route('customer.invoice.list') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                  <i class="fa-solid fa-money-bill flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="flex-1 ml-3 whitespace-nowrap">Invoices</span>
              </a>
           </li>
        </ul>
     </div>
  </aside>
  
  <div class="p-4 sm:ml-60 bg-white min-h-screen">
     <div class="mt-14 bt-white">
      @include('customer.layout.breadcrumbs')