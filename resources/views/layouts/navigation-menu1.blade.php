
	<!-- Navigation -->
	<div class="navbar navbar-sm shadow" id="navigation-nav">
		<div class="container-fluid">
			<div class="flex-fill overflow-auto overflow-lg-visible scrollbar-hidden">
				<ul class="nav gap-1 flex-nowrap flex-lg-wrap">
					<li class="nav-item">
						<a href="/dashboard" class="navbar-nav-link rounded">
							<i class="ph-house me-2"></i>
							Home
						</a>
					</li>
					<li class="nav-item ms-xl-1">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown" data-bs-auto-close="outside">Menu</a>

						<div class="dropdown-menu start-0 end-0 p-0 mx-xl-3">
							<div class="d-xl-flex">
								<div class="d-flex flex-row flex-xl-column bg-light overflow-auto overflow-xl-visible rounded-top rounded-top-xl-0 rounded-start-xl">
									<div class="flex-1 border-bottom border-bottom-xl-0 p-2 p-xl-3">
										<div class="fw-bold border-bottom d-none d-xl-block pb-2 mb-2">Navigation</div>
										<ul class="nav nav-pills flex-xl-column flex-nowrap text-nowrap justify-content-center wmin-xl-300">
										
											<li class="nav-item">
											@if(Auth::user()->hasModulePermission('Procument'))
												<a href="#tab_navbars_demo_Procument" class="nav-link rounded" data-bs-toggle="tab">
													<i class="ph-rows me-2"></i>
													Procument
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>
											</li>
											@endif
											@if(Auth::user()->hasModulePermission('Sales And Distribution'))
											<li class="nav-item">
												<a href="#tab_navbars_demo" class="nav-link rounded" data-bs-toggle="tab">
													<i class="ph-rows me-2"></i>
													Sales & Distributions
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>


											</li>
											@endif
											@if(Auth::user()->hasModulePermission('Stock Controller'))
											<li class="nav-item">
												<a href="#tab_navbars_demo_stock_controller" class="nav-link rounded" data-bs-toggle="tab">
													<i class="ph-rows me-2"></i>
													Stock Controller
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>
											</li>
											@endif
											@if(Auth::user()->hasModulePermission('Debtor Ledger'))
											<li class="nav-item">
												<a href="#tab_navbars_demo_Customer_Ledger" class="nav-link rounded" data-bs-toggle="tab">
													<i class="ph-rows me-2"></i>
													Debtor Ledger
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>
											</li>
											@endif
											@if(Auth::user()->hasModulePermission('Cash Bank'))
											<li class="nav-item">
												<a href="#tab_navbars_demo_cashBank" class="nav-link rounded" data-bs-toggle="tab">
													<i class="ph-rows me-2"></i>
													Cash Bank
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>
											</li>
											@endif
											@if(Auth::user()->hasModulePermission('Master Data'))
											<li class="nav-item">
												
												<a href="#tab_page_demo" class="nav-link rounded active" data-bs-toggle="tab">
													<i class="ph-layout me-2"></i>
													Master Data
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>
												
											</li>
											@endif
											@if(Auth::user()->hasModulePermission('Tools'))
											<li class="nav-item">
												<a href="#tab_navbars_demo_tools" class="nav-link rounded" data-bs-toggle="tab">
													<i class="ph-rows me-2"></i>
													Tools
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>
											</li>
											@endif
											@if(Auth::user()->hasModulePermission('Setting'))
											<li class="nav-item">
												<a href="#tab_navbars_demo_setting" class="nav-link rounded" data-bs-toggle="tab">
													<i class="ph-rows me-2"></i>
													Settings
													<i class="ph-arrow-right nav-item-active-indicator d-none d-xl-inline-block ms-auto"></i>
												</a>
											</li>
											@endif
											
										</ul>
									</div>
								</div>

								<div class="tab-content flex-xl-1">
								@if(Auth::user()->hasModulePermission('Master Data'))
									<div class="tab-pane dropdown-scrollable-xl fade show active p-3" id="tab_page_demo">
										<div class="row">
											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												@if(Auth::user()->can('md_item') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/itemList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Items</a>
												@endif
												@if(Auth::user()->can('md_customer') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/customerList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customers</a>
												@endif
												@if(Auth::user()->can('md_employee') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/employeeList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Employee</a>
												@endif
												@if(Auth::user()->can('md_sfa_access') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/sfa_access" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>SFA</a>
												@endif
												@if(Auth::user()->can('md_location') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/locationList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Location</a>
												@endif
												@if(Auth::user()->can('md_vehicle') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/vehicle" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Vehicle</a>
												@endif
												@if(Auth::user()->can('md_supply_group') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/suply_group" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Supply Group</a>
												@endif
												@if(Auth::user()->can('md_international_nonproprietary_name') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/item_altenative_name" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Alternative Name</a>
												@endif
												@if(Auth::user()->can('md_bank_list') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/bank" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Bank</a>
												@endif
												@if(Auth::user()->can('md_branch_list') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/brancList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Branch</a>
												@endif
											</div>

											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												@if(Auth::user()->can('md_supplier_list') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/supplierList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Supplier</a>
												@endif
												@if(Auth::user()->can('md_administrative_district_list') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/district" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Administrative District</a>
												@endif
												@if(Auth::user()->can('md_administrative_town_list') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/town" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Adinistrative Town</a>
												@endif
												@if(Auth::user()->can('md_town_list') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/townNon" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Town</a>
												@endif
												@if(Auth::user()->can('md_route_list') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/sd/routeList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Route</a>
												@endif
												@if(Auth::user()->can('md_gl_account') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/gl_account" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>GL Account</a>
												@endif
												@if(Auth::user()->can('md_book') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/book" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Book</a>
												@endif
												
												<a href="/md/marketingRoute" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Marketing Route</a>
												
												
												
												
												
											</div>

											<div class="col-lg-4">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												@if(Auth::user()->can('md_supplier_item_code') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/supplier_item_code" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Suppier's item codes</a>
												@endif
												@if(Auth::user()->can('md_assign_customer_to_branch') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/assignCustomertoLocation" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign Customer to branch</a>
												@endif
												@if(Auth::user()->can('md_common_settings') && Auth::user()->hasModulePermission('Master Data'))
												<a href="/md/commonSetting" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Common Setting</a>
												@endif
											</div>
										</div>
									</div>
									@endif

									<div class="tab-pane dropdown-scrollable-xl fade show p-3" id="tab_navbars_demo_cashBank">
										<div class="row">
											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												@if(Auth::user()->can('cb_customer_receipt') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/customer_receipt_list" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customer Receipt</a>
												@endif
												@if(Auth::user()->can('cb_cash_collection_by_branch') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cash_collection_by_branch" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cash Collected By Branch Cashier</a>
												@endif
												@if(Auth::user()->can('cb_receipt_for_cash_collected_by_branch') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cus_rcpt_cash_bundle" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Receipt for Cash Collected By Branch Cashier</a>
												@endif
												@if(Auth::user()->can('cb_cash_collection_by_ho') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cash_collection_by_ho" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cash Collection By Head Office</a>
												@endif
												@if(Auth::user()->can('cb_cheque_collected_by_branch_cashier_sfa') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cheque_collection_by_branch_to_collect_sfa" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cheque Collected By Branch Cashier</a>
												@endif
												@if(Auth::user()->can('cb_cheque_collection_by_branch') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cheque_collection_by_branch" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Receipt for Cheque Collected By Branch Cashier</a>
												@endif
												
												
												
												@if(Auth::user()->can('cb_cheque_collection_by_ho') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cheque_collection_by_ho" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cheque Collection By Head Office</a>
												@endif

												
												
												
											</div>

											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												
												@if(Auth::user()->can('cb_cheque_deposit') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cheque_deposit" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cheque Deposit</a>
												@endif
												
												@if(Auth::user()->can('cb_cheque_dishonour') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cheque_dishonour" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cheque Return</a>
												@endif
												
												@if(Auth::user()->can('cb_cheque_dishonour_list') && Auth::user()->hasModulePermission('Cash Bank'))
												<a href="/cb/cheque_dishonour_list" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cheque Returned List</a>
												@endif

												<a href="/cb/cash_audit" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cash Audit</a>
												<a href="/cb/cheque_audit" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cheque Audit</a>

												<a href="/cb/cash_audit_list" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cash Audit List</a>
												<a href="/cb/cheque_audit_list" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Cheque Audit List</a>
											</div>

											<div class="col-lg-4">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Reports</div>
												<a href="/cb/cash_bank_reports" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Report</a>

											</div>
										</div>
									</div>

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_navbars_demo">
										<div class="row">
											<div class="col-lg-3 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												<!-- <a href="/sd/getSalesOrderList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Orders</a> -->
												@if(Auth::user()->can('sd_sales_order') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/getSalesOrderList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Order</a>
												@endif
												@if(Auth::user()->can('sd_sales_invoice') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/salesInvoiceList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Invoice</a>
												@endif
												@if(Auth::user()->can('sd_sales_return') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/salesReturnList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Return</a>
												@endif
												
												@if(Auth::user()->can('sd_sales_return_details') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/sales_return_details" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Return Details</a>
												@endif

												@if(Auth::user()->can('sd_delivery_plan') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/delivery_plan?status=plan" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Delivery Plan</a>
												<a href="/sd/delivery_plan?status=delivered" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Delivery Information</a>
												@endif
												@if(Auth::user()->can('sd_delivery_confirmation') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/delivery_confirmation" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Delivery Confirmation</a>
												@endif
												@if(Auth::user()->can('sd_return_transfer_list') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/retrun_trnasfer_list" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Return Transfer</a>
												@endif
												
											</div>

											<!-- <div class="col-lg-3 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div> -->
												<!-- <a href="navbar_multiple_top_static" class="dropdown-item rounded"></a> -->
												<!-- <a href="/sd/salesInvoiceList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Invoice</a> -->
												<!-- <a href="/sd/salesReturnList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Return</a> -->
												<!-- <a href="/sd/getSalesOrderList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Order List</a> -->

											<!-- </div> -->
											<div class="col-lg-3 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Action</div>
												<!-- <a href="/sd/getSalesInvoiceApprovalList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Invoice Approval</a>
												<a href="/sd/salesInvoiceRetuyrnApprovalLIst" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Return Approval</a> -->
												@if(Auth::user()->can('sd_customer_block_list') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/customerBlockList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customer Block List</a>
												@endif
												@if(Auth::user()->can('sd_special_bonus') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/special_bonus" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Special Bonus</a>
												@endif
												@if(Auth::user()->can('sd_special_bonus_approval_list') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/special_bonus_approval" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Special Bonus Approval List</a>
												@endif
												@if(Auth::user()->can('sd_sales_invoice_reprint') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/invoice_reprint" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Invoice Re-print request</a>
												@endif
												
												<a href="/sd/invoice_reprint_approval_list" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Re-print requests approval list</a>
												
												
		
												@if(Auth::user()->can('sd_missed_sales_order') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/missed_sales_order_list" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Missed Sales Orders List</a>
												@endif

												@if(Auth::user()->can('sd_invoice_info') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/invoice_nfo" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Invoice Inquery</a>
												@endif
												

											</div>

											<div class="col-lg-3">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Setting</div>
												@if(Auth::user()->can('sd_assign_customer_to_sales_rep') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/employeeCustomerView" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign Customer to Sales Rep</a>
												@endif
												@if(Auth::user()->can('sd_assign_employee_to_branch') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/employeBranchView" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign Employee to Branch</a>
												@endif
												@if(Auth::user()->can('sd_assign_route_to_sales_rep') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/assignrouteSalesrep" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign Route to Sales Rep</a>
												@endif
												@if(Auth::user()->can('sd_supply_group_to_sales_rep') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/assignsupplygrouptoSalesrep" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign Supply Group to Sales Rep</a>
												@endif
												<!-- <a href="/sd/freeOfferView" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Free Offer</a> -->
												@if(Auth::user()->can('sd_free_offer') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/freeOfferListView" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Free Offer List</a>

												
												<a href="/sd/freeOfferCreateNewView" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Free Offer</a>
												@endif
												@if(Auth::user()->can('sd_customer_app_user') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/customer_Appuser" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customer App user</a>
												@endif
											
											</div>
											<div class="col-lg-3">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Reports</div>
												@if(Auth::user()->can('sd_sales_report') && Auth::user()->hasModulePermission('Sales And Distribution'))
												<a href="/sd/salesReport" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Report</a>
												@endif	
											</div>
											
										</div>
									</div>

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_navbars_demo_stock_controller">
										<div class="row">
											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Transactions</div>
												<!-- <a href="{{url('sc/stockBalanceReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Stock Balance</a>
												<a href="{{url('sc/printItemMovementHistoryReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Item Movement History</a>
												<a href="{{url('sc/printoutsalseinvoiseAndRetirnReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Summery</a> -->
												
												<!-- <a href="{{url('sc/genarateReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Reports</a> -->
												
												@if(Auth::user()->can('sc_sample_dispatch') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/sample_dispatch_list')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sample Dispatch</a>
												@endif
												
												@if(Auth::user()->can('sc_stock_adjustment') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/stock_adjustment_list')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Stock Adjustment</a>
												@endif
												@if(Auth::user()->can('sc_goods_transfer_list') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/goods_transfer_list')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Transfer</a>
												@endif

												@if(Auth::user()->can('sc_goods_transfer_approval_list') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/goods_transfer_approve_list')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Transfer Approval List</a>
												@endif
												@if(Auth::user()->can('sc_price_approval_list') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/price_approve_list')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Price Approval List</a>
												@endif

												
												
												
												

												
											</div>
										<div class="col-lg-4">
											<div class="fw-bold border-bottom pb-2 mb-2">View</div>
												@if(Auth::user()->can('sc_stock_balance') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/genarateStockBalanceReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Stock Balance</a>
												@endif
												@if(Auth::user()->can('sc_bin_card') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/binCard')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Bin Card</a>
												@endif

												
												
											</div>
											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Reports</div>
												<!-- <a href="{{url('sc/stockBalanceReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Stock Balance</a>
												<a href="{{url('sc/printItemMovementHistoryReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Item Movement History</a>
												<a href="{{url('sc/printoutsalseinvoiseAndRetirnReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales Summery</a> -->
												@if(Auth::user()->can('sc_reports') && Auth::user()->hasModulePermission('Stock Controller'))
												<a href="{{url('sc/genarateReport')}}" class="dropdown-item rounded" ><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Reports</a>
												@endif

												
											</div>

											
										</div>
									</div>

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_navbars_demo_Customer_Ledger">
										<div class="row">
										<div class="col-lg-6">
												<div class="fw-bold border-bottom pb-2 mb-2">List</div>
												@if(Auth::user()->can('dl_customer_transaction_allocation') && Auth::user()->hasModulePermission('Debtor Ledger'))
												<a href="{{url('dl/transaction_allocation_list')}}" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customer Transaction Allocation</a>
												@endif

												@if(Auth::user()->can('dl_debit_note') && Auth::user()->hasModulePermission('Debtor Ledger'))
												<a href="{{url('dl/debit_note_list')}}" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Debit Note</a>
												@endif

												@if(Auth::user()->can('dl_credit_note') && Auth::user()->hasModulePermission('Debtor Ledger'))
												<a href="{{url('dl/credit_note_list')}}" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Credit Note</a>
												@endif
											</div>
											<div class="col-lg-6 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Reports</div>
												
												
												
												@if(Auth::user()->can('dl_debtor_ledger_report') && Auth::user()->hasModulePermission('Debtor Ledger'))
												<a href="{{url('sc/genaratedebtorreport')}}" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Report</a>
												@endif
											</div>

											
										</div>
									</div>

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_sidebar_types_demo">
										<div class="row">
											<div class="col-lg-3 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Main</div>
												<a href="sidebar_default_resizable" class="dropdown-item rounded">Resizable</a>
												<a href="sidebar_default_resized" class="dropdown-item rounded">Resized</a>
												<a href="sidebar_default_collapsible" class="dropdown-item rounded">Collapsible</a>
												<a href="sidebar_default_collapsed" class="dropdown-item rounded">Collapsed</a>
												<a href="sidebar_default_hideable" class="dropdown-item rounded">Hideable</a>
												<a href="sidebar_default_hidden" class="dropdown-item rounded">Hidden</a>
												<a href="sidebar_default_color_dark" class="dropdown-item rounded">Dark color</a>
											</div>

											<div class="col-lg-3 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Secondary</div>
												<a href="sidebar_secondary_collapsible" class="dropdown-item rounded">Collapsible</a>
												<a href="sidebar_secondary_collapsed" class="dropdown-item rounded">Collapsed</a>
												<a href="sidebar_secondary_hideable" class="dropdown-item rounded">Hideable</a>
												<a href="sidebar_secondary_hidden" class="dropdown-item rounded">Hidden</a>
												<a href="sidebar_secondary_color_dark" class="dropdown-item rounded">Dark color</a>
											</div>

											<div class="col-lg-3 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Right</div>
												<a href="sidebar_right_collapsible" class="dropdown-item rounded">Collapsible</a>
												<a href="sidebar_right_collapsed" class="dropdown-item rounded">Collapsed</a>
												<a href="sidebar_right_hideable" class="dropdown-item rounded">Hideable</a>
												<a href="sidebar_right_hidden" class="dropdown-item rounded">Hidden</a>
												<a href="sidebar_right_color_dark" class="dropdown-item rounded">Dark color</a>
											</div>

											<div class="col-lg-3">
												<div class="fw-bold border-bottom pb-2 mb-2">Content</div>
												<a href="sidebar_content_left" class="dropdown-item rounded">Left aligned</a>
												<a href="sidebar_content_left_stretch" class="dropdown-item rounded">Left stretched</a>
												<a href="sidebar_content_left_sections" class="dropdown-item rounded">Left sectioned</a>
												<a href="sidebar_content_right" class="dropdown-item rounded">Right aligned</a>
												<a href="sidebar_content_right_stretch" class="dropdown-item rounded">Right stretched</a>
												<a href="sidebar_content_right_sections" class="dropdown-item rounded">Right sectioned</a>
												<a href="sidebar_content_color_dark" class="dropdown-item rounded">Dark color</a>
											</div>
										</div>
									</div>

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_sidebar_content_demo">
										<div class="row">
											<div class="col-lg-6 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Sticky areas</div>
												<a href="sidebar_sticky_header" class="dropdown-item rounded">Header</a>
												<a href="sidebar_sticky_footer" class="dropdown-item rounded">Footer</a>
												<a href="sidebar_sticky_header_footer" class="dropdown-item rounded">Header and footer</a>
												<a href="sidebar_sticky_custom" class="dropdown-item rounded">Custom elements</a>
											</div>

											<div class="col-lg-6">
												<div class="fw-bold border-bottom pb-2 mb-2">Other</div>
												<a href="sidebar_components" class="dropdown-item rounded">Sidebar components</a>
											</div>
										</div>
									</div>

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_navigation_demo">
										<div class="row">
											<div class="col-lg-6 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2">Vertical</div>
												<a href="navigation_vertical_collapsible" class="dropdown-item rounded">Collapsible menu</a>
												<a href="navigation_vertical_accordion" class="dropdown-item rounded">Accordion menu</a>
												<a href="navigation_vertical_bordered" class="dropdown-item rounded">Bordered navigation</a>
												<a href="navigation_vertical_right_icons" class="dropdown-item rounded">Right icons</a>
												<a href="navigation_vertical_badges" class="dropdown-item rounded">Badges</a>
												<a href="navigation_vertical_disabled" class="dropdown-item rounded">Disabled items</a>
											</div>

											<div class="col-lg-6">
												<div class="fw-bold border-bottom pb-2 mb-2">Horizontal</div>
												<a href="navigation_horizontal_click" class="dropdown-item rounded">Submenu on click</a>
												<a href="navigation_horizontal_hover" class="dropdown-item rounded">Submenu on hover</a>
												<a href="navigation_horizontal_elements" class="dropdown-item rounded">With custom elements</a>
												<a href="navigation_horizontal_tabs" class="dropdown-item rounded">Tabbed navigation</a>
												<a href="navigation_horizontal_disabled" class="dropdown-item rounded">Disabled navigation links</a>
												<a href="navigation_horizontal_mega" class="dropdown-item rounded">Horizontal mega menu</a>
											</div>
										</div>
									</div>

									

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_navbars_demo_setting">
										<div class="row">
											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												<!-- <a href="/st/user" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Users</a> -->
												@if(Auth::user()->can('st_users') && Auth::user()->hasModulePermission('Setting'))
												<a href="/st/userlist" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Users</a>
												@endif
												@if(Auth::user()->can('st_user_role') && Auth::user()->hasModulePermission('Setting'))
												<a href="/st/Role" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>User Role</a>
												@endif
												@if(Auth::user()->can('st_permission') && Auth::user()->hasModulePermission('Setting'))
												<a href="/st/permissions" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Permission</a>
												@endif
											</div>

											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												
											</div>

											<div class="col-lg-4">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Setting</div>
												@if(Auth::user()->can('st_assign_user_to_branch') && Auth::user()->hasModulePermission('Setting'))
												<a href="{{url('st/assignusertoBranch')}}" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign user to branch</a>
												@endif

											</div>
										</div>
									</div>

									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_navbars_demo_tools">
										<div class="row">
											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												<!-- <a href="/st/user" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Users</a> -->
												@if(Auth::user()->can('tl_update_batch_price') && Auth::user()->hasModulePermission('Tools'))
												<a href="/tools/update_batch_price" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Update Batch Price</a>
												@endif
											</div>

											<div class="col-lg-4 mb-3 mb-lg-0">
												
												
											</div>

											<div class="col-lg-4">
												


											</div>
										</div>
									</div>


									<div class="tab-pane dropdown-scrollable-xl fade p-3" id="tab_navbars_demo_Procument">
										<div class="row">
											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												<!-- <a href="/prc/purchaseRequestList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Purchase Requests</a> -->
												@if(Auth::user()->can('prc_purchase_order') && Auth::user()->hasModulePermission('Procument'))
												<a href="/prc/purchaseOrderList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Purchase Order</a>
												@endif
												@if(Auth::user()->can('prc_goods_receive') && Auth::user()->hasModulePermission('Procument'))
												<a href="/prc/grnList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Receive</a>
												@endif
												@if(Auth::user()->can('prc_goods_return') && Auth::user()->hasModulePermission('Procument'))
												<a href="/prc/goodReceiveReturnList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Return</a>
												@endif
											</div>

										<!-- 	<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>List</div>
												<a href="/prc/purchaseRequestList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Purchase Requests</a>
												
												<a href="/prc/purchaseOrderList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Purchase Order List</a>
												
												<a href="/prc/grnList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Receive</a>
												<a href="/prc/goodReceiveReturnList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Receive Return</a>
												


											</div> -->

											<div class="col-lg-4 mb-3 mb-lg-0">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Approve</div>
												<!-- <a href="/prc/purchaseReuqestApprovalList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Purchase Requests Approval</a> -->
												@if(Auth::user()->can('prc_purchase_order_approval') && Auth::user()->hasModulePermission('Procument'))
												<a href="/prc/purchaseOrderApprovalList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Purchase Order Approval List</a>
												@endif
												@if(Auth::user()->can('prc_goods_received_approval') && Auth::user()->hasModulePermission('Procument'))
												<a href="/prc/GRNapprovalList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Receive Note Approval List</a>
												@endif
												<!-- <a href="/prc/GRRetrunApprovalList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Goods Receive Return Approval</a> -->

											</div>

											<div class="col-lg-4">
												<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Reports</div>
												@if(Auth::user()->can('prc_procument_reports') && Auth::user()->hasModulePermission('Procument'))
												<a href="/prc/reports" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Reports</a>
												@endif

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<!--<li class="nav-item">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-layout me-2"></i>
							Page
						</a>

						<div class="dropdown-menu start-0 end-0 p-3 mx-md-3">
							<div class="row">
								<div class="col-md-4 mb-3 mb-md-0">
									<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Menu</div>
									<div class="mb-3 mb-md-0">
										<a href="/customer" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customer</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/customerList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customer List</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/item" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Item</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/itemList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Item List</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/employee" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Employee</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/employeeList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Employee List</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/commonSetting" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Common Setting</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/item_altenative_name" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Altenative Names</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/suply_group" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Supply Groups</a>
									</div>

								</div>
								<div class="col-md-4 mb-3 mb-md-0">
									<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Menu</div>
									<div class="mb-3 mb-md-0">
										<a href="/location" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Locations</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/locationList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Location List</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/assignCustomertoLocation" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign Customer To Location</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/customer_Appuser" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Customer App user</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/purchaseorder" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Purchase Order</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/employeeCustomerView" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Assign employees to customer</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/getSalesOrderList" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Sales order list</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/freeOfferView" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Free offers</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/vehicle" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Vehicle</a>
									</div>
								</div>
								<div class="col-md-4 mb-3 mb-md-0">
									<div class="fw-bold border-bottom pb-2 mb-2"><i class="fa fa-bars text-info" aria-hidden="true">&#160</i>Menu</div>
									<div class="mb-3 mb-md-0">
										<a href="/bank" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Banks</a>
									</div>
									<div class="mb-3 mb-md-0">
										<a href="/supplier_item_code" class="dropdown-item rounded"><i class="fa fa-chevron-circle-down  text-info" aria-hidden="true">&#160</i>Supply Item Code</a>
									</div>
								</div>
							</div>
						</div>
					</li>!-->
					<li class="nav-item nav-item-dropdown-lg dropdown ms-lg-auto">
						<a href="#" class="navbar-nav-link dropdown-toggle rounded" data-bs-toggle="dropdown">
							<i class="ph-arrows-clockwise me-2"></i>
							Switch
						</a>

						<div class="dropdown-menu dropdown-menu-end">
							<div class="dropdown-submenu dropdown-submenu-start">
								<a href="#" class="dropdown-item dropdown-toggle">
									<i class="ph-swatches me-2"></i>
									Themes
								</a>
								<div class="dropdown-menu">
									<a href="/" class="dropdown-item active">Default</a>
									<a href="javascript:void(0)" class="dropdown-item disabled">
										Material
										<span class="opacity-75 fs-sm ms-auto">Coming soon</span>
									</a>
									<a href="javascript:void(0)" class="dropdown-item disabled">
										Clean
										<span class="opacity-75 fs-sm ms-auto">Coming soon</span>
									</a>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /navigation -->