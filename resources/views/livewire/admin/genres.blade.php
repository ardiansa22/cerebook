<div>
<div>
@if ($showNotification)
    <div
        id="notification"
        class="fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg
            @if($notifyType === 'success') bg-green-100 text-green-700
            @elseif($notifyType === 'error') bg-red-100 text-red-700
            @elseif($notifyType === 'warning') bg-yellow-100 text-yellow-700
            @endif"
    >
        <strong class="font-bold">{{ Str::upper($notifyType) }}</strong>
        <span class="block sm:inline">{{ $notifyMessage }}</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notification = document.getElementById('notification');

            if (notification) {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => notification.remove(), 500);
                }, 3000);
            }
        });
    </script>
@endif
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Genres</h2>
        <div class="flex space-x-4">
           <flux:button variant="primary"  wire:click="openCreateModal">Create New</flux:button>
            <flux:button variant="danger" wire:click="deleteSelected">Delete Selected ({{ count($selectedIds) }})</flux:button>
        </div>
    </div>

    <div class="mb-4 w-64">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search Genres..."
            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
        />
    </div>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">genre Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($genres as $index => $genre)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $genre->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $genres->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $genre->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button
                            wire:click="openEditModal({{ $genre->id }})"
                            class="text-blue-600 hover:text-blue-900 mr-3"
                        >
                            <svg class="h-5 w-5 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M13.586 3.586a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0l-2.828-2.828a2 2 0 010-2.828l7.93-7.931a2 2 0 012.828 0zM1.414 10.586l7.93-7.93a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0z" />
                            </svg>
                        </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $genres->links() }}
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
                                <label for="name" class="block text-sm font-medium text-gray-700">genre Name</label>
                                <input
                                    type="text"
                                    wire:model="fields.name"
                                    id="name"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700 bg-white" <!-- Tambahkan bg-white dan text-gray-700 -->
                                
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
</div>
