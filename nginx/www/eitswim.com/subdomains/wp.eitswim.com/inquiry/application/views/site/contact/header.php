<?php
$common_path = param('common_path');

if(empty($isSecure)) {
  $isSecure = false;
}
$http = 'http';
if($isSecure) {
  $http = 'https';
}
$title = 'CONTACT';
if(!empty($_GET['kind'])) {
  if($_GET['kind']=='conf') {
    $title = 'CONTACT';
  }
  if($_GET['kind']=='complete') {
    $title = 'CONTACT';
  }
}

?>

<body itemscope itemtype="https://schema.org/Brand">

<!-- Wrapper -->
<div id="wrapper">

  <!--HEADER-->
  <header id="header" class="header-logo-center header-sticky-resposnive">
    <div id="header-wrap">
      <div class="container">
        <!--Logo-->
        <div id="logo">
          <a itemprop="url" href="index.html" class="logo" data-dark-logo="/system/wp-content/themes/eitswim/images/logo-dark.png"> <img itemprop="logo" src="/system/wp-content/themes/eitswim/images/logo.png" alt="Logpose Logo"> </a>
        </div><!--End: Logo-->

        <!--Navigation Resposnive Trigger-->
        <div id="mainMenu-trigger">
          <button class="lines-button x"> <span class="lines"></span> </button>
        </div><!--end: Navigation Resposnive Trigger-->

        <!--Navigation-->
        <div id="mainMenu" class="menu-hover-background light">
          <div class="container">
            <nav>
              <!--Left menu-->
              <ul>
                <li><a href="index.html">Home</a></li>
                <li class="dropdown">
                  <a href="/colection">Colection</a>
                  <ul class="dropdown-menu">
                    <li><a href="/collection/city">2018 AW COLLECTION CITY</a></li>
                    <li class="last-child"><a href="/collection/surf">2018 AW COLLECTION SURF</a></li>
                  </ul>
                </li>
                <li><a href="/stockist">Stockist</a></li>
              </ul>
              <!--Right Menu-->
              <ul>
                <li><a href="/profile">Profile</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/media">Shop</a></li>
              </ul>
            </nav>
          </div>
        </div><!--end: Navigation-->

      </div>
    </div>
  </header><!--END: HEADER-->

  <!-- Page title -->
  <section id="page-title" class="page-title-center p-t-80 p-b-80 dark" style="background:#fff; border:none;">
    <div class="container">
      <div class="page-title">
        <h1 style="font-size:24px; line-height:28px; letter-spacing:0.05em;"><?php echo $title;?></h1>
      </div>
    </div>
    <div class="breadcrumb">
      <ul>
        <li><a href="/">HOME</a></li>
        <li class="active"><?php echo $title;?></li>
      </ul>
    </div>
  </section><!-- end: Page title -->
