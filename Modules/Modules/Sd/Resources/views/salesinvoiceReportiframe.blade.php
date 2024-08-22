@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>

</style>
@endsection

@section('content')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <title>Laravel</title>

</head>
<style>
    tr.highlighted {
        background-color: #E2F7FB !important;
        font-weight: bold;
    }

    input[type="radio"] {
        width: 18px;
        height: 18px;
    }

    /* Style the label for the radio buttons (optional) */
    label {
        margin-right: 10px;

        /* Add some spacing between the label and the radio button */
    }

    ul.list-group li {
        border: none;
    }
</style>

<body>

    <br>
    <div class="card">
        <div class="card-header bg-darkr" style="color: white;background-color: #252b36;">
            <div class="row">
                
                <div class="col-md-1">
                    <button type="button" id="btnPrint" class="btn btn-primary" style="width: 100%;">Print</button>
                </div>
                
            </div>
        </div>
        

    </div>

    <div class="row" style="background-color: gray;">
        <div class="col-md-12" style="text-align: center;">
            <iframe id="pdfContainer" src="" style="min-width: 60%; min-height: 750px;background-color: white;margin-top: 30px;;"></iframe>
        </div>
    </div>
</body>

</html>

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


<!-- <script src="/assets/report.js"></script> -->

@endsection
@section('scripts')
<script src="{{Module::asset('sd:js/salesinvoiceReport.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesinvoiceReportiframe.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection