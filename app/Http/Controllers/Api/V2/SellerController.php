<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\Seller\OrderCollection;
use App\Http\Resources\V2\Seller\OrderDetailResource;
use App\Http\Resources\V2\Seller\OrderItemResource;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderDetail;

class SellerController extends Controller
{
	public function getOrderList(Request $request)
	{
		$orders = Order::query()
			->when($request->filled('payment_status'), function ($query) use ($request) {
				$query->where('payment_status', $request->payment_status);
			})
			->when($request->filled('delivery_status'), function ($query) use ($request) {
				$query->whereHas('orderDetails', function ($detailQuery) use ($request) {
					$detailQuery->where('delivery_status', $request->delivery_status);
				});
			})
			->where('seller_id', api_user()->id)
			->latest()
			->paginate(10);

		return new OrderCollection($orders);
	}

	public function getOrderDetails(int|string $id)
	{
		$order = Order::where('id', $id)
			->where('seller_id', api_user()->id)
			->firstOrFail();

		return OrderDetailResource::collection([$order]);
	}

	public function getOrderItems(int|string $id)
	{
		$orderExists = Order::where('id', $id)
			->where('seller_id', api_user()->id)
			->exists();

		abort_unless($orderExists, 404);

		return OrderItemResource::collection(
			OrderDetail::where('order_id', $id)->get()
		);
	}
}
