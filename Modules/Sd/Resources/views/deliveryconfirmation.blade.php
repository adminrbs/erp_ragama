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
                <h5 class="mb-0">Delivery Confirmation</h5>
            </div>
            
            <div class="col-md-3" style="margin-left: 10px;margin-top:5px">

                <label class="col-form-label">Delivery Plan</label>
                <select class="select2 form-control validate" name="cmbdelevery_plan" data-live-search="false" id="cmbdelevery_plan">

                </select>


            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="delivery_confirmation_table">
                        <thead>
                            <tr>
                            <th>Invoice Date</th>
                                <th>Sales Invoice</th>
                                <th>Customer</th>
                                <th>Invoice Amount</th>
                                <th>Sales Order number</th>
                              
                                <th>Loading Number</th>
                               
                                <th>Invoice Add User</th>
                                <th>Sales Order Add User</th>
                                <th>Loading Add User</th>
                                
                                <th>Sales Rep</th>
                                <th>Route</th>   
                                <th>Delivered</th>
                                <th>Seal</th>
                                <th>Signature</th>
                                <!--  <th>No Seal</th> -->
                                <th>Cash</th>
                                <th>Cheque</th>
                                <th>No Seal</th>
                                <th>Cancel</th>
                                <th>PL No</th>
                                
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="row">
        <div class="col-3" style="margin-left:10px; width: 75%;">
           <input type="button" class="btn btn-primary" value="Save" id="save_confirmation">
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
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!--  <script src="{{URL::asset('assets/js/customerList.js')}}"></script>  -->
<script src="{{Module::asset('sd:js/deliveryconfirmation.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection