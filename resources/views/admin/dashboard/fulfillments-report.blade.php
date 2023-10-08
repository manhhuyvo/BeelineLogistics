<div class="w-full py-2">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left text-gray-500 min-width border-collapse">
        <thead>
            <tr class="text-center">
                <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-r-[2px] bg-gray-200 w-70">
                    Actions
                </th>
                <th scope="col" class="border px-6 sm:py-4 py-2 border-solid border-r-[2px] whitespace-nowrap bg-gray-200 w-20">
                    No. of records
                </th>
            </tr>
        </thead>
            <tbody>
                <tr class="" style="text-align: left !important">
                    <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Fulfillments have been <span class="font-semibold text-blue-500">CREATED</span></td>
                    <td class="border px-3 py-3 whitespace-nowrap text-blue-500 text-center">{{ $fulfillmentsDetails['created'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Fulfillments have been <span class="font-semibold text-blue-500">WAITING</span></td>
                    <td class="border px-3 py-3 whitespace-nowrap text-blue-500 text-center">{{ $fulfillmentsDetails['waiting'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Fulfillments have been <span class="font-semibold text-yellow-500">SHIPPED</span></td>
                    <td class="border px-3 py-3 whitespace-nowrap text-yellow-500 text-center">{{ $fulfillmentsDetails['shipped'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Fulfillments have been <span class="font-semibold text-green-500">DELIEVERED</span></td>
                    <td class="border px-3 py-3 whitespace-nowrap text-green-500 text-center">{{ $fulfillmentsDetails['delivered'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td class="border px-3 py-3 whitespace-nowrap text-gray-900">Fulfillments have been <span class="font-semibold text-red-500">RETURNED</span></td>
                    <td class="border px-3 py-3 whitespace-nowrap text-red-500 text-center">{{ $fulfillmentsDetails['returned'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>