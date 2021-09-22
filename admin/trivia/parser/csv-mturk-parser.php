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
    $i = 0;
    $questionsArray = array();
    while (($line = fgetcsv($file)) !== FALSE) {
        
        if ($i > 0) {
            $questionsArray[$i+1]['category'] = !empty($line[28]) ? $line[28] : "";
            $questionsArray[$i+1]['correct_answer'] = !empty($line[30]) ? $line[30] : "";
            $questionsArray[$i+1]['incorrect_answers'][0] = !empty($line[31]) ? $line[31] : "";
            $questionsArray[$i+1]['incorrect_answers'][1] = !empty($line[32]) ? $line[32] : "";
            $questionsArray[$i+1]['incorrect_answers'][2] = !empty($line[33]) ? $line[33] : "";
            $questionsArray[$i+1]['question'] = !empty($line[34]) ? $line[34] : "";
            $questionsArray[$i+1]['question_source'] = !empty($line[27]) ? $line[27] : "";
        }
        $i++;
    }
    fclose($file);

    // insert into database
    if(!empty($questionsArray)) 
    {
        $rowCount = 0;
        $successCount = 0;
        $successQuestions = array();
        $failCount = 0;
        $failQuestions = array();
        $duplicatedCount = 0;
        $duplicatedQuestions = array();
        foreach($questionsArray as $item) 
        {
            // get category
            $category->name = $item['category'];
            $category->getByName();

            $category_id = 0;
            (!empty($category->id)) && $category_id = $category->id;
            
            // get question
            $question->question = htmlspecialchars(strip_tags($item['question']));
            $question->getQuestionByName();

            // if question duplicated or not
            if(empty($question->id))
            {
                $question->category_id = $category_id;
                $question->question = $item['question'];
                $question->correct_answer = $item['correct_answer'];
                $question->incorrect_answer1 = $item['incorrect_answers'][0];
                $question->incorrect_answer2 = $item['incorrect_answers'][1];
                $question->incorrect_answer3 = $item['incorrect_answers'][2];
                $question->question_source = !empty($item['question_source']) ? $item['question_source'] : "";
                $question->country_spesific = !empty($item['country_spesific']) ? $item['country_spesific'] : "";
                $question->correct_count = 0;
                $question->incorrect_count = 0;
                $question->reported = 0;
                $question->is_mturk = 1;
                $question->status = 1;
                $question->created = date('Y-m-d H:i:s');
                $question->modified = date('Y-m-d H:i:s');
                
                if($question->create())
                {
                    $successCount++;
                    $successQuestions[] = $item['question'];
                }
                else
                {
                    $failCount++;
                    $failQuestions[] = $item['question'];
                }
            }
            else {
                $duplicatedCount++;
                $duplicatedQuestions[] = $item['question'];
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
        <div style="margin: 20px auto; padding: 30px; width: 70%; text-align: center; border: 1px solid #ddd; border-radius: 6px;">
            <h3>Trivia Quiz Questions Generator</h3>
            <p>
            Parse Mturk CSV file into question data in database. 
            </p>

            
            <?php if(empty($message)) {?>
                <form method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <input type="file" name="csv_file" class="" id="csvFile">
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-info btn-block mt-2">Parse CSV</button>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </form>
                
            <?php } elseif(!empty($message)) {?>
                <hr class="mt-4">
                <p class="alert alert-success"><?php echo $message?>. <a href="./mysql-parser.php">Parse again.</a></p>
                
                <div style="text-align: left">
                    <?php if($successCount > 0) {?><strong>Success (<?php echo $successCount;?>):</strong><br><?php } ?> 
                    <small><?php 
                    if($successCount > 0){
                        echo '<ul>';
                        foreach($successQuestions as $successQuestion){
                            echo '<li>'.$successQuestion.'</li>';
                        }
                        echo '</ul>';
                    }?></small>

                    <?php if($failCount > 0) {?><strong>Failed (<?php echo $failCount;?>):</strong> <br><?php } ?>
                    <small><?php 
                    if($failCount > 0){
                        echo '<ul>';
                        foreach($failQuestions as $failQuestion){
                            echo '<li>'.$failQuestion.'</li>';
                        }
                        echo '</ul>';
                    }?></small>

                    <?php if($duplicatedCount > 0){?><strong>Duplicated (<?php echo $duplicatedCount;?>):</strong> <br><?php } ?>
                    <small><?php 
                    if($duplicatedCount > 0){
                        echo '<ul>';
                        foreach($duplicatedQuestions as $duplicatedQuestion){
                            echo '<li>'.$duplicatedQuestion.'</li>';
                        }
                        echo '</ul>';
                    }?></small>
                </div>
            <?php } ?>
        </div>
    </body>
</html>