<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto" wire:poll.10s>
    <h1 class="text-4xl font-bold text-slate-500">Order Details</h1>
  
    <!-- Grid -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-5">
      <!-- Card -->
      <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
            <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
  
          <div class="grow">
            <div class="flex items-center gap-x-2">
              <p class="text-xs uppercase tracking-wide text-gray-500">
                Customer
              </p>
            </div>
            <div class="mt-1 flex items-center gap-x-2">
              <div>{{ $order->user->name }}</div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
  
      <!-- Card -->
      <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
            <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 22h14" />
              <path d="M5 2h14" />
              <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" />
              <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" />
            </svg>
          </div>
  
          <div class="grow">
            <div class="flex items-center gap-x-2">
              <p class="text-xs uppercase tracking-wide text-gray-500">
                Order Date
              </p>
            </div>
            <div class="mt-1 flex items-center gap-x-2">
              <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200">
                {{ $order->created_at->format('d-m-Y') }}
              </h3>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
  
      <!-- Card -->
      <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
            <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
              <path d="m12 12 4 10 1.7-4.3L22 16Z" />
            </svg>
          </div>
  
          <div class="grow">
            <div class="flex items-center gap-x-2">
              <p class="text-xs uppercase tracking-wide text-gray-500">
                Order Status
              </p>
            </div>
            <div class="mt-1 flex items-center gap-x-2">
              <span class="bg-{{ $order->status == 'completed' ? 'green' : ($order->status == 'processing' ? 'blue' : 'orange') }}-500 py-1 px-3 rounded text-white shadow">
                {{ ucfirst($order->status) }}
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
  
      <!-- Card -->
      <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-slate-900 dark:border-gray-800">
        <div class="p-4 md:p-5 flex gap-x-4">
          <div class="flex-shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-gray-800">
            <svg class="flex-shrink-0 size-5 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12s2.545-5 7-5c4.454 0 7 5 7 5s-2.546 5-7 5c-4.455 0-7-5-7-5z" />
              <path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
              <path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2" />
              <path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2" />
            </svg>
          </div>
  
          <div class="grow">
            <div class="flex items-center gap-x-2">
              <p class="text-xs uppercase tracking-wide text-gray-500">
                Payment Status
              </p>
            </div>
            <div class="mt-1 flex items-center gap-x-2">
              <span class="bg-{{ $order->payment_status == 'paid' ? 'green' : 'orange' }}-500 py-1 px-3 rounded text-white shadow">
                {{ ucfirst($order->payment_status) }}
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- End Card -->
    </div>
    <!-- End Grid -->
  
    <div class="flex flex-col md:flex-row gap-4 mt-4">
      <div class="md:w-3/4">
        <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
          <table class="w-full">
            <thead>
              <tr>
                <th class="text-left font-semibold">Product</th>
                <th class="text-left font-semibold">Price</th>
                <th class="text-left font-semibold">Quantity</th>
                <th class="text-left font-semibold">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orderItems as $item)
              <tr wire:key="{{ $item->id }}">
                <td class="py-4">
                  <div class="flex items-center">
                    <img class="h-16 w-16 mr-4" src="{{ !empty($item->product->imageUrls) ? $item->product->imageUrls[0] : asset('storage/products/default.jpg') }}" alt="Product image">
                    <span class="font-semibold">{{ $item->product->name }}</span>
                  </div>
                </td>
                <td class="py-4">{{ $order->currency }} {{ number_format($item->unit_price, 2) }}</td>
                <td class="py-4">
                  <span class="text-center w-8">{{ $item->quantity }}</span>
                </td>
                <td class="py-4">{{ $order->currency }} {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
  
        @if($address)
        <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
          <h1 class="font-3xl font-bold text-slate-500 mb-3">Shipping Address</h1>
          <div class="flex justify-between items-center">
            <div>
              <p>{{ $address->street_address }}, {{ $address->city }}, {{ $address->state }}, {{ $address->zip_code }}</p>
            </div>
            <div>
              <p class="font-semibold">Phone:</p>
              <p>{{ $address->phone }}</p>
            </div>
          </div>
        </div>
        @endif
  
      </div>
      <div class="md:w-1/4">
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-lg font-semibold mb-4">Summary</h2>
          <div class="flex justify-between mb-2">
            <span>Subtotal</span>
            <span>{{ $order->currency }} {{ number_format($order->subtotal ?? ($order->grand_total - ($order->shipping_amount ?? 0)), 2) }}</span>
          </div>
          <!-- Tax removed -->
          <div class="flex justify-between mb-2">
            <span>Shipping</span>
            <span>{{ $order->currency }} {{ number_format($order->shipping_amount ?? 0, 2) }}</span>
          </div>
          
          @if($order->discount_amount > 0)
          <div class="flex justify-between mb-2 text-green-600">
            <span>Discount
              @if($order->coupon_code)
                ({{ $order->coupon_code }})
              @endif
            </span>
            <span>- {{ $order->currency }} {{ number_format($order->discount_amount, 2) }}</span>
          </div>
          @endif
          
          <hr class="my-2">
          <div class="flex justify-between mb-2">
            <span class="font-semibold">Grand Total</span>
            <span class="font-semibold">{{ $order->currency }} {{ number_format($order->grand_total, 2) }}</span>
          </div>
  
        </div>
      </div>
    </div>
  </div>