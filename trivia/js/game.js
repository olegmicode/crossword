////////////////////////////////////////////////////////////
// GAME
////////////////////////////////////////////////////////////

/*!
 *
 * GAME SETTING CUSTOMIZATION START
 *
 */

var stageW = 1200; //game width
var stageH = 650; //game height
var portraitW = 380; //game portrait width
var portraitH = 600; //game portrait height
var fitToScreen = true; //fit to browser screen
var maintainAspectRatio = true; //maintain aspect ratio
var viewportMode = {
  enable: true,
  viewport: 'portrait',
  text: 'Rotate your device <br/>to portrait',
}; //device viewport mode, portrait or landscape

var loaderText = 'Loading question...'; //game loading text

var categoryPage = true; //show/hide category select page
var gameModePage = false; //enable/disable game mode selection
var isRandomMode = false;
var categoryAllOption = false; //add ALL category select option
var categoryAllText = 'All'; //text for all category select option

var questionTotalDisplay = '[NUMBER]/[TOTAL]'; //current question and total question display
var totalQuestions = 0; //set 0 for all questions, set more than 0 to limit total questions

var enableRandomQuestion = true; //enable question in random sequence
var enableRandomAnswer = true; //enable answer in random sequence
var enableRevealAnswer = true; //enable reveal answer
var enableExplanation = true; //enable show explanation

var enableTimer = true; //true or false to enable timer
var timerMode = 'default'; //default or countdown mode
var timerAllSession = true; //true for whole session, false for one single questions
var coundownTimer = 25000; //countdown timer

//question property
var questionFontSize = 50;
var questionLineHeight = 58;
var questionColor = '#fff';
var questionTop = 25;
var questionLeft = 5;
var questionWidth = 90;
var questionHeight = 30;
var questionAlign = 'center';

//video property
var videoTop = 15;
var videoLeft = 30;
var videoWidth = 40;
var videoHeight = 41;
var videoAutoplay = true;
var videoControls = true;

//answers property
var answerFontSize = 40;
var answerLineHeight = 40;
var answerColor = '#fff';
var answerAlign = 'center';
var answerWidth = 30;
var answerHeight = 14;
var answerOffsetTop = -15;

var answerListsEnable = true; //enable answer list style
var answerLists = [
  '<span style="margin-right: 8px">A. </span>',
  '<span style="margin-right: 8px">B. </span>',
  '<span style="margin-right: 8px">C. </span>',
  '<span style="margin-right: 8px">D. </span>',
  '<span style="margin-right: 8px">e. </span>',
  '<span style="margin-right: 8px">F. </span>',
  '<span style="margin-right: 8px">G. </span>',
  '<span style="margin-right: 8px">H. </span>',
]; //answer list style format, maximum 8
var answerAnimationEnable = true; //enable answer animation

var answerButtonBgEnable = true; //toggle answer button background
var answerButtonBgRoundNumber = 10; //answer button background round corner number
var answerButtonBgColour = '#a2cd4a'; //answer button background colour
var answerButtonBgShadowColour = '#6fad4e'; //answer button background shadow colour
var answeredButtonBgColour = '#f89a31'; //answered button background colour
var answeredButtonBgShadowColour = '#dc4832'; //answered button background shadow colour
var wrongButtonBgColour = '#989898'; //answered button background colour
var wrongButtonBgShadowColour = '#666'; //answered button background shadow colour

var audioQuestionDelay = 300; //timer delay to play question audio
var audioAnswerDelay = 100; //timer delay to play answer audio

//inputs property
var inputFontSize = 40;
var inputLineHeight = 40;
var inputColor = '#333';
var inputBackground = '#fff';
var inputAlign = 'center';
var inputWidth = 20;
var inputHeight = 12;
var inputTop = 50;
var inputLeft = 40;
var inputOffsetTop = -15;

//drag property
var dragRevertSpeed = 0.5; //revert speed
var dragListEnable = false; //enable drag answer list style
var dragDroppedAnswerAgain = true; //enable drag answer again after dropped
var dragRandomAnswer = true; //enable drag answer display in random sequence
var dropBorder = '#fff';
var dropStroke = '1px';
var dropBackground = '';

var dropLabelFontSize = 50;
var dropLabelLineHeight = 50;
var dropLabelColor = '#fff';
var dropLabelAlign = 'right';
var dropLabelOffsetTop = -15;

//group drop property
var groupBorder = '#fff';
var groupStroke = '1px';
var groupBackground = '';
var groupDropMax = 4;
var groupDropWidth = 40;
var groupDropHeight = 30;
var groupDropOffLeft = 1;
var groupDropOffTop = 3;

var groupFontSize = 50;
var groupLineHeight = 50;
var groupColor = '#fff';
var groupAlign = 'right';
var groupOffsetTop = -15;

//correct or wrong property
var correctDisplayText = "That's Correct!";
var wrongDisplayText = 'Incorrect!';
var quesResultFontSize = 50;
var quesResultLineHeight = 50;
var quesResultColor = '#fff';
var quesResultTop = 30;
var quesResultLeft = 5;
var quesResultWidth = 90;
var quesResultHeight = 30;
var quesResultAlign = 'center';

//explanation property
var explanationFontSize = 35;
var explanationLineHeight = 35;
var explanationColor = '#ccc';
var explanationTop = 45;
var explanationLeft = 5;
var explanationWidth = 90;
var explanationHeight = 10;
var explanationAlign = 'center';

//result
var scoreMode = 'score'; //display result by 'score' or 'timer'
var scoreDisplayText = '[NUMBER]'; //score result display text
var timerDisplayText = 'Best time : [NUMBER]!'; //timer result display text

//Social share, [SCORE] will replace with game score
var shareEnable = true; //toggle share
var shareTitle = 'My Highscore on Daily Trivia Game is [SCORE]pts'; //social share score title
var shareMessage =
  'I just played this awesome trivia quiz at [URL] with score [SCORE]! You should try it too! '; //social share score message

// onboard
var onboardIndex = 0;
const onboardModalContent = $('.modal-content-onboard__content');
const onboardModalIndicator = $('.modal-content-onboard__indicator__item');
const skipOnboardModalButton = $('#btnOnboardModalSkip');
const nextOnboardModalButton = $('#btnOnboardModalNext');
const startOnboardModalButton = $('#btnOnboardModalStart');

//Confetti Settings

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

/*!
 *
 * GAME SETTING CUSTOMIZATION END
 *
 */
$.editor = { enable: false };
var playerData = { score: 0, answered: false, answerType: '', answer_arr: [] };
var gameData = {
  page: '',
  questionNum: 0,
  sequenceNum: 0,
  categoryNum: 0,
  category_arr: [],
  categoryThumb_arr: [],
  sequence_arr: [],
  targetArray: null,
  targetAnswerSequence: null,
  mode: 'landscape',
  oldMode: 'landscape',
  build: false,
  initScreenH: $(window).height(),
  iframed: (window.location !== window.parent.location ? true : false)
};
var storeData = { status: false, timerDate: 0 };

var quesLandscape_arr = [];
var quesPortrait_arr = [];
var quesLandscapeSequence_arr = [];
var quesPortraitSequence_arr = [];
var categoryData = { page: 1, total: 0, thumb: 16, max: 3 };

var audioLandscape_arr = [];
var audioPortrait_arr = [];
var audioData = { audioNum: 0, audioInterval: null };

var timeData = { enable: false, startDate: null, nowDate: null, timer: 0 };

var questionStatistic = {
  incorrect_count: 0,
  correct_count: 0,
  reported: 0,
  question_id: 0,
};

//__info: additional data
var ccValueCorrect = 0;
var ccValueWrong = 0;
var isPaused = false;
var isReportEnabled = true;
var isEditEnabled = false;

var defaultStyle = {
  question: {
    portrait: {
      fontSize: 24,
      top: 15,
      lineHeight: 30,
    },
    landscape: {
      fontSize: '',
      top: '',
      lineHeight: '',
    },
  },
  answer: {
    portrait: [
      {
        fontSize: 18,
        width: 90,
        height: 10,
        top: 50,
      },
      {
        fontSize: 18,
        width: 90,
        height: 10,
        top: 62,
      },
      {
        fontSize: 18,
        width: 90,
        height: 10,
        top: 74,
      },
      {
        fontSize: 18,
        width: 90,
        height: 10,
        top: 86,
      },
    ],
    landscape: [
      {
        fontSize: 24,
        top: 60,
      },
      {
        fontSize: 24,
        top: 80,
      },
      {
        fontSize: 24,
        top: 60,
        left: 50,
      },
      {
        fontSize: 24,
        top: 80,
        left: 50,
      },
    ],
  },
};

// user score
// var historyData;
var userScore = {
  userId: null,
  scores: {},
};
var today = new Date();
var ymd =
  today.getFullYear() +
  String(today.getMonth() + 1).padStart(2, '0') +
  String(today.getDate()).padStart(2, '0');

/*!
 *
 * SWIPE CATEGORY LIST FUNCTION
 *
 */
var categoryHolderElement = $('#categoryHolder');
var startX = 0;
var endX = 0;

categoryHolderElement.on('touchstart', function (e) {
  // e.preventDefault();
  var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
  startX = touch.pageX;
});

categoryHolderElement.on('touchend', function (e) {
  // e.preventDefault();
  var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
  endX = touch.pageX;

  var dir = 'right';
  var diff = endX - startX;
  Math.sign(diff) == 1 ? (dir = 'right') : (dir = 'left');
  var dist = Math.abs(diff);

  // if (diff > 100) console.log("swipe");

  if (dir == 'right' && dist > 50) {
    playSound('soundClick');
    toggleCategory(false);
  } else if (dir == 'left' && dist > 50) {
    playSound('soundClick');
    toggleCategory(true);
  }
});

/*!
 *
 * GAME BUTTONS - This is the function that runs to setup button event
 *
 */
function buildGameButton() {
  $('#buttonStart').click(function () {
    playSound('soundClick');
    if (gameModePage) {
      goPage('gameMode');
    } else {
      if (categoryPage) {
        goPage('category');
      } else {
        goPage('game');
      }
    }
  });

  $('#buttonNextCat').click(function () {
    playSound('soundClick');
    toggleCategory(true);
  });

  $('#buttonPrevCat').click(function () {
    playSound('soundClick');
    toggleCategory(false);
  });

  $('#buttonOk').click(function () {
    playSound('soundClick');
    toggleConfirm(false);
    goPage('main');
  });

  $('#buttonCancel').click(function () {
    playSound('soundClick');
    toggleConfirm(false);
  });

  $('#buttonNextQues').click(function () {
    playSound('soundClick');
    prepareNextQuestion();
  });

  $('#buttonPreviewQues').click(function () {
    playSound('soundClick');
    previewQuestion();
  });

  $('#buttonReplay').click(function () {
    resetProgress();
    playSound('soundClick');

    TweenMax.to($(this), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });

    TweenMax.to($(this), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        var dim = './img/result-dim-star.svg';

        $('.rateStar').each((i, el) => {
          $(el).attr('src', dim);
          $(el).removeClass('show');
        });
        if (gameModePage) {
          goPage('gameMode');
        } else {
          if (categoryPage) {
            $('#buttonOption').removeClass('d-none');
            $('#overlay-answer').addClass('d-none');
            goPage('category');
          } else {
            goPage('game');
          }
        }
      },
    });
  });

  $('#buttonFacebook').click(function () {
    playSound('soundClick');

    TweenMax.to($(this), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });

    TweenMax.to($(this), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        share('facebook');
      },
    });
  });

  $('#buttonTwitter').click(function () {
    playSound('soundClick');

    TweenMax.to($(this), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });

    TweenMax.to($(this), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        share('twitter');
      },
    });
  });

  $('#buttonWhatsapp').click(function () {
    share('whatsapp');
  });

  $('#shareFunfactFacebook').click(function () {
    playSound('soundClick');

    TweenMax.to($(this), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });

    TweenMax.to($(this), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        shareFunfact('facebook');
      },
    });
  });

  $('#shareFunfactTwitter').click(function () {
    playSound('soundClick');

    TweenMax.to($(this), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });

    TweenMax.to($(this), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        shareFunfact('twitter');
      },
    });
  });

  $('#buttonOption').click(function () {
    playSound('soundClick');
    toggleGameOption();
  });

  $('#buttonSound').click(function () {
    playSound('soundClick');
    toggleGameMute();
  });

  $('#buttonHelp').click(function () {
    playSound('soundClick');
    onboardIndex = 0;
    showOnboardModal(onboardIndex);
    pauseTimeTick();
  });

  $('#buttonFullscreen').click(function () {
    playSound('soundClick');
    toggleFullScreen();
  });

  $('#buttonExit').click(function () {
    playSound('soundClick');
    toggleGameOption();
    toggleConfirm(true);
  });

  $(window).focus(function () {
    //resizeGameDetail();
  });
}

/*!
 *
 * GAME STYLE - This is the function that runs to build game style
 *
 */
function buildGameStyle() {
  // $(".preloadText").html(loaderText);

  $('.questionResultText').html(correctDisplayText);
  $('.questionResultText').css('font-size', quesResultFontSize + 'px');
  $('.questionResultText').css('line-height', quesResultLineHeight + 'px');

  $('.questionResultText').attr('data-fontSize', quesResultFontSize);
  $('.questionResultText').attr('data-lineHeight', quesResultLineHeight);
  $('.questionResultText').css('color', quesResultColor);

  $('.questionResultText').css('top', quesResultTop + '%');
  $('.questionResultText').css('left', quesResultLeft + '%');

  $('.questionResultText').css('width', quesResultWidth + '%');
  $('.questionResultText').css('height', quesResultHeight + '%');
  $('.questionResultText').css('text-align', quesResultAlign);

  $('#today').text(getDateText());

  if (isMute) {
    $('#buttonSound').removeClass('buttonSoundOn');
    $('#buttonSound').addClass('buttonSoundOff');
    $('#soundTooltip').text('Unmute');
  } else {
    $('#buttonSound').removeClass('buttonSoundOff');
    $('#buttonSound').addClass('buttonSoundOn');
    $('#soundTooltip').text('Mute');
  }

  if (isRandomMode) {
    $('#navbar-main > .cc-back').show();
  } else {
    $('#navbar-main > .cc-back').hide();
  }

  if (!enableTimer) {
    $('.gameTimer').hide();
  }

  toggleConfirm(false);
}

/*!
 *
 * DISPLAY PAGES - This is the function that runs to display pages
 *
 */
function goPage(page) {
  $('#confettiWrapper').hide();
  closeGameOption();
  gameData.page = page;
  $('#logoHolder').hide();
  $('#gameModeHolder').hide();
  $('#categoryHolder').hide();
  $('#gameHolder').hide();
  $('#resultHolder').hide();
  $('#buttonExit').show();

  var targetContainer = '';
  switch (page) {
    case 'main':
      targetContainer = $('#logoHolder');
      $('#buttonExit').hide();
      break;

    case 'gameMode':
      isRandomMode = true;
      targetContainer = $('#gameModeHolder');
      $('#buttonOption').removeClass('d-none');
      $('.cc-back').addClass('d-none');
      break;

    case 'category':
      getGameScore();
      targetContainer = $('#categoryHolder');
      $('#buttonOption').removeClass('d-none');
      $('.score-navbar').removeClass('d-none');
      $('.cc-back').removeClass('d-none');
      $('.cc-btn-reset-wrapper').removeClass('d-none');
      break;

    case 'game':
      if (gameData.onBoarding == 0) {
        showOnboardModal(onboardIndex);
      }

      targetContainer = $('#gameHolder');
      startGame();
      $('#navbar-main').addClass('d-none');
      $('#navbar-score').removeClass('d-none');
      $('.score-navbar').removeClass('d-none');
      $('#scoreText').html(setScoreNavbar());
      $('.cc-btn-reset-wrapper').removeClass('d-none');
      $('.cc-back').addClass('d-none');
      $('#btn-hint').removeClass('d-none');
      break;

    case 'result':
      targetContainer = $('#resultHolder');
      if (!shareEnable) {
        $('#shareOption').hide();
      }
      // playSound("soundResult");
      stopGame();
      postGameScore();
      if (scoreMode == 'score') {
        countUpResultScore();
        // $("#resultScore").html(
        //   scoreDisplayText.replace("[NUMBER]", playerData.score)
        // );
        // saveGame(playerData.score, gameData.category_arr[gameData.categoryNum]);
      } else if (scoreMode == 'timer') {
        playerData.timer = timeData.timer;
        $('#resultScore').html(
          timerDisplayText.replace(
            '[NUMBER]',
            millisecondsToTime(playerData.timer)
          )
        );
        // saveGame(playerData.timer, gameData.category_arr[gameData.categoryNum]);
      }

      showRateStar();

      goScorePage('');
      $('#modal-funfact').modal('hide');
      $('#buttonOption').addClass('d-none');
      $('#btn-hint').addClass('d-none');

      $('#navbar-main').removeClass('d-none');
      $('#navbar-score').addClass('d-none');

      toggleSaveButton(true);
      break;
  }

  targetContainer.show();

  TweenMax.to(targetContainer, 0, { opacity: 0, overwrite: true });
  TweenMax.to(targetContainer, 1, { opacity: 1, overwrite: true });
  resizeGameDetail();
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
  setOnboardSetting();
  startTimeTick();
});

$(document).on('click', '.modal-content-onboard__indicator__item', (e) => {
  let curIndex = $('.modal-content-onboard__indicator__item').index(
    $(e.currentTarget)
  );
  onboardIndex = curIndex;
  showOnboardModal(curIndex);
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

function countUpResultScore() {
  // if (ccValueWrong > 0 || ccValueCorrect > 0) {
  playSound('soundCounter');

  // Animate correct / incorrect count
  $({ Counter: 0 }).animate(
    {
      Counter: ccValueCorrect,
    },
    {
      duration: 2000,
      easing: 'swing',
      step: function () {
        $('#value-correct').html(Math.ceil(this.Counter));
      },
    }
  );

  $({ Counter: 0 }).animate(
    {
      Counter: 10 - ccValueCorrect,
    },
    {
      duration: 2000,
      easing: 'swing',
      step: function () {
        $('#value-wrong').html(Math.ceil(this.Counter));
      },
    }
  );

  // Animate score bar
  var answerTotal = 10;
  var correctPercentage = (ccValueCorrect / answerTotal) * 100;
  $('#percentage-wrong').css('flex-basis', 100 + '%');

  $({ Counter: 0 }).animate(
    {
      Counter: correctPercentage,
    },
    {
      duration: 2000,
      easing: 'swing',
      step: function () {
        $('#percentage-correct').css(
          'flex-basis',
          Math.ceil(this.Counter) + '%'
        );
        $('#percentage-wrong').css(
          'flex-basis',
          100 - Math.ceil(this.Counter) + '%'
        );
      },
    }
  );

  // Animate score points

  $('#resultScore').text(playerData.score);

  $({ Counter: 0 }).animate(
    {
      Counter: playerData.score,
    },
    {
      duration: 2000,
      easing: 'swing',
      step: function () {
        $('#resultScore').text(Math.ceil(this.Counter));
      },
      complete: function () {
        stopSound();
        setTimeout(() => {
          if (playerData.score > 0) {
            playSound('soundCelebrate');
            $('#confettiWrapper').show();
          } else {
            playSound('soundFail');
          }
        }, 500);
      },
    }
  );
  // } else {
  //   $("#resultScore").text(playerData.score);
  //   $("#percentage-correct").css("flex-basis", 0);
  //   $("#percentage-wrong").css("flex-basis", 100 + "%");
  //   $("#value-correct").html(0);
  //   $("#value-wrong").html(10);
  //   playSound("soundFail");
  // }
}

function showRateStar() {
  var stars = $('.rateStar');
  var shine = './img/result-shine-star.svg';

  if (ccValueCorrect == 10) {
    stars.each((i, el) => {
      $(el)
        .delay(500 * (i + 1))
        .queue(() => {
          $(el).attr('src', shine);
          $(el).addClass('show').dequeue();
        });
    });
  } else if (ccValueCorrect >= 8) {
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
  } else if (ccValueCorrect >= 6) {
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
  } else if (ccValueCorrect >= 4) {
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
  } else if (ccValueCorrect >= 2) {
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

/*!
 *
 * BUILD CATEGORY - This is the function that runs to build category page
 *
 */

// category card color class randomize
const cardColorClass = [
  'card-red',
  'card-navy',
  'card-blue',
  'card-purple',
  'card-tomato',
  'card-green',
  'card-lime',
];

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

function buildCategory() {
  if (gameData.iframed) {
    $('#categoryList').addClass('iframed');
  } else {
    $('#categoryList').removeClass('iframed');
  }

  resetCategory();

  $('#categoryList').empty();

  var randomScore = '<div></div>';
  var randomCatScore = userScore.scores?.find((x) => x.categoryId == 10);

  if (randomCatScore) {
    randomScore =
      '<div class="categoryLatestScore d-flex align-items-center">' +
      '<img src="img/star.svg" class="mr-2"></img>' +
      randomCatScore.score +
      ' pts</div>';
  }

  var randomCatHTML =
    '<li data-catname="random" class="categoryThumb buttonClick card-yellow">' +
    randomScore +
    '<div class="categoryTitle fontCategory mt-auto" data-fontSize="30" data-lineHeight="30">RANDOM</div></li>';

  $('#categoryList').append(randomCatHTML);

  for (var c = 0; c < categoryData.thumb; c++) {
    var colorClass;

    if (c < cardColorClass.length) {
      colorClass = cardColorClass[c];
    } else {
      colorClass = cardColorClass[c % cardColorClass.length];
    }

    var scoreElement = '<div></div>';

    var catScore = userScore.scores?.find(
      (x) => x.categoryId == gameData.categoryThumb_arr[c]?.id
    );

    if (catScore) {
      scoreElement =
        '<div class="categoryLatestScore mb-auto d-flex align-items-center">' +
        '<img src="img/star.svg" class="mr-2"></img>' +
        catScore.score +
        ' pts</div>';
    }

    var categoryHTML =
      '<li data-catname="' +
      gameData.category_arr[c] +
      '" class="categoryThumb buttonClick ' +
      colorClass +
      '">' +
      scoreElement +
      '<div class="categoryTitle fontCategory mt-auto" data-fontSize="30" data-lineHeight="30">' +
      gameData.category_arr[c] +
      '</div>' +
      '</li>';

    if (gameData.category_arr[c] != undefined) {
      $('#categoryList').append(categoryHTML);
    }
  }

  $('.categoryThumb').click(function (e) {
    console.log('click');
    e.preventDefault();
    playSound('soundClick');
    gameData.initScreenH = $(window).height();

    if ($(this).attr('data-catname') == 'random') {
      isRandomMode = true;
      fetchRandomQuestion(function () {
        goPage('game');
      });
    } else {
      isRandomMode = false;
      gameData.categoryNum = $(this).index() - 1;
      fetchQuestionByCategory($(e.currentTarget).data('catname'), function () {
        goPage('game');
      });
    }
  });

  displayCategory();
}

function resetCategory() {
  if (gameData.mode == 'portrait') {
    if(gameData.iframed) {
      if (window.innerWidth > 1023) {
        // iPad Pro
        categoryData.max = 16;
      } else if (window.innerWidth > 737) {
        // iPad
        categoryData.max = 16;
      } else {
        // Mobile
        categoryData.max = 4;
      }
    } else {
      if (window.innerWidth > 1023) {
        // iPad Pro
        categoryData.max = 12;
      } else if (window.innerWidth > 767) {
        // iPad
        categoryData.max = 9;
      } else {
        // Mobile
        categoryData.max = 4;
      }
    }
  } else {
    // desktop

    if (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1) {
      categoryData.max = 9;
    } else {
      categoryData.max = 24;
    }
  }

  // if (categoryData.total == 1) {
  //   $('.categoryList-pagination').hide();
  // } else {
  //   $('.categoryList-pagination').show();
  // }

  categoryData.total = categoryData.thumb / categoryData.max;
  if (String(categoryData.total).indexOf('.') > -1) {
    categoryData.total = Math.floor(categoryData.total) + 1;
  }

  displayCategory();
}

function toggleCategory(con) {
  if (con) {
    categoryData.page++;
    categoryData.page =
      categoryData.page > categoryData.total
        ? categoryData.total
        : categoryData.page;
  } else {
    categoryData.page--;
    categoryData.page = categoryData.page < 1 ? 1 : categoryData.page;
  }
  displayCategory();
}

function displayCategory() {
  var startPageNum = (categoryData.page - 1) * categoryData.max;
  var endPageNum = startPageNum + categoryData.max;

  //__override
  $('#categoryList li').hide().removeClass('d-flex');
  $('#categoryList li').each(function (index, element) {
    if (index >= startPageNum && index < endPageNum) {
      $(this).show().addClass('d-flex flex-column');
    }
  });

  $('#pageNumber').text(categoryData.page);
  $('#pageTotal').text(categoryData.total);

  if (categoryData.page > categoryData.total) {
    categoryData.page = 1;
  }

  if (categoryData.page == 1) {
    $('#buttonPrevCat').addClass('cs-disabled');
  } else {
    $('#buttonPrevCat').removeClass('cs-disabled');
  }

  if (categoryData.page == categoryData.total) {
    $('#buttonNextCat').addClass('cs-disabled');
  } else {
    $('#buttonNextCat').removeClass('cs-disabled');
  }
}

/*!
 *
 * FILTER CATEGORY - This is the function that runs to filter category
 *
 */
function filterCategoryQuestion() {
  gameData.sequence_arr = [];
  for (n = 0; n < gameData.targetArray.length; n++) {
    gameData.sequence_arr.push(n);
  }
  if ($.editor.enable) {
    return;
  }

  //do nothing if category page is off
  if (!categoryPage) {
    return;
  }

  //do nothing if category all is selected
  if (
    categoryAllOption &&
    gameData.category_arr[gameData.categoryNum] == categoryAllText
  ) {
    return;
  }

  if (!isRandomMode) {
    //filter the category
    gameData.sequence_arr = [];
    for (n = 0; n < gameData.targetArray.length; n++) {
      if (
        gameData.category_arr[gameData.categoryNum] ==
        gameData.targetArray[n].category
      ) {
        gameData.sequence_arr.push(n);
      }
    }
  }
}

/*!
 *
 * START GAME - This is the function that runs to start play game
 *
 */
function startGame() {
  gameData.questionNum = 0;
  gameData.sequenceNum = 0;
  playerData.score = 0;

  timeData.accumulate = 0;
  timeData.countdown = coundownTimer;
  updateTimerDisplay(true);

  $('#gameStatus .gameQuestionStatus').html('');
  toggleResult(true);

  filterCategoryQuestion();
  if (enableRandomQuestion && !$.editor.enable) {
    shuffle(gameData.sequence_arr);
  }
  loadQuestion();

  //__info: adding start time icon
  $('#time-trigger-start').addClass('d-none');
  $('#time-trigger-pause').removeClass('d-none');
}

/*!
 *
 * STOP GAME - This is the function that runs to stop play game
 *
 */
function stopGame() {
  TweenMax.killAll(false, true, false);
  $('.questionHolder').remove();
}

function saveGame(score, type) {
  $.ajax({
    type: 'POST',
    url: 'saveResults.php',
    data: { score: score },
    success: function (result) {
      console.log(result);
    },
  });
}

function resetStatistic() {
  questionStatistic.question_id = 0;
  questionStatistic.incorrect_count = 0;
  questionStatistic.correct_count = 0;
  questionStatistic.reported = 0;
}

function sendStatistic() {
  $.ajax({
    url: './api/question/set-statistic.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(questionStatistic),
  })
    .done((e) => {
      // console.log(e, questionStatistic);
    })
    .error((err) => {
      console.log(err);
    });
}

/*!
 *
 * LOAD QUESTION - This is the function that runs to load new question
 *
 */
function loadQuestion() {
  resetStatistic();

  $('#questionHolder').show();
  $('#questionResultHolder').hide();
  $('#questionCounter').text(gameData.questionNum + 1);

  storeData.timerDate = 0;
  storeData.status = false;

  toggleQuestionLoader(true);
  resetQuestion();
  fileFest = [];
  gameData.build = false;
  playerData.answered = false;
  gameData.sequenceNum = gameData.sequence_arr[gameData.questionNum];

  questionStatistic.question_id =
    gameData.targetArray[gameData.sequenceNum].question_id;

  var randomAnswerLayout = false;
  if (enableRandomAnswer && !$.editor.enable) {
    randomAnswerLayout = true;
  }

  if (
    gameData.targetArray[gameData.sequenceNum].drag == 'true' &&
    dragRandomAnswer &&
    !$.editor.enable
  ) {
    randomAnswerLayout = true;
  }

  //landscape & portrait
  quesLandscapeSequence_arr = [];
  quesPortraitSequence_arr = [];

  audioLandscape_arr = [];
  audioPortrait_arr = [];

  for (var t = 0; t < 2; t++) {
    var loopTargetArray = t == 0 ? quesLandscape_arr : quesPortrait_arr;
    var loopTargetSeqArray =
      t == 0 ? quesLandscapeSequence_arr : quesPortraitSequence_arr;
    var loopAudioArray = t == 0 ? audioLandscape_arr : audioPortrait_arr;
    var thisMode = t == 0 ? 'landscape' : 'portrait';

    var submitButton = -1;
    for (
      var n = 0;
      n < loopTargetArray[gameData.sequenceNum].answer.length;
      n++
    ) {
      if (
        loopTargetArray[gameData.sequenceNum].answer[n].submit == 'false' ||
        loopTargetArray[gameData.sequenceNum].answer[n].submit == undefined
      ) {
        loopTargetSeqArray.push(n);
      } else {
        submitButton = n;
      }
    }

    if (loopTargetArray[gameData.sequenceNum].background != '') {
      fileFest.push({
        src: loopTargetArray[gameData.sequenceNum].background,
        id: thisMode + 'backgroundImage',
        type: createjs.LoadQueue.IMAGE,
      });
    }

    if (loopTargetArray[gameData.sequenceNum].type == 'image') {
      fileFest.push({
        src: loopTargetArray[gameData.sequenceNum].question,
        id: thisMode + 'questionImage',
        type: createjs.LoadQueue.IMAGE,
      });
    }

    var questionAudio = loopTargetArray[gameData.sequenceNum].audio;
    questionAudio = questionAudio == undefined ? '' : questionAudio;

    if (questionAudio != '') {
      loopAudioArray.push({
        type: 'question',
        id: thisMode + 'questionAudio',
        list: 0,
      });
      fileFest.push({
        src: loopTargetArray[gameData.sequenceNum].audio,
        id: thisMode + 'questionAudio',
      });
    }

    if (randomAnswerLayout) {
      shuffle(loopTargetSeqArray);
    }
    if (submitButton != -1) {
      loopTargetSeqArray.push(submitButton);
    }

    for (
      var n = 0;
      n < loopTargetArray[gameData.sequenceNum].groups.length;
      n++
    ) {
      var groupAudio = loopTargetArray[gameData.sequenceNum].groups[n].audio;
      groupAudio = groupAudio == undefined ? '' : groupAudio;

      if (groupAudio != '') {
        loopAudioArray.push({
          type: 'group',
          id: thisMode + 'groupAudio' + n,
          list: n,
        });
        fileFest.push({
          src: loopTargetArray[gameData.sequenceNum].groups[n].audio,
          id: thisMode + 'groupAudio' + n,
        });
      }
    }

    for (
      var n = 0;
      n < loopTargetArray[gameData.sequenceNum].answer.length;
      n++
    ) {
      if (loopTargetArray[gameData.sequenceNum].answer[n].type == 'image') {
        fileFest.push({
          src: loopTargetArray[gameData.sequenceNum].answer[n].text,
          id: thisMode + 'answerImage' + n,
          type: createjs.LoadQueue.IMAGE,
        });
      }

      if (
        loopTargetArray[gameData.sequenceNum].answer[n].dropLabelType == 'image'
      ) {
        fileFest.push({
          src: loopTargetArray[gameData.sequenceNum].answer[n].dropLabelText,
          id: thisMode + 'answerLabelImage' + n,
          type: createjs.LoadQueue.IMAGE,
        });
      }

      var answerNum = loopTargetSeqArray[n];
      var answerAudio =
        loopTargetArray[gameData.sequenceNum].answer[answerNum].audio;
      answerAudio = answerAudio == undefined ? '' : answerAudio;

      if (
        answerAudio != '' &&
        checkBoolean(
          loopTargetArray[gameData.sequenceNum].answer[answerNum].dragEnable
        )
      ) {
        loopAudioArray.push({
          type: 'answer',
          id: thisMode + 'answerAudio' + answerNum,
          list: n,
        });
        fileFest.push({
          src: loopTargetArray[gameData.sequenceNum].answer[answerNum].audio,
          id: thisMode + 'answerAudio' + answerNum,
        });
      }
    }

    for (
      var n = 0;
      n < loopTargetArray[gameData.sequenceNum].input.length;
      n++
    ) {
      if (loopTargetArray[gameData.sequenceNum].input[n].type == 'image') {
        fileFest.push({
          src: loopTargetArray[gameData.sequenceNum].input[n].text,
          id: thisMode + 'inputImage' + n,
          type: createjs.LoadQueue.IMAGE,
        });
      }
    }

    if (loopTargetArray[gameData.sequenceNum].explanationType == 'image') {
      fileFest.push({
        src: loopTargetArray[gameData.sequenceNum].explanation,
        id: thisMode + 'explanationImage',
        type: createjs.LoadQueue.IMAGE,
      });
    }

    var explanationAudio =
      loopTargetArray[gameData.sequenceNum].explanationAudio;
    explanationAudio = explanationAudio == undefined ? '' : explanationAudio;

    if (explanationAudio != '') {
      loopAudioArray.push({
        type: 'explanation',
        id: thisMode + 'explanationAudio',
        list: 0,
      });
      fileFest.push({
        src: loopTargetArray[gameData.sequenceNum].explanationAudio,
        id: thisMode + 'explanationAudio',
      });
    }
  }

  if (fileFest.length > 0) {
    loadQuestionAssets();
  } else {
    buildQuestion();
  }
}

/*!
 *
 * BUILD QUESTION - This is the function that runs to build question
 *
 */
function buildQuestion() {
  $('#overlay-answer').addClass('d-none');
  toggleQuestionLoader(false);
  stopAudio();
  toggleAudioInterval(false);
  audioData.audioNum = 0;
  resetQuestion();

  gameData.targetArray = [];
  if (gameData.mode == 'landscape') {
    gameData.targetArray = quesLandscape_arr;
    gameData.targetAnswerSequence = quesLandscapeSequence_arr;
    gameData.targetAudio = audioLandscape_arr;
  } else {
    gameData.targetArray = quesPortrait_arr;
    gameData.targetAnswerSequence = quesPortraitSequence_arr;
    gameData.targetAudio = audioPortrait_arr;
  }

  //total display
  var curQuestionText = questionTotalDisplay.replace(
    '[NUMBER]',
    gameData.questionNum + 1
  );
  if (totalQuestions != 0) {
    var totalMax =
      totalQuestions > gameData.sequence_arr.length
        ? gameData.sequence_arr.length
        : totalQuestions;
    curQuestionText = curQuestionText.replace('[TOTAL]', totalMax);
  } else {
    curQuestionText = curQuestionText.replace(
      '[TOTAL]',
      gameData.sequence_arr.length
    );
  }
  // $('#gameStatus .gameQuestionStatus').html(curQuestionText);

  buildBackground();

  //questions
  var value = getArrayValue('question');
  // var newCounter =
  //   '<div class="gameTotal"><div class="gameQuestionStatus"></div></div>';
  // var report =
  //   '<div data-index-question="' +
  //   gameData.sequenceNum +
  //   '" class="cc-game-report hoverpointer"><div class="cc-game-report-inner"><img src="./img/flag-report.svg"> report</div></div>';
  //__info: Change report to edit for temporary
  var edit =
    '<div data-question-id="' +
    gameData.targetArray[gameData.sequenceNum].question_id +
    '" data-index-question="' +
    gameData.sequenceNum +
    '" class="cc-game-edit hoverpointer"><div class="cc-game-edit-inner">edit</div></div>';

  // if (!isReportEnabled) {
  //   report = "";
  // }

  if (!isEditEnabled) {
    edit = '';
  }

  if (value.type == 'image') {
    var questionHTML =
      '<div class="question fontQuestion fitImg" style="top:' +
      value.top +
      '%; left:' +
      value.left +
      '%; width:' +
      value.width +
      '%; ">' +
      // newCounter +
      // report +
      edit +
      '<div class="cc-question-wrapper"><img src="' +
      gameData.targetArray[gameData.sequenceNum].question +
      '" /></div></div>';
  } else {
    var questionHTML =
      '<div class="question fontQuestion resizeFont" data-fontSize="' +
      value.fontSize +
      '" data-lineHeight="' +
      value.lineHeight +
      '" style="font-size:' +
      value.fontSize +
      'px; line-height:' +
      value.lineHeight +
      'px; color:' +
      value.color +
      ';  text-align:' +
      value.align +
      '; top:' +
      value.top +
      '%; left:' +
      value.left +
      '%; width:' +
      value.width +
      '%; height:' +
      value.height +
      '%; ">' +
      // newCounter +
      // report +
      edit +
      '<div data-fontSize="' +
      40 +
      '" data-lineHeight="' +
      50 +
      '" class="cc-question-wrapper"><div class="">' +
      gameData.targetArray[gameData.sequenceNum].question +
      '</div></div></div>';
  }
  $('#questionHolder').append(questionHTML);
  if ($(window).width() <= 576) {
    setTimeout(function () {
      $('#overlay-answer').removeClass('d-none');
    }, 1000);
  } else {
    $('#overlay-answer').addClass('d-none');
  }

  $('.gameQuestionStatus').html(curQuestionText);
  setQuestionWidth();

  buildGroups();
  buildAnswers();
  buildInputs();
  buildVideo();
  buildExplanation();
  gameData.build = true;
  resizeGameDetail();

  if (playerData.answered) {
    presetAnswered();
    return;
  }

  if (gameData.targetAudio.length == 0) {
    initAnimateAnswers();
  } else if (
    gameData.targetAudio.length == 1 &&
    gameData.targetAudio[0].type == 'question'
  ) {
    initAnimateAnswers();
  }

  if ($.editor.enable) {
    if (edit.con == 'explanation') {
      $('#questionResultHolder').show();
      $('#questionHolder').hide();
      playerData.answered = true;
      playAudioLoop('explanation');
      $('#explanationHolder').show();
    }
    setBorderFocus();
  }

  if ($.editor.enable && !edit.replay) {
    return;
  }

  updateTimerDisplay(false);
  $('#questionHolder').css('opacity', 0);
  TweenMax.to($('#questionHolder'), 0.5, {
    alpha: 1,
    overwrite: true,
    onComplete: function () {
      if (gameData.targetAudio.length > 0) {
        playAudioLoop();
      }
      if (!isPaused) {
        toggleGameTimer(true);
      }
      storeData.status = true;

      var questionHeight = $('.cc-question-wrapper').find('div').height();
      var questionWrapperHeight = $('.cc-question-wrapper').height();

      if (questionHeight > questionWrapperHeight) {
        $('.cc-question-wrapper').find('div').addClass('overflowing');
      }
    },
  });
}

function resetQuestion() {
  $('#questionHolder').empty();
  $('#explanationHolder').empty();
}

function buildBackground() {
  //questions
  var value = getArrayValue('background');
  if (value.image != '') {
    var bgHolderHTML = '<div id="bgHolder"></div>';
    $('#questionHolder').append(bgHolderHTML);

    var backgroundHTML =
      '<div class="background fitImg" style="top:' +
      value.top +
      '%; left:' +
      value.left +
      '%; width:' +
      value.width +
      '%; "><img src="' +
      gameData.targetArray[gameData.sequenceNum].background +
      '" /></div>';
    $('#bgHolder').append(backgroundHTML);
  }
}

/*!
 *
 * GET ARRAY VALUE - This is the function that runs to get array value
 *
 */
function getArrayValue(type, answerNum, n) {
  var value = {
    type: '',
    submit: '',
    text: '',
    top: '',
    left: '',
    width: '',
    height: '',
    fontSize: '',
    lineHeight: '',
    color: '',
    background: '',
    align: '',
    correctAnswer: '',
  };

  if (type == 'background') {
    value.image = gameData.targetArray[gameData.sequenceNum].background;
    value.top = !checkValue(
      gameData.targetArray[gameData.sequenceNum].backgroundTop
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].backgroundTop;
    value.left = !checkValue(
      gameData.targetArray[gameData.sequenceNum].backgroundLeft
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].backgroundLeft;
    value.width = !checkValue(
      gameData.targetArray[gameData.sequenceNum].backgroundWidth
    )
      ? 100
      : gameData.targetArray[gameData.sequenceNum].backgroundWidth;
    value.height = !checkValue(
      gameData.targetArray[gameData.sequenceNum].backgroundHeight
    )
      ? 100
      : gameData.targetArray[gameData.sequenceNum].backgroundHeight;
  } else if (type == 'question') {
    value.type = gameData.targetArray[gameData.sequenceNum].type;
    value.top = !checkValue(gameData.targetArray[gameData.sequenceNum].top)
      ? questionTop
      : gameData.targetArray[gameData.sequenceNum].top;
    value.left = !checkValue(gameData.targetArray[gameData.sequenceNum].left)
      ? questionLeft
      : gameData.targetArray[gameData.sequenceNum].left;
    value.width = !checkValue(gameData.targetArray[gameData.sequenceNum].width)
      ? questionWidth
      : gameData.targetArray[gameData.sequenceNum].width;
    value.height = !checkValue(
      gameData.targetArray[gameData.sequenceNum].height
    )
      ? questionHeight
      : gameData.targetArray[gameData.sequenceNum].height;
    value.fontSize = !checkValue(
      gameData.targetArray[gameData.sequenceNum].fontSize
    )
      ? questionFontSize
      : gameData.targetArray[gameData.sequenceNum].fontSize;
    value.lineHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].lineHeight
    )
      ? questionLineHeight
      : gameData.targetArray[gameData.sequenceNum].lineHeight;
    value.color = !checkValue(gameData.targetArray[gameData.sequenceNum].color)
      ? questionColor
      : gameData.targetArray[gameData.sequenceNum].color;
    value.align = !checkValue(gameData.targetArray[gameData.sequenceNum].align)
      ? questionAlign
      : gameData.targetArray[gameData.sequenceNum].align;
  } else if (type == 'video') {
    value.embed =
      gameData.targetArray[gameData.sequenceNum].videos[answerNum].embed;
    value.top = !checkValue(
      gameData.targetArray[gameData.sequenceNum].videos[answerNum].top
    )
      ? videoTop
      : gameData.targetArray[gameData.sequenceNum].videos[answerNum].top;
    value.left = !checkValue(
      gameData.targetArray[gameData.sequenceNum].videos[answerNum].left
    )
      ? videoLeft
      : gameData.targetArray[gameData.sequenceNum].videos[answerNum].left;
    value.width = !checkValue(
      gameData.targetArray[gameData.sequenceNum].videos[answerNum].width
    )
      ? videoWidth
      : gameData.targetArray[gameData.sequenceNum].videos[answerNum].width;
    value.height = !checkValue(
      gameData.targetArray[gameData.sequenceNum].videos[answerNum].height
    )
      ? videoHeight
      : gameData.targetArray[gameData.sequenceNum].videos[answerNum].height;
    value.autoplay = !checkValue(
      gameData.targetArray[gameData.sequenceNum].videos[answerNum].autoplay
    )
      ? videoAutoplay
      : gameData.targetArray[gameData.sequenceNum].videos[answerNum].autoplay;
    value.controls = !checkValue(
      gameData.targetArray[gameData.sequenceNum].videos[answerNum].controls
    )
      ? videoControls
      : gameData.targetArray[gameData.sequenceNum].videos[answerNum].controls;
  } else if (type == 'answer') {
    value.submit =
      gameData.targetArray[gameData.sequenceNum].answer[answerNum].submit;
    value.type =
      gameData.targetArray[gameData.sequenceNum].answer[answerNum].type;
    value.text =
      gameData.targetArray[gameData.sequenceNum].answer[answerNum].text;
    value.top = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].top
    )
      ? questionTop
      : gameData.targetArray[gameData.sequenceNum].answer[n].top;
    value.left = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].left
    )
      ? questionLeft
      : gameData.targetArray[gameData.sequenceNum].answer[n].left;
    value.width = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].width
    )
      ? answerWidth
      : gameData.targetArray[gameData.sequenceNum].answer[n].width;
    value.height = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].height
    )
      ? answerHeight
      : gameData.targetArray[gameData.sequenceNum].answer[n].height;
    value.fontSize = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].fontSize
    )
      ? answerFontSize
      : gameData.targetArray[gameData.sequenceNum].answer[n].fontSize;
    value.lineHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].lineHeight
    )
      ? answerLineHeight
      : gameData.targetArray[gameData.sequenceNum].answer[n].lineHeight;
    value.color = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].color
    )
      ? answerColor
      : gameData.targetArray[gameData.sequenceNum].answer[n].color;
    value.align = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].align
    )
      ? answerAlign
      : gameData.targetArray[gameData.sequenceNum].answer[n].align;
    value.offsetTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].offsetTop
    )
      ? answerOffsetTop
      : gameData.targetArray[gameData.sequenceNum].answer[n].offsetTop;

    value.dragEnable =
      gameData.targetArray[gameData.sequenceNum].answer[answerNum].dragEnable ==
      'false'
        ? false
        : true;
    value.dropEnable =
      gameData.targetArray[gameData.sequenceNum].answer[answerNum].dropEnable ==
      'false'
        ? false
        : true;
    value.dropLabelType =
      gameData.targetArray[gameData.sequenceNum].answer[
        answerNum
      ].dropLabelType;
    value.dropLabelText = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[answerNum].dropLabelText
    )
      ? ''
      : gameData.targetArray[gameData.sequenceNum].answer[answerNum]
          .dropLabelText;
    value.dropLabelTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelTop
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelTop;
    value.dropLabelLeft = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelLeft
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelLeft;
    value.dropLabelWidth = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelWidth
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelWidth;
    value.dropLabelHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelHeight
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelHeight;
    value.dropLabelFontSize = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelFontSize
    )
      ? dropLabelFontSize
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelFontSize;
    value.dropLabelLineHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelLineHeight
    )
      ? dropLabelLineHeight
      : gameData.targetArray[gameData.sequenceNum].answer[n]
          .dropLabelLineHeight;
    value.dropLabelColor = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelColor
    )
      ? dropLabelColor
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelColor;
    value.dropLabelAlign = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelAlign
    )
      ? dropLabelAlign
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelAlign;
    value.dropLabelOffsetTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelOffsetTop
    )
      ? dropLabelOffsetTop
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLabelOffsetTop;

    value.dropLeft = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropLeft
    )
      ? questionLeft
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropLeft;
    value.dropTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropTop
    )
      ? questionTop
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropTop;
    value.dropWidth = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropWidth
    )
      ? answerWidth
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropWidth;
    value.dropHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropHeight
    )
      ? answerHeight
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropHeight;

    value.dropOffLeft = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropOffLeft
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropOffLeft;
    value.dropOffTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].answer[n].dropOffTop
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].answer[n].dropOffTop;
  } else if (type == 'input') {
    value.type =
      gameData.targetArray[gameData.sequenceNum].input[answerNum].type;
    value.submit =
      gameData.targetArray[gameData.sequenceNum].input[answerNum].submit;
    value.correctAnswer =
      gameData.targetArray[gameData.sequenceNum].input[answerNum].correctAnswer;
    value.top = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].top
    )
      ? inputTop
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].top;
    value.left = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].left
    )
      ? inputLeft
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].left;
    value.width = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].width
    )
      ? inputWidth
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].width;
    value.height = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].height
    )
      ? inputHeight
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].height;
    value.fontSize = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].fontSize
    )
      ? inputFontSize
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].fontSize;
    value.lineHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].lineHeight
    )
      ? inputLineHeight
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].lineHeight;
    value.color = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].color
    )
      ? inputColor
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].color;
    value.background = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].background
    )
      ? inputBackground
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].background;
    value.align = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].align
    )
      ? inputAlign
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].align;
    value.offsetTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].input[answerNum].offsetTop
    )
      ? inputOffsetTop
      : gameData.targetArray[gameData.sequenceNum].input[answerNum].offsetTop;
  } else if (type == 'explanation') {
    value.type = gameData.targetArray[gameData.sequenceNum].explanationType;
    value.top = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationTop
    )
      ? explanationTop
      : gameData.targetArray[gameData.sequenceNum].explanationTop;
    value.left = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationLeft
    )
      ? explanationLeft
      : gameData.targetArray[gameData.sequenceNum].explanationLeft;
    value.width = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationWidth
    )
      ? explanationWidth
      : gameData.targetArray[gameData.sequenceNum].explanationWidth;
    value.height = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationHeight
    )
      ? explanationHeight
      : gameData.targetArray[gameData.sequenceNum].explanationHeight;
    value.fontSize = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationFontSize
    )
      ? explanationFontSize
      : gameData.targetArray[gameData.sequenceNum].explanationFontSize;
    value.lineHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationLineHeight
    )
      ? explanationLineHeight
      : gameData.targetArray[gameData.sequenceNum].explanationLineHeight;
    value.color = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationColor
    )
      ? explanationColor
      : gameData.targetArray[gameData.sequenceNum].explanationColor;
    value.align = !checkValue(
      gameData.targetArray[gameData.sequenceNum].explanationAlign
    )
      ? explanationAlign
      : gameData.targetArray[gameData.sequenceNum].explanationAlign;
  } else if (type == 'group') {
    value.correctAnswer =
      gameData.targetArray[gameData.sequenceNum].groups[n].correctAnswer;
    value.dropMax = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].dropMax
    )
      ? groupDropMax
      : gameData.targetArray[gameData.sequenceNum].groups[n].dropMax;
    value.dropTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].dropTop
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].groups[n].dropTop;
    value.dropLeft = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].dropLeft
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].groups[n].dropLeft;
    value.dropWidth = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].dropWidth
    )
      ? groupDropWidth
      : gameData.targetArray[gameData.sequenceNum].groups[n].dropWidth;
    value.dropHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].dropHeight
    )
      ? groupDropHeight
      : gameData.targetArray[gameData.sequenceNum].groups[n].dropHeight;

    value.dropOffLeft = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].dropOffLeft
    )
      ? groupDropOffLeft
      : gameData.targetArray[gameData.sequenceNum].groups[n].dropOffLeft;
    value.dropOffTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].dropOffTop
    )
      ? groupDropOffTop
      : gameData.targetArray[gameData.sequenceNum].groups[n].dropOffTop;

    value.type = gameData.targetArray[gameData.sequenceNum].groups[n].type;
    value.text = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].text
    )
      ? ''
      : gameData.targetArray[gameData.sequenceNum].groups[n].text;
    value.top = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].top
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].groups[n].top;
    value.left = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].left
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].groups[n].left;
    value.width = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].width
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].groups[n].width;
    value.height = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].height
    )
      ? 0
      : gameData.targetArray[gameData.sequenceNum].groups[n].height;
    value.fontSize = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].fontSize
    )
      ? groupFontSize
      : gameData.targetArray[gameData.sequenceNum].groups[n].fontSize;
    value.lineHeight = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].lineHeight
    )
      ? groupLineHeight
      : gameData.targetArray[gameData.sequenceNum].groups[n].lineHeight;
    value.color = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].color
    )
      ? groupColor
      : gameData.targetArray[gameData.sequenceNum].groups[n].color;
    value.align = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].align
    )
      ? groupAlign
      : gameData.targetArray[gameData.sequenceNum].groups[n].align;
    value.offsetTop = !checkValue(
      gameData.targetArray[gameData.sequenceNum].groups[n].offsetTop
    )
      ? groupOffsetTop
      : gameData.targetArray[gameData.sequenceNum].groups[n].offsetTop;
  }

  return value;
}

function checkValue(value) {
  if (value == undefined || value == '') {
    return false;
  } else {
    return true;
  }
}

/*!
 *
 * AUDIO - This is the function that runs to play question and answer audio
 *
 */
function playAudioLoop(con) {
  if (gameData.targetAudio.length <= 0) {
    return;
  }

  toggleAudioInterval(false);
  if (con == 'explanation') {
    audioData.audioNum = gameData.targetAudio.length - 1;
    if (
      gameData.targetAudio[audioData.audioNum].type == 'explanation' &&
      playerData.answered
    ) {
      TweenMax.to(audioData, 1, {
        overwrite: true,
        onComplete: function () {
          playAudio(gameData.targetAudio[audioData.audioNum].id);
        },
      });
    }
  } else {
    if (gameData.targetAudio[audioData.audioNum].type == 'question') {
      playAudio(gameData.targetAudio[audioData.audioNum].id);
    } else if (gameData.targetAudio[audioData.audioNum].type == 'group') {
      playAudio(gameData.targetAudio[audioData.audioNum].id);
    } else if (gameData.targetAudio[audioData.audioNum].type == 'answer') {
      playAudio(gameData.targetAudio[audioData.audioNum].id);
      animateAnswer(gameData.targetAudio[audioData.audioNum].list);
    }
  }
}

function playAudioComplete() {
  audioData.audioNum++;
  if (audioData.audioNum < gameData.targetAudio.length) {
    toggleAudioInterval(true);
  }
}

function toggleAudioInterval(con) {
  if (con) {
    var audioTimer = audioAnswerDelay;
    if (
      gameData.targetAudio.length > 0 &&
      gameData.targetAudio[audioData.audioNum].type == 'question'
    ) {
      audioTimer = audioQuestionDelay;
    }
    audioData.audioInterval = setInterval(function () {
      playAudioLoop();
    }, audioTimer);
  } else {
    TweenMax.killTweensOf(audioData);
    clearInterval(audioData.audioInterval);
    audioData.audioInterval = null;
  }
}

/*!
 *
 * BUILD VIDEO - This is the function that runs to build video
 *
 */
function buildVideo() {
  if (gameData.targetArray[gameData.sequenceNum].videos[0] == undefined) {
    return;
  }
  if (gameData.targetArray[gameData.sequenceNum].videos[0].types.length <= 0) {
    return;
  }

  var value = getArrayValue('video', 0);
  var videoProperty = '';
  var videoWrapperHTML =
    '<div id="videoHolder" style="top:' +
    value.top +
    '%; left:' +
    value.left +
    '%; width:' +
    value.width +
    '%; height:' +
    value.height +
    '%;">';

  if (value.embed == 'youtube') {
    for (
      var n = 0;
      n < gameData.targetArray[gameData.sequenceNum].videos[0].types.length;
      n++
    ) {
      videoWrapperHTML +=
        gameData.targetArray[gameData.sequenceNum].videos[0].types[n].src;
    }
    videoWrapperHTML += '</div>';
  } else {
    if (value.autoplay == 'true' || value.autoplay == true) {
      videoProperty += ' autoplay';
    }
    if (value.controls == 'true' || value.controls == true) {
      videoProperty += ' controls';
    }
    videoWrapperHTML +=
      '<video width="100%" height="100%"' + videoProperty + '>';
    for (
      var n = 0;
      n < gameData.targetArray[gameData.sequenceNum].videos[0].types.length;
      n++
    ) {
      videoWrapperHTML +=
        '<source src="' +
        gameData.targetArray[gameData.sequenceNum].videos[0].types[n].src +
        '" type="' +
        gameData.targetArray[gameData.sequenceNum].videos[0].types[n].type +
        '">';
    }
    videoWrapperHTML += 'Your browser does not support the video tag.';
    videoWrapperHTML += '</video>';
    videoWrapperHTML += '</div>';
  }

  $('#questionHolder').append(videoWrapperHTML);
  if (value.embed == 'youtube') {
    $('#videoHolder iframe').attr(
      'data-src',
      $('#videoHolder iframe').attr('src')
    );
  }
}

/*!
 *
 * BUILD GROUP - This is the function that runs to build groups
 *
 */
function buildGroups() {
  if (gameData.targetArray[gameData.sequenceNum].groups.length <= 0) {
    return;
  }

  var groupHolderHTML = '<div id="groupHolder"></div>';
  $('#questionHolder').append(groupHolderHTML);

  for (
    n = 0;
    n < gameData.targetArray[gameData.sequenceNum].groups.length;
    n++
  ) {
    var value = getArrayValue('group', n, n);

    //label
    if (value.type == 'image') {
      var groupLabelWrapperHTML =
        "<div id='groupLabel" +
        n +
        "' class='groupDropLabel fitImg' style='width:" +
        value.width +
        '%; height:' +
        value.height +
        '%; top:' +
        value.top +
        '%; left:' +
        value.left +
        "%;'><img src='" +
        value.text +
        "' /></div>";
    } else {
      var groupLabelWrapperHTML =
        "<div id='groupLabel" +
        n +
        "' class='groupDropLabel fontAnswer resizeFont' data-fontSize='" +
        value.fontSize +
        "' data-lineHeight='" +
        value.lineHeight +
        "' style='width:" +
        value.width +
        '%; height:' +
        value.height +
        '%; top:' +
        value.top +
        '%; left:' +
        value.left +
        '%; font-size:' +
        value.fontSize +
        'px; line-height:' +
        value.lineHeight +
        'px; color:' +
        value.color +
        '; text-align:' +
        value.align +
        ";'>" +
        value.text +
        '</div>';
    }

    $('#groupHolder').append(groupLabelWrapperHTML);

    //drop group
    var dropLeft = Number(value.dropLeft) + Number(value.dropOffLeft);
    var dropTop = Number(value.dropTop) + Number(value.dropOffTop);

    var groupDropWrapperHTML =
      "<div id='groupDrop" +
      n +
      "' class='groupDrop' style='width:" +
      value.dropWidth +
      '%; height:' +
      value.dropHeight +
      '%; top:' +
      value.dropTop +
      '%; left:' +
      value.dropLeft +
      '%; border:' +
      groupBorder +
      ' solid ' +
      groupStroke +
      '; background:' +
      groupBackground +
      ";' data-left='" +
      dropLeft +
      "%' data-top='" +
      dropTop +
      "%' data-offleft='" +
      value.dropOffLeft +
      "%' data-offtop='" +
      value.dropOffTop +
      "%' data-width='" +
      value.dropWidth +
      "%' data-height='" +
      value.dropHeight +
      "%' data-max='" +
      value.dropMax +
      "' data-answer='" +
      value.correctAnswer +
      "' data-id='" +
      n +
      "'></div>";

    $('#groupHolder').append(groupDropWrapperHTML);
  }
}

/*!
 *
 * BUILD ANSWERS - This is the function that runs to build answers
 *
 */
function buildAnswers() {
  if (gameData.targetArray[gameData.sequenceNum].answer.length <= 0) {
    return;
  }

  var answerHolderHTML = '<div id="answerHolder"></div>';
  $('#questionHolder').append(answerHolderHTML);
  playerData.answerType = 'select';
  if (gameData.targetArray[gameData.sequenceNum].drag == 'true') {
    playerData.answerType = 'drag';
  }
  var answerArray = gameData.targetArray[gameData.sequenceNum].correctAnswer
    .split(',')
    .map(function (item) {
      return parseInt(item, 10);
    });

  playerData.correctAnswer = [];
  for (
    n = 0;
    n < gameData.targetArray[gameData.sequenceNum].answer.length;
    n++
  ) {
    var answerNum = gameData.targetAnswerSequence[n];
    if (
      answerArray.indexOf(answerNum + 1) != -1 &&
      playerData.answerType != 'drag'
    ) {
      playerData.correctAnswer.push(n + 1);
    }

    var value = getArrayValue('answer', answerNum, n);
    var dragLabel = getArrayValue('answer', n, n);
    if (value.type == 'image') {
      var answerHTML =
        '<div id="answer' +
        n +
        '" class="answer fitImg buttonClick" style="top:' +
        value.top +
        '%; left:' +
        value.left +
        '%; width:' +
        value.width +
        '%; "><img src="' +
        value.text +
        '" /></div>';
      $('#answerHolder').append(answerHTML);
    } else {
      var curAnswerList = '';
      if (answerListsEnable) {
        curAnswerList = answerLists[n];
      }
      if (value.submit == 'true') {
        curAnswerList = '';
      }

      if (playerData.answerType == 'drag' && !dragListEnable) {
        curAnswerList = '';
      }

      //__info: add more spacing between answer button
      if (gameData.mode != 'portrait') {
        var windowHeight = $(window).height();

        if (windowHeight > 850) {
          if (n == 0 || n == 2) {
            value.top = parseInt(value.top) + 1.3;
          } else {
            value.top = parseInt(value.top) - 9;
          }
        } else if (windowHeight > 650) {
          if (n == 0 || n == 2) {
            value.top = parseInt(value.top) + 2;
          } else {
            value.top = parseInt(value.top) - 2;
          }
        } else if (windowHeight <= 560) {
          if (n == 0 || n == 2) {
            value.top = parseInt(value.top) + 2.2;
          } else {
            value.top = parseInt(value.top) - 2.4;
          }
        } else {
          if (n == 0 || n == 2) {
            value.top = parseInt(value.top) + 1.3;
          } else {
            value.top = parseInt(value.top) - 6;
          }
        }

        //for landscape screen with short height (650px)
      } else {
        if (parseInt($(window).width()) <= 576) {
          value.top =
            n == 0
              ? parseInt(value.top) + 7
              : parseInt(value.top) + (n + 7) * 1;
        } else {
          value.top = parseInt(value.top) + 2;
        }
      }

      var answerWrapperHTML =
        "<div id='answer" +
        n +
        "' class='answer resizeBorder' data-border='" +
        answerButtonBgRoundNumber +
        "' style='border-radius: " +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px; -moz-border-radius: ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px; -webkit-border-radius: ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px; width:' +
        value.width +
        '%; height:' +
        value.height +
        '%; top:' +
        value.top +
        '%; left:' +
        value.left +
        "%;'></div>";

      $('#answerHolder').append(answerWrapperHTML);

      if (answerButtonBgEnable) {
        var backgroundShadowHTML =
          "<div class='shadow resizeBorder' data-border='" +
          answerButtonBgRoundNumber +
          "' style='border-radius: " +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -moz-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -webkit-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; background:' +
          answerButtonBgShadowColour +
          '; width:100%; height:100%; position:absolute; top:' +
          value.offsetTop +
          "%; left:0;'></div>";
        $('#answer' + n).append(backgroundShadowHTML);

        var backgroundHTML =
          "<div class='background resizeBorder' data-border='" +
          answerButtonBgRoundNumber +
          "' style='border-radius: " +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -moz-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -webkit-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; background:' +
          answerButtonBgColour +
          '; width:100%; height:85%; position:absolute; top:' +
          value.offsetTop +
          "%; left:0;'></div>";
        $('#answer' + n).append(backgroundHTML);
      }

      //XXX
      var answerHTML =
        '<div id="text' +
        n +
        '" class="fontAnswer resizeFont" data-fontSize="' +
        value.fontSize +
        '" data-lineHeight="' +
        value.lineHeight +
        '" style="position:relative; font-size:' +
        value.fontSize +
        'px; line-height:' +
        value.lineHeight +
        'px; color:' +
        value.color +
        ';  text-align:' +
        value.align +
        ';"><span>' +
        curAnswerList +
        value.text.charAt(0).toUpperCase() +
        value.text.slice(1) +
        '<span></div>';
      // var answerHTML = '<div id="text'+n+'" class="fontAnswer resizeFont" data-fontSize="'+value.fontSize+'" data-lineHeight="'+value.lineHeight+'" style="position:relative; font-size:'+value.fontSize+'px; line-height:'+value.lineHeight+'px; color:'+value.color+';  text-align:'+value.align+';"><span>'+'<span style="margin-right: 8px">B. </span>they pulled me out of the sack fdsacdsfd fdsacdf fdsacdsaf'+'<span></div>';
      $('#answer' + n).append(answerHTML);

      var clickHTML =
        "<div class='buttonClick resizeBorder' data-border='" +
        answerButtonBgRoundNumber +
        "' style='position:absolute; border-radius: " +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px; -moz-border-radius: ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px; -webkit-border-radius: ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px ' +
        answerButtonBgRoundNumber +
        'px; width:100%; height:100%; position:absolute; top:' +
        value.offsetTop +
        "%;'></div>";
      $('#answer' + n).append(clickHTML);
    }

    $('#answer' + n).attr('data-id', n);
    $('#answer' + n).attr('data-type', value.type);
    $('#answer' + n).attr('data-submit', value.submit);

    if (playerData.answerType == 'drag' && value.submit != 'true') {
      if (gameData.targetArray[gameData.sequenceNum].groups.length > 0) {
        $('#answer' + n).attr('data-ori-id', answerNum + 1);
        $('#answer' + n).attr('data-top', value.top + '%');
        $('#answer' + n).attr('data-left', value.left + '%');
        $('#answer' + n).addClass('groupDrag');
      } else {
        if (value.dragEnable) {
          $('#answer' + n).addClass('dragActive');
        } else {
          $('#answer' + n).hide();
        }

        //drop label
        if (dragLab$(el).dropLabelType == 'image') {
          var answerDropLabelWrapperHTML =
            "<div id='dropLabel" +
            n +
            "' class='dropLabel fitImg' style='width:" +
            dragLab$(el).dropLabelWidth +
            '%; height:' +
            dragLab$(el).dropLabelHeight +
            '%; top:' +
            dragLab$(el).dropLabelTop +
            '%; left:' +
            dragLab$(el).dropLabelLeft +
            "%;'><img src='" +
            dragLab$(el).dropLabelText +
            "' /></div>";
        } else {
          var answerDropLabelWrapperHTML =
            "<div id='dropLabel" +
            n +
            "' class='dropLabel fontAnswer resizeFont' data-fontSize='" +
            dragLab$(el).dropLabelFontSize +
            "' data-lineHeight='" +
            dragLab$(el).dropLabelLineHeight +
            "' style='width:" +
            dragLab$(el).dropLabelWidth +
            '%; height:' +
            dragLab$(el).dropLabelHeight +
            '%; top:' +
            dragLab$(el).dropLabelTop +
            '%; left:' +
            dragLab$(el).dropLabelLeft +
            '%; font-size:' +
            dragLab$(el).dropLabelFontSize +
            'px; line-height:' +
            dragLab$(el).dropLabelLineHeight +
            'px; color:' +
            dragLab$(el).dropLabelColor +
            '; text-align:' +
            dragLab$(el).dropLabelAlign +
            ";'>" +
            dragLab$(el).dropLabelText +
            '</div>';
        }

        $('#answerHolder').append(answerDropLabelWrapperHTML);

        //drop group
        var dropLeft = Number(value.dropLeft) + Number(value.dropOffLeft);
        var dropTop = Number(value.dropTop) + Number(value.dropOffTop);

        var answerDropWrapperHTML =
          "<div id='drop" +
          n +
          "' class='drop' style='width:" +
          value.dropWidth +
          '%; height:' +
          value.dropHeight +
          '%; top:' +
          value.dropTop +
          '%; left:' +
          value.dropLeft +
          '%; border:' +
          dropBorder +
          ' solid ' +
          dropStroke +
          '; background:' +
          dropBackground +
          ";' data-left='" +
          dropLeft +
          "%' data-top='" +
          dropTop +
          "%'></div>";

        if (dragLab$(el).dropEnable) {
          $('#answerHolder').append(answerDropWrapperHTML);
        }

        $('#answer' + n).attr('data-top', value.top + '%');
        $('#answer' + n).attr('data-left', value.left + '%');
        $('#answer' + n).attr('data-answer', answerNum);
        $('#answer' + n).addClass('drag');
      }
    }

    buildAnswerEvent('#answer' + n);
  }

  if (playerData.answerType == 'drag') {
    setDragIndex();
  }
}

/*!
 *
 * BUILD INPUTS - This is the function that runs to build inputs
 *
 */
function buildInputs() {
  if (gameData.targetArray[gameData.sequenceNum].input.length <= 0) {
    return;
  }

  var answerHolderHTML = '<div id="inputHolder"></div>';
  $('#questionHolder').append(answerHolderHTML);
  playerData.answerType = 'input';

  for (
    n = 0;
    n < gameData.targetArray[gameData.sequenceNum].input.length;
    n++
  ) {
    var value = getArrayValue('input', n);

    if (value.submit == 'true') {
      if (value.type == 'image') {
        var inputHTML =
          '<div id="input' +
          n +
          '" class="input fitImg buttonClick" style="top:' +
          value.top +
          '%; left:' +
          value.left +
          '%; width:' +
          value.width +
          '%; "><img src="' +
          gameData.targetArray[gameData.sequenceNum].input[n].text +
          '" /></div>';
        $('#inputHolder').append(answerHTML);
        buildInputEvent('#input' + n);
      } else if (value.type == 'text') {
        var inputWrapperHTML =
          "<div id='input" +
          n +
          "' class='input resizeFont resizeBorder' data-border='" +
          answerButtonBgRoundNumber +
          "' style='border-radius: " +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -moz-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -webkit-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; width:' +
          value.width +
          '%; height:' +
          value.height +
          '%; top:' +
          value.top +
          '%; left:' +
          value.left +
          "%;'></div>";

        $('#inputHolder').append(inputWrapperHTML);

        if (answerButtonBgEnable) {
          var backgroundShadowHTML =
            "<div class='shadow resizeBorder' data-border='" +
            answerButtonBgRoundNumber +
            "' style='border-radius: " +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px; -moz-border-radius: ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px; -webkit-border-radius: ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px; background:' +
            answerButtonBgShadowColour +
            '; width:100%; height:100%; position:absolute; top:' +
            value.offsetTop +
            "%; left:0;'></div>";
          $('#input' + n).append(backgroundShadowHTML);

          var backgroundHTML =
            "<div class='background resizeBorder' data-border='" +
            answerButtonBgRoundNumber +
            "' style='border-radius: " +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px; -moz-border-radius: ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px; -webkit-border-radius: ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px ' +
            answerButtonBgRoundNumber +
            'px; background:' +
            answerButtonBgColour +
            '; width:100%; height:85%; position:absolute; top:' +
            value.offsetTop +
            "%; left:0;'></div>";
          $('#input' + n).append(backgroundHTML);
        }

        var inputHTML =
          '<div id="text' +
          n +
          '" class="fontAnswer resizeFont" data-fontSize="' +
          value.fontSize +
          '" data-lineHeight="' +
          value.lineHeight +
          '" style="position:relative; font-size:' +
          value.fontSize +
          'px; line-height:' +
          value.lineHeight +
          'px; color:' +
          value.color +
          '; text-align:' +
          value.align +
          ';">' +
          gameData.targetArray[gameData.sequenceNum].input[n].text +
          '</div>';
        $('#input' + n).append(inputHTML);

        var clickHTML =
          "<div class='buttonClick resizeBorder' data-border='" +
          answerButtonBgRoundNumber +
          "' style='border-radius: " +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -moz-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; -webkit-border-radius: ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px ' +
          answerButtonBgRoundNumber +
          'px; width:100%; height:100%; position:absolute; top:' +
          value.offsetTop +
          "%;'></div>";
        $('#input' + n).append(clickHTML);

        buildInputEvent('#input' + n);
      }
    } else {
      if (value.type == 'blank') {
        //input
        var inputWrapperHTML =
          "<input id='input" +
          n +
          "' class='input fontInput' type='text' style='font-size:" +
          value.fontSize +
          'px; line-height:' +
          value.lineHeight +
          'px; color:' +
          value.color +
          '; background:' +
          value.background +
          '; text-align:' +
          value.align +
          '; width:' +
          value.width +
          '%; height:' +
          value.height +
          '%; top:' +
          value.top +
          '%; left:' +
          value.left +
          "%;' placeholder='" +
          gameData.targetArray[gameData.sequenceNum].input[n].text +
          "'></input>";

        $('#inputHolder').append(inputWrapperHTML);
      }
    }

    $('#input' + n).attr('data-id', n);
    $('#input' + n).attr('data-type', value.type);
    $('#input' + n).attr('data-answer', value.correctAnswer);
  }
}

/*!
 *
 * INIT ANIMATE ANSWERS - This is the function that runs to animate answers
 *
 */
function initAnimateAnswers() {
  var animateDelayNum = 0.5;
  for (
    var n = 0;
    n < gameData.targetArray[gameData.sequenceNum].answer.length;
    n++
  ) {
    if (answerAnimationEnable) {
      if (gameData.mode != 'portrait') {
        $('#answer' + n).removeClass('cc-portrait-mode');
      } else {
        $('#answer' + n).addClass('cc-portrait-mode');
      }

      $('#answer' + n).css('opacity', 0);
      TweenMax.to($('#answer' + n), 0, {
        delay: animateDelayNum,
        scaleX: 1,
        scaleY: 1,
        overwrite: false,
        ease: Elastic.easeOut,
        onComplete: animateAnswer,
        onCompleteParams: [n],
      });
      animateDelayNum += 0.3;
    }
  }
}

function setTopSecondRowButtonAnswer(n, addSpace) {
  if (n == 0) {
    var topValue = parseInt($('#answer1').css('top'));
    $('#answer1').css('top', topValue + addSpace + 'px');
  }

  if (n == 2) {
    var topValue = parseInt($('#answer3').css('top'));
    $('#answer3').css('top', topValue + addSpace + 'px');
  }
}

function animateAnswer(n) {
  var scaleNum = 0.7;
  var speedNum = 1.3;

  TweenMax.to($('#answer' + n), 0, {
    scaleX: 0.5,
    scaleY: 0.5,
    overwrite: false,
  });
  TweenMax.to($('#answer' + n), speedNum, {
    alpha: 1,
    scaleX: 1,
    scaleY: 1,
    overwrite: false,
    ease: Elastic.easeOut,
  });

  setAnswerHeight(n);
}

function setAnswerHeight(n) {
  var heightOfText = $('#text' + n).height();
  var indexAnswer = [0, 1, 2, 3, 4];
  var addSpace = 0;

  //__info: for two line text in button answer

  if (heightOfText > 30 && parseInt($(window).width()) <= 576) {
    $('#text' + n).addClass('two-line');
    heightOfText = $('#text' + n).height();
  }

  if (heightOfText < 40 && parseInt($('#questionHolder').outerWidth()) <= 777) {
    $('#text' + n).css('top', '6px');
  }

  if (heightOfText > 50 && parseInt($('#questionHolder').outerWidth()) <= 777) {
    $('#text' + n).css('top', '-3px');
  }

  if (heightOfText > 80 && parseInt($('#questionHolder').outerWidth()) <= 777) {
    $('#text' + n).css('top', '-9px');
  }

  if (gameData.mode != 'portrait') {
    //for three line text in button answer
    if (heightOfText > 90) {
      $('#text' + n).css('top', '-12px');

      addSpace = 50;

      setTopSecondRowButtonAnswer(n, addSpace);
    } else if (heightOfText > 50) {
      //two line
      addSpace = 30;
      setTopSecondRowButtonAnswer(n, addSpace);
      $('#text' + n).css('top', '-6px');
    }
  } else {
    removeA(indexAnswer, n);

    //for two or three line spacing

    if (parseInt($(window).width()) <= 576) {
      // if(heightOfText > 80) {
      // 	addSpace = 70;
      // }

      // if(heightOfText > 50 && heightOfText < 70) {
      // 	addSpace = 20;
      // }

      // if(heightOfText <= 40) {
      // 	addSpace = 10;
      // }

      // if(heightOfText < 20) {
      // 	addSpace = -10;
      // }

      addSpace = -10;
    } else {
      if (heightOfText > 50 && heightOfText < 70) {
        addSpace = 0;
      } else if (heightOfText > 80) {
        addSpace = 20;
      } else {
        addSpace = -40;
      }
    }

    for (var i = 0; i < indexAnswer.length; i++) {
      if (n < indexAnswer[i]) {
        var topValue = parseInt($('#answer' + indexAnswer[i]).css('top'));
        $('#answer' + indexAnswer[i]).css('top', topValue + addSpace + 'px');
      }
    }
  }
}

/*!
 *
 * BUILD EXPLANATION - This is the function that runs to build explanation
 *
 */
function buildExplanation() {
  var value = getArrayValue('explanation');
  if (value.type == 'image') {
    var explanationHTML =
      '<div class="explanation fontExplanation fitImg" style="top:' +
      value.top +
      '%; left:' +
      value.left +
      '%; width:' +
      value.width +
      '%; "><img src="' +
      gameData.targetArray[gameData.sequenceNum].explanation +
      '" /></div>';
  } else {
    var explanationHTML =
      '<div class="explanation fontExplanation resizeFont" data-fontSize="' +
      value.fontSize +
      '" data-lineHeight="' +
      value.lineHeight +
      '" style="font-size:' +
      value.fontSize +
      'px; line-height:' +
      value.lineHeight +
      'px; color:' +
      value.color +
      ';  text-align:' +
      value.align +
      '; top:' +
      value.top +
      '%; left:' +
      value.left +
      '%; width:' +
      value.width +
      '%; height:' +
      value.height +
      '%; ">' +
      gameData.targetArray[gameData.sequenceNum].explanation +
      '</div>';
  }
  $('#explanationHolder').append(explanationHTML);
}

/*!
 *
 * BUILD ANSWER EVENT - This is the function that runs to build answer event
 *
 */
function buildAnswerEvent(obj) {
  if (!$.editor.enable) {
    if ($(obj).hasClass('groupDrag')) {
      $('.groupDrag').droppable({
        accept: '.groupDrag',
        greedy: true,
        drop: function (event, ui) {
          var targetDrop = $('#groupDrop' + $(this).attr('data-drop-id'));
          updateGroupID(targetDrop, $(this), false);
          revertPosition($(this));
        },
      });

      $('.groupDrag').draggable({
        start: function (event, ui) {
          setDragIndex($(this));
        },
        stop: function () {
          setGroupPosition();
          revertPosition($(this));
        },
      });

      $('.groupDrop').droppable({
        accept: '.groupDrag',
        greedy: false,
        drop: function (event, ui) {
          updateGroupID($(this), $(ui.draggable), true);
        },
        out: function (event, ui) {
          updateGroupID($(this), $(ui.draggable), false);
        },
      });
    } else if ($(obj).hasClass('drag')) {
      $('.drag').draggable({
        start: function () {
          if ($(this).hasClass('occupied')) {
            if (dragDroppedAnswerAgain) {
              $(this).removeClass('occupied');
              playerData.correctAnswer.splice(1, 0);

              var currentID = $(this).attr('id');
              $('.drop').each(function (index, element) {
                if ($(this).attr('data-drag-id') == currentID) {
                  $(this).attr('data-drag-id', '');
                }
              });
            } else {
              return false;
            }
          } else {
            setDragIndex($(this));
          }

          setDragIndex($(this));
        },
        stop: function () {
          revertPosition($(this));
        },
      });

      $('.drop').droppable({
        accept: '.drag',
        drop: function (event, ui) {
          if ($(this).hasClass('occupied')) {
            var lastDrag = $('#' + $(this).attr('data-drag-id'));
            lastDrag.removeClass('occupied');
            revertPosition(lastDrag);
          } else {
            playerData.correctAnswer.push(0);
          }

          $(ui.draggable).addClass('occupied');
          $(ui.draggable).attr('data-top-drop', $(this).attr('data-top'));
          $(ui.draggable).attr('data-left-drop', $(this).attr('data-left'));

          $(this).attr('data-drag-id', ui.draggable.attr('id'));
          $(this).addClass('occupied');
        },
      });
    } else {
      $(obj).click(function () {
        if (
          playerData.answerType == 'drag' &&
          $('#groupHolder .groupDrop').length
        ) {
          //group drag
          var totalGroup = 0;
          var totalDrop = 0;
          $('#groupHolder .groupDrop').each(function (index, element) {
            var groupArray = $(this).attr('data-group');
            var dropMax = Number($(this).attr('data-max'));

            if (dropMax > 0) {
              totalDrop++;
            }

            groupArray = groupArray == undefined ? [] : groupArray.split(',');
            if (groupArray.length > 0) {
              totalGroup++;
            }
          });

          //alert(totalGroup+' : '+totalDrop);
          if (totalGroup > 0) {
            $('.groupDrag').draggable('disable');
            $('.groupDrop').droppable('disable');
            focusTapAnswer(
              $(this).attr('data-id'),
              $(this).attr('data-type'),
              'true',
              true
            );
          }
        } else if (playerData.answerType == 'drag') {
          //drag
          var totalDrop = $('.drop').length;
          var totalDrag = $('.dragActive').length;

          var proceedCon = false;
          if (totalDrag < totalDrop) {
            if (playerData.correctAnswer.length == $('.dragActive').length) {
              proceedCon = true;
            }
          } else {
            if (playerData.correctAnswer.length == $('.drop').length) {
              proceedCon = true;
            }
          }

          if (proceedCon) {
            $('.drag').draggable('disable');
            $('.drop').droppable('disable');
            focusTapAnswer(
              $(this).attr('data-id'),
              $(this).attr('data-type'),
              'true',
              true
            );
          }
        } else if (playerData.correctAnswer.length > 1) {
          focusTapAnswer(
            $(this).attr('data-id'),
            $(this).attr('data-type'),
            $(this).attr('data-submit'),
            true
          );
        } else {
          focusTapAnswer(
            $(this).attr('data-id'),
            $(this).attr('data-type'),
            'true',
            false
          );
        }
      });
    }
  }
}

function updateGroupID(obj, target, con) {
  var groupName =
    playerData.answered == true ? 'data-groupanswered' : 'data-group';
  var groupArray = obj.attr(groupName);
  groupArray = groupArray == undefined ? [] : groupArray.split(',');

  if (con) {
    /*if(groupArray.length >= Number(obj.attr('data-max'))){
			return;
		}*/

    target.attr('data-drop-id', obj.attr('data-id'));
    target.addClass('occupied');
    groupArray.push(target.attr('data-id'));
  } else {
    target.removeAttr('data-drop-id');
    target.removeClass('occupied');
    var removeIndex = groupArray.indexOf(target.attr('data-id'));
    if (removeIndex != -1) groupArray.splice(removeIndex, 1);
  }

  groupArray = unique(groupArray);
  if (groupArray.length == 0) {
    obj.removeAttr(groupName);
  } else {
    obj.attr(groupName, groupArray);
  }
}

function removeGroupID(index, target) {
  var groupName =
    playerData.answered == true ? 'data-groupanswered' : 'data-group';
  $('#groupHolder .groupDrop').each(function (dropIndex, dropElement) {
    if (index != dropIndex) {
      var groupArray = $(this).attr(groupName);
      groupArray = groupArray == undefined ? [] : groupArray.split(',');

      var removeIndex = groupArray.indexOf(target.attr('data-id'));
      if (removeIndex != -1) {
        groupArray.splice(removeIndex, 1);
      }

      if (groupArray.length == 0) {
        $(dropElement).removeAttr(groupName);
      } else {
        $(dropElement).attr(groupName, groupArray);
      }
    }
  });
}

function setGroupPosition() {
  var groupName =
    playerData.answered == true ? 'data-groupanswered' : 'data-group';
  $('#groupHolder .groupDrop').each(function (index, element) {
    var maxItem = Number($(this).attr('data-max'));
    var currentOffTop = Number($(this).attr('data-offtop').replace('%', ''));
    var currentOffLeft = Number($(this).attr('data-offleft').replace('%', ''));
    var currentTop = Number($(this).attr('data-top').replace('%', ''));
    var currentLeft = Number($(this).attr('data-left').replace('%', ''));
    var currentWidth = Number($(this).attr('data-width').replace('%', ''));
    var currentHeight = Number($(this).attr('data-height').replace('%', ''));

    var startTop = currentTop;
    var startLeft = currentLeft;

    var groupArray = $(this).attr(groupName);
    groupArray = groupArray == undefined ? [] : groupArray.split(',');

    for (var n = 0; n < groupArray.length; n++) {
      if (n < maxItem) {
        $('#answer' + groupArray[n]).attr('data-top-drop', startTop + '%');
        $('#answer' + groupArray[n]).attr('data-left-drop', startLeft + '%');
        revertPosition($('#answer' + groupArray[n]));

        startLeft += Number(
          (
            ($('#answer' + groupArray[n]).outerWidth() /
              $('#answerHolder').outerWidth()) *
            100
          ).toFixed()
        );
        startLeft += currentOffLeft;

        if (Number(startLeft + 10) >= Number(currentLeft + currentWidth)) {
          startLeft = currentLeft;
          startTop += Number(
            (
              ($('#answer' + groupArray[n]).outerHeight() /
                $('#answerHolder').outerHeight()) *
              100
            ).toFixed()
          );
          startTop += currentOffTop / 2;
        }
      } else {
        var targetDrop = $(
          '#groupDrop' + $('#answer' + groupArray[n]).attr('data-drop-id')
        );
        updateGroupID(targetDrop, $('#answer' + groupArray[n]), false);
      }
    }
  });
}

function setDragIndex(obj) {
  $('.answer').each(function (index, element) {
    $(this).css('z-index', 10);
  });

  if (obj != undefined) {
    obj.css('z-index', 11);
  }
}

function revertPosition(obj) {
  if (obj.hasClass('occupied')) {
    TweenMax.to(obj, dragRevertSpeed, {
      css: { left: obj.attr('data-left-drop'), top: obj.attr('data-top-drop') },
    });
  } else {
    TweenMax.to(obj, dragRevertSpeed, {
      css: { left: obj.attr('data-left'), top: obj.attr('data-top') },
    });
  }
}

/*!
 *
 * BUILD INPUT EVENT - This is the function that runs to build input event
 *
 */
function buildInputEvent(obj) {
  if (!$.editor.enable) {
    $(obj).click(function () {
      checkInputAnswer();
    });
  }
}

/*!
 *
 * FOCUS ANSWER ANIMATION - This is the function that runs to focus on answer animation
 *
 */

function focusTapAnswer(n, type, submit, hide) {
  if (!playerData.answered) {
    stopAudio();
    toggleAudioInterval(false);
    playSound('soundSelectAnswer');

    if (submit == 'true') {
      //for draggable, input and multiple choice answers

      //__info: keep timer ticking when click answer
      toggleGameTimer(false);

      playerData.answered = true;
      if (hide) {
        $('#answer' + n).hide();
      }
    }

    //reset animation
    $('#answerHolder .answer').each(function (index, element) {
      TweenMax.to($(this), 0, {
        scaleX: 1,
        scaleY: 1,
        alpha: 1,
        overwrite: true,
      });
    });

    //asnwer selected button colour
    var currentBgColor = answeredButtonBgColour;
    var currentBgShadowColor = answeredButtonBgShadowColour;
    var curScaleNum = 0.5;

    if ($('#answer' + n).hasClass('answerFocus')) {
      $('#answer' + n).removeClass('answerFocus');
      currentBgColor = answerButtonBgColour;
      currentBgShadowColor = answerButtonBgShadowColour;
    } else {
      $('#answer' + n).addClass('answerFocus');
    }

    $('#answer' + n)
      .find('.background')
      .css('background', currentBgColor);
    $('#answer' + n)
      .find('.shadow')
      .css('background', currentBgShadowColor);

    //answer selected image effect
    if (playerData.answerType != 'drag') {
      $('#answerHolder .answer').each(function (index, element) {
        if ($(this).attr('data-type') == 'image') {
          $(this).css('opacity', 0.5);
          if ($(this).hasClass('answerFocus')) {
            $(this).css('opacity', 1);
          }
        }
      });
    }

    //select animation
    TweenMax.to($('#answer' + n), 0, {
      scaleX: curScaleNum,
      scaleY: curScaleNum,
      overwrite: true,
    });
    TweenMax.to($('#answer' + n), 1, {
      scaleX: 1,
      scaleY: 1,
      overwrite: true,
      ease: Elastic.easeOut,
      onComplete: function () {
        if (playerData.answered) {
          playerData.answer_arr = [];
          if (playerData.answerType == 'drag' && $('.groupDrop').length) {
            $('#answerHolder .answer').each(function (index, element) {
              if ($(this).attr('data-type') == 'image') {
                $(this).css('opacity', 1);
              }
            });

            if (enableRevealAnswer && !checkAnswerCorrect()) {
              $('#groupHolder .groupDrop').each(function (
                dropIndex,
                dropElement
              ) {
                var groupArray = $(this).attr('data-group');
                if (groupArray != undefined) {
                  $(this).attr(
                    'data-groupanswered',
                    $(this).attr('data-group')
                  );
                }
              });

              var revertPos = [];
              $('#answerHolder .answer').each(function (index, element) {
                var answerOriID = $(this).attr('data-ori-id');
                var answerID = $(this).attr('data-id');
                var targetAnswer = $(this);

                if (answerOriID != undefined) {
                  //not submit button

                  var foundInGroup = false;
                  $('#groupHolder .groupDrop').each(function (
                    dropIndex,
                    dropElement
                  ) {
                    var groupAnswerArray = $(this)
                      .attr('data-answer')
                      .split(',');
                    var groupArray = $(this).attr('data-groupanswered');
                    groupArray =
                      groupArray == undefined ? [] : groupArray.split(',');

                    var answerInCorrectGroup = groupAnswerArray.indexOf(
                      answerOriID
                    );
                    var answerInGroup = groupArray.indexOf(String(answerID));

                    if (answerInCorrectGroup != -1 && !foundInGroup) {
                      foundInGroup = true;

                      //is in the  group
                      if (answerInGroup == -1) {
                        if (targetAnswer.attr('data-type') == 'image') {
                          targetAnswer.css('opacity', 0.8);
                        }
                        targetAnswer.removeClass('answerFocus');
                        targetAnswer
                          .find('.background')
                          .css('background', wrongButtonBgColour);
                        targetAnswer
                          .find('.shadow')
                          .css('background', wrongButtonBgShadowColour);

                        targetAnswer.removeClass('occupied');
                        removeGroupID(dropIndex, targetAnswer);
                        updateGroupID($(dropElement), targetAnswer, true);
                      }
                    }
                  });

                  if (!foundInGroup) {
                    targetAnswer.removeClass('answerFocus');
                    targetAnswer
                      .find('.background')
                      .css('background', wrongButtonBgColour);
                    targetAnswer
                      .find('.shadow')
                      .css('background', wrongButtonBgShadowColour);

                    removeGroupID(-1, targetAnswer);
                    targetAnswer.removeClass('occupied');
                    revertPos.push(targetAnswer);
                  }

                  TweenMax.to(targetAnswer, 0, {
                    delay: 1,
                    overwrite: true,
                    onComplete: function () {
                      displayQuestionResult();
                    },
                  });
                }
              });

              setGroupPosition();
              for (var n = 0; n < revertPos.length; n++) {
                revertPosition(revertPos[n]);
              }
            } else {
              displayQuestionResult();
            }
          } else if (playerData.answerType == 'drag') {
            //drag
            $('#answerHolder .answer').each(function (index, element) {
              if ($(this).attr('data-type') == 'image') {
                $(this).css('opacity', 1);
              }
            });

            if (enableRevealAnswer && !checkAnswerCorrect()) {
              $('#answerHolder .answer').each(function (index, element) {
                var answerNum = $(this).attr('data-answer');
                if ($('#drop' + answerNum).length == 0) {
                  var targetAnswer = $(this);
                  if (targetAnswer.attr('data-type') == 'image') {
                    targetAnswer.css('opacity', 0.8);
                  }
                  targetAnswer.removeClass('answerFocus');
                  targetAnswer
                    .find('.background')
                    .css('background', wrongButtonBgColour);
                  targetAnswer
                    .find('.shadow')
                    .css('background', wrongButtonBgShadowColour);
                }
              });

              $('#answerHolder .drop').each(function (index, element) {
                var dropID = $(this)
                  .attr('id')
                  .substring(4, $(this).attr('id').length);
                var dragID = $('#' + $(this).attr('data-drag-id')).attr(
                  'data-answer'
                );

                var targetAnswer = $('#' + $(this).attr('data-drag-id'));
                if (dropID != dragID) {
                  if (targetAnswer.attr('data-type') == 'image') {
                    targetAnswer.css('opacity', 0.8);
                  }
                  targetAnswer.removeClass('answerFocus');
                  targetAnswer
                    .find('.background')
                    .css('background', wrongButtonBgColour);
                  targetAnswer
                    .find('.shadow')
                    .css('background', wrongButtonBgShadowColour);
                }

                TweenMax.to(targetAnswer, 0, {
                  delay: 1,
                  overwrite: true,
                  onComplete: function () {
                    displayQuestionResult();
                  },
                });
              });

              $('#answerHolder .answer').each(function (index, element) {
                var answerNum = $(this).attr('data-answer');
                if ($('#drop' + answerNum).length) {
                  $(this).addClass('occupied');
                  $(this).attr(
                    'data-top-drop',
                    $('#drop' + answerNum).attr('data-top')
                  );
                  $(this).attr(
                    'data-left-drop',
                    $('#drop' + answerNum).attr('data-left')
                  );
                } else {
                  $(this).removeClass('occupied');
                }
                revertPosition($(this));
              });
            } else {
              displayQuestionResult();
            }
          } else {
            //others
            $('#answerHolder .answer').each(function (index, element) {
              if ($(this).hasClass('answerFocus')) {
                if (
                  $(this).attr('data-submit') == undefined ||
                  $(this).attr('data-submit') == 'false'
                ) {
                  playerData.answer_arr.push(Number($(this).attr('data-id')));
                }
              }
            });

            $('.answer').addClass('answer-disabled');
            if (enableRevealAnswer && !checkAnswerCorrect()) {
              $('#answerHolder .answer').each(function (index, element) {
                if ($(this).attr('data-type') == 'image') {
                  $(this).css('opacity', 0.5);
                }
              });

              for (var n = 0; n < playerData.answer_arr.length; n++) {
                var currentAnswer = playerData.answer_arr[n];
                $('#answer' + currentAnswer).removeClass('answerFocus');

                //__info: This if added to force error marking button for the first selected
                // if(n == 0) {

                //__info: this commented two line of code is the original state
                // $('#answer'+currentAnswer).find('.background').css('background', wrongButtonBgColour);
                // $('#answer'+currentAnswer).find('.shadow').css('background', wrongButtonBgShadowColour);

                $('#answer' + currentAnswer)
                  .find('.background')
                  .addClass('wrong-ans');
                $('#answer' + currentAnswer).addClass('answer-disabled');
                $('#answer' + currentAnswer)
                  .find('.background')
                  .html('<img class="status-icon" src="./img/error-icon.svg">');
                $('#answer' + currentAnswer)
                  .find('.shadow')
                  .addClass('wrong-ans');

                //__info: this setTimeout used to remove the wrong accent, but now we need to make the wrong answer stay dimmed.
                // setTimeout(function() {
                // 	$('#answer'+currentAnswer).find('.background').removeClass('wrong-ans');
                // 	$('#answer'+currentAnswer).find('.background .status-icon').remove();
                // 	$('#answer'+currentAnswer).find('.shadow').removeClass('wrong-ans');
                // }, 1500)

                // playerData.answer_arr = [];
                // }
              }

              //reveal answer
              // for(var n=0;n<playerData.correctAnswer.length;n++){
              // 	var currentAnswer = playerData.correctAnswer[n]-1;
              // 	$('#answer'+currentAnswer).addClass('answerFocus');
              // 	$('#answer'+currentAnswer).find('.background').css('background', answeredButtonBgColour);
              // 	$('#answer'+currentAnswer).find('.shadow').css('background', answeredButtonBgShadowColour);

              // 	TweenMax.to($('#answer'+currentAnswer), 0, {scaleX:.5, scaleY:.5, overwrite:true});
              // 	TweenMax.to($('#answer'+currentAnswer), 1, {scaleX:1, scaleY:1, alpha:1, overwrite:true, ease:Elastic.easeOut, onComplete:function(){
              // 		TweenMax.to($('#answer'+currentAnswer), 0, {delay:1, overwrite:true, onComplete:function(){
              // 			displayQuestionResult();
              // 		}});
              // 	}});
              // }
              ccValueWrong++;
              setPoint(false);
            } else {
              //correct

              for (var n = 0; n < playerData.correctAnswer.length; n++) {
                var currentAnswer = playerData.correctAnswer[n] - 1;
                $('#answer' + currentAnswer).addClass('answerFocus');
                $('#answer' + currentAnswer)
                  .find('.background')
                  .addClass('correct-ans');
                $('#answer' + currentAnswer)
                  .find('.background')
                  .html(
                    '<img class="status-icon-correct" src="./img/correct-icon.svg">'
                  );
                $('#answer' + currentAnswer)
                  .find('.shadow')
                  .addClass('correct-ans');
              }
              ccValueCorrect++;
              setPoint(true);
            }

            playerData.answered = false;
          }
        }
      },
    });
  }
}

function hintWrongButton() {
  var answers = playerData.answer_arr;
  var correctAnswer = playerData.correctAnswer[0];
  var disabled = 0;
  gameData.targetArray[gameData.sequenceNum].hint = true;

  for (var i = 0; i < 4; i++) {
    if (i != correctAnswer - 1 && disabled < 2) {
      $('#answer' + i).addClass('answer-dim');
      $('#answer' + i).addClass('answer-disabled');
      disabled++;
    }
  }
}

/*!
 *
 * CHECK RIGHT ANSWER - This is the function that runs to check right answer
 *
 */

function checkAnswerCorrect() {
  var correctAnswer = false;
  var correctAnswerCount = 0;

  stopVideoPlayer(true);

  if (playerData.answerType == 'drag' && $('#groupHolder .groupDrop').length) {
    var totalAnswerCount = 0;
    var correctAnswerCount = 0;
    var totalDragCount = 0;

    $('#groupHolder .groupDrop').each(function (index, element) {
      var groupAnswerArray = $(this).attr('data-answer');
      groupAnswerArray =
        groupAnswerArray == '' ? [] : groupAnswerArray.split(',');
      totalAnswerCount += groupAnswerArray.length;

      var groupArray = $(this).attr('data-group');
      groupArray = groupArray == undefined ? [] : groupArray.split(',');

      for (var n = 0; n < groupArray.length; n++) {
        var answerOriID = $('#answer' + groupArray[n]).attr('data-ori-id');
        var answerInGroup = groupAnswerArray.indexOf(answerOriID);
        if (answerInGroup != -1) {
          correctAnswerCount++;
        }
        totalDragCount++;
      }
    });

    if (
      totalAnswerCount == correctAnswerCount &&
      totalAnswerCount == totalDragCount
    ) {
      correctAnswer = true;
    }
  } else if (playerData.answerType == 'drag') {
    //drag and drop question
    var totalDrop = $('#answerHolder .drop').length;
    var totalDrag = $('#answerHolder .dragActive').length;

    $('#answerHolder .drop').each(function (index, element) {
      var dropID = $(this).attr('id').substring(4, $(this).attr('id').length);
      var dragID = $('#' + $(this).attr('data-drag-id')).attr('data-answer');

      if (dropID == dragID) {
        correctAnswerCount++;
      }
    });

    if (totalDrag < totalDrop) {
      if (correctAnswerCount == totalDrag) {
        correctAnswer = true;
      }
    } else {
      if (correctAnswerCount == totalDrop) {
        correctAnswer = true;
      }
    }
  } else if (playerData.answerType == 'select') {
    //multiple choices select
    for (var n = 0; n < playerData.answer_arr.length; n++) {
      var currentAnswer = playerData.answer_arr[n] + 1;
      if (playerData.correctAnswer.indexOf(currentAnswer) != -1) {
        correctAnswerCount++;
      }
    }

    //HERE
    if (
      correctAnswerCount == playerData.correctAnswer.length &&
      playerData.answer_arr.length == playerData.correctAnswer.length
    ) {
      correctAnswer = true;
    }
  } else if (playerData.answerType == 'input') {
    //input question
    var totalInput = $('#inputHolder input').length;
    $('#inputHolder input').each(function (index, element) {
      if ($(this).val() == $(this).attr('data-answer')) {
        correctAnswerCount++;
      }
    });

    if (correctAnswerCount == totalInput) {
      correctAnswer = true;
    }
  }

  return correctAnswer;
}

function checkInputAnswer() {
  if (!playerData.answered) {
    var proceedInput = false;
    var totalInput = $('#inputHolder input').length;
    var totalCount = 0;

    playerData.answer_arr = [];
    $('#inputHolder input').each(function (index, element) {
      if ($(this).val() != '') {
        playerData.answer_arr.push($(this).val());
        totalCount++;
      }
    });

    if (totalInput == totalCount) {
      proceedInput = true;
    }

    if (proceedInput) {
      playSound('soundClick');
      toggleGameTimer(false);
      stopAudio();
      toggleAudioInterval(false);
      playerData.answered = true;

      $('#inputHolder input').prop('disabled', true);
      displayQuestionResult();
    }
  }
}

function isFastAnswered() {
  let time = millisecondsToTime(timeData.elapsedTime);
  let timeArr = time.split(':');
  let hrs = parseInt(timeArr[0]);
  let mnt = parseInt(timeArr[1]);
  let sec = parseInt(timeArr[2]);

  if (hrs < 1 && mnt < 1 && sec < 6) {
    return true;
  }

  return false;
}

function setPoint(isCorrect) {
  var isFast = isFastAnswered();
  var point = 0;
  if (isCorrect) {
    playSound('soundAnswerCorrect');

    let isHintClicked = gameData.targetArray[gameData.sequenceNum].hint;
    if (isHintClicked) {
      point = 6;
    } else {
      isFast ? (point = 15) : (point = 10);
    }
    playerData.score += point;
    questionStatistic.correct_count++;
  } else {
    playSound('soundAnswerWrong');
    questionStatistic.incorrect_count++;
  }

  // if (info) {
  showFunfact(isCorrect, isFast, point);
  // } else {
  //   displayQuestionResult();
  // }

  sendStatistic();
}

function showFunfact(isCorrect, isFast, point) {
  var info = gameData.targetArray[gameData.sequenceNum].info;
  $('#btn-reset').hide();
  if ($(window).width() < 576) {
    $('#modal-funfact').addClass('cc-modal-fullscreen');
  } else {
    $('#modal-funfact').removeClass('cc-modal-fullscreen');
  }

  if (
    gameData.sequenceNum ==
    gameData.sequence_arr[gameData.sequence_arr.length - 1]
  ) {
    $('#btn-next span').text('Finish');
    $('#btn-next img').hide();
  } else {
    $('#btn-next span').text('Go to Next Question');
    $('#btn-next img').show();
  }

  $('.btn-next').removeClass('disabled');
  $('#btn-report').removeClass('disabled');
  $('#btn-like').removeClass('cs-disabled');
  $('#btn-dislike').removeClass('cs-disabled');
  let currentQuestion = gameData.targetArray[gameData.sequenceNum];
  let correctCount = parseInt(currentQuestion.correct_count);
  let incorrectCount = parseInt(currentQuestion.incorrect_count);

  if (isCorrect) {
    $('#funfactPoint').show();
    $('#funfactPoint span').text(point);
    if (isFast) {
      $('#funfactGreet').text("You're fast and smart!");
    } else {
      $('#funfactGreet').text("You're brilliant!");
    }
    $('#funfactSign').removeClass('wrong');
    correctCount++;
  } else {
    $('#funfactPoint').hide();
    $('#funfactGreet').text('Oops, not quite.');
    $('#funfactSign').addClass('wrong');
    incorrectCount++;
  }

  $('#funfactThumbnail').hide();
  $('#funfactThumbnail').attr('src', '');
  if (info) {
    $('#funfactSource').show();
    $('#funfactSource').attr('href', currentQuestion.source);
    $('#funfactInfo').text(currentQuestion.info);
    if (currentQuestion.image?.length > 0) {
      $('#funfactThumbnail').show();
      $('#funfactThumbnail').attr('src', currentQuestion.image);
    } else {
      $('#funfactThumbnail').hide();
      $('#funfactThumbnail').attr('src', '#');
    }
  } else {
    $('#funfactSource').hide();
    $('#funfactInfo').text(
      "Sorry. We don't have more info about this at the moment."
    );
    $('#funfactThumbnail').show();
    $('#funfactThumbnail').attr('src', './img/no-pass.svg');
  }

  let answerCount = correctCount + incorrectCount;
  let correctPercentage = (correctCount / answerCount) * 100;
  let incorrectPercentage = (incorrectCount / answerCount) * 100;

  $('#funfactTitle').text(currentQuestion.correct_answer);
  $('#funfactCorrectBar').css('flex-basis', correctPercentage + '%');
  $('#funfactIncorrectBar').css('flex-basis', incorrectPercentage + '%');
  $('#funfactCorrect').text(correctCount);
  $('#funfactIncorrect').text(incorrectCount);
  $('#modal-funfact').modal('show');
}

function setScoreNavbar() {
  var score = playerData.score.toString();

  // var padded = score.padStart(6, "0");

  return score;
}

/*!
 *
 * DISPLAY QUESTION RESULT - This is the function that runs to display question result
 *
 */
function displayQuestionResult() {
  if (checkAnswerCorrect()) {
    $('.questionResultText').html(correctDisplayText);
    $('#scoreText').html(setScoreNavbar());
  } else {
    $('.questionResultText').html(wrongDisplayText);
  }

  // setTimeout(function () {
  prepareNextQuestion();
  $('#modal-funfact').modal('hide');
  // }, 1500);
  // TweenMax.killTweensOf($('.questionResultText'));
  // TweenMax.to($('.questionResultText'), 0, {scaleX:.8, scaleY:.8, alpha:0, overwrite:true});
  // TweenMax.to($('.questionResultText'), 1, {delay:.2, scaleX:1, scaleY:1, alpha:1, ease:Elastic.easeOut, overwrite:true});

  // if(enableExplanation){
  // 	playAudioLoop('explanation');
  // 	$('#explanationHolder').show();
  // }else{
  // 	$('#explanationHolder').hide();
  // }

  // $('#questionHolder').hide();
  // $('#questionResultHolder').show();
  // $('#questionResultHolder').css('opacity',0);

  // TweenMax.to($('#questionResultHolder'), 1, {alpha:1, overwrite:true, onComplete:function(){

  // }});
}

function presetAnswered() {
  TweenMax.killAll();

  stopVideoPlayer(true);

  TweenMax.to($('.questionResultText'), 0, {
    scaleX: 1,
    scaleY: 1,
    overwrite: true,
  });
  if (playerData.answerType == 'select') {
    $('#answerHolder .answer').each(function (index, element) {
      if ($(this).attr('data-submit') == 'true') {
        $(this).hide();
      }
    });

    $('#answerHolder .answer').each(function (index, element) {
      if ($(this).attr('data-type') == 'image') {
        $(this).css('opacity', 0.5);
      }
    });

    for (var n = 0; n < playerData.answer_arr.length; n++) {
      var currentAnswer = playerData.answer_arr[n];
      $('#answer' + currentAnswer).removeClass('answerFocus');
      $('#answer' + currentAnswer)
        .find('.background')
        .css('background', wrongButtonBgColour);
      $('#answer' + currentAnswer)
        .find('.shadow')
        .css('background', wrongButtonBgShadowColour);
    }

    if (enableRevealAnswer) {
      for (var n = 0; n < playerData.correctAnswer.length; n++) {
        var currentAnswer = playerData.correctAnswer[n] - 1;
        $('#answer' + currentAnswer).addClass('answerFocus');
        $('#answer' + currentAnswer)
          .find('.background')
          .css('background', answeredButtonBgColour);
        $('#answer' + currentAnswer)
          .find('.shadow')
          .css('background', answeredButtonBgShadowColour);

        TweenMax.to($('#answer' + currentAnswer), 0, {
          scaleX: 0.5,
          scaleY: 0.5,
          overwrite: true,
        });
        TweenMax.to($('#answer' + currentAnswer), 1, {
          scaleX: 1,
          scaleY: 1,
          alpha: 1,
          overwrite: true,
          ease: Elastic.easeOut,
          onComplete: function () {
            TweenMax.to($('#answer' + currentAnswer), 0, {
              delay: 1,
              overwrite: true,
            });
          },
        });
      }
    }
  } else if (playerData.answerType == 'input') {
    $('#inputHolder input').prop('disabled', true);
    $('#inputHolder input').each(function (index, element) {
      $(this).val(playerData.answered[index]);
    });
  }

  if (checkAnswerCorrect()) {
    $('.questionResultText').html(correctDisplayText);
  } else {
    $('.questionResultText').html(wrongDisplayText);
  }

  if (enableExplanation) {
    $('#explanationHolder').show();
  } else {
    $('#explanationHolder').hide();
  }

  $('#questionHolder').hide();
  $('#questionResultHolder').show();
  $('#questionResultHolder').css('opacity', 1);
}

function previewQuestion() {
  $('#questionResultHolder').hide();
  $('#questionHolder').show();
  $('#questionHolder').css('opacity', 0);

  playYoutubeVideo();
  TweenMax.to($('#questionHolder'), 1, {
    alpha: 1,
    overwrite: true,
    onComplete: function () {
      TweenMax.to($('#questionHolder'), 0, {
        delay: 2,
        overwrite: true,
        onComplete: function () {
          stopVideoPlayer(true);
          $('#questionHolder').hide();
          $('#questionResultHolder').show();
          $('#questionResultHolder').css('opacity', 0);

          TweenMax.to($('#questionResultHolder'), 1, {
            alpha: 1,
            overwrite: true,
            onComplete: function () {},
          });
        },
      });
    },
  });
}

function playYoutubeVideo() {
  $('#videoHolder iframe').attr(
    'src',
    $('#videoHolder iframe').attr('data-src')
  );
}

function stopVideoPlayer(con) {
  $('video').each(function () {
    $(this).get(0).pause();
  });

  if (con) {
    $('#videoHolder iframe').attr('src', '');
  }
}

/*!
 *
 * PREPARE NEXT QUESTION - This is the function that runs for next question
 *
 */
function prepareNextQuestion() {
  $('#btn-reset').show();
  stopAudio();

  if (totalQuestions != 0) {
    gameData.questionNum++;

    var totalMax =
      totalQuestions > gameData.sequence_arr.length
        ? gameData.sequence_arr.length
        : totalQuestions;
    if (gameData.questionNum < totalMax) {
      loadQuestion();
    } else {
      playSound('soundComplete');
      goPage('result');
    }
  } else {
    if (gameData.questionNum < gameData.sequence_arr.length - 1) {
      gameData.questionNum++;
      loadQuestion();
    } else {
      playSound('soundComplete');
      goPage('result');
    }
  }
}

/*!
 *
 * TOGGLE QUESTION LOADER - This is the function that runs to display question loader
 *
 */
function toggleQuestionLoader(con) {
  if (con) {
    $('#questionLoaderHolder').show();
    $('#questionHolder').hide();
  } else {
    $('#questionLoaderHolder').hide();
    $('#questionHolder').show();
  }
}

// setInterval(() => {
//   console.log(timeData.timer);
// }, 1000);

/*!
 *
 * GAME TIMER - This is the function that runs for game timer
 *
 */
function toggleGameTimer(con) {
  if ($.editor.enable) {
    return;
  }

  if (!enableTimer) {
    return;
  }

  TweenMax.killTweensOf(timeData);
  if (con) {
    if (storeData.status) {
      timeData.startDate = storeData.timerDate;
    } else {
      timeData.startDate = storeData.timerDate = new Date();
    }
    loopTimer();
  } else {
    if (timerAllSession) {
      timeData.accumulate = timeData.timer;
      timeData.countdown = timeData.timer;
    }
  }
  timeData.enable = con;
}

function updateTimerDisplay(con) {
  var resetDisplay = true;

  if (!con) {
    if (timerAllSession) {
      resetDisplay = false;
    }
  }

  if (resetDisplay) {
    if (timerMode == 'countdown') {
      $('#gameStatus .gameTimerStatus').html(
        millisecondsToTime(timeData.countdown)
      );
    } else {
      $('#gameStatus .gameTimerStatus').html('00:00:00');
    }
  }
}

function loopTimer() {
  TweenMax.to(timeData, 0.2, { overwrite: true, onComplete: updateTimer });
}

function updateTimer() {
  timeData.nowDate = new Date();
  timeData.elapsedTime = Math.floor(
    timeData.nowDate.getTime() - timeData.startDate.getTime()
  );

  if (timerMode == 'default') {
    timeData.timer = timeData.elapsedTime + timeData.accumulate;
  } else if (timerMode == 'countdown') {
    timeData.timer = Math.floor(timeData.countdown - timeData.elapsedTime);
  }

  $('.gameTimerStatus').html(millisecondsToTime(timeData.timer));

  if (timeData.timer <= 0) {
    toggleResult(false);
    goPage('result');
  } else {
    if (timeData.enable) {
      loopTimer();
    }
  }
}

function toggleResult(con) {
  if (con) {
    $('.itemWinnerCup img').attr('src', 'assets/item_cup.svg');
  } else {
    $('.itemWinnerCup img').attr('src', 'assets/item_cup_over.svg');
  }
}

/*!
 *
 * XML - This is the function that runs to load word from xml
 *
 */
function loadXML(src) {
  $('.preloadText').show();
  $('#buttonStart').hide();

  $.ajax({
    url: src,
    type: 'GET',
    dataType: 'xml',
    success: function (result) {
      if ($.editor.enable) {
        edit.xmlFile = result;
      }

      $(result)
        .find('thumb')
        .each(function (catIndex, catElement) {
          gameData.categoryThumb_arr.push({
            src: $(catElement).text(),
            name: $(catElement).attr('name'),
          });
        });

      $(result)
        .find('item')
        .each(function (questionIndex, questionElement) {
          pushDataArray(questionIndex, questionElement);
        });

      loadXMLComplete();
    },
  });
}

function pushDataArray(questionIndex, questionElement) {
  var curCategory = $(questionElement).find('category').text();
  var indexAnswer;

  if (curCategory != '') {
    gameData.category_arr.push($(questionElement).find('category').text());
  }

  //landscape
  $(questionElement)
    .find('landscape')
    .each(function (landscapeIndex, landscapeElement) {
      quesLandscape_arr.push({
        category: curCategory,
        question: $(landscapeElement).find('question').text(),
        // fontSize:$(landscapeElement).find('question').attr('fontSize'),
        fontSize: defaultStyle.question.landscape.fontSize,
        // lineHeight:$(landscapeElement).find('question').attr('lineHeight'),
        lineHeight: defaultStyle.question.landscape.lineHeight,
        color: $(landscapeElement).find('question').attr('color'),
        align: $(landscapeElement).find('question').attr('align'),
        // top:$(landscapeElement).find('question').attr('top'),
        top: defaultStyle.question.landscape.top,
        // left:$(landscapeElement).find('question').attr('left'),
        left: defaultStyle.question.landscape.left,
        // width:$(landscapeElement).find('question').attr('width'),
        width: defaultStyle.question.landscape.width,
        // height:$(landscapeElement).find('question').attr('height'),
        height: defaultStyle.question.landscape.height,
        type: $(landscapeElement).find('question').attr('type'),
        correctAnswer: $(landscapeElement)
          .find('answers')
          .attr('correctAnswer'),
        drag: $(landscapeElement).find('answers').attr('drag'),
        groups: [],
        videos: [],
        answer: [],
        input: [],
        audio: $(landscapeElement).find('question').attr('audio'),
        explanation: $(landscapeElement).find('explanation').text(),
        explanationFontSize: $(landscapeElement)
          .find('explanation')
          .attr('fontSize'),
        explanationLineHeight: $(landscapeElement)
          .find('explanation')
          .attr('lineHeight'),
        explanationColor: $(landscapeElement).find('explanation').attr('color'),
        explanationAlign: $(landscapeElement).find('explanation').attr('align'),
        explanationTop: $(landscapeElement).find('explanation').attr('top'),
        explanationLeft: $(landscapeElement).find('explanation').attr('left'),
        explanationWidth: $(landscapeElement).find('explanation').attr('width'),
        explanationHeight: $(landscapeElement)
          .find('explanation')
          .attr('height'),
        explanationType: $(landscapeElement).find('explanation').attr('type'),
        explanationAudio: $(landscapeElement).find('explanation').attr('audio'),
        background: $(landscapeElement).find('background').text(),
        backgroundTop: $(landscapeElement).find('background').attr('top'),
        backgroundLeft: $(landscapeElement).find('background').attr('left'),
        backgroundWidth: $(landscapeElement).find('background').attr('width'),
        backgroundHeight: $(landscapeElement).find('background').attr('height'),
      });

      $(landscapeElement)
        .find('videos')
        .each(function (videosIndex, videosElement) {
          quesLandscape_arr[questionIndex].videos.push({
            width: $(videosElement).attr('width'),
            height: $(videosElement).attr('height'),
            top: $(videosElement).attr('top'),
            left: $(videosElement).attr('left'),
            autoplay: $(videosElement).attr('autoplay'),
            controls: $(videosElement).attr('controls'),
            embed: $(videosElement).attr('embed'),
            types: [],
          });

          $(videosElement)
            .find('video')
            .each(function (videoIndex, videoElement) {
              quesLandscape_arr[questionIndex].videos[videosIndex].types.push({
                src: $(videoElement).text(),
                type: $(videoElement).attr('type'),
              });
            });
        });

      indexAnswer = 0;
      $(landscapeElement)
        .find('answers answer')
        .each(function (answerIndex, answerElement) {
          quesLandscape_arr[questionIndex].answer.push({
            text: $(answerElement).text(),
            submit: $(answerElement).attr('submit'),
            type: $(answerElement).attr('type'),
            // width:$(answerElement).attr('width'),
            width: defaultStyle.answer.landscape[indexAnswer].width,
            // height:$(answerElement).attr('height'),
            height: defaultStyle.answer.landscape[indexAnswer].height,
            // top:$(answerElement).attr('top'),
            top: defaultStyle.answer.landscape[indexAnswer].top,
            // left:$(answerElement).attr('left'),
            left: defaultStyle.answer.landscape[indexAnswer].left,
            // fontSize:$(answerElement).attr('fontSize'),
            fontSize: defaultStyle.answer.landscape[indexAnswer].fontSize,
            // lineHeight:$(answerElement).attr('lineHeight'),
            lineHeight: defaultStyle.answer.landscape[indexAnswer].lineHeight,
            color: $(answerElement).attr('color'),
            align: $(answerElement).attr('align'),
            audio: $(answerElement).attr('audio'),
            offsetTop: $(answerElement).attr('offsetTop'),

            dropLabelText: $(answerElement).attr('dropLabelText'),
            dropLabelType: $(answerElement).attr('dropLabelType'),
            dropLabelWidth: $(answerElement).attr('dropLabelWidth'),
            dropLabelHeight: $(answerElement).attr('dropLabelHeight'),
            dropLabelTop: $(answerElement).attr('dropLabelTop'),
            dropLabelLeft: $(answerElement).attr('dropLabelLeft'),
            dropLabelFontSize: $(answerElement).attr('dropLabelFontSize'),
            dropLabelLineHeight: $(answerElement).attr('dropLabelLineHeight'),
            dropLabelColor: $(answerElement).attr('dropLabelColor'),
            dropLabelAlign: $(answerElement).attr('dropLabelAlign'),
            dropLabelOffsetTop: $(answerElement).attr('dropLabelOffsetTop'),

            dragEnable: $(answerElement).attr('dragEnable'),
            dropEnable: $(answerElement).attr('dropEnable'),
            dropLeft: $(answerElement).attr('dropLeft'),
            dropTop: $(answerElement).attr('dropTop'),
            dropWidth: $(answerElement).attr('dropWidth'),
            dropHeight: $(answerElement).attr('dropHeight'),
            dropOffLeft: $(answerElement).attr('dropOffLeft'),
            dropOffTop: $(answerElement).attr('dropOffTop'),
          });

          indexAnswer++;
        });

      $(landscapeElement)
        .find('inputs input')
        .each(function (inputIndex, inputElement) {
          quesLandscape_arr[questionIndex].input.push({
            text: $(inputElement).text(),
            submit: $(inputElement).attr('submit'),
            type: $(inputElement).attr('type'),
            width: $(inputElement).attr('width'),
            height: $(inputElement).attr('height'),
            top: $(inputElement).attr('top'),
            left: $(inputElement).attr('left'),
            fontSize: $(inputElement).attr('fontSize'),
            lineHeight: $(inputElement).attr('lineHeight'),
            correctAnswer: $(inputElement).attr('correctAnswer'),
            color: $(inputElement).attr('color'),
            bacgkround: $(inputElement).attr('bacgkround'),
            align: $(inputElement).attr('align'),
            audio: $(inputElement).attr('audio'),
            offsetTop: $(inputElement).attr('offsetTop'),
          });
        });

      $(landscapeElement)
        .find('groups group')
        .each(function (groupIndex, groupElement) {
          quesLandscape_arr[questionIndex].groups.push({
            text: $(groupElement).text(),
            type: $(groupElement).attr('type'),
            width: $(groupElement).attr('width'),
            height: $(groupElement).attr('height'),
            top: $(groupElement).attr('top'),
            left: $(groupElement).attr('left'),
            fontSize: $(groupElement).attr('fontSize'),
            lineHeight: $(groupElement).attr('lineHeight'),
            color: $(groupElement).attr('color'),
            align: $(groupElement).attr('align'),
            offsetTop: $(groupElement).attr('offsetTop'),
            correctAnswer: $(groupElement).attr('correctAnswer'),
            dropMax: $(groupElement).attr('dropMax'),
            dropWidth: $(groupElement).attr('dropWidth'),
            dropHeight: $(groupElement).attr('dropHeight'),
            dropTop: $(groupElement).attr('dropTop'),
            dropLeft: $(groupElement).attr('dropLeft'),
            dropOffLeft: $(groupElement).attr('dropOffLeft'),
            dropOffTop: $(groupElement).attr('dropOffTop'),
            audio: $(groupElement).attr('audio'),
          });
        });
    });

  //portrait
  $(questionElement)
    .find('portrait')
    .each(function (portraitIndex, portraitElement) {
      quesPortrait_arr.push({
        category: curCategory,
        question: $(portraitElement).find('question').text(),
        // fontSize:$(portraitElement).find('question').attr('fontSize'),
        fontSize: defaultStyle.question.portrait.fontSize,
        // lineHeight:$(portraitElement).find('question').attr('lineHeight'),
        lineHeight: defaultStyle.question.portrait.lineHeight,
        align: $(portraitElement).find('question').attr('align'),
        // top:$(portraitElement).find('question').attr('top'),
        top: defaultStyle.question.portrait.top,
        // left:$(portraitElement).find('question').attr('left'),
        left: defaultStyle.question.portrait.left,
        // width:$(portraitElement).find('question').attr('width'),
        width: defaultStyle.question.portrait.width,
        // height:$(portraitElement).find('question').attr('height'),
        height: defaultStyle.question.portrait.height,
        type: $(portraitElement).find('question').attr('type'),
        correctAnswer: $(portraitElement).find('answers').attr('correctAnswer'),
        drag: $(portraitElement).find('answers').attr('drag'),
        color: $(portraitElement).find('answers').attr('color'),
        groups: [],
        videos: [],
        answer: [],
        input: [],
        audio: $(portraitElement).find('question').attr('audio'),
        explanation: $(portraitElement).find('explanation').text(),
        explanationFontSize: $(portraitElement)
          .find('explanation')
          .attr('fontSize'),
        explanationLineHeight: $(portraitElement)
          .find('explanation')
          .attr('lineHeight'),
        explanationColor: $(portraitElement).find('explanation').attr('color'),
        explanationAlign: $(portraitElement).find('explanation').attr('align'),
        explanationTop: $(portraitElement).find('explanation').attr('top'),
        explanationLeft: $(portraitElement).find('explanation').attr('left'),
        explanationWidth: $(portraitElement).find('explanation').attr('width'),
        explanationHeight: $(portraitElement)
          .find('explanation')
          .attr('height'),
        explanationType: $(portraitElement).find('explanation').attr('type'),
        explanationAudio: $(portraitElement).find('explanation').attr('audio'),
        background: $(portraitElement).find('background').text(),
        backgroundTop: $(portraitElement).find('background').attr('top'),
        backgroundLeft: $(portraitElement).find('background').attr('left'),
        backgroundWidth: $(portraitElement).find('background').attr('width'),
        backgroundHeight: $(portraitElement).find('background').attr('height'),
      });

      $(portraitElement)
        .find('videos')
        .each(function (videosIndex, videosElement) {
          quesPortrait_arr[questionIndex].videos.push({
            width: $(videosElement).attr('width'),
            height: $(videosElement).attr('height'),
            top: $(videosElement).attr('top'),
            left: $(videosElement).attr('left'),
            autoplay: $(videosElement).attr('autoplay'),
            controls: $(videosElement).attr('controls'),
            embed: $(videosElement).attr('embed'),
            types: [],
          });

          $(videosElement)
            .find('video')
            .each(function (videoIndex, videoElement) {
              quesPortrait_arr[questionIndex].videos[videosIndex].types.push({
                src: $(videoElement).text(),
                type: $(videoElement).attr('type'),
              });
            });
        });

      indexAnswer = 0;
      $(portraitElement)
        .find('answers answer')
        .each(function (answerIndex, answerElement) {
          quesPortrait_arr[questionIndex].answer.push({
            text: $(answerElement).text(),
            submit: $(answerElement).attr('submit'),
            type: $(answerElement).attr('type'),
            // width:$(answerElement).attr('width'),
            width: defaultStyle.answer.portrait[indexAnswer].width,
            // height:$(answerElement).attr('height'),
            height: defaultStyle.answer.portrait[indexAnswer].height,
            // top:$(answerElement).attr('top'),
            top: defaultStyle.answer.portrait[indexAnswer].top,
            // left:$(answerElement).attr('left'),
            left: defaultStyle.answer.portrait[indexAnswer].left,
            // fontSize:$(answerElement).attr('fontSize'),
            fontSize: defaultStyle.answer.landscape[indexAnswer].fontSize,
            // lineHeight:$(answerElement).attr('lineHeight'),
            lineHeight: defaultStyle.answer.landscape[indexAnswer].lineHeight,
            color: $(answerElement).attr('color'),
            align: $(answerElement).attr('align'),
            audio: $(answerElement).attr('audio'),
            offsetTop: $(answerElement).attr('offsetTop'),

            dropLabelText: $(answerElement).attr('dropLabelText'),
            dropLabelType: $(answerElement).attr('dropLabelType'),
            dropLabelWidth: $(answerElement).attr('dropLabelWidth'),
            dropLabelHeight: $(answerElement).attr('dropLabelHeight'),
            dropLabelTop: $(answerElement).attr('dropLabelTop'),
            dropLabelLeft: $(answerElement).attr('dropLabelLeft'),
            dropLabelFontSize: $(answerElement).attr('dropLabelFontSize'),
            dropLabelLineHeight: $(answerElement).attr('dropLabelLineHeight'),
            dropLabelColor: $(answerElement).attr('dropLabelColor'),
            dropLabelAlign: $(answerElement).attr('dropLabelAlign'),
            dropLabelOffsetTop: $(answerElement).attr('dropLabelOffsetTop'),

            dragEnable: $(answerElement).attr('dragEnable'),
            dropEnable: $(answerElement).attr('dropEnable'),
            dropLeft: $(answerElement).attr('dropLeft'),
            dropTop: $(answerElement).attr('dropTop'),
            dropWidth: $(answerElement).attr('dropWidth'),
            dropHeight: $(answerElement).attr('dropHeight'),
            dropOffLeft: $(answerElement).attr('dropOffLeft'),
            dropOffTop: $(answerElement).attr('dropOffTop'),
          });

          indexAnswer++;
        });

      $(portraitElement)
        .find('inputs input')
        .each(function (inputIndex, inputElement) {
          quesPortrait_arr[questionIndex].input.push({
            text: $(inputElement).text(),
            submit: $(inputElement).attr('submit'),
            type: $(inputElement).attr('type'),
            width: $(inputElement).attr('width'),
            height: $(inputElement).attr('height'),
            top: $(inputElement).attr('top'),
            left: $(inputElement).attr('left'),
            fontSize: $(inputElement).attr('fontSize'),
            lineHeight: $(inputElement).attr('lineHeight'),
            correctAnswer: $(inputElement).attr('correctAnswer'),
            color: $(inputElement).attr('color'),
            bacgkround: $(inputElement).attr('bacgkround'),
            align: $(inputElement).attr('align'),
            audio: $(inputElement).attr('audio'),
            offsetTop: $(inputElement).attr('offsetTop'),
          });
        });

      $(portraitElement)
        .find('groups group')
        .each(function (groupIndex, groupElement) {
          quesPortrait_arr[questionIndex].groups.push({
            text: $(groupElement).text(),
            type: $(groupElement).attr('type'),
            width: $(groupElement).attr('width'),
            height: $(groupElement).attr('height'),
            top: $(groupElement).attr('top'),
            left: $(groupElement).attr('left'),
            fontSize: $(groupElement).attr('fontSize'),
            lineHeight: $(groupElement).attr('lineHeight'),
            color: $(groupElement).attr('color'),
            align: $(groupElement).attr('align'),
            offsetTop: $(groupElement).attr('offsetTop'),
            correctAnswer: $(groupElement).attr('correctAnswer'),
            dropMax: $(groupElement).attr('dropMax'),
            dropWidth: $(groupElement).attr('dropWidth'),
            dropHeight: $(groupElement).attr('dropHeight'),
            dropTop: $(groupElement).attr('dropTop'),
            dropLeft: $(groupElement).attr('dropLeft'),
            dropOffLeft: $(groupElement).attr('dropOffLeft'),
            dropOffTop: $(groupElement).attr('dropOffTop'),
            audio: $(groupElement).attr('audio'),
          });
        });
    });
}

function checkBoolean(value) {
  if (value == undefined) {
    return true;
  } else {
    if (value == 'true') {
      return true;
    } else {
      return false;
    }
  }
}

function loadXMLComplete() {
  $('.preloadText').hide();
  $('#buttonStart').show();

  gameData.targetArray = quesLandscape_arr;
  if (gameData.targetArray.length != 0) {
    gameData.category_arr = unique(gameData.category_arr);
    gameData.category_arr.sort();
    if (categoryAllOption) {
      gameData.category_arr.push(categoryAllText);
    }
  }

  // if (categoryPage) {
  //   buildCategory();
  // }

  if ($.editor.enable) {
    loadEditPage();
    goPage('game');
  } else {
    goPage('main');
  }
}

/*!
 *
 * QUESTION AND ANSWER IMAGE PRELOADER - This is the function that runs to preload question/answer image
 *
 */
var imageLoader, fileFest;
function loadQuestionAssets() {
  imageLoader = new createjs.LoadQueue(false);
  createjs.Sound.alternateExtensions = ['mp3'];
  imageLoader.installPlugin(createjs.Sound);

  imageLoader.addEventListener('complete', handleImageComplete);
  imageLoader.loadManifest(fileFest);
}

function handleImageComplete() {
  buildQuestion();
}

/*!
 *
 * RESIZE GAME - This is the function that runs to resize game
 *
 */
function resizeGameDetail() {
  if (gameData.mode != gameData.oldMode) {
    gameData.oldMode = gameData.mode;
    if (gameData.build && gameData.page == 'game') {
      buildQuestion();
    }
  }

  var curHoldeW = $('#mainHolder').outerWidth();
  if (gameData.mode == 'portrait') {
    resetCategory();
    $('.fontPreload').attr('data-fontSize', 50);
    $('.fontPreload').attr('data-lineHeight', 50);
    $('.fontCategory').attr('data-fontSize', 16);
    $('.fontCategory').attr('data-lineHeight', 16);

    $('.gameQuestionStatus').attr('data-fontSize', 20);
    $('.gameQuestionStatus').attr('data-lineHeight', 20);
    $('.gameTimerStatus').attr('data-fontSize', 20);
    $('.gameTimerStatus').attr('data-lineHeight', 20);

    $('.fontResultScore').attr('data-fontSize', 40);
    $('.fontResultScore').attr('data-lineHeight', 40);
    $('.fontShare').attr('data-fontSize', 25);
    $('.fontShare').attr('data-lineHeight', 25);

    $('.fontMessage').attr('data-fontSize', 25);
    $('.fontMessage').attr('data-lineHeight', 25);

    $('.fontScoreTitle').attr('data-fontSize', 25);
    $('.fontScoreTitle').attr('data-lineHeight', 25);
    $('.fontSubmitTitle').attr('data-fontSize', 25);
    $('.fontSubmitTitle').attr('data-lineHeight', 25);
    $('.fontScoreList').attr('data-fontSize', 15);
    $('.fontScoreList').attr('data-lineHeight', 15);

    $('.fontLabel').attr('data-fontSize', 20);
    $('.fontLabel').attr('data-lineHeight', 20);
    $('.fontInput').attr('data-fontSize', 20);
    $('.fontInput').attr('data-lineHeight', 20);
  } else {
    resetCategory();
    $('.fontPreload').attr('data-fontSize', 60);
    $('.fontPreload').attr('data-lineHeight', 60);
    $('.fontCategory').attr('data-fontSize', 20);
    $('.fontCategory').attr('data-lineHeight', 20);

    $('.gameQuestionStatus').attr('data-fontSize', 30);
    $('.gameQuestionStatus').attr('data-lineHeight', 30);
    $('.gameTimerStatus').attr('data-fontSize', 30);
    $('.gameTimerStatus').attr('data-lineHeight', 30);
    $('.fontResultScore').attr('data-fontSize', 50);
    $('.fontResultScore').attr('data-lineHeight', 50);
    $('.fontShare').attr('data-fontSize', 30);
    $('.fontShare').attr('data-lineHeight', 30);

    $('.fontMessage').attr('data-fontSize', 30);
    $('.fontMessage').attr('data-lineHeight', 30);

    $('.fontScoreTitle').attr('data-fontSize', 50);
    $('.fontScoreTitle').attr('data-lineHeight', 50);
    $('.fontSubmitTitle').attr('data-fontSize', 50);
    $('.fontSubmitTitle').attr('data-lineHeight', 50);
    $('.fontScoreList').attr('data-fontSize', 20);
    $('.fontScoreList').attr('data-lineHeight', 20);

    $('.fontLabel').attr('data-fontSize', 30);
    $('.fontLabel').attr('data-lineHeight', 30);
    $('.fontInput').attr('data-fontSize', 30);
    $('.fontInput').attr('data-lineHeight', 30);
  }

  $('.resizeFont').each(function (index, element) {
    $(this).css(
      'font-size',
      Math.round(Number($(this).attr('data-fontSize')) * scalePercent) + 'px'
    );
    $(this).css(
      'line-height',
      Math.round(Number($(this).attr('data-lineHeight')) * scalePercent) + 'px'
    );
  });

  $('.resizeBorder').each(function (index, element) {
    var borderNumber = Number($(this).attr('data-border'));
    var scaleNum = Number($('#questionHolder').outerWidth() / stageW);
    borderNumber = borderNumber * scaleNum;
    $(this).css(
      'border-radius',
      borderNumber +
        'px ' +
        borderNumber +
        'px ' +
        borderNumber +
        'px ' +
        borderNumber +
        'px'
    );
    $(this).css(
      '-moz-border-radius',
      borderNumber +
        'px ' +
        borderNumber +
        'px ' +
        borderNumber +
        'px ' +
        borderNumber +
        'px'
    );
    $(this).css(
      '-webkit-border-radius',
      borderNumber +
        'px ' +
        borderNumber +
        'px ' +
        borderNumber +
        'px ' +
        borderNumber +
        'px'
    );
  });
}

/*!
 *
 * MILLISECONDS CONVERT - This is the function that runs to convert milliseconds to time
 *
 */
function millisecondsToTime(milli) {
  var milliseconds = milli % 1000;
  var seconds = Math.floor((milli / 1000) % 60);
  var minutes = Math.floor((milli / (60 * 1000)) % 60);
  var hours = Math.floor((milli / (60 * (60 * 1000))) % 60);

  if (seconds < 10) {
    seconds = '0' + seconds;
  }

  if (minutes < 10) {
    minutes = '0' + minutes;
  }

  if (hours < 10) {
    hours = '0' + hours;
  }

  return hours + ':' + minutes + ':' + seconds;
}

/*!
 *
 * TOGGLE CONFIRM - This is the function that runs to toggle confirm exit
 *
 */
function toggleConfirm(con) {
  if (con) {
    $('#confirmHolder').show();
  } else {
    $('#confirmHolder').hide();
  }
}

/*!
 *
 * OPTIONS - This is the function that runs to mute and fullscreen
 *
 */
function toggleGameOption() {
  if ($('#buttonOption').hasClass('buttonOptionOn')) {
    $('#buttonOption').removeClass('buttonOptionOn');
    $('#buttonOption').addClass('buttonOptionOff');
    $('#optionList').removeClass('d-flex');
    $('#optionList').addClass('d-none');
  } else {
    $('#buttonOption').removeClass('buttonOptionOff');
    $('#buttonOption').addClass('buttonOptionOn');
    $('#optionList').addClass('d-flex');
    $('#optionList').removeClass('d-none');
  }
}

function closeGameOption() {
  $('#buttonOption').removeClass('buttonOptionOn');
  $('#buttonOption').addClass('buttonOptionOff');
  $('#optionList').addClass('d-none').removeClass('d-flex');
}

function toggleGameMute() {
  if ($('#buttonSound').hasClass('buttonSoundOn')) {
    $('#buttonSound').removeClass('buttonSoundOn');
    $('#buttonSound').addClass('buttonSoundOff');
    $('#soundTooltip').text('Unmute');
    toggleMute(true);
  } else {
    $('#buttonSound').removeClass('buttonSoundOff');
    $('#buttonSound').addClass('buttonSoundOn');
    $('#soundTooltip').text('Mute');
    toggleMute(false);
  }
}

function toggleFullScreen() {
  if (
    !document.fullscreenElement && // alternative standard method
    !document.mozFullScreenElement &&
    !document.webkitFullscreenElement &&
    !document.msFullscreenElement
  ) {
    // current working methods
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      document.documentElement.msRequestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(
        Element.ALLOW_KEYBOARD_INPUT
      );
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}

/*!
 *
 * SHARE - This is the function that runs to open share url
 *
 */
function share(action) {
  gtag('event', 'click', { event_category: 'share', event_label: action });

  var loc = location.href;
  loc = loc.substring(0, loc.lastIndexOf('/') + 1);
  var gameLoc = loc;
  if (window.location !== window.parent.location) {
    loc = document.referrer + 'fun/games/trivia/';
  }

  var title = '';
  var text = '';

  //__info: override turn of the description for now
  if (scoreMode == 'score') {
    title = shareTitle.replace('[SCORE]', playerData.score);
    text = shareMessage.replace('[SCORE]', playerData.score + ' pts');
    text = text.replace('[URL]', loc);
  } else if (scoreMode == 'timer') {
    title = shareTitle.replace('[SCORE]', millisecondsToTime(playerData.timer));
    text = shareMessage.replace(
      '[SCORE]',
      millisecondsToTime(playerData.timer)
    );
  }

  var shareurl = '';

  if (action == 'twitter') {
    shareurl = 'https://twitter.com/intent/tweet?url=' + loc + '&text=' + text;
  } else if (action == 'facebook') {
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
  } else if (action == 'google') {
    shareurl = 'https://plus.google.com/share?url=' + loc;
  } else if (action == 'whatsapp') {
    shareurl =
      'whatsapp://send?text=' +
      encodeURIComponent(text) +
      ' - ' +
      encodeURIComponent(loc);
  }

  window.open(shareurl);
}

function shareFunfact(action) {
  gtag('event', 'click', { event_category: 'share', event_label: action });

  var loc = location.href;
  loc = loc.substring(0, loc.lastIndexOf('/') + 1);

  var slug = gameData.targetArray[gameData.sequenceNum].slug;
  var shareloc =
    'https://seniorsdiscountclub.com.au/games/trivia/fun-fact/index.php?slug=' +
    slug;

  var title = gameData.targetArray[gameData.sequenceNum].correct_answer;
  var text = gameData.targetArray[gameData.sequenceNum].info;
  var question = gameData.targetArray[gameData.sequenceNum].question;

  var shareurl = '';

  if (action == 'twitter') {
    //handle twitter character limitation
    let tweetText = question;
    if (question.length > 250) {
      // 280 - 23 (twitter alter the link to 23 chars) - 7 (safe size + '...')
      tweetText = question.substring(250, 0).concat('...');
    }

    shareurl =
      'https://twitter.com/intent/tweet?url=' + shareloc + '&text=' + tweetText;
  } else if (action == 'facebook') {
    shareurl =
      'https://www.facebook.com/sharer/sharer.php?u=' +
      encodeURIComponent(
        loc +
          'share.php?desc=' +
          text +
          ' ' +
          loc +
          '&title=' +
          title +
          '&url=' +
          shareloc
      );
  } else if (action == 'google') {
    shareurl = 'https://plus.google.com/share?url=' + loc;
  } else if (action == 'whatsapp') {
    shareurl =
      'whatsapp://send?text=' +
      encodeURIComponent(text) +
      ' - ' +
      encodeURIComponent(loc);
  }

  window.open(shareurl);
}

/*!
 *
 * SCORE - handle player score cookie & API
 *
 */

function checkUserCookie() {
  var cookie = document.cookie.split(';');

  cookie.map((ck) => {
    var item = ck.split('=');
    if (item[0].trim() == 'trivia-userId' && item[1].trim().includes(ymd)) {
      userScore.userId = item[1];
    }
  });

  if (userScore.userId == null) {
    getNewUser();
  } else {
    var resdata = {
      userId: userScore.userId.toString(),
    };

    $.ajax({
      url: './api/question/get-game-result.php',
      dataType: 'json',
      type: 'post',
      data: JSON.stringify(resdata),
    })
      .done((e) => {
        // console.log(e)
        if (e.result == null) {
          getNewUser();
        }
      })
      .error((err) => {
        console.log(err);
      });
  }

  setTimeout(() => {
    checkOnboardSetting();
  }, 1000);
}

function getNewUser() {
  $.ajax({
    url: './api/question/generate-user-id.php',
    dataType: 'json',
    type: 'get',
  })
    .done((e) => {
      setUserCookie(e.result.userId);
      userScore.userId = e.result.userId;
    })
    .error((err) => {
      console.log(err);
    });
}

const checkOnboardSetting = () => {
  let resdata = {
    user_key: userScore.userId.toString(),
    setting_param: 'onboarding_state',
  };

  $.ajax({
    url: './api/setting/get-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      // console.log(e, resdata)
      if (e.onboarding_state) {
        gameData.onBoarding = e.onboarding_state;
      } else {
        gameData.onBoarding = 0;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const setOnboardSetting = () => {
  let resdata = {
    user_key: userScore.userId.toString(),
    setting_param: 'onboarding_state',
    setting_value: 1,
  };

  $.ajax({
    url: './api/setting/set-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      // console.log(e, resdata)
      if (e.status == 'success') {
        gameData.onBoarding = 1;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
};

function setUserCookie(uid) {
  document.cookie = 'trivia-userId=' + uid + ';path=/';
}

function postGameScore() {
  var resdata = {
    score: playerData.score.toString(),
    userId: userScore.userId.toString(),
    categoryId: isRandomMode
      ? '10'
      : gameData.categoryThumb_arr[gameData.categoryNum].id.toString(),
  };

  var lastScore = userScore.scores?.find(
    (x) => x.categoryId == resdata.categoryId
  );

  if (
    (lastScore == undefined && playerData.score > 0) ||
    (lastScore != undefined && playerData.score > lastScore.score)
  ) {
    $.ajax({
      url: './api/question/set-game-result.php',
      dataType: 'json',
      type: 'post',
      data: JSON.stringify(resdata),
    })
      .done((e) => {
        // console.log(e);
      })
      .error((err) => {
        console.log(err);
      });
  }
}

function getGameScore() {
  var resdata = {
    userId: userScore.userId.toString(),
  };

  $.ajax({
    url: './api/question/get-game-result.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
  })
    .done((e) => {
      setUserScore(e.result);
    })
    .error((err) => {
      console.log(err);
    });
}

function setUserScore(result) {
  if (result.data != null) {
    userScore.scores = result.data.scores;
  } else {
    userScore.scores = null;
  }
  buildCategory();
}


// get parent url via query passed query string ?parent=
// console.log('window.location');
// console.log(decodeURIComponent(getUrlVars()['parent']));
function getUrlVars() {
  var vars = [], hash;
  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
  }
  return vars;
}