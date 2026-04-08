<table class="table table-bordered">

    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>

        @forelse($assignment_history as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->created_at }}</td>
            </tr>

        @empty

            <tr>
                <td colspan="4" class="text-center">
                    No assignment history
                </td>
            </tr>
        @endforelse

    </tbody>

</table>
