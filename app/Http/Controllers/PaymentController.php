<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle JazzCash payment callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function jazzCashCallback(Request $request)
    {
        // Log the callback data
        Log::info('JazzCash callback received', $request->all());
        
        // In a real implementation, you would verify the payment status
        // with JazzCash API using the transaction ID and other parameters
        
        // For now, we'll just check if the request has a success parameter
        if ($request->has('pp_ResponseCode') && $request->pp_ResponseCode == '000') {
            // Payment was successful
            // Create order if not already created
            $orderId = $request->get('pp_BillReference', null);
            if ($orderId) {
                $order = \App\Models\Order::find($orderId);
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->save();
                }
            }
            
            session()->flash('success', 'Payment completed successfully via JazzCash!');
            return redirect()->route('success');
        } else {
            // Payment failed
            session()->flash('error', 'Payment failed. Please try again.');
            return redirect()->route('checkout');
        }
    }
    
    /**
     * Handle EasyPaisa payment callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function easyPaisaCallback(Request $request)
    {
        // Log the callback data
        Log::info('EasyPaisa callback received', $request->all());
        
        // In a real implementation, you would verify the payment status
        // with EasyPaisa API using the transaction ID and other parameters
        
        // For now, we'll just check if the request has a success parameter
        if ($request->has('status') && $request->status == 'PAID') {
            // Payment was successful
            // Create order if not already created
            $orderId = $request->get('order_id', null);
            if ($orderId) {
                $order = \App\Models\Order::find($orderId);
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->save();
                }
            }
            
            session()->flash('success', 'Payment completed successfully via EasyPaisa!');
            return redirect()->route('success');
        } else {
            // Payment failed
            session()->flash('error', 'Payment failed. Please try again.');
            return redirect()->route('checkout');
        }
    }
    
    /**
     * Handle Bank Transfer payment callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bankCallback(Request $request)
    {
        // Log the callback data
        Log::info('Bank Transfer callback received', $request->all());
        
        // In a real implementation, you would verify the payment status
        // with Bank API using the transaction ID and other parameters
        
        // For now, we'll just check if the request has a success parameter
        if ($request->has('status') && $request->status == 'success') {
            // Payment was successful
            // Create order if not already created
            $orderId = $request->get('order_id', null);
            if ($orderId) {
                $order = \App\Models\Order::find($orderId);
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->save();
                }
            }
            
            session()->flash('success', 'Payment completed successfully via Bank Transfer!');
            return redirect()->route('success');
        } else {
            // Payment failed
            session()->flash('error', 'Payment failed. Please try again.');
            return redirect()->route('checkout');
        }
    }
    
    /**
     * Handle payment completion for any payment method
     * This is a fallback method to handle payment completion
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completePayment(Request $request)
    {
        try {
            // Get order ID from session or request
            $orderId = $request->input('order_id', session('order_id'));
            if (!$orderId) {
                throw new \Exception('Order ID not found in request or session');
            }
            
            // Log the order ID for debugging
            \Illuminate\Support\Facades\Log::info('Processing payment completion for order: ' . $orderId);
            
            // Find the order
            $order = \App\Models\Order::find($orderId);
            if (!$order) {
                throw new \Exception('Order not found with ID: ' . $orderId);
            }
            
            // Update order status
            $order->payment_status = 'paid';
            $order->save();
            
            // Log successful payment update
            \Illuminate\Support\Facades\Log::info('Payment status updated to paid for order: ' . $orderId);
            
            // Redirect to success page
            session()->flash('success', 'Payment completed successfully!');
            return redirect()->route('success');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Payment completion error: ' . $e->getMessage());
            
            // Redirect to checkout with error
            session()->flash('error', 'Payment processing error: ' . $e->getMessage());
            return redirect()->route('checkout');
        }
    }
}