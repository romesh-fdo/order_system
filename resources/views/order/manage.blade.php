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
                    <h3>Manage Products</h3>
                    <div class="mt-3 data_actions">
                        <a href="javascript:void(0)" onclick="addRecord()" class="float-end">
                            <button class="btn btn-sm btn-success">
                                <i class="me-2 fas fa-plus-circle"></i> Add
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table border-top border-translucent fs-9 mb-0 data_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Created On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="addDialog" style="display:none">
                <form class="mb-2" id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input class="form-control add-name" name="name" type="text" placeholder="Name" oninput="handleChange('add-name')"/>
                                <label>Name</label>
                            </div>
                            <small class="text-danger" id="error-add-name"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control add-description" name="description" placeholder="Description"
                                    rows="3" oninput="handleChange('add-description')"></textarea>
                                <label>Description</label>
                            </div>
                            <small class="text-danger" id="error-add-description"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input class="form-control add-price" name="price" type="number" step="0.01" placeholder="Price" oninput="handleChange('add-price')"/>
                                <label>Price</label>
                            </div>
                            <small class="text-danger" id="error-add-price"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input class="form-control add-stock_quantity" name="stock_quantity" type="number"
                                    placeholder="Stock Quantity" oninput="handleChange('add-stock_quantity')"/>
                                <label>Stock Quantity</label>
                            </div>
                            <small class="text-danger" id="error-add-stock_quantity"></small>
                        </div>
                    </div>
                    <div class="row g-5 mt-1">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" id="btn_create_record">
                                <i class="fa fa-save me-2"></i>Create Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="editDialog" style="display:none">
                <form class="mb-2" id="editForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input class="form-control edit-name" name="name" type="text" placeholder="Name" oninput="handleChange('edit-name')"/>
                                <label>Name</label>
                            </div>
                            <small class="text-danger" id="error-edit-name"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control edit-description" name="description" placeholder="Description"
                                    rows="3"oninput="handleChange('edit-description')"></textarea>
                                <label>Description</label>
                            </div>
                            <small class="text-danger" id="error-edit-description"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input class="form-control edit-price" name="price" type="number" step="0.01" placeholder="Price" oninput="handleChange('edit-price')"/>
                                <label>Price</label>
                            </div>
                            <small class="text-danger" id="error-edit-price"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input class="form-control edit-stock_quantity" name="stock_quantity" type="number"
                                    placeholder="Stock Quantity" oninput="handleChange('edit-stock_quantity')"/>
                                <label>Stock Quantity</label>
                            </div>
                            <small class="text-danger" id="error-edit-stock_quantity"></small>
                        </div>
                    </div>
                    <div class="row g-5 mt-1">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" id="btn_update_record">
                                <i class="fa fa-save me-2"></i>Update Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="viewDialog" style="display:none">
                <table class="table-borderless table_view_record">
                    <tr>
                        <td><b>Product Name :</b></td>
                        <td><span class="view-name"></span></td>
                    </tr>
                    <tr>
                        <td><b>Description :</b></td>
                        <td><span class="view-description"></span></td>
                    </tr>
                    <tr>
                        <td><b>Price :</b></td>
                        <td><span class="view-price"></span></td>
                    </tr>
                    <tr>
                        <td><b>Stock Quantity :</b></td>
                        <td><span class="view-stock_quantity"></span></td>
                    </tr>
                    <tr>
                        <td><b>Created On :</b></td>
                        <td><span class="view-created_at"></span></td>
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

        addDialogHTML = $("#addDialog").html();
        $("#addDialog").html('');

        viewDialogHTML = $("#viewDialog").html();
        $("#viewDialog").html('');

        editDialogHTML = $("#editDialog").html();
        $("#editDialog").html('');
    });

    function loadTableData() {
        let columns = [
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'price', name: 'price'},
            {data: 'stock_quantity', name: 'stock_quantity'},
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
            ajax: "{{ route('products.all') }}",
            stateSave: true,
            columns: columns,
            order: [[1, 'asc']],
            responsive: true
        });
    }

    async function viewRecord(id) {
        var formData = new FormData();
        formData.append('record_id', id);
        var url = '{{ route("products.show") }}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        try {
            const response = await makeAPIRequest(formData, url, null);

            if (response.success) {
                bootbox.dialog({
                    title: 'View Product',
                    message: viewDialogHTML,
                    onShown: function () {
                        $('.view-name').html(response.data.name);
                        $('.view-description').html(response.data.description);
                        $('.view-price').html(response.data.price);
                        $('.view-stock_quantity').html(response.data.stock_quantity);
                        $('.view-is_active').html(response.data.is_active ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-warning">No</span>');
                        $('.view-created_at').html(response.data.created_at);
                    }
                });
            }
        } catch (error) {
            console.error('Error fetching record:', error);
        }
    }
</script>
