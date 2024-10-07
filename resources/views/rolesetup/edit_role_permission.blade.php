@extends('layouts.master')
@section('css')
    <!--Internal  Font Awesome -->
    <link href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!--Internal  treeview -->
    <link href="{{ URL::asset('assets/plugins/treeview/treeview-rtl.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-check-label {
            margin-right: 20px;
            direction: rtl;
            font-size: 16px;
        }

        .form-check-input {
            margin-left: 20px;
            transform: scale(1.2);
        }

        .permissions-box {
            margin-bottom: 30px;
        }

        .permissions-box .col-3 {
            width: 20%;
        }

        .permissions-box .col-9 {
            width: 80%;
        }
    </style>
@endsection

@section('title')
    تعديل الادوار الخاصة بالصلاحيات
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الصلاحيات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة
                    الدور لكل صلاحية</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    <!-- row -->
    <div class="row">
        <div class="col-md-9">
            <div class="card mg-b-20">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{ route('update.role.permission', $role->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الدور</label>
                            <h6>{{ $role->name }}</h6>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="checkDefaultmain">
                            <label class="form-check-label" for="checkDefaultmain">
                                كافة الصلاحيات
                            </label>
                        </div>
                        <hr>
                        @foreach ($permission_group as $item)
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            {{ $item->group_name }}
                                        </label>
                                    </div>
                                </div>

                                @php
                                    $permissions = App\Models\User::getpermissionByGroupName($item->group_name);
                                @endphp


                                <div class="col-9">
                                    @foreach ($permissions as $per)
                                        <div class="form-check mb-2">
                                            <input type="checkbox" class="form-check-input" name="permission[]"
                                                id="checkDefault{{ $per->id }}" value="{{ $per->id }}">
                                            <label class="form-check-label" for="checkDefault{{ $per->id }}">
                                                {{ $per->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                    <br>
                                </div>

                            </div>
                        @endforeach


                        <button type="submit" class="btn btn-primary me-2">Save Changes</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->

@endsection

@section('js')
    <!-- Internal Treeview js -->
    <script src="{{ URL::asset('assets/plugins/treeview/treeview.js') }}"></script>

    <script type="text/javascript">
        $('#checkDefaultmain').click(function() {
            if ($(this).is(':checked')) {
                $('input[type=checkbox]').prop('checked', true);
            } else {
                $('input[type=checkbox]').prop('checked', false);
            }

        });
    </script>
@endsection
