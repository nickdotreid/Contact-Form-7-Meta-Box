<div id="cf7mb" class="container">
	<form action="" method="post">
		<h2>Meta Box Options</h2>
		<p>Select which post types to show contact form 7 meta box on.</p>
		<?	foreach($post_types as $post_type):	?>
		<label class="checkbox">
			<input type="checkbox" name="<?=$this->option_types;?>[]" value="<?=$post_type->name;?>" <?
				if(in_array($post_type->name,$selected_types)) echo "checked='checked'";
			?>/>
			<?=$post_type->label;?>
		</label>
		<br />
		<?	endforeach;	?>
		<input type="submit" value="Save" />
	</form>
</div>