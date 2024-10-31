<?php
/*
Plugin Name: TSW PostItz
Plugin URI: http://buffercode.com/article-difficulty-level/
Description: User meta-box to add shortcode into post which outputs fancy post it note.
Version: 0.1
Author: tradesouthwest
Author URI: http://tradesouthwest.com/
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
    // Register style sheet
function postitz_plugin_scripts() 
{
    wp_enqueue_style( 'postitz-plugin', plugins_url( '/postitz/style-postitz.css' ) );
}
add_action( 'wp_enqueue_scripts', 'postitz_plugin_scripts' );

    // Function to return a custom field value
function postitz_get_custom_field( $value ) 
{
    global $post;
        $custom_field = get_post_meta( $post->ID, $value, true );
        if ( !empty( $custom_field ) )
        return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );
    return false;
}
// Register the Metabox
function postitz_add_custom_meta_box() 
{
    add_meta_box( 'postitz-meta-box', __( 'Post Note - PostItz by TSW', 'postitz' ), 'postitz_meta_box_output', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'postitz_add_custom_meta_box' );
 
    // Output the Metabox
function postitz_meta_box_output( $post ) 
{
    // create a nonce field
    wp_nonce_field( 'new_postitz_meta_box_nonce', 'postitz_meta_box_nonce' ); 

    //Get the value previous value from the database to display in the admin dashboard
    $value = get_post_meta( $post->ID, 'postitz_meta_value_key', true );
?>
<table padding="30" cell-spacing="10"><tbody>
<tr>
<td width="360"> 
    <label for="postitz_textarea"><b><?php _e( 'Note', 'postitz' ); ?></b></label><br />
    <textarea name="postitz_textarea" id="postitz_textarea" cols="40" rows="4"><?php echo postitz_get_custom_field( 'postitz_textarea' ); ?></textarea>
</td><td>
    <label for="postitz_colorfield"><b><?php _e( 'Color of Post-Itz', 'postitz' ); ?></b></label><br> 
    <!-- Combo box to select Post Itz Color  -->
    <?php $value2c = get_post_meta( get_the_ID(),'postitz_colorfield', true ); 
              if( ! isset( $value2c ) ) { $value2c = 'yellow'; } ?>
    <select name="postitz_colorfield">
        <option value="yellow" <?php if( $value2c == 'yellow' ) { echo 'selected="selected" '; } ?>> yellow - default</option>
        <option value="white"  <?php if( $value2c == 'white' ) { echo 'selected="selected" '; } ?>> white</option>
        <option value="pink"   <?php if( $value2c == 'pink' ) { echo 'selected="selected" '; } ?>> pink</option>
        <option value="blue"   <?php if( $value2c == 'blue' ) { echo 'selected="selected" '; } ?>> blue</option>
        <option value="red"    <?php if( $value2c == 'red' ) { echo 'selected="selected" '; } ?>> red</option>
    </select>

</td><td width="220">
    <label for="postitz_fontfield"><b><?php _e( 'Font for Post-Itz', 'postitz' ); ?></b></label><br> 
    <!-- Combo box to select Font Types  -->
    <?php $value3f = get_post_meta( get_the_ID(),'postitz_fontfield', true );
              if( ! isset( $value3f ) ) { $value3f = 'Honey'; } ?>
    <select name="postitz_fontfield">
        <option value="Honey"     <?php if( $value3f == 'Honey' ) { echo 'selected="selected" '; } ?>>Honey Script Light</option>
        <option value="Sansation" <?php if( $value3f == 'Sansation' ) { echo 'selected="selected" '; } ?>>Sansation Light</option>
        <option value="Curve"     <?php if( $value3f == 'Curve' ) { echo 'selected="selected" '; } ?>>Learning Curve</option>
        <option value="SansSerif" <?php if( $value3f == 'SansSerif' ) { echo 'selected="selected" '; } ?>>Sans Serif</option>
        <option value="Courier"   <?php if( $value3f == 'Courier' ) { echo 'selected="selected" '; } ?>>Courier New</option>
    </select>
</td></tr>
<tr>
<td><p><?php _e( 'The shortcode to add inside of your post is: <code>[postitz]</code>', 'postitz' ); ?></p></td>
<td>
   <label for="postitz_sizefield"><b><?php _e( 'Size of Post-Itz', 'postitz' ); ?></b></label><br> 
    <!-- Combo box to select Size of Post Itz  -->
    <?php $value4s = get_post_meta( get_the_ID(),'postitz_sizefield', true ); 
              if( ! isset( $value4s ) ) { $value4s = 'postitz_lrg'; } ?>
    <select name="postitz_sizefield">
        <option value="postitz_sml" <?php if( $value4s == 'postitz_sml' ) { echo 'selected="selected" '; } ?>>Small</option>
        <option value="postitz_med" <?php if( $value4s == 'postitz_med' ) { echo 'selected="selected" '; } ?>>Medium</option>
        <option value="postitz_lrg" <?php if( $value4s == 'postitz_lrg' ) { echo 'selected="selected" '; } ?>>Large</option>
    </select>
</td>
<td>
   <label for="postitz_ocdfield"><b><?php _e( 'OCD Mode -</b> <small>Change to Remove Tilt</small>', 'postitz' ); ?></label><br> 
    <!-- Combo box to select Alignment  -->
    <?php $value5a = get_post_meta( get_the_ID(),'postitz_ocdfield', true ); 
if( ! isset( $value5a ) ) { $value5a = 'postitz_off'; } ?>
     <select name="postitz_ocdfield">
        <option value="postitz_off" <?php if( $value5a == 'postitz_off' ) { echo 'selected="selected" '; } ?>>Tilt Please </option>
        <option value="postitz_ocd" <?php if( $value5a == 'postitz_ocd' ) { echo 'selected="selected" '; } ?>>No Tilt!</option>
    </select>
</td></tr></tbody></table>
<?php 
}

// Save the Metabox values
function postitz_meta_box_save( $post_id ) 
{
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
 
	// Verify the nonce. If not, stop the script
	if( !isset( $_POST['postitz_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['postitz_meta_box_nonce'], 'new_postitz_meta_box_nonce' ) ) return;
 
	// Stop the script if the user does not have edit permissions
	//if( !current_user_can( 'edit_post' ) ) return;
 
        // Save the textarea
	if( isset( $_POST['postitz_textarea'] ) )
		update_post_meta( $post_id, 'postitz_textarea', esc_attr( $_POST['postitz_textarea'] ) );

        // Save the colorfield
	if( isset( $_POST['postitz_colorfield'] ) )
		update_post_meta( $post_id, 'postitz_colorfield', esc_attr( $_POST['postitz_colorfield'] ) );

       // Save the fontfield
	if( isset( $_POST['postitz_fontfield'] ) )
		update_post_meta( $post_id, 'postitz_fontfield', esc_attr( $_POST['postitz_fontfield'] ) );

       // Save the sizefield
	if( isset( $_POST['postitz_sizefield'] ) )
		update_post_meta( $post_id, 'postitz_sizefield', esc_attr( $_POST['postitz_sizefield'] ) );
        // Save the ocdfield
	if( isset( $_POST['postitz_ocdfield'] ) )
		update_post_meta( $post_id, 'postitz_ocdfield', esc_attr( $_POST['postitz_ocdfield'] ) );



    // Sanitize user input.
     

     // Update the meta field in the database.
     update_post_meta( $post_id, 'postitz_textarea', esc_attr( $_POST['postitz_textarea'] )  );
     // Update the meta field in the database.
     update_post_meta( $post_id, 'postitz_colorfield', esc_attr( $_POST['postitz_colorfield'] )  );
     // Update the meta field in the database.
     update_post_meta( $post_id, 'postitz_fontfield', esc_attr( $_POST['postitz_fontfield'] )  );
     // Update the meta field in the database.
     update_post_meta( $post_id, 'postitz_sizefield', esc_attr( $_POST['postitz_sizefield'] )  );
     // Update the meta field in the database.
     update_post_meta( $post_id, 'postitz_ocdfield', esc_attr( $_POST['postitz_ocdfield'] )  );
}

add_action( 'save_post', 'postitz_meta_box_save' );
include( 'tsw-postitz-note.php' );
?>