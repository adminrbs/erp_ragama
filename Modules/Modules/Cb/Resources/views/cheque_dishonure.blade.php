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
                <h5 class="mb-0">Cheque Return</h5>
            </div>
            <div class="row" id="top_border">
          <!--   <div class="col-md-3" style="margin-left: 10px;margin-top: 10px">
                    <select class="form-select" id="cmbAccountNumber">
                        <option>Select Account</option>
                    </select>

            </div> -->
            <div class="col-md-3" style="margin-left: 10px;margin-top: 10px">
                   <input type="date" class="form-control" id="dtBankingDate">

            </div>
               
                
              <!--   <div class="col-md-2" style="margin-left: 50px; margin-top: 10px">
                    <h4>Number Of Selected:</h4>

                </div> -->
              <!--   <div class="col-md-2" style=" margin-top: 10px" id="">
                    <h4 id="row_count">0</h4>

                </div> -->

                <div class="col-md-2" style="margin-top: 10px;margin-left: 10px;display:none;">
                    <input type="date" name="cashDate" id="cashDate" class="form-control">
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">

                    <table class="table datatable-fixed-both table-striped" id="cheque_dishonur_table" style="table-layout:fixed">
                        <thead>
                            <tr>
                                <th>Receipt Date</th>
                                <th>CHQ Banked Date</th>
                                <th>Deposit Date</th>
                                <th>Bank Name</th>
                                <th>Reference #</th>
                                <th>Customer</th>
                                <th style="text-align: right;">Amount</th>
                                <th>CHQ No</th>
                                <th>Return Reason</th>
                                <th>Bank Charges</th>
                                <th>Action</th>
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
<script src="{{Module::asset('cb:js/cheque_dishonour.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection