@php use Illuminate\Support\Str; @endphp

<div>
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Categories</h2>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                class="px-2 py-1 rounded text-white text-sm {{ $category->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </flux:badge>

                        </td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <flux:field variant="inline">
                                <flux:switch wire:click="toggleStatus({{ $category->id }})" />
                                <flux:error name="notifications" />
                            </flux:field>
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
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
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
