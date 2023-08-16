<?php
$CLASS = 'm-menu__item--active';
$class_array = array();
$class_array['dashboard'] = '';
$class_array['advertisement'] = '';
$class_array['support'] = '';
$class_array['inquiry'] = '';
$class_array['others'] = '';
$class_array['all'] = '';
if(!isset($action)) {
    $class_array['dashboard'] = $CLASS;
} else if($action === 'advertisement') {
    $class_array['advertisement'] = $CLASS;
} else if($action === 'support') {
    $class_array['support'] = $CLASS;
} else if($action === 'inquiry') {
    $class_array['inquiry'] = $CLASS;
} else if($action === 'others') {
    $class_array['others'] = $CLASS;
} else if($action === 'all') {
    $class_array['all'] = $CLASS;
} else {
    $class_array['dashboard'] = $CLASS;
}
//URLに付加する
$ses = '?' . date("YmdHis");


?>

<!DOCTYPE html>
<html lang="ja-JP">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <title>Logpose | Dashboard</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <!--begin::Page Vendors -->
    <link href="/admin/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css">
    <!--end::Page Vendors -->
    <link href="/admin/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/demo/demo2/base/style.bundle.css" rel="stylesheet" type="text/css">
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="/admin/assets/demo/demo2/media/img/logo/favicon.png">
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">

    <header class="m-grid__item		m-header "  data-minimize="minimize" data-minimize-offset="200" data-minimize-mobile-offset="200" >
        <div class="m-header__top">
            <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
                <div class="m-stack m-stack--ver m-stack--desktop">
                    <!-- begin::Brand -->
                    <div class="m-stack__item m-brand">
                        <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
                            <div class="m-stack__item m-stack__item--middle m-brand__logo">
                                <a href="<?php echo RA() ?>top" class="m-brand__logo-wrapper"> <img alt="" src="/admin/assets/demo/demo2/media/img/logo/logo.png"> </a>
                            </div>
                            <div class="m-stack__item m-stack__item--middle m-brand__tools">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-left m-dropdown--align-push" data-dropdown-toggle="click" aria-expanded="true">
                                    <a href="#" class="dropdown-toggle m-dropdown__toggle btn btn-outline-metal m-btn  m-btn--icon m-btn--pill"> <span> All Mails </span> </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--left m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__section m-nav__section--first m--hide"> <span class="m-nav__section-text"> Quick Menu </span> </li>
                                                        <li class="m-nav__item"> <a href="<?php echo RA() ?>top" class="m-nav__link"> <i class="m-nav__link-icon flaticon-chat-1"></i> <span class="m-nav__link-text"> Dashboard </span> </a> </li>
                                                        <li class="m-nav__item"> <a href="<?php echo RA()?>history/all" class="m-nav__link"> <i class="m-nav__link-icon flaticon-chat-1"></i> <span class="m-nav__link-text"> All mails </span> </a> </li>
                                                        <li class="m-nav__item"> <a href="<?php echo RA()?>history/advertisement" class="m-nav__link"> <i class="m-nav__link-icon flaticon-chat-1"></i> <span class="m-nav__link-text"> Advertisement </span> </a> </li>
                                                        <li class="m-nav__item"> <a href="<?php echo RA()?>history/support" class="m-nav__link"> <i class="m-nav__link-icon flaticon-chat-1"></i> <span class="m-nav__link-text"> Support </span> </a> </li>
                                                        <li class="m-nav__item"> <a href="<?php echo RA()?>history/inquiry" class="m-nav__link"> <i class="m-nav__link-icon flaticon-chat-1"></i> <span class="m-nav__link-text"> Inquiry </span> </a> </li>
                                                        <li class="m-nav__item"> <a href="<?php echo RA()?>history/advertisement/others" class="m-nav__link"> <i class="m-nav__link-icon flaticon-chat-1"></i> <span class="m-nav__link-text"> Others </span> </a> </li>
                                                        <li class="m-nav__item"> <a href="<?php echo R() ?>user/logout/" class="m-nav__link"> <i class="m-nav__link-icon flaticon-share"></i> <span class="m-nav__link-text"> Logout </span> </a> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- begin::Responsive Header Menu Toggler-->
                                <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block"> <span></span> </a>
                                <!-- end::Responsive Header Menu Toggler-->
                                <!-- begin::Topbar Toggler-->
                                <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block"> <i class="flaticon-more"></i> </a>
                                <!--end::Topbar Toggler-->
                            </div>
                        </div>
                    </div>
                    <!-- end::Brand -->
                    <!-- begin::Topbar -->
                    <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                        <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                            <div class="m-stack__item m-topbar__nav-wrapper">
                                <ul class="m-topbar__nav m-nav m-nav--inline">
                                    <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click"> <a href="#" class="m-nav__link m-dropdown__toggle"> <span class="m-topbar__userpic m--hide"> <img src="/admin/assets/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless m--img-centered" alt=""> </span> <span class="m-topbar__username"> Studio E.L.C </span> </a>
                                        <div class="m-dropdown__wrapper"> <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <ul class="m-nav m-nav--skin-light">
                                                            <li class="m-nav__item"> <a href="<?php echo R() ?>user/logout/" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder"> Logout </a> </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end::Topbar -->
                </div>
            </div>
        </div>
        <div class="m-header__bottom">
            <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
                <div class="m-stack m-stack--ver m-stack--desktop">
                    <!-- begin::Horizontal Menu -->
                    <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
                        <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn"> <i class="la la-close"></i> </button>
                        <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light "  >
                            <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                                <li class="m-menu__item"  aria-haspopup="true"> <a  href="<?php echo RA() ?>top" class="m-menu__link "> <span class="m-menu__item-here"></span> <span class="m-menu__link-text"> Dashboard </span> </a> </li>
                                <li class="m-menu__item  m-menu__item--active m-menu__item--submenu m-menu__item--rel"  data-menu-submenu-toggle="click" aria-haspopup="true">
                                    <a  href="#" class="m-menu__link m-menu__toggle">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">
                      Inquiry List
                    </span>
                                        <i class="m-menu__hor-arrow la la-angle-down"></i>
                                        <i class="m-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                                        <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                        <ul class="m-menu__subnav">
                                            <li class="m-menu__item "  aria-haspopup="true">
                                                <a  href="<?php echo RA()?>history/all" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-symbol"></i>
                                                    <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                              <span class="m-menu__link-text">
                                All Mails
                              </span>
                            </span>
                          </span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item "  aria-haspopup="true">
                                                <a  href="<?php echo RA()?>history/advertisement" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-symbol"></i>
                                                    <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                              <span class="m-menu__link-text">
                                Advertisement
                              </span>
                            </span>
                          </span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item "  aria-haspopup="true">
                                                <a  href="<?php echo RA()?>history/support" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-symbol"></i>
                                                    <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                              <span class="m-menu__link-text">
                                Support
                              </span>
                            </span>
                          </span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item "  aria-haspopup="true">
                                                <a  href="<?php echo RA()?>history/inquiry" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-symbol"></i>
                                                    <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                              <span class="m-menu__link-text">
                                Inquiry
                              </span>
                            </span>
                          </span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item "  aria-haspopup="true">
                                                <a  href="<?php echo RA()?>history/others" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-symbol"></i>
                                                    <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                              <span class="m-menu__link-text">
                                Others
                              </span>
                            </span>
                          </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end::Horizontal Menu -->
                </div>
            </div>
        </div>
    </header>

