
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
         <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <!-- Card 1: Buku -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Jumlah Produk Buku</h1>
            <p class="text-4xl font-bold mt-2 text-blue-600">{{ $totalBookProducts }}</p>
        </div>

        <!-- Card 2: Non-Buku -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Jumlah Produk Non-Buku</h1>
            <p class="text-4xl font-bold mt-2 text-green-600">{{ $totalNonBookProducts }}</p>
        </div>

        <!-- Card 3: Transaksi -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Jumlah Transaksi</h1>
            <p class="text-4xl font-bold mt-2 text-red-600">{{ $totalTransactions }}</p>
        </div>
    </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                            Nama Buku
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tanggal Pembelian
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transactions as $index => $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $transaction->book->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                             @if($transaction->status == 1)
                                 <span class="text-green-600 font-semibold">Paid</span>
                             @elseif($transaction->status == 0)
                                 <span class="text-red-600 font-semibold">Unpaid</span>
                             @else
                                 <span class="text-gray-600">Unknown</span>
                             @endif
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $transaction->created_at }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>

    </div>  