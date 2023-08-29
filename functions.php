<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'astra-theme-css' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// function get_file_name_from_database($atts) {
//     $atts = shortcode_atts(array(
//         'field_name' => 'summery_report',
//     ), $atts);

//     global $wpdb, $post;
//     $table_name = $wpdb->prefix . 'postmeta'; // Assuming the ACF field is stored in the postmeta table

//     $file_id = $wpdb->get_var(
//         $wpdb->prepare(
//             "SELECT meta_value FROM $table_name WHERE meta_key = %s AND post_id = %d",
//             $atts['field_name'],
//             $post->ID
//         )
//     );

//     if ($file_id) {
//         $file_name = get_post_meta($file_id, '_wp_attached_file', true);
//         if ($file_name) {
//             $file_name = basename($file_name);
//             $post_link = get_permalink();
//             $file_link = '<a href="' . $post_link . '">' . $file_name . '</a>';
//             return $file_link;
//         }
//     }

//     return 'No file uploaded.';
// }
// add_shortcode('file_name_shortcode', 'get_file_name_from_database');
// //file size
// function get_file_info_from_database($atts) {
//     $atts = shortcode_atts(array(
//         'field_name' => 'summery_report',
//     ), $atts);

//     global $wpdb, $post;
//     $table_name = $wpdb->prefix . 'postmeta'; // Assuming the ACF field is stored in the postmeta table

//     $file_id = $wpdb->get_var(
//         $wpdb->prepare(
//             "SELECT meta_value FROM $table_name WHERE meta_key = %s AND post_id = %d",
//             $atts['field_name'],
//             $post->ID
//         )
//     );

//     if ($file_id) {
//         $file_name = get_post_meta($file_id, '_wp_attached_file', true);
//         if ($file_name) {
//             $file_name = basename($file_name);
//             $file_size = filesize(get_attached_file($file_id));
//             $file_size_formatted = size_format($file_size);

//             $file_link = wp_get_attachment_url($file_id);
//             $download_link = '<a href="' . $file_link . '" download>' . $file_size_formatted . '</a>';
//             return $download_link;
//         }
//     }
// }
// add_shortcode('file_info_shortcode', 'get_file_info_from_database');
//Symbol

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Short Codes for nested categories posts to display
// Shortcode for nested post titles
function display_nested_meetinginformation_titles_shortcode($atts) {
    global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'meeting_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_title = get_the_title($nested_post_id);
            $nested_post_link = get_permalink($nested_post_id);
            $output .= '<a style="color:#55ADBD;" class="link-underline" href="' . $nested_post_link . '">' . $nested_post_title . '</a>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_meetinginformation_titles', 'display_nested_meetinginformation_titles_shortcode');
// Shortcode for file download links
function display_nested_meetinginformation_files_shortcode($atts) {
    global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'meeting_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
			$nested_post_file_path = get_attached_file($nested_post_file); // Get the file path
			$nested_post_file_size = filesize($nested_post_file_path); // Get the file size in bytes
			$output .= '<a class="link-underline" href="' . wp_get_attachment_url($nested_post_file) . '" download><i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i>' . format_file_size($nested_post_file_size) . '</a>';
			$output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_meetinginformation_files', 'display_nested_meetinginformation_files_shortcode');
// Function to format file size with decimal values
function format_file_size($size) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $decimal_places = 2;
    $i = 0;
    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }
    return number_format($size, $decimal_places) . ' ' . $units[$i];
}
// Shortcode for file symbols
function display_nested_meetinginformation_symbols_shortcode($atts) {

	global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'meeting_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_file_symbol = get_field('symbol', $nested_post_id); // Replace 'file_symbol' with the name of your custom field for the file symbol  
            $output .= '<span class="custom-gap">' . $nested_post_file_symbol . '</span>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_meetinginformation_symbols', 'display_nested_meetinginformation_symbols_shortcode');
// Shortcode for dates
function display_nested_meetinginformation_dates_shortcode($atts) {

	global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'meeting_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_date = get_field('meeting_data_date_of_issues', $nested_post_id);
			if($nested_post_date !== ''){
				$formatted_date = date('j F Y', strtotime($nested_post_date)); // Format the date as per your requirements
				$output .= '<span class="custom-gap">' . $formatted_date . '</span>';
				$output .= '<br><br><hr><br>';
			}
            else{
				$output .= '<br><br><hr><br>';
			}
        }
    }
    return $output;
}
add_shortcode('nested_meetinginformation_dates', 'display_nested_meetinginformation_dates_shortcode');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Shortcode for nested post titles
function display_nested_workingpaper_titles_shortcode($atts) {
   global $post;
   global $wpdb;
   $postID = $post->ID;

   $meetingIDs = array();

   $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'working_title'");

   if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_title = get_the_title($nested_post_id);
        $nested_post_link = get_permalink($nested_post_id);
        $output .= '<a style="color:#55ADBD;" class="link-underline" href="' . $nested_post_link . '">' . $nested_post_title . '</a>';
        $output .= '<br><br><hr><br>';
    }
}
return $output;
}
add_shortcode('nested_workingpaper_titles', 'display_nested_workingpaper_titles_shortcode');
// Shortcode for file download links
function display_nested_workingpaper_files_shortcode($atts) {
  global $post;
  global $wpdb;
  $postID = $post->ID;

  $meetingIDs = array();

  $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'working_title'");

  if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
            $nested_post_file_path = get_attached_file($nested_post_file); // Get the file path
            $nested_post_file_size = filesize($nested_post_file_path); // Get the file size in bytes
            $output .= '<a class="link-underline" href="' . wp_get_attachment_url($nested_post_file) . '" download><i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i>' . format_file_size($nested_post_file_size) . '</a>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_workingpaper_files', 'display_nested_workingpaper_files_shortcode');
// Shortcode for file symbols
function display_nested_workingpaper_symbols_shortcode($atts) {
   global $post;
   global $wpdb;
   $postID = $post->ID;

   $meetingIDs = array();

   $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'working_title'");

   if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file_symbol = get_field('symbol', $nested_post_id); // Replace 'file_symbol' with the name of your custom field for the file symbol  
            $output .= '<span class="custom-gap">' . $nested_post_file_symbol . '</span>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_workingpaper_symbols', 'display_nested_workingpaper_symbols_shortcode');
// Shortcode for dates
function display_nested_workingpaper_dates_shortcode($atts) {
  global $post;
  global $wpdb;
  $postID = $post->ID;

  $meetingIDs = array();

  $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'working_title'");

  if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_date = get_field('working_paper_date_of_issues', $nested_post_id);
		if($nested_post_date !== ''){
            $formatted_date = date('j F Y', strtotime($nested_post_date)); // Format the date as per your requirements
            $output .= '<span class="custom-gap">' . $formatted_date . '</span>';
            $output .= '<br><br><hr><br>';
        }
		else{
				$output .= '<br><br><hr><br>';
			}
    }
    return $output;
}
}
add_shortcode('nested_workingpaper_dates', 'display_nested_workingpaper_dates_shortcode');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Shortcode for nested post titles
function display_nested_presentation_files_titles_shortcode($atts) {
  global $post;
  global $wpdb;
  $postID = $post->ID;

  $meetingIDs = array();

  $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'presentation_title'");

  if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_title = get_the_title($nested_post_id);
        $nested_post_link = get_permalink($nested_post_id);
        $output .= '<a style="color:#55ADBD;" class="link-underline" href="' . $nested_post_link . '">' . $nested_post_title . '</a>';
        $output .= '<br><br><hr><br>';
    }
}
return $output;
}
add_shortcode('nested_presentation_files_titles', 'display_nested_presentation_files_titles_shortcode');
// Shortcode for file download links
function display_nested_presentation_files_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'presentation_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
            $nested_post_file_path = get_attached_file($nested_post_file); // Get the file path
            $nested_post_file_size = filesize($nested_post_file_path); // Get the file size in bytes
            $output .= '<a class="link-underline" href="' . wp_get_attachment_url($nested_post_file) . '" download><i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i>' . format_file_size($nested_post_file_size) . '</a>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_presentation_files', 'display_nested_presentation_files_shortcode');
// Shortcode for file symbols
function display_nested_presentation_files_symbols_shortcode($atts) {
  global $post;
  global $wpdb;
  $postID = $post->ID;

  $meetingIDs = array();

  $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'presentation_title'");

  if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file_symbol = get_field('symbol', $nested_post_id); // Replace 'file_symbol' with the name of your custom field for the file symbol  
            $output .= '<span class="custom-gap">' . $nested_post_file_symbol . '</span>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_presentation_files_symbols', 'display_nested_presentation_files_symbols_shortcode');
// Shortcode for dates
function display_nested_presentation_files_dates_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'presentation_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_date = get_field('file_date_of_issue', $nested_post_id);
		if($nested_post_date !== ''){
            $formatted_date = date('j F Y', strtotime($nested_post_date)); // Format the date as per your requirements
            $output .= '<span class="custom-gap">' . $formatted_date . '</span>';
            $output .= '<br><br><hr><br>';
        }
		else{
				$output .= '<br><br><hr><br>';
			}
	}
    }
    return $output;
}
add_shortcode('nested_presentation_files_dates', 'display_nested_presentation_files_dates_shortcode');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Shortcode for nested post titles
function display_nested_information_papers_titles_shortcode($atts) {
   global $post;
   global $wpdb;
   $postID = $post->ID;

   $meetingIDs = array();

   $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'information_title'");

   if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_title = get_the_title($nested_post_id);
        $nested_post_link = get_permalink($nested_post_id);
        $output .= '<a style="color:#55ADBD;" class="link-underline" href="' . $nested_post_link . '">' . $nested_post_title . '</a>';
        $output .= '<br><br><hr><br>';
    }
}
return $output;
}
add_shortcode('nested_information_papers_titles', 'display_nested_information_papers_titles_shortcode');
// Shortcode for file download links
function display_nested_information_papers_files_shortcode($atts) {
    global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'information_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
            $nested_post_file_path = get_attached_file($nested_post_file); // Get the file path
            $nested_post_file_size = filesize($nested_post_file_path); // Get the file size in bytes
            $output .= '<a class="link-underline" href="' . wp_get_attachment_url($nested_post_file) . '" download><i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i>' . format_file_size($nested_post_file_size) . '</a>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_information_papers_files', 'display_nested_information_papers_files_shortcode');
// Shortcode for file symbols
function display_nested_information_papers_symbols_shortcode($atts) {
    global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'information_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_file_symbol = get_field('symbol', $nested_post_id); // Replace 'file_symbol' with the name of your custom field for the file symbol  
            $output .= '<span class="custom-gap">' . $nested_post_file_symbol . '</span>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_information_papers_symbols', 'display_nested_information_papers_symbols_shortcode');
// Shortcode for dates
function display_nested_information_papers_dates_shortcode($atts) {
    global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'information_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_date = get_field('information_papers_date_of_issues', $nested_post_id);
			if($nested_post_date !== ''){
				$formatted_date = date('j F Y', strtotime($nested_post_date)); // Format the date as per your requirements
				$output .= '<span class="custom-gap">' . $formatted_date . '</span>';
				$output .= '<br><br><hr><br>';
        	}
			else{
				$output .= '<br><br><hr><br>';
			}
    	}
    return $output;
}
}
add_shortcode('nested_information_papers_dates', 'display_nested_information_papers_dates_shortcode');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Shortcode for nested post titles
function display_nested_delegation_proposals_and_papers_titles_shortcode($atts) {
   global $post;
   global $wpdb;
   $postID = $post->ID;

   $meetingIDs = array();

   $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'delegation_title'");

   if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_title = get_the_title($nested_post_id);
        $nested_post_link = get_permalink($nested_post_id);
        $output .= '<a style="color:#55ADBD;" class="link-underline" href="' . $nested_post_link . '">' . $nested_post_title . '</a>';
        $output .= '<br><br><hr><br>';
    }
}
return $output;
}
add_shortcode('nested_delegation_proposals_and_papers_titles', 'display_nested_delegation_proposals_and_papers_titles_shortcode');
// Shortcode for file download links
function display_nested_delegation_proposals_and_papers_files_shortcode($atts) {
    global $post;
    global $wpdb;
    $postID = $post->ID;

    $meetingIDs = array();

    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'delegation_title'");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
            $nested_post_file_path = get_attached_file($nested_post_file); // Get the file path
            $nested_post_file_size = filesize($nested_post_file_path); // Get the file size in bytes
            $output .= '<a class="link-underline" href="' . wp_get_attachment_url($nested_post_file) . '" download><i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i>' . format_file_size($nested_post_file_size) . '</a>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_delegation_proposals_and_papers_files', 'display_nested_delegation_proposals_and_papers_files_shortcode');
// Shortcode for file symbols
function display_nested_delegation_proposals_and_papers_symbols_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'delegation_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file_symbol = get_field('symbol', $nested_post_id); // Replace 'file_symbol' with the name of your custom field for the file symbol  
            $output .= '<span class="custom-gap">' . $nested_post_file_symbol . '</span>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_delegation_proposals_and_papers_symbols', 'display_nested_delegation_proposals_and_papers_symbols_shortcode');
// Shortcode for dates
function display_nested_delegation_proposals_and_papers_dates_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'delegation_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_date = get_field('proposals_date_of_issues', $nested_post_id);
		if($nested_post_date !== ''){
            $formatted_date = date('j F Y', strtotime($nested_post_date)); // Format the date as per your requirements
            $output .= '<span class="custom-gap">' . $formatted_date . '</span>';
            $output .= '<br><br><hr><br>';
        }
		else{
				$output .= '<br><br><hr><br>';
		}
	}
    }
    return $output;
}
add_shortcode('nested_delegation_proposals_and_papers_dates', 'display_nested_delegation_proposals_and_papers_dates_shortcode');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Shortcode for nested post titles
function display_nested_observer_paper_titles_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'observer_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_title = get_the_title($nested_post_id);
        $nested_post_link = get_permalink($nested_post_id);
        $output .= '<a style="color:#55ADBD;" class="link-underline" href="' . $nested_post_link . '">' . $nested_post_title . '</a>';
        $output .= '<br><br><hr><br>';
    }
}
return $output;
}
add_shortcode('nested_observer_paper_titles', 'display_nested_observer_paper_titles_shortcode');
// Shortcode for file download links
function display_nested_observer_paper_files_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'observer_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
            $nested_post_file_path = get_attached_file($nested_post_file); // Get the file path
            $nested_post_file_size = filesize($nested_post_file_path); // Get the file size in bytes
            $output .= '<a class="link-underline" href="' . wp_get_attachment_url($nested_post_file) . '" download><i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i>' . format_file_size($nested_post_file_size) . '</a>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_observer_paper_files', 'display_nested_observer_paper_files_shortcode');
// Shortcode for file symbols
function display_nested_observer_paper_symbols_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'observer_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file_symbol = get_field('symbol', $nested_post_id); // Replace 'file_symbol' with the name of your custom field for the file symbol  
            $output .= '<span class="custom-gap">' . $nested_post_file_symbol . '</span>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_observer_paper_symbols', 'display_nested_observer_paper_symbols_shortcode');
// Shortcode for dates
function display_nested_observer_paper_dates_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'observer_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_date = get_field('files_date_of_issues', $nested_post_id);
		if($nested_post_date !== ''){
            $formatted_date = date('j F Y', strtotime($nested_post_date)); // Format the date as per your requirements
            $output .= '<span class="custom-gap">' . $formatted_date . '</span>';
            $output .= '<br><br><hr><br>';
        }
		else{
				$output .= '<br><br><hr><br>';
		}
	}
    }
    return $output;
}
add_shortcode('nested_observer_paper_dates', 'display_nested_observer_paper_dates_shortcode');

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Shortcode for nested post titles
function display_nested_summary_report_titles_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'summery_meeting_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_title = get_the_title($nested_post_id);
        $nested_post_link = get_permalink($nested_post_id);
        $output .= '<a style="color:#55ADBD;" class="link-underline" href="' . $nested_post_link . '">' . $nested_post_title . '</a>';
        $output .= '<br><br><hr><br>';
    }
}
return $output;
}
add_shortcode('nested_summary_report_titles', 'display_nested_summary_report_titles_shortcode');
// Shortcode for file download links
function display_nested_summary_report_files_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'summery_meeting_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
            $nested_post_file_path = get_attached_file($nested_post_file); // Get the file path
            $nested_post_file_size = filesize($nested_post_file_path); // Get the file size in bytes
            $output .= '<a class="link-underline" href="' . wp_get_attachment_url($nested_post_file) . '" download><i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i>' . format_file_size($nested_post_file_size) . '</a>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_summary_report_files', 'display_nested_summary_report_files_shortcode');
// Shortcode for file symbols
function display_nested_summary_report_symbols_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'summery_meeting_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
            $nested_post_file_symbol = get_field('symbol', $nested_post_id); // Replace 'file_symbol' with the name of your custom field for the file symbol  
            $output .= '<span class="custom-gap">' . $nested_post_file_symbol . '</span>';
            $output .= '<br><br><hr><br>';
        }
    }
    return $output;
}
add_shortcode('nested_summary_report_symbols', 'display_nested_summary_report_symbols_shortcode');
// Shortcode for dates
function display_nested_summary_report_dates_shortcode($atts) {
 global $post;
 global $wpdb;
 $postID = $post->ID;

 $meetingIDs = array();

 $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'summery_meeting_title'");

 if(sizeof($results) > 0){
    foreach($results as $r){
        if(isset($r) && strlen($r->meta_value) > 0){
            $ids  = unserialize($r->meta_value);
            if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                $meetingIDs[] = $r->post_id;
            }
        }
    }
}

$output = '';

if(sizeof($meetingIDs) > 0){
    foreach($meetingIDs as $meetingID){
        $nested_post_id = $meetingID;
        $nested_post_date = get_field('summery_report_date_of_issues', $nested_post_id);
		if($nested_post_date !== ''){
            $formatted_date = date('j F Y', strtotime($nested_post_date)); // Format the date as per your requirements
            $output .= '<span class="custom-gap">' . $formatted_date . '</span>';
            $output .= '<br><br><hr><br>';
        }
		else{
				$output .= '<br><br><hr><br>';
		}
	}
    }
    return $output;
}
add_shortcode('nested_summary_report_dates', 'display_nested_summary_report_dates_shortcode');
///////////////////////////////////////////////////
//============================================================
// //To hide button for location online & physical
// function hide_button_based_on_acf_field() {
//     if (is_singular('meetings')) {
//         global $post;

//         // Retrieve the ACF field value
//         $acf_field_value = get_field('location', $post->ID); // Replace 'your_acf_field' with the name of your ACF field

//         // Check if the ACF field value meets the condition
//         if ($acf_field_value === 'Online') {
//             echo '<style>.register-btn-checkbox { display: none; }</style>';
//         } else {
//             echo '<style>.register-btn { display: none; }</style>';
//         }
//     }
// }
// add_action('wp_footer', 'hide_button_based_on_acf_field');
function hide_checkbox_based_on_location() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var locationFieldValue = $('input[name="location"]').val();

            if (locationFieldValue === 'Online') {
                $('.checka').hide();
            }
        });
    </script>
    <?php
}

add_action('wp_footer', 'hide_checkbox_based_on_location');
//====================================================================================================
//Hide Login/Logout Button
function hide_button_based_on_login_logout() {
    if (is_singular('meetings')) {
        global $post;

        // Retrieve the ACF field value
        $acf_field_value = get_field('location', $post->ID); // Replace 'your_acf_field' with the name of your ACF field

        // Check if the ACF field value meets the condition
        if (is_user_logged_in()) {
            echo '<style>.logout { display: none; }</style>';
        }
    }
}
add_action('wp_footer', 'hide_button_based_on_login_logout');
// Logout Shortcode
function custom_logout_button_shortcode($atts) {
    $atts = shortcode_atts(array(
        'redirect' => home_url(),
    ), $atts);

    if (is_user_logged_in()) {
        $logout_url = wp_logout_url($atts['redirect']);
        $logout_button = '<form action="' . esc_url($logout_url) . '" method="post">
        <input type="submit" value="Logout" class="custom-logout-button" />
        </form>';

        return $logout_button;
    }

    return '';
}
add_shortcode('logout_button', 'custom_logout_button_shortcode');
//====================================================================================================
//Change Date on home Page
function custom_acf_date_format() {
    if (is_front_page()) {
        add_filter('acf/format_value/name=meeting_date', 'format_homepage_date', 10, 3);
    }
}
function format_homepage_date($value, $post_id, $field) {
	$value = strtoupper(date('M', strtotime($value)) . '<br><span class="date-size">' . date('j', strtotime($value)).'</span>');
	$value = nl2br($value);
    return $value;
}
add_action('template_redirect', 'custom_acf_date_format');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Total Number of days Between 1st meeting date and 2nd Meeting date.
function calculate_date_difference() {
	$start_date = get_field('meeting_date',false, false);
	$end_date = get_field('2nd_meeting_date');
  if (!empty($end_date)) 	{
   $start_timestamp = strtotime($start_date);
   $end_timestamp = strtotime($end_date);

   $start_human_date = date('Y-m-d', $start_timestamp);
   $end_human_date = date('Y-m-d', $end_timestamp);

   $difference = $end_timestamp - $start_timestamp;

   $days = floor($difference / (24 * 60 * 60)+1);
   $result = "{$days} DAYS";
} 
else {
        // If 2nd_meeting_date is missing, set an empty result
    $result = '';
}

echo $result;
}
add_shortcode('date_difference', 'calculate_date_difference');
//======================================================================

// =======================================================================================
// Redirect users to home page after login
function redirect_members_login($user_login, $user) {
    wp_redirect(home_url());
    exit;
}
add_action('wp_login', 'redirect_members_login', 10, 2);
// Disable admin bar on subscriber when login
function disable_admin_bar() {
    if (current_user_can('subscriber')) {
        return false;
    }
    return true;
}
add_filter('show_admin_bar', 'disable_admin_bar');
// =========================================================================================
add_action('wp_ajax_downloadMeetingFiles', 'downloadMeetingFiles');
add_action('wp_ajax_nopriv_downloadMeetingFiles', 'downloadMeetingFiles'); // For non-logged in users

function downloadMeetingFiles(){

    global $wpdb;
    //global $post;

    $postID = $_POST['id']; // Array of file IDs to download

    $downloadURL = "#";

    $fileList = array();

    $meetingIDs = array();

    //$results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'presentation_title'");
    $results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key IN ('document_meetings_title')");

    if(sizeof($results) > 0){
        foreach($results as $r){
            if(isset($r) && strlen($r->meta_value) > 0){
                $ids  = unserialize($r->meta_value);
                if(is_array($ids) && sizeof($ids) > 0 && in_array($postID, $ids)){
                    $meetingIDs[] = $r->post_id;
                }
            }
        }
    }

    $output = '';

    if(sizeof($meetingIDs) > 0){
        foreach($meetingIDs as $meetingID){
            $nested_post_id = $meetingID;
            $nested_post_file = get_field('file', $nested_post_id); // Replace 'file' with the name of your custom field for the file
            $filePath = get_attached_file($nested_post_file);
            if(isset($filePath)){
                $fileList[] = $filePath;
            }
        }
    }

    // Summary Reprot
    
    $table_name = $wpdb->prefix . 'postmeta'; // Assuming the ACF field is stored in the postmeta table

    $file_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT meta_value FROM $table_name WHERE meta_key = %s AND post_id = %d",
            "summery_report",
            $postID
        )
    );

    if ($file_id) {
        $filePath = get_attached_file($file_id);
        if(isset($filePath)){
            $fileList[] = $filePath;
        }
    }

    if (sizeof($fileList) > 0) {
    $zip = new ZipArchive;
    $uniqName = get_post_field('post_name', get_post($postID));
    $uploadPath = wp_upload_dir()['basedir'] . '/uploader/zips/';
    $tmp_file = $uploadPath . $uniqName . '.zip';

    // Create the directory if it doesn't exist
    wp_mkdir_p($uploadPath);

    if ($zip->open($tmp_file, ZipArchive::CREATE)) {
        foreach ($fileList as $file) {
            // Get the file path based on the file ID (Modify this part according to your file storage logic)
            if ($file) {
                $fileName = basename($file);
                $zip->addFile($file, $fileName);
            }
        }

        $zip->close();

        $downloadURL = site_url() . '/wp-content/uploads/uploader/zips/' . $uniqName . '.zip';
    }
}

if ($downloadURL != "#") {
    echo json_encode(array("status" => "Success", "message" => "Downloaded Successfully!", "file" => $downloadURL));
} else {
    echo json_encode(array("status" => "Failed", "message" => "Failed to download because it's empty!"));
}

wp_die();

}


function meetingDownloadJquery() {
    global $post;
    ?>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <script type="text/javascript">        
        jQuery(function($){
            $("#download-meeting-zip").on("click", function(e){
                e.preventDefault();
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    data: {action: 'downloadMeetingFiles', id: '<?php echo $post->ID; ?>'},
                    success: function( response ) {
                        resp = JSON.parse(response);
                        if(resp.status == 'Success'){
                            window.location.href = resp.file;
                        }else{
                            console.log("its failure");
                            alert("Failed to download. Please try again!");
                        }
                        console.log("files downloaded");
                        console.log(response);
                    },
                    error: function() {                             
                        console.log("some wrong");
                    }

                });
                return false;

            });
        });
   
    </script>
    <?php
}
add_action( 'wp_footer', 'meetingDownloadJquery' );
// =================================================================================
// To hide those Accordion items who have empty rows
function add_custom_inline_script() {
	?>
    <script type="text/javascript">        
        jQuery(document).ready(function($) {
		  $('.theplus-accordion-item').each(function() {
			var $this = $(this);
			var $postTitle = $this.find('.inner-class');

			if ($postTitle.text().trim() === '') {
			  $this.hide();
			} else {
			  $this.show();
			}
		  });
		});
    </script>
<?php
}
add_action( 'wp_footer', 'add_custom_inline_script' );
// =============================================================================
// // Generate pdf size using shortcode
function pdf_download_button_shortcode($atts) {
    $atts = shortcode_atts(array(
        'acf_field' => 'file', // Replace 'pdf_link' with the name or key of your ACF field
    ), $atts);

    $pdf_link = get_field($atts['acf_field'], get_the_ID());
    
    if (!empty($pdf_link)) {
        $file_url = wp_get_attachment_url($pdf_link);
        $file_path = get_attached_file($pdf_link);
        $file_size = size_format(filesize($file_path));

        return '<a href="' . esc_url($file_url) . '"download>Download PDF (' . $file_size . ')</a>';
    }

    return ''; // Return empty string if the PDF link is empty
}
add_shortcode('download_pdf', 'pdf_download_button_shortcode');

/*// Generate pdf size using shortcode
function pdf_download__summery_report_button_shortcode($atts) {
    $atts = shortcode_atts(array(
        'acf_field' => 'summery_report', // Replace 'pdf_link' with the name or key of your ACF field
    ), $atts);

    $pdf_link = get_field($atts['acf_field'], get_the_ID());
    
    if (!empty($pdf_link)) {
        $file_url = wp_get_attachment_url($pdf_link);
        $file_path = get_attached_file($pdf_link);
        
        // Check if the file exists
        if (file_exists($file_path)) {
            $file_size = size_format(filesize($file_path));
            return '<i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i><a class="pdf-download" style="text-decoration:none;" href="' . esc_url($file_url) . '" download>(' .$file_size . ')</a>';
        }
    }

    return ''; // Return empty string if the PDF link is empty or file doesn't exist
}
add_shortcode('download_pdf_summery_report', 'pdf_download__summery_report_button_shortcode');*/
// =========================================================================================
// =========================================================================================
// Custom Accordion for post page
add_shortcode('custom_posts_accordion', 'custom_posts_accordion_shortcode');
// Shortcode callback function
function custom_posts_accordion_shortcode($atts) {
    ob_start();

    // Set default attributes
    $atts = shortcode_atts(array(
        'post_type' => 'custom_documents',
        'number' => -1, // -1 to display all posts
    ), $atts);

    // Query the custom posts
    $args = array(
        'post_type' => $atts['post_type'],
        'posts_per_page' => $atts['number'],
        'meta_query' => array(
            array(
                'key' => 'document_meetings_title', // Replace with the actual ACF Relationship field key
                'value' => get_the_ID(),
                'compare' => 'LIKE',
            ),
        ),
    );
    $query = new WP_Query($args);

    // Check if there are posts
    if ($query->have_posts()) {
        $posts_by_term = array(); // Array to store posts grouped by term ID

        // Loop through the posts
        while ($query->have_posts()) {
            $query->the_post();

            // Get the document type
            $document_type = get_field('document_type');
            // Check if the term ID has already been encountered
            if (!isset($posts_by_term[$document_type])) {
                $posts_by_term[$document_type] = array();
            }		
				$posts_by_term[$document_type][] = get_post(); // Store the entire post object

        }

        // Output the accordion
        ?>
        <div class="theplus-accordion">
             <?php
            foreach ($posts_by_term as $term_id => $posts) {
                // Get the term name
                $term_name = get_term($term_id)->name;

                // Output the accordion item for the term
                ?>  
                <div class="theplus-accordion-item">
                    <div class="elementor-tab-title plus-accordion-header text-left" tabindex="<?php echo $term_id; ?>" data-tab="<?php echo $term_id; ?>" role="tab" aria-controls="elementor-tab-content-<?php echo $term_id; ?>">
                        <div class="elementor-accordion-icon elementor-accordion-icon-left" aria-hidden="true">
                            <i class="elementor-accordion-icon-closed fa fa-chevron-right"></i>
                        
                        <span class="accordion-button"><?php echo $term_name; ?></span></div>
                    </div>
                
					<div id="elementor-tab-content-<?php echo $post_id; ?>" class="elementor-tab-content elementor-clearfix plus-accordion-content" data-tab="<?php echo $post_id; ?>" role="tabpanel" aria-labelledby="elementor-tab-title-<?php echo $post_id; ?>" style="box-sizing: border-box; display: none;">
						<!-- Custom fields and content for the accordion item -->
						<!-- Replace the placeholders with your custom field values -->
						<table>
							<thead class="change_header">
                    			<tr>
                        			<th class="change_table">Symbol</th>
                        			<th class="change_table">Title</th>
                        			<th class="change_table">File</th>
                        			<th class="change_table">Date of Issues</th>
                    			</tr>
                			</thead>
							<tbody>
					<?php
                    // Loop through the posts for the current term
                   foreach ($posts as $post) {
					// Get the post ID
					$post_id = $post->ID;
					?>					
										<!-- Custom field values -->
										<tr>
									<td><span class="elementor-heading-title elementor-size-default"><?php echo get_field('symbol', $post_id); ?></span></td>
									<td id="wide-column"><a class="title-acc" style="color:#2997AB" href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a></td>
											
 								<?php $file = get_field('file', $post_id); ?>
									<td class="table-width">
										<?php if ($file) : ?>
											<?php
											$file_url = wp_get_attachment_url($file);
											$file_path = get_attached_file($file);
											$file_size = filesize($file_path);
											$file_size_formatted = size_format($file_size, 2);
											?>
											<a class="link-underline" href="<?php echo esc_url($file_url); ?>" download>
												<i class="fa fa-file" style="margin-right:10px; color:#55ADBD;"></i><?php echo $file_size_formatted; ?>
											</a>
										<?php endif; ?>
									</td>
		
								<td><span class="elementor-heading-title elementor-size-default"><?php echo get_field('document_date_of_issues', $post_id); ?></span></td>
							</tr>
								<?php
				   }
					   ?>
						</tbody>
					  </table>
					</div>
                </div>
                <?php
            }
            ?>
</div>
        <script>
        jQuery(function($) {
            // Accordion functionality
            $('.theplus-accordion-item').each(function() {
                var $accordion = $(this);
                var $header = $accordion.find('.plus-accordion-header');
                var $content = $accordion.find('.plus-accordion-content');

                $header.on('click', function() {
                    $accordion.toggleClass('active');
                    $content.slideToggle();
					
					 // Change background color of accordion title in active state
      if ($accordion.hasClass('active')) {
        $header.css('background-color', '#2997AB');
		$header.css('color', '#DFFAFF');
      } else {
        $header.css('background-color', '#F7F9FB');
		$header.css('color', '#2997AB');
      }
                });
            });
			// Add expand all and collapse all buttons	
// 			$('.theplus-accordion').append('<button class="expand-all">Expand All</button>');
// 			$('.theplus-accordion').append('<button class="collapse-all">Collapse All</button>');
			
			// Expand all accordions when the "Expand All" button is clicked
			
			$('.expand-all').on('click', function() {
				$('.theplus-accordion').addClass('active');
				$('.plus-accordion-content').slideDown();
			});
			
			// Collapse all accordions when the "Collapse All" button is clicked
			
			$('.collapse-all').on('click', function() {
				$('.theplus-accordion').removeClass('active');
				$('.plus-accordion-content').slideUp();
			});
        });
        </script>
        <?php

        wp_reset_postdata();
    } else {
        echo '* Secure documents are not included in the zip file.';
    }

    return ob_get_clean();
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function summary_report_button_shortcode($atts) {
    $a = shortcode_atts(array(
        'post_type' => 'custom_documents',
        'number' => -1, // -1 to display all posts
    ), $atts);

    // Query the custom posts
    $args = array(
        'post_type' => $a['post_type'],
        'posts_per_page' => $a['number'],
        'meta_query' => array(
            array(
                'key' => 'document_type', // Replace with the actual ACF field key for document type
                'value' => '42', // Replace with the desired meta value
                'compare' => '=',
            ),
			array(
            'key' => 'document_meetings_title', // Replace with the actual ACF Relationship field key
            'value' => get_the_ID(),
            'compare' => 'LIKE',
        ),
        ),
    );
    $query = new WP_Query($args);

    // Check if there are posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the ACF file field value
            $file = get_field('file'); // Replace with the actual ACF field key for file

            // Proceed if the file exists
            if ($file) {
                // Get the file details
                $file_url = wp_get_attachment_url($file);
                $file_path = get_attached_file($file);
                $file_size = filesize($file_path);
                $file_size_formatted = size_format($file_size, 2);

                // Output the file button
                ?>
                <a class="file-button" href="<?php echo esc_url($file_url); ?>" download>
					 PDF - <?php echo $file_size_formatted; ?> <i class="fa fa-download" style="margin-right:10px; color:#fff;"></i>
                </a>
                <?php
            }
        }

        wp_reset_postdata();
    } else{
		?>
        <style>
            .hide-column{
                display: none;
            }
        </style>
        <?php
	}
}

add_shortcode('summary_report_button', 'summary_report_button_shortcode');
//+++++++++++++++++++++++++++
//For All Meetings SHow Tables
function summary_report_meeting_page_shortcode($atts) {
    $a = shortcode_atts(array(
        'post_type' => 'custom_documents',
        'number' => -1, // -1 to display all posts
    ), $atts);

    // Query the custom posts
    $args = array(
        'post_type' => $a['post_type'],
        'posts_per_page' => $a['number'],
        'meta_query' => array(
            array(
                'key' => 'document_type', // Replace with the actual ACF field key for document type
                'value' => '42', // Replace with the desired meta value
                'compare' => '=',
            ),
			array(
            'key' => 'document_meetings_title', // Replace with the actual ACF Relationship field key
            'value' => get_the_ID(),
            'compare' => 'LIKE',
        ),
        ),
    );
    $query = new WP_Query($args);

    // Check if there are posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the ACF file field value
            $file = get_field('file'); // Replace with the actual ACF field key for file

            // Proceed if the file exists
            if ($file) {
                // Get the file details
                $file_url = wp_get_attachment_url($file);
                $file_path = get_attached_file($file);
                $file_size = filesize($file_path);
                $file_size_formatted = size_format($file_size, 2);

                // Output the file button
                ?>
                <a class="file-button_list" href="<?php echo esc_url($file_url); ?>" download>
					<i class="fa fa-download" style="margin-right:10px; color:#2997AB;"></i><?php echo $file_size_formatted; ?> 
                </a>
                <?php
            }
        }

        wp_reset_postdata();
    } 
	
}

add_shortcode('summary_report_list_view', 'summary_report_meeting_page_shortcode');

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//This is the Testing Github