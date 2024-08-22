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
                <h5 class="mb-0">SFA Receipts</h5>
            </div>
            <div class="row" id="top_border">
                <!-- <div class="row">
                <div class="col-2" style="margin-top: 10px;color:red;">
                        <h4>Unselected Amount :</h4>

                    </div>
                    <div class="col-2" style=" margin-top: 10px;color:red;" >
                        <h4 id="unselecteted_lbl">0.00</h4>

                    </div>
                    <div class="col-2" style="margin-top: 10px">
                        <h4>Selected Amount :</h4>

                    </div>
                    <div class="col-2" style=" margin-top: 10px" id="sum_label">
                        <h4>0.00</h4>

                    </div>

                    
                    <div class="col-2" style="margin-left: 50px; margin-top: 10px">
                        <h4>No Of Selected:</h4>

                    </div>
                    <div class="col-1" style=" margin-top: 10px" id="">
                        <h4 id="row_count">0</h4>

                    </div>

                </div> -->
                <div class="row">
                    <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                        <select class="select2 form-control validate" id="cmbEmp">
                            <!-- <option value="">Select Collector</option> -->
                        </select>

                    </div>



                    <!-- <div class="col-md-2" style="margin-top: 10px;margin-left: 10px;display:none;">
                    <input type="date" name="cashDate" id="cashDate" class="form-control">
                </div> -->
                    <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                        <select class="form-select" id="cmbBranch">
                            <option>Select Branch</option>
                        </select>

                    </div>
                    <!-- <div class="col-md-2" style="margin-left: 10px;margin-top: 10px;display:none;">
                        <select class="form-select" id="cmbBook">
                            <option>Select Book</option>
                        </select>

                    </div> -->
                    <!-- <div class="col-md-2" style="margin-left: 10px;margin-top: 10px;display:none;">
                        <input type="number" class="form-control form-control-sm validate" id="txtNumber" placeholder="Enter pag number">

                    </div> -->
                    <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                        <div class="col-12">
                            <div class="row">
                                <!-- <div class="col-6">
                                    <button class="btn btn-success" id="cash_table_prntBtn" name="printBtn">Print&nbsp;<i class="fa fa-print" aria-hidden="true"></i>
                                    </button>
                                </div> -->

                              <!--   <div class="col-6">
                                    <input type="button" id="btn_cash_branch_save" class="btn btn-primary" value="Save">
                                </div> -->


                            </div>
                        </div>

                    </div>

                    <!-- <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                    <button class="btn btn-success" id="cash_table_prntBtn" name="printBtn">Print&nbsp;<i class="fa fa-print" aria-hidden="true"></i>
                    </button>

                </div> -->

                    <!-- <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                    <input type="button" id="btn_cash_branch_save" class="btn btn-primary" value="Save">

                </div> -->



                </div>


            </div>

            <div class="row">

                <div class="col-md-12">

                    <table class="table datatable-fixed-both table-striped" id="sfa_receipts_table" style="table-layout:fixed">
                        <thead>
                            <tr>
                                <th>Reference #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Sales Rep</th>
                                <th>Type</th>
                                <th>Cheque No</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>

                </div>
                <div class="col-md-4" style="margin-left: 10px;margin-bottom:5px;float:right;">
                    <!-- <input type="button" id="btn_cash_branch_save" class="btn btn-primary" value="Save"> -->
                </div>

            </div>
        </div>
    </div>
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->
@include('cb::sfa_receipt_modal')
@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<!-- <script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script> -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>

@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{Module::asset('cb:js/report.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('cb:js/sfa_receipts.js')}}?random=<?php echo uniqid(); ?>"></script>


@endsection