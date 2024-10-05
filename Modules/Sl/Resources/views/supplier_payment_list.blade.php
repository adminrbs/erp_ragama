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
                <h5 class="mb-0">Supplier Payment List</h5>
            </div>
            <div class="col-md-3" style="margin-left: 10px;margin-top:5px;">
                <a href="/sl/supplier_payment" class="btn btn-primary" target="_blank">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="supplier_payment_list" style="table-layout:fixed;">
                        <thead>
                            <tr>
                                <th>Reference #</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th style="text-align: right;">Amount</th>
                                <th>CHQ No</th>
                                <th>Banking Date</th>
                                <th>Payment Mode</th>
                                <th>Action Menu</th>
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
<script src="{{Module::asset('sl:js/supplier_payment_list.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sl:js/supplier_receipt_report.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection