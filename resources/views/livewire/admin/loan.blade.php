<div>
<div>
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete ')

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Data Peminjaman Buku</h2>
        <div class="flex space-x-4">
            @include('layouts.component.createdel ')
        </div>
    </div>
  <div class="mb-4">
        @include('layouts.component.searchtable')
        <label class="text-sm text-gray-700">
            Show
            <select wire:model.live="perPage" class="ml-2 border rounded p-1">
                <option value="5">5</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            entries
            </label>
    </div>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Transaksi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Peminjaman</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengembalian</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($loans as $index => $loan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $loan->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loans->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                           TSAJA {{ $loans->firstItem() + $index }}201EJ
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->user->name}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            
                                    @foreach ($loan->items as $detail)
                                        {{ $detail->book->name }}
                                    @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->rental_date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->return_date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @switch($loan->status)
                                @case('rented')
                                    <flux:badge color="blue">Rented</flux:badge>
                                    @break

                                @case('returned')
                                    <flux:badge color="green">Returned</flux:badge>
                                    @break

                                @case('late')
                                    <flux:badge color="yellow">Late</flux:badge>
                                    @break

                                @case('cancelled')
                                    <flux:badge color="red">Cancelled</flux:badge>
                                    @break

                                @default
                                    <flux:badge color="gray">{{ ucfirst($loan->status) }}</flux:badge>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @foreach ($loan->items as $detail)
                                {{ $detail->quantity }}
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @if ($loan->fine)
                                Rp {{ number_format($loan->fine->fine_amount, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($loan->status === 'rented')
                            <button wire:click="openReturnModal({{ $loan->id }})" class="bg-green-600 hover:bg-green-700 px-2 py-1 text-white rounded">
                                Kembalikan
                            </button>
                        @else
                            <span class="text-gray-500">Selesai</span>
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $loans->links() }}
    </div>
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        {{ $modalTitle }}
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <flux:input type="text" label="Nama Peminjam" :value="$selectedRental?->user?->name" disabled />
                        </div>
                        <div>
                            <flux:input type="text" label="Judul Buku" :value="$selectedRental?->book?->title" disabled />
                        </div>
                        <div>
                            <flux:input type="text" label="Total Price" :value="$selectedRental?->total_price" disabled />
                        </div>
                        <div>
                            <flux:input type="date" label="Tanggal Pengembalian Aktual" wire:model="actualReturnDate" />
                            @error('actualReturnDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pengembalian (Opsional)</label>
                            <input type="file" wire:model="proofImage" class="block w-full border border-gray-300 rounded p-1">
                            @if ($proofImage)
                                <div class="mt-2">
                                    Preview: <img src="{{ $proofImage->temporaryUrl() }}" alt="Bukti" class="w-32 h-auto mt-1 rounded">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <div class="flex space-x-4 sm:space-x-reverse">
                        <flux:button wire:click="$set('showModal', false)" variant="filled">
                            Cancel
                        </flux:button>
                        <flux:button wire:click.prevent="processReturn" variant="primary">
                            Simpan
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

</div>
</div>
