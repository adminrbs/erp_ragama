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
    <!-- Dashboard content -->
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Request List</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card-body d-sm-flex align-items-sm-center justify-content-sm-center flex-sm-wrap">
                    <div class="row">
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>UserName</th>
                                    <th>Browser</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_request"></tbody>
                        </table>
                    </div>
                </div>


                <!-- Modal structure -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Request Approval</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="hid_request_id">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label>Status</label>
                                        <select class="select form-control" id="cmbRequest">
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="div_num_time">
                                    <div class="col-md-12 mb-3">
                                        <label>Time(MM)</label>
                                        <input type="number" class="number form-control" value="1" id="num_time">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="confirmRequest()">Confirm</button>
                            </div>
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
<script src="{{URL::asset('assets/js/approval_request_list.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection
@section('scripts')

@endsection