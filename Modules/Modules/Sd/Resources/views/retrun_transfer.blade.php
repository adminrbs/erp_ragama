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

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Return Transfer</h5>
            </div>
            <!-- <div class="row">
                <div class="col-md-2" style="margin-left: 10px;margin-top:5px">
                    <select class="form-select" id="cmbBranch">

                    </select>
                </div>

                <div class="col-md-2" style="margin-left: 10px;margin-top:5px">
                    <select class="form-select" id="cmbLocation">

                    </select>
                </div>
            </div> -->
        </div>
        <form id="form" class="form-validate-jquery">
            <div class="card-body border-top">
                <div class="col-md-12 " style="background-color:#EBFFFF;height: 50px; text-align:right;">
                <button type="button" class="btn btn-primary" id="btnBack">
                                <i class="fa fa-arrow-left" aria-hidden="true"> Back to list</i>
                            </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="rtn_model_btn">
                        Add
                    </button>
                </div>
                <div class="mb-4">

                    <div class="row mb-1">

                        <div class="col-md-4">
                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>
                            <!-- <input type="text" name="customer_id" id="customer_id" class="form-control form-control-sm" required placeholder="Referance No" autocomplete="off"> -->
                            <input type="text" class="form-control" id="LblexternalNumber" value="New Document" disabled>
                        </div>


                        <div class="col-md-4">
                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>
                            <!-- <input type="text" name="date" id="date" class="form-control form-control-sm" required placeholder="Date" autocomplete="off"> -->
                            <input type="date" class="form-control" id="rtn_date" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                            <select class="form-select" id="cmbBranch"></select>
                        </div>


                     
                    </div>

                    <div class="row mb-1">
                        <div class="col-md-4">
                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>From Location</span></label>
                            <select class="form-select" id="cmbLocation"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>To Location</span></label>
                            <select class="form-select" id="cmb_to_Location"></select>
                        </div>

                    </div>


                </div>

        </form>

        <div class="row">
            <div class="col-md-12">
                <table class="table datatable-fixed-both table-striped" id="sales_return_transfer_table" style="table-layout:fixed">
                    <thead>
                        <tr>
                            <th>Return Date</th>
                            <th>Reference #</th>
                            <th>Customer</th>
                            <th>Item Name</th>
                            <th>Pack Size</th>
                            <th>Total Qty</th>
                            <th>Transfer Qty</th>
                            <th><input type="checkbox" id="chkAll" onchange="selectAll(this)"></th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class=" col-21" style="float: right">
 
    <button class="btn btn-info" type="button" style="width: 100%;" id="btnSave">Save</button>
  
</div>

    </div>
</div>
<!-- /multiple fixed columns -->

</div>
<!-- /content area -->

@include('sd::return_transfer_model')

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('sd:js/return_transfer.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesReturnReport.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
@endsection