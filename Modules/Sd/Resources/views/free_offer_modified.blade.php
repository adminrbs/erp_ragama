@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')
<!-- Content area -->
<style>
    .hide_icon {
        display: none;
    }

    .overlapped_row {
        background-color: rgb(243, 246, 6);
    }
</style>

<div class="content">


    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Free Offer</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>


                <div class="card card-body">
                    <div class="col-3">
                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="btnModaladd">
                            Add Free Offer
                        </button> -->
                    </div>
                    <form id="frmAddOffer">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label id="hiddnLBLforID" style="display: hidden;"></label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Name <span class="text-danger">*</span> </label>

                                        <div>

                                            <input class="form-control form-control-sm validate" type="text" id="txtOfferName" name="Offername" autocomplete="new-search" required>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Apply to <span class="text-danger">*</span> </label>

                                        <div class="row">
                                            <div class="col-9">

                                                <select class="form-select form-control-sm validate" id="cmbApplyTo">

                                                    <option value="1">All</option>
                                                    <option value="2">Customers</option>
                                                    <option value="3">Customers Group</option>


                                                </select>

                                            </div>
                                            <div class="col-3">
                                                <input type="button" class="btn btn-primary" id="btn_pick" value="Add">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Start date </label>

                                                <div>

                                                    <input class="form-control " type="date" id="dtStartDate" name="startdate">

                                                </div>

                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>End date </label>

                                                <div>

                                                    <input class="form-control " type="date" id="dtEndDate" name="enddate">

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Activate </label>
                                        <div>
                                            <label class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" name="switch_single" id="chkActivate">
                                                <span class="form-check-label"></span>
                                            </label>
                                        </div>

                                    </div>

                                </div>

                                <!-- <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Apply To </label> -->

                                <!--   <div style="display: none;">

                                    <select class="form-control form-control-sm validate" id="cmbApplyTo">
                                        <option value="1">All</option> -->
                                <!-- <option value="2">Locations</option>
                                        <option value="3">Customer</option>
                                        <option value="4">Customer grade</option>
                                        <option value="5">Customer group</option> -->
                                <!--       </select>

                                </div> -->

                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="mb-1">
                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Description </label>

                                    <div>

                                        <input class="form-control form-control-sm validate" type="text" id="txtDescription" name="description" autocomplete="new-search">

                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Item </label>

                                            <div>


                                                <select class="select2 form-control validate" id="cmbItem" data-live-search="true">

                                                </select>


                                            </div>

                                        </div>
                                        <div class="col-6">
                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Supply Group </label>

                                            <div>


                                                <select class="select2 form-control validate" id="cmbSupplyGroup" data-live-search="true">

                                                </select>


                                            </div>

                                        </div>
                                    </div>


                                </div>

                            </div>


                        </div>


                    </form>
                    <form id="frmOfferData">
                        <div class="row">

                            <div class="col-md-8 mb-4">
                                <div class="card card-body">
                                    <ul class="nav nav-tabs mb-0" id="tabs">
                                        <li class="nav-item rbs-nav-item" id="item_li">
                                            <a href="#item" class="nav-link active" aria-selected="true">Item</a>
                                        </li>
                                        <li class="nav-item rbs-nav-item" id="customer_li">
                                            <a href="#customer" class="nav-link" aria-selected="true">Customer</a>
                                        </li>
                                        <li class="nav-item rbs-nav-item" id="customer_group_li">
                                            <a href="#customer_group" class="nav-link" aria-selected="true">Customer Group</a>
                                        </li>

                                        
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="item">
                                            <div class="col-md-12 mb-4">
                                                <table class="table" id="offerItemtable">
                                                    <thead>
                                                        <tr>

                                                            <th>Item Code</th>
                                                            <th>Item</th>
                                                            <th></th>
                                                            <th><input type="checkbox" id="chkAll" onchange="selectAll_item(this)" class="form-check-input"></th>
                                                            <th></th>



                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>

                                                </table>

                                            </div>

                                            <input type="button" class="btn btn-danger" id="btn_remove_item" value="Remove">
                                        </div>

                                        <div class="tab-pane fade show" id="customer">
                                            <div class="col-md-12 mb-4">
                                                <table class="table" id="offer_customer">
                                                    <thead>
                                                        <tr>

                                                            <th>Customer Code</th>
                                                            <th>Customer Name</th>
                                                            <th>Town</th>
                                                            <th><input type="checkbox" id="selectAll" class="form-check-input" onchange="selectAll_customer(this,'customer')"></th>

                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>

                                                </table>
                                            </div>

                                            <input type="button" class="btn btn-danger" id="btn_remove_customer" value="Remove">
                                        </div>

                                        <div class="tab-pane fade show" id="customer_group">
                                            <div class="col-md-12 mb-4">
                                                <table class="table" id="offer_customer_group">
                                                    <thead>
                                                        <tr>

                                                           
                                                            <th>Group Name</th>
                                                            <th><input type="checkbox" id="selectAll" class="form-check-input" onchange="selectAll_customer(this,'group')"></th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>

                                                </table>
                                            </div>

                                            <input type="button" class="btn btn-danger" id="btn_remove_customer" value="Remove">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div id="threshold_div">
                                    <table class="table table-sm table-striped" id="AddThresholdsTable">
                                        <thead>
                                            <tr>
                                                <th>Qty</th>
                                                <th>Free Offer Qty</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <br>
                                    <input type="button" id="btn_apply_threshold" class="btn btn-primary btn-sm" value="Apply">
                                    <input type="button" id="btn_reset_threshold" class="btn btn-danger btn-sm" value="Reset">

                                </div>
                            </div>


                        </div>


                    </form>


                    <div class="col-3">
                        <input type="button" value="Save" id="save_data" class="btn btn-primary">
                    </div>


                </div>

            </div>




        </div>

    </div>

</div>

</div>

<!-- /dashboard content -->


</div>

@include('datachooser.data-chooser')
@include('sd::free_offer_new_customer_model')

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>


<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>

<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_buttons.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/dual_listbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>







@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!-- <script src="{{URL::asset('assets/js/item.js')}}"></script> -->
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<script src="{{URL::asset('assets/rbs-js/is-required.min.js')}}"></script>
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{Module::asset('sd:js/free_offer_modified.js')}}?random=<?php echo uniqid(); ?>"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



@endsection