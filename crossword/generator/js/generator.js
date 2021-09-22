var cw;

/*window.onload = function(){




    new Clipboard('#copy');

    var regenerate = document.getElementById('regenerate');
    regenerate.addEventListener('click', generate, false);


    generate();


};*/
const MAX_GENERATED = 10;
const ALLOW_REGENERATED = false;
const MAX_GRID_SIZE = 20;
let generateCount = 0;
let maxTotalQuestion = 0;
let selectedBoardData = {};
let currentGridSize = null;

function generate(grid) {
  if(gameData.boardJSON && !ALLOW_REGENERATED){
    let boardData = JSON.parse(gameData.boardJSON);
    cw = boardData.cw;
    let legend = boardData.legend;
    printJson(legend);
    return;
  }
  if (typeof gameQuestions === "undefined") {
    alert("Could not find list of words and clues");
    return;
  }

  if (gameQuestions.length == 0) {
    alert("Please enter some words and clues to use.");
    return;
  }

  //   console.log(entries);
  //   console.log(gameQuestions);

  var words = new Array();
  var clues = new Array();

  //   entries = shuffle(entries);

  gameQuestions = shuffle(gameQuestions);

  //   for (var i = 0; i < entries.length; i++) {
  //     var entry = entries[i];
  //     words[i] = entry.word;
  //     clues[i] = entry.clue;
  //   }

  for (var i = 0; i < gameQuestions.length; i++) {
    var entry = gameQuestions[i];
    words[i] = entry.correct_answer.replace(/\s+/g, "");
    clues[i] = entry.question;
  }

  function shuffle(o) {
    for (
      var j, x, i = o.length;
      i;
      j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x
    );
    return o;
  }

  var r = grid; //parseInt(document.getElementById('size').value);
  var c = r;

  if (isNaN(r) || isNaN(c)) {
    alert("rows and/or columns number value invalid!");
    return;
  }

  cw = new Crossword(words, clues, r, c);

  // create the crossword grid (try to make it have a 1:1 width to height ratio in 10 tries)
  var tries = 10;
  var grid = cw.getSquareGrid(tries);

  // report a problem with the words in the crossword
  let bad_words = [];
  if (grid == null) {
    bad_words = cw.getBadWords();
    var str = [];
    for (var i = 0; i < bad_words.length; i++) {
      str.push(bad_words[i].word);
    }
    alert(
      "Shoot! A grid could not be created with these words:\n" + str.join("\n")
    );
    return;
  }

  // turn the crossword grid into HTML
  var show_answers = true;
  //document.getElementById("crossword").innerHTML = CrosswordUtils.toHtml(grid, show_answers);
  //   console.log(show_answers);
  // make a nice legend for the clues
  var legend = cw.getLegend(grid);
  if(!currentGridSize){
    currentGridSize = getMaxLengthOfAnswer();
    maxTotalQuestion = 0;
  }

  if(generateCount <= MAX_GENERATED){
    let totalLegend = legend.across.length + legend.down.length;
    if(maxTotalQuestion < totalLegend){
      selectedBoardData = {
          cw: cw,
          grid: grid,
          bad_words: bad_words,
          legend: legend
      }
      maxTotalQuestion = totalLegend;
    }
    generateCount ++;
    generate(currentGridSize);
  }else {
    console.log({
      grid: currentGridSize,
      total_question: gameQuestions.length,
      max_total_question: maxTotalQuestion
    });
    generateCount = 0;
    if(maxTotalQuestion != gameQuestions.length && currentGridSize < MAX_GRID_SIZE){
      currentGridSize++;
      generate(currentGridSize);
    }else {
      maxTotalQuestion = 0;
      currentGridSize = null;
      saveBoardData();
      //addLegendToPage(legend);
      printJson(selectedBoardData.legend);
    }
  }
}

function printJson(groups) {
  var puzzle = {};
  puzzle.width = cw.width;
  puzzle.height = cw.height;

  puzzle.acrossClues = new Array();
  puzzle.downClues = new Array();

  for (var k in groups) {
    var html = [];
    for (var i = 0; i < groups[k].length; i++) {
      var g = groups[k][i];
      var item = { answer: g.word, clue: g.clue, x: g.col, y: g.row };

      if (g.dir == "H") {
        puzzle.acrossClues.push(item);
      } else {
        puzzle.downClues.push(item);
      }
    }
  }

  puzzle.settings = settings;
  puzzle.labels = labels;

  var json = JSON.stringify(puzzle, null, 4);
  jsonLoaded(puzzle);
  //document.getElementById('output').innerHTML = '<pre>' + json + '</pre>';
}

function addLegendToPage(groups) {
  for (var k in groups) {
    var html = [];
    for (var i = 0; i < groups[k].length; i++) {
      html.push(
        "<li><strong>" +
          groups[k][i]["position"] +
          ".</strong> " +
          groups[k][i]["clue"] +
          "</li>"
      );
    }
    document.getElementById(k).innerHTML = html.join("\n");
  }
}

function saveBoardData() {
    let boardJSON = JSON.stringify(selectedBoardData);

    $.ajax({
        url: 'http://seniorsdiscountclub.com.au/games/crossword/api/level/update-board-json.php',
        type: 'POST',
        dataType: 'json',
        data: JSON.stringify({id: gameData.levelId, board_json: boardJSON}),
        success: function (result) {
            console.log(result);
            gameData.boardJSON = boardJSON;
        },
        error: function (err) {
            console.log(err.responseText);
        }
    });
}

function getMaxLengthOfAnswer(){
  let max = 0;
  gameQuestions.forEach(function(v){
    let correct_answer = v.correct_answer.replace(/\s+/g, "");
    max = max < correct_answer.length ? correct_answer.length : max;
  });

  return max;
}