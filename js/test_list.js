// test_list.js
// - Generates a list of tests in the form of a table #test_list

var test_list_options = test_list_options || {};
objDefault(test_list_options, {
	// RowEndHTML - a function to return HTML content to append to the end of each row
	RowEndHTML: function(data) { return ''; }
});

$(document).ready(function() {
	// Load json/test_list.json
	$.ajax({
		url: 'json/test_list.json',
		dataType: 'JSON',
		success: function(data) {
			console.log(data);
			// Sort data based on time_edited
			data.sort(function(a, b) {
				return (a.time_edited < b.time_edited) ? 1 : ((b.time_edited < a.time_edited) ? -1 : 0);
			});

			// Generate #test_list table contents
			var tableContents = '<tr><th>Name</th><th>Created</th><th>Edited</th></tr>';
			for(var i = 0, l = data.length; i < l; i++) {
				tableContents += '<tr>';
				tableContents += '<td>' +data[i].name+ '</td><td>' +dateStr(data[i].time_created)+ '</td><td>' +dateStr(data[i].time_edited)+ '</td>';
				tableContents += test_list_options.RowEndHTML(data[i]);
				tableContents += '</tr>';
			}

			$('#test_list').html(tableContents);
		}
	})
});