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

if(!empty($_POST["json_link"])) 
{
    $curl = curl_init($_POST["json_link"]);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, []);
    
    $json_response = curl_exec($curl);
    $json_response_info = curl_getinfo($curl);

    if (curl_errno($curl)) {
        throw new Exception(curl_error($curl));
    }

    curl_close($curl);

    if (!empty($json_response)) {
        $json_response = json_decode($json_response, true);
    }
    // print_r($json_response);exit();

    // init vars
    $questionsArray = array();
    $newBatch = false;
    $batchSetCount = 0;
    $question_category = ($_POST['category']) ? $_POST['category'] : 'General Knowledge';

    if (is_array($json_response) || is_object($json_response))
    {
        foreach ($json_response as $q) {
            $batchSetCount++;
            $questionsArray[$batchSetCount]['category'] = $question_category;
            $questionsArray[$batchSetCount]['question'] = $q['question'];
            $questionsArray[$batchSetCount]['question_source'] = $q['source'];
            $questionsArray[$batchSetCount]['correct_answer'] = $q['correct_answer'];
            $questionsArray[$batchSetCount]['incorrect_answers'] = $q['incorrect_answer'];
        }
    }

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
                $question->is_mturk = 0;
                $question->origin = "json-scraped";
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

                //duplicated item need to update as 'json-scraped'
                $question->origin = "json-scraped";
                $question->setOrigin();
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
            Parse Json Link into question. All question generated from here, will be marked as "json-scraped" question. 
            (example: https://ylc.ccwiki.com.au/ajax/get-trivia-capital-city?ids=6,2,5,32)
            </p>

            
            <?php if(empty($message)) {?>
                <form method="POST" enctype="multipart/form-data" class="mt-4">
                    <div class="form-group row">
                        <div class="col-sm-2 text-right"></div>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Input json link" name="json_link" class="input-element form-control" id="json_link" required>
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
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info btn-block mt-2">Parse Now</button>
                        </div>
                        
                    </div>
                </form>
                
            <?php } elseif(!empty($message)) { ?>
                <hr class="mt-4">
                <p class="alert alert-success"><?php echo $message?>. <a href="./json-api-parser.php">Parse again.</a></p>
                
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