<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Dashboard</h1>
    <div class="grid auto-rows-min gap-4 md:grid-cols-2">
        <!-- Card 1: Buku -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Jumlah Produk Buku</h1>
            <p class="text-4xl font-bold mt-2 text-blue-600">{{ $totalBookProducts }}</p>
        </div>

        <!-- Card 2: Transaksi Berlangsung -->
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <h1 class="text-lg font-semibold text-gray-700 dark:text-white">Jumlah Penyewaan Aktif</h1>
            <p class="text-4xl font-bold mt-2 text-red-600">{{ $loans->total() }}</p>
        </div>
    </div>

    <!-- Tabel Data Rental -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
    <tr>
        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">No</th>
        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">Nama Buku</th>
        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">Status</th>
        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">Tanggal Sewa</th>
    </tr>
</thead>
<tbody>
@foreach($loans as $index => $loan)
    <tr>
        <!-- Kolom Nomor -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $loop->iteration + ($loans->firstItem() - 1) }}
        </td>

        <!-- Kolom Nama Buku -->
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            @foreach($loan->items as $item)
                {{ $item->book->name ?? '-' }}{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </td>

        <!-- Kolom Status -->
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            @if($loan->status == 'rented')
                <span class="text-blue-600 font-semibold">Rented</span>
            @elseif($loan->status == 'late')
                <span class="text-yellow-600 font-semibold">Late</span>
            @else
                <span class="text-gray-600">Unknown</span>
            @endif
        </td>

        <!-- Kolom Tanggal -->
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $loan->created_at->format('d M Y') }}
        </td>
    </tr>
@endforeach
</tbody>

        </table>
    </div>

    <div class="mt-4">
        {{ $loans->links() }}
    </div>
</div>
