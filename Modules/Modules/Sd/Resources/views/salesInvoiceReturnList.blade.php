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
            <h5 class="mb-0">Sales Return List</h5>
        </div>
        <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/sd/salesReturn" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div>

        <div class="row">
            <div class="col-md-12">
            <table class="table datatable-fixed-both table-striped" id="sales_return_table">
                <thead>
                    <tr>  
                        <th>Reference #</th>
                        <th>Date</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Sales Rep</th>
                        <th>Amount</th>
                        <th>Your Ref number</th>
                        <th>Status</th>
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
@include('sd::sales_return_debtor_ledger_setoff_info_model');


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
<script src="{{Module::asset('sd:js/salesInvoiceReturnList.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesReturnReport.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection