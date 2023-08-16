<?php

if(empty($isSecure)) {
	$isSecure = false;
}
$http = 'http';
if($isSecure) {
	$http = 'https';
}

?>
<!-- FOOTER -->
<footer id="footer" class="p-t-50 p-b-50">
  <div class="footer-content">
    <div class="container">
      <div class="row text-center">
        <!-- Social -->
        <div class="clearfix">
          <div class="center social-icons social-icons-medium social-icons-rounded social-icons-colored-hover">
            <ul>
              <li class="social-instagram"><a href=" " target="_blank"><i class="fa fa-instagram"></i></a></li>
            </ul>
          </div><!-- end: Social -->
        </div>
        <div class="copyright-text text-center p-t-0">
          <p class="m-b-0"><a href="/news">News</a> / <a href="/privacy">Privacy Policy</a></p>
          Copyright &copy; eit swim All Rights Reserved.
        </div>
      </div>
    </div>
  </div>
</footer><!-- END: FOOTER -->

</div><!-- end: Wrapper -->

<!-- Go to top button -->
<a id="goToTop"><i class="fa fa-angle-up top-icon"></i><i class="fa fa-angle-up"></i></a>
<!--Plugins-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="/system/wp-content/themes/eitswim/js/plugins.js"></script>

<!-- functions JS -->
<script src="/system/wp-content/themes/eitswim/js/functions.js"></script>

</body>
</html>
