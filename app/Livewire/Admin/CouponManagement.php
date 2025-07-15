<?php

namespace App\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;
use Illuminate\Support\Str;

class CouponManagement extends Component
{
    public $coupons;
    public $showModal = false;
    public $editMode = false;
    public $couponId;
    
    // Form fields
    public $code;
    public $type = 'percentage';
    public $value;
    public $min_order_amount;
    public $max_uses;
    public $starts_at;
    public $expires_at;
    public $is_active = true;
    
    protected $rules = [
        'code' => 'required|string|max:20|unique:coupons,code',
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'min_order_amount' => 'nullable|numeric|min:0',
        'max_uses' => 'nullable|integer|min:0',
        'starts_at' => 'nullable|date',
        'expires_at' => 'nullable|date|after_or_equal:starts_at',
        'is_active' => 'boolean',
    ];
    
    public function mount()
    {
        $this->loadCoupons();
    }
    
    public function loadCoupons()
    {
        $this->coupons = Coupon::orderBy('created_at', 'desc')->get();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function resetForm()
    {
        $this->reset(['code', 'type', 'value', 'min_order_amount', 'max_uses', 'starts_at', 'expires_at']);
        $this->is_active = true;
        $this->resetValidation();
    }
    
    public function generateRandomCode()
    {
        $this->code = strtoupper(Str::random(8));
    }
    
    public function edit($id)
    {
        $this->editMode = true;
        $this->couponId = $id;
        $coupon = Coupon::findOrFail($id);
        
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->min_order_amount = $coupon->min_order_amount;
        $this->max_uses = $coupon->max_uses;
        $this->starts_at = $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : null;
        $this->expires_at = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null;
        $this->is_active = $coupon->is_active;
        
        $this->showModal = true;
    }
    
    public function save()
    {
        if ($this->editMode) {
            $this->rules['code'] = 'required|string|max:20|unique:coupons,code,' . $this->couponId;
        }
        
        $this->validate();
        
        if ($this->editMode) {
            $coupon = Coupon::findOrFail($this->couponId);
        } else {
            $coupon = new Coupon();
        }
        
        $coupon->code = strtoupper($this->code);
        $coupon->type = $this->type;
        $coupon->value = $this->value;
        $coupon->min_order_amount = $this->min_order_amount;
        $coupon->max_uses = $this->max_uses;
        $coupon->starts_at = $this->starts_at;
        $coupon->expires_at = $this->expires_at;
        $coupon->is_active = $this->is_active;
        
        $coupon->save();
        
        $this->closeModal();
        $this->loadCoupons();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => $this->editMode ? 'Coupon updated successfully!' : 'Coupon created successfully!'
        ]);
    }
    
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();
        
        $this->loadCoupons();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Coupon status updated!'
        ]);
    }
    
    public function delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        
        $this->loadCoupons();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Coupon deleted successfully!'
        ]);
    }
    
    public function render()
    {
        return view('livewire.admin.coupon-management')
            ->layout('components.layouts.admin', [
                'title' => 'Coupon Management',
                'header' => 'Coupon Management'
            ]);
    }
}