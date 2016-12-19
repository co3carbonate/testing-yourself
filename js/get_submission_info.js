// get_submission_info.js
// - Gets information about a submission (whose id is in the URL parameter 'id')
// - Sets the content of all span.testName to the name of the test
// - Sets the content of all #dateString to dates like "19 Dec '16 at 2:29 pm"
// - Appends the name of the test to title
// - (Only for access from a folder)

function get_submission_info(callback) {
	var id = getQueryString('id');
	$.ajax({
		url: '../json/submissions/submission_' +id+ '.json',
		dataType: 'JSON',
		success: function(data) {
			console.log(data);
			$('span.testName').html(data.name);
			$('title').append(data.name);

			var date = new Date(0);
			date.setUTCSeconds(id);
			$('#dateString').html(
				dateStr(id)+ ' at ' +formatAMPM(date)
			);

			callback(data, id);
		}
	});	
}