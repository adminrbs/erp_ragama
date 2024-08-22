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
<div class="content">

    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Inter Location Transfer Approve</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div class="card-body border-top">
                        <div class="col-md-12 " style="background-color:#EBFFFF;height: 50px; text-align:right;">
                            <button type="button" class="btn btn-primary" id="btnBack">
                                <i class="fa fa-arrow-left" aria-hidden="true"> Back to list</i>
                            </button>

                        </div>
                        <div class="mb-4">
                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>

                                    <input type="text" class="form-control" id="LblexternalNumber" value="New Document" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>

                                    <input type="date" class="form-control" id="goods_received_date_time" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Your Reference</span></label>
                                    <input type="text" id="txtYourReference" class="form-control">
                                </div>

                            </div>

                            <div class="row mb-1">
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>From Branch</span></label>
                                            <select class="form-select" id="cmbBranch"></select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>From Location</span></label>
                                            <select class="form-select" id="cmbLocation"></select>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>To Branch</span></label>
                                            <select class="form-select" id="cmb_to_Branch"></select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>To Location</span></label>
                                            <select class="form-select" id="cmb_to_Location"></select>
                                        </div>

                                    </div>

                                </div>





                            </div>

                        </div>

                        <hr>
                </form>


                <ul class="nav nav-tabs mb-0" id="tabs">
                    <li class="nav-item rbs-nav-item">
                        <a href="#Item" class="nav-link active" aria-selected="true">Product</a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="Item">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table datatable-fixed-both table-striped" id="approve_table">
                                        <thead>
                                            <tr>

                                                <th>Item Code</th>
                                                <th>Name</th>
                                                <th>QTY</th> 
                                                <th>Pack Size</th>
                                                <th>Price</th>
                                                
                                               <!--  <th>Batch#</th> -->
                                                <!-- <th>Avl. Qty</th> -->
                                            <!--     <th>Set Off Qty</th>
                                                <th>Set off</th> -->
                                               <!--  <th style="width: 120px;">Action</th> -->
                                               <!--  <th>value</th>
                                                <th>whole_sale_price</th>
                                                <th>retail price</th>
                                                <th>cost price</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-8 mt-2">
                        <div class="col-md-12">
                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Remarks</span></label>
                            <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off"></textarea>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Gross Total :</label>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblGrossTotal">0.00</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Total Discount :</label>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblTotalDiscount">0.00</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Total Tax :</label>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblTotaltax">0.00</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong class="font-weight-bold">Net Total :</strong>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblNetTotal">0.00</label>
                            </div>
                        </div>


                        <div class="row">
                            
                           <!--  <div class="col-md-6">
                                <button class="btn btn-info" type="button" style="width: 100%;" id="btnSave">Save</button>
                            </div> -->
                            <div class="col-md-6">
                                <button class="btn btn-danger" type="button" style="width: 100%;" id="btnReject">Reject</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-success" type="button" id="btnApprove" style="width: 100%;">Approve</button>
                            </div>
                            <br>

                        </div>
                        <br>
                        

                    </div>
                </div>


                <hr>

            </div>
        </div>
    </div>
</div>
<!-- /dashboard content -->




</div>
<!-- /content area -->


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
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>

@endsection
@section('scripts')

<script src="{{URL::asset('assets/HashMap/HashMap.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/Item.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/ItemSetoff.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{Module::asset('sc:js/goods_transfer_approval.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection