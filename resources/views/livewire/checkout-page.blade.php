<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
	<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
		Checkout
	</h1>
	
	@if(session()->has('error'))
	<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
		{{ session('error') }}
	</div>
	@endif
	
	<div class="grid grid-cols-12 gap-4">
		<div class="md:col-span-12 lg:col-span-8 col-span-12">
			<!-- Card -->
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<!-- Shipping Address -->
				<div class="mb-6">
					<h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
						Shipping Address
					</h2>
					<div class="grid grid-cols-2 gap-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="first_name">
								First Name
							</label>
							<input wire:model="firstName" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="first_name" type="text">
							@error('firstName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="last_name">
								Last Name
							</label>
							<input wire:model="lastName" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="last_name" type="text">
							@error('lastName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
						</div>
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="phone">
							Phone
						</label>
						<input wire:model="phone" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="phone" type="text">
						@error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="address">
							Address
						</label>
						<input wire:model="address" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="address" type="text">
						@error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="city">
							City
						</label>
						<input wire:model="city" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="city" type="text">
						@error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
					</div>
					<div class="grid grid-cols-2 gap-4 mt-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="state">
								State
							</label>
							<input wire:model="state" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="state" type="text">
							@error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="zip">
								ZIP Code
							</label>
							<input wire:model="zipCode" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="zip" type="text">
							@error('zipCode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
						</div>
					</div>
				</div>
				<!-- Payment Method Selection (Only shown after address validation) -->
				@if($showPaymentFields)
				<div class="mt-6 p-4 border border-gray-200 rounded-lg">
					<div class="text-lg font-semibold mb-4">
						Select Payment Method
					</div>
					<div class="grid w-full gap-6 md:grid-cols-2">
						<div>
							<input wire:model="paymentMethod" class="hidden peer" id="payment-cod" name="paymentMethod" type="radio" value="cod" />
							<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="payment-cod">
								<div class="block">
									<div class="w-full text-lg font-semibold">
										Cash on Delivery
									</div>
								</div>
								<svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
									</path>
								</svg>
							</label>
						</div>
						<div>
							<input wire:model="paymentMethod" class="hidden peer" id="payment-jazzcash" name="paymentMethod" type="radio" value="jazzcash">
							<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="payment-jazzcash">
								<div class="block">
									<div class="w-full text-lg font-semibold">
										JazzCash
									</div>
								</div>
								<svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
									</path>
								</svg>
							</label>
						</div>
						<div>
							<input wire:model="paymentMethod" class="hidden peer" id="payment-easypaisa" name="paymentMethod" type="radio" value="easypaisa">
							<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="payment-easypaisa">
								<div class="block">
									<div class="w-full text-lg font-semibold">
										EasyPaisa
									</div>
								</div>
								<svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
									</path>
								</svg>
							</label>
						</div>
						<div>
							<input wire:model="paymentMethod" class="hidden peer" id="payment-bank" name="paymentMethod" type="radio" value="bank">
							<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="payment-bank">
								<div class="block">
									<div class="w-full text-lg font-semibold">
										Bank Transfer
									</div>
								</div>
								<svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
									</path>
								</svg>
							</label>
						</div>
					</div>
					
					<!-- Payment Method Specific Fields -->
					@if($paymentMethod == 'jazzcash')
					<div class="mt-6 p-4 border border-gray-200 rounded-lg">
						<h3 class="text-lg font-semibold mb-3">JazzCash Payment Details</h3>
						<div class="mb-3">
							<label class="block text-gray-700 dark:text-white mb-1">Phone Number</label>
							<input wire:model="jazzCashFields.phone" class="w-full rounded-lg border py-2 px-3" type="text" value="03166673016">
						</div>
						<div class="mb-3">
							<label class="block text-gray-700 dark:text-white mb-1">CNIC (Last 6 digits)</label>
							<input wire:model="jazzCashFields.cnic" class="w-full rounded-lg border py-2 px-3" type="text">
						</div>
						<div class="mb-3">
							<label class="block text-gray-700 dark:text-white mb-1">OTP</label>
							<input wire:model="jazzCashFields.otp" class="w-full rounded-lg border py-2 px-3" type="text" {{ empty($jazzCashFields['otp']) && $jazzCashFields['otp'] !== '' ? 'disabled' : '' }}>
							<p class="text-sm text-gray-500 mt-1">OTP will be sent to your phone number</p>
						</div>
						<div class="mt-4">
							<button wire:click="requestOtp('jazzcash')" type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Request OTP</button>
						</div>
					</div>
					@elseif($paymentMethod == 'easypaisa')
					<div class="mt-6 p-4 border border-gray-200 rounded-lg">
						<h3 class="text-lg font-semibold mb-3">EasyPaisa Payment Details</h3>
						<div class="mb-3">
							<label class="block text-gray-700 dark:text-white mb-1">Phone Number</label>
							<input wire:model="easyPaisaFields.phone" class="w-full rounded-lg border py-2 px-3" type="text" value="03166673016">
						</div>
						<div class="mb-3">
							<label class="block text-gray-700 dark:text-white mb-1">OTP</label>
							<input wire:model="easyPaisaFields.otp" class="w-full rounded-lg border py-2 px-3" type="text" {{ empty($easyPaisaFields['otp']) && $easyPaisaFields['otp'] !== '' ? 'disabled' : '' }}>
							<p class="text-sm text-gray-500 mt-1">OTP will be sent to your phone number</p>
						</div>
						<div class="mt-4">
							<button wire:click="requestOtp('easypaisa')" type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Request OTP</button>
						</div>
					</div>
					@elseif($paymentMethod == 'bank')
					<div class="mt-6 p-4 border border-gray-200 rounded-lg">
						<h3 class="text-lg font-semibold mb-3">Bank Transfer Details</h3>
						<div class="mb-3">
							<label class="block text-gray-700 dark:text-white mb-1">Bank Name</label>
							<input wire:model="bankFields.bankName" class="w-full rounded-lg border py-2 px-3" type="text">
						</div>
						<div class="mb-3">
							<label class="block text-gray-700 dark:text-white mb-1">Account Title</label>
							<input wire:model="bankFields.accountTitle" class="w-full rounded-lg border py-2 px-3" type="text">
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1">Account Number</label>
							<input wire:model="bankFields.accountNumber" class="w-full rounded-lg border py-2 px-3" type="text">
						</div>
					</div>
					@endif
				</div>
				@endif
				</div>
			</div>
			<!-- End Card -->
		</div>
		<div class="md:col-span-12 lg:col-span-4 col-span-12">
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
					ORDER SUMMARY
				</div>
				<div class="flex justify-between mb-2 font-bold">
					<span>
						Subtotal
					</span>
					<span>
						PKR {{ number_format($grandTotal, 2) }}
					</span>
				</div>
				<div class="flex justify-between mb-2 font-bold">
					<span>
						Taxes
					</span>
					<span>
						PKR {{ number_format($taxAmount, 2) }}
					</span>
				</div>
				<div class="flex justify-between mb-2 font-bold">
					<span>
						Shipping Cost
					</span>
					<span>
						PKR {{ number_format($shippingCost, 2) }}
					</span>
				</div>
				
				@if($discountAmount > 0)
				<div class="flex justify-between mb-2 font-bold text-green-600">
					<span>
						Discount ({{ $couponCode }})
					</span>
					<span>
						- PKR {{ number_format($discountAmount, 2) }}
					</span>
				</div>
				@endif
				
				<hr class="bg-slate-400 my-4 h-1 rounded">
				<div class="flex justify-between mb-2 font-bold">
					<span>
						Grand Total
					</span>
					<span>
						PKR {{ number_format($finalTotal, 2) }}
					</span>
				</div>
				
				<!-- Coupon Component -->
				<div class="mt-4">
					<livewire:apply-coupon />
				</div>
			</div>
			<button wire:click="processPayment" class="bg-green-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-green-600">
				@if($showPaymentFields)
				Complete Payment
				@else
				Continue to Payment
				@endif
			</button>
			<div class="bg-white mt-4 rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
					BASKET SUMMARY
				</div>
				@if(count($cartItems) > 0)
				<ul class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
					@foreach($cartItems as $item)
					<li class="py-3 sm:py-4">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<img alt="{{ $item['product_name'] ?? 'Product' }}" class="w-12 h-12 rounded-full" src="{{ $item['image'] ?? asset('storage/products/default.jpg') }}">
							</div>
							<div class="flex-1 min-w-0 ms-4">
								<p class="text-sm font-medium text-gray-900 truncate dark:text-white">
									{{ $item['product_name'] ?? 'Product' }}
								</p>
								<p class="text-sm text-gray-500 truncate dark:text-gray-400">
									Quantity: {{ $item['quantity'] }}
								</p>
							</div>
							<div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
								PKR {{ number_format($item['total_amount'], 2) }}
							</div>
						</div>
					</li>
					@endforeach
				</ul>
				@else
				<div class="text-center py-4">
					<p class="text-gray-500">Your cart is empty</p>
					<a href="/" class="text-blue-500 hover:underline mt-2 inline-block">Continue Shopping</a>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>