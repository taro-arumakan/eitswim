<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Pri_Cat_Box {
    
    public function __construct() {
        
        add_action( 'save_post', array( $this, 'pc_field_data' ) );
        add_action('admin_footer', array( $this, 'pc_radio' ));
        
    }

    public function pc_radio() {

                // Retrieve the taxonomy name 
        $screen = get_current_screen(); 
        
            //If not on the screen with ID 'edit-post' abort.
        if( !is_admin() || $screen->parent_base !='edit') {
            return;
        }

        //Check if custom post type hasn't taxonomies
        $mytax=get_post_type_object($screen->post_type);
        if ($screen->post_type != 'post' && empty($mytax->taxonomies)) return;

        global $post;

        $primary_category = '';
    
        // Retrieve data from primary_category custom field 
        $current_selected = get_post_meta( $post->ID, 'primary_category', true );
    
        // Retrieve the taxonomy name 
        $taxarray=get_object_taxonomies( $screen->post_type );
        $taxonomy_name=$taxarray[0];
        $terms = get_terms( $taxonomy_name );


        //Add a jquery script
        $html = '<script language="javascript">

                    jQuery(document).ready(function($) {';

        foreach( $terms as $term ) {

            // Set variable so that radio element displays the set primary category on page load
            $sel = ( $current_selected == $term->term_id ) ? ' checked' : '';
            $rd='<input id="pc_' . $term->term_id  . '" name="nw_primary_'.$taxonomy_name.'" type="radio" value="' . $term->term_id  . '"'.$sel.' />';
            $html .= '$("#'.$taxonomy_name.'checklist #'.$taxonomy_name.'-' . $term->term_id . ' input:checked").before(\''.$rd.'\');'."\n";
        }
    
        $html .= '$("#'.$taxonomy_name.'checklist").on(\'change\', \'input[type=checkbox]\', function(e) {
            str = e.target.id;
            var pieces = str.split(/[\s-]+/);
            nm=pieces[pieces.length-1];
            if (!$("#pc_"+nm).length) {
                $(e.target).before(\'<input id="pc_\'+nm+\'" name="nw_primary_'.$taxonomy_name.'" type="radio" value="\'+nm+\'" />\')
            } else {
               $("#pc_"+nm).remove(); 
            }});';

        $html .= '$(document).ajaxComplete(function(event, xhr, settings) {
                   if( settings.data==undefined ) return;
                    var queryStringArr = settings.data.split(\'&\');
                    if ($.inArray(\'action=add-'.$taxonomy_name.'\', queryStringArr) !== -1) {
                        var strf = $("#'.$taxonomy_name.'checklist li").first().attr("id");
                        var fp = strf.split(/[\s-]+/);
                        nm=fp[fp.length-1];
                        $("#'.$taxonomy_name.'checklist input").first().before(\'<input id="pc_\'+nm+\'" name="nw_primary_'.$taxonomy_name.'" type="radio" value="\'+nm+\'" />\');
                    }
                });';
                $msg=sprintf('<br>%s<strong>%s</strong><br>',__('Please, use the radio button for ','primary-category'), __('primary category','primary-category'));

                $html .= '$("#'.$taxonomy_name.'-adder a").before(\''.$msg.'\')';

        $html .= '});</script>';
    
        echo $html;
        
    }
    
    public function pc_field_data() {
        
      if(function_exists('get_current_screen')){
        global $post;

        $screen = get_current_screen();
        $taxarray=get_object_taxonomies( $screen->post_type );
        $taxonomy_name=$taxarray[0];

        if ( isset( $_POST[ 'nw_primary_'.$taxonomy_name ] ) ) {

          $primary_category = sanitize_text_field( $_POST[ 'nw_primary_'.$taxonomy_name ] );

          update_post_meta( $post->ID, 'primary_category', $primary_category );

        }
      }

    }
    
}