<flux:button 
    size="xs" 
    wire:click="openCreateModal" 
    class="bg-gradient-to-r from-emerald-400 to-emerald-600 text-white font-semibold px-3 py-1 rounded-md text-xs hover:from-emerald-500 hover:to-emerald-700">
    Create New
</flux:button>

@if(count($selectedIds) > 0)
    <flux:button 
        size="xs" 
        wire:click="confirmDeleteSelected" 
        class="bg-gradient-to-r from-red-500 to-gray-700 text-white font-semibold px-3 py-1 rounded-md text-xs hover:from-red-600 hover:to-gray-800">
        Delete Selected ({{ count($selectedIds) }})
    </flux:button>
@endif
