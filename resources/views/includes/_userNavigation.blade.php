<?php

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\MenuPrivileges;
use App\Models\Menu;

$dashboard_access = explode(',',Auth::user()->dashboard_access);

$icons = array(
    'Finance'=>'fa fa-usd',
    'Purchase'=>'fa fa-money-bill',
    'Inventory'=>'fa fa-list',
    'Store'=>'fa fa-shopping-cart',
    'Sales'=>'fa fa-money',
    'Reports'=>'fa fa-print',
    'Users'=>'glyphicon glyphicon-user',
    'Dashboard' => 'glyphicon glyphicon-home',
    'HR' => 'glyphicon glyphicon-heart',
    'Productions' => 'glyphicon glyphicon-cog',
    'Import' => 'glyphicon glyphicon-import',
    'Inventory Reports' => 'fa fa-print',
    'HR Master' => 'glyphicon glyphicon-wrench',
    'Inventory Master' => 'glyphicon glyphicon-wrench',
    'Production Master' => 'glyphicon glyphicon-wrench',
    "Assets" => "glyphicon glyphicon-list",

);

$accType = Auth::user()->acc_type;
if($accType == 'client'){$m = $_GET['m'];}else{$m = Auth::user()->company_id;}







$company_id=  Session::get('run_company');
$user_rights = MenuPrivileges::where([['emp_code','=',Auth::user()->emp_code],['compnay_id','=',$company_id]]);



$parent_code = [];
$crud_permission='';
if($user_rights->count() > 0):
    $main_modules = explode(",",$user_rights->value('main_modules'));
    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));
    $crud_rights  = explode(",",$user_rights->value('crud_rights'));
    $companyList= $user_rights->value('company_list');

    foreach($submenu_ids as $val):
        $parent_code[] = Menu::where([['id', '=', $val],['status','=', 1]])->value('m_parent_code');
    endforeach;
else:
    echo "Account Type:".$accType;
    echo 'Insufficient Menu Privileges'."<br>";
    echo "<a href='".url('/logout')."'>Logout</a>";
    die;
endif;
?>


<style>
   img.logo_m {
        width: 225px;
    }
     .dropdown:hover>.dropdown-menu {
        display: block;
        margin-top: 0;
    }
</style>


<div id="mySidenav" class="sidenavnr">
    <div class="logo_wrp">
        <a href="{{route('dClient')}}">
          <img class="logo_m" src="{{ url('/logoo.png') }}">
        </a>
       </div>
@if(Session::get('run_company') != null)

        <?php


        $MainMenuTitles = DB::table('main_menu_title')->select(['main_menu_id','id'])->
        where([['status','=',1]])->whereIn('id',$main_modules)
        ->groupBy('main_menu_id')->orderBy('menu_type')->orderBy('id')->get();




    $counter = 1;
    $count = 1;
?>
       @foreach($main_modules as $row)
                @if(in_array($row,$main_modules))
                    <?php
                    $main_menu_id = DB::table('main_menu_title')->select('main_menu_id')->where([['id','=',$row]])->value('main_menu_id');
                    ?>
    <ul  class="m_list " id="myGroup">
        <li>
            <div class="sm-bx">
                <button class="btn settingListSb theme-bg" data-toggle="collapse" data-target="#masterSetting<?=$counter?>" >
                    <span><i class="<?=$icons[$main_menu_id]?>" aria-hidden="true"></i></span>
                    <p><?php echo $main_menu_id;?></p>
                </button>
                <div id="masterSetting<?=$counter?>" class="collapse pmastermnu">
                    <ul class="list-unstyled">
                        <?php



                        $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->
                        where([['main_menu_id','=',$main_menu_id],['status','=',1]])->whereIn('id', $parent_code)->orderBy('orderby','ASC')->get();


                        foreach($MainMenuTitlesSub as $row1){
                        ?>
                        <li class="dd">
                            <ul class="list-unstyled">
                                <a href="#" class="settingListSb-subItem" data-toggle="collapsee" data-target="#masterSetting<?=$counter?>-<?= $count ?>"><?php echo $row1->title; ?></a>
                                <div id="masterSetting<?= $counter ?>-<?= $count ?>" class="collapsee smastermnu">
                                    <ul class="list-unstyled">
                                        <?php
                                        $InCompany = Session::get('run_company');
                                        //if($InCompany != 1):
                                        $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->orderBy('order_by', 'ASC')->get();

                                        //else:
                                        //  $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->whereNotIn('id', [309,310,311])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->orderBy('order_by', 'ASC')->get();
                                        //endif;
                                        foreach($data as $dataValue){
                                       if(in_array($dataValue->id,$submenu_ids)):
                                        $MakeUrl = url(''.$dataValue->m_controller_name.'');?>
                                        <li>
                                            <span><i class="fal fa-circle-notch"></i></span>
                                            <a href="<?php echo url(''.$dataValue->m_controller_name.'?pageType='.$dataValue->m_type.'&&parentCode='.$dataValue->m_parent_code.'&&m='.Session::get('run_company').'#signsnow')?>"> <?php echo $dataValue->name;?>
                                            </a>
                                        </li>
                                        <?php endif; } ?>
                                    </ul>
                                </div>
                            </ul>
                        </li>
                        <?php $count++; ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
    <?php $counter++; ?>
                @endif @endforeach
    @endif
    </div>

    <div class="container-fluid head-sh">
        <div class="headerwrap">
           <div class="nav navbar-nav hide">
              <ul class=" tmenu-list d">
                 <li>
                    <div class="o_f">
                       <a href="#" class="closebtn theme-f-clr Navclose" ><i class="fa fa-list-ul" aria-hidden="true"></i></a>
                    </div>
                 </li>
                 <!-- <li>
                     <a class="btn btn-primary" href="{{route('dClient')}}">
                     Dashboard
                 </a>
                 </li>
                 <li>
                     <a class="btn btn-primary" href="{{route('production_dashboard')}}">
                     Production Dashboard
                 </a>
                 </li> -->
              </ul>
           </div>
           <div class="nav navbar-nav">
                <ul class=" tmenu-list d">
                    <li>
                    {{-- <div class="o_f">
                        <a href="#" class="closebtn theme-f-clr Navclose" ><i class="fa fa-list-ul" aria-hidden="true"></i></a>
                    </div> --}}
                    </li>
             
                </ul>
            </div>
    
           <ul class='ctn-list'>
              
              <li>
                 <div class="tim d">
                     {{-- <p>{{ date('Y-m-d H:i:s') }}</p> --}}
                    <h3 id="live-time">{{ date('h:i:s') }}<span>{{ date('A') }}</span></h3>
                 </div>
              </li>
             
           </ul>
             @php
                        $pending_delivery_notes = \App\Helpers\CommonHelper::pendingDocuments("delivery_note", "status", 0);
                        $pending_sale_tax_invoices = \App\Helpers\CommonHelper::pendingDocuments("sales_tax_invoice", "si_status", 1, "status", 1, true);
                        $pending_sale_returns = \App\Helpers\CommonHelper::pendingDocuments("credit_note", "status", 0);
                        $pending_purchase_requests = \App\Helpers\CommonHelper::pendingDocuments("demand", "demand_status", 1, "status", 1);
                        $pending_purchase_quotations = \App\Helpers\CommonHelper::pendingDocuments("quotation", "quotation_status", 1, "status", 1);
                        $pending_purchase_orders = \App\Helpers\CommonHelper::pendingDocuments("purchase_request", "purchase_request_status", 1, "status", 1);
                        $pending_grns = \App\Helpers\CommonHelper::pendingDocuments("goods_receipt_note", "grn_status", 1, "status", 1);
                        $pending_purchase_invoices = \App\Helpers\CommonHelper::pendingDocuments("new_purchase_voucher", "pv_status", 1, "status", 1);
                        $pending_stock_transfers = \App\Helpers\CommonHelper::pendingDocuments("stock_transfer", "tr_status", 1, "status", 1);
                        $delivery_note_creatable = \App\Helpers\CommonHelper::deliveryNoteCreatable();
                        $total_pending = 
                            $pending_delivery_notes +
                            $pending_sale_tax_invoices +
                            $pending_sale_returns +
                            $pending_purchase_requests +
                            $pending_purchase_quotations +
                            $pending_purchase_orders +
                            $pending_grns +
                            $pending_purchase_invoices +
                            $pending_stock_transfers +
                            $delivery_note_creatable;
                    @endphp
           <ul class="profile-admin d-flex">
             
              
            <li class="nav-item dropdown dropdown-notification me-25">
                <a class="nav-link bella" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bell" aria-hidden="true"></i>
                    <span
                        class="badge rounded-pill bg-danger badge-up notification-count">{{ $total_pending }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                            <div class="badge rounded-pill badge-light-primary"><span
                                    class="notification-count">{{ $total_pending }}</span>
                                New</div>
                        </div>
                    </li>
                  
                    <li class="scrollable-container media-list">

                        @if($delivery_note_creatable > 0)
                            <a class="d-flex" href="/sales/CreateDeliveryNoteList?m={{ request()->m }}&parentCode={{ request()->parentCode }}">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Create Delivery Note</span>
                                        </p>
                                        <small class="notification-text">{{ $delivery_note_creatable }} Pending Delivery notes can be created</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                        {{-- @foreach (App\Helpers\CommonHelper::getUnreadNotifications() as $notification) --}}
                        @if($pending_delivery_notes > 0)
                            <a class="d-flex" href="/sales/viewDeliveryNoteList?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Delivery Note</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_delivery_notes }} Delivery Notes are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if($pending_sale_tax_invoices > 0)
                            <a class="d-flex" href="/sales/viewSalesTaxInvoiceList?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Sales Tax Invoice</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_sale_tax_invoices }} Sales Tax Invoice are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if($pending_sale_returns > 0)
                             <a class="d-flex" href="/sales/viewCustomerCreditNoteList?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Sales Return</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_sale_returns }} Sale Returns are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if($pending_purchase_requests > 0)
                            <a class="d-flex" href="/purchase/viewDemandList?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Purchase Requests</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_purchase_requests }} Purchase Requests are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if($pending_purchase_quotations > 0)
                            <a class="d-flex" href="/quotation/quotation_list?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Purchase Quotations</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_purchase_quotations }} Purchase Quotations are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if($pending_purchase_orders > 0)
                            <a class="d-flex" href="/store/viewPurchaseRequestList?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Purchase Orders</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_purchase_orders }} Purchase Orders are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if($pending_grns > 0)
                            <a class="d-flex" href="/purchase/viewGoodsReceiptNoteList?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Goods Receipt Note</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_grns }} Goods Receipt Note are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if($pending_purchase_invoices > 0)
                            <a class="d-flex" href="/purchase/viewPurchaseVoucherListThroughGrn?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Purchase Invoice</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_purchase_invoices }} Purchase Invoices are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if($pending_stock_transfers > 0)
                            <a class="d-flex" href="/store/stock_transfer_list?m={{ request()->m }}&parentCode={{ request()->parentCode }}&type=pending">
                                <div class="list-item d-flex align-items-start">
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder">Stock Transfer</span>
                                        </p>
                                        <small class="notification-text">{{ $pending_stock_transfers }} Stock Transfers are pending</small>
                                        <br>
                                    </div>
                                </div>
                            </a>
                        @endif
                            
                        {{-- @endforeach --}}


                    <li class="dropdown-menu-footer">
                        <a class="btn btn-primary w-100 mark-all-as-read" onclick="markAllAsRead()">
                            Mark all as read
                        </a>
                    </li>
                </ul>
            </li>
              <li>
                 <div class="pro-user d-flex">
                   
                   <span class="avatar">
                    <img class="round" src="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/avatars/1.png" alt="avatar" height="40" width="40">
                   </span>
                   <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{ Auth::user()->name }}</span></div>
                 </div>
              </li>
              <li class="dropdown user-name-drop">
                 <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                 <div class="account-information dropdown-menu">
                    <div class="account-inner d-flex">
                       <div class="davtar">
                       <span class="avatar">
                    <img class="round" src="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/avatars/1.png" alt="avatar" >
                   </span>
                       </div>
                       <div class="main-heading">
                          <h5>{{ Auth::user()->name }}</h5>
                          <p>Bridging the Future of Industry.</p>
                          <ul class="list-unstyled" id="nav">
                             <li>
                                <a href="#" rel="{{ url('assets/css/color-one.css') }}">
                                   <div class="color-one"></div>
                                </a>
                             </li>
                             <li>
                                <a href="#" rel="{{ url('assets/css/color-two.css') }}">
                                   <div class="color-two"></div>
                                </a>
                             </li>
                             <li>
                                <a href="#" rel="{{ url('assets/css/color-three.css') }}">
                                   <div class="color-three"></div>
                                </a>
                             </li>
                             <li>
                                <a href="#" rel="{{ url('assets/css/color-four.css') }}">
                                   <div class="color-four"></div>
                                </a>
                             </li>
                             <li>
                                <a href="#" rel="{{ url('assets/css/color-five.css') }}">
                                   <div class="color-five"></div>
                                </a>
                             </li>
                             <li>
                                <a href="#" rel="{{ url('assets/css/color-six.css') }}">
                                   <div class="color-six"></div>
                                </a>
                             </li>
                             <li>
                                <a href="#" rel="{{ url('assets/css/color-seven.css') }}">
                                   <div class="color-seven"></div>
                                </a>
                             </li>
                          </ul>
                       </div>
                    </div>
                    <div class="account-footer">
                       <a href="{{ url('/users/editUserProfile') }}" class="btn  contact_support btn btn-primary">
                      Edit</a>
                          <form action="{{ route('logout') }}" method="POST" style="display: inline-block; float: right; margin-right: 15px;">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger">Sign out</button>
                    </form>

                    </div>
                 </div>
              </li>
           </ul>
        </div>
     </div>
<br />

<!--For Demo Only (End Removable) -->
<input type="hidden" id="baseUrl" value="<?php echo url('/') ?>">
<input type="hidden" id="emp_code" value="<?php echo Auth::user()->emp_code ?>">


<!-- MENU SECTION END-->
<script>
    // Function to update time dynamically (with seconds)
    function updateTime() {
        const now = new Date();

        let hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // 0 ko 12 banana

        // Update inner HTML
        document.getElementById('live-time').innerHTML =
            `${hours}:${minutes}:${seconds}<span>${ampm}</span>`;
    }

    // Run immediately
    updateTime();

    // Update every second
    setInterval(updateTime, 1000);
</script>
