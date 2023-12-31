<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class AdminCouponsComponent extends Component
{
    public function deleteCoupon($coupon_id)
    {
        $coupon = Coupon::find($coupon_id);
        $coupon->delete();
        flash()->addSuccess('Coupon has been deleted successfully!');
        return redirect()->route('admin.coupons');
    }

    public function render()
    {
        $coupons = Coupon::all();

        return view('livewire.admin.admin-coupons-component', ['coupons' => $coupons])->layout('layouts.base');
    }
}
