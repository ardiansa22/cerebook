@php use Illuminate\Support\Str; @endphp

<div x-data="{ showBookDropdown: @entangle('showBookDropdown') }"> {{-- Tambahkan x-data untuk Alpine.js --}}
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete')
<div class="mb-6">
    <!-- Judul halaman dan aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Discount</h2>

                <!-- Breadcrumb dengan jarak -->
                <div class="mt-2">
                    @include('layouts.component.breadcrumb', [
                        'breadcrumbs' => [
                            ['label' => 'Dashboard', 'url' => route('dashboard')],
                            ['label' => 'Discount']
                        ]
                    ])
                </div>
            </div>

            <!-- Tombol aksi -->
            <div class="flex space-x-4">
                @include('layouts.component.createdel')
                <flux:button variant="danger" size="xs" wire:click="toggleStatus">Aktif/nonaktif ({{ count($selectedIds) }})</flux:button>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book rent Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">After Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th> {{-- Tambahkan kolom Actions --}}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($discounts as $index => $item)
                    <tr>
                        <td class="px-6 py-4">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $item->id }}">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $discounts->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $item->book->name }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            Rp {{ number_format($item->book->rent_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $item->percentage }}%
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            Rp {{ number_format($item->book->rent_price * (1 - $item->percentage / 100), 0, ',', '.') }} {{-- Hitung harga setelah diskon --}}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                             {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }} {{-- Format tanggal --}}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                             {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }} {{-- Format tanggal --}}
                        </td>
                        <td class="px-6 py-4">
                            <flux:badge
                                class="px-2 py-1 rounded text-white text-sm {{ $item->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="flex items-center space-x-2">
                                <flux:button class="text-blue-600 hover:text-blue-900" wire:click="openEditModal({{ $item->id }})" size="xs">
                                    Edit
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-gray-500 py-4">No discounts found.</td> {{-- Sesuaikan colspan --}}
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $discounts->links() }}
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ $modalTitle }}
                        </h3>

                        <div class="space-y-4">
                             <div>
                                <label for="category_id" class=" text-sm font-medium text-gray-700">Category</label>
                                <select 
                                    wire:model="fields.book_id" 
                                    id="book_id"
                                    class="mt-1 w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700 bg-white"
                                >
                                    <option value="">Select Book</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                                
                                @error('fields.book_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Other fields for discount --}}
                            <div>
                                <flux:input type="number" label="Diskon (%)" wire:model="fields.percentage" id="percentage"/>
                                @error('fields.percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <flux:input type="date" label="Tanggal Mulai" wire:model="fields.start_date" id="start_date"/>
                                @error('fields.start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <flux:input type="date" label="Tanggal Selesai" wire:model="fields.end_date" id="end_date"/>
                                @error('fields.end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <div class="flex space-x-4 sm:space-x-reverse">
                            <flux:button wire:click="$set('showModal', false)" variant="filled">
                                Cancel
                            </flux:button>
                            <flux:button wire:click.prevent="save" variant="primary">
                                Save
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>