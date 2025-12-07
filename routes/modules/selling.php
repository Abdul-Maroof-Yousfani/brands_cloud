<?php
//    New Implement Taion start Here 

use App\Http\Controllers\ModeDeliveryController;

Route::group(['middleware' => 'mysql2','before' => 'csrf'], function () {
Route::get('/createJobtitle','ProspectController@createJobtitle');
Route::get('/getCustomerAssignedWarehouse','SalesOrderController@getCustomerAssignedWarehouse');
Route::get('/getCustomerAssignedWarehouseByWarehouse','SalesOrderController@getCustomerAssignedWarehouseByWarehouseId');
Route::get('/getJobtitle','ProspectController@getJobtitle')->name('getJobtitle');
Route::post('/JobtitleStore','ProspectController@JobtitleStore')->name('JobtitleStore');


// ---- --------------      ----- ProsPect   ----        --------------------//
Route::group(['prefix' => 'prospect'], function () {
    Route::get('/prospectList','ProspectController@prospectList');
    Route::post('/prospectStore','ProspectController@prospectStore')->name('prospectStore');
    Route::get('/createProspect','ProspectController@createProspect');
    Route::get('/createContact','ProspectController@createContact');
    Route::get('/viewProspect','ProspectController@viewProspect')->name('viewProspect');
    Route::get('/deleteProspect/{id}', 'ProspectController@deleteProspect');
    Route::get('/editProspect/{id}', 'ProspectController@editProspect');

    Route::post('/editProspectDetail/{id?}', 'ProspectController@editProspectDetail');//->name('editProspectDetail');

});

 // ---- --------------      -----Contact   ----        --------------------//
Route::group(['prefix' => 'contact'], function () {
    Route::get('/createContact','ContactController@createContact');
    Route::post('/contactStore','ContactController@contactStore')->name('contactStore');
    Route::get('/getContact','ContactController@getContact')->name('getContact');
    Route::get('contactList','ContactController@contactList')->name('contactList');
    Route::get('editContact/{id}','ContactController@editContact')->name('editContact');
    Route::post('contactUpdate/{id}','ContactController@contactUpdate')->name('contactUpdate');
    Route::get('contactDelete/{id}','ContactController@contactDelete')->name('contactDelete');
});
 // ---- --------------      -----Customer  GET   ----        --------------------//
Route::group(['prefix' => 'customer'], function () {
    Route::get('get_customer','SaleQuotationController@get_customer')->name('get_customer');
});
Route::group(['prefix' => 'saleQuotation'], function () {

     // ---- --------------      ----- Sale Quotation  ----        --------------------//
    Route::get('/createSaleQuotation','SaleQuotationController@createSaleQuotation')->name('createSaleQuotation');
    Route::post('saleQuotaionStore','SaleQuotationController@saleQuotaionStore')->name('saleQuotaionStore');
    Route::get('get_item_by_id','SaleQuotationController@get_item_by_id');
    Route::get('get_quotation_data','SaleQuotationController@get_quotation_data');
    Route::get('addProduct','SaleQuotationController@addProduct');
    Route::get('listSaleQuotation','SaleQuotationController@listSaleQuotation')->name('listSaleQuotation');
    Route::get('get_all_currenecy','SaleQuotationController@get_all_currenecy')->name('get_all_currenecy');
    Route::get('viewSaleQuotation/{id}','SaleQuotationController@viewSaleQuotation')->name('viewSaleQuotation');
    Route::get('viewSaleQuotationPrint/{id}','SaleQuotationController@viewSaleQuotation1')->name('viewSaleQuotationPrint');

    Route::get('approveSaleQuoatation/{id}','SaleQuotationController@approveSaleQuoatation')->name('approveSaleQuoatation');
    Route::get('rejectSaleQuoatation/{id}','SaleQuotationController@rejectSaleQuoatation')->name('rejectSaleQuoatation');
    Route::get('removeDraft','SaleQuotationController@removeDraft')->name('removeDraft');
    
    Route::get('/editSaleQuotation/{id}','SaleQuotationController@editSaleQuotation')->name('editSaleQuotation');
});
// ---- --------------      ----- Sale Order   ----        --------------------//

    Route::group(['prefix' => 'selling'], function () {
    Route::get('createSaleOrder','SalesOrderController@createSaleOrder')->name('createSaleOrder');
    Route::get('listSaleOrder','SalesOrderController@listSaleOrder')->name('listSaleOrder');
    Route::Post('getlistSaleOrder','SalesOrderController@getlistSaleOrder')->name('getlistSaleOrder');
    Route::get('getSaleOrderData','SalesOrderController@getSaleOrderData')->name('getSaleOrderData');
    Route::get('getSaleOrderDataCategory','SalesOrderController@getSaleOrderDataCategory')->name('getSaleOrderDataCategory');
    Route::get('viewSaleOrder/{id}','SalesOrderController@viewSaleOrder')->name('viewSaleOrder');

    Route::get('editSaleOrder/{id}','SalesOrderController@edit')->name('editSaleOrder');
    Route::get('deleteSaleOrder/{id}','SalesOrderController@destroy')->name('deleteSaleOrder');
    Route::post('updateSaleOrder/{id}','SalesOrderController@update')->name('updateSaleOrder');

// ---- --------------      ----- Sale Order   ----        --------------------//

Route::get("addSaleReturn", "SaleReturnController@create")->name("createSaleReturn");
Route::get('listSaleReturn','SaleReturnController@show')->name('listSaleReturn');
Route::post("getListSaleReturn", "SaleReturnController@getlistSaleOrder")->name("getListSaleReturn");
Route::get('editSaleReturn/{id}','SaleReturnController@edit')->name('editSaleReturn');
Route::post("updateSaleReturn/{id}", "SaleReturnController@update")->name("updateSaleReturn");
Route::get('deleteSaleReturn/{id}','SaleReturnController@destroy')->name('deleteSaleReturn');
   
   

// ---- --------------      ----- Sale Module   ----        --------------------//
    Route::get('salemodule','SalesmoduleController@salemodule')->name('salemodule');


    //  Section A B
    Route::get('saleOrderSectionA/{id}','SalesOrderController@saleOrderSectionA')->name('saleOrderSectionA');
    Route::get('viewSaleOrderPrint/{id}','SalesOrderController@viewSaleOrdernew')->name('viewSaleOrderPrint');
    Route::post('approveSaleOrder','SalesOrderController@approveSaleOrder')->name('approveSaleOrder');

    Route::get('viewSaleReturnPrint/{id}','SaleReturnController@viewSaleOrdernew')->name('viewSaleReturnPrint');
    Route::post('approveSaleReturn','SaleReturnController@approveSaleReturn')->name('approveSaleReturn');


     // ---- --------------      ----- Work Order    ----        --------------------//
    Route::get('createWorkOrder','WorkOrderController@createWorkOrder')->name('createWorkOrder');
    Route::post('workOrderStore','WorkOrderController@workOrderStore')->name('workOrderStore');
    Route::post('viewWorkOrder','WorkOrderController@viewWorkOrder')->name('viewWorkOrder');
    Route::get('workOrderList','WorkOrderController@workOrderList')->name('workOrderList');


    
   // ---- --------------      ----- Production  ----        --------------------//

    Route::get('createProductionOrder','ProductionController@createProductionOrder')->name('createProductionOrder');
    Route::get('viewProductionOrder','ProductionController@viewProductionOrder')->name('viewProductionOrder');
    Route::get('viewProductionOrderPrint','ProductionController@viewProductionOrderPrint')->name('viewProductionOrderPrint');
    Route::get('editProductionOrder','ProductionController@editProductionOrder')->name('editProductionOrder');
    Route::get('getWorkOrderData','ProductionController@getWorkOrderData')->name('getWorkOrderData');
    Route::get('getWorkOrderDataForView','ProductionController@getWorkOrderDataForView')->name('getWorkOrderDataForView');
    Route::get('getWorkOrderDataForEdit','ProductionController@getWorkOrderDataForEdit')->name('getWorkOrderDataForEdit');
    Route::get('listProductionOrder','ProductionController@listProductionOrder')->name('listProductionOrder');
    Route::post('storeProductionOrder','ProductionController@storeProductionOrder')->name('storeProductionOrder');
    Route::post('updateProductionOrder','ProductionController@updateProductionOrder')->name('updateProductionOrder');
    Route::get('getOverAllstock','ProductionController@getOverAllstock')->name('getOverAllstock');
    Route::get('getItemByCategory','ProductionController@getItemByCategory')->name('getItemByCategory');
    Route::get('getStockForProduction','ProductionController@getStockForProduction')->name('getStockForProduction');
    Route::get('getReceipeData','ProductionController@getReceipeData')->name('getReceipeData');
    Route::get('getReceipeDataView','ProductionController@getReceipeDataView')->name('getReceipeDataView');
    Route::get('approveProductionPlanMr','ProductionController@approveProductionPlanMr')->name('approveProductionPlanMr');
    
    // ---- --------------      ----- Material List Form  ----        --------------------//
    Route::get('listRawMaterial','WorkOrderController@listRawMaterial')->name('listRawMaterial');
    Route::get('viewRawMaterial','WorkOrderController@viewRawMaterial')->name('viewRawMaterial');
    Route::get('createPurcahseRawMaterial','WorkOrderController@createPurcahseRawMaterial')->name('createPurcahseRawMaterial');
    Route::get('createPurcahseRawMaterialForm','WorkOrderController@createPurcahseRawMaterialForm')->name('createPurcahseRawMaterialForm');
    Route::get('createRawMaterialIssuance','WorkOrderController@createRawMaterialIssuance')->name('createRawMaterialIssuance');
    
    
    
    // ---- --------------      ----- Item Upload Fnnction   ----        --------------------//
    Route::get('/uploadFile', 'SalesAddDetailControler@uploadFile')->name('uploadFile');
    Route::post('/uploadCustomer', 'SalesAddDetailControler@uploadCustomer')->name('uploadCustomer');
    
    
    // createMaterialRequisition
    Route::get('createMaterialRequisition/{id}','MaterialRequisitionController@createMaterialRequisition')->name('createMaterialRequisition');
    Route::post('storeMaterialRequisition','MaterialRequisitionController@storeMaterialRequisition')->name('storeMaterialRequisition');
    Route::get('listMaterialRequisition','MaterialRequisitionController@listMaterialRequisition')->name('listMaterialRequisition');
    Route::get('getStock','MaterialRequisitionController@getStock')->name('getStock');
    Route::get('viewProductionPlane/{id}','MaterialRequisitionController@viewProductionPlane')->name('viewProductionPlane');
    Route::post('issueMaterial','MaterialRequisitionController@issueMaterial')->name('issueMaterial');
    
    
    Route::get('createMachineProccess','MachineProccessController@createMachineProccess')->name('createMachineProccess');
    Route::get('productionPlanAgainstSo','MachineProccessController@productionPlanAgainstSo'); //->name('productionPlanAgainstSo');
    Route::get('getMachineProcessAgainstPP','MachineProccessController@getMachineProcessAgainstPP'); //->name('getMachineProcessAgainstPP');
    Route::post('storeMachineProccess','MachineProccessController@storeMachineProccess')->name('storeMachineProccess');
    Route::get('getMrData','MachineProccessController@getMrData')->name('getMrData');
    Route::get('RemainingQtyOfSaleOrder','MachineProccessController@RemainingQtyOfSaleOrder')->name('RemainingQtyOfSaleOrder');
    Route::get('getMrDataWithProductionPlanId','MachineProccessController@getMrDataWithProductionPlanId')->name('getMrDataWithProductionPlanId');
    Route::get('viewProductInProccess','MachineProccessController@viewProductInProccess')->name('viewProductInProccess');
    Route::get('viewProductProccessComplete','MachineProccessController@viewProductProccessComplete')->name('viewProductProccessComplete');
    Route::get('/pipeMachineList', 'MachineProccessController@pipeMachineList')->name('pipeMachineList');
    Route::post('received_length', 'MachineProccessController@received_length')->name('received_length');


    


});


    Route::get('/salesPoolList','SalesPoolController@salesPoolList')->name('salesPoolList');
    Route::get('/salesPoolCreate','SalesPoolController@salesPoolCreate')->name('salesPoolCreate');
    Route::post('/salesPoolStore','SalesPoolController@salesPoolStore')->name('salesPoolStore');




    Route::get('/salesTypeList','SalesTypeController@salesTypeList')->name('salesTypeList');
    Route::get('/salesTypeCreate','SalesTypeController@salesTypeCreate')->name('salesTypeCreate');
    Route::post('/salesTypeStore','SalesTypeController@salesTypeStore')->name('salesTypeStore');

    Route::get('/listStorageDemention','StorageDimentionController@listStorageDemention')->name('listStorageDemention');
    Route::get('/createStorageDiemention','StorageDimentionController@createStorageDiemention')->name('createStorageDiemention');
    Route::post('/storeStorageDiemention','StorageDimentionController@storeStorageDiemention')->name('storeStorageDiemention');

    Route::get('/saleTaxGroupList','SalesTaxGroupController@saleTaxGroupList')->name('saleTaxGroupList');
    Route::get('/saleTaxGroupCreate','SalesTaxGroupController@saleTaxGroupCreate')->name('saleTaxGroupCreate');
    Route::post('/storeSaleTaxGroup','SalesTaxGroupController@storeSaleTaxGroup')->name('storeSaleTaxGroup');
    Route::get('/getSubItemByCategory','PurchaseDataCallController@getSubItemByCategory')->name('getSubItemByCategory');
    Route::get('/getPriceForSaleOrder','SpecialPriceController@getPriceForSaleOrder')->name('getSubItemByCategory');
    Route::get('/getSubItemByBrand','PurchaseDataCallController@getSubItemByBrand')->name('getSubItemByBrand');
    Route::get('/getAllSubItem','PurchaseDataCallController@getAllSubItem')->name('getAllSubItem');
    Route::get('/getSubItemByBrandWithDetail','PurchaseDataCallController@getSubItemByBrandWithDetail')->name('getSubItemByBrandWithDetail');
    Route::get('/getDiscountByCustomerAndBrand','PurchaseDataCallController@getDiscountByCustomerAndBrand')->name('getDiscountByCustomerAndBrand');
    Route::get('/getContactByprospect','ProspectController@getContactByprospect')->name('getContactByprospect');

    
    Route::get('purchase_order','PurchaseDataCallController@purchase_order')->name('purchase_order');
    
    
    Route::get('purchase_order_new','PurchaseDataCallController@purchase_order_new')->name('purchase_order_new');

 


Route::group(['prefix' => 'company'], function () {
    Route::get('/createCompanyLocation','CompanyLocationController@createCompanyLocation');
    Route::get('/companyLocationList','CompanyLocationController@companyLocationList')->name('companyLocationList');
    Route::post('companyLocationStore','CompanyLocationController@companyLocationStore')->name('companyLocationStore');
    Route::get('editCompanyLocation/{id}','CompanyLocationController@editCompanyLocation')->name('editCompanyLocation');
    Route::post('updateCompanyLocation/{id}','CompanyLocationController@updateCompanyLocation')->name('updateCompanyLocation');

    Route::get('/createCompanyGroup','CompanyGroupController@createCompanyGroup');
    Route::get('/companyGroupList','CompanyGroupController@companyGroupList')->name('companyGroupList');
    Route::post('companyGroupStore','CompanyGroupController@companyGroupStore')->name('companyGroupStore');
    Route::get('editCompanyGroup/{id}','CompanyGroupController@editCompanyGroup')->name('editCompanyGroup');
    Route::post('updateCompanyGroup/{id}','CompanyGroupController@updateCompanyGroup')->name('updateCompanyGroup');
});
Route::group(['prefix' => 'brand'], function () {
    // Route::get('/createCompanyLocation','CompanyLocationController@createCompanyLocation');
    // Route::get('/companyLocationList','CompanyLocationController@companyLocationList')->name('companyLocationList');
    // Route::post('companyLocationStore','CompanyLocationController@companyLocationStore')->name('companyLocationStore');
    // Route::get('editCompanyLocation/{id}','CompanyLocationController@editCompanyLocation')->name('editCompanyLocation');
    // Route::post('updateCompanyLocation/{id}','CompanyLocationController@updateCompanyLocation')->name('updateCompanyLocation');

    Route::get('/brandList','BrandController@index')->name('brandList');
    Route::match(['GET','POST'],'/createBrand','BrandController@createBrand')->name('createBrand');
    Route::match(['GET','POST'],'editCompanyGroup/{id}','CompanyGroupController@editBrand')->name('editBrand');
});

Route::get('listModeDelivery', 'ModeDeliveryController@listModeDelivery')->name('listModeDelivery');
Route::get('craeteModeDelivery', 'ModeDeliveryController@craeteModeDelivery')->name('craeteModeDelivery');
Route::post('storeModeDelivery', 'ModeDeliveryController@storeModeDelivery')->name('storeModeDelivery');

Route::get('rmplaningReport','ReportsController@rmplaningReport')->name('rmplaningReport');
});


//  End New Implement tation  
