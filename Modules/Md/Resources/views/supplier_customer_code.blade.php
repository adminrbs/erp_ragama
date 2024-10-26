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


    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Supplier's Customer code</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label><i class="fa fa-address-book-o" aria-hidden="true"></i>Branch</label>
                            <select id="cmbBranch" class="form-control form-control-sm select2" style="width: 100%" data-placeholder="Select Here...."></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <button id="btnAdd" type="button" class="btn btn-primary" data-bs-toggle="modal" onclick="">
                                Add
                            </button>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <table class="table datatable-fixed-both mt-3 table-striped" id="supplierCustomerCodeTable">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Customer Code</th>
                                    <th>Branch</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>



            </div>

        </div>

    </div>

</div>

<!-- /dashboard content -->




<!-- Modal Town-->
<div class="modal fade" id="modelCustomer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supplier's Customer Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="modal-body p-4 bg-white">
                    <form id="supplierCustomerForm" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg mb-3">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Branch<span class="text-danger">*</span></label>
                                <input type="text" name="branch" id="txtBranch" class="form-control validate" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg mb-3">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Customer<span class="text-danger">*</span></label>
                                <select id="cmbCustomer" class="form-control form-control-sm select2" style="width: 100%"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg mb-3">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Customer Code<span class="text-danger">*</span></label>
                                <input type="text" name="customerCode" id="txtCustomerCode" class="form-control validate" required>
                            </div>
                        </div>


                </div>


            </div>
            <div class="modal-footer">
                <input type="hidden" id="id">
                <button type="button" id="btnCloseModal" class="btn btn-secondary">Close</button>
                <button type="button" id="btnSaveModal" class="btn btn-primary ">Save</button>
                <button type="button" id="btnUpdateModal" class="btn btn-primary updateTown">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->

</div>
<!-- /content area -->





@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{ URL::asset('assets/js/jquery/jquery.min.js') }}"></script>


<!-- Theme JS files -->
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/ui/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/pickers/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/pickers/datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/uploaders/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_modals.js') }}"></script>











@endsection
@section('scripts')
<script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>

<script src="{{ Module::asset('md:js/supplier_customer_code.js') }}?random=<?php echo uniqid(); ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>

@endsection