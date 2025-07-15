<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-4xl font-bold text-slate-500">My Orders</h1>
  
  <!-- Coupon Usage Stats Component -->
  <livewire:coupon-usage-stats />
  <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="overflow-hidden">
          @if(count($orders) > 0)
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
              <tr>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Date</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order Status</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Payment Status</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Coupon</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order Amount</th>
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
              <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $order->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $order->created_at->format('d-m-Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                  <span class="bg-{{ $order->status == 'completed' ? 'green' : ($order->status == 'processing' ? 'blue' : 'orange') }}-500 py-1 px-3 rounded text-white shadow">
                    {{ ucfirst($order->status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                  <span class="bg-{{ $order->payment_status == 'paid' ? 'green' : 'orange' }}-500 py-1 px-3 rounded text-white shadow">
                    {{ ucfirst($order->payment_status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                  @if($order->coupon_code)
                    <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-medium">
                      {{ $order->coupon_code }}
                      @if($order->discount_amount > 0)
                        ({{ number_format($order->discount_amount, 2) }})
                      @endif
                    </span>
                  @else
                    <span class="text-gray-400">-</span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ number_format($order->grand_total, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                  <a href="{{ route('my-order-details', $order->id) }}" class="bg-slate-600 text-white py-2 px-4 rounded-md hover:bg-slate-500">View Details</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @else
          <div class="text-center py-8">
            <p class="text-gray-500 text-lg">You don't have any orders yet</p>
            <a href="/products" class="inline-block mt-4 bg-blue-500 text-white py-2 px-4 rounded-lg">Start Shopping</a>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>