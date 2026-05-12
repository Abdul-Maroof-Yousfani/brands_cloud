<?php
use App\Helpers\CommonHelper;
$UserId = Auth::guard('reseller')->id();
CommonHelper::reconnectMasterDatabase();
?>
<style>
    img.logo_m { width: 225px; }
    .dropdown:hover>.dropdown-menu { display: block; margin-top: 0; }
    #mySidenav .pmastermnu ul li a { color: #fff; text-decoration: none; padding-left: 10px; font-size: 13px; opacity: 0.8; transition: opacity 0.3s; }
    #mySidenav .pmastermnu ul li a:hover { opacity: 1; }
    #mySidenav .pmastermnu ul li { padding: 8px 0; margin-left: 25px; }
    #mySidenav .pmastermnu ul li span i { color: #fff; opacity: 0.8; font-size: 11px; }
</style>
@include('select2')
<div id="mySidenav" class="sidenavnr">
    <div class="logo_wrp">
        <a href="{{ route('reseller.dashboard') }}">
            <img class="logo_m" src="{{ url('/logoo.png') }}">
        </a>
        <div class="nav navbar-nav" style="float: right; align-items: center">
            <ul class=" tmenu-list d">
                <li>
                    <div class="o_f">
                        <a href="#" class="closebtn theme-f-clr Navclose"><i class="fa fa-list-ul" aria-hidden="true" style="color: white; margin-top: 30px;"></i></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Hardcoded Sidebar for Reseller -->
    <ul class="m_list " id="myGroup">
        <li>
            <div class="sm-bx">
                <a href="{{ route('reseller.dashboard') }}" style="text-decoration:none;">
                    <button class="btn settingListSb theme-bg">
                        <span><i class="glyphicon glyphicon-home" aria-hidden="true"></i></span>
                        <p>Dashboard</p>
                    </button>
                </a>
            </div>
        </li>
        <li>
            <div class="sm-bx">
                <button class="btn settingListSb theme-bg" data-toggle="collapse" data-target="#soRequests">
                    <span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                    <p>SO Requests</p>
                </button>
                <div id="soRequests" class="collapse pmastermnu">
                    <ul class="list-unstyled">
                        <li class="dd">
                            <ul class="list-unstyled">
                                <li>
                                    <span><i class="fal fa-circle-notch"></i></span>
                                    <a href="{{ route('reseller.so.create') }}">Create SO Request</a>
                                </li>
                                <li>
                                    <span><i class="fal fa-circle-notch"></i></span>
                                    <a href="{{ route('reseller.so.list') }}">SO Request List</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        <li>
            <div class="sm-bx">
                <button class="btn settingListSb theme-bg" data-toggle="collapse" data-target="#inventoryMenu">
                    <span><i class="fa fa-list" aria-hidden="true"></i></span>
                    <p>Inventory</p>
                </button>
                <div id="inventoryMenu" class="collapse pmastermnu">
                    <ul class="list-unstyled">
                        <li class="dd">
                            <ul class="list-unstyled">
                                <li>
                                    <span><i class="fal fa-circle-notch"></i></span>
                                    <a href="{{ route('reseller.inventory.stock') }}">My Stock</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>

<div class="container-fluid head-sh">
    <div class="headerwrap">
        <ul class='ctn-list'>
            <li>
                <div class="tim d">
                    <h3 id="live-time">{{ date('h:i:s') }}<span>{{ date('A') }}</span></h3>
                </div>
            </li>
        </ul>

        <ul class="profile-admin d-flex">
            <li>
                <div class="pro-user d-flex">
                    <span class="avatar">
                        <img class="round" src="{{ url('assets/img/avatar.png') }}" alt="avatar" height="40" width="40" onerror="this.src='{{ url('logoo.png') }}';">
                    </span>
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{ Auth::guard('reseller')->user()->email }}</span></div>
                </div>
            </li>
            <li class="dropdown user-name-drop">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                <div class="account-information dropdown-menu">
                    <div class="account-inner d-flex">
                        <div class="davtar">
                            <span class="avatar">
                                <img style="width: 100px;" class="round" src="{{ url('assets/img/avatar.png') }}" alt="avatar" onerror="this.src='{{ url('logoo.png') }}';">
                            </span>
                        </div>
                        <div class="main-heading">
                            <h5>{{ Auth::guard('reseller')->user()->email }}</h5>
                            <p>Reseller Portal</p>
                        </div>
                    </div>
                    <div class="account-footer">
                        <a href="{{ route('reseller.logout') }}" class="btn btn-danger">Sign out</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<a id="button"></a>
