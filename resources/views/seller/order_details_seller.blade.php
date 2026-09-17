<div class="modal-header">
    <h5 class="modal-title">{{ translate('Order Details') }}: {{ $order->code }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @php($shippingAddress = json_decode($order->shipping_address ?: '{}'))
    <div class="row mb-3">
        <div class="col-md-6">
            <p class="mb-1"><strong>{{ translate('Customer') }}:</strong> {{ $shippingAddress->name ?? '' }}</p>
            <p class="mb-1"><strong>{{ translate('Email') }}:</strong> {{ $shippingAddress->email ?? ($order->user->email ?? '') }}</p>
            <p class="mb-1"><strong>{{ translate('Phone') }}:</strong> {{ $shippingAddress->phone ?? '' }}</p>
        </div>
        <div class="col-md-6">
            <p class="mb-1"><strong>{{ translate('Payment') }}:</strong> {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</p>
            <p class="mb-1"><strong>{{ translate('Status') }}:</strong> {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</p>
            <p class="mb-1"><strong>{{ translate('Total') }}:</strong> {{ single_price($order->grand_total) }}</p>
        </div>
    </div>
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ translate('Product') }}</th>
                <th>{{ translate('Quantity') }}</th>
                <th>{{ translate('Price') }}</th>
            </tr>
        </thead>
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
