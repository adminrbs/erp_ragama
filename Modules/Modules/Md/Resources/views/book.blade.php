@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title')
Home
@endslot
@slot('subtitle')
Dashboard
@endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')


<!-- Content area -->
<div class="content">
    <div class="card">
        <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
            <h5 class="mb-0">Books</h5>
            <div class="d-inline-flex ms-auto"></div>
        </div>


        <div class="card-body">
            <div>

                <button id="btnglaccount" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNonproprietary" >
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
            <div class="table-responsive">
                <!--Required for Responsive-->
                <table id="books_table" class="table datatable-fixed-both table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th>Book Number</th>
                            <th>Book Name</th>
                            <th>Book Type</th>
                            <th class="edit edit_bank">Action</th>

                        </tr>
                    </thead>
                    <tbody>


                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<!-- Dashboard content -->


</div>
<!-- /dashboard content -->

</div>
<!-- /content area -->


{{--.........Model.......--}}

<!-- suply Group -->
<div class="modal fade" id="modalNonproprietary" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Books</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="modal-body p-4 bg-white">
                    <form id="" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Book Number</label>
                                <input type="text" name="accountcode" id="txtBookNumber" class="form-control validate" autocomplete="off">
                                <span class="text-danger font-weight-bold Nonproprietary"></span>


                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Book Name</label>
                                <input type="text" name="accountTitle" id="txtBookName" class="form-control validate" autocomplete="off">

                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Books type</label>
                                <select id="cmbBooksType" class="form-select">
                                    <option value="1">Sales Return Books</option>
                                    <option value="2">Cash Books</option>
                                    <option value="3">Cheque Books</option>
                                    
                                </select>

                                <span class="text-danger font-weight-bold Nonproprietary"></span>
                                <div id="book_status_div">
                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Books Status </label>
                                    <div>
                                        <label class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" name="switch_single" id="chkStatus">
                                            <span class="form-check-label"></span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>


                </div>


            </div>
            <div class="modal-footer">
                <input type="hidden" id="id">

                <button type="button" id="btnCloseupdate" class="btn btn-secondary">Close</button>
                <button type="button" id="btnsave" class="btn btn-primary ">Save</button>

            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->

{{--........End Model.......--}}




@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Theme JS files -->
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>


@endsection
@section('scripts')

<script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
<script src="{{ Module::asset('md:js/books.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


@endsection