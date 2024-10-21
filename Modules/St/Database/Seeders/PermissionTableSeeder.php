<?php

namespace Modules\St\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('permissions')->insert([
            ['name' => 'Item','slug' => 'md_item','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()], //1
            ['name' => 'Add Item','slug' => 'md_add_item','sub'=>'1','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()], //2
            ['name' => 'Item List','slug' => 'md_item_list','sub'=>'1','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()], //3
            ['name' => 'Edit Item','slug' => 'md_edit_item','sub'=>'3','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()], //4
            ['name' => 'Delete Item','slug' => 'md_delete_item','sub'=>'3','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//5
            ['name' => 'View Item','slug' => 'md_view_item','sub'=>'3','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//6

            ['name' => 'Customer','slug' => 'md_customer','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//7
            ['name' => 'Add Customer','slug' => 'md_add_customer','sub'=>'7','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//8
            ['name' => 'Customer List','slug' => 'md_customer_list','sub'=>'7','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//9
            ['name' => 'Edit Customer','slug' => 'md_edit_customer','sub'=>'9','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//10
            ['name' => 'Delete Customer','slug' => 'md_delete_customer','sub'=>'9','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//11
            ['name' => 'View Customer','slug' => 'md_view_customer','sub'=>'9','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//12

            ['name' => 'Employee','slug' => 'md_employee','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//13
            ['name' => 'Add Employee','slug' => 'md_add_employee','sub'=>'13','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//14
            ['name' => 'Employee List','slug' => 'md_employee_list','sub'=>'13','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//15
            ['name' => 'Edit Employee','slug' => 'md_edit_employee','sub'=>'15','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//16
            ['name' => 'Delete Employee','slug' => 'md_delete_emloyee','sub'=>'15','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//17
            ['name' => 'View Employee','slug' => 'md_view_employee','sub'=>'15','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//18

            ['name' => 'Location','slug' => 'md_location','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//19
            ['name' => 'Add Location','slug' => 'md_add_location','sub'=>'19','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//20
            ['name' => 'Location List','slug' => 'md_location_list','sub'=>'19','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//21
            ['name' => 'Edit Location','slug' => 'md_edit_location','sub'=>'21','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//22
            ['name' => 'Delete Location','slug' => 'md_delete_location','sub'=>'21','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//23
            ['name' => 'View Location','slug' => 'md_view_location','sub'=>'21','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//24
 
            ['name' => 'Vehicle','slug' => 'md_vehicle','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//25
            ['name' => 'Add Vehicle','slug' => 'md_add_vehicle','sub'=>'25','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//26
            ['name' => 'Edit Vehicle','slug' => 'md_edit_vehicle','sub'=>'25','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//27
            ['name' => 'Delete Vehicle','slug' => 'md_delete_vehicle','sub'=>'25','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//28
            ['name' => 'View Vehicle','slug' => 'md_view_vehicle','sub'=>'25','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//29
            
            ['name' => 'Supply Group','slug' => 'md_supply_group','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//30
            ['name' => 'Add Supply Group','slug' => 'md_add_supply_group','sub'=>'30','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//31
            ['name' => 'Edit Supply Group','slug' => 'md_edit_supply_group','sub'=>'30','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//32
            ['name' => 'Delete Supply Group','slug' => 'md_delete_supply_group','sub'=>'30','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//33
            ['name' => 'View Supply Group','slug' => 'md_view_supply_group','sub'=>'30','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//34 

            ['name' => 'International Nonproprietary Name (INN)','slug' => 'md_international_nonproprietary_name','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//35
            ['name' => 'Add International Nonproprietary Name (INN)','slug' => 'md_add_international_nonproprietary_name','sub'=>'35','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//36
            ['name' => 'Edit International Nonproprietary Name (INN)','slug' => 'md_edit_international_nonproprietary_name','sub'=>'35','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//37
            ['name' => 'Delete International Nonproprietary Name (INN)','slug' => 'md_delete_international_nonproprietary_name','sub'=>'35','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//38

            
            ['name' => 'Bank List','slug' => 'md_bank_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//39
            ['name' => 'Bank','slug' => 'md_bank','sub'=>'39','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//40
            ['name' => 'Add Bank','slug' => 'md_add_bank','sub'=>'40','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//41
            ['name' => 'Edit Bank','slug' => 'md_edit_bank','sub'=>'40','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//42
            ['name' => 'Delete Bank','slug' => 'md_delete_bank','sub'=>'40','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//43
            ['name' => 'Active Bank','slug' => 'md_active_bank','sub'=>'40','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//44 
            ['name' => 'Bank Branch','slug' => 'md_bank_branch','sub'=>'39','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//45 
            ['name' => 'Add Bank Branch','slug' => 'md_add_bank_branch','sub'=>'45','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//46 
            ['name' => 'Edit Bank Branch','slug' => 'md_edit_bank_branch','sub'=>'45','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//47
            ['name' => 'Delete Bank Branch','slug' => 'md_delete_bank_branch','sub'=>'45','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//48
            ['name' => 'Active Bank Branch','slug' => 'md_activate_bank_branch','sub'=>'45','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//49

            ['name' => 'Branch List','slug' => 'md_branch_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//50
            ['name' => 'Add Branch','slug' => 'md_add_branch','sub'=>'50','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//51
            ['name' => 'Edit Branch','slug' => 'md_edit_branch','sub'=>'50','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//52
            ['name' => 'Delete Branch','slug' => 'md_delete_branch','sub'=>'50','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//53
            ['name' => 'View Branch','slug' => 'md_view_branch','sub'=>'50','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//54

            ['name' => 'Supplier List','slug' => 'md_supplier_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//55
            ['name' => 'Add Supplier','slug' => 'md_add_supplier','sub'=>'55','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//56
            ['name' => 'Edit Supplier','slug' => 'md_edit_supplier','sub'=>'55','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//57
            ['name' => 'Delete Supplier','slug' =>'md_delete_supplier','sub'=>'55','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//58
            ['name' => 'View Supplier','slug' => 'md_view_supplier','sub'=>'55','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//59

            ['name' => 'Administrative District','slug' => 'md_administrative_district_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//60
            ['name' => 'Add Administrative District','slug' => 'md_add_administrative_district','sub'=>'60','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//61
            ['name' => 'Edit Administrative District','slug' => 'md_edit_administrative_district','sub'=>'60','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//62
            ['name' => 'Delete Administrative District','slug' =>'md_delete_administrative_district','sub'=>'60','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//63
            ['name' => 'Activate Administrative District','slug' => 'md_activate_administrative_district','sub'=>'60','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//64

            ['name' => 'Administrative Town','slug' => 'md_administrative_town_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//65
            ['name' => 'Add Administrative Town','slug' => 'md_add_administrative_town','sub'=>'65','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//66
            ['name' => 'Edit Administrative Town','slug' => 'md_edit_administrative_town','sub'=>'65','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//67
            ['name' => 'Delete Administrative Town','slug' =>'md_delete_administrative_town','sub'=>'65','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//68
            ['name' => 'Activate Administrative Town','slug' => 'md_activate_administrative_town','sub'=>'65','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//69

            ['name' => 'Town','slug' => 'md_town_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//70
            ['name' => 'Add Town','slug' => 'md_add_town','sub'=>'70','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//71
            ['name' => 'Edit Town','slug' => 'md_edit_town','sub'=>'70','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//72
            ['name' => 'Delete Town','slug' =>'md_delete_town','sub'=>'70','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//73

            ['name' => 'Route','slug' => 'md_route_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//74
            ['name' => 'Add Route','slug' => 'md_add_route','sub'=>'74','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//75
            ['name' => 'Edit Route','slug' => 'md_edit_route','sub'=>'74','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//76
            ['name' => 'Delete Route','slug' =>'md_delete_route','sub'=>'74','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//77

            ['name' => 'Supplier Item Code','slug' => 'md_supplier_item_code','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//78
            ['name' => 'Add Supplier Item Code','slug' => 'md_add_supplier_item_code','sub'=>'78','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//79

            ['name' => 'Assign Customer to Branch','slug' => 'md_assign_customer_to_branch','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//80
            ['name' => 'Add Assign Customer to Branch','slug' => 'md_add_assign_customer_to_branch','sub'=>'80','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//81
            ['name' => 'Assign Customer to Branch List','slug' => 'md_list_assign_customer_to_branch','sub'=>'80','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//82
            ['name' => 'Delete Assign Customer to Branch ','slug' => 'md_list_assign_customer_to_branch','sub'=>'82','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//83

            ['name' => 'Common Settings','slug' => 'md_common_settings','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//84
            ['name' => 'Add Common Settings','slug' => 'md_add_common_settings','sub'=>'84','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//85
            ['name' => 'Edit Common Settings','slug' => 'md_edit_common_settings','sub'=>'84','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//86
            ['name' => 'Delete Common Settings','slug' => 'md_delete_common_settings','sub'=>'84','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//87
            ['name' => 'Activate Common Settings','slug' => 'md_activate_common_settings','sub'=>'84','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//88

            ['name' => 'Purchase Order','slug' => 'prc_purchase_order','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//89

            ['name' => 'Purchase Order Approval','slug' => 'prc_purchase_order_approval','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//90

            ['name' => 'Good Receive','slug' => 'prc_goods_receive','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//91

            ['name' => 'Good Return','slug' => 'prc_goods_return','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//92

           /*  ['name' => 'Good Return','slug' => 'prc_goods_return','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//93 */

            ['name' => 'Users','slug' => 'st_users','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//93

            ['name' => 'User Role','slug' => 'st_user_role','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//94

            ['name' => 'Permission','slug' => 'st_permission','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//95

            ['name' => 'Assign User to Branch','slug' => 'st_assign_user_to_branch','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//96

            ['name' => 'Sales Order','slug' => 'sd_sales_order','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//97

            ['name' => 'Sales Invoice','slug' => 'sd_sales_invoice','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//98

            ['name' => 'Sales Return','slug' => 'sd_sales_return','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//99

            ['name' => 'Delivery Plan','slug' => 'sd_delivery_plan','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//100

            ['name' => 'Delivery Confirmation','slug' => 'sd_delivery_confirmation','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//101

            ['name' => 'Assign Customer to Sales Rep','slug' => 'st_assign_customer_to_sales_rep','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//102

            ['name' => 'Assign Employee to Branch','slug' => 'st_assign_employee_to_branch','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//103

            ['name' => 'Assign Route to Sales Rep','slug' => 'st_assign_route_to_sales_rep','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//104

            ['name' => 'Assign Supply Group to Sales Rep','slug' => 'st_supply_group_to_sales_rep','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//105

            ['name' => 'Free Offer','slug' => 'sd_free_offer','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//106

            ['name' => 'Customer App user','slug' => 'sd_customer_app_user','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//107

            ['name' => 'Stock Reports','slug' => 'sc_reports','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//108

            ['name' => 'Stock Balance','slug' => 'sc_stock_balance','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//109

            ['name' => 'Debtor Ledger Report','slug' => 'dl_debtor_ledger_report','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//110

            ['name' => 'Customer Receipt','slug' => 'cb_customer_receipt','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//111

            ['name' => 'Cash Collection By Branch','slug' => 'cb_cash_collection_by_branch','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//112

            ['name' => 'Cash Collection By HO','slug' => 'cb_cash_collection_by_ho','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//113

            ['name' => 'Receipt for cheque collected by branch','slug' => 'cb_cheque_collection_by_branch','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//114 //rcpt for chq collected by branch

            ['name' => 'Cheque Collection By HO','slug' => 'cb_cheque_collection_by_ho','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//115

            ['name' => 'Update Batch Price','slug' => 'tl_update_batch_price','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//116

            ['name' => 'Goods Received Approval','slug' => 'prc_goods_received_approval','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//117

            ['name' => 'Receipt for cash collected by branch','slug' => 'cb_receipt_for_cash_collected_by_branch','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//118

            ['name' => 'Customer Block List','slug' => 'sd_customer_block_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//119
           
            ['name' => 'Bin Card','slug' => 'sc_bin_card','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//120

            ['name' => 'GL Account','slug' => 'md_gl_account','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//121

            ['name' => 'Cheque Deposit','slug' => 'cb_cheque_deposit','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//122

            ['name' => 'Cheque Dishonour','slug' => 'cb_cheque_dishonour','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//123

            ['name' => 'Cheque Dishonour List','slug' => 'cb_cheque_dishonour_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//124

            ['name' => 'Book','slug' => 'md_book','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//125

            ['name' => 'Cheque Collected By Branch Cashier','slug' => 'cb_cheque_collected_by_branch_cashier_sfa','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//126

            ['name' => 'SFA','slug' => 'md_sfa_access','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//127

            ['name' => 'Sales Return Details','slug' => 'sd_sales_return_details','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//128

            ['name' => 'Return Transfer','slug' => 'sd_return_transfer_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//129

            ['name' => 'Special Bonus','slug' => 'sd_special_bonus','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//130

            ['name' => 'Special Bonus Approval List','slug' => 'sd_special_bonus_approval_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//131

            ['name' => 'Sales Invoice Reprint','slug' => 'sd_sales_invoice_reprint','sub'=>'98','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//132 need to adjust

            ['name' => 'Sales Report','slug' => 'sd_sales_report','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//133

            ['name' => 'Price Approval List','slug' => 'sc_price_approval_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//134

            ['name' => 'Missed Sales Order','slug' => 'sd_missed_sales_order','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//135

            ['name' => 'Invoice Info','slug' => 'sd_invoice_info','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//136

            ['name' => 'Goods Transfer List','slug' => 'sc_goods_transfer_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//137

            ['name' => 'Goods Transfer Approval List','slug' => 'sc_goods_transfer_approval_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//138

            ['name' => 'Sample Dispatch List','slug' => 'sc_sample_dispatch','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//139

            ['name' => 'Customer Transaction Allocation','slug' => 'dl_customer_transaction_allocation','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//140

            ['name' => 'Procument Reports','slug' => 'prc_procument_reports','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//141

            ['name' => 'Stock Adjustment','slug' => 'sc_stock_adjustment','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//142

            ['name' => 'Debit Note','slug' => 'dl_debit_note','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//143

            ['name' => 'Credit Note','slug' => 'dl_credit_note','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//144

            ['name' => 'Division Transfer Entry','slug' => 'sc_division_transfer_entry','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//145

            ['name' => 'Division Transfer Confirmation','slug' => 'sc_division_transfer_confirmation','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//146

            ['name' => 'Merge Order','slug' => 'sd_merge_order','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//147

            ['name' => 'Reverse Division Transfer','slug' => 'sc_reverse_division_transfer','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//148

            ['name' => 'Reverse Division Transfer Approval','slug' => 'sc_reverse_division_transfer_approval','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//149

            ['name' => 'Internal Orders','slug' => 'sc_internal_orders','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//150

            ['name' => 'Stock Balance Batch Wise','slug' => 'sc_stock_balance_batch_wise','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//151

            ['name' => 'Supplier Debit Note','slug' => 'sl_supplier_debitNote','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//152

            ['name' => 'Supplier Credit Note','slug' => 'sl_supplier_creditNote','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//153

            ['name' => 'Cheque Return Cancel Approval Lis','slug' => 'cb_cheque_return_cancel_approval','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//154

            ['name' => 'Cash Audit','slug' => 'cb_cash_audit','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//155

            ['name' => 'Cheque Audit','slug' => 'cb_cash_audit','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//156

            ['name' => 'Cheque Audit List','slug' => 'cb_cash_audit_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//157

            ['name' => 'Cash Audit List','slug' => 'cb_cheque_audit_list','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//158

            ['name' => 'Cash Bank Reports','slug' => 'cb_bank_reports','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//159

            ['name' => 'Divisional Transfer Shortage','slug' => 'sc_divisional_transfer_shortage','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//160

            ['name' => 'Supplier Payment','slug' => 'sl_supplier_payment','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//161

            ['name' => 'Cash with sales rep','slug' => 'cb_cash_with_rep','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//162

            ['name' => 'Cheque with sales rep','slug' => 'cb_cheque_with_rep','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//163

            ['name' => 'Supplier Transaction Allocation','slug' => 'sl_supplier_transaction_allocation','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//164

            ['name' => 'Add Customer Receipt','slug' => 'cb_add_customer_receipt','sub'=>'111','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//165
            ['name' => 'Edit Customer Receipt','slug' => 'cb_edit_customer_receipt','sub'=>'111','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//166
            ['name' => 'View Customer Receipt','slug' => 'cb_view_receipt','sub'=>'111','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//167
            ['name' => 'Delete Customer Receipt','slug' => 'cb_delete_customer_receipt','sub'=>'111','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//168

            ['name' => 'Add Purchase Order','slug' => 'prc_add_purchase_order','sub'=>'111','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//169
            ['name' => 'Edit Purchase Order','slug' => 'prc_edit_purchase_order','sub'=>'169','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//170
            ['name' => 'View Purchase Order','slug' => 'prc_view_purchase_order','sub'=>'169','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//171
            ['name' => 'Delete Purchase Order','slug' => 'prc_delete_purchase_order','sub'=>'169','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//172

            ['name' => 'Cash Bank Dashboard','slug' => 'cb_cash_bank_dashboard','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//173
            ['name' => 'SFA Receipts','slug' => 'cb_sfa_recceipt','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//174

            ['name' => 'Bonus Claim','slug' => 'prc_bonus_claim','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//175
            ['name' => 'Add Bonus Claim','slug' => 'prc_add_bonus_claim','sub'=>'174','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//176
            ['name' => 'View Bonus Claim','slug' => 'prc_view_bonus_claim','sub'=>'174','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//177
            ['name' => 'Print Bonus Claim','slug' => 'prc_print_bonus_claim','sub'=>'174','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//178

            ['name' => 'Supplier Report','slug' => 'sl_supplier_report','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//179

            ['name' => 'Payment Voucher','slug' => 'cb_payment_voucher','sub'=>'null','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//180
            ['name' => 'Add Payment Voucher','slug' => 'cb_add_payment_voucher','sub'=>'179','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//181
            ['name' => 'View Payment Voucher','slug' => 'cb_view_payment_voucher','sub'=>'179','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//182
            ['name' => 'Print Payment Voucher','slug' => 'cb_print_payment_voucher','sub'=>'179','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//184
            ['name' => 'Delete Payment Voucher','slug' => 'cb_delete_payment_voucher','sub'=>'179','action'=>false,'created_at' => Carbon::now(),'updated_at' => Carbon::now()],//184
            
            

        ]);
        
    }
}
