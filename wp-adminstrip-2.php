<?php
/*
Plugin Name: Admin Strip 2
Plugin URI: http://www.somefoolwitha.com/tag/adminstrip
Description: Add an admin strip to your WP 2.1.x blog - based on an idea by <a href="http://www.somefoolwitha.com/2006/02/01/bow-before-me-for-i-am-root/">Matthew M</a> tweaked by <a href="http://www.smackfoo.com/" title="smackfoo.com All craft prepare to retreat">Brendan Borlaise</a>.
Author: Matthew Maber & Brendan Borlase
Version: 1.4.1
Author URI: http://www.somefoolwitha.com/
*/

/* Made into a plugin by  http://www.smackfoo.com/ */
/* iphone detection from iWPhone http://iwphone.contentrobot.com */
/* UTW IS NOW SUPPORTED - IF TAG COUNT IS *NOT* DISPLAYED, SCROLL DOWN AND UPDATE THE $tags VARIABLE */

function admin_strip_css() {
	echo '<style type="text/css">
		#adminstrip { 
			text-align:center; 
			bottom: 0; 
			clear:none; 
			z-index:9999;
			position:fixed; 
			background-color: #000;
			border-top: 1px solid #292929; 
			list-style: none; 
			color: #747474;
			-moz-opacity:0.85;
			width: 100%;
			float: none;
			opacity: 0.85;
			font: 10px Helvetica, Arial, Geneva, sans-serif;
			text-shadow: #000 0 1px 0;
			display: inline;
			right: 0;
			padding: 5px 4px 6px;
		}

		#adminstrip select {
			font-size: 10px;
		}
		#adminstrip li {
			display: inline;
		}

		#adminstrip #admin {
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			background-color: #303030;
			color: #fff;
			font-weight: bold;
			padding: 2px 3px 1px;
		}

		#adminstrip #admin:hover {
			background-color: #595959;
			text-decoration: underline;
		}

		#adminstrip a, #adminstrip a:visited { 
			color: #fff !important;
		}
			
		#adminstrip a:hover { 
			text-decoration: underline;
			opacity: 1;
		}
				
		#admincomments { 
			background-color: #f92e2d; 
			color: #fff !important; 
			padding: 1px 4px 2px;
			text-shadow: #a70000 0 1px 0;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			font-weight: bold;
		}
				
		#themeswitcher {
			display:inline;
		}
		
		#themeswitcher li {
			display:inline;
		}
		
	</style>';
}

// and so it begins

function admin_strip() {

// PLEASE EDIT THE TAGS VARIABLE BELOW TO MATCH YOUR WORDPRESS DATABASE PREFIX (default is wp_)
	$tags = 'wp_tags';

// PLEASE DO NOT EDIT BELOW THIS LINE UNLESS YOU KNOW WHAT YOU'RE DOING

	global $user_level, $wpdb;
	if ($user_level > 8) {
	$awaiting_mod = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'");
        $akismet_mod = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'");
		$numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'");
		if (0 < $numposts) $numposts = number_format($numposts); 

		$numcomms = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
		if (0 < $numcomms) $numcomms = number_format($numcomms);

		$numcats = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->categories");
		if (0 < $numcats) $numcats = number_format($numcats);
		
		if (function_exists('UTW_ShowCurrentTagSet')) {
			$numtags = $wpdb->get_var("SELECT COUNT(*) FROM $tags");
			if (0 < $numtags) $numtags = number_format($numtags); }
		
?>


<?php 	$container = $_SERVER['HTTP_USER_AGENT'];
	//print_r($container); //this prints out the user agent array. uncomment to see it shown on page.
	$useragents = array (
		"iPhone","iPod");
	$iphone = false;
	foreach ( $useragents as $useragent ) {
		if (eregi($useragent,$container)){
			$iphone = true;
		}
	}
	if($iphone){
		//echo ("<div style='font-size:30px; color:white;'>---!!!!!!!!!!You are on an iPhone or iPod touch - Lucky you!<br></div>");
	}else{  ?>
		
		<div id="adminstrip">

	
	<?php // cut down on db hits == good
		
		($home_ = get_settings('siteurl')); ($realhome_ = get_settings('home')); ?>
	<a href="<?php echo ($home_); ?>/wp-admin" title="Visit the Dashboard.."><span id="admin">DASHBOARD</span></a>
	 &middot; <a href="<?php echo ($home_); ?>/wp-login.php?action=logout" title="Log out..">Logout</a> 
	 &middot; <a href="<?php echo ($home_); ?>/wp-admin/post-new.php" title="Write an entry..">Write</a> 
	 &middot; <a href="<?php echo ($home_); ?>/wp-admin/edit.php" title="Update entries">Manage</a> 
	 &middot; <a href="<?php echo ($home_); ?>/wp-admin/options-general.php" title="Update settings..">Options</a> 
	<?php if ($awaiting_mod!=0) { ?> &middot; <a href="<?php echo ($home_); ?>/wp-admin/edit-comments.php" id ="admincomments" class="updated fade" title="Manage comments..">Moderate (<?php echo $awaiting_mod ?>)</a> <?php } ?>
	 <?php if ((function_exists('akismet_init')) & ($akismet_mod!=0) ) { ?>
		&middot; <a href="<?php echo ($home_); ?>/wp-admin/edit-comments.php?comment-status=spam" title="Manage spam..">Akismet (<?php echo $akismet_mod ?>)</a><?php } ?>
		

	<!--> uncomment this if you have Mint http://www.haveamint.com installed	&middot; <a href="<?php echo ($realhome_); ?>/mint" title="Mint">Mint</a>   <!-->
	  
	 &middot; <?php printf(__('There are <a href="%2$s" title="Posts">%1$s posts</a> and <a href="%4$s" title="Comments">%3$s comments</a>, contained within <a href="%6$s" title="categories">%5$s categories</a>'), 
				$numposts, $home_.'/wp-admin/edit.php',  $numcomms, $home_.'/wp-admin/edit-comments.php', $numcats, $home_.'/wp-admin/categories.php'); ?> 
	   <?php if (function_exists('UTW_ShowCurrentTagSet')) { echo (' and <a href="'.$home_.'/wp-admin/edit.php?page=ultimate-tag-warrior-actions.php" title="Manage tags..">'.$numtags.' tags</a>'); } ;?>
	 <?php if (function_exists('wp_theme_switcher')) { echo(' &middot; '); wp_theme_switcher('dropdown'); }?></div>
<?php
	}
}


	}
add_action('wp_head', 'admin_strip_css');
add_action('wp_footer', 'admin_strip');



// updated by Matthew M - rock!

?>