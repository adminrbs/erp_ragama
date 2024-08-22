
@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var md_edit_employee = "{{Auth::user()->can('md_edit_employee')}}";
    var md_delete_emloyee = "{{Auth::user()->can('md_delete_emloyee')}}";
    var md_view_employee = "{{Auth::user()->can('md_view_employee')}}";

    
</script>

@endsection

@section('content')


<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card">
        <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
            <h5 class="mb-0">Employee List</h5>
            <div class="d-inline-flex ms-auto"></div>
        </div>
        <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
        @if(Auth::user()->can('md_add_employee') && Auth::user()->hasModulePermission('Master Data'))
                <a href="/md/employee" class="btn btn-primary" target="">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
                @endif
            </div>

        <table class="table datatable-fixed-both table-striped" id="employeeListTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>EMP Code</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Mobile</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody >

            </tbody>
        </table>
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
<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>


@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('md:js/employeeList.js')}}?random=<?php echo uniqid(); ?>"></script>
@endsection
