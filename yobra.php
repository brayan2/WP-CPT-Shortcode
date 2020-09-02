<?php 
/**
 * @package Yobra Plugin
 */
/**
 Plugin Name: Yobra Plugin
 Plugin URI: https://bgathuita.com
 Description: This is my first attempt on coding a custom plugin for my learning process
 Version: 1.0.0
 Author: Brian "Yobra" Gathuita
 Author URI: https:bgathuita.com
 License: GPLv2 or later
 Text Domain: yobra_plugin
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

Copyright 2005-2015 Automattic, Inc.
*/
defined( 'ABSPATH' ) or die ('Hi there, you can not access this plugin without AUTH');

//the main class for the plugin
class YobraPlugin 
{
	//initialising the custom post type from this class
	function __construct(){
		add_action('init', array($this, 'custom_post_type'));
	}
	//load/initialize the shortcode from this class
	function loadcpt(){
		add_shortcode( 'yobra-shortcode', array($this,  'yobra_custom_shortcode' ));
	}
	//load the custom scripts from the frontend 
	function register(){
		add_action( 'wp_enqueue_scripts', array($this, 'yobra_shortcode_enqueue' ));
	}
	//plugin activation for the cpt
	function activate(){
		//generate a CPT
		$this->custom_post_type();
		//flush rewrite rules
		flush_rewrite_rules();
	}
	//plugin deactivation to remove cpt from backend
	function deactivate(){
		//flush rewrite rules
		flush_rewrite_rules();

	}
	function uninstall(){

	}
	//get the assets files and aplly them to the shortcode
	function yobra_shortcode_enqueue() {
		wp_register_style( 'yobra_shortcode_css', plugins_url('assets/style.css', __FILE__ ));
		wp_enqueue_style( 'yobra_shortcode_css' );
		wp_register_script( 'yobra_shortcode_js', plugins_url('assets/shortcode.js', __FILE__ ));
		wp_enqueue_script('yobra_shortcode_js');
	}

	function custom_post_type(){
		    // Set labels for custom post type

		$labels = array(
			'name' => 'Yobras',
			'singular_name' => 'Yobra',
			'add_new'    => 'Add Yobra',
			'add_new_item' => 'Enter Yobra Details',
			'all_items' => 'All Yobras',
			'featured_image' => 'Add Featured Image',
			'set_featured_image' => 'Set Featured Image',
			'remove_featured_image' => 'Remove Poster Image'

		);

    // Set Options for this custom post type;

		$args = array(    
			'public' => true,
			'label'       => 'Yobras',
			'labels'      => $labels,
			'description' => 'Yobras is a collection of Yobras and their info',
			'menu_icon'      => 'dashicons-heart',    
			'supports'   => array( 'title', 'editor', 'thumbnail'),
			'capability_type' => 'page',

		);
		register_post_type( 'yobra', $args );
	}
	//what to display on the shortcode
	function yobra_custom_shortcode() {

		$shortcode = array(
			'post_type'      => 'yobra',
			'posts_per_page' => '5',
			'publish_status' => 'published',
		);

		$query = new WP_Query($shortcode);

		if($query->have_posts()) :

			while($query->have_posts()) :

				$query->the_post() ;

				$result .= '<div class="yobra-item">';
				$result .= '<div class="yobra-poster">' . get_the_post_thumbnail() . '</div>';
				$result .= '<div class="yobra-name">' . get_the_title() . '</div>';
				$result .= '<div class="yobra-excpt">' . get_the_excerpt() . '</div>'; 
				$result .= '</div>';

			endwhile;

			wp_reset_postdata();

		endif;    

		return $result;

	}

	

}
if (class_exists('YobraPlugin'))
{
	$yobra_plugin = new YobraPlugin();
	$yobra_plugin->loadcpt();
	$yobra_plugin->register();
}
//activation
register_activation_hook( __FILE__, array( $yobra_plugin, 'activate' ) );

//deactivation
register_deactivation_hook( __FILE__, array( $yobra_plugin, 'deactivate' ) );
