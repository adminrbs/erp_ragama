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
    .text-right{
  text-align: right;
}
</style>

<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Block Release List</h5>
            </div>
            <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                
            </div>
            <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
            <select class="select2 form-control validate" id="cmbEmp">
                        <option value="0">Any</option>
                    </select>

                </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="customer_block_list_table" style="table-layout:fixed">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Town</th>
                                <th>Route</th>
                                <th>Employee Name</th>
                                <th>Orders Value</th>
                                <th>Status</th>
                                <th>Info</th>
                                <th>Orders</th>
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
@include('sd::blockCustomerModel')
@include('sd::blockCustomerInfoModel')
@include('sd::block_orders_model')
@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!--  <script src="{{URL::asset('assets/js/customerList.js')}}"></script>  -->
<script src="{{Module::asset('sd:js/customer_block_list.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection