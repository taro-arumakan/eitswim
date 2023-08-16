<?php

namespace MW_WP_Form_reCAPTCHA\Controllers;

use MW_WP_Form_reCAPTCHA\Config;

class EnqueueController
{

    public function __construct()
    {
echo "<script>console.log('__construct' );</script>";
        add_action('wp_enqueue_scripts', array($this, 'add_scripts'), 150);
    }

    public function add_scripts()
    {
echo "<script>console.log('add_scripts' );</script>";
        global $post;
        $option = get_option(Config::OPTION);
        $site_key = esc_html($option['site_key']);
        if (!empty($post) && has_shortcode($post->post_content, 'mwform_formkey') && !empty($site_key)) {
echo "<script>console.log('wp_enqueue' );</script>";
            //wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
            wp_enqueue_script("recaptcha-script", 'https://www.google.com/recaptcha/api.js?render=' . $site_key, array(), array(), true);

            $data = <<< EOL
grecaptcha.ready(function() {
    grecaptcha.execute('$site_key', {
            action: 'homepage'
        }).then(function(token) {
            var recaptchaResponse = jQuery('input[name="recaptcha-v3"]');
            recaptchaResponse.val(token);
        });
    });
EOL;
            wp_add_inline_script('mw-wp-form-recaptcha-script', $data);
        }
    }
}
