@extends('layouts.master')
@section('page-header')
@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent
@endsection
@section('content')


<style>
    /* Existing styles for card_div */
    .card_div {
        transition: transform 0.5s ease;
    }

    .card_div:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    /* New styles for card headers */
    .order_header {
        border: none;
        border-radius: 10px;
        background: linear-gradient(45deg, #045D4E,#059669); /* Gradient background */
        color: white;
        padding: 15px;
        position: relative;
    }
    .pending_header {
        border: none;
        border-radius: 10px;
        background: linear-gradient(45deg, #0056b3,#0C83FF); /* Gradient background */
        color: white;
        padding: 15px;
        position: relative;
    }
    .missed_header {
        border: none;
        border-radius: 10px;
        background: linear-gradient(45deg, #D66433,#F58646); /* Gradient background */
        color: white;
        padding: 15px;
        position: relative;
    }
    .late_header {
        border: none;
        border-radius: 10px;
        background: linear-gradient(45deg,#DC2626,#EF4444); /* Gradient background */
        color: white;
        padding: 15px;
        position: relative;
    }

    .card-header h6 {
        margin: 0;
    }

    /* New styles for card bodies */
    .card-body {
        padding: 20px;
    }

    /* Style for the display-4 heading inside card bodies */
    .card-body h1.display-4 {
        font-size: 2.5rem;
    }

    /* Hover effect for card bodies */
    .card_div:hover .card-body {
        background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent white background on hover */
    }
</style>


<!-- Content area -->
<div class="content">
    <!-- Dashboard content -->
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Dashboard</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card-body d-sm-flex align-items-sm-center justify-content-sm-center flex-sm-wrap">
                    <div class="col-8 text-center">
                        <div class="row">
                            <div class="col-3 mb-3 card_div">
                                <a href="/sd/getSalesOrderList">
                                <div class="card bg-success text-white h-100" data-color-theme="dark">
                                    
                                    <div class="card-header border-white border-opacity-20 order_header">
                                    
                                    <div class="row">
                                    <h6 class="mb-0"><i class="fa fa-shopping-bag" aria-hidden="true"></i>&nbsp;Orders</h6>
                                    </div>
                                    </div>

                                    <div class="card-body">
                                        <h1 id="lbl_order_count" class="display-4 mb-0" style="font-weight: bold;"></h1>
                                        <p id="order_val"></p>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3 card_div">
                            <a href="/sd/pending_sales_orders">
                                <div class="card bg-primary text-white h-100" data-color-theme="dark">
                                    <div class="card-header border-white border-opacity-20 pending_header">
                                        <h6 class="mb-0"><i class="fa fa-thumb-tack" aria-hidden="true"></i>&nbsp;Pending Orders</h6>
                                    </div>
                                    <div class="card-body">
                                        <h1 id="lbl_pending_to_deliver" class="display-4 mb-0" style="font-weight: bold;"></h1>
                                        <p id="pending_val"></p>
                                    </div>
                                </div>
                            </a>
                            </div>
                        

                        
                            <div class="col-3 mb-3 card_div">
                                <a href="/sd/late_sales_orders">
                                <div class="card bg-danger text-white h-100" data-color-theme="dark">
                                    <div class="card-header border-white border-opacity-20 late_header">
                                        <h6 class="mb-0"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Late Orders</h6>
                                    </div>
                                    <div class="card-body">
                                            <h1 id="lbl_late_orders" class="display-4 mb-0" style="font-weight: bold;"></h1>
                                            <p id="late_val"></p>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-3 mb-3 card_div">
                                <a href="/sd/missed_sales_order_list">
                                <div class="card bg-warning text-white h-100" data-color-theme="dark">
                                    <div class="card-header border-white border-opacity-20 missed_header">
                                        <h6 class="mb-0"><i class="fa fa-exclamation" aria-hidden="true"></i>&nbsp;Missed Orders</h6>
                                    </div>
                                    <div class="card-body">
                                        <h1 id="lbl_missed_orders" class="display-4 mb-0" style="font-weight: bold;"></h1>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /dashboard content -->
</div>

<!-- /content area -->

@endsection
@section('center-scripts')
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/dashboard.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection
@section('scripts')

@endsection