jQuery(document).ready(function($) {
	
	
	document.addEventListener( 'wpcf7mailsent', function( event ) {
		if( misLeadsCF7.debug ) console.log('[MIS LEADS DEBUG]');
		SendLead();
	}, false );
	
	
	function SendLead(){
		
		var values = $('.wpcf7-form').serializeArray();
		var params = [];
		
		$.each( values, function( i, field ) {

			if( misLeadsCF7.fields[ field.name ] ){
				params[ misLeadsCF7.fields[ field.name ] ] = field.value;
			}
			
		});

		params['product'] = misLeadsCF7.products[ window.location.pathname ];
		params['source'] = misLeadsCF7.source;
		
		// Version 2
		if( misLeadsCF7.debug ) console.log('[MIS LEADS DEBUG] - addRequiredFields()');
		if( misLeadsCF7.debug ) console.log( params );
		params = addRequiredFields( params );
		if( misLeadsCF7.debug ) console.log('[MIS LEADS DEBUG] - addRequiredFields OUTPUT');
		if( misLeadsCF7.debug ) console.log(params);
		
		tmp_param = [];
		for (var key in params) {
			
//			if( misLeadsCF7.debug ) console.log('[MIS LEADS] - Validating '+ key +' - '+ params[key] );
//			
//			if( needDefaultValue( key, params[key] ) == true ){
//				tmp_param.push(key + '=' + encodeURIComponent( '-1' ));
//			}else{
//				tmp_param.push(key + '=' + encodeURIComponent(params[key]));
//			}
			
			// Version 1.0
			tmp_param.push(key + '=' + encodeURIComponent(params[key]));
			
		}
		
		str_param = tmp_param.join('&');
		
		if( misLeadsCF7.debug ) console.log('[MIS LEADS DEBUG] - URL Request');
		if( misLeadsCF7.debug ) console.log( 'https://gestiona.misleads.es/'+ misLeadsCF7.cliente +'/api/lead?' + String( str_param ) )
		
		$.ajax({
			  type: "GET",
			  url: 'https://gestiona.misleads.es/'+ misLeadsCF7.cliente +'/api/lead?' + String( str_param ),
			  data: values,
			  crossDomain: true,
			  dataType: 'jsonp',
//			  jsonp: false,
//			  jsonpCallback: false,
			  success: function(response, status, xhr){
				  console.log(response);
				  console.log(status);
				  console.log(xhr);
			  },
				  
			});
		
	}
	
	function addRequiredFields( fields ){
		// 'name', 'surname', 'phone', 'message', 'field_1' 
		if( ( fields.indexOf('name') == -1 ) && ( misLeadsCF7.required.indexOf( 'name' ) ) ){
			console.log( 'name' + fields.indexOf('name') );
			fields['name'] = '-1';
		}else if( ( fields.indexOf('surname') == -1 ) && ( misLeadsCF7.required.indexOf( 'surname' ) ) ){
			console.log( 'surname' + fields.indexOf('surname') );
			fields['field_1'] = '-1';
		}else if( ( fields.indexOf('phone') == -1 ) && ( misLeadsCF7.required.indexOf( 'phone' ) ) ){
			console.log( 'phone' + fields.indexOf('phone') );
			fields['phone'] = '-1';
		}else if( ( fields.indexOf('message') == -1 ) && ( misLeadsCF7.required.indexOf( 'message' ) ) ){
			console.log( 'message' + fields.indexOf('message') );
			fields['message'] = '-1';
		}else if( ( fields.indexOf('field_1') == -1 ) && ( misLeadsCF7.required.indexOf( 'field_1' ) ) ){
			console.log( 'field_1' + fields.indexOf('field_1') );
			fields['field_1'] = '-1';
		}
		
		return fields;
	}
	
	function needDefaultValue( $field, $value ){
		//if( params[ misLeadsCF7.required[ key ] ] == undefined ){
		if( misLeadsCF7.debug ) console.log( $field + ' IN '+ $value );
		
		ind = misLeadsCF7.required.indexOf( $field );
		indparam = params.indexOf( $field );
		
		console.log(ind);
		
		//if( ind !== -1 ) return;
		
		if( ( indparam != -1) && (ind == -1 ) && !$value ){
			if( misLeadsCF7.debug ) console.log( 'No se encuentra '+$field+ ' y valor vacio.');
			return true;
		}else if( ( indparam != -1) && (ind == -1 ) && $value ) {
			if( misLeadsCF7.debug ) console.log( 'No se encuentra '+$field+ ' y pero tiene valor.');
			return false;
		/*}else if( (ind >= 0 ) && !$value ){
			return true;*/
		}else{
			if( misLeadsCF7.debug ) console.log( '-ELSE');
			return false;
		}
	}
	
	/*
	$('.wpcf7-form').submit(function(ev) {
		ev.preventDefault(); // to stop the form from submitting
	
		var values = jQuery(this).serializeArray();		
		
		jQuery.each( values, function( i, field ) {
			
			if($.inArray( field.name, misLeadsCF7.fields ) !== -1){
				
				key = $.inArray( field.name, misLeadsCF7.fields );
				name = misLeadsCF7.fields[key]
				console.log(name);
			}
				
			
			if(field.name == 'your-name'){
				name = field.value
			}
			
		});
		
	});
	*/
	
	
});