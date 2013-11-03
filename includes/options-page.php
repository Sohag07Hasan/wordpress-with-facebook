<div class="wrap">
	
	<h2>Facbook Application Credentials</h2>
	
	<?php if($_POST['options_page_save'] == 'y'): ?>
		<div class="updated"> <p>Saved!..</p> </div>
		
		<?php 
			update_option('facebook_app_id', trim($_POST['fb_app_id']));
			update_option('facebook_app_secret', trim($_POST['fb_app_secret']));
		?>
		
	<?php endif; ?>
	
	
	<form method="post" action="">
	
		<input type="hidden" name="options_page_save" value="y" />
		<table class="form-table">
			<tr>
				<th scope="row"> <label for="fb_app_id">App ID</label> </th>
				<td> <input type="text" name="fb_app_id" value="<?php echo get_option('facebook_app_id'); ?>"> </td>
			</tr>
			
			<tr>
				<th scope="row"> <label for="fb_app_secret">App Secret</label> </th>
				<td> <input type="text" name="fb_app_secret" value="<?php echo get_option('facebook_app_secret'); ?>"> </td>
			</tr>
			
		</table>
		
		<p> <input type="submit" value="Save"> </p>
		
	</form>

</div>