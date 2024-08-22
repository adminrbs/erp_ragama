@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Option 1: Include in HTML -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
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

    /* Style the radio buttons */
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
        {{-- <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
            <h5 class="mb-0">Report</h5>
        </div>
        <div class="card-header">
            <h5 class="mb-0">
                <button class="btn btn-link" id="btn-collapse-search" data-bs-toggle="collapse" href="#moh_division"
                    role="button" aria-expanded="false" aria-controls="collapseExample" onclick="dataclear()" on>
                    <i class="bi bi-gear" style="margin-right: 5px"></i>Report
                </button>
            </h5>
        </div> --}}

        <div class="card-body" id="crdReportSearch">

            <div class="row">
                <div class="col-sm-5 mt-2" style="margin-left: 0rem;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reports</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="Stock Balance" id="stock-balance" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Stock Balance
                                    </label>
                                </li>
                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value=" Item Movement History" id="item-movement" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Item Movement History
                                    </label>
                                </li>
                                <li class="list-group-item" >
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="valuation" id="valuations" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Stock valuation
                                    </label>
                                </li>
                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="rdStock" id="rdStock" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Total RD Stock Report Without Free Issued
                                    </label>
                                </li>
                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="rdStock" id="rdStockWithFree" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Total RD Stock With Free Issued
                                    </label>
                                </li>
                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" value="rdStock" id="branchwiseStockReport" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Branchwise Stock Report
                                    </label>
                                </li>
                               
                                <br>

                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-sm-7 mt-2 ml-2" style="margin-left: 0rem;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filters</h5>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-11 mb-0 mt-0">
                                            <label style="font-weight: bold;">Branch</label>
                                            <select multiple="multiple" class="select  form-select" id="cmbBranch" data-placeholder="Select Branch"></select>
                                        </div>
                                        <div class="col-md-1 mt-0">
                                            <input id="chkBranch" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-11 mb-0 mt-0">
                                            <label style="font-weight: bold;">Location</label>
                                            <select multiple="multiple" class="form-select validate select" id="cmblocation" data-placeholder="Select Location"></select>
                                        </div>

                                        <div class="col-md-1 mt-0">

                                            <input id="chklocation" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-5">
                                            <label style="font-weight: bold;">From</label>
                                            <input id="txtFromDate" type="date" class="form-control daterange-single">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="tx-bold" style="font-weight: bold;">To</label>
                                            <input id="txtToDate" type="date" class="form-control daterange-single">
                                        </div>
                                        <div class="col-md-1">
                                            <input id="chkdate" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-11 mb-0 mt-0">
                                            <label style="font-weight: bold;">Item</label>
                                            <select multiple="multiple" class="form-control validate select" id="cmbproduct" data-placeholder="Select Product"></select>
                                        </div>
                                        <div class="col-md-1 mt-0">
                                            <input id="chkProduct" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-11 mb-0 ">
                                            <label style="font-weight: bold;"> Item Category Level 1</label>
                                            <select multiple="multiple" class="form-control validate select" id="cmbitemCategory1" data-placeholder="Select Item Category Level 1"></select>
                                        </div>
                                        <div class="col-md-1 mt-1">
                                            <input id="chkitemCategory1" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-md-11 mb-3 ">
                                            <label style="font-weight: bold;"> Item Category Level 2</label>
                                            <select multiple="multiple" class="form-control validate select" id="cmbitemCategory2" data-placeholder="Select Item Category Level 2"></select>
                                        </div>
                                        <div class="col-md-1 mt-1">
                                            <input id="chkitemCategory2" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-11 mb-3 ">
                                            <label style="font-weight: bold;"> Item Category Level 3</label>
                                            <select multiple="multiple" class="form-control validate select" id="cmbitemCategory3" data-placeholder="Select Item Category Level 3"></select>
                                        </div>
                                        <div class="col-md-1 mt-1">
                                            <input id="chkitemCategory3" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-11 mb-3 ">
                                            <label style="font-weight: bold;">supply group</label>
                                            <select multiple="multiple" class="form-control validate select" id="cmbsuplygroup" data-placeholder="Select supply group"></select>
                                        </div>
                                        <div class="col-md-1 mt-1">
                                            <input id="chksuplygroup" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-0" style="text-align: right;margin-right: 100px;">
                                            <button id="viewReport" data-bs-toggle="collapse" href="#moh_division" role="button" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary">Preview</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="row" style="background-color: gray;">
            <div class="col-md-12" style="text-align: center;">
                <iframe id="pdfContainer" src="" style="min-width: 70%; min-height: 750px;background-color: white;margin-top: 30px;;"></iframe>
            </div>
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


@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<script src="{{Module::asset('sc:js/reportGenarate.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>

@endsection