<?php

namespace App\Components\Finfo\Modules\Checkouts;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function index()
    {
        return $this->view('payment_gateway');
    }

    public function getPayment($id)
    {
    	return $this->view('payment_gateway')->with('id', $id);
    }
}
