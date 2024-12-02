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
<style>
    .table thead.thead-custom {
        background: linear-gradient(90deg, #a8c8fb, #c3a7e3); /* Lighter gradient */
        color: #333;
    }

    .zebra-table tbody tr:nth-child(odd) {
            background-color: #f2f2f2; /* Light gray */
        }
        .zebra-table tbody tr:nth-child(even) {
            background-color: #e0f7fa; /* Light blue */
        }
        .zebra-table tbody tr:hover {
            background-color: #b0e0e6; /* Slightly darker light blue on hover */
        }
        
</style>

<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Dashboard</h5>
            </div>
            <div class="row" id="top_border">
                <div class="col-md-3" style="margin-left: 10px;margin-top: 10px">
                    <!--   <select class="form-select" id="cmbAccountNumber">
                        <option>Select Account</option>
                    </select> -->

                </div>
                <div class="col-md-3" style="margin-left: 10px;margin-top: 10px">
                    <!--  <input type="date" class="form-control" id="dtBankingDate"> -->

                </div>


            </div>

            <div class="row">

                <div class="col-md-6">
                    <h3>Collection Status</h3>
                    <table class="table table-striped table-hover zebra-table" id="all_cash">
                        <thead class="thead-custom">
                            <tr style="height:82px;">
                                <th></th>
                                <th style="color:#4b0082;">Total</th>
                                <th style="color:#4b0082;">Late</th>
                            </tr>
                        </thead>
                        <tbody>



                        </tbody>
                    </table>


                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-4">
                            <h3>Collector Wise Cash</h3>

                        </div>
                        <div class="col-4">
                            <select name="cmbEmp" id="cmbEmp" class="form-select">
                            </select>
                        </div>
                    </div>


                    <table class="table table-striped table-hover zebra-table" id="collector_wise_cash">
                        <thead class="thead-custom">
                            <tr>
                                <th rowspan="2">Employee</th>
                                <th colspan="2" style="text-align: center;color:#4b0082;">Total</th>
                                <th colspan="2" style="text-align:center;">Late</th>
                            </tr>
                            <tr>
                                
                                <th style="text-align:right;color:#4b0082;">Cash</th>
                                <th style="text-align:right;color:#4b0082;">Cheque</th>
                                <th style="text-align:right;">Cash</th>
                                <th style="text-align:right;">Cheque</th>
                            </tr>
                        </thead>
                        <tbody style="text-align:right">



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
<script src="{{Module::asset('cb:js/dashboard.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection