<?php
/*
Plugin Name: XV Planeta
Plugin URI: http://xavi.ivars.me/codi/xv-planeta
Description: A blog planet plugin
Version: 0.1
Author: Xavi Ivars
Author URI: http://xavi.ivars.me
License: GPLv2 or later
*/

define( 'XV_PLANETA_PATH', plugin_dir_path( __FILE__ ) );

include( XV_PLANETA_PATH . 'classes/class.planet.php' );

function xv_planeta_create() {

	global $xv_planeta;

	$xv_planeta = new XV_Planet();
}

xv_planeta_create();
