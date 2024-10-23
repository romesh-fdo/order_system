<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    @include('elements.head')
</head>

<body>
    <main class="main" id="top">
        @include('elements.sidenav')
        @include('elements.topnav')

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Manage Orders</h3>
                </div>
                <div class="card-body">
                    <table class="table table border-top border-translucent fs-9 mb-0 data_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Created On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="viewDialog" style="display:none">
                <table class="table table-borderless table_view_record">
                    <tr>
                        <td><b>User :</b></td>
                        <td><span class="view-user"></span></td>
                    </tr>
                    <tr>
                        <td><b>Total Price :</b></td>
                        <td><span class="view-total-price"></span></td>
                    </tr>
                    <tr>
                        <td><b>Status :</b></td>
                        <td><span class="view-status"></span></td>
                    </tr>
                    <tr>
                        <td><b>Created On :</b></td>
                        <td><span class="view-created_at"></span></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="view-items">

                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            
        </div>
    </main>
</body>

@include('elements.js')

<script>
    var data_table;

    $(document).ready(function() {
        data_table = loadTableData();

        viewDialogHTML = $("#viewDialog").html();
        $("#viewDialog").html('');
    });

    function loadTableData() {
        let columns = [
            {data: 'user_name', name: 'user.name'},
            {data: 'total_price', name: 'total_price'},
            {
                    data: 'status_name', 
                    name: 'status_name', 
                    render: function(data, type, row) {
                        let badgeClass = row.status_badge || 'light';
                        return `<span class="badge badge-${badgeClass}">${data}</span>`;
                    }
                },
            {data: 'formatted_created_at', name: 'created_at'},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ];

        return table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('orders.all') }}",
            stateSave: true,
            columns: columns,
            order: [[1, 'asc']],
            responsive: true
        });
    }

    async function viewRecord(id) {
        var formData = new FormData();
        formData.append('record_id', id);
        var url = '{{ route("orders.show") }}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        try {
            const response = await makeAPIRequest(formData, url, null);

            if (response.success) {
                bootbox.dialog({
                    title: 'View Order',
                    message: viewDialogHTML,
                    onShown: function () {
                        $('.view-user').html(response.data.user_name);
                        $('.view-total-price').html(response.data.total_order_price);
                        $('.view-status').html('<span class="badge badge-'+response.data.status_badge+'">'+response.data.status_name+'</span>');
                        $('.view-created_at').html(response.data.formatted_created_at);

                        $('.view-items').html('');

                        response.data.items.forEach(item => {
                            const itemRow = `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.quantity}</td>
                                    <td>${item.price}</td>
                                    <td>${item.total_item_price}</td>
                                </tr>
                            `;
                            $('.view-items').append(itemRow);
                        });
                    }
                });
            }
        } catch (error) {
            console.error('Error fetching record:', error);
        }
    }

    async function cancelOrder(id) {
        bootbox.confirm({
            title: 'Cancel Order',
            message: 'Do you want to cancel this order?',
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-secondary'
                }
            },
            callback: async (result) => {
                if (result) {
                    var url = `{{ route("orders.cancel") }}`;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var formData = new FormData();
                    formData.append('record_id', id);

                    const delete_response = await makeAPIRequest(formData, url, null);

                    if (delete_response.success) {
                        bootbox.hideAll();
                        $('.data_table').DataTable().destroy();
                        data_table = loadTableData();
                    }
                }
            }
        });
    }

    async function completeOrder(id) {
        bootbox.confirm({
            title: 'Complete Order',
            message: 'Do you want to complete this order?',
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-secondary'
                }
            },
            callback: async (result) => {
                if (result) {
                    var url = `{{ route("orders.complete") }}`;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var formData = new FormData();
                    formData.append('record_id', id);

                    const delete_response = await makeAPIRequest(formData, url, null);

                    if (delete_response.success) {
                        bootbox.hideAll();
                        $('.data_table').DataTable().destroy();
                        data_table = loadTableData();
                    }
                }
            }
        });
    }
</script>
