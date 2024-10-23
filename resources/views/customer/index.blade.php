<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    @include('elements.head')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <main class="main" id="top">
        @include('elements.topnav')
        <div class="content">
            <div class="row g-4">
                <div class="col-sm-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3>Place an order</h3>
                        </div>
                        <div class="card-body">
                            <select class="form-select" id="product_selector" data-choices="data-choices" size="1" required="required" name="product_selector" data-options='{"removeItemButton":true,"placeholder":true}'>
                                <option value="">Select products...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product['uuid'] }}">{{ $product['name'] }}</option>
                                @endforeach
                            </select>

                            <div id="selected_products" class="mt-3"></div>

                            <div class="mt-3">
                                <small class="text-danger" id="error-items.quantity"></small>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-success" id="btn_place_order">
                                    Place Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3>My orders</h3>
                        </div>
                        <div class="card-body">
                            <div class="row" id="order_summary">
                                <div class="col-12 text-center">
                                    Loading ....
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

@include('elements.js')

<script>
    var cart_details = {
        user_id: "{{ $auth_user['uuid'] }}",
        items: []
    };

    $(document).ready(function() {
        $('#product_selector').change(function() {
            var selected_product_id = $(this).val();
            var selected_product_name = $('#product_selector option:selected').text();
            var product_exists = $('#selected_products .card[data-product-id="' + selected_product_id + '"]').length;

            if(selected_product_id && !product_exists)
            {
                $('#selected_products').append(`
                    <div class="card mb-2" data-product-id="${selected_product_id}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span>${selected_product_name}</span>
                            <div class="d-flex align-items-center">
                                <input type="number" class="form-control quantity-input" data-product-id="${selected_product_id}" min="1" value="1" style="width: 70px; display: inline-block;">
                                <button class="btn btn-danger btn-sm ms-2 remove-product" data-product-id="${selected_product_id}">Remove</button>
                            </div>
                        </div>
                    </div>
                `);

                cart_details.items.push({
                    product_id: selected_product_id,
                    quantity: 1
                });
            }
        });

        $(document).on('input', '.quantity-input', function() {
            var product_id = $(this).data('product-id');
            var quantity = $(this).val();

            var product = cart_details.items.find(item => item.product_id === product_id);
            if(product)
            {
                product.quantity = parseInt(quantity);
            }
        });

        $(document).on('click', '.remove-product', function() {
            var product_id = $(this).data('product-id');
            
            $('#selected_products .card[data-product-id="' + product_id + '"]').remove();

            cart_details.items = cart_details.items.filter(item => item.product_id !== product_id);
        });

        $('#btn_place_order').click(async function() {
            if(cart_details.items.length === 0)
            {
                bootbox.alert('Please select at least one product before placing an order.');
                return;
            }

            const button_properties = {
                id: 'place_order_btn',
                text: 'Place Order',
                process_text: 'Placing Order...'
            };

            const url = '{{ route("orders.place") }}';

            const jsonData = JSON.stringify(cart_details);

            const response = await makeAPIRequest(jsonData, url, button_properties);

            if(response.success)
            {
                cart_details.items = [];
                $('#selected_products').html('');
                $('#order_summary').text(JSON.stringify(response.order_summary, null, 2));
                loadMyOrders();
            }
        });

        loadMyOrders();
    });

    
    async function loadMyOrders()
    {
        const newConfigs = {
            method: 'GET',
        };

        const url = '{{ route("orders.my_orders") }}';

        const response = await makeAPIRequest(null, url, null, newConfigs);

        if (response.success) {
            const orderDetails = response.order_details;
            let summary = ``;

            if (orderDetails) {
                orderDetails.forEach(order => {
                    summary += `
                        <div class="col-sm-12 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <h4><b>Placed On :</b> ${new Date(order.created_at).toLocaleString()}</h4>
                                    <h4><b>Status :</b> <span class="badge badge-${order.status_badge}">${order.status_name || 'Unknown'}</span></h4>
                                    <h4><b>Total :</b> $${order.total_price}</h4>
                                    <hr>`;

                    order.items.forEach(item => {
                        if (item.product) {
                            summary += `
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <b>${item.product.name}</b> x ${item.product.quantity} <br>
                                        ${item.product.description ?? '-'}
                                        <hr>
                                        $${item.subtotal}
                                    </div>
                                </div>`;
                        } else {
                            summary += `
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <b>Product Unavailable</b> x ${item.product ? item.product.quantity : item.quantity} <br>
                                        <hr>
                                        $${item.subtotal}
                                    </div>
                                </div>`;
                        }
                    });

                    summary += `
                                </div>
                            </div>
                        </div>`;
                });
            } else {
                summary = `<div class="col-12 text-center">No Orders Yet</div>`;
            }

            $('#order_summary').html(summary);
        }
    }



</script>



</html>
