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
    <style>
        .rightAlign{
            text-align: right;
        }
    </style>

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Bin Card </h5>
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

                    <div class="col-3" style="margin-left: 10px;">
                        <label class="form-label">Location</label>
                        <select id="cmbLocation" name="location" class="select2 form-control validate">

                        </select>
                    </div>
                    <!-- <div class="col-3" style="margin-left: 10px;">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <span class="input-group-text" style="height: 36px;"><i class="ph-calendar"></i></span>
                        <input type="text" name="date_range" id="date_range" class="form-control daterange-single" style="height:55px !important;">
                    </div>

                   
                </div> -->
                    <div class="col-2" style="margin-left: 45px;">
                        <label class="form-label">From</label>
                        <div class="input-group">
                            <input id="date_from" type="text" class="form-control daterange-single">
                           
                        </div>

                    </div>
                    <div class="col-2" style="margin-left: 45px;">
                        <label class="form-label">To</label>
                        <div class="input-group">
                            <input id="date_to" type="text" class="form-control daterange-single">
                           
                        </div>

                    </div>
                 
                    <div class="col-6" style="margin-left: 45px;">
                        <label class="form-label">Product</label>
                        <select id="cmbItem" name="cmbproduct" class="form-control validate select2">

                        </select>
                    </div>
                </div>
            </div>
            <!-- <div class="col-12">
                <div class="row" style="margin-top: 15px; margin-left:100px">



                </div>
            </div>
            <div class="col-12">
                <div class="row" style="margin-top: 15px; margin-left:100px">


                  



                </div>
            </div>
 -->
            
          
            <div class="row" style="margin-top: 15px;">

                <div class="col-md-12">
                    <table class="table datatable-button-html5-name table-striped" id="bin_card_table">
                        <thead>
                            <tr>
                              <!--   <th>Item Code</th>
                                <th>Item Name </th> -->
                                <th>Date</th>
                                <th>Reference No</th>
                                <th>Description </th>
                                <th>In Qty</th>
                                <th>Out Qty</th>
                                <th>Balance</th>
                                <th>W.Price</th>
                                <th>Retial Price</th>
                                <th>Reference External No</th>
                                {{-- <th>User</th> --}}
                                
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
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/datatables_extension_buttons_excel.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('sc:js/binCard.js')}}?random=<?php echo uniqid(); ?>"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script> -->
@endsection