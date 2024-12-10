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
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

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

                <div class="col-4 mt-2" style="margin-left: 2rem;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reports</h5>
                            <ul class="list-group">

                                <li class="list-group-item" style="display:none;">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" name="poHelpReport1" value="poHelpReport1" id="poHelpReport1" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        PO Help Report
                                    </label>
                                </li>
                                <li class="list-group-item" style="">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" name="poHelpReport" value="poHelpReport" id="poHelpReport" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        PO Help Report
                                    </label>
                                </li>

                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" name="good_receive_summery_report" value="good_receive_summery_report" id="good_receive_summery_report" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Goods Receive Summary
                                    </label>
                                </li>

                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" name="goods_return_summery_report" value="goods_return_summery_report" id="goods_return_summery_report" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                        Goods Return Summary
                                    </label>
                                </li>

                                <li class="list-group-item">
                                    <label style="display: flex; align-items: center;">
                                        <input class="form-check-input" type="radio" name="option1" name="bonusClaimSummeryReport" value="bonusClaimSummeryReport" id="bonusClaimSummeryReport" style="margin-right: 10px;">
                                        <i class="bi bi-folder2 fa-lg"></i>&nbsp;
                                       Bonus Claim Summery
                                    </label>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mt-2 ml-2" style="margin-left: 2rem;">
                    <form>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Filters</h5>
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label style="font-weight: bold;">From</label>
                                            <input id="txtFromDate" type="date" class="form-control daterange-single">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="tx-bold" style="font-weight: bold;">To</label>
                                            <input id="txtToDate" type="date" class="form-control daterange-single">
                                        </div>

                                        <div class="col-md-1">

                                            <input id="chkdate" type="checkbox" style="margin-top: 30px;">
                                        </div>

                                        <div class="col-md-9 mb-3 mt-2">
                                            <label style="font-weight: bold;">Branch</label>
                                            <!--<select class="form-control validate select2" id="cmbBranch"></select>-->
                                            <select multiple="multiple" class="select  form-select" id="cmbBranch" data-placeholder="Select Branch"></select>
                                        </div>

                                        <div class="col-md-1 mt-2">

                                            <input id="chkBranch" type="checkbox" style="margin-top: 30px;">
                                        </div>



                                        <div class="col-md-9 mb-3 mt-2">
                                            <label style="font-weight: bold;">Supply Group</label>
                                            <!--<select class="form-control validate select2" id="cmbBranch"></select>-->
                                            <select multiple="multiple" class="select  form-select" id="cmbSupplyGroup" data-placeholder="Select Supply Group"></select>
                                        </div>

                                        <div class="col-md-1 mt-2">

                                            <input id="chkSupplyGroup" type="checkbox" style="margin-top: 30px;">
                                        </div>
                                      

                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-9 mb-3" style="text-align: right;margin-right: 100px;">
                            <button id="viewReport" data-bs-toggle="collapse" href="#moh_division" role="button" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary">Preview</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row" style="background-color: gray;">
    <div class="report_div" id="report_div">

    </div>
        <div class="col-md-12" style="text-align: center;">
            <iframe id="pdfContainer" src="" style="min-width: 80%; min-height: 750px;background-color: white;margin-top: 30px;;"></iframe>
        </div>
    </div>
   <!--  <div class="row" style="background-color: gray;">
        <div class="col-md-12" style="text-align: center;">
            <iframe id="pdfContainer" src=""
                style="min-width: 90%; min-height: 750px;background-color: white;margin-top: 30px;"></iframe>
        </div>
    </div> -->

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
<script src="{{Module::asset('prc:js/reports.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection