<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Str;
use App\Models\Order;

class AdminOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(EnsureUserIsAdmin::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.orders');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $order = Order::find($id);

        $order->state = $state;

        $order->save();


        $request->session()->flash('message', "Successfully changed order state.");
        return redirect()->route('orders');
    }
}
