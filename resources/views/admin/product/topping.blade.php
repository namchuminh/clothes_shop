@extends('Admin.layouts.app')
@section('title', 'Cập nhật topping')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Sản Phẩm</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Quản Lý Sản Phẩm</a></li>
                    <li class="breadcrumb-item active">Phụ Kiện</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-default">
            <!-- /.card-header -->
            <div class="card-body">
                <form id="update-form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ten">Tên Sản Phẩm</label>
                                <input type="text" class="form-control tenchinh" id="name" placeholder="Tên sản phẩm"
                                    name="name" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Chuyên Mục</label>
                                <input type="text" class="form-control" id="category" placeholder="Chuyên Mục"
                                    name="category" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Chọn Phụ Kiện</label>
                                <select class="form-control" name="topping_id" id="topping_id">
                                </select>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-success" href="{{ route('admin.product.index') }}">Quay Lại</a>
                    <button type="submit" class="btn btn-primary">Thêm Phụ Kiện</button>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách giá bán theo phụ kiện</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Phụ Kiện</th>
                                    <th>Giá Tính Thêm</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item">
                                <a class="page-link" href="#"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
@section('css')
<style>
    .form-control:disabled, .form-control[readonly] {
        background-color: white;
        opacity: 1;
    }
</style>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        var id = window.location.search.split('id=')[1];
        function fetchData() {
            $.ajax({
                url: `{{ $api_url }}products/${id}`,
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                },
                success: function(response) {
                    $("#name").val(response.name);
                    $("#category").val(response.category.name);
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            // Retry the fetch data request with the new token
                            fetchData();
                        });
                    }
                }
            });
        }

        function fetchDataTopping() {
            $.ajax({
                url: `{{ $api_url }}toppings`,
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                },
                success: function(response) {
                    $("#topping_id").empty();
                    response.data.forEach(item => {
                        $("#topping_id").append(`<option value="${item.id}">${item.name}</option>`);
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            // Retry the fetch data request with the new token
                            fetchDataTopping();
                        });
                    }
                }
            });
        }

        function fetchDataToppingProduct() {
            $.ajax({
                url: `{{ $api_url }}products/${id}/topping`,
                method: 'GET',
                success: function(response) {
                    $('tbody').empty();
                    let rowNumber = 1;
                    response.forEach(item => {
                        let price = Number(item.topping.price);
                        $('tbody').append(`
                            <tr>
                                <td>${rowNumber++}</td>
                                <td>${item.topping.name}</td>
                                <td>${price.toLocaleString('vi-VN')}đ</td>
                                <td>
                                    <a href="#" data-id="${item.id}" class="btn btn-danger delete-btn">Xóa</a>
                                </td>
                            </tr>
                        `);
                    });

                    // Handle delete button click events
                    $('.delete-btn').on('click', function(event) {
                        event.preventDefault();
                        const id = $(this).data('id');
                        if (confirm('Bạn có chắc chắn muốn xóa Phụ Kiện này?')) {
                            deleteAction(id);
                        }
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            // Retry the fetch data request with the new token
                            fetchData();
                        });
                    }
                }
            });
        }

        fetchData();
        fetchDataTopping();
        fetchDataToppingProduct();

        $('form').on('submit', function(event) {
            event.preventDefault(); // Ngăn không cho form gửi theo cách mặc định

            const formData = new FormData(this);
            function create() {
                $.ajax({
                    url: `{{ $api_url }}products/${id}/topping`,
                    type: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                    },
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        fetchDataToppingProduct();
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: 5000
                        };
                        toastr.success('Thêm Topping thành công!', 'Thành Công');
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            refreshToken().done(function() {
                                create();
                            });
                        } else if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    toastr.options = {
                                        closeButton: true,
                                        progressBar: true,
                                        positionClass: 'toast-top-right',
                                        timeOut: 5000
                                    };
                                    errors[key].forEach(function(error) {
                                        toastr.error(error, 'Thất Bại');
                                    });
                                }
                            }
                        } else {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                                positionClass: 'toast-top-right',
                                timeOut: 5000
                            };
                            toastr.error('Thêm Topping thất bại!', 'Thất Bại');
                        }
                    }
                });
            }

            function refreshToken() {
                return $.ajax({
                    url: `{{ $api_url }}refresh`,
                    method: 'POST',
                    data: {
                        'refresh_token': localStorage.getItem('refresh_token')
                    }
                }).done(function(response) {
                    localStorage.setItem('access_token', response.access_token);
                }).fail(function(xhr) {
                    // Clear localStorage and redirect to login
                    localStorage.removeItem('access_token');
                    localStorage.removeItem('refresh_token');
                    window.location.href = '{{ route('admin.login') }}'; // Replace with your login route
                });
            }

            create();
        });

        function deleteAction(id) {
            $.ajax({
                url: `{{ $api_url }}products/${id}/topping`,
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                },
                success: function() {
                    fetchDataToppingProduct();
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            deleteAction(id);
                        });
                    } else {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: 5000
                        };
                        toastr.error('xóa Phụ Kiện thất bại!', 'Thất Bại');
                    }
                }
            });
        }
    });
</script>
@endsection
