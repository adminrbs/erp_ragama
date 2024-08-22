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
                <h5 class="mb-0">Missed Sales Orders List</h5>
            </div>
            <!-- <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/sd/salesOrder" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div> -->

            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="missed_sales_orders" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Reference #</th>
                                <th>Invoice Date</th>
                                <th>Invoice Number</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Pacs</th>
                                <th>Order Qty</th>
                                <th>Missed Order Qty</th>
                                <!-- <th>Amount</th>
                                <th>Status</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>


            <div class="col-1" style="margin-left: 5px;">
                <button class="btn btn-primary" type="button" style="width: 100%;" id="btnSave">Save</button>
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
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!--  <script src="{{URL::asset('assets/js/customerList.js')}}"></script>  -->
<script src="{{Module::asset('sd:js/missed_sales_orders_list.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection