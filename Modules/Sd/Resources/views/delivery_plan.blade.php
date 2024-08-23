@extends('layouts.master')
@section('page-header')
@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')

<style>
    .right-align {
        text-align: right;
    }

    .center-align {
        text-align: center;
    }

    .col-width {
        max-width: 150px;
        min-width: 150px;
    }

     
    
</style>

<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0" id="pageName"></h5>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-4">
                        <button class="btn btn-primary" id="btnAdd">+Create New</button>
                    </div>
                    
                    <div class="col-md-8" >
                    
                    <button class="btn btn-primary" id="btnView_postpond" style="float: right !important;">Postponed Invoices</button>
                    <button class="btn btn-primary" id="btnView_non_allocated" style="float: right !important;margin-right :5px;">Non-Allocated Invoices</button>  
                        
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table datatable-fixed-both-delivery-plan table-striped batch-table" id="deliveryPlanTable">
                            <thead>
                                <tr>
                                    <th>Ref No #</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Helper</th>
                                    <th>No of Invoice</th>
                                    <th>Route List</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->
@include('sd::delivery_plan_modal')
@include('sd::delivery_plan_invoice_modal')
@include('sd::delivery_plan_invoice_list_modal')
@include('sd::delivery_plan_picking_list_modal')
@include('sd::delivery_plan_postpone_modal')
@include('sd::deliver_plan_non_allocated_list_model')
@include('sd::deliveryplan_postpond_list_model')
@include('sd::blockCustomerInfoModel')
@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/delivery_plan_modal.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/delivery_plan_invoice_modal.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/delivery_plan_invoice_list_modal.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/delivery_plan_picking_list_modal.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/delivery_plan_postpone_modal.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/delivery_plan.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection