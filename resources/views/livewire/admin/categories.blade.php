@php use Illuminate\Support\Str; @endphp

<div>
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete')
   <div class="mb-6">
    <!-- Judul halaman dan aksi -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Categories</h2>

            <!-- Breadcrumb dengan jarak -->
            <div class="mt-2">
                @include('layouts.component.breadcrumb', [
                    'breadcrumbs' => [
                        ['label' => 'Dashboard', 'url' => route('dashboard')],
                        ['label' => 'Categori']
                    ]
                ])
            </div>
        </div>

        <!-- Tombol aksi -->
        <div class="flex space-x-2">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $index => $category)
                    <tr>
                        <td class="px-6 py-4">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $category->id }}">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $categories->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4">
                            <flux:badge
                                class="px-2 py-1 rounded text-white text-sm {{ $category->is_active ? 'bg-gradient-to-r from-green-200 to-green-400' : 'bg-gradient-to-r from-red-400 to-red-600' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4">
                            <flux:button 
                                class="bg-gradient-to-r from-amber-400 to-yellow-600 text-white px-3 py-1 rounded-md text-xs hover:from-amber-500 hover:to-yellow-500" 
                                wire:click="openEditModal({{ $category->id }})" 
                                size="xs">
                                Edit
                            </flux:button>


                        </td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-4">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $categories->links() }}
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
                                <flux:input type="email" label="Category" wire:model="fields.name"
                                    id="name"/>
                                    @error('fields.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
