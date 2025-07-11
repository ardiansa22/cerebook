<div>
<div>
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete ')
<div class="mb-6">
    <!-- Judul halaman dan aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Pengembalian Buku</h2>

                <!-- Breadcrumb dengan jarak -->
                <div class="mt-2">
                    @include('layouts.component.breadcrumb', [
                        'breadcrumbs' => [
                            ['label' => 'Dashboard', 'url' => route('dashboard')],
                            ['label' => 'Data Pengembalian Buku']
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengembalian</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktual Tanggal Pengembalian</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($returnedRentals  as $index => $return)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $returnedRentals ->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $return->user->name}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          
                                    @foreach ($return->items as $detail)
                                        {{ $detail->book->title }}
                                    @endforeach
                      
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $return->return_date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $return->actual_return_date->format('d-m-Y') }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @switch($return->status)
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

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button class="text-blue-600 hover:text-blue-900" wire:click="openDetailModal({{ $return->id }})" size="xs">
                                Lihat Detail
                            </flux:button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $returnedRentals ->links() }}
    </div>
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
                <div class="border-b px-6 py-4">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $modalTitle }}</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 py-4">
                    <div>
                        <flux:input type="text" label="Nama Peminjam" :disabled="true"
                            value="{{ $selectedRental?->user->name }}" />
                    </div>

                    <div>
                        <flux:input type="text" label="Buku yang Dipinjam" :disabled="true"
                            value="{{ $selectedRental?->items->pluck('book.title')->join(', ') }}" />
                    </div>

                    <div>
                        <flux:input type="text" label="Tanggal Pengembalian" :disabled="true"
                            value="{{ optional($selectedRental?->return_date)->format('d-m-Y') }}" />
                    </div>

                    <div>
                        <flux:input type="text" label="Tanggal Dikembalikan" :disabled="true"
                            value="{{ optional($selectedRental?->actual_return_date)->format('d-m-Y') }}" />
                    </div>

                    <div>
                        <flux:input type="text" label="Status" :disabled="true"
                            value="{{ ucfirst($selectedRental?->status) }}" />
                    </div>

                    <div>
                        <flux:input type="text" label="Terlambat (hari)" :disabled="true"
                            value="{{ $selectedRental && $selectedRental->actual_return_date > $selectedRental->return_date ? $selectedRental->actual_return_date->diffInDays($selectedRental->return_date) : 0 }}" />
                    </div>

                    <div>
                        <flux:input type="text" label="Denda" :disabled="true"
                            value="Rp {{ number_format($selectedRental?->fine->fine_amount ?? 0, 0, ',', '.') }}" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pengembalian</label>
                        @if ($selectedRental?->return_evidence)
                            <a href="{{ asset('storage/' . $selectedRental->return_evidence) }}" target="_blank">
                                <img src="{{ asset('storage/' . $selectedRental->return_evidence) }}"
                                    alt="Bukti Pengembalian"
                                    class="w-full max-w-xs h-auto object-cover rounded border">
                            </a>
                        @else
                            <p class="text-gray-400 italic">Tidak ada bukti</p>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 text-right">
                    <flux:button wire:click="$set('showModal', false)" variant="filled" class="text-blue-600 hover:text-blue-900">
                        Tutup
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
@endif

</div>
</div>
