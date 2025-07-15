<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CheckoutPage extends Component
{
    public $cartItems = [];
public $grandTotal = 0;
public $taxAmount = 0;
public $shippingCost = 0;
public $discountAmount = 0;
public $finalTotal = 0;
public $couponId = null;
public $couponCode = null;
    
    // Shipping address fields
    #[Rule('required|min:2')]
    public $firstName = '';
    
    #[Rule('required|min:2')]
    public $lastName = '';
    
    #[Rule('required|min:10')]
    public $phone = '';
    
    #[Rule('required|min:5')]
    public $address = '';
    
    #[Rule('required')]
    public $city = '';
    
    #[Rule('required')]
    public $state = '';
    
    #[Rule('required')]
    public $zipCode = '';
    
    // Payment method
    #[Rule('required')]
    public $paymentMethod = '';
    
    // Payment gateway specific fields
    public $jazzCashFields = [
        'phone' => '',
        'cnic' => '',
        'otp' => ''
    ];
    
    public $easyPaisaFields = [
        'phone' => '',
        'otp' => ''
    ];
    
    public $bankFields = [
        'accountNumber' => '',
        'accountTitle' => '',
        'bankName' => ''
    ];
    
    // Flag to show payment fields after clicking Place Order
    public $showPaymentFields = false;
    
    // Flag to indicate validation status
    public $addressValidated = false;
    
    public function mount()
    {
        // Check if user is logged in, if not redirect to login page
        if (!auth()->check()) {
            // Store intended URL in session
            session(['intended_url' => route('checkout')]);
            redirect()->route('login');
            return;
        }
        
        $this->loadCartItems();
        
        // Listen for coupon events
        $this->listeners = [
            'coupon-applied' => 'handleCouponApplied',
            'coupon-removed' => 'handleCouponRemoved'
        ];
    }
    
    public function loadCartItems()
    {
        $this->cartItems = CartManagement::getCartItemsFromCookie();
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
        $this->taxAmount = 0; // No tax
        $this->shippingCost = 0; // Free shipping for now
        
        // Apply coupon if exists in session
        if (session()->has('coupon')) {
            $coupon = session('coupon');
            $this->couponId = $coupon['id'];
            $this->couponCode = $coupon['code'];
            $this->discountAmount = $coupon['discount_amount'];
        } else {
            $this->discountAmount = 0;
        }
        
        $this->finalTotal = $this->grandTotal + $this->taxAmount + $this->shippingCost - $this->discountAmount;
    }
    
    public function processPayment()
    {
        // If payment fields are not shown yet, validate address and show payment fields
        if (!$this->showPaymentFields) {
            // Validate only address fields
            $this->validate([
                'firstName' => 'required|min:2',
                'lastName' => 'required|min:2',
                'phone' => 'required|min:10',
                'address' => 'required|min:5',
                'city' => 'required',
                'state' => 'required',
                'zipCode' => 'required',
            ]);
            
            // Set flags to show payment fields
            $this->showPaymentFields = true;
            $this->addressValidated = true;
            return;
        }
        
        // If payment fields are shown, validate payment method and process payment
        $this->validate([
            'paymentMethod' => 'required',
        ]);
        
        // Process payment based on selected method
        $paymentResult = false;
        
        switch($this->paymentMethod) {
            case 'jazzcash':
                // Validate JazzCash fields
                if (empty($this->jazzCashFields['phone']) || empty($this->jazzCashFields['cnic'])) {
                    session()->flash('error', 'Please fill all JazzCash payment fields');
                    return;
                }
                
                // Validate OTP
                if (empty($this->jazzCashFields['otp'])) {
                    session()->flash('error', 'Please request and enter OTP to complete payment');
                    return;
                }
                $result = $this->processJazzCashPayment();
                $paymentResult = $result['success'] ?? false;
                if (!$paymentResult) {
                    session()->flash('error', $result['message'] ?? 'JazzCash payment failed');
                }
                break;
            case 'easypaisa':
                // Validate EasyPaisa fields
                if (empty($this->easyPaisaFields['phone'])) {
                    session()->flash('error', 'Please enter phone number for EasyPaisa payment');
                    return;
                }
                
                // Validate OTP
                if (empty($this->easyPaisaFields['otp'])) {
                    session()->flash('error', 'Please request and enter OTP to complete payment');
                    return;
                }
                $result = $this->processEasyPaisaPayment();
                $paymentResult = $result['success'] ?? false;
                if (!$paymentResult) {
                    session()->flash('error', $result['message'] ?? 'EasyPaisa payment failed');
                }
                break;
            case 'bank':
                // Validate Bank fields
                if (empty($this->bankFields['accountNumber']) || empty($this->bankFields['accountTitle']) || empty($this->bankFields['bankName'])) {
                    session()->flash('error', 'Please fill all Bank Transfer fields');
                    return;
                }
                $result = $this->processBankPayment();
                $paymentResult = $result['success'] ?? false;
                if (!$paymentResult) {
                    session()->flash('error', $result['message'] ?? 'Bank transfer failed');
                }
                break;
            case 'cod':
                $paymentResult = true; // Cash on delivery always succeeds
                break;
            default:
                session()->flash('error', 'Please select a payment method');
                return;
        }
        
        if ($paymentResult) {
            // Store order information in session for success page
            session([
                'order_id' => 'ORD-' . rand(10000, 99999),
                'order_name' => $this->firstName . ' ' . $this->lastName,
                'order_address' => $this->address,
                'order_city' => $this->city,
                'order_state' => $this->state,
                'order_zip' => $this->zipCode,
                'order_phone' => $this->phone,
                'order_subtotal' => number_format($this->grandTotal, 2),
                'order_tax' => number_format($this->taxAmount, 2),
                'order_shipping' => number_format($this->shippingCost, 2),
                'order_discount' => number_format($this->discountAmount, 2),
                'order_coupon' => $this->couponCode,
                'order_total' => number_format($this->finalTotal, 2),
                'payment_method' => $this->getPaymentMethodName(),
                'order_items' => $this->cartItems
            ]);
            
            // Create order in database
            $order = new \App\Models\Order([
                'user_id' => auth()->id(),
                'coupon_id' => $this->couponId,
                'coupon_code' => $this->couponCode,
                'discount_amount' => $this->discountAmount,
                'subtotal' => $this->grandTotal,
                'grand_total' => $this->finalTotal,
                'payment_method' => $this->getPaymentMethodName(),
                'payment_status' => 'pending', // Set as pending initially
                'status' => 'new',
                'currency' => 'PKR',
                'shipping_amount' => $this->shippingCost,
                'shipping_method' => 'Standard Shipping',
                'notes' => ''
            ]);
            $order->save();
            
            // Store order ID in session
            session(['order_id' => $order->id]);
            \Illuminate\Support\Facades\Log::info('Order created and stored in session: ' . $order->id);
            
            // Create order items
            foreach ($this->cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_amount' => $item['unit_amount'],
                    'total_amount' => $item['total_amount']
                ]);
            }
            
            // Create address
            $order->address()->create([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'phone' => $this->phone,
                'street_address' => $this->address, // Using address property for street_address field
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zipCode
            ]);
            
            // Store real order ID in session
            session(['order_id' => $order->id]);
            
            // Clear cart
            CartManagement::clearCartItems();
            
            // Update payment status based on payment method
            if ($this->paymentMethod === 'cod') {
                // For COD, mark as pending payment
                $order->payment_status = 'pending';
                $order->save();
                
                // Redirect to success page
                session()->flash('success', 'Order placed successfully!');
                return redirect()->route('success');
            } else {
                // For other payment methods, redirect to payment completion endpoint
                try {
                    // Call the payment completion endpoint
                    return redirect()->route('payment.complete');
                } catch (\Exception $e) {
                    // If there's an error, log it and show error message
                    \Illuminate\Support\Facades\Log::error('Payment error: ' . $e->getMessage());
                    session()->flash('error', 'Payment processing error. Please try again.');
                    return;
                }
            }
        } else {
            session()->flash('error', 'Payment failed. Please try again.');
        }
    }
    
    private function processJazzCashPayment()
    {
        // Use the PaymentGateway helper to process JazzCash payment
        return \App\Helpers\PaymentGateway::processJazzCashPayment(
            $this->jazzCashFields,
            $this->finalTotal
        );
    }
    
    private function processEasyPaisaPayment()
    {
        // Use the PaymentGateway helper to process EasyPaisa payment
        return \App\Helpers\PaymentGateway::processEasyPaisaPayment(
            $this->easyPaisaFields,
            $this->finalTotal
        );
    }
    
    private function processBankPayment()
    {
        // Use the PaymentGateway helper to process Bank payment
        return \App\Helpers\PaymentGateway::processBankPayment(
            $this->bankFields,
            $this->finalTotal
        );
    }
    
    /**
     * Get the human-readable payment method name
     */
    private function getPaymentMethodName()
    {
        switch($this->paymentMethod) {
            case 'jazzcash':
                return 'JazzCash';
            case 'easypaisa':
                return 'EasyPaisa';
            case 'bank':
                return 'Bank Transfer';
            case 'cod':
                return 'Cash on Delivery';
            default:
                return $this->paymentMethod;
        }
    }
    
    /**
     * Request OTP for mobile payment methods
     * 
     * @param string $method The payment method (jazzcash or easypaisa)
     * @return void
     */
    public function requestOtp($method)
    {
        if ($method === 'jazzcash') {
            if (empty($this->jazzCashFields['phone']) || empty($this->jazzCashFields['cnic'])) {
                session()->flash('error', 'Please enter phone number and CNIC first');
                return;
            }
            
            // In a real implementation, this would make an API call to request OTP
            // For now, we'll just simulate a successful OTP request
            session()->flash('success', 'OTP sent to your phone number. Please enter it to complete payment.');
            
            // Enable the OTP field
            $this->jazzCashFields['otp'] = '';
        } elseif ($method === 'easypaisa') {
            if (empty($this->easyPaisaFields['phone'])) {
                session()->flash('error', 'Please enter phone number first');
                return;
            }
            
            // In a real implementation, this would make an API call to request OTP
            // For now, we'll just simulate a successful OTP request
            session()->flash('success', 'OTP sent to your phone number. Please enter it to complete payment.');
            
            // Enable the OTP field
            $this->easyPaisaFields['otp'] = '';
        }
    }
    
    /**
     * Handle coupon applied event
     */
    public function handleCouponApplied($data)
    {
        $this->discountAmount = $data['discount_amount'];
        $this->finalTotal = $this->grandTotal + $this->taxAmount + $this->shippingCost - $this->discountAmount;
        
        // Get coupon details from session
        if (session()->has('coupon')) {
            $coupon = session('coupon');
            $this->couponId = $coupon['id'];
            $this->couponCode = $coupon['code'];
        }
    }
    
    /**
     * Handle coupon removed event
     */
    public function handleCouponRemoved()
    {
        $this->discountAmount = 0;
        $this->couponId = null;
        $this->couponCode = null;
        $this->finalTotal = $this->grandTotal + $this->taxAmount + $this->shippingCost;
    }
    
    public function render()
    {
        return view('livewire.checkout-page');
    }
}
