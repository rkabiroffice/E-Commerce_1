@extends('backend.layouts.app')

@section('content')
    @php($shippingAddress = json_decode($order->shipping_address ?: '{}'))
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Auction Order Details') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="h6 mb-0">{{ translate('Order Summary') }}: {{ $order->code }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><td class="fw-600">{{ translate('Customer') }}</td><td>{{ $shippingAddress->name ?? '' }}</td></tr>
                        <tr><td class="fw-600">{{ translate('Email') }}</td><td>{{ $shippingAddress->email ?? ($order->user->email ?? '') }}</td></tr>
                        <tr><td class="fw-600">{{ translate('Phone') }}</td><td>{{ $shippingAddress->phone ?? '' }}</td></tr>
                        <tr><td class="fw-600">{{ translate('Shipping address') }}</td><td>{{ $shippingAddress->address ?? '' }}, {{ $shippingAddress->city ?? '' }}, {{ $shippingAddress->country ?? '' }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><td class="fw-600">{{ translate('Order status') }}</td><td>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td></tr>
                        <tr><td class="fw-600">{{ translate('Payment status') }}</td><td>{{ translate(ucfirst($order->payment_status)) }}</td></tr>
                        <tr><td class="fw-600">{{ translate('Payment method') }}</td><td>{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td></tr>
                        <tr><td class="fw-600">{{ translate('Total') }}</td><td>{{ single_price($order->grand_total) }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="h6 mb-0">{{ translate('Order Items') }}</h5></div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead><tr><th>#</th><th>{{ translate('Product') }}</th><th>{{ translate('Quantity') }}</th><th>{{ translate('Price') }}</th></tr></thead>
                <tbody>
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $orderDetail->product ? $orderDetail->product->getTranslation('name') : translate('Product Unavailable') }}</td>
                            <td>{{ $orderDetail->quantity }}</td>
                            <td>{{ single_price($orderDetail->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
