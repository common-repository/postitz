<?php
/* shortcode generated and output defined
 *
 */
// Add a shortcode called 'postitz' that runs the 'shortcode_routine' function
 
add_shortcode('postitz', 'postitz_add_shortcode');
 
function postitz_add_shortcode($atts, $output) {

$value1 = get_post_meta( get_the_ID(),'postitz_textarea', true );
$value2 = get_post_meta( get_the_ID(),'postitz_colorfield', true ); 
$value3 = get_post_meta( get_the_ID(),'postitz_fontfield', true );
$value4 = get_post_meta( get_the_ID(),'postitz_sizefield', true );
$value5 = get_post_meta( get_the_ID(),'postitz_ocdfield', true );

extract( shortcode_atts( 
array(
'postitz_text'  => $value1,
'postitz_color' => $value2,
'postitz_font'  => $value3,
'postitz_size'  => $value4,
'postitz_ocd'   => $value5,
), $atts ));
 

// sanitize values
$value5 = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $value5);
$value4 = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $value4);
$value3 = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $value3);
$value2 = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $value2); // sanitize color or name
$value1 = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $value1);
 
$output = '<div id="postitz-container" class="' . $postitz_ocd . '">';
$output .= '<div id="' . $postitz_size . '" class="' . $postitz_color . '">';
$output .= '<div class="postitz-text"><p class="'. $postitz_font .'">'. $postitz_text .'</p></div>';
$output .= '</div></div>';
   
 
return do_shortcode($output);
}



?>