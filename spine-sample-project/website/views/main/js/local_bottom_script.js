$(document).ready(function(){
	$('#sampleButton').click(function(){
		var query = 'hello';
		
		$.php('/new/sampleAjax', { query:query });
	});
});