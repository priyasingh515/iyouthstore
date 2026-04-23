@extends('backend.layouts.app')

@section('content')
    <div class="page-content">
        <div class="flex-grow-1 p-4">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
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
                            <option value="{{ $shop->user_id }}">
                                {{ $shop->name }}
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
                                <option value="{{ $product->id }}"
                                    data-min="{{ $product->seller_min_purchase_limit ?? 1 }}"
                                    data-max="{{ $product->seller_purchase_limit ?? 9999 }}">
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

    <div class="modal fade" id="limitModal">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invalid Quantity</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <p id="limitMessage"></p>
                    <button class="btn btn-primary mt-2" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let count = 0;

        //     function addProduct() {

        //         let productSelect = document.getElementById('product_id');
        //         let quantity = document.getElementById('quantity').value;

        //         let productId = productSelect.value;
        //         let productName = productSelect.options[productSelect.selectedIndex].text;

        //         if (productId == "" || quantity == "") {
        //             alert("Please select product and enter quantity");
        //             return;
        //         }

        //         count++;

        //         let row = `
    //     <tr id="row${count}">
    //         <td>${count}</td>
    //         <td>
    //             ${productName}
    //             <input type="hidden" name="products[${count}][product_id]" value="${productId}">
    //         </td>
    //         <td>
    //             ${quantity}
    //             <input type="hidden" name="products[${count}][quantity]" value="${quantity}">
    //         </td>
    //         <td>
    //             <button type="button" class="btn btn-danger btn-sm"
    //                 onclick="removeRow(${count})">
    //                 Remove
    //             </button>
    //         </td>
    //     </tr>
    // `;

        //         document.getElementById('product_table_body').innerHTML += row;

        //         document.getElementById('product_id').value = "";
        //         document.getElementById('quantity').value = "";
        //     }

        //     function removeRow(id) {
        //         document.getElementById('row' + id).remove();
        //     }


        function addProduct() {

            let productSelect = document.getElementById('product_id');
            let quantity = parseInt(document.getElementById('quantity').value);

            let selected = productSelect.options[productSelect.selectedIndex];

            let productId = productSelect.value;
            let productName = selected.text;

            let min = parseInt(selected.getAttribute('data-min')) || 1;
            let max = parseInt(selected.getAttribute('data-max')) || 9999;

            if (productId == "" || !quantity) {
                alert("Please select product and enter quantity");
                return;
            }

            let exists = document.querySelector(
                `input[name^="products"][value="${productId}"]`
            );

            if (exists) {
                showModal("Product already added");
                return;
            }

            if (quantity < min) {
                showModal("Minimum quantity is " + min);
                return;
            }

            if (quantity > max) {
                showModal("Maximum quantity is " + max);
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

        function showModal(message) {
            document.getElementById('limitMessage').innerText = message;
            $('#limitModal').modal('show');
        }

        function removeRow(id) {
            document.getElementById('row' + id).remove();
        }
    </script>
@endsection
