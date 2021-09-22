<?php require_once __DIR__ . '/auth.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="trivia/assets/plugins/fontawesome-free/css/all.min.css">
    <title>YLC Games</title>
</head>
<body style="background-color: #f8f8f8;">
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <h5 class="my-0 mr-md-auto font-weight-normal">
        <img src="./assets/img/logo.svg" />
    </h5>
    <span class="navbar-text my-2 my-md-0 mr-md-3">
        Logged in as: <?= $_SESSION['user_name'] ?> <br/><small>(<?= $_SESSION['user_email'] ?>)</small>
  </span>
    <a class="btn btn-outline-primary" href="./logout.php">Logout</a>
</div>
<div class="container mt-4">
    <div class="row">
        <div class="col-4">
            <div class="card mt-4" style="min-height: 300px; box-shadow: 0px 0px 7px #ddd;">
                <div class="card-body">
                    <h5 class="card-title">DAILY TRIVIA</h5>
                    <h6 class="card-subtitle" style="color: red">YourLifeChoices</h6>
                    <p class="card-text"><small>a trivia game or competition is one where the competitors
                            are asked questions about interesting but unimportant facts in many subjects.</small></p>
                    <a href="trivia/views/question/" target="_blank" class="btn btn-primary"><i
                                class="fa fa-list"></i> Admin Panel</a>
                    <a href="/games/trivia/" target="_blank" class="btn btn-success"><i class="fa fa-play"></i> Play</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card mt-4" style="min-height: 300px; box-shadow: 0px 0px 7px #ddd;">
                <div class="card-body">
                    <h5 class="card-title">CROSSWORD</h5>
                    <h6 class="card-subtitle" style="color: red">YourLifeChoices</h6>
                    <p class="card-text"><small>A crossword is a word puzzle and word search game that usually takes
                            the form of a square or a rectangular grid of white- and black-shaded squares.
                            The game's goal is to fill the white squares with letters, forming words or phrases,
                            by solving clues, which lead to the answers.</small></p>
                    <a href="crossword/views/level/" target="_blank" class="btn btn-primary"><i
                                class="fa fa-list"></i> Admin Panel</a>
                    <a href="/games/crossword/" target="_blank" class="btn btn-success"><i class="fa fa-play"></i> Play</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card mt-4" style="min-height: 300px; box-shadow: 0px 0px 7px #ddd;">
                <div class="card-body">
                    <h5 class="card-title">WORDSEARCH</h5>
                    <h6 class="card-subtitle" style="color: red">YourLifeChoices</h6>
                    <p class="card-text"><small>Word games (also called word game puzzles or word search games)
                            are spoken or board games often designed to test ability with language or to explore its
                            properties.
                            Word games are generally used as a source of entertainment, but can additionally serve an
                            educational purpose.</small></p>
                    <a href="wordsearch/views/level/" target="_blank" class="btn btn-primary"><i
                                class="fa fa-list"></i> Admin Panel</a>
                    <a href="/games/wordsearch/" target="_blank" class="btn btn-success"><i class="fa fa-play"></i> Play</a>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card mt-4" style="min-height: 300px; box-shadow: 0px 0px 7px #ddd;">
                <div class="card-body">
                    <h5 class="card-title">SOLITAIRE</h5>
                    <h6 class="card-subtitle" style="color: red">YourLifeChoices</h6>
                    <p class="card-text"><small>Patience, or solitaire, is a genre of card games that can be played by a
                            single player.
                            Patience games can also be played in a head-to-head fashion with the winner selected by a
                            scoring scheme.</small></p>
                    <a href="solitaire/views/score" target="_blank" class="btn btn-primary"><i class="fa fa-list"></i> Admin Panel</a>
                    <a href="/games/solitaire/" target="_blank" class="btn btn-success"><i class="fa fa-play"></i> Play</a>

                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card mt-4" style="min-height: 300px; box-shadow: 0px 0px 7px #ddd;">
                <div class="card-body">
                    <h5 class="card-title">SUDOKU</h5>
                    <h6 class="card-subtitle" style="color: red">YourLifeChoices</h6>
                    <p class="card-text"><small>Sudoku is a logic-based, combinatorial number-placement puzzle.
                            In classic sudoku, the objective is to fill a 9×9 grid with digits so that each column,
                            each row, and each of the nine 3×3 subgrids that compose the grid contain all of the digits
                            from 1 to 9.</small></p>
                    <a href="sudoku/views/score" target="_blank" class="btn btn-primary"><i class="fa fa-list"></i> Admin Panel</a>
                    <a href="/games/sudoku/" target="_blank" class="btn btn-success"><i class="fa fa-play"></i> Play</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
