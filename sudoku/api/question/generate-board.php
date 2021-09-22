<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../../../admin/sudoku/config/database.php';
  
// instantiate object
include_once '../../../admin/sudoku/objects/sudoku.php';
  
$database = new Database();
$db = $database->getConnection();

// get posted data
$data = json_decode(file_get_contents("php://input"));
// level accepted: 1, 2, 3, or 4
$level = !empty($data->level) ? $data->level : 1;
$level = ($level > 4 && $level < 1) ? 1: $level;

switch ($level) {
    case 1:
        $prePopulated = 40;
        $levelTitle = "easy";
        break;
    case 2:
        $prePopulated = 30;
        $levelTitle = "Medium";
        break;
    case 3:
        $prePopulated = 25;
        $levelTitle = "Hard";
        break;
    case 4:
        $prePopulated = 23;
        $levelTitle = "Expert";
        break;
    default:
        $prePopulated = 40;
        $levelTitle = "easy";
        break;
}

$puzzle = new Xeeeveee\Sudoku\Puzzle();
$puzzle->generatePuzzle($prePopulated);
$puzzle->solve();
$sudokuSolution = $puzzle->getSolution();
$sudoku = $puzzle->getPuzzle();

// construct response
$result = [
    "levelId" => $level,
    "level" => $levelTitle,
    "grid" => $sudoku,
    "clue" => $sudokuSolution,
];
// print_r($result);

http_response_code(200);
echo json_encode(array(
    "message" => 'Grid generated',
    "result" => $result
));

