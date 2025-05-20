@php use Illuminate\Support\Str; @endphp

<div>
    @if ($showNotification)
        <div
            class="fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg"
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            :class="{
                'bg-green-100 text-green-700': '{{ $notifyType }}' === 'success',
                'bg-red-100 text-red-700': '{{ $notifyType }}' === 'error',
                'bg-yellow-100 text-yellow-700': '{{ $notifyType }}' === 'warning',
            }"
        >
            <strong class="font-bold">{{ Str::upper($notifyType) }}</strong>
            <span class="block sm:inline">{{ $notifyMessage }}</span>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Categories</h2>
        <div class="flex space-x-4">
            <button
                wire:click="openCreateModal"
                class="px-4 py-2 text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Create New
            </button>
            <button
                wire:click="deleteSelected"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                @if(empty($selectedIds)) disabled @endif
            >
                Delete Selected ({{ count($selectedIds ?? []) }})
            </button>
        </div>
    </div>

    <div class="mb-4 w-64">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search categories..."
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
        />
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3">
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
                            <span class="px-2 py-1 rounded text-white text-sm {{ $category->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <button wire:click="openEditModal({{ $category->id }})" class="text-blue-600 hover:text-blue-900">
                                <svg class="h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0l-2.828-2.828a2 2 0 010-2.828l7.93-7.931a2 2 0 012.828 0z" />
                                    <path d="M1.414 10.586l7.93-7.93a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0z" />
                                </svg>
                            </button>
                            <button wire:click="toggleStatus({{ $category->id }})"
                                class="px-3 py-1 bg-blue-500 hover:bg-blue-700 text-white rounded">
                                Toggle
                            </button>
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
                                <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                                <input
                                    type="text"
                                    wire:model="fields.name"
                                    id="name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700 bg-white"
                                    autofocus
                                >
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            wire:click="save"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <span wire:loading wire:target="save">Menyimpan...</span>
                            <span wire:loading.remove wire:target="save">Simpan</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
