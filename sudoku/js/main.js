let windowWidth;

// page setting
let settingOpen = false;
let onBoardModalIndex = 0;
let gameData = {};
let onBoarding = 0;

// pages
const introPage = $("#intro");
const levelPage = $("#level");
const gamePage = $("#game");
const resultPage = $("#result");

// elements
const topBar = $("#topBar");
const progressWrapper = $("#progressWrapper");
const progressBar = $("#progressBar");
const progressPercentage = $("#progressPercentage");
const settingWrapper = $("#setting");
const settingDropdown = $("#settingDropdown");
const scoreWrapper = $("#scoreWrapper");
const scoreText = $("#scoreText");
const scoreResultText = $("#scoreResultText");
const stars = $(".rateStar");
const levelWrapper = $("#levelWrapper");
const todayText = $("#todayText");
const noteIndicator = $("#noteIndicator");
const onboardModalContent = $(".modal--help__content");
const onboardModalIndicator = $(".modal--help__indicator__item");

// clickable
const startButton = $("#btnStart");
const settingButton = $("#btnSetting");
const resetButton = $("#btnReset");
const resetYesButton = $("#btnResetYes");
const resetNoButton = $("#btnResetNo");
const soundButton = $("#btnSound");
const timerButton = $("#btnTimer");
const replayButton = $("#replayButton");
const undoButton = $("#btnUndo");
const helpButton = $("#btnHelp");
const skipOnboardModalButton = $("#btnOnboardModalSkip");
const nextOnboardModalButton = $("#btnOnboardModalNext");
const startOnboardModalButton = $("#btnOnboardModalStart");

// modal
const modalBackdrop = $("#modalBackdrop");
const pauseModal = $("#modalPause");
const resetModal = $("#modalReset");
const onboardModal = $("#modalOnboard")

// confetti
var confettiElement = document.getElementById("confettiWrapper");
var confettiSettings = {
  target: confettiElement,
  size: 2,
  animate: true,
  respawn: true,
  clock: 30,
  rotate: true,
};
var confetti = new ConfettiGenerator(confettiSettings);

// ready function
$(document).ready(() => {
  goPage("intro");

  confetti.render();
  todayText.html(moment().format("dddd, DD MMMM YYYY"));
});

// route function
const goPage = (page) => {
  $("#confettiWrapper").hide();
  closeSetting();
  hideAllPages();
  startButton.hide();
  progressWrapper.hide();
  settingWrapper.hide();
  progressBar.hide();
  scoreWrapper.hide();
  resetButton.hide();
   $(".container-fluid").css('max-width','unset');

  switch (page) {
    case "intro":
      show(introPage);
      initPreload();
      progressWrapper.show();
      progressBar.show();
      break;

    case "level":
      getScore(userId);
      show(topBar);
      show(levelPage);
      settingWrapper.show();
      break;

    case "game":
      $(".container-fluid").css('max-width','1024px');
      show(topBar);
      show(gamePage);
      settingWrapper.show();
      resetButton.show();
      scoreWrapper.show();
      break;

    case "result":
      // resultPage.show();
      show(resultPage);
      topBar.show();
      break;

    default:
      break;
  }
};

// start Game
const startGame = (level) => {
  gameLevel = level;
  fetchGrid();
  resetScore();

};

const stopGame = () => {
  closeModal();
  stopTimer();
  goPage("result");
  let curScore = scoreData.find((x) => x.levelId == gameLevel)?.score;
  if (curScore) {
    if (score > curScore) {
      postScore();
    }
  } else if (score > 0) {
    postScore();
  }
  countResultScore();
};

const countResultScore = () => {

  scoreResultText.html(score);
  // let scoreStep = scoresData.map((x) => x.minScore);

  let scoreStep = scoresData.map((x) => x.minScore);

  if (score > 0) {
    playSound("soundCount");
    $({ Counter: 0 }).animate(
      {
        Counter: score,
      },
      {
        duration: 2000,
        easing: "swing",
        step: function () {
          scoreResultText.html(Math.ceil(this.Counter));
        },
        complete: function () {
          stopSound();
          setTimeout(() => {
            playSound("soundSuccess");
            $("#confettiWrapper").show();
          }, 500);
        },
      }
    );
  } else {
    setTimeout(() => {
      playSound("soundFail");
    // $("#confettiWrapper").show();
    }, 500);

  }
};

const showRateStar = () => {
  var shine = "./img/icon/result-star-shine-icon.svg";
  for (let index = 0; index < scoresData.length; index++) {
    if (score >= scoresData[index].minScore) {
      stars.each((i, el) => {
        if (i < scoresData[index].star)
          $(el)
            .delay(300 * (i + 1))
            .queue(() => {
              $(el).attr("src", shine);
              $(el).addClass("bounce").dequeue();
            });
      });
    }
  }
};

const resetStars = () => {
  let dim = "./img/icon/result-star-icon.svg";
  stars.each((i, el) => {
    $(el).attr("src", dim);
    $(el).removeClass("bounce");
  });
};

const resetGame = () => {
  board.empty();
  gameHistory = [];
  stopTimer();
  closeModal();
  resetScore();
  // resetStars();
};

const restartGame = () => {
  gameHistory = [];
  resetScore();
  stopTimer();
  startTimer();
  closeModal();
  resetBoard();
};

// pause Game
const pauseGame = () => {
  showModal("pause");
  pauseTimer();
};

const resumeGame = () => {
  closeModal();
  startTimer();
};

const confirmReset = () => {
  pauseTimer();
  showModal("reset");
};

const showOnboardModal = (index) => {
  console.log('show')
  pauseTimer();
  hide(onboardModalContent);
  show(onboardModalContent[index]);
  showModal("onBoard");
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
}

// show / hide modal function
const showModal = (modalName) => {
  modalBackdrop.show();
  switch (modalName) {
    case "pause":
      pauseModal.show();
      break;

    case "reset":
      resetModal.show();
      break;

    case "onBoard":
       onboardModal.show();
      break;

    default:
      break;
  }
};

const closeModal = () => {
  modalBackdrop.hide();
  $(".modal").hide();
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
  settingButton.find("img").attr("src", "./img/icon/cog-icon.svg");
  settingDropdown.hide();
  settingOpen = false;
};

const openSetting = () => {
  settingButton.find("img").attr("src", "./img/icon/close-icon.svg");
  settingDropdown.show();
  settingOpen = true;
};

const initLevels = () => {
  levelWrapper.empty();
  for (let index = 0; index < gameLevelData.length; index++) {
    // setTimeout(() => {
    let lastScore = "<div></div>";
    scoreData.forEach((x) => {
      if (x.levelId == gameLevelData[index].levelId) {
        lastScore =
          '<div class="d-flex align-items-center" ><img src="./img/icon/star-icon.svg" alt="Score" class="level__thumb__star-icon mr-1" />' +
          '<span class="text-uppercase text-white text-bolder">' +
          x.score +
          " pts</span></div>";
      }
    });
    let levelThumb =
      '<div class="level__thumb level__thumb--' +
      gameLevelData[index].class +
      ' flex-column justify-content-between p-2 mb-2 clickable fadeIn" data-level="' +
      gameLevelData[index].levelId +
      '">' +
      lastScore +
      '<span class="text-uppercase text-white text-bolder">' +
      gameLevelData[index].level.toUpperCase() +
      "</span></div>";
    levelWrapper.append(levelThumb);
    // }, 300 * index);
  }
};

const fetchGrid = () => {
  let resdata = {
    level: gameLevel,
  };

  $.ajax({
    url:
      "https://seniorsdiscountclub.com.au/games/sudoku/api/question/generate-board.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
  })

    .done((e) => {
      // console.log(e);
      gameData = e.result;
      originalGameData = e.result;
      initBoard();
    })

    .error((err) => {
      console.log(err);
    });
};

const hideAllPages = () => {
  hide(introPage);
  hide(levelPage);
  hide(gamePage);
  hide(resultPage);
  hide(topBar);
};

const hide = (element) => {
  $(element).addClass("d-none");
  $(element).removeClass("d-flex");
  $(element).removeClass("fadeIn");
};

const show = (element) => {
  $(element).addClass("d-flex");
  $(element).addClass("fadeIn");
  $(element).removeClass("d-none");
};

const share = (action) => {

  let shareTitle = "My Highscore on Sudoku Game is [SCORE] pts";
  let shareMessage =
  "I just played this awesome sudoku game at [URL] with score [SCORE]! You should try it too! ";

  var loc = location.href;
  loc = loc.substring(0, loc.lastIndexOf("/") + 1);
  var gameLoc = loc;
  if (window.location !== window.parent.location) {
    loc = document.referrer + 'fun/games/trivia/';
  }

  let title = shareTitle.replace("[SCORE]", score);

  let text = shareMessage.replace("[SCORE]", score + " pts");
  text = text.replace("[URL]", loc);
  let shareUrl = "";

  switch (action) {
    case "twitter":
      shareurl =
        "https://twitter.com/intent/tweet?url=" + loc + "&text=" + text;
      break;

    case "facebook":
      shareurl =
        "https://www.facebook.com/sharer/sharer.php?u=" +
        encodeURIComponent(
          gameLoc +
            "share.php?desc=" +
            text +
            " " +
            loc +
            "&title=" +
            title +
            "&url=" +
            loc
        );

    default:
      break;
  }

  window.open(shareurl);
};

const checkOnboardSetting = () => {
  let resdata = {
    user_key: userId,
    setting_param : 'onboarding_state'
  };

  $.ajax({
    url:
      "./api/setting/get-setting.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log('get setting', e)
      if (e.onboarding_state) {
        onBoarding = e.onboarding_state;
      } else {
        onBoarding = 0;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}

const setOnboardSetting = () => {
   let resdata = {
    user_key: userId,
    setting_param : 'onboarding_state',
    setting_value: 1
  };

  $.ajax({
    url:
      "./api/setting/set-setting.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log('set setting', e)
      if (e.status == 'success') {
        onBoarding = 1;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}