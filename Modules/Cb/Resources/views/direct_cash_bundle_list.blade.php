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
                <h5 class="mb-0">Direct Cash Bundle List</h5>
            </div>
            <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/cb/direct_cash_bundle" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div>
            <div class="row" id="top_border">
                 
                <div class="col-md-2" style="margin-left: 10px;margin-top: 10px">
                    <select class="form-select" id="cmbBranch">
                        <option>Select Branch</option>
                    </select>

                </div>


               
            </div>

            <div class="row">

                <div class="col-md-12">

                    <table class="table datatable-fixed-both table-striped" id="direct_cash_bundle_list" style="table-layout:fixed">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference #</th>
                                <th style="text-align:right;">Amount</th>
                                <th>Collector</th>
                                <th>Branch</th>
                                <th>Action</th>
                               <!--  <th>Print</th> -->


                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>

          <!--   <div class="col-md-4" style="margin-left: 10px;margin-bottom:5px;float:right;">
                <button type="button" id="btn_cash_ho_save" class="btn btn-primary">Save</button>

            </div> -->
        </div>
    </div>
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->
@include('cb::direct_cash_bundle_info_modal')

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
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('cb:js/direct_Cash_bundle_list.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection