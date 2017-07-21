<?php
/*
Plugin Name: MQ ReLinks
Plugin URI: http://www.maiq.info/
Description: Inserts target="_blank" and rewrites all direct links in posts, widgets, comments and author links, to a out.php file.
Version: 1.8
Author: maiq
Author URI: http://www.maiq.info/
	Copyright (c) 2008 - 2017 maiq (http://www.maiq.info)
	MQ ReLinks is released under the GNU General Public
	License (GPLv2) http://www.gnu.org/licenses/gpl-2.0.html
	This is a WordPress plugin (http://wordpress.org).
*/
	// variables for the fields and option names 
	$opt_name =array( 'replace_in_post', 'blank_in_post', 'replace_in_comment', 'blank_in_comment', 'replace_in_author_comment_link', 'blank_in_author_comment_link','replace_in_widget_link','blank_in_widget_link','mq_use_pretty_url','mq_replace_image_links','qr_path');
	global $opt_name;
	$vers ='1.7';
	
function mq_relinks_install(){
	$opt_name =array( 'replace_in_post', 'blank_in_post', 'replace_in_comment', 'blank_in_comment', 'replace_in_author_comment_link', 'blank_in_author_comment_link','replace_in_widget_link','blank_in_widget_link','mq_use_pretty_url','mq_replace_image_links','qr_path');
	//add the options and values
	foreach ($opt_name as $cz){ 
	((get_option($cz) != 'yes') || (get_option($cz) != 'no')) ? update_option($cz, 'no') : add_option($cz, 'no', '', 'no'); 
	}
	$qr_path_value = (get_option('qr_path') != 'wp-content/plugins/mq-relinks/') ? update_option('qr_path', 'wp-content/plugins/mq-relinks/') : add_option('qr_path','wp-content/plugins/mq-relinks/', '', 'no'); 
}

function mq_relinks_uninstall(){
	$opt_name =array( 'replace_in_post', 'blank_in_post', 'replace_in_comment', 'blank_in_comment', 'replace_in_author_comment_link', 'blank_in_author_comment_link','replace_in_widget_link','blank_in_widget_link','mq_use_pretty_url','mq_replace_image_links','qr_path');
	// delete options
	foreach ($opt_name as $cz){ delete_option($cz);	}
	delete_option('qr_path');
}	

	//options page
function mq_relinks_options_page() {
#var_dump($_POST);
	global $opt_name;
	global $vers;
		
	//set hidden field name
    $hidden_field_name = 'mq_relinks_submit_hidden';
	
	//check if options exist
	foreach ($opt_name as $content){ 	if( $mq_relinks_options_value[$content] !='no' || $mq_relinks_options_value[$content] !='yes' ) add_option($content, 'no');	}
	$qr_path_value = (get_option('qr_path') != 'wp-content/plugins/mq-relinks/') ? update_option('qr_path', 'wp-content/plugins/mq-relinks/') : add_option('qr_path','wp-content/plugins/mq-relinks/', '', 'no'); 
	
	//get the option values
	foreach ($opt_name as $mq_relinks_options){ $mq_relinks_options_value[$mq_relinks_options] = get_option( $mq_relinks_options ); }
	$qr_path_value = get_option('qr_path');
	
	//if the form is posted
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
	
	//get the posted values
	foreach ($opt_name as $mq_relinks_options_posted){ $mq_relinks_options_posted_value[$mq_relinks_options_posted] = $_POST[ $mq_relinks_options_posted ];	}
	
	//update the option values
	foreach ($opt_name as $posted_opt_name ){ update_option( $posted_opt_name, $mq_relinks_options_posted_value[$posted_opt_name] );	}
	
	//get the saved option values
	foreach ($opt_name as $mq_relinks_options){ $mq_relinks_options_value[$mq_relinks_options] = get_option( $mq_relinks_options );	}
	$qr_path_value = get_option('qr_path');
	//show a message
?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mq_relinks' ); ?></strong></p></div>
<?php
}
 	echo '<div class="wrap">
			<h2>'.__('MQ ReLinks '.$vers.' Options','mq_relinks').'</h2>
			<div class="postbox-container" style="width:75%">
				<form method="post" action="options-general.php?page=mq_relinks">
				<div class="metabox-holder">
				<div class="meta-box-sortables">
					<div id="edit-pages" class="postbox">
					<div class="handlediv" title="'.__('Click to toggle','mq_relinks').'"></div>
					<h3 class="hndle"><span>'.__('Plugin Settings','mq_relinks').'</span></h3>
					<div class="inside">				
						<table>';
							foreach ($opt_name as $mq_relinks_options){
								if($mq_relinks_options!='qr_path'){
									echo'<tr><td>'.ucwords(str_replace("_"," ", $mq_relinks_options)).': ';
									echo '</td><td><select name="'.$mq_relinks_options.'">
									<option value="yes" ';
									echo ($mq_relinks_options_value[$mq_relinks_options] =='yes')?'selected':'';
									echo'>Yes</option> <option value="no"';
									echo ($mq_relinks_options_value[$mq_relinks_options] =='no')?'selected':'';
									echo'>No</option></select></td></tr>';
								}
							}
						echo'
						</table>
						<input type="hidden" name="'.$hidden_field_name.'" value="Y">
<div>
Add the following line to your .htaccess file if you want the pretty url feature enabled:<br>
<input disabled type="text" style="width:500px;" value="RewriteRule ^out/(.*)$ wp-content/plugins/mq-relinks/out.php?url=$1 [L]">
<br>
So your .htaccess will look like this:<br>
<textarea style="width:520px; height:235px;" disabled>
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteRule ^out/(.*)$ wp-content/plugins/mq-relinks/out.php?url=$1 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
</textarea>
</div>
					</div><!-- .inside -->
					</div><!-- #edit-pages -->
					<input type="submit" name="Submit" class="button-primary button-block" value="'.__('Save Settings','mq_relinks').'" />
					</form>
				</div><!-- .meta-box-sortables -->
				</div><!-- .metabox-holder -->
				
			</div><!-- .postbox-container -->
			
			<div class="postbox-container" style="width:25%">
			
				<div class="metabox-holder">
				<div class="meta-box-sortables">
				
					<div id="edit-pages" class="postbox">
					<div class="handlediv" title="'.__('Click to toggle','mq_relinks').'"></div>
					<h3 class="hndle"><span>'.__('Plugin Information','mq_relinks').'</span></h3>
					<div class="inside">
						<p>'.__(' If you\'ve enjoyed the plugin please give the plugin 5 stars on WordPress.org.','mq_relinks').'</p>
						<p>'.__('Want to help? <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=andreiburca@gmail.com&currency_code=EUR&amount=&return=&item_name=MQ Relinks plugin" target="_new"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPoAAABJCAYAAAAUsjvmAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo1QUNBNTY5RTZGRDExMUUyOTY5MkJFRTJEMTQ1NDcxMyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo1QUNBNTY5RjZGRDExMUUyOTY5MkJFRTJEMTQ1NDcxMyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjVBQ0E1NjlDNkZEMTExRTI5NjkyQkVFMkQxNDU0NzEzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjVBQ0E1NjlENkZEMTExRTI5NjkyQkVFMkQxNDU0NzEzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+0A7sXAAAFitJREFUeNrsXQd0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"></a>','mq_relinks').'</p>
						<p><a href="http://wordpress.org/plugins/mq-relinks/">WordPress.org</a> | <a href="http://www.maiq.info/work/wordpress/mq-relinks/">'.__('Plugin Author','mq_relinks').'</a></p>
					</div><!-- .inside -->
					</div><!-- #edit-pages -->
				
				</div><!-- .meta-box-sortables -->
				</div><!-- .metabox-holder -->
				
			</div><!-- .postbox-container -->	
	</div><!-- .wrap -->';
}
	//function to add the page to the admin settings menu
function mq_ReLinks_add_pages() { 
	$mq_relinks_settings=add_options_page('MQ ReLinks', 'MQ ReLinks', 8, 'mq_relinks', 'mq_relinks_options_page');  
	add_action('load-'.$mq_relinks_settings, 'mq_relinks_help_page_scripts');
	}
 
function mq_relinks_help_page_scripts() {
	wp_enqueue_style('dashboard');
	wp_enqueue_script('postbox');
	wp_enqueue_script('dashboard');
}
 function mq_ReLinks_content($str, $arg = 1) {
    // manipulate hyperlinks in $str...
    if (!isset($str)) return $str;
    if (!$arg) return $str;
    return preg_replace_callback('#<a\s([^>]*\s*href\s*=[^>]*)>#i', 'mq_ReLinks_replaceit_post', $str);
}

 function mq_ReLinks_comment($str, $arg = 1) {
    // manipulate hyperlinks in $str...
    if (!isset($str)) return $str;
    if (!$arg) return $str;
    return preg_replace_callback('#<a\s([^>]*\s*href\s*=[^>]*)>#i', 'mq_ReLinks_replaceit_comment', $str);
}
 function mq_ReLinks_widget($str, $arg = 1) {
    // manipulate hyperlinks in $str...
    if (!isset($str)) return $str;
    if (!$arg) return $str;
    return preg_replace_callback('#<a\s([^>]*\s*href\s*=[^>]*)>#i', 'mq_ReLinks_replaceit_widget', $str);
}
function mq_ReLinks_replaceit_post($matches) {
	global $opt_name;
    $str = $matches[1];
    preg_match_all('/[^=[:space:]]*\s*=\s*"[^"]*"|[^=[:space:]]*\s*=\s*\'[^\']*\'|[^=[:space:]]*\s*=[^[:space:]]*/', $str, $attr);
    $href_arr = preg_grep('/^href\s*=/i', $attr[0]);
    if (count($href_arr) > 0) $href = array_pop($href_arr);
	if ($href) {
	foreach ($opt_name as $mq_relinks_options){ $mq_relinks_options_value[$mq_relinks_options] = get_option( $mq_relinks_options );	}
	$extensions=array("jpg","gif","png");
	$relink_path_value = get_option('qr_path');
	$blogurl = get_bloginfo('url');
	$blog_url = parse_url(get_bloginfo('url'));
	$blog_host = $blog_url['host'];
	$local = strpos($href, $blog_host);
	$javalink = strpos($href, "javascript:");
	$y = preg_replace("#\"#","",$href);
	$ext = substr(strrchr(strtolower($y),'.'),1);
	$isimage = (in_array($ext, $extensions)===true) ? "yes" : "no";
	if ($mq_relinks_options_value['blank_in_post'] =='yes'){ if (($local === false) && ($javalink === false) &&  ($href{6}!="#") && ($href{7}!="#") ) $blank = 'target="_blank"';}
	if ($mq_relinks_options_value['replace_in_post'] =='yes'){
		if( ($isimage == 'yes' ) && ($mq_relinks_options_value['mq_replace_image_links'] == 'no')){
		return;
		}else{
			if (($local === false) && ($javalink === false) && ($href{6}!="#") && ($href{7}!="#") ) {
				if ($mq_relinks_options_value['mq_use_pretty_url'] =='no'){
					$href = preg_replace('/^(href\s*=\s*[\'"]?)/i', '\1'.$blogurl.'/'.$relink_path_value.'out.php?url=', $href);
				}else{
					$href = preg_replace('/^(href\s*=\s*[\'"]?)/i', '\1'.$blogurl.'/out/', $href);
				}
			}
		}
	}
	$attr = preg_grep('/^href\s*=/i', $attr[0], PREG_GREP_INVERT);
    return '<a '.$blank .' '. join(' ', $attr) . ' ' . $href . '>';
	// return $href;
	}
}

function mq_ReLinks_replaceit_comment($matches) {
	global $opt_name;
    $str = $matches[1];
    preg_match_all('/[^=[:space:]]*\s*=\s*"[^"]*"|[^=[:space:]]*\s*=\s*\'[^\']*\'|[^=[:space:]]*\s*=[^[:space:]]*/', $str, $attr);
    $href_arr = preg_grep('/^href\s*=/i', $attr[0]);
    if (count($href_arr) > 0) $href = array_pop($href_arr);
	if ($href) {
	foreach ($opt_name as $mq_relinks_options){	$mq_relinks_options_value[$mq_relinks_options] = get_option( $mq_relinks_options );	}
	$relink_path_value = get_option('qr_path');
	$burl = get_bloginfo('url');
	$local = strpos($href, $burl);
	if ($mq_relinks_options_value['blank_in_comment'] =='yes'){if (($local === false) && ($href{6}!="#") && ($href{7}!="#") ) $blank = 'target="_blank"';} 
	if ($mq_relinks_options_value['replace_in_comment'] =='yes'){
	if (($local === false) &&  ($href{6}!="#") && ($href{7}!="#") ) {
			if ($mq_relinks_options_value['mq_use_pretty_url'] =='no'){
	        $href = preg_replace('/^(href\s*=\s*[\'"]?)/i', '\1'.$burl.$relink_path_value.'out.php?url=', $href);
			}else{
			$href = preg_replace('/^(href\s*=\s*[\'"]?)/i', '\1'.$burl.'/out/', $href);
			}
		}
    $attr = preg_grep('/^href\s*=/i', $attr[0], PREG_GREP_INVERT);
	}
    return '<a '.$blank . join(' ', $attr) . ' ' . $href . '>';
	}
}
function mq_ReLinks_replaceit_widget($matches) {
	global $opt_name;
    $str = $matches[1];
    preg_match_all('/[^=[:space:]]*\s*=\s*"[^"]*"|[^=[:space:]]*\s*=\s*\'[^\']*\'|[^=[:space:]]*\s*=[^[:space:]]*/', $str, $attr);
    $href_arr = preg_grep('/^href\s*=/i', $attr[0]);
    if (count($href_arr) > 0) $href = array_pop($href_arr);
	if ($href) {
	foreach ($opt_name as $mq_relinks_options){ $mq_relinks_options_value[$mq_relinks_options] = get_option( $mq_relinks_options );	}
	$extensions=array("jpg","gif","png");
	$relink_path_value = get_option('qr_path');
	$blogurl = get_bloginfo('url');
	$blog_url = parse_url(get_bloginfo('url'));
	$blog_host = $blog_url['host'];
	$local = strpos($href, $blog_host);
	$javalink = strpos($href, "javascript:");
	$y = preg_replace("#\"#","",$href);
	$ext = substr(strrchr(strtolower($y),'.'),1);
	$isimage = (in_array($ext, $extensions)===true) ? "yes" : "no";
	if ($mq_relinks_options_value['blank_in_widget_link'] =='yes'){ if (($local === false) && ($javalink === false) &&  ($href{6}!="#") && ($href{7}!="#") ) $blank = 'target="_blank"';}
	if ($mq_relinks_options_value['replace_in_widget_link'] =='yes'){
		if( ($isimage == 'yes' ) && ($mq_relinks_options_value['mq_replace_image_links'] == 'no')){
		return;
		}else{
			if (($local === false) && ($javalink === false) && ($href{6}!="#") && ($href{7}!="#") ) {
				if ($mq_relinks_options_value['mq_use_pretty_url'] =='no'){
					$href = preg_replace('/^(href\s*=\s*[\'"]?)/i', '\1'.$blogurl.'/'.$relink_path_value.'out.php?url=', $href);
				}else{
					$href = preg_replace('/^(href\s*=\s*[\'"]?)/i', '\1'.$blogurl.'/out/', $href);
				}
			}
		}
	}
	$attr = preg_grep('/^href\s*=/i', $attr[0], PREG_GREP_INVERT);
    return '<a '.$blank .' '. join(' ', $attr) . ' ' . $href . '>';
	// return $href;
	}
}
	//filter comment author link
function mq_auth_ReLinks($link) {
	global $opt_name;
	foreach ($opt_name as $mq_relinks_options){
		$mq_relinks_options_value[$mq_relinks_options] = get_option( $mq_relinks_options );
	}
	$relink_path_value = get_option('qr_path');
	$burl = get_bloginfo('url');
	$local = strpos($link, $burl);
	if ($local === false) 
	if ($mq_relinks_options_value['replace_in_author_comment_link'] =='yes'){
	if ($mq_relinks_options_value['blank_in_author_comment_link'] =='yes'){if ($local === false) $blank = 'target="_blank"';}
    if ($mq_relinks_options_value['mq_use_pretty_url'] =='no'){
	$link = preg_replace("#(.*href\s*=\s*)[\"\']*(.*)[\"\'] (.*)#i", "<a ".$blank." href='".$burl.$relink_path_value."out.php?url=$2' $3", $link);
	}else{
	$link = preg_replace("#(.*href\s*=\s*)[\"\']*(.*)[\"\'] (.*)#i", "<a ".$blank." href='".$burl."/out/$2' $3", $link);
	}

	}
	return $link;
} 

//install and uninstall functios
register_activation_hook( __FILE__, 'mq_relinks_install' );
register_deactivation_hook( __FILE__,'mq_relinks_uninstall');
register_uninstall_hook( __FILE__,'mq_relinks_uninstall');
//execute the function that adds the options page
add_action('admin_menu', 'mq_ReLinks_add_pages');
// add_action('activated_plugin','my_save_error');
// function my_save_error() {  file_put_contents(dirname(__file__).'/error_activation.txt', ob_get_contents()); }
//execute the filters
add_filter('the_content', 'mq_ReLinks_content');
add_filter('comment_text', 'mq_ReLinks_comment');
add_filter('get_comment_author_link', 'mq_auth_ReLinks');
add_filter('widget_text', 'mq_ReLinks_widget');
?>