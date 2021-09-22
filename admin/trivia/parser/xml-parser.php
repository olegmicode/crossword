<?php

if(!empty($_FILES["csv_file"]["name"])) 
{
    // $opentdbUrl = !empty($_POST['api_url']) ?  $_POST['api_url'] : $defaultUrl ;

    // Read CSV file
    // category:
    // General Knowledge
    // TV & Movies
    // History
    // Food & Drink
    // Geography
    // Science & Technology
    // Words & Language
    // Plants & Animals
    // Musics
    // Books
    // World Politics
    // Sports
    // Maths & Numbers
    // Religion

    // loop each row
        // get column: 
        /**
         * Answer.Category
         * Answer.Question
         * Answer.Correct_answer
         * Answer.Incorrect_answer_1
         * Answer.Incorrect_answer_2
         * Answer.Incorrect_answer_3
         */
    // generate xml structure from column data

    // done
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
        }
        $i++;
    }
    fclose($file);

    if(!empty($questionsArray)) 
    {
        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;

        $nodeAttrTypeText = new DOMAttr('type', 'text');

        $root = $dom->createElement('questions');
        
        // category node loop
        $categoryArray = array();
        $category_list_node = $dom->createElement('category');
        foreach($questionsArray as $item) 
        {
            $category = $item['category'];

            $isExist = array_search($category, $categoryArray);
            $skippedCategory = ($category=='Entertainment: Japanese Anime & Manga') || ($category=='Entertainment: Video Games');
            // echo $category . ' | search result: '. $isExist;
            if($isExist === false AND !$skippedCategory) {
                // echo ' | not exist, put in array.<hr>';
                array_push($categoryArray, $category);
                $category_thumb_node = $dom->createElement('thumb', 'assets/item_thumb_3.svg');
                $category_thumb_node->setAttributeNode(new DOMAttr('name', $category));
                $category_list_node->appendChild($category_thumb_node);
            }
            // echo '<hr>';
            
        }

        // All category
        $category_thumb_node = $dom->createElement('thumb', 'assets/item_thumb_3.svg');
        $category_thumb_node->setAttributeNode(new DOMAttr('name', 'All'));
        $category_list_node->appendChild($category_thumb_node);
        
        $root->appendChild($category_list_node);

        // item Node loop
        foreach($questionsArray as $item) 
        {
            $category = $item['category'];
            $question = $item['question'];
            $correctAnswer = $item['correct_answer'];
            $incorrectAnswers = $item['incorrect_answers'];

            $skippedCategory = ($category=='Entertainment: Japanese Anime & Manga') || ($category=='Entertainment: Video Games');
            $lastHistoryFetched = '';
            if(!$skippedCategory) 
            {
                // construct XML
                $item_node = $dom->createElement('item');

                // category node
                $category_node = $dom->createElement('category');
                $category_node->appendChild($dom->createTextNode($category));
                $item_node->appendChild($category_node);

                // landscape node
                $landscape_node = $dom->createElement('landscape');

                    // question node
                    $question_node = $dom->createElement('question');
                    $question_node_val = $dom->createCDATASection($question);
                    $question_node->appendChild($question_node_val);
                    $question_node->setAttributeNode($nodeAttrTypeText);
                    
                    $landscape_node->appendChild($question_node);

                    // answers node
                    $answers_node = $dom->createElement('answers');
                    $answers_node->setAttributeNode(new DOMAttr('correctAnswer', '1'));

                        // correct answer node
                        $correctAnswer_node = $dom->createElement('answer');
                        $correctAnswer_node_val = $dom->createCDATASection($correctAnswer);
                        $correctAnswer_node->appendChild($correctAnswer_node_val);
                        $correctAnswer_node->setAttributeNode($nodeAttrTypeText);
                        $correctAnswer_node->setAttributeNode(new DOMAttr('fontSize', '24'));
                        $correctAnswer_node->setAttributeNode(new DOMAttr('top', '60'));
                        $answers_node->appendChild($correctAnswer_node);

                        $index = 1;
                        foreach($incorrectAnswers as $incorrectAnswer) {
                            // answer node
                            $answer_node = $dom->createElement('answer');
                            $answer_node_val = $dom->createCDATASection($incorrectAnswer);
                            $answer_node->appendChild($answer_node_val);
                            $answer_node->setAttributeNode($nodeAttrTypeText); 
                            $answer_node->setAttributeNode(new DOMAttr('fontSize', '24'));
                            ($index == 1) && $answer_node->setAttributeNode(new DOMAttr('top', '80'));
                            ($index == 2) && $answer_node->setAttributeNode(new DOMAttr('top', '60'));
                            ($index == 2) && $answer_node->setAttributeNode(new DOMAttr('left', '50'));
                            ($index == 3) && $answer_node->setAttributeNode(new DOMAttr('top', '80'));
                            ($index == 3) && $answer_node->setAttributeNode(new DOMAttr('left', '50')); 
                            $answers_node->appendChild($answer_node);
                            $index++;
                        }
                    
                    $landscape_node->appendChild($answers_node);

                    // $inputs_node = $dom->createElement('inputs');
                    // $landscape_node->appendChild($inputs_node);
                $item_node->appendChild($landscape_node);

                // portrait node
                $portrait_node  = $dom->createElement('portrait');

                    // question node
                    $question_node = $dom->createElement('question');
                    $question_node_val = $dom->createCDATASection($question);
                    $question_node->setAttributeNode(new DOMAttr('fontSize', '24'));
                    $question_node->setAttributeNode(new DOMAttr('top', '15'));
                    $question_node->setAttributeNode(new DOMAttr('lineHeight', '30'));
                    $question_node->appendChild($question_node_val);
                    $question_node->setAttributeNode($nodeAttrTypeText);

                    $portrait_node->appendChild($question_node);

                    // answers node
                    $answers_node = $dom->createElement('answers');
                    $answers_node->setAttributeNode(new DOMAttr('correctAnswer', '1'));

                        // correct answer node
                        $correctAnswer_node = $dom->createElement('answer');
                        $correctAnswer_node_val = $dom->createCDATASection($correctAnswer);
                        $correctAnswer_node->appendChild($correctAnswer_node_val);
                        $correctAnswer_node->setAttributeNode($nodeAttrTypeText); 
                        $correctAnswer_node->setAttributeNode(new DOMAttr('fontSize', '18'));
                        $correctAnswer_node->setAttributeNode(new DOMAttr('width', '90'));
                        $correctAnswer_node->setAttributeNode(new DOMAttr('height', '10'));
                        $correctAnswer_node->setAttributeNode(new DOMAttr('top', '50'));
                        $answers_node->appendChild($correctAnswer_node);

                        $index = 1;
                        foreach($incorrectAnswers as $incorrectAnswer) {
                            // answer node
                            $answer_node = $dom->createElement('answer');
                            $answer_node_val = $dom->createCDATASection($incorrectAnswer);
                            $answer_node->appendChild($answer_node_val);
                            $answer_node->setAttributeNode($nodeAttrTypeText); 
                            $answer_node->setAttributeNode(new DOMAttr('fontSize', '18'));
                            $answer_node->setAttributeNode(new DOMAttr('width', '90'));
                            $answer_node->setAttributeNode(new DOMAttr('height', '10'));
                            ($index == 1) && $answer_node->setAttributeNode(new DOMAttr('top', '62'));
                            ($index == 2) && $answer_node->setAttributeNode(new DOMAttr('top', '74'));
                            ($index == 3) && $answer_node->setAttributeNode(new DOMAttr('top', '86')); 
                            $index++;
                            $answers_node->appendChild($answer_node);
                        }
                    
                    $portrait_node->appendChild($answers_node);

                    // $inputs_node = $dom->createElement('inputs');
                    // $portrait_node->appendChild($inputs_node);
                $item_node->appendChild($portrait_node);

                // put inside root node
                $root->appendChild($item_node);
            }
            
        }

        $dom->appendChild($root);
        $xml_file_name = 'questions.xml';
        $dom->save($xml_file_name);

        $message =  "$xml_file_name has been successfully created. The questions are updated.";
    }
    else
    {
        $message =  "Failed to read/parse opentdb API.";
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
            <img src="assets/trivia_main_logo.svg" class="img-fluid" style="width: 30%" />
            <h3>Trivia Quiz Questions Generator</h3>
            <p>
            Parse Mturk CSV file into question list. 
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
                
            <?php } elseif(!empty($message)) {
                    echo '<hr class="mt-4"><p class="alert alert-success">' . $message . ' <a href="/game/scrape.php">Go back</a></p>';
            } ?>
        </div>
    </body>
</html>