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
                <h5 class="mb-0">Sales Return Details</h5>
            </div>
            <!-- <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/sd/salesReturn" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div> -->

            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="sales_return_table" style="table-layout:fixed">
                        <thead>
                            <tr>
                                <th>Return Date</th>
                                <th>Reference #</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                               <!--  <th>SO Manual No</th> -->
                                <th>Repname</th>
                             <!--    <th>Book Name</th>
                                <th>Book No</th>
                                <th>Page No</th> -->
                                <th>RTN user</th>
                                <th>Branch</th>
                                <th>Location</th>
                                <th>Reason</th>
                                <th>Item Name</th>
                                <th>Pacs</th>
                                <th>Qty</th>
                                <th>FOC</th>
                                <th>T.Qty</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4" style="margin-left: 10px;margin-bottom:5px;float:right;">
                <input type="button" id="btn_save" class="btn btn-primary" value="Save">
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
<script src="{{Module::asset('sd:js/return_details.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesReturnReport.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection