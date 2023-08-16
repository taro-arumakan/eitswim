<?php
$active = array();
for($i=0;$i<10;$i++) {
    $active[] = '';
}
if(!isset($template)) {
    $active[2] = 'active';
} else if($template === 'index') {
    $active[2] = 'active';
} else if($template === 'form') {
    $active[0] = 'active';
} else if($template === 'detail') {
    $active[2] = 'active';
}
?>
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="/" style="color: #fff;font-size: 16px;margin-top: 22px;" class="logo-default">お問い合わせ管理</a>
            <div class="menu-toggler sidebar-toggler">
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu" style="margin-top: 17px;margin-right: 15px;">
                <a class="btn btn-default" href="<?php echo R() ?>user/logout/" role="button">ログアウト</a>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- END SIDEBAR -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item start <?php echo $active[0];?>">
                    <a href="<?php echo RM();?>form">
                        <i class="icon-list"></i>
                        <span class="title">フォーム一覧</span>
                        <span class="selected"></span>
                        <span class="arrow"></span>
                    </a>
                </li>
                <li class="nav-item <?php echo $active[1];?>">
                    <a href="<?php echo RM();?>form/?cmd=add">
                        <i class="icon-plus"></i>
                        <span class="title">フォーム登録</span>
                        <span class="selected"></span>
                        <span class="arrow"></span>
                    </a>
                </li>
                <li class="nav-item <?php echo $active[2];?>">
                    <a href="<?php echo RM();?>">
                        <i class="icon-list"></i>
                        <span class="title">履歴一覧</span>
                        <span class="selected"></span>
                        <span class="arrow"></span>
                    </a>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
