<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto" wire:poll.2s>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('cart-updated', () => {
                // Refresh the component when cart is updated
                @this.$refresh();
            });
        });
    </script>
    <div class="container mx-auto px-4">
      <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
      <div class="flex flex-col md:flex-row gap-4">
        <div class="md:w-3/4">
          <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
            @if(count($cartItems) > 0)
            <table class="w-full">
              <thead>
                <tr>
                  <th class="text-left font-semibold">Product</th>
                  <th class="text-left font-semibold">Price</th>
                  <th class="text-left font-semibold">Quantity</th>
                  <th class="text-left font-semibold">Total</th>
                  <th class="text-left font-semibold">Remove</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cartItems as $item)
                <tr>
                  <td class="py-4">
                    <div class="flex items-center">
                      <img class="h-16 w-16 mr-4 object-cover" src="{{ $item['image'] ?? asset('storage/products/default.jpg') }}" alt="Product image">
                      <span class="font-semibold">{{ $item['product_name'] ?? 'Product' }}</span>
                    </div>
                  </td>
                  <td class="py-4">PKR {{ number_format($item['unit_amount'], 2) }}</td>
                  <td class="py-4">
                    <div class="flex items-center">
                      <button wire:click="decrementQuantity('{{ $item['product_id'] }}')" class="border rounded-md py-2 px-4 mr-2">-</button>
                      <span class="text-center w-8">{{ $item['quantity'] }}</span>
                      <button wire:click="incrementQuantity('{{ $item['product_id'] }}')" class="border rounded-md py-2 px-4 ml-2">+</button>
                    </div>
                  </td>
                  <td class="py-4">PKR {{ number_format($item['total_amount'], 2) }}</td>
                  <td><button wire:click="removeItem('{{ $item['product_id'] }}')" class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700">Remove</button></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <div class="text-center py-8">
              <p class="text-gray-500 text-lg">Your cart is empty</p>
              <a href="/products" class="inline-block mt-4 bg-blue-500 text-white py-2 px-4 rounded-lg">Continue Shopping</a>
            </div>
            @endif
          </div>
        </div>
        <div class="md:w-1/4">
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Summary</h2>
            <div class="flex justify-between mb-2">
              <span>Subtotal</span>
              <span>PKR {{ number_format($grandTotal, 2) }}</span>
            </div>
            <!-- Tax removed -->
            <div class="flex justify-between mb-2">
              <span>Shipping</span>
              <span>PKR 0.00</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between mb-2">
              <span class="font-semibold">Total</span>
              <span class="font-semibold">PKR {{ number_format($grandTotal, 2) }}</span>
            </div>
            @if(count($cartItems) > 0)
            <button wire:click="proceedToCheckout" class="block text-center bg-blue-500 text-white py-2 px-4 rounded-lg mt-4 w-full">Checkout</button>
            @else
            <button disabled class="bg-gray-300 text-gray-500 py-2 px-4 rounded-lg mt-4 w-full cursor-not-allowed">Checkout</button>
            @endif
          </div>
        </div>
      </div>
    </div>
</div>
