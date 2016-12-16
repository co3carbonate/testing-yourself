// setup.js
// - Standard setup options for each page

// AJAX default options
$.ajaxSetup({
	beforeSend: function(xhr, settings) {
		xhr.url = settings.url;
	},
	error: function(xhr) {
		console.log(xhr);
		var url = xhr.url.split('/').pop();
		var extension = url.split('.').pop();
		$('#ajaxStatus').html("Error " +xhr.status+ " while " +((extension != 'php') ? "loading \"" : "processing request with \"")+url+ "\": " +xhr.statusText+ ". Please try again.");
	},
	timeout: 8000,
	method: 'POST',
	dataType: 'text',
	async: true
});

// Gets the value of a query string
function getQueryString(name) {
	var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
	return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

// Generates a date string from a UTC Unix timestamp (e.g. 25 Nov '16)
function dateStr(timestamp) {
	var date = new Date(0);
	date.setUTCSeconds(timestamp);
	return date.getDate() + ' ' +(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])[date.getMonth()]+ ' \'' +date.getFullYear().toString().slice(-2);
}

// Generate time string with am/pm from a date (e.g. 12:01 pm)
function formatAMPM(date) {
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var ampm = hours >= 12 ? 'pm' : 'am';
	hours = hours % 12;
	hours = hours ? hours : 12;
	minutes = minutes < 10 ? '0'+minutes : minutes;
	var strTime = hours + ':' + minutes + ' ' + ampm;
	return strTime;
}


// Each property of an object (object) is set to the corresponding default value (defaultValues) 
// if it is not already set 
function objDefault(object, defaultValues) {
	for(var k in defaultValues) {
		if(!object.hasOwnProperty(k)) {
			object[k] = defaultValues[k];
		}
	}
}

// Trim leading and ending brs from a string 
function trim_br(str) {
	return str.replace(/<br\s*[\/]?>/gi, '\n').trim().replace(/\n/g, '<br>');
}

// Shuffle array
function shuffle(array) {
	for(var i = array.length - 1; i > 0; i--) {
		var j = Math.floor(Math.random() * (i + 1));
		var temp = array[i];
		array[i] = array[j];
		array[j] = temp;
	}
	return array;
}

// Actions to execute for each page
$(document).ready(function() {
	// Add an arrow at the end of each link in the page navigation
	$('.pageNav > a:not(:last-child)').after(' >');	

	// Back button clicked
	$('.backBtn').on('click', function() {
		window.location.href = $('.pageNav > a[href]').last().attr('href');
	});

});