<div>
    <div>
    @include('layouts.component.swalert')
    @include('layouts.component.confirmdelete')

<div class="mb-6">
    <!-- Judul halaman dan aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Data Peminjaman Buku</h2>

                <!-- Breadcrumb dengan jarak -->
                <div class="mt-2">
                    @include('layouts.component.breadcrumb', [
                        'breadcrumbs' => [
                            ['label' => 'Dashboard', 'url' => route('dashboard')],
                            ['label' => 'Data Peminjaman Buku']
                        ]
                    ])
                </div>
            </div>

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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Transaksi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Peminjaman</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengembalian</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($loans as $index => $loan)
                    <tr>
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
                            {{ $loan->payment->status}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @foreach ($loan->items as $detail)
                                {{ $detail->quantity }}
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">

                           Rp.{{ number_format($loan->payment->amount) }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($loan->status === 'rented'|| $loan->status === 'late')
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
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 aria-hidden="true"
                 wire:click="$set('showModal', false)"></div>

            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[99999]">
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
                            <flux:input type="date" label="Tanggal Pengembalian Aktual" wire:model="actualReturnDate" 
                                        wire:change="calculateFine" />
                        </div>

                        @if($showFineDetails)
                            <div class="border-t border-gray-200 pt-4 bg-red-100 rounded-lg p-4">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Detail Denda</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Hari Keterlambatan:</p>
                                        <p class="font-medium">{{ $lateDays }} hari</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Denda:</p>
                                        <p class="font-medium">Rp {{ number_format($fineAmount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                    <select wire:model="paymentMethod" class="block w-full border border-gray-300 rounded p-2 bg-white">
                                        <option value="cash">Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        
                        <!-- File Upload Section - Diperbaiki -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pengembalian (Opsional)</label>
                            <div class="relative">
                                <input type="file" wire:model="return_evidence" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       id="fileInput">
                                <div class="flex items-center justify-between px-4 py-2 border border-gray-300 rounded-md bg-white">
                                    <span class="text-sm text-gray-500 truncate">
                                        {{ $return_evidence ? $return_evidence->getClientOriginalName() : 'Pilih file...' }}
                                    </span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            @if ($return_evidence)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $return_evidence->temporaryUrl() }}" 
                                         alt="Bukti Pengembalian" 
                                         class="w-32 h-auto mt-1 rounded border border-gray-200">
                                </div>
                            @endif
                            @error('return_evidence') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
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

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal:success', (data) => {
            console.log('Global Event diterima:', data);
            Swal.fire({
                title: data[0].title,
                text: data[0].text,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        });
    });
</script>

<style>
    /* Style tambahan untuk memastikan elemen dapat diklik */
    .fixed.inset-0 {
        z-index: 9999 !important;
    }
    
    .relative.z-\[99999\] {
        z-index: 99999 !important;
    }
    
    /* Style untuk custom file input */
    .relative:hover .border-gray-300 {
        border-color: #6366f1;
    }
    
    .relative:focus-within .border-gray-300 {
        border-color: #6366f1;
        box-shadow: 0 0 0 1px #6366f1;
    }
    
    /* Pastikan modal dan kontennya bisa diklik */
    [x-cloak] { display: none !important; }
</style>
</div>