<?php
/*
Plugin Name: Contact Form 7 Meta Box
Plugin URI: github??
Description: Adds a metabox for github
Version: 1.0
Author: nickreid
Author URI: http://nickreid.com
Author Email: nickreid@nickreid.com
License:

  Copyright 2012 TODO (email@domain.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class contact_form_7_meta_box {
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	var $folder_name = "contact_form_7_meta_box";
	
	var $option_types = "contact_form_7_meta_box_types";
	var $meta_field = "contact_form_7_meta_box";
	
	function __construct() {
	
		// TODO: replace "plugin-name-locale" with a unique value for your plugin
		load_plugin_textdomain( 'cf7mb-locale', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		
		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( &$this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_scripts' ) );
		
		register_activation_hook( __FILE__, array( &$this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
		
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
		
		add_action( 'get_footer',array(&$this,'display_contact_form'),10);

	} // end constructor
	
	function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate
	
	function deactivate( $network_wide ) {
		// TODO define deactivation functionality here		
	} // end deactivate
	
	public function register_admin_styles() {
	
		// TODO change 'plugin-name' to the name of your plugin
		wp_register_script( 'plugin-name-admin-styles', plugins_url( $this->folder_name.'/css/admin.css' ) );
		wp_enqueue_script( 'plugin-name-admin-styles' );
	
	} // end register_admin_styles
	public function register_admin_scripts() {
	
		// TODO change 'plugin-name' to the name of your plugin
		wp_register_script( 'plugin-name-admin-script', plugins_url( $this->folder_name.'/js/admin.js' ) );
		wp_enqueue_script( 'plugin-name-admin-script' );
	
	} // end register_admin_scripts
	
	
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	
	function admin_menu(){
		add_options_page( 'MetaBox Options', 'Contact Form 7 Meta Box Options', 'manage_options', 'my-unique-identifier',  array( $this, 'admin_menu_options' ) );
	}
	
	function admin_menu_options(){
		if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			if(isset($_POST[$this->option_types])){
				update_option($this->option_types, $_POST[$this->option_types]);
			}
			$selected_types = get_option( $this->option_types );
			$post_types = get_post_types(array(),'objects');
			include ('views/admin.php');
	}
	
	function add_meta_box(){
		$post_types = get_option( $this->option_types );
		if(!$post_types){
			return;
		}
		foreach($post_types as $post_type){
			add_meta_box(
					'contact_form_7_meta_box',
					esc_html__( 'Contact Form 7', 'cf7mb' ),
					array(&$this,'draw_meta_box'),
					$post_type,
					'normal',
					'default'
				);			
		}
	}
	function draw_meta_box($post){
		$contact_forms = get_posts( array(
			'numberposts' => -1,
			'orderby' => 'ID',
			'order' => 'ASC',
			'post_type' => 'wpcf7_contact_form' ) );
		include('views/metabox.php');
	}
	function save_meta($post_ID){
		if(isset($_POST[$this->meta_field])){
			update_post_meta($post_ID,$this->meta_field,$_POST[$this->meta_field]);
		}
		if(isset($_POST['cf7mb-title'])){
			update_post_meta($post_ID,'cf7mb-title',$_POST['cf7mb-title']);
		}
	}
	
	function has_contact_form($ID=false){
		if(!$ID){
			$ID = get_the_ID();
		}
		$selected_types = get_option( $this->option_types );
		if(!in_array(get_post_type($ID),$selected_types)){
			return false;
		}
		$form_id = get_post_meta($ID,$this->meta_field,true);
		if(!$form_id) return false;
		return $form_id;
	}
	
	function display_contact_form(){
		$form_id = $this->has_contact_form(get_the_ID());
		if(!$form_id) return;
		$title = get_post_meta(get_the_ID(),'cf7mb-title',true);
		echo do_shortcode('[contact-form-7 id="'.$form_id.'" title="'.$title.'"]');
	}
  
} // end class

new contact_form_7_meta_box();
?>