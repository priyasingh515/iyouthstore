@extends('backend.layouts.app')

@section('content')

<div class="page-content">
    <div class="flex-grow-1 p-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h4>Assign Product to Sellers</h4>

        <form action="{{ route('product.assign.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Select Seller</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Select Seller</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->user->id }}">
                            {{ $shop->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <label>Select Product</label>
                    <select id="product_id" class="form-control">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Quantity</label>
                    <input type="number" id="quantity" class="form-control">
                </div>

                <div class="col-md-2 mt-4">
                    <button type="button" class="btn btn-primary mt-2" onclick="addProduct()">
                        Add
                    </button>
                </div>
            </div>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="product_table_body">
                </tbody>
            </table>

            <button type="submit" class="btn btn-success">
                Submit
            </button>

        </form>
    </div>
</div>

<script>

let count = 0;

function addProduct() {

    let productSelect = document.getElementById('product_id');
    let quantity = document.getElementById('quantity').value;

    let productId = productSelect.value;
    let productName = productSelect.options[productSelect.selectedIndex].text;

    if (productId == "" || quantity == "") {
        alert("Please select product and enter quantity");
        return;
    }

    count++;

    let row = `
        <tr id="row${count}">
            <td>${count}</td>
            <td>
                ${productName}
                <input type="hidden" name="products[${count}][product_id]" value="${productId}">
            </td>
            <td>
                ${quantity}
                <input type="hidden" name="products[${count}][quantity]" value="${quantity}">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeRow(${count})">
                    Remove
                </button>
            </td>
        </tr>
    `;

    document.getElementById('product_table_body').innerHTML += row;

    document.getElementById('product_id').value = "";
    document.getElementById('quantity').value = "";
}

function removeRow(id) {
    document.getElementById('row'+id).remove();
}

</script>

@endsection