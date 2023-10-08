<div class="w-full py-2">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left text-gray-500 min-width border-collapse">
        <thead>
            <tr class="text-center">
                <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-r-[2px] bg-gray-200 w-70">
                    Actions
                </th>
                <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-r-[2px] bg-gray-200 w-5">
                    No. of records
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="" style="text-align: left !important">
                <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Invoices have been <span class="font-semibold text-blue-500">CREATED</span></td>
                <td class="border px-3 py-3 whitespace-nowrap text-blue-500 text-center">{{ $invoicesDetails['created'] ?? 0 }}</td>
            </tr>
            <tr>
                <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Invoices are still <span class="font-semibold text-blue-500">PENDING</span></td>
                <td class="border px-3 py-3 whitespace-nowrap text-blue-500 text-center">{{ $invoicesDetails['pending'] ?? 0 }}</td>
            </tr>
            <tr>
                <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Invoices are still <span class="font-semibold text-red-500">UNPAID</span></td>
                <td class="border px-3 py-3 whitespace-nowrap text-green-500 text-center">{{ $invoicesDetails['unpaid'] ?? 0 }}</td>
            </tr>
            <tr>
                <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Invoices are <span class="font-semibold text-red-500">OVERDUE</span></td>
                <td class="border px-3 py-3 whitespace-nowrap text-green-500 text-center">{{ $invoicesDetails['overdue'] ?? 0 }}</td>
            </tr>
        </tbody>
        </table>
    </div>
</div>