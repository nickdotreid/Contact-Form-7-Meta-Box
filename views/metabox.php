<select name="<?=$this->meta_field;?>">
	<option value="">Select a contact form</option>
	<?foreach($contact_forms as $form):	?>
	<option value="<?=$form->ID;?>" <?
		if($form->ID == get_post_meta($post->ID,$this->meta_field,true)) echo "selected='true'";
	?>><?=$form->post_title;?></option>
	<?	endforeach;	?>
</select>
<label for="cf7mb-title">Title for form</label>
<input id="cf7mb-title" name="cf7mb-title" value="<?=get_post_meta($post->ID,'cf7mb-title',true);?>" />