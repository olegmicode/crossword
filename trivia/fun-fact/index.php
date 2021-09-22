<?php

error_reporting(0);
// get database connection
include_once '../../admin/trivia/config/database.php';
  
// instantiate object
include_once '../../admin/trivia/objects/question.php';
include_once '../../admin/trivia/objects/category.php';
  
$database = new Database();
$db = $database->getConnection();

$question = new Question($db);
$category = new Category($db);


$path_components = explode('/', $_GET['slug']);
// print_r($path_components); exit;
$slug = $path_components[0];

if(!empty($slug)) {
    $question->slug = $slug;
    $question->getQuestionBySlug();
}

$isLoggedIn = false;

$imgUrl = '../../img/placeholder-300x200.jpg';
if(!empty($question->scrap_image)) {
    $imgUrl = $question->scrap_image;
}


$info = "Sorry. We don't have more info about this at the moment.";
$isAvailable = (!empty($question->answer_info));

if (!$isAvailable) {
    $imgUrl = '.././img/no-pass.svg';
} else {
    $info = $question->answer_info;
}

$answerCount = $question->correct_count + $question->incorrect_count;
$correctPercentage = 50;
$incorrectPercentage = 50;
if(!is_nan($question->correct_count) && $answerCount > 0) {
    $correctPercentage = $question->correct_count / $answerCount * 100;
}
if(!is_nan($question->incorrect_count)  && $answerCount > 0) {
    $incorrectPercentage = $question->incorrect_count / $answerCount * 100;
}
// print_r($question); exit;

?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <link rel="shortcut icon" href="./../icon.ico" type="image/x-icon" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <link rel="shortcut icon" href="./../icon.ico" type="image/x-icon" />

        <!-- custom style -->
        <link rel="stylesheet" href="./../css/funfact.css">

        <title>Your Life Choices - Trivia Fun Fact</title>

    </head>
    <body>
        <header class="topbar d-flex align-items-center">
            <div class="topbar_wrapper container-fluid d-flex justify-content-between align-items-center">
                <picture class="topbar_logo d-flex align-items-center">
                    <img src="./../img/ylc-color.svg" class="d-none d-lg-block" alt="Your Life Choices" srcset="">
                    <img src="./../img/ylc-color-simple.svg" class="topbar_logo_mobile d-block d-lg-none" alt="Your Life Choices" srcset="">
                </picture>
                <section class="d-none d-lg-flex topbar_action ">
                    <!-- <div class="input_container">
                        <input class="input pl-5 mr-3" type="text" placeholder="Search">
                        <div class="input_prefix">
                            <img src="./../img/search.svg" alt="">
                        </div>

                    </div> -->
                    <a href="https://www.yourlifechoices.com.au/member/login" class="btn btn--red btn--flat">Join or Sign In</a>
                </section>
                <section class="d-flex d-lg-none topbar_mobile_action">
                    <!-- <div class="mobile-search-menu">
                        <img src="./../img/searc-mobile.svg" alt="">
                    </div> -->
                    <div class="mobile-menu-toggler ml-5">
                        <img src="./../img/mobile-menu.svg" alt="">
                    </div>
                </section>
            </div>
        </header>
        <main class="main">
            <div class="container py-3 py-lg-0">
                <div class="row">
                    <section class="main_content col-lg-8">
                        <section class="card">
                            <h2 class="card-title">Question</h2>
                            <p><?php echo $question->question; ?></p>
                        </section>
                        <section class="card main_content_detail">
                            <h2 class="card-title">Answer</h2>
                            <div class="main_content_detail_container">
                                
                                <section class="d-flex flex-column align-items-center">
                                    <picture class="answer_img mb-3">
                                        <img src="<?php echo $imgUrl;?>" alt="">
                                    </picture>
                                    <section class="d-flex flex-column align-items-center">
                                        <h2 class="text-center section-title"><?php echo $question->correct_answer; ?></h2>
                                        <p class="text-center"><?php echo $info; ?></p>
                                        <a class="readmore-link" target="blank" href="<?php echo $question->question_source; ?>">Read More <img src="./../img/chevron_right_red.svg" alt=""></a>
                                    </section>
                                </section>

                                <div class="divider"></div>

                                <!-- <section class="main_content_detail_answer">
                                    <div class="answer-bar d-flex">
                                        <div class="answer-bar--correct" style="flex-basis:<?php echo $correctPercentage ?>%"></div>
                                        <div class="answer-bar--incorrect" style="flex-basis:<?php echo $incorrectPercentage ?>%"></div>
                                    </div>

                                    <section class="d-flex justify-content-around">
                                        <div class="text-center">
                                            <p class="text--small">Answer Correctly</p>
                                            <h1><?php echo $question->correct_count; ?></h1>
                                        </div>
                                        <div class="text-center">
                                            <p class="text--small">Answer Incorrectly</p>
                                            <h1><?php echo $question->incorrect_count; ?></h1>
                                        </div>
                                    </section>
                                </section>

                                 <div class="divider"></div> -->

                                 <div class="main_content_detail_share d-flex justify-content-center align-items-center flex-column flex-md-row">

                               
                                 <!-- <div class="text-center mb-5 mb-md-0 ">
                                    <p class="section-title">Did you like this answer?</p>
                                    <div class="d-flex mt-3 justify-content-center">
                                        <button id="likeBtn" class="btn btn--small btn--green mx-1">
                                            <img class="imgBtn" src="./../img/thumb-up.svg" alt="Like">
                                        </button>
                                        <button  id="dislikeBtn" class='btn btn--red btn--small mx-1'>
                                        <img class="imgBtn " src="./../img/thumb-down.svg" alt="Dislike">

                                        </button>
                                    </div>
                                </div> -->

                                 <div class="text-center ">
                                         <p class="section-title">Incorrect Info?</p>
                                         <div class="mb-3">
                                             <a id="reportBtn" href="#" class="btn btn--blue btn--small btn--flat p-2">
                                                 <img src="./../img/flag-report.svg" alt="" class="mr-2"> REPORT
                                             </a>
                                         </div>
                                     </div>

                                     
                                     <!-- <div class="text-center">
                                         <p class="text--small">Share to:</p>
                                         <div class="d-flex mt-3">
                                             <a href="#" id="shareFacebookBtn" class="mx-1">

                                                 <img class="imgBtn " src="./../img/btn-facebook.svg" alt="Share to Facebok" >
                                             </a>
                                             <a href="#" class="imgBtn mx-1"  id="shareTwitterBtn">
                                                <img class="" src="./../img/btn-twitter.svg" alt="Share to Twitter">

                                             </a>
                                         </div>
                                     </div> -->

                                    
                                 </div>

                                 <!-- <div class="divider d-none d-md-block"></div> -->

                                
                                <div class="main_content_detail_action pt-2 pb-3 d-none d-lg-block">

                                    <a id="playButton" href="./../index.html" class="btn btn--red mt-3 mx-auto btn--flat">Play Trivia Game <img src="./../img/chevron_right.svg"></a>
                                </div>
                            </div>

                        </section>
                    </section>
                    <aside class="main_side col-lg-4">
                        <div class="card">
                            <h2 class="card-title">
                                Play Other Games
                            </h2>
                            <section class="main_side_games">
                                <a href="https://fairshake.com.au/wordsearch-game/" target="blank" class="main_side_games_item">
                                    <img src="./../assets/game_wordsearch.png" alt="Play WordSearch" srcset="">
                                </a>
                                <a href="https://fairshake.com.au/crossword-game/" target="blank" class="main_side_games_item">
                                    <img src="./../assets/game_crossword.png" alt="Play CrossWord" srcset="">
                                </a>
                                <a href="https://fairshake.com.au/solitaire-game/" target="blank" class="main_side_games_item">
                                    <img src="./../assets/game_solitaire.png" alt="Play Solitaire" srcset="">
                                </a>
                                <a target="blank" href="http://www.yourlifechoices.com.au/fun/games/" class="btn btn--border-red btn--flat mt-3  align-self-stretch">
                                    View More Games
                                </a>
                            </section>
                        </div>
                        <!-- <div class="card">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title">Comments</h2>
                                <p class="text--small">15 comments</p>
                            </div>
                            <section class="main_side_loginbox">
                                <h2 class="text-center">Please sign in to make a comment.</h2>
                                <a href="#" class="btn btn--facebook mb-2 " ><img src="./../img/facebook-icon.svg" class="mr-3" alt="facebook" srcset=""> Sign in with Facebook</a>
                                <a href="#" class="btn btn--google "><img src="./../img/google-icon.svg" class="mr-3" alt="google" srcset=""> Sign in with Google</a>
                                <div class="main_side_loginbox_divider">or</div>
                                <form action="" class="d-flex flex-column">
                                    <input type="text" class="input mb-2" placeholder="Email">
                                    <input type="password" class="input mb-2" placeholder="Password">
                                    <button class="btn btn--red " type="submit">Sign in</button>
                                    <div class="d-flex align-items-center justify-content-center  mt-3">
                                        <a href="#" class="text-left">Forgot Password</a>
                                        <span class="mx-auto mx-md-3 mx-lg-auto">|</span>
                                        <a href="#" class="text-right">Create Account</a>
                                    </div>
                                </form>
                            </section>
                            <section class="main_side_commentbox mt-3">
                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                                    <div class="commenter d-flex align-items-center ">
                                        <span>comment as</span>
                                        <picture class="comment-avatar ml-3">
                                            <img src="https://i.pravatar.cc/304" alt="" srcset="">
                                        </picture>
                                    </div>
                                    <div class="word-count">
                                        <p class="mb-0">word : <span class="font-weight-bold">2</span></p>
                                    </div>
                                </div>
                                <form action="" class="d-flex flex-column">
                                   
                                    <textarea rows="6"  class="input mb-3" placeholder="comment"></textarea>
                                    <div class="d-flex justify-content-end">
                                        <a href="#" class="btn btn--border-red ">
                                           Cancel
                                        </a>
                                        <button class="btn btn--red ml-2 " type="submit">Post</button>

                                    </div>
                                </form>
                            </section>
                            <section class="main_side_comments py-3">
                                <div class="comment py-3 d-flex flex-column">
                                    <div class="comment_profile d-flex align-items-center">
                                        <picture class="comment-avatar mr-3">
                                            <img src="https://i.pravatar.cc/300" alt="" srcset="">
                                        </picture>
                                        <div class="comment_profile">
                                            <h6>Charlessss <span class="ml-2">Member</span></h6>
                                            <h6><span>21/05/2020</span></h6>
                                        </div>
                                    </div>
                                    <div class="comment_text my-3">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta sit ipsum debitis placeat similique inventore deserunt, in esse laboriosam quo cupiditate quae tenetur quibusdam ipsam eos repudiandae atque laudantium doloremque?</p>
                                    </div>
                                    <a href="#">Reply</a>
                                </div>

                                <div class="comment py-3 d-flex flex-column">
                                    <div class="comment_profile d-flex align-items-center">
                                        <picture class="comment-avatar mr-3">
                                            <img src="https://i.pravatar.cc/301" alt="" srcset="">
                                        </picture>
                                        <div class="comment_profile">
                                            <h6>Charlessss <span class="ml-2">Member</span></h6>
                                            <h6><span>21/05/2020</span></h6>
                                        </div>
                                    </div>
                                    <div class="comment_text my-3">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic nam veniam vel officia, nobis pariatur illo atque illum minus obcaecati non sapiente mollitia voluptates excepturi natus provident adipisci voluptatum incidunt.</p>
                                    </div>
                                    <a href="#">Reply</a>
                                </div>

                                <div class="comment py-3 d-flex flex-column">
                                    <div class="comment_profile d-flex align-items-center">
                                        <picture class="comment-avatar mr-3">
                                            <img src="https://i.pravatar.cc/302" alt="" srcset="">
                                        </picture>
                                        <div class="comment_profile">
                                            <h6>Charlessss <span class="ml-2">Member</span></h6>
                                            <h6><span>21/05/2020</span></h6>
                                        </div>
                                    </div>
                                    <div class="comment_text my-3">
                                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Numquam laborum eius, error aliquam atque voluptatem aperiam non, deleniti deserunt beatae nemo architecto eaque quae qui at neque quos quaerat consequatur?</p>
                                    </div>
                                    <a href="#">Reply</a>
                                </div>
                               
                                <a href="#" class="btn btn--border-red mt-3 ">
                                    <span>Load More Comments</span><img class="icon-red" class="ml-3" src="./../img/chevron_right_red.svg"  alt="">
                                </a>
                            </section>
                        </div> -->
                    </aside>
                </div>
                <div class="main_action py-3 d-block d-lg-none">

                    <a id="playButtonSticky" href="#" class="btn btn--red btn--flat mx-3">Play Trivia Game <img src="./../img/chevron_right.svg"></a>
                </div>
            </div>
            <div class="main_modal" id="reportModal">
                <a id="modalCloseBtn" class="main_modal_close">
                    <img src="./../img/close.svg" alt="">
                </a>
                <div class="main_modal_header">
                    <img class="trivia-logo" src="./../img/logo.svg" alt="Daily Trivia">
                    <img class="ylc-logo" src="./../img/ylc-color-simple.svg" alt="YLC">
                </div>
                <div class="main_modal_content">
                    <p>Thank you for helping us to keep the game awesome!</p>
                    <p>Your report will help us to improve the game quality</p>
                </div>
            </div>
            <div class="modal-backdrop" id="modalBackdrop"></div>
        </main>

        <script type="text/javascript" src="./../js/dist/bundle.js"></script>
        <script src="./../js/vendor/TweenMax.min.js"></script>
       
        <script>
            var slug = <?php echo json_encode($question->slug); ?>;
            var info = <?php echo json_encode($question->answer_info); ?>;
            var question = <?php echo json_encode($question->question); ?>;
            var answer = <?php echo json_encode($question->correct_answer); ?>;
            var statistic = {
                question_id : <?php echo json_encode($question->id); ?>,
                incorrect_count : 0,
                correct_count : 0,
                reported : 0,
            }
        </script>
        <script src="./../js/funfact.js"></script>
    </body>
</html>
