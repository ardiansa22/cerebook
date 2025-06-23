<div>
    @php use Illuminate\Support\Str; @endphp

<div x-data="{ showBookDropdown: @entangle('showBookDropdown') }">
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
                <flux:button 
                    size="xs" 
                    wire:click="toggleStatus" 
                    class="bg-gradient-to-r from-blue-500 via-green-300 to-green-200 text-white hover:from-blue-600 hover:to-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Aktif/nonaktif ({{ count($selectedIds) }})
                </flux:button>
            </div>
        </div>
    </div>

    <div class="mb-4">
        @include('layouts.component.searchtable')
        <label class="text-sm text-gray-700">
            Show
            <select wire:model.live="perPage" class="ml-2 border rounded p-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book rent Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">After Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($discounts as $index => $discount)
                    <tr>
                        <td class="px-6 py-4">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $discount->id }}" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $discounts->firstitem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $discount->book->name }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            Rp {{ number_format($discount->book->rent_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $discount->percentage }}%
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            Rp {{ number_format($discount->book->rent_price * (1 - $discount->percentage / 100), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($discount->start_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($discount->end_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <flux:badge
                                class="px-2 py-1 rounded text-white text-sm {{ $discount->is_active ? 'bg-gradient-to-r from-green-200 to-green-400' : 'bg-gradient-to-r from-red-400 to-red-600' }}">
                                {{ $discount->is_active ? 'Aktif' : 'Nonaktif' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="flex items-center space-x-2">
                                <flux:button 
                                    class="bg-gradient-to-r from-amber-400 to-yellow-600 text-white px-3 py-1 rounded-md text-xs hover:from-amber-500 hover:to-yellow-500" 
                                    wire:click="openEditModal({{ $discount->id }})" 
                                    size="xs">
                                    Edit
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-gray-500 py-4">No discounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $discounts->links() }}
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
                            <!-- Input tanggal -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" 
                                       wire:model="fields.start_date" 
                                       id="start_date" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                @error('fields.start_date') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" 
                                       wire:model="fields.end_date" 
                                       id="end_date" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                @error('fields.end_date') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                            <div>
                                <label for="book_id" class="block text-sm font-medium text-gray-700">Pilih Buku</label>
                                <select 
                                    wire:model="fields.book_id" 
                                    id="book_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required
                                >
                                    <option value="">-- Pilih Buku --</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                                @error('fields.book_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Input diskon -->
                            <div>
                                <label for="percentage" class="block text-sm font-medium text-gray-700">Diskon (%)</label>
                                <input 
                                    type="number" 
                                    wire:model="fields.percentage" 
                                    id="percentage"
                                    min="1"
                                    max="100"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required
                                >
                                @error('fields.percentage') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <div class="flex space-x-4 sm:space-x-reverse">
                            <button 
                                wire:click="$set('showModal', false)" 
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button 
                                wire:click.prevent="save" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    /* Style untuk memastikan elemen input bisa diklik */
    .fixed.inset-0 {
        z-index: 9999 !important;
    }
    
    .relative.z-\[99999\] {
        z-index: 99999 !important;
        pointer-events: auto !important;
    }
    
    /* Style untuk input */
    input, select {
        pointer-events: auto !important;
    }
    
    /* Style untuk input date */
    input[type="date"] {
        appearance: none;
        -webkit-appearance: none;
        background-color: white;
    }
    
    /* Style untuk fokus input */
    input:focus, select:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    
    /* Style untuk tombol dalam modal */
    button:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
</style>
</div>