<flux:button 
    size="xs" 
    wire:click="openCreateModal" 
    class="bg-gradient-to-r from-[#00B4D8] to-[#0077B6] text-white font-semibold px-3 py-1 rounded-md text-xs hover:from-[#0096C7] hover:to-[#023E8A]">
    Create New
</flux:button>

@if(count($selectedIds) > 0)
    <flux:button 
        size="xs" 
        wire:click="confirmDeleteSelected" 
        class="bg-gradient-to-r from-[#EF476F] to-[#2C2C54] text-white font-semibold px-3 py-1 rounded-md text-xs hover:from-[#D62839] hover:to-[#1B1B2F]">
        Delete Selected ({{ count($selectedIds) }})
    </flux:button>
@endif

