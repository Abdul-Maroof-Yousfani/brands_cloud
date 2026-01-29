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
           <ul class="profile-admin d-flex">
             
              <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link bella" href="#" data-bs-toggle="dropdown"><i class="fa fa-bell" aria-hidden="true"></i><span class="badge rounded-pill bg-danger badge-up">5</span></a>
                 <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                     <li class="dropdown-menu-header">
                         <div class="dropdown-header d-flex">
                             <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                             <div class="badge rounded-pill badge-light-primary">6 New</div>
                         </div>
                     </li>
                     <li class="scrollable-container  media-list ">
                       <a class="d-flex" href="#">
                             <div class="list-item d-flex align-items-start">
                                
                                 <div class="list-item-body flex-grow-1">
                                     <p class="media-heading"><span class="fw-bolder">Congratulation Sam 🎉</span>winner!</p><small class="notification-text"> Won the monthly best seller badge.</small>
                                 </div>
                             </div>
                         </a><a class="d-flex" href="#">
                             <div class="list-item d-flex align-items-start">
                                
                                 <div class="list-item-body flex-grow-1">
                                     <p class="media-heading"><span class="fw-bolder">New message</span>&nbsp;received</p><small class="notification-text"> You have 10 unread messages</small>
                                 </div>
                             </div>
                         </a><a class="d-flex" href="#">
                             <div class="list-item d-flex align-items-start">
                               
                                 <div class="list-item-body flex-grow-1">
                                     <p class="media-heading"><span class="fw-bolder">Revised Order 👋</span>&nbsp;checkout</p><small class="notification-text"> MD Inc. order updated</small>
                                 </div>
                             </div>
                         </a>
                         <div class="list-item d-flex align-items-center">
                             <h6 class="fw-bolder me-auto mb-0">System Notifications</h6>
                             <div class="form-check form-check-primary form-switch">
                                 <input class="form-check-input" id="systemNotification" type="checkbox" checked="">
                                 <label class="form-check-label" for="systemNotification"></label>
                             </div>
                         </div><a class="d-flex" href="#">
                             <div class="list-item d-flex align-items-start">
                                
                                 <div class="list-item-body flex-grow-1">
                                     <p class="media-heading"><span class="fw-bolder">Server down</span>&nbsp;registered</p><small class="notification-text"> USA Server is down due to high CPU usage</small>
                                 </div>
                             </div>
                         </a><a class="d-flex" href="#">
                             <div class="list-item d-flex align-items-start">
                                 
                                 <div class="list-item-body flex-grow-1">
                                     <p class="media-heading"><span class="fw-bolder">Sales report</span>&nbsp;generated</p><small class="notification-text"> Last month sales report generated</small>
                                 </div>
                             </div>
                         </a><a class="d-flex" href="#">
                             <div class="list-item d-flex align-items-start">
                                 
                                 <div class="list-item-body flex-grow-1">
                                     <p class="media-heading"><span class="fw-bolder">High memory</span>&nbsp;usage</p><small class="notification-text"> BLR Server using high memory</small>
                                 </div>
                             </div>
                         </a>
                     </li>
                     <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all notifications</a></li>
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
