<?php
header("Content-Type: application/json");

include_once '../../config/database.php';
include_once '../../objects/question.php';

include_once '../../../../vendor/autoload.php';
  
$database = new Database();
$db = $database->getConnection();
  
$question = new Question($db);

$question->id = isset($_GET['id']) ? $_GET["id"] : null;
$question->getQuestionById();

if (empty($question)) {
	http_response_code(200);
    echo json_encode([
        "success" => 0,
        "message" => "Question not found"
    ]);
}elseif (empty($question->answer_info)) {
	http_response_code(200);
    echo json_encode([
        "success" => 0,
        "message" => "Answer info not found"
    ]);
}else{
	$prev_answer_info = $question->answer_info;
	$str = $question->answer_info;

	$rep = "";

	for($i=0,$len=strlen($str),$pattern=""; $i<$len; ++$i) {
	  $pattern.= $str[$i];
	  if(substr_count($str,$pattern)>1)
	    $rep = strlen($rep)<strlen($pattern) ? $pattern : $rep;
	  else
	    $pattern = "";
	}

	$isDuplicate = false;
	$update = null;
	// warn if 20%+ of the string is repetitive
	if(strlen($rep)>=strlen($str)/2) {
		$isDuplicate = true;
		$question->answer_info = $rep;
		$update = $question->update();
	}else {
		$isDuplicate = false;
	}

	http_response_code(200);
	echo json_encode([
	    "success" => 1,
	    "message" => "",
	    "id" => $question->id,
	    "isDuplicate" => $isDuplicate,
	    "prev_answer_info" => $prev_answer_info,
	    "current_answer_info" => $question->answer_info,
	    "update" => $update,
	    "len_rep" => strlen($rep),
	    "len_str" => strlen($str),
	]);
}
?>