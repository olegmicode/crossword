window.$ = window.jQuery;
let ua = detect.parse(navigator.userAgent);

let windowWidth;
let windowHeight;
let iFramed = false;

let gameData = {};

// share settings
let shareTitle = 'My Highscore on Word Search Game is [SCORE] pts';
let shareMessage =
  'I just played this awesome word search game at [URL] with score [SCORE]! You should try it too! ';

// page setting
let settingOpen = false;
let checkWordMode = false;
let checkLetterMode = false;
let onBoardIndex = 0;
let onBoardModalIndex = 0;

// pages
const introPage = $('#intro');
const onboardPage = $('#onboard');
const categoryPage = $('#category');
const gamePage = $('#game');
const resultPage = $('#result');

// elements
const topBar = $('#topBar');
const progressWrapper = $('#progressWrapper');
const progressBar = $('#progressBar');
const progressPercentage = $('#progressPercentage');
const settingWrapper = $('#setting');
const settingDropdown = $('#settingDropdown');
const scoreWrapper = $('#scoreWrapper');
const scoreText = $('#scoreText');
const scoreResultText = $('#scoreResultText');
const stars = $('.rateStar');
const categoryWrapper = $('#categoryWrapper');
const todayText = $('#todayText');
const pageIndex = $('#pageIndex');
const gameBg = $('#gameBg');
const hintTextWrapper = $('#hintTextWrapper');
const hintText = $('#hintText');
const loader = $('#loader');
const wordCount = $('#wordCount span');
const categoryText = $('#categoryText');
const onboardContent = $('.onboard__content');
const onboardIndicator = $('.onboard__pagination__index');
const onboardModalContent = $('.modal--help__content');
const onboardModalIndicator = $('.modal--help__indicator__item');
const hintCountText = $('#hintCountText');

// clickable
const startButton = $('#btnStart');
const settingButton = $('#btnSetting');
const resetButton = $('#btnReset');
const resetYesButton = $('#btnResetYes');
const resetNoButton = $('#btnResetNo');
const soundButton = $('#btnSound');
const timerButton = $('#btnTimer');
const replayButton = $('#replayButton');
const undoButton = $('#btnUndo');
const prevButton = $('#btnPrevIndex');
const nextButton = $('#btnNextIndex');
const checkWordButton = $('#btnCheckWord');
const checkLetterButton = $('#btnCheckLetter');
const skipOnboardButton = $('#btnSkipOnboard');
const nextOnboardButton = $('#btnNextOnboard');
const startOnboardButton = $('#btnStartOnboard');
const helpButton = $('#helpButton');
const skipOnboardModalButton = $('#btnOnboardModalSkip');
const nextOnboardModalButton = $('#btnOnboardModalNext');
const startOnboardModalButton = $('#btnOnboardModalStart');

// modal
const modalBackdrop = $('#modalBackdrop');
const pauseModal = $('#modalPause');
const resetModal = $('#modalReset');
const onboardModal = $('#modalOnboard');

// confetti
var confetti;
var confettiElement = document.getElementById('confettiWrapper');
var confettiSettings = {
  target: confettiElement,
  size: 2,
  animate: true,
  respawn: true,
  clock: 30,
  rotate: true,
};

//disable ctrl f
$(window).keydown(function (e) {
  if ((e.ctrlKey || e.metaKey) && e.keyCode === 70) {
    e.preventDefault();
  }
});

// ready function
$(document).ready(() => {
  windowWidth = $(window).width();
  windowHeight = $(window).height();
  checkOrientation();
  if (windowWidth > 768) {
    confettiSettings.size = 2;
  } else {
    confettiSettings.size = 1;
  }
  var confetti = new ConfettiGenerator(confettiSettings);
  goPage('intro');
  confetti.render();
  todayText.html(moment().format('dddd, DD MMMM YYYY'));
});

function checkIframe() {
  if (window.location !== window.parent.location) {
    iFramed = true;
  }

  console.log('iframe mode: ' + iFramed);
}

function checkOrientation() {
  checkIframe();
  console.log('checking orientation');
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

// resize function
$(window).on('resize', function () {
  ua = detect.parse(navigator.userAgent);
  windowWidth = $(window).width();
  windowHeight = $(window).height();
  checkOrientation();
  if (windowWidth < windowHeight) {
    resetBoardSize();
    board.css('opacity', 0);
    setTimeout(() => {
      resizeBoard();

      board.css('opacity', 1);
    }, 1000);
  }

  // pageIndexSetting();
});

const showOnboard = (index) => {
  hide(onboardContent);
  show(onboardContent[index]);
  onboardIndicator.removeClass('active');
  onboardIndicator.eq(index).addClass('active');
  // onboardIndicator.index(index).addClass('active');
  if (index == onboardContent.length - 1) {
    skipOnboardButton.hide();
    nextOnboardButton.hide();
    startOnboardButton.show();
  } else {
    startOnboardButton.hide();
    skipOnboardButton.show();
    nextOnboardButton.show();
  }
};

const showOnboardModal = (index) => {
  pauseTimer();
  hide(onboardModalContent);
  show(onboardModalContent[index]);
  showModal('onBoard');
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

// route function
const goPage = (page) => {
  $('#confettiWrapper').hide();
  closeSetting();
  hideAllPages();
  timerButton.addClass('hide');
  startButton.hide();
  progressWrapper.hide();
  settingWrapper.hide();
  progressBar.hide();
  scoreWrapper.hide();
  resetButton.hide();
  pageIndex.hide();
  // pageIndexSetting();
  gameWrapper.addClass('hide');
  gameBg.hide();
  hintTextWrapper.hide();

  switch (page) {
    case 'intro':
      show(introPage);
      initPreload();
      progressWrapper.show();
      progressBar.show();
      break;

    // case "onboard":
    //   showOnboard(0);
    //   settingWrapper.show();
    //   show(topBar);
    //   show(onboardPage)
    //   break;

    case 'category':
      categoryWrapper.empty();
      getScore();
      show(topBar);
      show(categoryPage);
      settingWrapper.show();
      pageIndex.show();
      break;

    case 'game':
      show(topBar);
      show(gamePage);
      settingWrapper.show();
      resetButton.show();
      scoreWrapper.show();
      gameBg.show();
      break;

    case 'result':
      // resultPage.show();
      show(resultPage);
      show(topBar);
      break;

    default:
      break;
  }
};

const checkOnboardSetting = () => {
  let resdata = {
    user_key: gameData.userId,
    setting_param: 'onboarding_state',
  };

  $.ajax({
    url: './api/setting/get-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log('get setting', e);
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
    user_key: gameData.userId,
    setting_param: 'onboarding_state',
    setting_value: 1,
  };

  $.ajax({
    url: './api/setting/set-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      // console.log('set setting', e)
      if (e.status == 'success') {
        gameData.onBoarding = 1;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
};

// main game function
const startGame = (catId, category = null) => {
  gameData.categoryId = catId;
  if(category != null){
    gameData.checkWordCount = parseInt(category.hint);
    gameData.checkLetterCount = parseInt(category.hint);
    gameData.boardSize = category.size;
    gameData.timeInterval = category.time_interval;
    gameData.pointDeduction = category.point_deduction;
    gameData.initPoint = category.initial_score;
    hintCountText.text(category.hint);
    categoryText.text(category.name);
  }else {
    removePermalinkParam();
    let newPermalinkParam = categoriesData.find((x) => x.id == catId).permalink;
    if(newPermalinkParam && newPermalinkParam != ""){
      window.history.pushState({}, null, "?l=" + newPermalinkParam);
    }
    
    gameData.checkWordCount = parseInt(
      categoriesData.find((x) => x.id == catId).hint
    );
    gameData.checkLetterCount = parseInt(
      categoriesData.find((x) => x.id == catId).hint
    );
    gameData.boardSize = categoriesData.find((x) => x.id == catId).size;
    gameData.timeInterval = categoriesData.find(
      (x) => x.id == catId
    ).time_interval;
    gameData.pointDeduction = parseInt(
      categoriesData.find((x) => x.id == catId).point_deduction
    );
    gameData.initPoint = parseInt(
      categoriesData.find((x) => x.id == catId).initial_score
    );
    hintCountText.text(categoriesData.find((x) => x.id == catId).hint);
    categoryText.text(categoriesData.find((x) => x.id == catId).name);
  }
  setHintCount();
  getQuestion();
  resetBoardSize();
  resetScore();
  hide(loader);
  if (gameData.onBoarding == 1) {
    startTimer();
  } else {
    showOnboardModal(0);
  }
};

const setHintCount = () => {
  $(checkWordButton).find('span').text(gameData.checkWordCount);
  $(checkLetterButton).find('span').text(gameData.checkLetterCount);
};

const stopGame = () => {
  closeModal();
  stopTimer();
  goPage('result');
  let curScore = scoreData.find((x) => x.levelId == gameData.categoryId)?.score;
  if (curScore) {
    if (gameData.score > curScore) {
      postScore();
    }
  } else {
    if (gameData.score > 0) {
      postScore();
    }
  }

  countResultScore();
};

const pageIndexSetting = () => {
  let availHeight = screen.availHeight;
  if (ua.device.family == 'iPhone' || ua.device.family == 'iPad') {
    pageIndex.css('bottom', windowHeight - availHeight);
  }

  // console.log(availHeight)
};

const getQuestion = () => {
  let resdata = {
    level_id: gameData.categoryId,
  };

  $.ajax({
    url:
    'https://seniorsdiscountclub.com.au/games/wordsearch/api/word/words-set.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      gameData.words = e.records;
      initBoard();
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const countResultScore = () => {
  scoreResultText.html(gameData.score);
  // showRateStar();
  let scoreStep = scoresData.map((x) => x.minScore);

  // if (gameData.score >= Math.min(...scoreStep)) {
  if (gameData.score > 0) {
    setTimeout(() => {
      playSound('soundCount');
    }, 500);
    $({ Counter: 0 }).animate(
      {
        Counter: gameData.score,
      },
      {
        duration: 2000,
        easing: 'swing',
        step: function () {
          scoreResultText.html(Math.ceil(this.Counter));
        },
        complete: function () {
          stopSound();
          setTimeout(() => {
            playSound('soundSuccess');
            $('#confettiWrapper').show();
          }, 500);
        },
      }
    );
  } else {
    setTimeout(() => {
      playSound('soundFail');
    }, 500);
  }
};

const showRateStar = () => {
  var shine = './img/icon/result-star-shine-icon.svg';
  for (let index = 0; index < scoresData.length; index++) {
    if (gameData.score >= scoresData[index].minScore) {
      stars.each((i, el) => {
        if (i < scoresData[index].star)
          $(el)
            .delay(300 * (i + 1))
            .queue(() => {
              $(el).attr('src', shine);
              $(el).addClass('bounce').dequeue();
            });
      });
    }
  }
};

const resetStars = () => {
  let dim = './img/icon/result-star-icon.svg';
  stars.each((i, el) => {
    $(el).attr('src', dim);
    $(el).removeClass('bounce');
  });
};

const resetGame = () => {
  stopTimer();
  closeModal();
  resetScore();
  resetStars();
};

// setting button - dropdown toggle function
const toggleSetting = () => {
  if (settingOpen) {
    closeSetting();
  } else {
    openSetting();
  }
};

const closeSetting = () => {
  settingButton.find('img').attr('src', './img/icon/cog-icon.svg');
  settingDropdown.hide();
  settingOpen = false;
};

const openSetting = () => {
  settingButton.find('img').attr('src', './img/icon/close-icon.svg');
  settingDropdown.show();
  settingOpen = true;
};

// show / hide pages
const hideAllPages = () => {
  hide(introPage);
  hide(onboardPage);
  hide(categoryPage);
  hide(gamePage);
  hide(resultPage);
  hide(topBar);
};

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

// pause / resume game
const pauseGame = () => {
  showModal('pause');
  pauseTimer();
};

const resumeGame = () => {
  closeModal();
  startTimer();
};

const confirmReset = () => {
  pauseTimer();
  showModal('reset');
};

// show / hide modal function
const showModal = (modalName) => {
  modalBackdrop.show();
  switch (modalName) {
    case 'pause':
      pauseModal.show();
      break;

    case 'reset':
      resetModal.show();
      break;

    case 'onBoard':
      onboardModal.show();
      break;

    default:
      break;
  }
};

const closeModal = () => {
  modalBackdrop.hide();
  $('.modal').hide();
};

// share function
const share = (action) => {
  var loc = location.href;
  loc = loc.substring(0, loc.lastIndexOf('/') + 1);
  var gameLoc = loc;
  if (window.location !== window.parent.location) {
    loc = document.referrer + 'fun/games/trivia/';
  }

  let title = shareTitle.replace('[SCORE]', gameData.score);

  let text = shareMessage.replace('[SCORE]', gameData.score + ' pts');
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

const removePermalinkParam = () => {
  let urlParams = new URLSearchParams(window.location.search);
  let permalinkParam = urlParams.get('l');
  if(permalinkParam){
    let currentURL = window.location.href.replace("?l="+permalinkParam, "");
    window.history.pushState({}, null, currentURL);
  }
}