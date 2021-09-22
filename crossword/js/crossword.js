var ua = detect.parse(navigator.userAgent);
// var ua = navigator.userAgent;
var puzzle;

var acrossClues;
var downClues;
var zoomVal = 0;
var dragger;

var windowWidth = $(window).width();
var windowHeight = $(window).height();
var ScreenHeight = windowHeight;

const cardColorClass = [
  'card-red',
  'card-navy',
  'card-blue',
  'card-purple',
  'card-tomato',
  'card-green',
  'card-lime',
];

var key_interceptor = $('#key_interceptor');

let shareTitle = 'My Highscore on Crossword Game is [SCORE] out of 100';
let shareMessage =
  'I just played this awesome crossword game at [URL] with score [SCORE] out of 100! You should try it too! ';

var textBottomInfo = 'Select one block to view the question';
var textBottomInfoNoSelectedCell = 'Please select one of the block';
var board = $('#board');
var dragger = $('#dragger');
var bottomInfo = $('#bottom-info-trigger');
var btnStart = $('#btn_start');
var btnCheck = $('#btn-check');
var btnSetting = $('#btn-setting');
var btnPrev = $('#prevPage');
var btnNext = $('#nextPage');
var btnCheckWord = $('#btn-check-word');
var btnSolveWord = $('#btn-solve-word');
var btnHint = $('#btn-hint');
var btnStartTime = $('#time-trigger-start');
var btnPauseTime = $('#time-trigger-pause');
var pauseModal = $('#modal-pause');
var btnContinue = $('#btn-continue');
var btnSurrender = $('#btn-surrender');

// onboard
var onBoardModal = $('#modal-onboard');
var onboardIndex = 0;
const onboardModalContent = $('.modal-content-onboard__content');
const onboardModalIndicator = $('.modal-content-onboard__indicator__item');
const skipOnboardModalButton = $('#btnOnboardModalSkip');
const nextOnboardModalButton = $('#btnOnboardModalNext');
const startOnboardModalButton = $('#btnOnboardModalStart');

var prevSelectectedLiId;
var canvasFix = 0.5;
var solvedState;
var canvas;
var stage;
var grid;
var gridContainer;
var board;
var cellSize;
var cellMap;
var selectedCell = null;
var selectedCells = [];
var clueNumbers = {};
var horizontal = true;
var selectedWord;
var currentCache;
var canvasScale = 1;
var draggingCanvas;
var canvasX = 0;
var canvasY = 0;
var offsetX;
var offsetY;
var gameData = {};
var lastActiveCell;

var currentX = 0;
var currentY = 0;
var innerHeight;
var timer = new Timer();

var RESIZE_OPTION = {
  RESET: 'reset',
  ZOOM_IN: 'zoomIn',
  ZOOM_OUT: 'zoomOut',
  UNKNOWN: 'unknown',
};

//__info: additional data
var isOnTheGame = false;

var difficulty = {
  level: '',
  grid: '',
};

var loaderPosition = 0;

var gameLevels = [];
var gameQuestions = [];
var playerData = {};
var levelScores = [];

var pageData = {
  pageIndex: 1,
  pageTotal: 0,
  firstData: 0,
  lastData: 9,
  maxData: 9,
};

// Confetti Settings
var confettiElement = document.getElementById('confettiWrapper');
var confettiSettings = {
  target: confettiElement,
  size: 2,
  animate: true,
  respawn: true,
  clock: 30,
  rotate: true,
};
var confetti = new ConfettiGenerator(confettiSettings);
confetti.render();

var rippleZIndex = 100;

var iFramed = false;
let point = 10;
function Cell(number, x, y) {
  var c = new createjs.Container();

  for (var property in c) {
    this[property] = c[property];
  }

  this.gridX = x;
  this.gridY = y;

  this.mainBg = paint(puzzle.settings.letter_cell_color);
  this.addChild(this.mainBg);

  this.answered = false;
  this.disabled = false;
  this.revealed = false;

  this.highlightBg = paint(puzzle.settings.cell_highlight_color);
  this.addChild(this.highlightBg);
  this.highlightBg.visible = false;

  this.correctBg = paint(puzzle.settings.correct_cell_color);
  this.addChild(this.correctBg);
  this.correctBg.visible = false;

  // this.incorrectBg = paintTriangle(puzzle.settings.wrong_cell_color);
  this.incorrectBg = paint(puzzle.settings.wrong_cell_color); //__override
  this.addChild(this.incorrectBg);
  this.incorrectBg.visible = false;

  this.selectBg = paint(
    'RGBA(255,255,255,0)',
    puzzle.settings.selected_stroke_color,
    4
  );
  this.addChild(this.selectBg);
  this.selectBg.visible = false;

  this.hintBg = paint(puzzle.settings.hint_cell_color);
  this.addChild(this.hintBg);
  this.hintBg.visible = false;

  this.letter = '';
  this.letterText = new createjs.Text(
    this.letter,
    'bold 20px Source Sans Pro',
    puzzle.settings.letter_color
  );

  this.letterText.align = 'center';

  this.addChild(this.letterText);
  this.number = number;
  this.numberText = new createjs.Text(
    number,
    '10px Source Sans Pro',
    puzzle.settings.number_color
  );
  this.numberText.align = 'left';
  this.numberText.x = 5; //currently not effected when change the position (for little padding between cell and number)
  this.numberText.y = 5; //currently not effected when change the position (for little padding between cell and number)
  this.addChild(this.numberText);

  var thisCell = this;

  this.onClick = function (e) {
    if (!thisCell.disabled) {
      playSound('soundClick');
      cellClicked(thisCell, e);
    }
  };

  this.addEventListener('click', this.onClick, false);

  this.update = function () {
    this.letterText.text = this.letter;
    this.numberText.text = this.number;
    var b = this.letterText.getBounds();
    if (b) {
      this.letterText.x = cellSize.width / 2 - b.width / 2;
      this.letterText.y = cellSize.height / 2 - b.height / 2;
    }
  };

  this.highlight = function (on) {
    this.highlightBg.visible = on;
  };

  this.setSelected = function (on) {
    gridContainer.swapChildrenAt(
      gridContainer.getChildIndex(this),
      gridContainer.getChildIndex(lastActiveCell)
    );
    lastActiveCell = this;

    if (this.disabled) {
      this.selectBg.visible = false;
    } else {
      this.selectBg.visible = on;
    }
    // lastActiveCell = this;
  };

  this.showIncorrect = function (show) {
    if (this.letterText.text) {
      this.incorrectBg.visible = show;
      this.answered = true;
    }
  };

  this.showCorrect = function (show) {
    if (this.letterText.text) {
      this.correctBg.visible = show;
      this.disabled = show;
      this.answered = true;
    }
  };

  // this.clear = function () {
  //   if (this.letterText.text) {
  //     this.correctBg.visible = false;
  //     this.incorrectBg.visible = false;
  //     this.answered = false;
  //     this.disabled = false;
  //     this.revealed = false;
  //   }
  // }

  this.showHint = function (show) {
    this.hintBg.visible = show;
    this.answered = true;
    this.revealed = true;
  };

  this.scale = function (w, h) {
    this.numberText.x = w * 0.03;
    this.numberText.y = w * 0.03;
    //this.letterText.x = h * 0.3;
    this.letterText.y = h * 0.15;

    this.numberText.font = 'bold ' + w / 3 + 'px Source Sans Pro';
    this.letterText.font = 'bold ' + w / 1.7 + 'px Source Sans Pro';
  };

  this.ripple = function () {
    var tileId = 'tile_' + this.gridX + '_' + this.gridY;

    var cellWidth = cellSize.width - 1;
    var cellHeight = cellSize.height - 1;

    $('<div>', {
      id: tileId,
      class: 'reveal_ripple',
      css: {
        position: 'absolute',
        'z-index': ++rippleZIndex,
        overflow: 'hidden',
        width: cellWidth + 'px',
        height: cellHeight + 'px',
        left: this.x + 10 + 'px',
        top: this.y + 10 + 'px',
      },
    }).appendTo('#dragger');

    $('<a>', {
      id: tileId + '_a',
      href: '#',
      css: {
        'z-index': '10',
        width: '100%',
        height: '100%',
      },
    }).appendTo('#' + tileId);

    var element = $('#' + tileId + '_a');

    var parent = element.parent();

    if (parent.find('.tile_animator').length == 0)
      parent.prepend("<span class='tile_animator'></span>");

    parent.css('overflow', 'hidden');

    var tile_animator = parent.find('.tile_animator');
    tile_animator.removeClass('animate');
    tile_animator.css('overflow', 'hidden');

    if (!tile_animator.height() && !tile_animator.width()) {
      var d = Math.max(parent.outerWidth(), parent.outerHeight());
      tile_animator.css({ height: d, width: d });
    }

    var xPos = element.offset().left - parent.offset().left;
    var yPos = element.offset().top - parent.offset().top;

    tile_animator
      .css({ top: yPos + 'px', left: xPos + 'px' })
      .addClass('animate');

    setTimeout(function () {
      $('#' + tileId).remove();
    }, 600);
  };

  function paintTriangle(color) {
    var shape = new createjs.Shape();
    shape.graphics.beginFill(color);
    shape.graphics.moveTo(cellSize.width / 2, 0);
    shape.graphics.lineTo(cellSize.width, 0);
    shape.graphics.lineTo(cellSize.width, cellSize.height / 2);
    shape.graphics.lineTo(cellSize.width / 2, 0);
    shape.graphics.endFill();
    return shape;
  }

  function paint(
    color,
    borderColor = puzzle.settings.empty_cell_color,
    strokeWidth = 0
  ) {
    var shape = new createjs.Shape();

    shape.graphics.beginFill(color);

    shape.graphics.snapToPixel = true;
    shape.graphics.drawRect(0, 0, cellSize.width, cellSize.height);
    shape.graphics.endFill();

    shape.graphics.setStrokeStyle(strokeWidth);
    shape.graphics.beginStroke(borderColor);
    shape.graphics.moveTo(0, 0);
    shape.graphics
      .lineTo(0, cellSize.width)
      .lineTo(cellSize.width, cellSize.width)
      .lineTo(cellSize.width, 0)
      .lineTo(0, 0)
      .closePath();

    shape.graphics.endStroke();

    return shape;
  }
}

$(document).ready(function () {
  initPreload();
  // checkOrientation();
});

function supportsTouch() {
  return (
    'ontouchstart' in window ||
    navigator.MaxTouchPoints > 0 ||
    navigator.msMaxTouchPoints > 0
  );
}

function mouseScrolled(e) {
  var delta = 0;
  e = e || window.event;

  if (e.wheelDelta) delta = e.wheelDelta / 120;
  else if (e.detail) delta = -e.detail / 3;

  if (delta) zoomBoard(delta);
  if (e.preventDefault) e.preventDefault();
  e.returnValue = false;
}

function zoomBoard(delta) {
  resizeGrid(delta < 0 ? RESIZE_OPTION.ZOOM_OUT : RESIZE_OPTION.ZOOM_IN);
}

function startGame() {
  console.log('startGame')
  
  $('#btn-solve-word span').text(gameData.solveCount);
  $('#btn-hint span').text(gameData.hintCount);
  countScore();
  resetTimer();
  timer.start();
  timer.addEventListener('secondsUpdated', function (e) {
    $('#chrono').html(timer.getTimeValues().toString());
  });

  if (gameData.onBoarding == 1) {
    startTimeTick();
  }

  if (!supportsTouch()) {
    canvas.addEventListener('mousedown', canvasMouseDown, false);
    canvas.addEventListener('mouseup', canvasMouseUp, false);
    canvas.addEventListener('mousemove', canvasMouseMove, false);

    dragger[0].onmousewheel = mouseScrolled;
    dragger[0].addEventListener('DOMMouseScroll', mouseScrolled, false);
  }

  gridContainer.children.forEach((element) => {
    if (element.cursor == null) {
      lastActiveCell = element;
    }
  });

  // setTimeout(() => {
  //   centerBoard();
  // }, 200);
}

function selectFirstWord() {
  if ($('#content').width() > 600 && !supportsTouch()) {
    $('.clue_list li a').first().click();
  } else {
    playSound('soundClick');
  }
}

function centerBoard() {
  // var left = (board.width() - dragger.width()) / 2 - 10;
  // var left = (board.width() - dragger.width()) / 2;
  // dragger.css('left', Math.round(left));
  // var top = (board.height() - dragger.height()) / 2;
  // dragger.css('top', Math.round(top));
}

function resizeGrid(option) {
  if (option == RESIZE_OPTION.UNKNOWN) {
    resize();
    // centerBoard();
  }

  if (option == RESIZE_OPTION.RESET) {
    zoomVal = 1;
    canvasScale = 1;
    // centerBoard();

    canvasX = 0;
    canvasY = 0;

    transformDragger(canvasScale, canvasX, canvasY);
  }
  if (option == RESIZE_OPTION.ZOOM_IN) {
    zoomVal = 1;
    if (canvasScale != 5) {
      canvasScale += 0.1;
      canvasScale = Math.min(canvasScale, 5);
      transformDragger(canvasScale, canvasX, canvasY);
    }
  }
  if (option == RESIZE_OPTION.ZOOM_OUT) {
    zoomVal = 1;

    if (canvasScale != 1) {
      canvasScale -= 0.1;
      canvasScale = Math.max(canvasScale, 1);

      transformDragger(canvasScale, canvasX, canvasY);

      var x =
        (dragger.width() * canvasScale - dragger.width()) / 2 -
        (board.width() - dragger.width()) / 2;
      x = board.width() > dragger.width() * canvasScale ? 0 : x;

      var height = (dragger.height() * canvasScale - dragger.height()) / 2;
      var totalHeight =
        (dragger.height() * canvasScale - dragger.height()) / 2 +
        (dragger.height() - board.height());

      if (canvasX >= x) {
        transformDragger(canvasScale, x, canvasY);
        canvasX = x;
      } else if (canvasX < -x) {
        transformDragger(canvasScale, -x, canvasY);
        canvasX = -x;
      }
      if (canvasY >= height) {
        transformDragger(canvasScale, canvasX, height);
        canvasY = height;
      } else if (canvasY < -totalHeight) {
        transformDragger(canvasScale, 'canvasX', -totalHeight);
        canvasY = -totalHeight;
      }
      if (canvasScale < 1) {
        transformDragger(canvasScale, 0, 0);
        canvasX = 0;
        canvasY = 0;
      }
    }
  }
}

function canvasMouseDown(e) {
  transformDragger(canvasScale, canvasX, canvasY);
  draggingCanvas = draggingCanvas ? false : true;

  offsetX = e.clientX;
  offsetY = e.clientY;
}

function canvasMouseUp(ev) {
  draggingCanvas = false;
  var finalMatrix = dragger.css('-moz-transform');
  if (finalMatrix == undefined) finalMatrix = dragger.css('-ms-transform');
  if (finalMatrix == undefined) finalMatrix = dragger.css('-o-transform');
  if (finalMatrix == undefined) finalMatrix = dragger.css('-webkit-transform');
  var parts = finalMatrix.split(',');
  var tempX = parseInt(parts[4]);
  var tempY = parseInt(parts[5]);
  canvasX = tempX;
  canvasY = tempY;
  var newX =
    (dragger.width() * canvasScale - dragger.width()) / 2 -
    (board.width() - dragger.width()) / 2;

  newX = board.width() > dragger.width() * canvasScale ? 0 : newX;
  var newY = (dragger.height() * canvasScale - dragger.height()) / 2;
  var height =
    (dragger.height() * canvasScale - dragger.height()) / 2 +
    (dragger.height() - board.height());
  if (tempX >= newX) {
    transformDragger(canvasScale, newX, currentY);
    currentX = newX;
    canvasX = newX;
  } else if (tempX < -newX) {
    transformDragger(canvasScale, -newX, currentY);
    currentX = -newX;
    canvasX = -newX;
  }
  if (tempY >= newY) {
    transformDragger(canvasScale, currentX, newY);
    currentY = newY;
    canvasY = newY;
  } else if (tempY < -height) {
    transformDragger(canvasScale, currentX, -height);
    currentY = -height;
    canvasY = -height;
  }
  if (canvasScale < 1) {
    transformDragger(canvasScale, 0, 0);
    canvasX = 0;
    canvasY = 0;
  }
}

function canvasMouseMove(e) {
  if (draggingCanvas) {
    var x = e.clientX - offsetX + canvasX;
    var y = e.clientY - offsetY + canvasY;
    console.log(x, y);
    transformDragger(canvasScale, x, y);
  }
}

function transformDragger(scale, x, y) {
  dragger.css(
    '-moz-transform',
    'matrix(' + scale + ',0,0,' + scale + ',' + x + 'px,' + y + 'px)'
  );
  dragger.css(
    '-ms-transform',
    'matrix(' + scale + ',0,0,' + scale + ',' + x + ',' + y + ')'
  );
  dragger.css(
    '-o-transform',
    'matrix(' + scale + ',0,0,' + scale + ',' + x + ',' + y + ')'
  );
  dragger.css(
    '-webkit-transform',
    'matrix(' + scale + ',0,0,' + scale + ',' + x + ',' + y + ')'
  );
}

function setCanvasDimensions() {
  const documentHeight = $('body').height();
  const documentWidth = $('body').width();
  const minWidth = Math.min(documentWidth, documentHeight - 130);

  // $('.clue_list').width(0);
  var rows = puzzle.width;
  var columns = puzzle.height;
  var maxwidth = board.width() - 20;
  var maxHeight = board.height() - 20; //__info: this -20 is intended to make the grid to look same as the design (by adding black border around)

  var boxWidth = parseInt(maxwidth / columns);
  var boxHeight = parseInt(maxHeight / rows);

  var boxSize;
  
  canvas.width = canvas.height = minWidth;
  $('#game_view').width(minWidth)

  if (boxWidth >= boxHeight) {
    boxSize = maxHeight / rows;
    // canvas.width = canvas.height = maxHeight;
  } else {
    boxSize = maxwidth / columns;
    // canvas.width = canvas.height = maxwidth;
  }

  dragger.width(canvas.width);
  dragger.height(canvas.height);
  const clueWidth = windowWidth - canvas.width - 20;
  // $('.clue_list').width(clueWidth / 2);

  cellSize = { width: boxSize, height: boxSize };
}

function setLabels() {
  $('#crossword_theme').html(puzzle.labels.crossword_theme);
  $('#description').html(puzzle.labels.description);

  $('#help_text').html(puzzle.labels.help_text);
  $('#help_title').html(puzzle.labels.help_title);
  $('.game_title').html(puzzle.labels.game_title);
  $('#btn_dialog_close').html(puzzle.labels.button_label_dialog_close);

  $('#across_label').html(puzzle.labels.across);
  $('#down_label').html(puzzle.labels.down);

  $('#congrat_title').html(puzzle.labels.congrat_title);
  $('.dialog_close').html(puzzle.labels.button_label_dialog_close);
  $('#btn_share_win').html(puzzle.labels.btn_label_share_win);

  $('#btn-yes').html(puzzle.labels.btn_label_yes);
  $('#btn-no').html(puzzle.labels.btn_label_no);

  $('#reveal_letter').html(puzzle.labels.reveal_letter);
  $('#reveal_word').html(puzzle.labels.reveal_word);
  $('#submit').html(puzzle.labels.submit_answer);
}

function setupGameMenu() {
  var btn_menu_opener = $('.fixed-action-btn');
  var btn_menu_clear = $('#btn_menu_clear');
  var btn_menu_reveal_word = $('#btn_menu_reveal_word');
  var btn_menu_reveal_letter = $('#btn_menu_reveal_letter');
  var btn_menu_check = $('#btn_menu_check');

  if (supportsTouch()) {
    btn_menu_opener.addClass('click-to-toggle');
  }

  if ($('#content').width() <= 600) {
    btn_menu_reveal_letter.parent().remove();
    btn_menu_reveal_word.parent().remove();
  }
}

function resize() {
  if (selectedCell) {
    var tmpSelectedX = selectedCell.gridX;
    var tmpSelectedY = selectedCell.gridY;
  }

  gridContainer.removeAllChildren();
  for (var x = 0; x < cellMap.length; x++) {
    for (var y = 0; y < cellMap[x].length; y++) {
      if (cellMap[x][y]) {
        cellMap[x][y] = null;
      }
    }
  }

  setCanvasDimensions();
  createCrosswordGrid();
  restoreGridState();

  if (selectedCell) {
    if (selectedWord) {
      selectClue(selectedWord, true);
    }
    selectedCell = cellMap[tmpSelectedX][tmpSelectedY];
    selectedCell.setSelected(true);
  }

  stage.update();
}

function showSuccess() {
  key_interceptor.blur();

  // $("#modal3").openModal();
  goPage('result');

  setTimeout(function () {
    $('.check').attr('class', 'check check-complete');
  }, 500);
}

function setupClueLists() {
  var acrossUl = $('#across_list_ul');
  var downUl = $('#down_list_ul');
  acrossUl.empty();
  downUl.empty();

  for (var i = 0; i < acrossClues.length; i++) {
    acrossUl.append(
      '<li id="clue_H_' +
        acrossClues[i].number +
        '" ><a class="clue_text">' +
        '<span>' +
        acrossClues[i].number +
        '.</span>' +
        '<p>' +
        acrossClues[i].clue +
        '</p>' +
        '</a></li>'
    );
  }

  for (var i = 0; i < downClues.length; i++) {
    downUl.append(
      '<li id="clue_V_' +
        downClues[i].number +
        '" ><a class="clue_text">' +
        '<span>' +
        downClues[i].number +
        '.</span>' +
        '<p>' +
        downClues[i].clue +
        '</p>' +
        '</a></li>'
    );
  }

  $('.clue_list li a').hover(
    function () {
      if (!$(this).hasClass('clue_highlight')) {
        $(this).addClass('clue_hover');
      }
    },
    function () {
      $('.clue_list li a').removeClass('clue_hover');
    }
  );

  var parent, ink, d, x, y;

  $('.clue_list li a').click(function (e) {
    playSound('soundClick');
    var selectedId = $(this).parent().attr('id');
    var num = selectedId.substring(selectedId.lastIndexOf('_') + 1);
    num = parseInt(num);

    var dir = extractDirectionFromClueLi($(this));

    selectClue({ number: num, across: dir == 'H' ? true : false });

    parent = $(this).parent();

    if (parent.find('.ink').length == 0)
      parent.prepend("<span class='ink'></span>");

    ink = parent.find('.ink');

    ink.removeClass('animate');

    if (!ink.height() && !ink.width()) {
      d = Math.max(parent.outerWidth(), parent.outerHeight());
      ink.css({ height: d, width: d });
    }

    x = e.pageX - parent.offset().left - ink.width() / 2;
    y = e.pageY - parent.offset().top - ink.height() / 2;

    ink.css({ top: y + 'px', left: x + 'px' }).addClass('animate');

    renderClue(false);

    // $("#key_interceptor").click();
    // $("#key_interceptor").focus();
  });
}

function extractDirectionFromClueLi(li) {
  var id = li.parent().attr('id');
  return id.substr(id.indexOf('_') + 1, 1);
}

function createSolvedCrosswordGrid() {
  var grid = createEmptyCrosswordGrid(puzzle.width, puzzle.height);
  var answer;
  for (var x = 0; x < acrossClues.length; x++) {
    answer = acrossClues[x].answer;
    for (var i = 0; i < answer.length; i++) {
      grid[acrossClues[x].x + i][acrossClues[x].y] = { letter: answer[i] };
    }
  }
  for (var y = 0; y < downClues.length; y++) {
    answer = downClues[y].answer;
    for (var i = 0; i < answer.length; i++) {
      grid[downClues[y].x][downClues[y].y + i] = { letter: answer[i] };
    }
  }
  return grid;
}

function setCrosswordNumbers() {
  var cols = solvedState[0].length;
  var rows = solvedState.length;
  var number = 1;
  for (var y = 0; y < rows; y++) {
    for (var x = 0; x < cols; x++) {
      if (
        (findClosestWord(x, y, 1, 0) && !findClosestWord(x, y, -1, 0)) ||
        (findClosestWord(x, y, 0, 1) && !findClosestWord(x, y, 0, -1))
      ) {
        for (var i = 0; i < acrossClues.length; i++) {
          if (acrossClues[i].x == x && acrossClues[i].y == y) {
            acrossClues[i].number = number;
          }
        }
        for (var i = 0; i < downClues.length; i++) {
          if (downClues[i].x == x && downClues[i].y == y) {
            downClues[i].number = number;
          }
        }
        if (solvedState[x][y]) {
          solvedState[x][y].number = number;
          number++;
        }
      }
    }
  }
}

function cacheCurrentGridState() {
  var cols = solvedState[0].length;
  var rows = solvedState.length;
  currentCache = createEmptyCrosswordGrid(rows, cols);
  for (var x = 0; x < cellMap.length; x++) {
    for (var y = 0; y < cellMap[x].length; y++) {
      if (cellMap[x][y]) {
        currentCache[x][y] = cellMap[x][y];
      }
    }
  }
}

function doInitialSelection() {
  var cols = solvedState[0].length;
  var rows = solvedState.length;
  currentCache = createEmptyCrosswordGrid(rows, cols);
  selectFirstWord();
}

function restoreGridState() {
  for (var x = 0; x < currentCache.length; x++) {
    for (var y = 0; y < currentCache[x].length; y++) {
      if (currentCache[x][y]) {
        cellMap[x][y].letter = currentCache[x][y].letter;
        cellMap[x][y].update();
        cellMap[x][y].showIncorrect(currentCache[x][y].incorrectBg.visible);
        cellMap[x][y].showCorrect(currentCache[x][y].correctBg.visible);
        cellMap[x][y].showHint(currentCache[x][y].hintBg.visible);
      }
    }
  }
}

function initGameData() {
  gameData.tiles = 0;
  gameData.score = 0;
  gameData.incorrect = 0;
  gameData.correct = 0;
  gameData.hintCount = 3;
  gameData.solveCount = 3;
  currentCache = [];
  cellMap = [];
  wordCells = [];
  selectedCells = [];
  grid = [[]];

  if (stage) {
    stage.clear();
    stage.removeAllChildren();
    gridContainer.removeAllChildren();
  }
}

function resetGame() {
  initGameData();
  // clearBoard();
  generate(difficulty.grid);
}

function createCrosswordGrid() {
  stage = new createjs.Stage(canvas);
  stage.snapToPixelEnabled = true;
  stage.enableMouseOver();

  createjs.Touch.enable(stage);
  var columns = solvedState.length;
  var rows = solvedState[0].length;

  gridContainer = new createjs.Container();
  cellMap = createEmptyCrosswordGrid(rows, columns);

  setCanvasDimensions();

  stage.addChild(gridContainer);

  var cells = solvedState;
  for (var x = 0; x < cells.length; x++) {
    for (var y = 0; y < cells[x].length; y++) {
      var cell = cells[x][y];

      if (typeof cell != 'undefined') {
        gameData.tiles++;
        var ct = new Cell(cell.number, x, y);
        setCell(x, y, ct);
      } else {
        var empty = new createjs.Container();
        var shape = new createjs.Shape();
        shape.snapToPixel = true;
        shape.graphics.beginFill(puzzle.settings.empty_cell_color);
        shape.graphics.drawRect(0, 0, cellSize.width, cellSize.height);
        shape.graphics.endFill();
        shape.graphics.setStrokeStyle(1);
        shape.graphics.beginStroke(puzzle.settings.cell_line_color);
        shape.graphics.moveTo(0, 0);
        shape.graphics.lineTo(0, cellSize.width);

        shape.graphics.moveTo(0, 0);
        shape.graphics.lineTo(cellSize.width, 0);
        shape.graphics.endStroke();
        empty.addChild(shape);
        empty.x = Math.floor(x * cellSize.width) - canvasFix;
        empty.y = Math.floor(y * cellSize.height) - canvasFix;
        empty.cursor = 'move';
        gridContainer.addChild(empty);
      }
    }
  }
  stage.update();
}

function checkCorrectCells() {
  var correct = 0;

  for (var i = 0; i < selectedCells.length; i++) {
    selectedCells[i].letter.toUpperCase() ==
    solvedState[selectedCells[i].gridX][
      selectedCells[i].gridY
    ].letter.toUpperCase()
      ? correct++
      : null;
  }

  correct == selectedCells.length ? correctClue() : null;

  //if all letters are correct then update the color to green and disable input box
  if(correct == selectedCells.length){
    for (var i = 0; i < selectedCells.length; i++) {
      selectedCell = selectedCells[i];
      if (!selectedCell.revealed && !selectedCell.answered) {
        gameData.correct++;
        countScore();
      }
      selectedCell.showIncorrect(false);
      selectedCell.showCorrect(true);
    }
  }
}

function highlightWord(clue) {
  for (var i = 0; i < selectedCells.length; i++) {
    selectedCells[i].highlight(false);
  }

  selectedCells = findWordCells.apply(this, [
    clueNumbers[clue.number],
    clue.across,
  ]);

  for (var i = 0; i < selectedCells.length; i++) {
    selectedCells[i].highlight(true);
  }
}

function selectClue(clue, resizing) {
  highlightWord(clue);

  selectedWord = clue;

  if (!resizing) {
    if (selectedCell) {
      selectedCell.setSelected(false);
    }
    selectedCell = selectedCells[0];
    selectedCell.setSelected(true);
    gridContainer.parent.update();
  }

  $('#header_clue').html(getClueByNumber(selectedWord));
}

function findWordCells(cell, horizontal) {
  var x, y;
  if (horizontal) {
    x = 1;
    y = 0;
  } else {
    x = 0;
    y = 1;
  }
  return findCompleteWord
    .apply(this, [cell, -x, -y])
    .concat([cell])
    .concat(findCompleteWord.apply(this, [cell, x, y]));
}

function findCompleteWord(cell, x, y) {
  var wordCells = [];
  var nextCell = findConsecutiveCell(cell, x, y);
  while (nextCell) {
    wordCells.push(nextCell);
    nextCell = findConsecutiveCell(nextCell, x, y);
  }
  if (x < 0 || y < 0) {
    wordCells.reverse();
  }
  return wordCells;
}

function findConsecutiveCell(cell, x, y) {
  var cells = { x: cell.gridX, y: cell.gridY };
  cells.x += x;
  cells.y += y;
  if (
    cells.x >= 0 &&
    cells.y >= 0 &&
    cells.x < cellMap.length &&
    cells.y < cellMap[cells.x].length
  ) {
    return cellMap[cells.x][cells.y];
  }
}

function setCell(x, y, cell) {
  if (cell.number) {
    clueNumbers[cell.number] = cell;
  }

  gridContainer.addChild(cell);
  cell.x = Math.floor(x * cellSize.width) - canvasFix;
  cell.y = Math.floor(y * cellSize.height) - canvasFix;
  cell.scale(cellSize.width, cellSize.height);
  cellMap[x][y] = cell;
}

function correctClue() {
  if (selectedWord) {
    var number = selectedWord.number;
    var dir = selectedWord.across ? 'H' : 'V';
    var a = $('#clue_' + dir + '_' + number + ' a');
    a.addClass('clue_done');
  }
}

function renderClue(rightAway) {
  var number = selectedWord.number;
  var dir = selectedWord.across ? 'H' : 'V';

  if (screen.width >= 600) {
    var timeout = rightAway ? 0 : 510;

    setTimeout(function () {
      $('#across_list li a').removeClass('clue_highlight');
      $('#down_list li a').removeClass('clue_highlight');
      var a = $('#clue_' + dir + '_' + number + ' a');
      a.addClass('clue_highlight');

      var selectedLi = $('#clue_' + dir + '_' + number);

      var top = selectedLi.offset().top;

      var list;
      if (dir == 'H') {
        list = $('#across_list_ul');
      } else {
        list = $('#down_list_ul');
      }

      var firstTop = list.children(':first').offset().top;

      if (prevSelectectedLiId != selectedLi.attr('id')) {
        list.parent().animate(
          {
            scrollTop: top - firstTop,
          },
          'slow'
        );
      }

      prevSelectectedLiId = selectedLi.attr('id');
    }, timeout);
  }
}

function cellFocus(cell) {
  // var left = cell.x / 2 - 10;
  // dragger.css("left", Math.round(-left));
  // var top = 20;
  // dragger.css("top", Math.round(top));
  // console.log(left, top);
}

function cellClicked(newCell) {
  if (supportsTouch()) {
    openMobileKeyboard();
    cellFocus(newCell);
  }

  var oldSelectedCell;

  if (selectedCell != null) {
    selectedCell.setSelected(false);
    oldSelectedCell = selectedCell;
    oldSelectedCell.setSelected(false);
  }

  var horizontalCells = findWordCells.apply(this, [newCell, true]);
  var verticalCells = findWordCells.apply(this, [newCell, false]);
  var newCells;

  if (horizontalCells.length == 1 && verticalCells.length > 1) {
    horizontal = false;
  } else if (verticalCells.length == 1) {
    horizontal = true;
  } else {
    if (newCell == oldSelectedCell) {
      horizontal = !horizontal;
    } else if (horizontalCells.length > 1 && verticalCells.length > 1) {
      var horizontalLocation = horizontalCells.indexOf(newCell);
      var verticalLocation = verticalCells.indexOf(newCell);
      var horizontalSum = 0;
      var verticalSum = 0;

      if (
        horizontalLocation > 0 &&
        horizontalCells[horizontalLocation - 1].letter
      ) {
        horizontalSum++;
      }
      if (
        horizontalLocation < horizontalCells.length - 1 &&
        horizontalCells[horizontalLocation + 1].letter
      ) {
        horizontalSum++;
      }
      if (verticalLocation > 0 && verticalCells[verticalLocation - 1].letter) {
        verticalSum++;
      }
      if (
        verticalLocation < verticalCells.length - 1 &&
        verticalCells[verticalLocation + 1].letter
      ) {
        verticalSum++;
      }
      if (horizontalSum != verticalSum) {
        horizontal = horizontalSum < verticalSum;
      } else {
        horizontal = true;
      }
    }
  }

  if (horizontal) {
    newCells = horizontalCells;
  } else {
    newCells = verticalCells;
  }

  selectedCell = newCell;

  var number = newCells[0].number;
  oldSelectedCell = selectedCell;

  selectedWord = { number: number, across: horizontal };

  selectClue(selectedWord);

  if (screen.width >= 600) {
    renderClue(true);
  }

  selectedCell.setSelected(false);
  selectedCell = oldSelectedCell;
  selectedCell.setSelected(true);
  gridContainer.parent.update();

  bottomInfo.removeClass('centering').find('.center').html('');
  bottomInfo.find('.left').html(`<b>${selectedWord.number}.</b>`);
  bottomInfo.find('.right').html(`${getClueByNumber(selectedWord)}`);
}

function showCluePopup() {
  $('#clue_number').text(
    selectedWord.number +
      ', ' +
      (selectedWord.across ? puzzle.labels.across : puzzle.labels.down)
  );
  var clueText = getClueByNumber(selectedWord);
  $('#clue_text').html(clueText);
  $('#header_clue').html(clueText);

  $('#modal2').openModal();

  $('.lean-overlay').click(function () {
    if ($('#modal2').length) {
      deselectWord();
    }
  });
}

function toggleGameOption() {
  if ($('#buttonOption').hasClass('buttonOptionOn')) {
    $('#buttonOption').removeClass('buttonOptionOn');
    $('#buttonOption').addClass('buttonOptionOff');
    $('#optionList').hide();
  } else {
    $('#buttonOption').removeClass('buttonOptionOff');
    $('#buttonOption').addClass('buttonOptionOn');
    $('#optionList').show();
  }
}

function countScore() {
  // gameData.score = (gameData.correct / gameData.tiles) * 100;
  // $('#scorePoint').text(Math.ceil(gameData.score) + '%');
  if(gameData.correct > 0){
    gameData.score += point;
  }
  $('#scorePoint').text(gameData.score);
}

function getDateText() {
  var days = [
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
  ];
  var months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ];
  var now = new Date();
  var dayName = days[now.getDay()];
  return (
    dayName +
    ', ' +
    now.getDate() +
    ' ' +
    months[now.getMonth()] +
    ' ' +
    now.getFullYear()
  );
}

function setEvents() {
  $('#btn_start').click(function (e) {
    playSound('soundClick');
    e.preventDefault();
    goPage('level');
  });

  $('.modal-trigger').leanModal();

  $('#submit').click(function (e) {
    e.preventDefault();
    $('#modal2').closeModal();
    selectedCell.setSelected(true);

    //setTimeout(function () {
    // triggerInput(selectedCells[0]);
    //}, 1000);
  });

  $('#reveal_letter').click(function (e) {
    $('#modal2').closeModal();

    setTimeout(function () {
      revealCurrentSquare(false);

      if (selectedCell) {
        setTimeout(showCluePopup, 1000);
      }
    }, 500);
  });

  $('#reveal_word').click(function (e) {
    $('#modal2').closeModal();

    setTimeout(function () {
      revealCurrentWord(false);
    }, 500);
  });

  $('#btn_menu_check').click(function () {
    $('.fixed-action-btn').closeFAB();
    checkEntireGrid();
  });

  $('#btn_menu_reveal_letter').click(function (e) {
    revealCurrentSquare();
  });

  $('#btn_menu_reveal_word').click(function (e) {
    revealCurrentWord(true);
  });

  // $("#btn_menu_clear").click(clearBoard);

  $('#btn_menu_reset_zoom').click(function () {
    $('.fixed-action-btn').closeFAB();
    resizeGrid(RESIZE_OPTION.RESET);
  });

  $(window).resize(function () {
    if (!$('#key_interceptor').is(':focus')) {
      resizeGrid(RESIZE_OPTION.UNKNOWN);
    }
  });

  window.addEventListener(
    'orientationchange',
    function () {
      resizeGrid(RESIZE_OPTION.UNKNOWN);

      orientationWarn();
    },
    false
  );

  // key_interceptor.blur(function () {
  //   deselectWord();
  // });
}

function orientationWarn() {
  // if (
  //   window.orientation &&
  //   Math.abs(window.orientation) == 90 &&
  //   $("#content").width() < 600
  // ) {
  //   $("#rotate").show();
  // } else {
  //   $("#rotate").hide();
  // }
}

function checkCurrentCell() {
  if (
    selectedCell.letter.toUpperCase() ==
    solvedState[selectedCell.gridX][selectedCell.gridY].letter.toUpperCase()
  ) {
    if (!selectedCell.revealed && !selectedCell.answered) {
      gameData.correct++;
      countScore();
    }
    selectedCell.showIncorrect(false);
    selectedCell.showCorrect(true);
  } else {
    selectedCell.showIncorrect(true);
  }
}

function getClueByNumber(w) {
  var arr = w.across ? acrossClues : downClues;
  for (var i = 0; i < arr.length; i++) {
    if (arr[i].number == w.number) return arr[i].clue;
  }

  /* for(var i=0;i<clues.length;i++){
        console.log(clues[i])
        if(clues[i].number ==n)
            return clues[i].clue;
    }*/
}

function setLetter(letter, focusNextCell) {
  if (selectedCell) {
    if (!selectedCell.disabled) {
      selectedCell.letter = letter;
      selectedCell.setSelected(false);
      selectedCell.ripple();
      selectedCell.letterText.color = '#000';
      selectedCell.update();

      // Disable true or false checking process on each letter
      // checkCurrentCell(); 
    }

    cacheCurrentGridState();

    var gameFinished = trackCorrectAnswers();

    var number = selectedCells.indexOf(selectedCell);
    selectedCell =
      number < selectedCells.length - 1
        ? selectedCells[number + 1]
        : selectedCell;
    if (selectedCell) selectedCell.setSelected(true);
    checkCorrectCells();
    stage.update();

    if (gameFinished) {
      playSound('soundCorrect');
      setTimeout(() => {
        showSuccess();
      }, 2000);
    }
  }
}

function trackCorrectAnswers() {
  for (var i = 0; i < cellMap.length; i++) {
    for (var j = 0; j < cellMap[i].length; j++) {
      if (
        cellMap[i][j] &&
        cellMap[i][j].letter != solvedState[i][j].letter.toUpperCase()
      ) {
        return false;
      }
    }
  }

  return true;
}

let bottomInfoTimeout;

function openMobileKeyboard() {
  document.activeElement.blur();
  $("input").blur();
  // key_interceptor.focus();
  // key_interceptor.click();
  // key_interceptor.select();
  bottomInfo.show();
  // $('.simple-keyboard').addClass('show');
  // bottomInfo.css('bottom', $('.simple-keyboard').height());
  clearTimeout(bottomInfoTimeout);
  bottomInfoTimeout = setTimeout(() => {
    bottomInfo.hide();
  }, 5000);
}

function keyboardOnPress(e) {
  if (e == '{close}') {
    clearTimeout(bottomInfoTimeout);
    deselectWord();
  } else {
    if (e.match(new RegExp('[' + puzzle.settings.alphabet + ']', 'i'))) {
      setLetter(e.toUpperCase(), true);
    }
  }
}

// key_interceptor.keydown(function (e) {

//   var keyCode = e.keyCode || e.which;
//   if (
//     keyCode == 8 ||
//     keyCode == 13 ||
//     keyCode == 37 ||
//     keyCode == 38 ||
//     keyCode == 39 ||
//     keyCode == 40 ||
//     keyCode == 46
//   ) {
//     keydown(e);
//     key_interceptor.select();
//   }
// });

// key_interceptor.keyup(function (e) {
//     const inputValue = $(this).val().charAt(0);
//     if (
//       inputValue.match(new RegExp("[" + puzzle.settings.alphabet + "]", "i"))
//     ) {
//       setLetter(inputValue.toUpperCase(), true);
//     }
//     key_interceptor.select();
//   });

function keypress(e) {
  console.log(e);
  e = e || window.event;

  var letter = String.fromCharCode(e.charCode);

  if (letter.match(new RegExp('[' + puzzle.settings.alphabet + ']', 'i'))) {
    setLetter(letter.toUpperCase(), true);
  }
}

function keydown(e) {
  console.log(e);
  e = e || window.event;

  if (e.keyCode == '38') {
    directionKeyPressed({ xdir: 0, ydir: -1 });
  } else if (e.keyCode == '40') {
    directionKeyPressed({ xdir: 0, ydir: 1 });
  } else if (e.keyCode == '37') {
    directionKeyPressed({ xdir: -1, ydir: 0 });
  } else if (e.keyCode == '39') {
    directionKeyPressed({ xdir: 1, ydir: 0 });
  } else if (e.keyCode == '46') {
    deleteCurrentLetter();
  } else if (e.keyCode == '8') {
    e.preventDefault();
    backspace();
  } else if (e.keyCode == '13') {
    if (supportsTouch()) deselectWord();
  }
}

function backspace() {
  if (!selectedCell.disabled) {
    selectedCell.setSelected(false);
    selectedCell.letter = '';
    selectedCell.update();
    var curNum = selectedCells.indexOf(selectedCell);
    selectedCell = curNum > 0 ? selectedCells[curNum - 1] : selectedCell;
    selectedCell.setSelected(true);
    gridContainer.parent.update();
    cacheCurrentGridState();
    // triggerInput();
  }
}

function deleteCurrentLetter() {
  if (!selectedCell.disabled) {
    selectedCell.letter = '';
    selectedCell.update();
    gridContainer.parent.update();
    cacheCurrentGridState();
  }
}

function directionKeyPressed(data) {
  var cell = findConsecutiveCell(selectedCell, data.xdir, data.ydir);
  if (cell) {
    cell.onClick();
  }
}

function revealCurrentSquare(focusNextCell) {
  if (!selectedCell.disabled) {
    checkCurrentCell();
    selectedCell.showHint(true);
  }
  setLetter(
    solvedState[selectedCell.gridX][selectedCell.gridY].letter.toUpperCase(),
    focusNextCell
  );
}

function deselectWord() {
  $('.simple-keyboard').removeClass('show');
  bottomInfo.find('.right').html('');
  bottomInfo.find('.left').html('');
  bottomInfo
    .addClass('centering')
    .find('.center')
    .html(textBottomInfoNoSelectedCell);
  bottomInfo.css('bottom', 0);
  setTimeout(() => {
    bottomInfo.show();
  }, 200);

  if (selectedCell) selectedCell.setSelected(false);

  for (var i = 0; i < selectedCells.length; i++) {
    selectedCells[i].highlight(false);
  }

  selectedCell = null;
  selectedCells = [];
  selectedWord = null;

  $('#across_list li a').removeClass('clue_highlight');
  $('#down_list li a').removeClass('clue_highlight');
  if (!$('#key_interceptor').is(":focus")) {
  $("#key_interceptor").blur();
  }

  // $("#key_interceptor").css("left", "-100px");
  // $("#key_interceptor").css("top", "-100px");

  stage.update();
  $('#header_clue').text('');
}

function revealCurrentWord(focusNextCell) {
  selectedCell = selectedCells[0];

  for (var i = 0; i < selectedCells.length; i++) {
    var cell = selectedCells[i];

    if (!cell.disabled) {
      checkCurrentCell();
      cell.showHint(true);
    }

    var letter = solvedState[selectedCell.gridX][
      selectedCell.gridY
    ].letter.toUpperCase();
    setLetter(letter, focusNextCell);
  }

  if (screen.width < 600) {
    deselectWord();
  }

  stage.update();
}

function clearEntireGrid() {
  for (var x = 0; x < cellMap.length; x++) {
    for (var y = 0; y < cellMap[x].length; y++) {
      if (cellMap[x][y]) {
        cellMap[x][y].letter = '';
        cellMap[x][y].showIncorrect(false);
        cellMap[x][y].showCorrect(false);
        cellMap[x][y].showHint(false);
        cellMap[x][y].answered = false;
        cellMap[x][y].disabled = false;
        cellMap[x][y].revealed = false;

        cellMap[x][y].update();
      }
    }
  }
  stage.update();
}

function checkEntireGrid() {
  for (var x = 0; x < cellMap.length; x++) {
    for (var y = 0; y < cellMap[x].length; y++) {
      if (cellMap[x][y])
        if (
          cellMap[x][y] &&
          cellMap[x][y].letter != solvedState[x][y].letter.toUpperCase()
        ) {
          cellMap[x][y].showIncorrect(true);
          cellMap[x][y].showCorrect(false);
        } else {
          cellMap[x][y].showIncorrect(false);
          cellMap[x][y].showCorrect(true);
        }
    }
  }
  stage.update();
}

function createEmptyCrosswordGrid(width, height) {
  var grid = [[]];
  for (var x = 0; x < width; x++) {
    grid[x] = new Array(height);
  }
  return grid;
}

function findClosestWord(x, y, right, down) {
  var nx = x + right;
  var nd = y + down;
  if (
    nx >= 0 &&
    nd >= 0 &&
    nx < solvedState.length &&
    nd < solvedState[nx].length
  ) {
    return solvedState[nx][nd];
  }
}

function gameInit() {
  canvas = document.getElementById('crossword_canvas');
  board = $('#board');
  dragger = $('#dragger');
  solvedState = createSolvedCrosswordGrid();
  // centerBoard();
  initGameData();
  setCrosswordNumbers();
  createCrosswordGrid();
  setupClueLists();
  doInitialSelection();
  setupGameMenu();
  setLabels();
  resetTimer();
  setBottomInfoHeight();

  if (supportsTouch()) {
    bottomInfo.addClass('centering').find('.center').html(textBottomInfo);
  }
  isOnTheGame = true;

  if (supportsTouch()) {
    key_interceptor.attr('autocorrect', 'off');
    key_interceptor.attr('autocomplete', 'off');
    key_interceptor.attr('autocapitalize', 'none');
  } else {
    key_interceptor.hide();
    document.onkeydown = keydown;
    document.onkeypress = keypress;
  }

  orientationWarn();

  if (supportsTouch()) {
    setTimeout(function () {
      new iScroll('board', { zoom: true });
    }, 5000);
  }
}

function resetTimer() {
  timer.stop();
  $('#chrono').html('00:00:00');
  $('.inner-time').removeClass('paused');
  $('#timer-tooltip').html('Pause');
  btnStartTime.hide();
  btnPauseTime.show();
}

function setupConfiguration() {
  console.log('setupConfiguration');
  fetchLevels();
  setEvents();
  checkMute();
  checkUser();
  setTimeout(function () {
    incrementLoader('full');
  }, 500);
}

function setBottomInfoHeight() {
  var borderDragger = 20;
  var restHeightBoard = board.height() - (dragger.height() + borderDragger);
  // restHeightBoard = restHeightBoard < 120 ?
  bottomInfo.css({
    minHeight: 80 + 'px',
    height: 'auto',
    display: 'flex',
  });
  // board.css({bottom: restHeightBoard + 'px'});
}

//__info: API Handler
function fetchLevels() {
  console.log('fetchLevels');
  $.ajax({
    url:
      'https://seniorsdiscountclub.com.au/games/crossword/api/level/read.php',
    type: 'GET',
    dataType: 'json',
    success: function (result) {
      if (result.records) {
        // Init game levels
        gameLevels = result.records;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}

function fetchQuestions(levelId, grid) {
  var postData = {
    level_id: levelId,
  };

  $.ajax({
    url:
      'https://seniorsdiscountclub.com.au/games/crossword/api/question/questions-set.php',
    type: 'POST',
    dataType: 'json',
    data: JSON.stringify(postData),
    success: function (result) {
      gameQuestions = result.records;
      generate(difficulty.grid);
    },
    error: function (err) {
      console.log(err);
    },
  });
}

//__info: Fetching Array to Elements
function displayLevels() {
  pageData.pageTotal = Math.round(gameLevels.length / pageData.maxData);

  pageData.pageTotal == 0 ? (pageData.pageTotal = 1) : pageData.pageTotal;
  pageData.firstData = (pageData.pageIndex - 1) * pageData.maxData;
  pageData.lastData = pageData.pageIndex * pageData.maxData;

  setPage();

  $('#difficultyList').empty();

  gameLevels.forEach((data, index) => {
    if (index >= pageData.firstData && index < pageData.lastData) {
      const difficulty = parseInt(data.difficulty);
      let stars = '';
      let point = undefined;
      if(levelScores){
        let levelScore = levelScores.find(function(x){
          return x.id = data.id;
        });
        if(levelScore){
          point = levelScore.score;
        }
      }
      let score = '';
      if (point) {
        score = '<div class="difficulty-score">' + Math.ceil(point) + '</div>';
      }

      for (var i = 0; i < difficulty; i++) {
        stars = stars + '<img src="images/custom/star-white.svg" />';
      }

      // var levelStars = '<div class="star-wrapper">' + stars + "</div>";
      var thumbClass = cardColorClass[index % cardColorClass.length];
      // data.id <= 2 ? "unlocked" : data.id == 3 ? "finished" : "locked";

      var difficultyThumb =
        '<div data-id="' +
        data.id +
        '" data-grid="' +
        data.width +
        '" data-permalink="' +
        data.permalink +
        '" data-index="' +
        index +
        '" class="difficulty-item ' +
        thumbClass +
        '">' +
        score +
        '<div class="difficulty-info">' +
        '<h5 class="difficulty-level text-main" >' +
        data.name +
        '</h5>' +
        '<h5 class="difficulty-desc ft-base" >' +
        data.description +
        '</h5>' +
        '</div></div>';

      $('#difficultyList').append(difficultyThumb);
    }
  });
}

//__info: Game Page
function goPage(page) {
  console.log('goPage', page);

  $('#hintWrapper').hide();
  $('#confettiWrapper').hide();
  $('#intro').hide();
  $('#game_difficulty').hide();
  $('#back-indifficulty').hide();
  bottomInfo.hide();
  $('.setting-wrapper').hide();
  $('#crossword').css('top', '-99999em');
  $('#result').hide();
  $('confettiWrapper').hide();
  // $("#btn-asc").hide();
  // $("#btn-desc").hide();
  switch (page) {
    case 'intro':
      $('#loader').fadeOut('fast', function () {
        $('#intro').fadeIn('fast');
      });
      break;

    case 'level':
      $('#game_difficulty').show();
      $('.setting-wrapper').show();
      // $(".setting-wrapper").addClass("container-md");
      $('.setting-wrapper').removeClass('in-game');
      $('#dateText').text(getDateText());
      pageData.pageIndex = 1;
      getScore();

      let urlParams = new URLSearchParams(window.location.search);
      let permalinkParam = urlParams.get('l');
      if(permalinkParam){
        let currentURL = window.location.href.replace("?l="+permalinkParam, "");
        window.history.pushState({}, null, currentURL);
      }
      break;

    case 'game':
      if (playerData.onBoarding == 0) {
        showOnboardModal(onboardIndex);
      }
      $('#crossword').css('top', 0);
      $('.setting-wrapper').show();
      bottomInfo.show();
      $('.setting-wrapper').addClass('in-game');
      $('.setting-wrapper').removeClass('container-md');
      // $("#btn-asc").show();
      // $("#btn-desc").show();
      break;

    case 'result':
      countUpResultScore();
      $('#result').show();
      isOnTheGame = false;
      deselectWord();
      showRateStar();
      // clearBoard();

      break;

    default:
      break;
  }
}

//__info: Page Configuration
function setPage() {
  if (pageData.pageIndex > pageData.pageTotal) {
    pageData.pageIndex = 1;
  }

  if (pageData.pageIndex == 1) {
    $('#prevPage').addClass('disabled');
  } else {
    $('#prevPage').removeClass('disabled');
  }

  if (pageData.pageIndex == pageData.pageTotal) {
    $('#nextPage').addClass('disabled');
  } else {
    $('#nextPage').removeClass('disabled');
  }

  $('#pageNumber').text(pageData.pageIndex);
  $('#pageTotal').text(pageData.pageTotal);
}

//__info: Score Count Animation on Result
function countUpResultScore() {
  if (gameData.score > 0) {
    playSound('soundCounter');

    let curScore = levelScores.filter((x) => x.levelId == gameData.levelId);
    if (curScore.length > 0) {
      if (curScore[0].score < gameData.score) {
        setScore();
      }
    } else {
      setScore();
    }

    $({ Counter: 0 }).animate(
      {
        Counter: gameData.score,
      },
      {
        duration: 2000,
        easing: 'swing',
        step: function () {
          // $('#result-point h1').html(Math.ceil(this.Counter) + '%');
          $('#result-point h1').html(Math.ceil(this.Counter));
        },
        complete: function () {
          stopSound();
          setTimeout(() => {
            if (gameData.score > 20) {
              playSound('soundCelebrate');
              $('#confettiWrapper').show();
            } else {
              playSound('soundFail');
            }
          }, 500);
        },
      }
    );
  } else {
    // $('#result-point h1').text(gameData.score + '%')
    $('#result-point h1').text(gameData.score);
    playSound('soundFail');
  }
}

//__info: Show Rate Stars on Result
function showRateStar() {
  var stars = $('.rateStar');
  $('.rateStar').each(function(i, e){
    $(e).css('display', 'none');
  });
  var shine = './images/custom/result-shine-star.svg';

  if (gameData.score == 100) {
    stars.each((i, el) => {
      $(el)
        .delay(500 * (i + 1))
        .queue(() => {
          $(el).attr('src', shine);
          $(el).addClass('show').dequeue();
        });
    });
  } else if (gameData.score >= 80) {
    stars.each((i, el) => {
      if (i < 4) {
        $(el)
          .delay(500 * (i + 1))
          .queue(() => {
            $(el).attr('src', shine);
            $(el).addClass('show').dequeue();
          });
      }
    });
  } else if (gameData.score >= 60) {
    stars.each((i, el) => {
      if (i < 3) {
        $(el)
          .delay(500 * (i + 1))
          .queue(() => {
            $(el).attr('src', shine);
            $(el).addClass('show').dequeue();
          });
      }
    });
  } else if (gameData.score >= 40) {
    stars.each((i, el) => {
      if (i < 2) {
        $(el)
          .delay(500 * (i + 1))
          .queue(() => {
            $(el).attr('src', shine);
            $(el).addClass('show').dequeue();
          });
      }
    });
  } else if (gameData.score >= 20) {
    stars.each((i, el) => {
      if (i < 1) {
        $(el)
          .delay(500 * (i + 1))
          .queue(() => {
            $(el).attr('src', shine);
            $(el).addClass('show').dequeue();
          });
      }
    });
  }
}

//__info: API Handler
function generateUser() {
  $.ajax({
    url: './api/question/generate-user-id.php',
    type: 'GET',
    dataType: 'json',
    success: function (result) {
      console.log(result);
      playerData.userId = result.result.userId;
      checkOnboardSetting();
      setUserCookie();
    },
    error: function (err) {
      console.log(err);
    },
  });
}

function setScore() {
  var postData = {
    score: gameData.score,
    levelId: gameData.levelId,
    userId: playerData.userId,
  };

  // console.log(postData);

  $.ajax({
    url: './api/question/set-game-result.php',
    type: 'POST',
    dataType: 'json',
    data: JSON.stringify(postData),
    success: function (result) {
      // console.log(result);
    },
    error: function (err) {
      console.log(err);
    },
  });
}

function getScore() {
  var param = { userId: playerData.userId };
  // console.log(JSON.stringify(param));
  $.ajax({
    url:
      'https://seniorsdiscountclub.com.au/games/crossword/api/question/get-game-result.php',
    type: 'POST',
    dataType: 'json',
    data: JSON.stringify(param),
    success: function (result) {
      // console.log(result);
      levelScores = [];
      if (result.result?.data != null) {
        levelScores = result?.result?.data?.scores;
      }
      displayLevels();
    },
    error: function (err) {
      console.log(err);
    },
  });
}

function setUserCookie() {
  document.cookie = 'crossword-userId=' + playerData.userId + ';path=/';
}

// share function
const share = (action) => {
  var loc = location.href;
  loc = loc.substring(0, loc.lastIndexOf('/') + 1);
  var gameLoc = loc;
  if (window.location !== window.parent.location) {
    loc = document.referrer + 'fun/games/crossword/';
  }

  let title = shareTitle.replace('[SCORE]', Math.ceil(gameData.score));

  let text = shareMessage.replace('[SCORE]', Math.ceil(gameData.score));
  text = text.replace('[URL]', loc);
  let shareUrl = '';

  switch (action) {
    case 'twitter':
      shareurl =
        'https://twitter.com/intent/tweet?url=' + loc + '&text=' + text;
      break;

    case 'facebook':
      shareurl =
        'https://www.facebook.com/sharer/sharer.php?u=' +
        encodeURIComponent(
          gameLoc +
            'share.php?desc=' +
            text +
            ' ' +
            loc +
            '&title=' +
            title +
            '&url=' +
            loc
        );

    default:
      break;
  }

  window.open(shareurl);
};

function checkIframe() {
  if (window.location !== window.parent.location) {
    iFramed = true;
  }

  console.log('iframe mode: ' + iFramed);
}

function checkOrientation() {
  checkIframe();
  if (!iFramed) {
    if (
      ua.os.family == 'iOS' ||
      /^(?!.*chrome).*safari/i.test(navigator.userAgent)
    ) {
      if (windowWidth > windowHeight) {
        $('#orientationWarning').removeClass('d-none');
        $('#orientationWarning').addClass('d-flex');
      } else {
        $('#orientationWarning').addClass('d-none');
        $('#orientationWarning').removeClass('d-flex');
      }
    } else if (ua.os.family == 'Android') {
      if (screen.orientation.angle == 0) {
        $('#orientationWarning').addClass('d-none');
        $('#orientationWarning').removeClass('d-flex');
      } else {
        $('#orientationWarning').removeClass('d-none');
        $('#orientationWarning').addClass('d-flex');
      }
    } else {
      $('#orientationWarning').addClass('d-none');
      $('#orientationWarning').removeClass('d-flex');
    }
  } else {
    $('#orientationWarning').addClass('d-none');
    $('#orientationWarning').removeClass('d-flex');
  }
}

const showOnboardModal = (index) => {
  pauseTimeTick();
  if ($(window).width() < 576) {
    $('#modal-onboard').addClass('cc-modal-fullscreen');
  } else {
    $('#modal-onboard').removeClass('cc-modal-fullscreen');
  }
  hide(onboardModalContent);
  show(onboardModalContent[index]);
  $('#modal-onboard').modal('show');
  onboardModalIndicator.removeClass('active');
  onboardModalIndicator.eq(index).addClass('active');

  if (index < onboardModalContent.length - 1) {
    skipOnboardModalButton.show();
    nextOnboardModalButton.show();
    startOnboardModalButton.hide();
  } else {
    startOnboardModalButton.show();
    skipOnboardModalButton.hide();
    nextOnboardModalButton.hide();
  }
};

$(document).on('click', '.modal-content-onboard__indicator__item', (e) => {
  let curIndex = $('.modal-content-onboard__indicator__item').index(
    $(e.currentTarget)
  );
  onboardIndex = curIndex;
  showOnboardModal(curIndex);
});

nextOnboardModalButton.click(function () {
  onboardIndex++;
  showOnboardModal(onboardIndex);
});

skipOnboardModalButton.click(function () {
  $('#modal-onboard').modal('hide');
  setOnboardSetting();
  startTimeTick();
});

startOnboardModalButton.click(function () {
  $('#modal-onboard').modal('hide');
  resize();
  setOnboardSetting();
  startTimeTick();
});

const hide = (element) => {
  $(element).addClass('d-none');
  $(element).removeClass('d-flex');
  $(element).removeClass('fadeIn');
};

const show = (element) => {
  $(element).addClass('d-flex');
  $(element).addClass('fadeIn');
  $(element).removeClass('d-none');
};

function startTimeTick() {
  timer.start();
  $('.inner-time').removeClass('paused');
  $('#timer-tooltip').html('Pause');
  btnStartTime.hide();
  btnPauseTime.show();
}

function pauseTimeTick() {
  timer.pause();
  btnStartTime.show();
  btnPauseTime.hide();
  $('.inner-time').addClass('paused');
  $('#timer-tooltip').html('Resume');
}
