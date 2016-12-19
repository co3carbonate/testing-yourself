<?php

// init
include 'class.file.php';
$id = $_POST['id'];
$data = $_POST['data'];

// bug: all values in $data are converted to string values
//		i.e. 0.5 => '0.5', null => ''
//		we need to convert them back
// while we're at it, calculate the total score
$all_null = true;
$total_score = 0;
$l = count($data);
for($i = 0; $i < $l; $i++) {
	if($data[$i] === '') $data[$i] = null;
	else {
		$all_null = false;
		$data[$i] = floatval($data[$i]);
		$total_score += $data[$i];
	}
}
if($all_null) $total_score = null;
$new_score = ($total_score == null) ? null : $total_score. ' / ' .$l;

// open submission_$id.json, modify the array, and write it to the file
$submission_file = new file('../json/submissions/submission_' .$id. '.json');
$submission_arr = $submission_file->readJSON();

$submission_arr['score'] = $new_score;
for($i = 0; $i < $l; $i++) {
	$submission_arr['questions'][$i]['score'] = $data[$i];
}

$submission_file->writeJSON($submission_arr);
$submission_file->close();

// modify score in submission_list
$submission_list_file = new file('../json/submission_list.json');
$submission_list = $submission_list_file->readJSON();

for($i = 0, $m = count($submission_list); $i < $m; $i++) {
	if($id == $submission_list[$i]['id']) {
		$submission_list[$i]['score'] = $new_score;
		break;
	}
}

$submission_list_file->writeJSON($submission_list);
$submission_list_file->close();


// success
echo './?id=' .$id;


?>