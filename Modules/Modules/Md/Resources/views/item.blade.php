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
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Item</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a href="#general" class="nav-link active" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#settings" class="nav-link" aria-selected="true">Settings</a>
                        </li>
                        <!-- <li class="nav-item rbs-nav-item">
                            <a href="#price" class="nav-link" aria-selected="false">Price</a>
                        </li> -->
                        <li class="nav-item rbs-nav-item">
                            <a href="#Attachments" class="nav-link" aria-selected="false" style="display: none;">Attachments</a>
                        </li>
                        <!--   <li class="nav-item rbs-nav-item">
                            <a href="#picture" class="nav-link" aria-selected="false">Picture</a>
                        </li> -->

                        <li class="nav-item rbs-nav-item">
                            <a href="#note" class="nav-link" aria-selected="false">Note</a>
                        </li>


                    </ul>
                    <!--enf of tabs -->
                    <!-- staring of form -->
                    <form id="frmItem" class="needs-validation" novalidate>

                        <div class="tab-content">
                            <!-- General tab -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="row">

                                    <div class="row">
                                        <h1>General</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Item Code <span class="text-danger">*</span></label>

                                                <div>

                                                    <input class="form-control form-control-sm validate" type="text" id="txtItemCode" name="itemcode" autocomplete="new-search">


                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Item Name <span class="text-danger">*</span></label>

                                                <div>

                                                    <input class="form-control form-control-sm validate" type="text" id="txtName" name="txtName" autocomplete="off" required>


                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Description </label>

                                                <div>

                                                    <input class="form-control form-control-sm validate" type="text" id="txtDescription" name="description">


                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>International nonproprietary name (INN)</label>

                                                <select class="select2 form-control" id="cmbInn" name="inn">

                                                </select>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>SKU</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtSKU" name="sku">

                                                </div>
                                            </div>
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Barcode </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtBarcode" name="barcode">

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Unit of measure</label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtUnitOfMeasure" name="unitofmeasure">

                                                </div>
                                            </div>
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Whole Sale Price </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtWholeSalePrice" name="wholesaleprice">

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Retail Price</label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtRetailPrice" name="retailprice">

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Average Cost Price </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtAverageCostPrice" name="averagecostprice">

                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Storage requirements </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtStorageRequirements" name="storagerequirements">
                                                </div>



                                            </div>


                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Pieces per pack </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtPackageSize" name="numbers">

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Pack Size <span class="text-danger">*</span></label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtPackageUnit" name="packageunit">
                                                </div>

                                            </div>
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Supply group <span class="text-danger">*</span> </label>

                                                <div>
                                                    <select class="select2 form-control" name="supplygroup" data-live-search="true" id="cmbSupplyGroup">

                                                    </select>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Category level 1 </label>
                                                <div>
                                                    <select class="select2 form-control" name="categorylevel1" data-live-search="true" id="cmbCategoryLevel1">

                                                    </select>

                                                </div>

                                            </div>
                                            <div class="mb-1">



                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Category level 2 </label>

                                                <div>
                                                    <select class="select2 form-control" name="categorylevel2" data-live-search="true" id="cmbCategoryLevel2">

                                                    </select>

                                                </div>
                                                <label class="col-form-label mb-0 mt-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Category level 3 </label>
                                                <div>
                                                    <select class="select2 form-control" name="categorylevel3" data-live-search="true" id="cmbCategoryLevel3">

                                                    </select>

                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Active </label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkActive">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- End of general tab -->
                            <!-- price tab -->
                            <div class="tab-pane fade show" id="price">
                                <div class="row">

                                    <div class="row">
                                        <h1>Price</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Description </label>


                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtPriceDescription" name="pricedescription">

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>whalesale price </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtWhalesalePrice" name="whalesaleprice">

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Retail Price </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtRetailPrice" name="retailprice">

                                                </div>


                                            </div>


                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- setting tab -->
                            <div class="tab-pane fade" id="settings">
                                <div class="row">

                                    <div class="row">
                                        <h1>Settings</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Minimum order quantity </label>


                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtMinimumOrderQquantity" name="numbers">

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Maximum order quantity </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtMaximumOrderQuantity" name="numbers">

                                                </div>


                                            </div>
                                            <div class="row mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Reorder level </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtReorderLevel" name="numbers">

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Reorder quantity</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtReorderQuantity" name="numbers">

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Payment Terms</label>

                                                <div>
                                                <select  class="form-select " id="cmbPaymentTerm" data-placeholder="Select Payment Term">
                                                   
                                                </select>

                                                </div>


                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Minimum margin %</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number" id="txtMinimum_margin" name="numbers">

                                                </div>




                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">


                                            <div class="row mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Manage Batch </label>

                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkManageBatch">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Manage expire date </label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkManageExpireDate">
                                                        <span class="form-check-label"></span>
                                                    </label>
                                                </div>

                                            </div>
                                            <div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Allowed free quantity </label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkAllowedFreeQuantity">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Allowed discount </label>
                                                <div class="col-md-4">
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkAllowedDiscount">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Allowed promotion </label>
                                                <div class="col-md-4">
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chlAllowedPromotion">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Allowed Payment Term </label>
                                                <div class="col-md-4">
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkAllowPaymentTerm">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- attachment tab -->
                            <div class="tab-pane fade show" id="Attachments">
                                <div class="row">

                                    <div class="row">
                                        <h1>Attachments</h1>

                                        <div class="mb-4">
                                            <button type="button" class="btn btn-primary btn-icon" id="bootbox_form">Attach <i class="ph-link"></i></button>
                                            <table id="Attachments" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Attachment</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- Note tab -->
                            <div class="tab-pane fade show" id="note">
                                <div class="row">

                                    <div class="row">
                                        <h1>Note</h1>

                                        <div class="col-md-6 mb-4">

                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Note</label>
                                                <div>
                                                    <textarea class="form-control form-control-sm validate" rows="4" name="note" id="txtnote"></textarea>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- picture tab -->
                            <div class="tab-pane fade show" id="picture">
                                <div class="row">

                                    <div class="row">
                                        <h1>Price</h1>

                                        <div class="mb-4">


                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4 mb-2">
                                <button type="submit" id="btnSave" class="btn btn-primary form-btn btn-sm">Save</button>
                                <button type="button" id="btnReset" class="btn btn-warning form-btn btn-sm">Reset</button>
                            </div>
                        </div>

                    </form>
                    <!-- end of form -->

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
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>


<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<!-- <script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script> -->
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_buttons.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>





@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<script src="{{Module::asset('md:js/item.js')}}?random=<?php echo uniqid(); ?>"></script>
<!-- <script src="{{URL::asset('assets/js/customer.js')}}"></script> -->


@endsection