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
                <h5 class="mb-0">Delivery Routes</h5>
            </div>
            <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#routeModel" id="btnRouteModel">
                    <i class="fa fa-plus" aria-hidden="true"></i>
            </button>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="route_list">
                        <thead>
                            <tr>
                                <th>Route Name</th>
                                <th>Route Order</th>
                                <th>Town</th>
                                <th>Edit</th>
                                <th>Delete</th>
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
@include('sd::routeModel')
@include('sd::routeTownModel')
@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/dual_listbox.min.js')}}"></script>

@endsection
@section('scripts')

<!--  <script src="{{URL::asset('assets/js/customerList.js')}}"></script>  -->

<script src="{{Module::asset('sd:js/routeList.js')}}?random=<?php echo uniqid(); ?>"></script>


<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection