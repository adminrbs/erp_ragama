@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>

</style>
@endsection

@section('content')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <title>Laravel</title>

</head>
<style>
    tr.highlighted {
        background-color: #E2F7FB !important;
        font-weight: bold;
    }

    input[type="radio"] {
        width: 18px;
        height: 18px;
    }

    /* Style the label for the radio buttons (optional) */
    label {
        margin-right: 10px;

        /* Add some spacing between the label and the radio button */
    }

    ul.list-group li {
        border: none;
    }
</style>

<body>

    <br>
    <div class="card">
        <div class="card-header bg-darkr" style="color: white;background-color: #252b36;">
            <div class="row">
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="btn_advanced_search">Select Report</button>
                </div>
                <div class="col-md-1">
                    <button type="button" id="btnPrint" class="btn btn-primary" style="width: 100%;">Print</button>
                </div>
                <div class="col-md-1">
                    <button type="button" id="btnExport" class="btn btn-primary" style="width: 100%;">Export</button>
                </div>
            </div>
        </div>
        <div class="card-body" id="crdReportSearch">

            <div class="row">
                <div class="col-4 mt-0" style="margin-left: 0rem;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Supplier Reports</h5>
                            <ul class="list-group">

                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="Supplier's Ledger" id="suplier_Ledger" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Supplier's Ledger
                                    </label>
                                </li>
                                <br>
                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="creditorleger" id="creditorlegerAge" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Creditor Age Analysis
                                    </label>
                                </li>


                                <br>
                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="Supplier's Outstanding Age Analysis" id="supplier_outstanding" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Supplier's Outstanding
                                    </label>
                                </li>
                                <br>
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-sm-8 mt-2 mt-0" style="margin-left: 0rem;">
                    <form>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Filters</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row ">
                                            <div class="col-md-5">
                                                <label style="font-weight: bold;">From</label>
                                                <input id="txtFromDate" type="date" class="form-control daterange-single date_range">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="tx-bold" style="font-weight: bold;">To</label>
                                                <input id="txtToDate" type="date" class="form-control daterange-single date_range">
                                            </div>
                                            <div class="col-md-1">
                                                <input id="chkdate" type="checkbox" style="margin-top: 30px;" class="date_range">
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-11 mb-0 mt-0">
                                                <label style="font-weight: bold;">Branch</label>
                                                <!--<select class="form-control validate select2" id="cmbBranch"></select>-->
                                                <select multiple="multiple" class="select  form-select branch" id="cmbBranch" data-placeholder="Select Branch"></select>
                                            </div>
                                            <div class="col-md-1 mt-0">
                                                <input id="chkBranch" type="checkbox" style="margin-top: 30px;" class="branch">
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-11 mb-0 mt-0">
                                                <label style="font-weight: bold;">Supplier</label>
                                                <!-- <select class="form-control validate select2" id="cmbCustomer"></select>-->
                                                <select multiple="multiple" class="form-select select supplier" id="cmbSupplier" data-placeholder="Select Supplier" style="height: 100%"></select>
                                            </div>
                                            <div class="col-md-1 mt-0">
                                                <input id="chkSupplier" type="checkbox" style="margin-top: 30px;" class="supplier">
                                            </div>
                                        </div>

                                      
                                       
                                        
                                    </div>
                                    <div class="col-md-6">
                                        
                                        <div class="row ">
                                            <div class="col-md-11 mb-0 ">
                                                <label style="font-weight: bold;"> Greater Than(Age)</label>
                                                <input type="number" class="form-control validate number greaterthan" id="cmbgreaterthan">
                                            </div>
                                            <div class="col-md-1 mt-0">
                                                <input id="chkreaterthan" type="checkbox" style="margin-top: 30px;" class="greaterthan">
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-5">
                                                <label style="font-weight: bold;">From(Age)</label>
                                                <input id="txtfromAge" type="number" class="form-control daterange-single age">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="tx-bold" style="font-weight: bold;">To(Age)</label>
                                                <input id="txtToAge" type="number" class="form-control daterange-single age">
                                            </div>

                                            <div class="col-md-1">

                                                <input id="chkfromtoAge" type="checkbox" style="margin-top: 30px;" class="age">
                                            </div>
                                        </div>

                                        <div class="row supplygrp">
                                            <div class="col-md-11 mb-0 ">
                                                <label style="font-weight: bold;"> Supply Group</label>
                                                <!--<select class="form-control validate select2" id="cmbcustomergroup"></select>-->
                                                <select multiple="multiple" class="form-select select " id="cmbSupplyGroup" data-placeholder="Select Supply Group"></select>
                                            </div>
                                            <div class="col-md-1 mt-0">
                                                <input id="chkSupply" type="checkbox" style="margin-top: 30px;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mt-3" style="text-align: right;margin-right: 100px;">
                                                <button type="button" id="viewReport" data-bs-toggle="collapse" href="#moh_division" role="button" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary">Preview</button>
                                            </div>
                                        </div>
                                    </div>




















                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>

        </div>


    </div>


    <div class="row" style="background-color: gray;">
        <div class="col-md-12" style="text-align: center;">
            <iframe id="pdfContainer" src="" style="min-width: 90%; min-height: 750px;background-color: white;margin-top: 30px;;"></iframe>
        </div>
    </div>

</body>

</html>

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>



<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>

<!-- <script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script> -->
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_buttons.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>
<script src="/assets/report.js"></script>

@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<script src="{{Module::asset('sl:js/supplier_reports.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection