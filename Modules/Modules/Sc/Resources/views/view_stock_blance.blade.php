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
                <h5 class="mb-0">Stock Blance </h5>
            </div>
            <br>
            <br>
            <div class="col-12">
                <div class="row" style="margin-top: 15px; margin-left:100px">
                <div class="col-3" style="margin-left: 45px;">
                        <label class="form-label">Branch</label>
                        <select id="cmbBranch" name="Branch" class="select2 form-control validate">

                        </select>
                    </div>
                    <div class="col-3" style="margin-left: 45px;">
                        <label class="form-label">Location</label>
                        <select id="cmbLocation" name="location" class="select2 form-control validate">

                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row" style="margin-top: 15px; margin-left:100px">


                    <div class="col-2" style="margin-left: 45px;">
                        <label class="form-label">As At give date</label>
                        <div class="input-group">
                            <input id="txtDateofTo" type="text" class="form-control daterange-single">
                            <!--<input id="txtDateofTo" type="date" class="form-control">-->
                        </div>

                    </div>
                    <div class="col-1">
                        <label class="form-label">Filter by</label>
                        <select id="cmbAny" name="cmbAny" class="form-select validate">
                            <option value="1">Any</option>
                            <option value="0">As At give date</option>
                        </select>
                    </div>
                    <div class="col-3" style="margin-left: 45px;">
                        <label class="form-label">Product</label>
                        <select id="cmbproduct" name="cmbproduct" class="form-control validate select2">

                        </select>
                    </div>

                    <div class="col-3" style="margin-left: 45px;">
                        <label class="form-label">Supply Group</label>
                        <select id="cmbSupplyGroup" name="cmbSupplyGroup" class="select2 form-control validate">

                        </select>
                    </div>

                </div>
            </div>
            <div class="col-12">
                <div class="row" style="margin-top: 15px; margin-left:100px">


                    <div class="col-3" style="margin-left: 45px;">
                        <label class="form-label">Item Category Level 1</label>
                        <select id="cmbcategory1" name="category1" class="form-control validate select2">


                        </select>
                    </div>
                    <div class="col-3" style="margin-left: 45px;">
                        <label class="form-label">Item Category Level 2</label>
                        <select id="cmbcategory2" name="category2" class="form-control validate select2">


                        </select>
                    </div>
                    <div class="col-3" style="margin-left: 45px;">
                        <label class="form-label">Item Category Level 3</label>
                        <select id="cmbcategory3" name="category3" class="form-control validate select2">


                        </select>
                    </div>



                </div>
            </div>

            <br>
            <br>
            <div class="row" style="margin-top: 15px;">

                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="stock_blance_table">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name </th>
                                <th>Quantity</th>
                                <th>Reorder level</th>
                                <th>U.O.M</th>

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
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/datatables_extension_buttons_excel.js')}}"></script> -->
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('sc:js/view_stock_blance.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection