<?php 

class Mis_Leads_CF7{
	
	private static $initiated = false;
	private static $adminpage;
	
	private static $option_name;
	private static $option;
	
	private static $fields;
	
	private static $i18n_domain;
	
	# Sino encontramos la presencia de estos campos, deberemos enviar un -1
	private static $required_fields = array(
			'name', 'surname', 'phone', 'message', 'field_1' );
	
	
	/**
	 * Initializes Plugin
	 */
	public static function init() {
		
		if ( ! self::$initiated ) {
			
			self::$option_name = 'misleads_cf7';
			
			self::$i18n_domain = 'misleads_cf7';
			
			self::init_hooks();
		}
		
	}
	
	/**
	 * Load the plugin text domain for translation.
	 */
	public static function load_plugin_textdomain() {
	
	
		$res = load_plugin_textdomain(
				self::$i18n_domain,
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
		
	
	}
	
	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
	
		self::$initiated = true;
		
		add_action( 'init', array( 'Mis_Leads_CF7', 'load_plugin_textdomain') );
		
		add_action( 'admin_menu', 		array( 'Mis_Leads_CF7', 'admin_menu') );
		
		add_action( 'init', 			array( 'Mis_Leads_CF7', 'save_config') );
		
		add_action( 'misleads_cf7_notices', 	array( 'Mis_Leads_CF7', 'admin_notices' ), 10, 2 );
		
		// Testing Contact Form Hook
		//add_action( 'wpcf7_before_send_mail', 	array( 'Mis_Leads_CF7', 'process_conversion' ) );
		
		// Version 1.0
		//add_action( 'wp_footer',				array( 'Mis_Leads_CF7', 'client_process_conversion') );
		
		// Backend Scripts
		add_action( 'admin_enqueue_scripts', array( 'Mis_Leads_CF7', 'admin_enqueue_scripts') );
		// Frontend Scripts
		add_action( 'wp_enqueue_scripts', array( 'Mis_Leads_CF7', 'enqueue_scripts') );

	
	}
	
	public static function admin_enqueue_scripts($hook){
		
		if( $hook == 'toplevel_page_misleads'){
			wp_enqueue_script( 'misleads_cf7', plugins_url( '/../js/admin.js', __FILE__ ), array('jquery') );
				
			// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
			wp_localize_script( 'misleads_cf7', 'misLeadsCF7',
					//array( 'ajax_url' => admin_url( 'admin-ajax.php' )
					array( 'option_name' => self::$option_name )
					);
			
		}
	
	}
	
	public static function enqueue_scripts(){
		
		wp_enqueue_script( 'misleads_cf7_front', plugins_url( '/../js/front.js', __FILE__ ), array('jquery'), null, true );
		
		wp_localize_script( 'misleads_cf7_front', 'misLeadsCF7',
				//array( 'ajax_url' => admin_url( 'admin-ajax.php' )
				array( 
						'option_name' => self::$option_name,
						'fields' => self::merge_fields( self::$option['cf7_fields'], self::$option['misleads_fields'] ),
						'products' => self::$option['products'],
						'source' => self::$option['source'],
						'cliente' => self::$option['cliente'],
						'required' => self::$required_fields,
						'debug' => WP_DEBUG,
						));
		
		
	}
	
	public static function admin_menu(){
		
		self::$adminpage = add_menu_page( 
				__('Mis Leads', self::$i18n_domain), 
				__('Mis Leads', self::$i18n_domain), 
				'manage_options', 
				'misleads', 
				array('Mis_Leads_CF7','renderAdminPage'), 
				'dashicons-cloud', 
				115 );
		
	}
	
	public static function renderAdminPage(){
		
		if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			self::admin_notices( __( 'The Contact Form 7 plugin is not activated.', self::$i18n_domain ), 'error');
		}
		
		$args = array(
				'sort_order' => 'asc',
				'sort_column' => 'post_title',
				'hierarchical' => 1,
				'exclude' => '',
				'include' => '',
				'meta_key' => '',
				'meta_value' => '',
				'authors' => '',
				'child_of' => 0,
				'parent' => -1,
				'exclude_tree' => '',
				'number' => '',
				'offset' => 0,
				'post_type' => 'page',
				'post_status' => 'publish'
		);
		$pages = get_pages($args);
		
		//$wp_domain = get_site_url(); // Fail with multisite folder
		
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$wp_domain = $protocol.$_SERVER['SERVER_NAME'];
		
		
		include( plugin_dir_path( __FILE__ ) . '../views/admin_page.php');
		
		
	}

	public static function get_config(){
		return get_option( self::$option_name );
	}
	
	public static function save_config(){
		
		if( isset($_POST[ self::$option_name ]) && !empty($_POST[ self::$option_name ]) && check_admin_referer( self::$option_name.'_nonce' ) ){
			
			$res = update_option( self::$option_name, $_POST[ self::$option_name ] );
			self::$option = $_POST[ self::$option_name ];
			
			
		}else{
			
			self::$option = self::get_config();
			
		}
		
		self::$fields = self::merge_fields( self::$option['cf7_fields'], self::$option['misleads_fields'] );
		
	}
	
	public static function merge_fields( $cf7_fields, $misleads_fields ){
		
		$fields = array();
		
		$total = count( $cf7_fields );
		
		for ($i = 0; $i < $total; $i++) {
			
			if(!empty($cf7_fields[$i])) $fields[ $cf7_fields[$i] ] = $misleads_fields[$i];
			
		}
		
		return $fields;
		
	}
	
	public static function admin_notices( $message, $class = 'updated' ) {
		
		if ( empty( $message ) ) {
			return;
		}
		?>
	   <div class="<?php esc_html_e( $class )?>">
	      <p><?php esc_html_e( $message, 'mis-leads-cf7' ); ?></p>
	   </div>
	   <?php
	}
	
	/*
	public static function process_conversion( $contact_form ){
		$submission = WPCF7_Submission::get_instance();
		echo '<pre>'.print_r($submission,1).'</pre>'; die;
	}
	
	
	public static function client_process_conversion(){

		?>
		<script>

		function ValidateFields( params ){

			//console.log(misLeadsCF7.required);
			console.log(params);
			test = [];

			for (var key in misLeadsCF7.required ) {
				
				if( params[ misLeadsCF7.required[ key ] ] == undefined ){
					console.log('NO EXISTE ' + misLeadsCF7.required[ key ] );
					
					params[ misLeadsCF7.required[ key ] ] == -1;
					test[ misLeadsCF7.required[ key ] ] == -1;
					//test.push( misLeadsCF7.required[ key ] )
				}
				
			}
			
			console.log( test )
			console.log( params )
			return params;
		}
		
		jQuery('.wpcf7-form').submit(function(ev) {
			ev.preventDefault(); // to stop the form from submitting

			var error = false;
			var values = jQuery(this).serializeArray();
			var params = [];

			jQuery.each( values, function( i, field ) {
				
				//console.log( field.name);
				if( misLeadsCF7.fields[ field.name ] ){
					params[ misLeadsCF7.fields[ field.name ] ] = field.value;
				}
				
			});

			params['product'] = misLeadsCF7.products[ window.location.pathname ];
			params['source'] = misLeadsCF7.source;
			
			//console.log( params );
			//  Validamos que los campos requeridos esten incluidos
			//params = ValidateFields( params );
			//console.log( params );
			
			//var str_param = jQuery.param( params, true );
			tmp_param = [];
			for (var key in params) {
				
				if( params[key] == ''){
					error = true;
				}else{
					tmp_param.push(key + '=' + encodeURIComponent(params[key]));	
				}
			}
			
			str_param = tmp_param.join('&');
			

			if(!error){
			
				console.log( 'https://gestiona.misleads.es/'+ misLeadsCF7.cliente +'/api/lead?' + String( str_param ) )
				
				jQuery.ajax({
					  type: "GET",
					  url: 'https://gestiona.misleads.es/'+ misLeadsCF7.cliente +'/api/lead?' + String( str_param ),
					  data: values,
					  crossDomain: true,
					  dataType: 'jsonp',
					  jsonp: false,
					  jsonpCallback: false
					  
					});
				
				// Validations go here 
				
				//this.submit(); // Envia el formulario 2 veces
				
			}else{
				console.log('Error, campos vacios');
				console.log( str_param );
			}
			
		});
		</script>
		<?php 
		
	}
	*/
}

?>