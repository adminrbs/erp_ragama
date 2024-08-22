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
                <h5 class="mb-0">Pending Order List</h5>
            </div>
            <!-- <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/sd/salesOrder" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a> -->
            </div>
            <div class="col-8" id="adDistributor">
                
                <div class="col-md-4" style="text-align: center; border: 0px solid #ffffff; padding: 20px;">
                    <label class="transaction-lbl mb-0" style="width: 100%; text-align: left;"><span>Branch</span></label>
                    <select class="form-select select2" id="cmbBranch"></select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-button-html5-name table-striped" id="sales_oderTable">
                        <thead>
                            <tr>
                                <th>Reference #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Sales Rep</th>
                                <th>Order type</th>
                                <th>Deliver on</th>
                                <th>Amount</th>
                                <th>Status</th>
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
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>

<script src="{{Module::asset('sd:js/pending_sales_oder_list.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesOrderReport.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection