
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
    <div class="card">
        <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
            <h5 class="mb-0">User List</h5>
            <div class="d-inline-flex ms-auto"></div>
        </div>
        <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/st/user" class="btn btn-primary" target="_blank">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div>

        <table class="table datatable-fixed-both table-striped" id="userListTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>USER NAME</th>
                    <th>EMAIL</th>
                    <th>USER ROLE</th>
                    <th>USER TYPE</th>
                    <th>EDIT</th>
                    <th>DELETE</th>

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

<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/ui/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/pickers/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/pickers/datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/uploaders/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>


@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('st:js/userList.js')}}?random=<?php echo uniqid(); ?>"></script>
@endsection
