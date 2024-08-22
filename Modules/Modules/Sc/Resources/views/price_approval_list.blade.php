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
                <h5 class="mb-0">Price Approval List</h5>
            </div>
            <div class="row col-3" style="margin-left: 10px; margin-top: 10px;">
                <div class="col-md-4">
                    <label class=" col-form-label mb-0">Locations :</label>
                </div>
                <div class="col-md-8" style="display: flex; align-items: center;">
                    <select id="cmbLocation" class="form-select"></select>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="price_approval_list" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th>Received Date</th>
                                <th>Reference #</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Pacs</th>
                                <th>Qty</th>
                                <th>Location</th>
                                <th>Wh. Price</th>
                                <th>Retail Price</th>
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
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!--  <script src="{{URL::asset('assets/js/customerList.js')}}"></script>  -->
<script src="{{Module::asset('sc:js/price_approval_list.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection