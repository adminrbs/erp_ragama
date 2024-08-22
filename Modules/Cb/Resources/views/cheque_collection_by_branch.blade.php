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
                <h5 class="mb-0">Receipt for cheque Collection By Branch</h5>
            </div>
            <div class="row" id="top_border">
            <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                    <select class="form-select" id="cmbBranch">
                        <option>Select Branch</option>
                    </select>

                </div>
                <!-- div class="col-md-3" style="margin-left: 50px; margin-top: 10px">
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

                </div> -->

               <!--  <div class="col-md-2" style="margin-top: 10px;margin-left: 10px;">
                    <input type="date" name="cashDate" id="cashDate" class="form-control">
                </div> -->
            </div>

            <div class="row">

                <div class="col-md-12">

                    <table class="table datatable-fixed-both table-striped" id="cheque_collection_by_branch_table" style="table-layout:fixed">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference #</th>
                                <th>Customer</th>
                                <th style="text-align:right">Amount</th>
                                <th>CHQ Date</th>
                                <th>Bank</th>
                                <th>Branch</th>
                                <th>CHQ No</th>
                                <th>Action</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->

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
<script src="{{Module::asset('cb:js/receipt_for_cheque.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection