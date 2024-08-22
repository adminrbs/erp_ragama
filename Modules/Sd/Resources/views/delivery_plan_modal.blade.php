<div class="modal fade" id="modalDeliveryPlan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-white">
            <div class="modal-body">
                <input type="hidden" class="form-control" name="hidden_delivery_plan_id" id="hidden_delivery_plan_id">
                <div class="row">
                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a id="tabDeliveryPlan" href="#deliveryPlan" class="nav-link active" aria-selected="true">Delivery Plan</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#route" class="nav-link" aria-selected="false">Route List</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#townList" class="nav-link" aria-selected="false">Town List</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Delivery plan tab -->
                        <div class="tab-pane fade show active" id="deliveryPlan">
                            <div class="row">

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Ref No</label>
                                        <input type="text" class="form-control form-control-sm " id="txtRefNo">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Date From</label>
                                        <input type="text" class="form-control form-control-sm daterange-single" id="txtDateFrom" name="date">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Date To</label>
                                        <input type="text" class="form-control form-control-sm daterange-single" id="txtDateTo" name="date">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <select class="form-select form-control-sm" id="cmbStatus">
                                           <!--  <option value="1">Schedule</option>
                                            <option value="2">Preparig</option>
                                            <option value="3">On Delivery</option>
                                            <option value="4">Vehicle Out</option> -->
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Vehicle</label>
                                        <select class="form-control form-control-sm select2" id="cmbVehicle"></select>
                                    </div>
                                </div>

                                <!--<div class="row">
                                    <div class="col-md-12">
                                        <label>Sales Rep</label>
                                        <select class="form-control form-control-sm select2" id="cmbSalesRep"></select>
                                    </div>
                                </div>!-->

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Driver</label>
                                        <select class="form-control form-control-sm select2" id="cmbDriver"></select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Helper</label>
                                        <select class="form-control form-control-sm select2" id="cmbHelper"></select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End of Delivery plan tab -->

                        <!-- Route tab -->
                        <div class="tab-pane fade" id="route">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button id="btnAddMoreTown" class="btn btn-link" data-bs-toggle="collapse" href="#more_route" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="bi bi-gear" style="margin-right: 5px"></i>Add Route
                                        </button>
                                    </h5>
                                </div>
                                <div id="more_route" class="collapse" data-parent="#accordionExample">
                                    <div class="card-body">


                                        <div class="row">
                                            <label>Route</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <select class="form-control form-control-sm select2" id="cmbRoute"></select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" id="btnAddroute" style="height: 35px;" class="btn btn-primary">Add</button>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>




                            <div class="row">
                                <table class="table datatable-fixed-both-delivery-plan-route table-striped" id="routeTable">
                                    <thead>
                                        <tr>
                                            <th>Route</th>
                                            <th></th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>


                        </div>
                        <!-- End of Town tab -->

                        <!-- Town tab -->
                        <div class="tab-pane fade" id="townList">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button id="btnAddMoreTown" class="btn btn-link" data-bs-toggle="collapse" href="#more_town" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="bi bi-gear" style="margin-right: 5px"></i>Add more town
                                        </button>
                                    </h5>
                                </div>
                                <div id="more_town" class="collapse" data-parent="#accordionExample">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>District</label>
                                                <select class="form-control form-control-sm select2" id="cmbDistrict"></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <label>Town</label>
                                                <select class="form-control form-control-sm select2" id="cmbTown"></select>
                                            </div>
                                            <div class="col-md-2">
                                                <br>
                                                <button style="height: 30px;" class="btn btn-primary" id="btnAddTown">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>




                            <div class="row">
                                <table class="table datatable-fixed-both-delivery-plan-town table-striped" id="townTable">
                                    <thead>
                                        <tr>
                                            <th>Check</th>
                                            <th>Town</th>
                                            <th>Order</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>


                        </div>
                        <!-- End of Town tab -->

                    </div>
                    <!--enf of tabs -->
                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    &nbsp;&nbsp;
                    <button type="button" id="btnAction" class="btn btn-primary">Save</button>
                </div>

            </div>
        </div>
    </div>
</div>