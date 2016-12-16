<?php

// setup
include 'class.file.php';
$id = $_POST['id'];
$data = $_POST['data'];
$time = time();

// create new submission_$time.json file
$submission_file = new file('../json/submissions/submission_' .$time. '.json');

// get this test's name and its questions
$test_file = new file('../json/tests/test_' .$id. '.json');
$test_arr = $test_file->readJSON();
$test_file->close();

// write the test's name, questions, answers, and actual information about the submission (user's answers, etc.) to the submission data file
$submission_file_arr = array(
	'name' => $test_arr['name'],
	'questions' => $test_arr['questions']
);
for($i = 0, $l = count($data); $i < $l; $i++) {
	$submission_file_arr['questions'][$i]['user_answer'] = $data[$i]['answer'];
}
$submission_file->writeJSON($submission_file_arr);
$submission_file->close();


// also add to submission_list.json
$submission_list_file = new file('../json/submission_list.json');
$submission_list = $submission_list_file->readJSON();

// add to array
$submission_list[] = array(
	'test_id' => $id,
	'name' => $test_arr['name'],
	'id' => $time
);

// write to submission_list.json
$submission_list_file->writeJSON($submission_list);
$submission_list_file->close();

// response
echo '../submission/?id=' .$time;




?>