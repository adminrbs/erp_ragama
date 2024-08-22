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
            <h5 class="mb-0">Special Bonus</h5>
            <div class="d-inline-flex ms-auto"></div>
        </div>


        <div class="card-body">
            <div>

                <button id="btn_add_special_bonus" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#special_bonus">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
            <br>
            <div>

                <div class="col-md-2">
                    <label class="transaction-lbl mb-0 " style="width: 100%;text-align: left;"><span>Status</span></label>
                    <select class="form-select" id="cmbStatus">
                        <option value="0">Any</option>
                        <option value="1">Active</option>
                        <option value="2">Pending</option>
                        <option value="3">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive" style="">
                <!--Required for Responsive-->
                <table id="special_bonus_table" class="table datatable-fixed-both table-striped" >
                    <thead>
                        <tr>
                            <!-- <th class="id">ID</th> -->
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Route</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Pacs</th>
                            <th>Qty</th>
                            <th>FOC</th>
                           <!--  <th title="valid days">Days</th> -->
                            <th>Reject Remark</th>
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
<div class="modal fade" id="special_bonus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Special Bonus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="modal-body p-4 bg-white">
                    <form id="special_bonus_form" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg">
                                <input type="hidden" id="bonus_id">
                                <label for="cmb_customer">
                                    <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>
                                    Customer<span style="color: red;">*</span>
                                </label>

                                <select id="cmb_customer" class="select2 form-control validate"></select>

                                <label for="fname">
                                    <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>
                                    Item<span style="color: red;">*</span>
                                </label>

                                <select id="cmbItem" class="select2 form-control validate"></select>



                                <label for="fname">
                                    <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>
                                    Qty<span style="color: red;">*</span>
                                </label>

                                <input type="number" name="txtQty" id="txtQty" class="form-control validate" autocomplete="off">



                                <label for="fname">
                                    <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>
                                    Free Qty<span style="color: red;">*</span>
                                </label>

                                <input type="number" name="txtFreeQty" id="txtFreeQty" class="form-control validate" autocomplete="off">

                                <div style="display: none;">
                                <label for="fname">
                                    <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>
                                    Valid Days<span style="color: red;">*</span>
                                </label>

                                <input type="number" name="txtValidDays" id="txtValidDays" class="form-control validate" autocomplete="off">
                                </div>
                               

                                <label for="fname">
                                    <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>
                                    Remark
                                </label>

                                <input type="text" name="txtRemark" id="txtRemark" class="form-control validate" autocomplete="off">

                            </div>
                        </div>


                </div>


            </div>
            <div class="modal-footer">
                <input type="hidden" id="id">

                <button type="button" id="btnClose" class="btn btn-secondary">Close</button>
                <button type="button" id="btnsave" class="btn btn-primary ">Save</button>

            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->

{{--........End.Model.......--}}




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
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>


@endsection
@section('scripts')

<script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
<script src="{{ Module::asset('sd:js/special_bonus.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


@endsection