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

<style>
    .text-right {
    text-align: right;
}

</style>

<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Cheque Received From Office(SR)</h5>
            </div>
            <div class="row" id="top_border">
            <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                    <select class="form-select" id="cmbBranch">
                        <option>Select Branch</option>
                    </select>

                </div>
                <div class="col-md-3" style="margin-left: 50px; margin-top: 10px">
                    <h4>Total Selected Amount :</h4>

                </div>
                <div class="col-md-2" style=" margin-top: 10px" id="sum_label">
                    <h4>0.00</h4>

                </div>
                <div class="col-md-2" style="margin-left: 50px; margin-top: 10px">
                    <h4>Number Of Selected:</h4>

                </div>
                <div class="col-md-2" style=" margin-top: 10px" id="">
                    <h4 id="row_count">0</h4>

                </div>

                <div class="col-md-2" style="margin-top: 10px;margin-left: 10px;display:none;">
                    <input type="date" name="cashDate" id="cashDate" class="form-control">
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">

                    <table class="table datatable-fixed-both table-striped" id="cheque_collection_by_ho_table" style="table-layout: auto;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference #</th>
                                <th style="text-align: right;">Amount</th>
                                <th>Info</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
                <div class="col-md-4" style="margin-left: 10px;margin-bottom:5px;float:right;">
                    <input type="button" id="btn_chq_ho_save" class="btn btn-primary" value="Save">
                </div>
            </div>
        </div>
    </div>
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->





<style>
    .highlight-row {
        color: red !important;
    }

    .table-zebra tr:nth-child(even) {
        background-color: #F6F6F6;
    }
</style>

<!-- Modal -->
<div class="modal fade modal-md" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <input type="hidden" id="hiddem_lbl">
            <div class="modal-body">
                <div class="row">
                    <!-- First Card -->
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Receipt Table</h5>
                            </div>
                            <div class="card-body">
                                <table class="table datatable-fixed-both-getdata table-zebra" id="gettable">
                                    <thead>
                                        <tr>
                                            <th style="display:none;">Reference No</th>
                                            <th>Bank</th>
                                            <th>Branch</th>
                                            <th>Cheque no</th>
                                            <th style="text-align:right;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Add dynamic rows here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


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
<script src="{{Module::asset('cb:js/cheque_collection_by_ho.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection