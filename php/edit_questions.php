<?php

// setup
include 'class.file.php';
$id = $_POST['id'];
$data = (isset($_POST['data'])) ? $_POST['data'] : array(); // (there's a strange bug where $_POST['data'] is not even set if an empty array is passed)

// process changes to test_$id.json
// initialize file
$test_file = new file('../json/tests/test_'.$id.'.json');
$test_arr = $test_file->readJSON();

// modify array and write to file
$test_arr['questions'] = $data;
$test_file->writeJSON($test_arr);
$test_file->close();

// also change the .last_edited of test_list.json
// initialize file
$test_list_file = new file('../json/test_list.json');
$test_list_arr = $test_list_file->readJSON();

// make changes to array
for($i = 0, $l = count($test_list_arr); $i < $l; $i++) {
	if($test_list_arr[$i]['id'] == $id) {
		$test_list_arr[$i]['time_edited'] = time();
		break;
	}
}

// write to file
$test_list_file->writeJSON($test_list_arr);
$test_list_file->close();


// success
echo 'Changes saved';

?>