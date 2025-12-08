<?php
use App\Helpers\HrHelper;
use App\Models\MenuPrivileges;
use App\Models\Menu;
use App\Helpers\CommonHelper;
$UserId = Auth::user()->id;
$accType = Auth::user()->acc_type;
$dashboard_access = explode(',',Auth::user()->dashboard_access);

if ($accType == "client") {
    $m = Auth::user()->company_id;
}

$icons = [
    "Finance" => "fa fa-usd",
    "Purchase" => "fa fa-money-bill",
    "Inventory" => "fa fa-list",
    "Store" => "fa fa-shopping-cart",
    "Sales" => "fa fa-money",
    "Reports" => "fa fa-print",
    "Users" => "glyphicon glyphicon-user",
    "Dashboard" => "glyphicon glyphicon-home",
    "HR" => "glyphicon glyphicon-heart",
    "Production" => "glyphicon glyphicon-cog",
    "Import" => "glyphicon glyphicon-import",
    "Inventory Reports" => "fa fa-print",
    "HR Master" => "glyphicon glyphicon-wrench",
    "Inventory Master" => "glyphicon glyphicon-wrench",
    "Production Master" => "glyphicon glyphicon-wrench",
    "Productions" => "glyphicon glyphicon-wrench",
    "Assets" => "glyphicon glyphicon-list",
];
CommonHelper::reconnectMasterDatabase();
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
   $Clause = "";
   if (Session::get("run_company") == 3) {
       $Clause = ",['id','!=',174]";
   } else {
       $Clause = "";
   }

   if (Auth::user()->id == 1040) {
       $MainMenuTitles = DB::table("main_menu_title")
           ->select(["main_menu_id", "id"])
           ->where([
               ["status", "=", 1],
               ["main_menu_id", "!=", "HR"],
               ["main_menu_id", "!=", "HR Master"],
               ["main_menu_id", "!=", "Users"],
           ])
           ->groupBy("main_menu_id")
           ->orderBy("menu_type")
           ->orderBy("id")
           ->get();
   } else {
       $MainMenuTitles = DB::table("main_menu_title")
           ->select(["main_menu_id", "id"])
           ->where([
               ["status", "=", 1],
               ["main_menu_id", "!=", "HR"],
               ["main_menu_id", "!=", "HR Master"],
               ["main_menu_id", "!=", "Production"],
               ["main_menu_id", "!=", "Production Master"],
           ])
           ->groupBy("main_menu_id")
           ->orderBy("menu_type")
           ->orderBy("id")
           ->get();
   }

   $counter = 1;
   $count = 1;

   foreach ($MainMenuTitles as $row) { ?>
   <ul  class="m_list " id="myGroup">
      <li>
         <div class="sm-bx">
            <button class="btn settingListSb theme-bg" data-toggle="collapse" data-target="#masterSetting<?= $counter ?>" >
               <span><i class="<?= $icons[
                   $row->main_menu_id
               ] ?>" aria-hidden="true"></i></span>
               <p><?php echo $row->main_menu_id; ?></p>
            </button>
            <div id="masterSetting<?= $counter ?>" class="collapse pmastermnu">
               <ul class="list-unstyled">
                  <?php
                  $m = 1;

                  $MainMenuTitlesSub = DB::table("main_menu_title")
                      ->select(["main_menu_id", "title", "title_id", "id"])
                      ->where([
                          ["main_menu_id", "=", $row->main_menu_id],
                          ["status", "=", 1],
                          ["id", "!=", 174],
                      ])
                      ->orderBy("orderby", "ASC")
                      ->get();

                  foreach ($MainMenuTitlesSub as $row1) { ?>
                  <li class="dd">
                     <ul class="list-unstyled">
                        <a href="#" class="settingListSb-subItem" data-toggle="collapsee" data-target="#masterSetting<?= $counter ?>-<?= $count ?>"><?php echo $row1->title; ?></a>
                        <div id="masterSetting<?= $counter ?>-<?= $count ?>" class="collapsee smastermnu">
                           <ul class="list-unstyled">
                              <?php
                              $InCompany = Session::get("run_company");
                              //if($InCompany != 1):
                              $data = DB::table("menu")
                                  ->select([
                                      "m_type",
                                      "name",
                                      "m_controller_name",
                                      "m_main_title",
                                      "id",
                                      "m_parent_code",
                                  ])
                                  ->where([
                                      ["m_parent_code", "=", $row1->id],
                                      ["page_type", "=", 1],
                                      ["status", "=", 1],
                                  ])
                                  ->orderBy("id", "ASC")
                                  ->get();
                              //else:
                              //  $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->whereNotIn('id', [309,310,311])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->orderBy('order_by', 'ASC')->get();
                              //endif;
                              foreach ($data as $dataValue) {
                                  $MakeUrl = url(
                                      "" . $dataValue->m_controller_name . ""
                                  ); ?>
                              <li>
                                 <span><i class="fal fa-circle-notch"></i></span>
                                 <a href="<?php echo url(
                                     "" .
                                         $dataValue->m_controller_name .
                                         "?pageType=" .
                                         $dataValue->m_type .
                                         "&&parentCode=" .
                                         $dataValue->m_parent_code .
                                         "&&m=" .
                                         Session::get("run_company") .
                                         "#premiorsCable"
                                 ); ?>"> <?php echo $dataValue->name; ?>
                                 </a>
                              </li>
                              <?php
                              }
                              ?>
                           </ul>
                        </div>
                     </ul>
                  </li>
                  <?php $count++; ?>
                  <?php }
                  ?>
               </ul>
            </div>
         </div>
      </li>
   </ul>
   <?php $counter++; ?>
   <?php }
   ?>
   @endif
</div>

<div class="container-fluid head-sh">
   <div class="headerwrap">
      <div class="nav navbar-nav">
         <ul class=" tmenu-list d">
            <li>
               <div class="o_f">
                  <a href="#" class="closebtn theme-f-clr Navclose" ><i class="fa fa-list-ul" aria-hidden="true"></i></a>
               </div>
            </li>
            @if(in_array("dashboard", $dashboard_access)) 
            <li>
                <a class="btn btn-primary" href="{{route('dClient')}}">
                Dashboard
            </a>
            </li>
            @endif
            @if(in_array("dashboard_production", $dashboard_access)) 

            <li>
                <a class="btn btn-primary" href="{{route('production_dashboard')}}">
                Production Dashboard
                </a>
            </li>
            @endif
            @if(in_array("dashboard_management", $dashboard_access)) 

            <li>
                <a class="btn btn-primary" href="{{route('fClient')}}">
                Management Dashboard
                </a>
            </li>
            @endif

         </ul>
      </div>
      <ul class='ctn-list'>
         <li>
            <div class='ser'>
               <input  placeholder="Search anything"  /> 
               <button><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
         </li>
         <li>
            <div class="tim d">
                {{-- Laravel se initial time show kare --}}
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
               <img class="round" src="{{ App\Helpers\CommonHelper::get_profile_pic() }}" alt="avatar" height="40" width="40">
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
               <img style="width: 100px;" class="round" src="{{ App\Helpers\CommonHelper::get_profile_pic() }}" alt="avatar" >
              
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
<div class="container-fluid">
   <div class="headerwrap">
      <nav class="navbar  erp-menus">
         <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
         </div>
         <div class="collapse navbar-collapse js-navbar-collapse">
            <!--Company List Begin-->
            <!--Company List End-->
            @if(Session::get('run_company') != null)
            <?php
            $Clause = "";
            if (Session::get("run_company") == 3) {
                $Clause = ",['id','!=',174]";
            } else {
                $Clause = "";
            }

            if (Auth::user()->id == 1040) {
                $MainMenuTitles = DB::table("main_menu_title")
                    ->select(["main_menu_id", "id"])
                    ->where([
                        ["status", "=", 1],
                        ["main_menu_id", "!=", "HR"],
                        ["main_menu_id", "!=", "HR Master"],
                        ["main_menu_id", "!=", "Users"],
                    ])
                    ->groupBy("main_menu_id")
                    ->orderBy("menu_type")
                    ->orderBy("id")
                    ->get();
            } else {
                $MainMenuTitles = DB::table("main_menu_title")
                    ->select(["main_menu_id", "id"])
                    ->where([["status", "=", 1]])
                    ->groupBy("main_menu_id")
                    ->orderBy("menu_type")
                    ->orderBy("id")
                    ->get();
            }

            $counter = 1;

            foreach ($MainMenuTitles as $row) { ?>
            <ul class="nav navbar-nav">
               <li class="dropdown mega-dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="<?= $icons[
                      $row->main_menu_id
                  ] ?>" aria-hidden="true"></i> <?php echo $row->main_menu_id; ?></a>
                  <ul class="dropdown-menu mega-dropdown-menu row">
                     <?php
                     $m = 1;

                     if (Session::get("run_company") != 2) {
                         $MainMenuTitlesSub = DB::table("main_menu_title")
                             ->select([
                                 "main_menu_id",
                                 "title",
                                 "title_id",
                                 "id",
                             ])
                             ->where([
                                 ["main_menu_id", "=", $row->main_menu_id],
                                 ["status", "=", 1],
                                 ["id", "!=", 174],
                             ])
                             ->orderBy("id", "ASC")
                             ->get();
                     } else {
                         $MainMenuTitlesSub = DB::table("main_menu_title")
                             ->select([
                                 "main_menu_id",
                                 "title",
                                 "title_id",
                                 "id",
                             ])
                             ->where([
                                 ["main_menu_id", "=", $row->main_menu_id],
                                 ["status", "=", 1],
                             ])
                             ->orderBy("id", "ASC")
                             ->get();
                     }

                     foreach ($MainMenuTitlesSub as $row1) { ?>
                     <li class="col-sm-2">
                        <ul>
                           <li class="dropdown-header"><?php echo $row1->title; ?> </li>
                           <?php
                           $InCompany = Session::get("run_company");
                           //if($InCompany != 1):
                           $data = DB::table("menu")
                               ->select([
                                   "m_type",
                                   "name",
                                   "m_controller_name",
                                   "m_main_title",
                                   "id",
                                   "m_parent_code",
                               ])
                               ->where([
                                   ["m_parent_code", "=", $row1->id],
                                   ["page_type", "=", 1],
                                   ["status", "=", 1],
                               ])
                               ->orderBy("id", "ASC")
                               ->get();
                           //else:
                           //  $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->whereNotIn('id', [309,310,311])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->orderBy('order_by', 'ASC')->get();
                           //endif;
                           foreach ($data as $dataValue) {
                               $MakeUrl = url(
                                   "" . $dataValue->m_controller_name . ""
                               ); ?>
                           <li><a href="<?php echo url(
                               "" .
                                   $dataValue->m_controller_name .
                                   "?pageType=" .
                                   $dataValue->m_type .
                                   "&&parentCode=" .
                                   $dataValue->m_parent_code .
                                   "&&m=" .
                                   Session::get("run_company") .
                                   "#signsnow"
                           ); ?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $dataValue->name; ?></a></li>
                           <?php
                           }
                           ?>
                        </ul>
                     </li>
                     <?php }
                     ?>
                  </ul>
               </li>
            </ul>
            <?php }
            ?>
            @endif
         </div>
      </nav>
   </div>
</div>
<a id="button"></a>
<?php if ($UserId == 104 || $UserId == 171): ?>
<style>
 /* feedback form css */
 .slide_in{}
.slide_out{}
.sliding_form{/* Permalink - use to edit and share this gradient:http://colorzilla.com/gradient-editor/#f09819+0,ff5858+100 */
 background:#f09819;/* Old browsers */
 background:-moz-linear-gradient(top,#ccc 0%,#2a6496 100%);/* FF3.6-15 */
 background:linear-gradient(to bottom,#121111 0%,#121111 100%);/* Chrome10-25,Safari5.1-6 */
 background:linear-gradient(to bottom,#121111 0%,#121111 100%);/* W3C,IE10+,FF16+,Chrome26+,Opera12+,Safari7+ */
 filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#f09819',endColorstr='#ff5858',GradientType=0);/* IE6-9 */
 position:fixed;right:0;bottom:0;top:200px;border-radius:0px;width:345px;z-index:9999;}
.sliding_form_inner{padding:30px 20px;width:100%;height:490px;overflow:auto;}
#form_trigger{border-radius:0px;color:#fff;font-family:"Roboto",sans-serif;font-size:14px;font-weight:bold;left:-146px;padding:10px 20px;position:absolute;text-transform:uppercase;top:72%;transform:rotate(-90deg);transform-origin:117px 11px 0;background:#f09819;/* Old browsers */
 background:-moz-linear-gradient(top,#ccc 0%,#2a6496 100%);/* FF3.6-15 */
 background:-webkit-linear-gradient(top,#ccc 0%,#2a6496 100%);/* Chrome10-25,Safari5.1-6 */
 background:linear-gradient(to bottom,#ccc 0%,#2a6496 100%);/* W3C,IE10+,FF16+,Chrome26+,Opera12+,Safari7+ */
 filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#f09819',endColorstr='#f67f31',GradientType=0);/* IE6-9 */
}
#form_trigger:hover,#form_trigger:focus{text-decoration:none;}
.sliding_form_inner .form-group{display:inline-block;height:auto;margin-bottom:0 !important;padding:10px 0;width:100%;}
.sliding_form_inner .form-group label{font-size:18px;color:#fff;font-family:'Roboto',sans-serif;font-weight:normal;margin-right:20px;}
.sliding_form_inner .form-group .fields_box{background:#ebebec;border:none;width:100%;height:35px;padding:0 0 0 15px;border-radius:5px;}
.sliding_form_inner span{font-size:16px;font-family:'Roboto',sans-serif;color:#fff;}
.sliding_form_inner textarea{background:#ebebec none repeat scroll 0 0;border:medium none;border-radius:5px;height:100px;overflow:auto;padding:10px 0 0 15px;resize:none;width:100%;}
.sliding_form_inner .submit_btn{font-size:16px;font-family:'Roboto',sans-serif;background:#252525;border-radius:5px;border:none;color:#fff;padding:10px 20px;}
.sliding_form_inner .submit_btn:hover,.sliding_form_inner .submit_btn:focus{background:#000;}
@media(max-width:1024px) and (min-width:767px){.sliding_form_inner .form-group .fields_box{margin-bottom:10px;}
.sliding_form_inner .form-group{padding:0px;}
.sliding_form_inner{height:auto;}
}
@media(max-width:767px){.sliding_form{height:auto;width:70%;top:50px;}
.sliding_form_inner{padding:10px;height:300px;display:inline-block;}
.sliding_form_inner .form-group .fields_box{margin-bottom:10px;}
.sliding_form_inner .form-group{padding:0px;}
}
</style>
<div style="display:none;" class="sliding_form slide_out">
   <a href="#" id="form_trigger" style="background: #121111 !important;" onclick="getOnlineUsersAjax()">Online User's</a>
   <div class="sliding_form_inner">
      <h3>Online User's</h3>
      <hr>
      <span id="AjaxDataOnlineUsers"></span>
   </div>
</div>
<?php endif; ?>
<script !src="">
   $(document).ready(function() {

       var formWidth = $('.sliding_form').width();
       $('.sliding_form').css('right', '-' + formWidth + 'px');
       $("#form_trigger").on('click', function() {

           if ($('.sliding_form').hasClass('slide_out')) {
               $('.sliding_form').removeClass('slide_out').addClass('slide_in')
               $(".sliding_form").animate({ right: 0 + 'px' });

               $('#AjaxDataOnlineUsers').html('<div class="loader"></div>');
               var m = '<?php echo $m; ?>';
               $.ajax({
                   url: '/pdc/getOnlineUserAjax',
                   type: 'Get',
                   data: {m:m},

                   success: function (response)
                   {
                       $('#AjaxDataOnlineUsers').html(response);
                   }
               });

           } else {
               $('.sliding_form').removeClass('slide_in').addClass('slide_out')
               $('.sliding_form').animate({ right: '-' + formWidth + 'px' });

           }

       });


   });
   
</script>





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



<input type="hidden" id="baseUrl" value="<?php echo url("/"); ?>">
<input type="hidden" id="emp_code" value="<?php echo Auth::user()
    ->emp_code; ?>">
<!-- MENU SECTION END-->
