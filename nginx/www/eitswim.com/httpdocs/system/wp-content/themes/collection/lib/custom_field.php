<?php

function custom_admin_menu() {
  locate_template('lib/cf_category.php', true);
  locate_template('lib/cf_category_image.php', true);
  locate_template('lib/cf_collection_link.php', true);
}
custom_admin_menu();
