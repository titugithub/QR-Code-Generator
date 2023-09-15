<?php

/*
 * Plugin Name: QR Code Generator
 */

function qr_code_content($content) {
    // Get the post ID and post type
    $post_id = get_the_ID();
    $post_type = get_post_type($post_id);

    // Define an array of post types where you want to display the QR code
    $post_type_filter = apply_filters('qr_post_type_change', array());

    // Check if the current post type is in the allowed list
    if (!in_array($post_type, $post_type_filter)) {
        return $content; // If not, return the original content
    }

    // Get the post title and permalink
    $post_title = get_the_title($post_id);
    $post_permalink = urldecode(get_permalink($post_id));
    $width = get_option('qr_setting_width_id');

    $height = get_option('qr_setting_height_id');

    // Define the dimension of the QR code (default is 150x150)
    $dimension = apply_filters('qr_code_dimension', "{$width}x{$height}");
    $image_attr = apply_filters('qr_code_image_attr', '');
  

    // Generate the QR code URL
    $src = sprintf("https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s", $dimension, $post_permalink);

    // Append the QR code image to the content
    $qr_code_image = sprintf("<img %s src='%s' alt='%s'/>",$image_attr, $src, $post_title);
    $content .= $qr_code_image;

    return $content;
}

add_filter('the_content', 'qr_code_content');

function qr_code_option(){
    add_settings_field( 'qr_setting_width_id',
	'Qr code width',
 	'qr_width_setting_callback_function',
	'general',
);
    add_settings_field( 'qr_setting_height_id',
	'Qr code Height',
 	'qr_height_setting_callback_function',
	'general',
);

register_setting('general','qr_setting_width_id',array('sanitize_callback' => 'esc_attr'));
register_setting('general','qr_setting_height_id',array('sanitize_callback' => 'esc_attr'));




}


add_action('admin_init','qr_code_option');

function qr_width_setting_callback_function($arg){
    $width = get_option('qr_setting_width_id');
    printf("<input type= 'text' id='%s' name = '%s' value = '%s'/>",'qr_setting_width_id','qr_setting_width_id',$width);
}
function qr_height_setting_callback_function($arg){
    $height = get_option('qr_setting_height_id');
    printf("<input type= 'text' id='%s' name = '%s' value = '%s'/>",'qr_setting_height_id','qr_setting_height_id',$height);
}

?>
