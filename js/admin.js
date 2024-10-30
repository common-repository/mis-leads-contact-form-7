jQuery(document).ready(function($) {
	
	$('#adwords-feed').on('click', '.downloadCSV', function(e){
		console.log('click');
		var data = {
				action: 'quadis_adwords_feed',
		}
		
		$.ajax({
			  type: "POST",
			  url: quadisAdwordsFeed.ajax_url,
			  data: data,
			  success: function(response,status, xhr){
				console.log(response);
				console.log(status);
				console.log(xhr);
			  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
				  console.log(XMLHttpRequest);
				  console.log(textStatus);
				  console.log(errorThrown);
			  }
		});
		  
	});
	
	var row = '<tr> \
					<td><input type="text" class="code" name="'+misLeadsCF7.option_name+'[cf7_fields][]"></td> \
					<td>= <input type="text" class="code" name="'+misLeadsCF7.option_name+'[misleads_fields][]"></td> \
				</tr>';
	
	$('#misleads-cf7-admin').on('click', '.misleads-addfield', function(e){
		
		console.log('duplicar');
		$('#misleads_tablefields tr:last').after( row );
		
	});
	
});