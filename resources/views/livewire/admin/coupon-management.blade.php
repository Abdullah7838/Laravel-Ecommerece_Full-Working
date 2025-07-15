<div class="container mx-auto py-6 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Coupon Management</h1>
        <button 
            wire:click="openModal"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            Create New Coupon
        </button>
    </div>

    <!-- Coupon Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-gray-500 text-sm font-medium">Total Coupons</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $coupons->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-gray-500 text-sm font-medium">Active Coupons</h3>
            <p class="text-2xl font-bold text-green-600">{{ $coupons->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-gray-500 text-sm font-medium">Expired Coupons</h3>
            <p class="text-2xl font-bold text-red-600">{{ $coupons->filter(function($coupon) { return $coupon->expires_at && $coupon->expires_at->isPast(); })->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-gray-500 text-sm font-medium">Most Used Coupon</h3>
            @if($coupons->isNotEmpty())
                @php
                    $mostUsed = $coupons->sortByDesc('used_count')->first();
                @endphp
                <p class="text-xl font-bold text-gray-800">{{ $mostUsed->code }} ({{ $mostUsed->used_count }})</p>
            @else
                <p class="text-xl font-bold text-gray-800">-</p>
            @endif
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Order</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $coupon->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ ucfirst($coupon->type) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $coupon->type === 'percentage' ? $coupon->value . '%' : 'PKR ' . number_format($coupon->value, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $coupon->min_order_amount ? 'PKR ' . number_format($coupon->min_order_amount, 2) : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $coupon->used_count }} {{ $coupon->max_uses ? '/ ' . $coupon->max_uses : '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($coupon->starts_at && $coupon->expires_at)
                                    {{ $coupon->starts_at->format('M d, Y') }} - {{ $coupon->expires_at->format('M d, Y') }}
                                @elseif($coupon->expires_at)
                                    Until {{ $coupon->expires_at->format('M d, Y') }}
                                @elseif($coupon->starts_at)
                                    From {{ $coupon->starts_at->format('M d, Y') }}
                                @else
                                    No limit
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $coupon->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <button wire:click="toggleStatus({{ $coupon->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                            <button wire:click="delete({{ $coupon->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No coupons found. Create your first coupon!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Coupon Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <div class="px-6 py-4 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">{{ $editMode ? 'Edit Coupon' : 'Create New Coupon' }}</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <form wire:submit.prevent="save">
                    <div class="px-6 py-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Coupon Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Coupon Code</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input 
                                        type="text" 
                                        id="code" 
                                        wire:model="code" 
                                        class="flex-1 focus:ring-blue-500 focus:border-blue-500 block w-full min-w-0 rounded-md sm:text-sm border-gray-300" 
                                        placeholder="SUMMER2023"
                                    >
                                    <button 
                                        type="button" 
                                        wire:click="generateRandomCode" 
                                        class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        Generate
                                    </button>
                                </div>
                                @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Coupon Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Coupon Type</label>
                                <select 
                                    id="type" 
                                    wire:model="type" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="percentage">Percentage Discount</option>
                                    <option value="fixed">Fixed Amount Discount</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Value -->
                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700">
                                    {{ $type === 'percentage' ? 'Percentage Value' : 'Fixed Amount (PKR)' }}
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    @if($type === 'percentage')
                                        <input 
                                            type="number" 
                                            id="value" 
                                            wire:model="value" 
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md" 
                                            placeholder="10"
                                            min="0"
                                            max="100"
                                        >
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    @else
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">PKR</span>
                                        </div>
                                        <input 
                                            type="number" 
                                            id="value" 
                                            wire:model="value" 
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 sm:text-sm border-gray-300 rounded-md" 
                                            placeholder="500"
                                            min="0"
                                        >
                                    @endif
                                </div>
                                @error('value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Min Order Amount -->
                            <div>
                                <label for="min_order_amount" class="block text-sm font-medium text-gray-700">Minimum Order Amount (PKR)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">PKR</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        id="min_order_amount" 
                                        wire:model="min_order_amount" 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 sm:text-sm border-gray-300 rounded-md" 
                                        placeholder="1000"
                                        min="0"
                                    >
                                </div>
                                @error('min_order_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Max Uses -->
                            <div>
                                <label for="max_uses" class="block text-sm font-medium text-gray-700">Maximum Uses (blank for unlimited)</label>
                                <input 
                                    type="number" 
                                    id="max_uses" 
                                    wire:model="max_uses" 
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                    placeholder="100"
                                    min="0"
                                >
                                @error('max_uses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Active Status -->
                            <div class="flex items-center h-full pt-5">
                                <input 
                                    type="checkbox" 
                                    id="is_active" 
                                    wire:model="is_active" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Start Date -->
                            <div>
                                <label for="starts_at" class="block text-sm font-medium text-gray-700">Start Date (optional)</label>
                                <input 
                                    type="date" 
                                    id="starts_at" 
                                    wire:model="starts_at" 
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                >
                                @error('starts_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Expiry Date -->
                            <div>
                                <label for="expires_at" class="block text-sm font-medium text-gray-700">Expiry Date (optional)</label>
                                <input 
                                    type="date" 
                                    id="expires_at" 
                                    wire:model="expires_at" 
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                >
                                @error('expires_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 text-right">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            {{ $editMode ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>