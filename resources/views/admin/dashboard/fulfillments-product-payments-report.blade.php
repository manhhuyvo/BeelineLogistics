<div class="w-full py-2">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left text-gray-500 min-width border-collapse">
        <thead>
            <tr class="text-center">
                <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-r-[2px] bg-gray-200 w-5">
                    Actions
                </th>
                <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-r-[2px] bg-gray-200 w-70">
                    No. of records
                </th>
            </tr>
        </thead>
            <tbody>
                <tr class="" style="text-align: left !important">
                    <td class="border px-5 py-3 whitespace-nowrap text-gray-900">Total number of payments have been <span class="font-semibold text-blue-500">CREATED</span></td>
                    <td class="border px-5 py-3 whitespace-nowrap text-blue-500 text-center">{{ $fulfillmentProductPayments['created'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td class="border px-5 py-3 whitespace-nowrap text-gray-900">Total number of payments are still <span class="font-semibold text-blue-500">PENDING</span></td>
                    <td class="border px-5 py-3 whitespace-nowrap text-blue-500 text-center">{{ $fulfillmentProductPayments['pending'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td class="border px-5 py-3 whitespace-nowrap text-gray-900">Total number of payments have been <span class="font-semibold text-green-500">APPROVED</span></td>
                    <td class="border px-5 py-3 whitespace-nowrap text-yellow-500 text-center">{{ $fulfillmentProductPayments['approved'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td class="border px-5 py-3 whitespace-nowrap text-gray-900">Total number of payments have been <span class="font-semibold text-red-500">DECLINED</span></td>
                    <td class="border px-5 py-3 whitespace-nowrap text-green-500 text-center">{{ $fulfillmentProductPayments['declined'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>