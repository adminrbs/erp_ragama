@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .text-right {
        text-align: right;
    }
</style>

<script>
    var md_edit_item = "{{Auth::user()->can('md_edit_item')}}";
    var md_delete_item = "{{Auth::user()->can('md_delete_item')}}";
    var md_view_item = "{{Auth::user()->can('md_view_item')}}";
</script>
@endsection

@section('content')


<!-- Content area -->
<div class="content">

    <!-- Multiple fixed  -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Item List</h5>
            </div>
            <div class="col-md-3" style="margin-left: 30px;margin-top:5px">
                @if(Auth::user()->can('md_add_item') && Auth::user()->hasModulePermission('Master Data'))
                <a href="/md/item" class="btn btn-primary" target="">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
                @endif
            </div>
            <div class="mt-2">
                <div class="card">
                    <div class="card-header text-secondary" id="headingDesignation" style="background-color:#F5F5F5">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-bs-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-gear" style="margin-left: 1px"></i>&nbsp;&nbsp;Advanse Filter
                            </button>
                        </h5>
                    </div>
                    <div id="filter" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="row" style="margin-top: 15px; margin-left:100px">

                                    <div class="col-3" style="margin-left: 45px;">
                                        <label class="form-label">Supply group </label>
                                        <select id="cmbSupplyGroup" name="cmbSupplyGroup" class="select2 form-control validate">

                                        </select>
                                    </div>

                                    <div class="col-3" style="margin-left: 45px;">
                                        <label class="form-label">Status</label>
                                        <select id="cmbstatus" name="status" class="form-select validate">
                                            <option value="0" selected>Any</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-3" style="margin-left: 45px;">
                                        <label class="form-label">Category level 1 </label>
                                        <select id="cmbcategory1" name="category1" class="form-control validate select2">


                                        </select>
                                    </div>






                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row" style="margin-top: 15px; margin-left:100px">



                                    <div class="col-3" style="margin-left: 45px;">
                                        <label class="form-label">Category level 2</label>
                                        <select id="cmbcategory2" name="category2" class="form-control validate select2">


                                        </select>
                                    </div>
                                    <div class="col-3" style="margin-left: 45px;">
                                        <label class="form-label">Category level 3</label>
                                        <select id="cmbcategory3" name="category3" class="form-control validate select2">


                                        </select>
                                    </div>



                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="itemTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Unit</th>

                                <th>Supply Group</th>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!--  <script src="{{URL::asset('assets/js/customerList.js')}}"></script>  -->
<script src="{{Module::asset('md:js/itemList.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection