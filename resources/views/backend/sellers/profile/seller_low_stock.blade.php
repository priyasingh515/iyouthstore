<table class="table table-bordered">

    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Stock</th>
        </tr>
    </thead>

    <tbody>

        @forelse($low_stock as $key => $item)
            <tr>

                <td>{{ $key + 1 }}</td>

                <td>{{ $item->product_name }}</td>

                <td>
                    <span class="badge badge-danger">
                        {{ $item->stock }}
                    </span>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="3" class="text-center">
                    No low stock items
                </td>
            </tr>
        @endforelse

    </tbody>

</table>
