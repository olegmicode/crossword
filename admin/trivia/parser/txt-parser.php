<?php
// get database connection
include_once '../config/database.php';
  
// instantiate object
include_once '../objects/question.php';
include_once '../objects/category.php';
  
$database = new Database();
$db = $database->getConnection();
  
$question = new Question($db);
$category = new Category($db);

if(!empty($_FILES["csv_file"]["name"])) 
{
    // Read CSV file
    $file = fopen($_FILES["csv_file"]["tmp_name"], 'r');

    // init vars
    $i = 0;
    $questionsArray = array();
    $newBatch = false;
    $batchSetCount = 0;
    $question_category = ($_POST['category']) ? $_POST['category'] : 'General Knowledge';
    $question_source = ($_POST['question_source']) ? $_POST['question_source'] : "";

    while ($line = fgets($file)) {
        $lineFirstChar = substr($line, 0, 1);
        // each batch consist: #, ^, a, b, c, d

        switch($lineFirstChar) {
            case '#':
                // new set of question start, reset array into new one
                $batchSetCount++;

                $questionLine = substr($line,3);
                $questionsArray[$batchSetCount]['category'] = $question_category;
                $questionsArray[$batchSetCount]['question'] = $questionLine;
                $questionsArray[$batchSetCount]['question_source'] = $question_source;
                $questionsArray[$batchSetCount]['correct_answer'] = '';
            break;

            case '^':
                $correct_answer = substr($line, 2);
                $questionsArray[$batchSetCount]['correct_answer'] = $correct_answer;
            break;

            case 'A':
                $answer1 = substr($line, 2);
                if($questionsArray[$batchSetCount]['correct_answer'] != $answer1) {
                    $questionsArray[$batchSetCount]['incorrect_answers'][] = $answer1;
                }
            break;

            case 'B':
                $answer2 = substr($line, 2);
                if($questionsArray[$batchSetCount]['correct_answer'] != $answer2) {
                    $questionsArray[$batchSetCount]['incorrect_answers'][] = $answer2;
                }
            break;

            case 'C':
                $answer3 = substr($line, 2);
                if($questionsArray[$batchSetCount]['correct_answer'] != $answer3) {
                    $questionsArray[$batchSetCount]['incorrect_answers'][] = $answer3;
                }
            break;

            case 'D':
                $answer4 = substr($line, 2);
                if($questionsArray[$batchSetCount]['correct_answer'] != $answer4) {
                    $questionsArray[$batchSetCount]['incorrect_answers'][] = $answer4;
                }
            break;
        }

        $i++;
    }
    fclose($file);

    // echo '<pre>';
    // $no=1;
    // foreach($questionsArray as $item) {
    //     echo $no .'. '. $item['category'] . '<br>';
    //     $no++;
    // }

    // insert into database
    if(!empty($questionsArray)) 
    {
        $rowCount = 0;
        $successCount = 0;
        $failCount = 0;
        $duplicatedCount = 0;
        foreach($questionsArray as $item) 
        {
            // get category
            $category->name = $item['category'];
            $category->getByName();

            $category_id = 0;
            (!empty($category->id)) && $category_id = $category->id;
            
            // get question
            $question->question = $item['question'];
            $question->getQuestionByName();

            // if question duplicated or not
            if(empty($question->id))
            {
                $question->category_id = $category_id;
                $question->question = $item['question'];
                $question->correct_answer = $item['correct_answer'];
                $question->incorrect_answer1 = $item['incorrect_answers'][0];
                $question->incorrect_answer2 = (!empty($item['incorrect_answers'][1])) ? $item['incorrect_answers'][1] : "";
                $question->incorrect_answer3 = (!empty($item['incorrect_answers'][2])) ? $item['incorrect_answers'][2] : "";
                $question->question_source = !empty($item['question_source']) ? $item['question_source'] : "";
                $question->country_spesific = !empty($item['country_spesific']) ? $item['country_spesific'] : "";
                $question->correct_count = 0;
                $question->incorrect_count = 0;
                $question->reported = 0;
                $question->status = 1;
                $question->created = date('Y-m-d H:i:s');
                $question->modified = date('Y-m-d H:i:s');

                if($question->create())
                {
                    $successCount++;
                }
                else
                {
                    $failCount++;
                }
            }
            else {
                $duplicatedCount++;
            }
            
            $rowCount++;
        }
    
        $message = "data read: $rowCount, success: $successCount, fail: $failCount, duplicated: $duplicatedCount";
    }
    else
    {
        $message =  "Failed to read/parse data.";
    }

}
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Trivia Quiz Questions Generator</title>
    </head>
    <body>
        <div style="margin: 20px auto; padding: 30px; width: 50%; text-align: center; border: 1px solid #ddd; border-radius: 6px;">
            <img src="../assets/img/trivia_main_logo.svg" class="img-fluid" style="width: 30%" />
            <h3>Trivia Quiz Questions Generator</h3>
            <p>
            Parse TXT file into question list. (example: https://raw.githubusercontent.com/uberspot/OpenTriviaQA/master/categories/general)
            </p>

            
            <?php if(empty($message)) {?>
                <form method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="form-group row">
                        <div class="col-sm-2 text-right"></div>
                        <div class="col-sm-8">
                            <input type="file" name="csv_file" class="input-element form-control" id="csvFile" required>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <select name="category" id="category" class="input-element form-control" required>
                                <option value="">Select a category</option>
                                <option value="General Knowledge">General Knowledge</option>
                                <option value="TV &amp; Movies">TV &amp; Movies</option>
                                <option value="History">History</option>
                                <option value="Food &amp; Drink">Food &amp; Drink</option>
                                <option value="Geography">Geography</option>
                                <option value="Science &amp; Technology">Science &amp; Technology</option>
                                <option value="Words &amp; Language">Words &amp; Language</option>
                                <option value="Plants &amp; Animals">Plants &amp; Animals</option>
                                <option value="Music">Music</option>
                                <option value="Books">Books</option>
                                <option value="World Politics">World Politics</option>
                                <option value="Sports">Sports</option>
                                <option value="Maths &amp; Numbers">Maths &amp; Numbers</option>
                                <option value="Religion">Religion</option>
                            </select>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 text-right"></div>
                        <div class="col-sm-8">
                            <input type="text" name="question_source" placeholder="Question source (eg: http://google.com)" class="input-element form-control" required>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info btn-block mt-2">Parse File</button>
                        </div>
                        
                    </div>
                </form>
                
            <?php } elseif(!empty($message)) {
                echo '<hr class="mt-4"><p class="alert alert-success">' . $message . '. <a href="./txt-parser.php">Parse again.</a></p>';
            } ?>
        </div>
    </body>
</html>