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
            <h5 class="mb-0">Location List</h5>
        </div>
        <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/md/location" class="btn btn-primary" target="_blank">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div>

        <div class="row">
            <div class="col-md-12">
            <table class="table datatable-fixed-both table-striped" id="locationTable">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Location Name</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Active</th>
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
<script src="{{Module::asset('md:js/locationList.js')}}?random=<?php echo uniqid(); ?>"></script> 
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection