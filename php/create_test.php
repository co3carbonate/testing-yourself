<?php

// setup
include 'class.file.php';
$name = trim($_POST['name']);

// process changes to test_list.json
// initialize file
$test_list_file = new file('../json/test_list.json');
$test_list_arr = $test_list_file->readJSON();

// create new array to push to file
$test_list_new = array(
	'name' => $name,
	'time_created' => time(),
	'time_edited' => time()
);

// set unique ID for the new array
if(count($test_list_arr) == 0) {
	$test_list_new['id'] = 0;
} else {
	$lastElem = end($test_list_arr);
	$test_list_new['id'] = $lastElem['id'] + 1;
}

// add to file
$test_list_arr[] = $test_list_new;
$test_list_file->writeJSON($test_list_arr);
$test_list_file->close();

// process creation of a new JSON file for this test
$test_file_new = new file('../json/tests/test_' .$test_list_new['id']. '.json');
$test_file_new->writeJSON(array(
	'name' => $name,
	'questions' => array(),
	'options' => array(
		'shuffle' => true,
		'multiline_response' => false
	)
));
$test_file_new->close();

// success
echo 'edit/?id=' . $test_list_new['id'];

?>