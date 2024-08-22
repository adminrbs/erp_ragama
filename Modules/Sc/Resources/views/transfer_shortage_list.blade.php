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
            <h5 class="mb-0">Divisinol Transfer Shortage</h5>
        </div>
        <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <!-- <a href="/sc/dispatch_to_branch" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a> -->

            </div>
            <div class="row" id="top_border" style="margin-left: 10px;">
    <div class="col-md-1">
        <label for="cmbFromBranch" class="form-label">From Branch:</label>
    </div>
    <div class="col-md-2">
        <select class="select2 form-control validate" id="cmbFromBranch">
            <option value="0">Any</option>
        </select>
    </div>

    <div class="col-md-1">
        <label for="cmbToBranch" class="form-label">To Branch:</label>
    </div>
    <div class="col-md-2">
        <select class="form-select" id="cmbToBranch">
            <option value="0">Any</option>
        </select>
    </div>
</div>


        <div class="row">
            <div class="col-md-12">
            <table class="table datatable-fixed-both table-striped" id="transfer_shortage_list_table">
                <thead>
                    <tr>  
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Item Name</th>
                        <th>Item Code</th>
                        <th>Pack Size</th>
                        <th>QTY</th>
                        <th>R.QTY</th>
                        <th>Balance</th>
                        <th>Fr.Branch</th>
                        <!-- <th>FrLocation</th> -->
                        <th>To Branch</th>
                        <th>Received By</th>
                        <th>Received Date</th>
                        <!-- <th>To Location</th> -->
                       
                       <!--  <th>Action</th> -->
                        
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
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('sc:js/transfer_shortage_list.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection