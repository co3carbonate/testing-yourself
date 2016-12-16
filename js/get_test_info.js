// get_test_info.js
// - Gets information about a test (whose id is in the URL parameter 't')
// - Sets the content of all span.testName to the name of the test
// - Appends the name of the test to title
// - (Only for access from a folder)

function get_test_info(callback) {
	var test = getQueryString('id');
	$.ajax({
		url: '../json/tests/test_' +test+ '.json',
		dataType: 'JSON',
		success: function(data) {
			console.log(data);
			$('span.testName').html(data.name);
			$('title').append(data.name);
			callback(data);
		}
	});	
}