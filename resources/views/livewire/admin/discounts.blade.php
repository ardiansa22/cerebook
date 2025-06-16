@php use Illuminate\Support\Str; @endphp

<div x-data="{ showBookDropdown: @entangle('showBookDropdown') }"> {{-- Tambahkan x-data untuk Alpine.js --}}
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">discounts</h2>
        <div class="flex space-x-4">
            @include('layouts.component.createdel ')
            <flux:button variant="danger" size="xs" wire:click="toggleStatus">Aktif/nonaktif ({{ count($selectedIds) }})</flux:button>
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
                                <flux:button class="text-blue-600 hover:text-blue-900" wire:click="edit({{ $item->id }})" size="xs">
                                    Edit
                                </flux:button>
                                <flux:button class="text-red-600 hover:text-red-900" wire:click="confirmDelete({{ $item->id }})" size="xs">
                                    Delete
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
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ $modalTitle }}
                        </h3>

                        <div class="space-y-4">
                            {{-- Autocomplete Input for Book --}}
                            <div class="relative" @click.away="showBookDropdown = false"> {{-- Tambahkan @click.away --}}
                                <flux:input
                                    type="text"
                                    label="Nama Buku"
                                    wire:model.debounce.300ms="selectedBookName"
                                    id="book_name_input"
                                    placeholder="Cari buku..."
                                    wire:keydown.escape="showBookDropdown = false"
                                    wire:keydown.tab="showBookDropdown = false"
                                    @focus="showBookDropdown = (@json(count($searchResults)) > 0)" {{-- Tampilkan dropdown jika ada hasil --}}
                                />
                                {{-- Hidden input to store book_id --}}
                                <input type="hidden" wire:model="fields.book_id">
                                @error('fields.book_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                @if(count($searchResults) > 0)
                                    <ul x-show="showBookDropdown"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto">
                                        @foreach($searchResults as $book)
                                            <li
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                                wire:click="selectBook({{ $book['id'] }}, '{{ $book['name'] }}')"
                                                wire:key="book-{{ $book['id'] }}"
                                            >
                                                {{ $book['name'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
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
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="fields.is_active" id="is_active" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Aktif
                                </label>
                                @error('fields.is_active') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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