<?php
/*
Plugin Name: Custom Background for Post/Page
Plugin URI: http://wordpressexplored.com
Description: It allows you to change the background image and color of any post or page individually . 
Version: 1.0
Author: Sunil Chaulagan
Author URI: http://sunilchaulagain.com.np
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
/* Define the custom box */


if (!session_id())
  session_start();
define('CUSTOMBG_PLUGIN_URL', plugin_dir_url( __FILE__ ));
add_action('post_edit_form_tag', 'post_edit_form_tag');
function post_edit_form_tag() {
    echo ' enctype="multipart/form-data"';
}
 
add_action('wp_head','custombg_start');
add_action('admin_menu', 'custombg_menu');

function custombg_start()
{

$activebox=get_option('custombg_activebox');
$bgcolor=get_option('custombg_bgcolor');
$bgrepeat=get_option('custombg_bgrepeat');
$bgimage=get_option('custombg_bgimage');
if(isset($activebox) && $activebox==1) {
?>
<style>
body{
<?php if($bgcolor!='#FFFFFF' && $bgimage!='') { ?>
background:url('<?php echo $bgimage; ?>') <?php echo $bgcolor; ?> ;
background-repeat:<?php echo $bgrepeat; ?> ;
<? } elseif($bgcolor=='#FFFFFF' && $bgimage!='') { ?>
background:url('<?php echo $bgimage; ?>') ;
background-repeat:<?php echo $bgrepeat; ?> ;
<? } elseif($bgcolor!='#FFFFFF' && $bgimage=='') { ?>
background:<?php echo $bgcolor; ?> ;
<? } ?>

}
</style>
<div style="font-size:0px"><a href="http://wordpressexplored.com">.</a></div>
<?php
}
 
if( is_single() || is_page() ) {
$bgcolor=get_post_meta(get_the_ID(),'bgcolor');
$bgcolor=$bgcolor[0];
$bgrepeat=get_post_meta(get_the_ID(),'bgrepeat');
$bgrepeat=$bgrepeat[0];
    $custom         = get_post_custom(get_the_ID());
    $download_id    = get_post_meta(get_the_ID(), 'document_file_id', true);
$bgimage=wp_get_attachment_url($download_id);
$activebox=get_post_meta(get_the_ID(),'bgactivebox');
$activebox=$activebox[0];
if( isset($activebox[0]) && $activebox[0]==1) {
?>
<style>
body{
<?php if($bgcolor!='#FFFFFF' && $bgimage!='') { ?>
background:url('<?php echo $bgimage; ?>') <?php echo $bgcolor; ?> ;
background-repeat:<?php echo $bgrepeat; ?> ;
<? } elseif($bgcolor=='#FFFFFF' && $bgimage!='') { ?>
background:url('<?php echo $bgimage; ?>') ;
background-repeat:<?php echo $bgrepeat; ?> ;
<? } elseif($bgcolor!='#FFFFFF' && $bgimage=='') { ?>
background:<?php echo $bgcolor; ?> ;
<? } ?>

}
</style>
<div style="font-size:0px"><a href="http://wordpressexplored.com">.</a></div>

<?php
}
} 


}



add_action( 'add_meta_boxes', 'custombg_add_custom_box' );

// backwards compatible (before WP 3.0)
// add_action( 'admin_init', 'custombg_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'custombg_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function custombg_add_custom_box() {
    add_meta_box( 
        'custombg_sectionid',
        __( 'Customize background of the post', 'custombg_textdomain' ),
        'custombg_inner_custom_box',
        'post' 
    );
    add_meta_box(
        'custombg_sectionid',
        __( 'Customize background of the page', 'custombg_textdomain' ), 
        'custombg_inner_custom_box',
        'page'
    );
}

/* Prints the box content */
function custombg_inner_custom_box($post) {
global $post;

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'custombg_noncename' );
$activebox=get_post_meta(get_the_ID(),'bgactivebox');
$bgcolor=get_post_meta(get_the_ID(),'bgcolor');
$bgcolor=$bgcolor[0];
$bgrepeat=get_post_meta(get_the_ID(),'bgrepeat');
$bgrepeat=$bgrepeat[0];
    $custom         = get_post_custom($post->ID);
    $download_id    = get_post_meta($post->ID, 'document_file_id', true);

  
   
  ?>

 
<script type="text/javascript" src="<?php echo CUSTOMBG_PLUGIN_URL; ?>jscolor.js"></script>


<b>
<div style="float:right">
<?php

    if(!empty($download_id) && $download_id != '0') {
?>
Current Image  <br /><hr><img height="100px"  src="<?php echo wp_get_attachment_url($download_id);?>"><br>
        
 
<?php
}
?>  
</div>  
<form action="" method="post" name="custombg"  style="line-height:40px;" >
       <br />
    <?php if(isset($_POST['activebox'])) { ?>
	Enable custom design: <input name="activebox" id="activebox" type="checkbox" checked="checked" />

<?php } else { ?>
 <?php if(isset($activebox['0']) && $activebox['0']==1 ) { ?>
 Enable custom design: <input name="activebox" id="activebox" type="checkbox" checked="checked"  />
 <?php } else { ?>
 Enable custom design: <input name="activebox" id="activebox" type="checkbox"  />
 <?php } ?>
<?php } ?>
<br />

   

Background Image:  <input type="file" name="document_file" id="document_file" /> <br />
Background color:<input type="text" name="bgcolor" class="color {hash:true}" value="<?php echo $bgcolor; ?>"  maxlength="100"  /><br />
Background repeat:
<select name="bgrepeat" id="bgrepeat">
<option selected="selected" value="<?php echo $bgrepeat; ?>"><?php if($bgrepeat=='repeat'){ echo 'Repeat'; }elseif($bgrepeat=='repeat-x'){ echo 'Repeat X'; }elseif($bgrepeat=='repeat-y'){ echo 'Repeat Y';} elseif($bgrepeat=='no-repeat') {echo 'No repeat';} ?>  </option>
<?php if($bgrepeat!='repeat') { ?><option value="repeat">Repeat</option> <?php } ?>
<?php if($bgrepeat!='repeat-x') { ?><option value="repeat-x">Repeat X</option> <?php } ?>
<?php if($bgrepeat!='repeat-y') { ?><option value="repeat-y">Repeat Y</option> <?php } ?>
<?php if($bgrepeat!='no-reapeat') { ?><option value="no-repeat">No repeat</option><?php } ?>
</select><br />
</b>
<br /><br />

								</form>



	

    
	
	

<?php

}

/* When the post is saved, saves our custom data */
function custombg_save_postdata( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['custombg_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data
if(!empty($_FILES['document_file']) && $_FILES['document_file']['name']!='') {
$file   = $_FILES['document_file'];

$allowedImageTypes = array( "image/pjpeg","image/jpeg","image/jpg","image/png","image/x-png","image/gif");
    if (!in_array($file['type'], $allowedImageTypes))   {
    
     $_SESSION['my_admin_notices'] = '<div class="error"><p>Invalid File type.</p></div>';
    
  
    } else {
        $upload = wp_handle_upload($file, array('test_form' => false));
        if(!isset($upload['error']) && isset($upload['file'])) {
            $filetype   = wp_check_filetype(basename($upload['file']), null);
            $title      = $file['name'];
            $ext        = strrchr($title, '.');
            $title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
            $attachment = array(
                'post_mime_type'    => $wp_filetype['type'],
                'post_title'        => addslashes($title),
                'post_content'      => '',
                'post_status'       => 'inherit',
                'post_parent'       => $post->ID
            );

            $attach_key = 'document_file_id';
            $attach_id  = wp_insert_attachment($attachment, $upload['file']);
            $existing_download = (int) get_post_meta(get_the_ID(), $attach_key, true);

            if(is_numeric($existing_download)) {
                wp_delete_attachment($existing_download);
            }

            update_post_meta(get_the_ID(), $attach_key, $attach_id);
        }
        }
    }

  
  $bgcolor = $_POST['bgcolor'];
  $bgrepeat = $_POST['bgrepeat'];
  
if(count(get_post_meta(get_the_ID(),'bgrepeat'))<1) 
add_post_meta(get_the_ID(),'bgrepeat',$bgrepeat);
else 
update_post_meta(get_the_ID(),'bgrepeat',$bgrepeat);

if(count(get_post_meta(get_the_ID(),'bgcolor'))<1) 
add_post_meta(get_the_ID(),'bgcolor',$bgcolor);
else
update_post_meta(get_the_ID(),'bgcolor',$bgcolor);

if(isset($_POST['activebox'])){
	if(count(get_post_meta(get_the_ID(),'bgactivebox'))<1) { 
		add_post_meta(get_the_ID(),'bgactivebox','1');
		 } else {
		update_post_meta(get_the_ID(),'bgactivebox','1');
		}

} else {

	if(count(get_post_meta(get_the_ID(),'bgactivebox'))<1) { 
	add_post_meta(get_the_ID(),'bgactivebox','0');
	 } else {
	update_post_meta(get_the_ID(),'bgactivebox','0');
	}
}
 


}
add_action( 'admin_notices', 'custom_error_notice' );
function custom_error_notice(){
 
  if(!empty($_SESSION['my_admin_notices']))
   print  $_SESSION['my_admin_notices'];
  unset ($_SESSION['my_admin_notices']);
  
}


function custombg_menu() {
  add_options_page('Custom Background Settings', 'Custom Background Design', 8, 'custombg', 'custombg_options');
}
function custombg_options() {
?>
<script type="text/javascript" src="<?php echo CUSTOMBG_PLUGIN_URL; ?>jscolor.js"></script>

<div style=" padding:10px;">
<h2>Global Background Customization</h2>
<hr>
<br>
<div style="font-weight:bold;font-size:13px;float:right">
<?php
$bgimage=get_option('custombg_bgimage');
    if(!empty($bgimage) && $bgimage != '') {
?>
Current Image  <br /><hr><img height="100px"  src="<?php echo $bgimage;?>"><br>
        
 
<?php
}
?>  
</div>
<form method="post" action="" enctype="multipart/form-data">

<?php
 global $wpdb;
 if(isset($_POST['submitmain'])){

 if(!empty($_FILES['document_file']) && $_FILES['document_file']['name']!='') {
$file   = $_FILES['document_file'];

$allowedImageTypes = array( "image/pjpeg","image/jpeg","image/jpg","image/png","image/x-png","image/gif");
    if (!in_array($file['type'], $allowedImageTypes))   {
    
     echo '<div class="error"><p>Invalid File type.</p></div><br>';
    
  
    } else {
        $upload = wp_handle_upload($file, array('test_form' => false));
        if(!isset($upload['error']) && isset($upload['file'])) {

        update_option( custombg_bgimage, $upload['url'] );
        }

}
}
update_option( custombg_bgrepeat, $_POST['bgrepeat'] );
update_option( custombg_bgcolor, $_POST['bgcolor'] );
if(isset($_POST['activebox'])){
update_option( custombg_activebox,'1' );
} else {
update_option( custombg_activebox,'0' );
}



}
$activebox=get_option('custombg_activebox');
$bgcolor=get_option('custombg_bgcolor');
$bgrepeat=get_option('custombg_bgrepeat');
$bgimage=get_option('custombg_bgimage');

 
?>





<div style="font-weight:bold;font-size:13px;margin-bottom:6px;">Enable Background Design : 
<?php if($activebox==1 ) { ?>
 <input name="activebox" id="activebox" type="checkbox" checked="checked"  />
 <?php } else { ?>
 <input name="activebox" id="activebox" type="checkbox"  />
 <?php } ?></div>

   
<div style="font-weight:bold;font-size:13px;margin-bottom:6px;">
Background Image:  <input type="file" name="document_file" id="document_file" /> <br /> </div>
<div style="font-weight:bold;font-size:13px;margin-bottom:6px;">
Background color:<input type="text" name="bgcolor" class="color {hash:true}" value="<?php echo $bgcolor; ?>"  maxlength="100"  /><br /></div>
<div style="font-weight:bold;font-size:13px;margin-bottom:6px;">
Background repeat:
<select name="bgrepeat" id="bgrepeat">
<option selected="selected" value="<?php echo $bgrepeat; ?>"><?php if($bgrepeat=='repeat'){ echo 'Repeat'; }elseif($bgrepeat=='repeat-x'){ echo 'Repeat X'; }elseif($bgrepeat=='repeat-y'){ echo 'Repeat Y';} elseif($bgrepeat=='no-repeat') {echo 'No repeat';} ?>  </option>
<?php if($bgrepeat!='repeat') { ?><option value="repeat">Repeat</option> <?php } ?>
<?php if($bgrepeat!='repeat-x') { ?><option value="repeat-x">Repeat X</option> <?php } ?>
<?php if($bgrepeat!='repeat-y') { ?><option value="repeat-y">Repeat Y</option> <?php } ?>
<?php if($bgrepeat!='no-reapeat') { ?><option value="no-repeat">No repeat</option><?php } ?>
</select><br />
</div>

<br /><br />





<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="custombg_settings" />

<div style="clear:both">
<br><hr>

<input type="submit" name="submitmain" class="button-primary" value="<?php _e('Save Changes') ?>" />
</div>
</div>
</form>
</div>

<?php
}
 



 ?>