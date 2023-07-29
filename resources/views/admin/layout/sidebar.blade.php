@php
   $userLoggedIn = Auth::user();
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
          <a href="{{ route('admin.index') }}" class="flex ml-2 md:mr-24">
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
                  <p class="text-sm font-medium text-gray-900 truncate" role="none">
                    neil.sims@flowbite.com
                  </p>
                </div>
                <ul role="none">
                  <li>
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 flex items-center" role="menuitem"><i class="fa-sharp fa-solid fa-house text-[12px] mr-2"></i>Dashboard</a>
                  </li>
                  <li>
                    <a href="{{ route('admin.user.profile.form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 flex items-center" role="menuitem"><i class="fa-solid fa-user text-[12px] mr-2"></i>Profile</a>
                  </li>
                  <li>
                    <a href="{{ route('admin.logout') }}" class="block px-4 py-2 font-medium text-sm text-red-500 hover:text-white hover:bg-red-500 flex items-center" role="menuitem"><i class="fa-solid fa-arrow-right-from-bracket text-[12.5px] mr-2"></i>Sign out</a>
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
              <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                 <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                    <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                 </svg>
                 <span class="ml-3">Dashboard</span>
              </a>
           </li>
           @if ( in_array($user->level, [StaffModel::POSITION_DIRECTOR, StaffModel::POSITION_ACCOUNTANT]))
           <li>
              <a href="{{ route('admin.staff.list') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                  <i class="fa-solid fa-user-secret flex-shrink-0 text-[19px] ml-[2px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="ml-3">Staffs</span>
              </a>
           </li>
           <li>
              <a href="{{ route('admin.supplier.list') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                  <i class="fa-solid fa-truck-field flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="ml-3">Suppliers</span>
              </a>
           </li>
           @endif
           <li>
              <a href="{{ route('admin.customer.list') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                 <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                    <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                 </svg>
                 <span class="flex-1 ml-3 whitespace-nowrap">Customers</span>
              </a>
           </li>
           @if ( in_array($user->level, [StaffModel::POSITION_DIRECTOR, StaffModel::POSITION_ACCOUNTANT]))
           <li>
              <a href="{{ route('admin.user.list') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                  <i class="fa-solid fa-user-check flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="flex-1 ml-3 whitespace-nowrap">Users</span>
              </a>
           </li>
           <li>
              <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="sidebar-product-dropdown" data-collapse-toggle="sidebar-product-dropdown">
                  <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                     <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
                  </svg>
                  <span class="flex-1 ml-3 text-left whitespace-nowrap">Products</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
              </button>
              <ul id="sidebar-product-dropdown" class="hidden bg-gray-100 text-sm">
                  <li>
                     <a href="{{ route('admin.product-group.list') }}" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Product Groups</a>
                  </li>
                  <li>
                     <a href="{{ route('admin.product.list') }}" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Products List</a>
                  </li>
              </ul>
           </li>
           @endif
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
              </ul>
           </li>
           <li>
              <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                  <i class="fa-solid fa-money-bill flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="flex-1 ml-3 whitespace-nowrap">Invoices</span>
              </a>
           </li>
           <li>
              <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
               <i class="fa-solid fa-receipt flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="flex-1 ml-3 whitespace-nowrap">Bills</span>
              </a>
           </li>
           <li>
              <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
               <i class="fa-solid fa-file-invoice-dollar flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="flex-1 ml-3 whitespace-nowrap">Salary Paycheck</span>
              </a>
           </li>
           <li>
              <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100" aria-controls="sidebar-payments-dropdown" data-collapse-toggle="sidebar-payments-dropdown">
                  <i class="fa-solid fa-comment-dollar flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                  <span class="flex-1 ml-3 text-left whitespace-nowrap">Payments</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
              </button>
              <ul id="sidebar-payments-dropdown" class="hidden bg-gray-100 text-sm">
                  <li>
                     <a href="#" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Invoice Payments</a>
                  </li>
                  <li>
                     <a href="#" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Bill Payments</a>
                  </li>
                  <li>
                     <a href="#" class="flex items-center w-full py-2 px-[4.5] text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200">Salary Payments</a>
                  </li>
              </ul>
           </li>
           <li>
              <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                  <i class="fa-solid fa-clock-rotate-left flex-shrink-0 text-[19px] ml-[1px] text-gray-500 transition duration-75 group-hover:text-gray-900"></i>
                 <span class="flex-1 ml-3 whitespace-nowrap">System Logs</span>
              </a>
           </li>
        </ul>
     </div>
  </aside>
  
  <div class="p-4 sm:ml-60 bg-white min-h-screen">
     <div class="mt-14 bt-white">
      @include('admin.layout.breadcrumbs')