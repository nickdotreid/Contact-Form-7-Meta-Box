<?php

function cf7mb_remove_display_action(){
	global $cf7mb;
	remove_action('get_footer',array(&$cf7mb,'display_contact_form'),10);
}

function cf7mb_get_post_contact_form($ID=false){
	if($ID == false){
		$ID = get_the_ID();
	}
	$cf7mb = new contact_form_7_meta_box();
	if(!$cf7mb->has_contact_form($ID)) return false;
	return $cf7mb->get_contact_form($ID);
}

?>