@extends('layouts.master')
@section('css')
    <!-- DataTables CSS -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('title')
    المنتجات
@stop
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto">الإعدادات</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المنتجات</span>
        </div>
    </div>
@endsection
@section('content')

    <!-- Products Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <a class="btn btn-outline-primary btn-block" data-toggle="modal" href="#addProductModal">إضافة منتج</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap" data-page-length='50'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المنتج</th>
                                    <th>اسم القسم</th>
                                    <th>الملاحظات</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($products as $Product)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $Product->Product_name }}</td>
                                        <td>{{ $Product->section->section_name }}</td>
                                        <td>{{ $Product->description }}</td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm" data-toggle="modal"
                                                data-target="#editProductModal"
                                                data-pro_id="{{ $Product->id }}"
                                                data-product_name="{{ $Product->Product_name }}"
                                                data-section_id="{{ $Product->section_id }}"
                                                data-description="{{ $Product->description }}">تعديل</button>

                                            <button class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteProductModal"
                                                data-pro_id="{{ $Product->id }}"
                                                data-product_name="{{ $Product->Product_name }}">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('products.store') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">إضافة منتج</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>اسم المنتج</label>
                                <input type="text" class="form-control" name="Product_name" required>
                            </div>
                            <div class="form-group">
                                <label>القسم</label>
                                <select name="section_id" class="form-control" required>
                                    <option value="" selected disabled> --حدد القسم--</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>الملاحظات</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">تأكيد</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('products.update') }}" method="post">
                        @csrf
                        {{ method_field('PATCH') }}
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل المنتج</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="pro_id" id="pro_id">
                            <div class="form-group">
                                <label>اسم المنتج</label>
                                <input type="text" class="form-control" name="Product_name" id="Product_name" required>
                            </div>
                            <div class="form-group">
                                <label>القسم</label>
                                <select name="section_id" id="section_id" class="form-control" required>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>الملاحظات</label>
                                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">تأكيد</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Product Modal -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('products.destroy') }}" method="post">
                        @csrf
                        {{ method_field('DELETE') }}
                        <div class="modal-header">
                            <h5 class="modal-title">حذف المنتج</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="pro_id" id="pro_id">
                            <p>هل أنت متأكد من حذف المنتج؟</p>
                            <input type="text" class="form-control" name="Product_name" id="Product_name" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">تأكيد</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')
    <!-- jQuery and Bootstrap JavaScript -->
    <script>
        // Edit Product Modal Logic
        $('#editProductModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var pro_id = button.data('pro_id');
            var product_name = button.data('product_name');
            var section_id = button.data('section_id');
            var description = button.data('description');
            var modal = $(this);

            modal.find('.modal-body #pro_id').val(pro_id);
            modal.find('.modal-body #Product_name').val(product_name);
            modal.find('.modal-body #section_id').val(section_id);
            modal.find('.modal-body #description').val(description);
        });

        // Delete Product Modal Logic
        $('#deleteProductModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var pro_id = button.data('pro_id');
            var product_name = button.data('product_name');
            var modal = $(this);

            modal.find('.modal-body #pro_id').val(pro_id);
            modal.find('.modal-body #Product_name').val(product_name);
        });
    </script>
@endsection
