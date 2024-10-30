<style>
#misleads-cf7-admin {
	
}
#misleads-cf7-admin dt{
	border-top: 1px solid #CCC;
	margin-right: 40px;
}

#misleads-cf7-admin input.code{
	font-family: 'Ubuntu Mono', monospace;
}

</style>
<div id="misleads-cf7-admin" style="padding-left: 15px; padding-right: 15px;">
	<div class="welcome-panel" style="padding-bottom: 25px;">
		<div class="welcome-panel-content">
		
			<form method="post">
			
			<h2><?php _e('Mis Leads for Conctact Form 7', self::$i18n_domain );?></h2>
			<p class="description"></p>
			
			<div class="welcome-panel-column-container" >
				<div class="welcome-panel-column">
				
					<h3><?php _e('Configuration', self::$i18n_domain );?></h3>
				
					<label><?php _e('Client', self::$i18n_domain );?></label><br/>
					<input type="text" name="<?php echo self::$option_name;?>[cliente]" value="<?php echo ( isset(self::$option['cliente']) ? self::$option['cliente'] : 'demo' )?>" class="regular-text code"/>
					<p class="description">https://gestiona.misleads.es/<?php echo ( isset(self::$option['cliente']) ? self::$option['cliente'] : 'demo' )?>/api/lead</p>
				
					<label><?php _e('ID Source', self::$i18n_domain );?></label><br/>
					<input type="number" name="<?php echo self::$option_name;?>[source]" value="<?php echo ( isset(self::$option['source']) ? self::$option['source'] : 26 )?>" class="small-text"/>
					
				
				</div>
				
			
				<div class="welcome-panel-column">
				
					<h3><?php _e('Form Fields', self::$i18n_domain );?></h3>
					<p class="description"><?php _e("Add the Contact Form 7's fields you want to send to My Leads", self::$i18n_domain );?></p>
					
					<a href="#" class="misleads-addfield"><span class="dashicons dashicons-plus"></span><?php _e('Add Field', self::$i18n_domain )?></a>
					
					<table id="misleads_tablefields" class="fields">
						<thead>
							<tr>
								<th><?php _e('Contact Form 7', self::$i18n_domain );?></th>
								<th><?php _e('Mis Leads', self::$i18n_domain );?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach( self::$fields as $cf7_field => $misleads_field ): ?>
						<tr>
							<td><input type="text" class="code" name="<?php echo self::$option_name;?>[cf7_fields][]" value="<?php echo $cf7_field; ?>"></td>
							<td>= <input type="text" class="code" name="<?php echo self::$option_name;?>[misleads_fields][]" value="<?php echo $misleads_field; ?>"></td>
						</tr>
						<?php endforeach; ?>
						
						</tbody>
					</table>
					
					<pre><?php print_r( self::$fields )?></pre>
					
					
					
				</div>
			
				<div class="welcome-panel-column welcome-panel-last">
				
					<h3><?php _e('Pages', self::$i18n_domain );?></h3>
					<p class="description"><?php _e('Page Matching', self::$i18n_domain );?></p>
					
					
					<table id="misleads_tablefields" class="fields">
						<thead>
							<tr>
								<th><?php _e('Pathname', self::$i18n_domain ); ?></th>
								<th><?php _e('ID Product', self::$i18n_domain ); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($pages as $page ):
						
							$pathname = str_replace( $wp_domain ,"", get_permalink( $page->ID ));
							$value = ( isset( self::$option['products'][ $pathname ] ) && !empty( self::$option['products'][ $pathname  ] ) ) ? self::$option['products'][ $pathname ] : ''; 

						?>
						
						<tr>
							<td><?php echo $pathname; ?></td>
							<td>= <input type="number" class="small-text" name="<?php echo self::$option_name;?>[products][<?php echo $pathname; ?>]" value="<?php echo $value;?>"></td>
						</tr>
						<?php endforeach;?>
						</tbody>
					</table>
					
				</div>
				
			</div>
			<div class="welcome-panel-column-container">
			<?php 
			
			wp_nonce_field( self::$option_name.'_nonce' );
			submit_button();
			
			?>
			</div>
			</form>
			
		</div>
		
		<div class="welcome-panel-content">

			
		
		</div>
		
	</div>
</div>

