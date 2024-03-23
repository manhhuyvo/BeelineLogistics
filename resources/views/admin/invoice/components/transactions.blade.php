@if(!empty($invoice['transactions']))
    <div class="w-full my-4 rounded-lg bg-white shadow-lg border-solid border-[1px] border-gray-200 flex flex-col gap-3 p-3 justify-center">
        <p class="text-lg font-medium text-blue-600 mt-1">
            Transactions
        </p>
        <div class="w-full flex flex-col gap-4">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 shadow-lg rounded-lg">
                    <thead class="text-xs text-white font-semibold uppercase bg-indigo-950"  style="text-align: center !important;">
                        <tr>
                            <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                                Transaction ID
                            </th>
                            <th scope="col" class="px-6 sm:py-3 py-2">
                                Amount
                            </th>
                            <th scope="col" class="px-6 sm:py-3 py-2">
                                Type
                            </th>
                            <th scope="col" class="px-6 sm:py-3 py-2">
                                Status
                            </th>
                            <th scope="col" class="px-6 sm:py-3 py-2">
                                Description
                            </th>
                            <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                                Transaction Date
                            </th>
                            <th scope="col" class="px-6 sm:py-3 py-2 whitespace-nowrap">
                                Added By
                            </th>
                            <th scope="col" class="px-6 sm:py-3 py-2">
                            </th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center !important;">
                        @foreach($invoice['transactions'] as $transaction)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <a href="#" class="underline text-blue-700 hover:text-blue-500" target="_blank">
                                    #{{ $transaction['id'] }}
                                </a>
                            </th>
                            <td class="px-6 py-4">
                                {{ $transaction['amount'] }}
                            </td>
                            <th scope="row" class="px-6 py-4 whitespace-nowrap text-{{ TransactionEnum::MAP_TYPE_COLORS[$transaction['type']] }}-500">
                                {{ TransactionEnum::MAP_TYPES[$transaction['type']] ?? 'Debit'  }}
                            </th>
                            <td scope="row" class="py-4 font-medium whitespace-nowrap text-[12px]">
                                <span class="bg-{{ PaymentEnum::MAP_STATUS_COLORS[$transaction['payment']['status']] }}-500 py-2 px-3 rounded-lg text-white">{{ PaymentEnum::MAP_STATUSES[$transaction['payment']['status']] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transaction['description'] ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transaction['transaction_date'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                <a href="{{ route('admin.staff.show', ['staff' => $transaction['payment']['user']['staff_id']]) }}" class="underline hover:text-gray-400" target="_blank">
                                    <b>{{ $transaction['payment']['user']['staff']['full_name'] }}</b> ({{ StaffEnum::MAP_POSITIONS[$transaction['payment']['user']['staff']['position']] }})
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <button class="border-solid border-[0.5px] border-transparent hover:bg-gray-50 px-[6px] py-[1px] rounded-sm hover:border-gray-200" title="More details"> 
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
<p class="text-sm w-full text-center text-red-500 font-semibold">There is no payments recorded for this invoice.</p>
@endif