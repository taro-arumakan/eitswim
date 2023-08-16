<?php
$title='';
if(isset($_SERVER['SCRIPT_NAME'])) {
	if($_SERVER['SCRIPT_NAME'] == '/contact/index.php') {
		$title = 'Logpose管理';
	}
}
$u = $model->hasErrors('username');
$p = $model->hasErrors('password');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
  <meta charset="utf-8">
  <title>Logpose | Sign in</title>
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

<!-- end::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
  <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-3" id="m_login" style="background-image: url(/admin/assets/app/media/img//bg/bg-2.jpg);">
    <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
      <form class="login-form" action="<?php echo R()?>user/login/" method="post">
        <div class="m-login__container">
          <div class="m-login__logo"> <a href="#"> <img src="/admin/assets/app/media/img/logos/logo_dark.png"> </a> </div>
          <div class="m-login__signin">
            <div class="m-login__head">
              <h3 class="m-login__title"> Sign In To Admin </h3>
            </div>
            <form class="m-login__form m-form" action="">
							<?php if($u):?>
                <div class="alert alert-danger" role="alert">
                  <strong>警　告</strong>：ユーザーIDが違います。
                </div>
							<?php endif;?>
							<?php if($p):?>
                <div class="alert alert-danger" role="alert">
                  <strong>警　告</strong>：パスワードが違います。
                </div>
							<?php endif;?>
              <div class="form-group m-form__group">
                <input class="form-control m-input"   type="text" placeholder="ユーザーID" name="UserLogin[username]" autocomplete="off"
                       value="<?php echo $model->username;?>">
              </div>
              <div class="form-group m-form__group">
                <input class="form-control m-input m-login__form-input--last" type="password" placeholder="パスワード" name="UserLogin[password]"
                       value="<?php echo $model->password;?>">
              </div>
              <div class="m-login__form-action">
                <button type="submit" id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn"> Sign In </button>
              </div>
              <input type="hidden" name="UserLogin[rememberMe]" value="1"/>

            </form>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- end:: Page -->

<script src="/admin/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="/admin/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Page Snippets -->
<!--
<script src="/admin/assets/snippets/pages/user/login.js" type="text/javascript"></script>
-->
<!--end::Page Snippets -->
</body>
<!-- end::Body -->
</html>
