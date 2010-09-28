<?php
/*
Plugin Name: BP Lotsa Feeds
Author: boonebgorges
Author URL: http://boonebgorges.com
Description: Member comment feeds
Version: 1.0
*/

function bplf_loader() {
	require_once( dirname(__FILE__) . '/bp-lotsa-feeds.php' );
}
add_action( 'bp_loaded', 'bplf_loader' );




?>