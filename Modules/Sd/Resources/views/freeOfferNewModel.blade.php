<!-- <---------------------------- Add free offer Modal -------------------------------------------------------------->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Free offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmAddOffer">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label id="hiddnLBLforID" style="display: hidden;"></label>
                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Name <span class="text-danger">*</span> </label>

                            <div>

                                <input class="form-control form-control-sm validate" type="text" id="txtOfferName" name="Offername" autocomplete="new-search" required>

                            </div>
                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Description </label>

                            <div>

                                <input class="form-control form-control-sm validate" type="text" id="txtDescription" name="description" autocomplete="new-search">

                            </div>

                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="mb-1">
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Start date </label>

                                <div>

                                    <input class="form-control form-control" type="date" id="dtStartDate" name="startdate">

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>End date </label>

                                <div>

                                    <input class="form-control form-control" type="date" id="dtEndDate" name="enddate">

                                </div>


                            </div>

                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="mb-1">

                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Apply To </label>

                                <div>

                                    <select class="form-select form-control-sm validate" id="cmbApplyTo">
                                        <option value="1">All</option>
                                        <option value="2">Locations</option>
                                        <option value="3">Customer</option>
                                        <option value="4">Customer grade</option>
                                        <option value="5">Customer group</option>
                                    </select>

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Activate </label>

                                <div>

                                    <label class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkActivate" checked>
                                        <span class="form-check-label"></span>
                                    </label>

                                </div>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSave">Save</button>
            </div>
        </div>
    </div>
</div>



<!------------------------------------------------------- offer  data model ------------------------------------------------>
<div class="modal fade" id="freeOfferDataModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Offer data</h5>
                &nbsp;
                &nbsp;
                <h5 class="modal-title" id="staticBackdropLabelSecodName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmOfferData">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Item </label>

                            <div>


                                <select class="select2 form-control validate" id="cmbItem" data-live-search="true">

                                </select>


                            </div>

                        </div>

                        <div class="col-md-6 mb-4">
                            <label style="display: none;" id="lblofferDatahidden"></label>
                            <label style="display: none;" id="lblofferDatahiddenForID"></label>
                            <div class="mb-1">


                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Offer Type</label>

                                <div>

                                    <select class="form-select" id="cmbofferType">
                                        <option value="1">Free Offering Thresholds</option>
                                        <option value="2">Free offering range</option>


                                    </select>

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Activate </label>

                                <div>

                                    <label class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkActivate_offerData" checked>
                                        <span class="form-check-label"></span>
                                    </label>

                                </div>


                            </div>

                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="mb-1">

                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Offer redeem as </label>

                                <div>

                                    <select class="form-select" id="cmbRedeemas">
                                        <option value="1">Free offer by quantity</option>
                                        <option value="2">Free offer by given value</option>
                                        <option value="3">Free offer by price</option>
                                        <option value="4">Free offer by another item</option>

                                    </select>

                                </div>

                            </div>
                        </div>

                    </div>

                </form>







            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBTNofferData">Save</button>
            </div>
        </div>
    </div>
</div>



<!------------------------------------------------------------- thresholds model ---------------------------------------->
<div class="modal fade" id="Thresholdsmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Thresholds</h5>
                &nbsp;
                &nbsp;
                <h5 class="modal-title" id="lblHeadersecondTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmthresholdData">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Quantity</label>

                            <div>

                                <input type="number" class="form-control" id="txtQuantity">

                            </div>

                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="mb-1">

                                <label style="display: none;" id="lblfreeOfferThresholds"></label>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Free offer quantity </label>

                                <div>


                                    <input type="number" class="form-control" id="txtFreeOfferQuantity">

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Maximum quantity </label>

                                <div>


                                    <input type="number" class="form-control" id="txtMaximumQuantity">

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Total offer quantity </label>

                                <div>


                                    <input type="number" class="form-control" id="txtTotalOfferQuantity">

                                </div>


                            </div>

                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="mb-1">
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Free offer value </label>

                                <div>


                                    <input type="number" class="form-control" id="txtFreeofferValue">

                                </div>


                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Maximum value </label>

                                <div>

                                    <input type="number" class="form-control" id="txtMaximumValue">

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Total offer value </label>

                                <div>


                                    <input type="number" class="form-control" id="txtTotalOfferValue">

                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Free offer another item </label>

                            <div>

                                <select class="select2 form-control" id="cmbFreeofferAnotherItem">

                                </select>

                            </div>

                        </div>

                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnThreshold">Add</button>
            </div>
        </div>
    </div>
</div>





<!---------------------------------------- range model------------------------------------------------------------- -->
<div class="modal fade" id="rangemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Range</h5>
                &nbsp;
                &nbsp;
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmRange">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="mb-1">
                                <label style="display: none;" id="lblHiddenIDRange"></label>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>From</label>

                                <div>

                                    <input type="number" class="form-control" id="txtFromRange">

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Free offer quantity </label>

                                <div>


                                    <input type="number" class="form-control" id="txtFreeOfferQuantityRange">

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Maximum quantity </label>

                                <div>


                                    <input type="number" class="form-control" id="txtMaximumquantityRange" required>

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Total offer quantity</label>

                                <div>

                                    <input type="number" class="form-control" id="txtTotalOfferQuantityRange">

                                </div>


                            </div>

                        </div>
                        <div class="col-md-6 mb-1">
                            <div class="mb-1">
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>To </label>

                                <div>


                                    <input type="number" class="form-control" id="txtToRange">

                                </div>

                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Free offer value </label>

                                <div>

                                    <input type="number" class="form-control" id="txtFreeOfferValueRange">

                                </div>

                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Maximum value </label>

                                <div>

                                    <input type="number" class="form-control" id="txtMaximumValueRange">

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Total offer value</label>

                                <div>

                                    <input type="number" class="form-control" id="txtTotalOfferValueRange">

                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <div class="mb-1">
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Free offer another item</label>

                                <div>

                                    <select class="select2 form-control" id="cmbFreeOfferAnotherItemIDRange" data-live-search="true">

                                    </select>

                                </div>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSaveRange">Add</button>
            </div>
        </div>
    </div>
</div>



<!---------------------------------------------------------- offer applying to model --------------------------------------->

<div class="modal fade" id="offer_applying_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lblOfferApplyModel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="tab-pane fade show active">

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Filter by</h5>
                            <select class="form-select" id="cmbFilterByOffer" disabled>
                                <!--  <option value="0" selected>Select</option> -->
                                <option value="1">Location</option>
                                <option value="2">Customer</option>
                                <option value="3">Customer Grade</option>
                                <option value="4">Customer Group</option>
                            </select>
                            <br>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-12">

                            <select multiple class="form-control  listbox-buttons" id="cmbOfferFilterData">

                            </select>

                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnAddApplyTo">Add</button>
            </div>
        </div>
    </div>
</div>