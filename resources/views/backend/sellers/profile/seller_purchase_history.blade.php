<div class="card">
    <div class="card-header">
        <h5>Seller Purchases</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order Code</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Delivery Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($purchase_orders as $order)
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ date('d M Y', $order->date) }}</td>
                        <td>{{ single_price($order->grand_total) }}</td>
                        <td>{{ ucfirst($order->payment_status) }}</td>
                        <td>{{ ucfirst($order->delivery_status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No Purchase Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>