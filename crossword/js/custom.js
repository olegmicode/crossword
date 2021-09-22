let windowsize = $(window).width();
windowsize >= 1024
  ? (pageData.maxData = 12)
  : windowsize >= 768
  ? (pageData.maxData = 9)
  : (pageData.maxData = 4);

let hintTimeout;

if (windowsize)
  // display category data on resize function
  $(window).resize(() => {
    ua = detect.parse(navigator.userAgent);
    windowsize = $(window).width();
    windowHeight = $(window).height();
    windowsize >= 1024
      ? (pageData.maxData = 12)
      : windowsize >= 768
      ? (pageData.maxData = 9)
      : (pageData.maxData = 4);

      //checkOrientation();
      displayLevels();

  });

$(document).ready(function () {
  $(".btn-dropdown").click(function () {
    playSound("soundClick");
    $(this).next(".btn-dropdown-child").toggleClass("dropdown-active");
    $(".btn-dropdown")
      .not(this)
      .next(".btn-dropdown-child")
      .removeClass("dropdown-active");
  });

  $(window).resize(function () {
    if (isOnTheGame) {
      setBottomInfoHeight();
    }
  });

  btnHint.on("click", function () {
    playSound("soundClick");
    if (gameData.hintCount > 0) {
      if (selectedCell) {
        revealCurrentSquare();
        gameData.hintCount--;
        $("#btn-hint span").text(gameData.hintCount);
      } else {
        bottomInfo.find(".left").html("");
        bottomInfo
          .addClass("centering")
          .find(".center")
          .html(textBottomInfoNoSelectedCell);
      }
    } else {
      $("#hintWrapper").show();
      clearTimeout(hintTimeout);

      hintTimeout = setTimeout(() => {
        $("#hintWrapper").hide();
      }, 3000);
    }
  });

  btnSolveWord.on("click", function () {
    playSound("soundClick");
    if (gameData.solveCount > 0) {
      if (selectedCell && selectedCell.correctBg.visible == false) {
        revealCurrentWord(true);
        gameData.solveCount--;
        $("#btn-solve-word span").text(gameData.solveCount);
      } else {
        bottomInfo.find(".left").html("");
        bottomInfo
          .addClass("centering")
          .find(".center")
          .html(textBottomInfoNoSelectedCell);
      }
    } else {
      $("#hintWrapper").show();
      clearTimeout(hintTimeout);
      hintTimeout = setTimeout(() => {
        $("#hintWrapper").hide();
      }, 3000);
    }
  });

  btnCheckWord.on("click", function () {
    playSound("soundClick");
    if (selectedCell) {
      checkEntireGrid();
    } else {
      bottomInfo.find(".left").html("");
      bottomInfo
        .addClass("centering")
        .find(".center")
        .html(textBottomInfoNoSelectedCell);
    }
  });

  btnPrev.on("click", function () {
    if (pageData.pageIndex > 1) {
      playSound("soundClick");
      pageData.pageIndex--;
      displayLevels();
    }
  });

  btnNext.on("click", function () {
    if (pageData.pageIndex < pageData.pageTotal) {
      playSound("soundClick");
      pageData.pageIndex++;
      displayLevels();
    }
  });

  btnContinue.on("click", function () {
    playSound("soundClick");
    pauseModal.modal("hide");
    $("#timer-tooltip").html("Pause");
    startTimeTick();
  });

  btnSurrender.on("click", function () {
    playSound("soundClick");
    pauseModal.modal("hide");
    goPage("result");
  });

  btnStartTime.on("click", function () {
    playSound("soundClick");
    startTimeTick();
    $("#timer-tooltip").html("Pause");
  });

  btnPauseTime.on("click", function () {
    playSound("soundClick");
    pauseTimeTick();
    pauseModal.modal("show");
    $("#timer-tooltip").html("Start");
  });

  $(document).on("click", ".difficulty-item", function (e) {
    // playSound("soundClick");
    var data = $(e.currentTarget);
    difficulty.id = data.data("id");
    difficulty.grid = data.data("grid");
    fetchQuestions(data.data("id"), data.data("grid"));
    gameData.levelId = data.data("id");
    gameData.boardJSON = gameLevels[data.data("index")].board_json;

    let permalink = data.data('permalink');
    if(permalink && permalink != ""){
      window.history.pushState({}, null, "?l=" + data.data('permalink'));
    }
  });

  $(document).on("click", ".reload", function (e) {
    e.preventDefault();
    pauseTimeTick();
    playSound("soundClick");
    $("#modal-reset").modal("show");
  });

  $(document).on("click", "#back-ingame", function (e) {
    e.preventDefault();
    playSound("soundClick");
    pauseTimeTick();
    $("#modal-back-ingame").modal("show");
  });

  $(document).on("click", "#back-indifficulty", function (e) {
    e.preventDefault();
    playSound("soundClick");
    $("#game_difficulty").hide();
    $("#intro").show();
  });

  $(document).on("click", "#btn-back-ingame-no", function (e) {
    e.preventDefault();
    startTimeTick();
    playSound("soundClick");
    $("#modal-back-ingame").modal("hide");
  });

  $(document).on("click", "#btn-back-ingame-yes", function (e) {
    e.preventDefault();
    playSound("soundClick");
    resetTimer();

    goPage("level");

    bottomInfo.hide();

    $("#modal-back-ingame").modal("hide");

    clearEntireGrid();
    deselectWord();
    currentCache = [];
  });

  $(document).on("click", "#btn-reset-no", function (e) {
    e.preventDefault();
    playSound("soundClick");
    startTimeTick();
    $("#modal-reset").modal("hide");
  });

  $(document).on("click", "#btn-reset-yes", function (e) {
    e.preventDefault();
    // playSound("soundClick");
    resetGame();
    // goPage("level");

    $("#modal-reset").modal("hide");
  });

  $("#bottom-info-trigger").click(function() {
    if (supportsTouch()) {
      $(this).hide();
    }
  })

  $("#buttonOption").click(function () {
    alert();
    toggleGameOption();
  });

  $("#btn-help").click(function () {
    playSound("soundClick")
    onboardIndex = 0;
    showOnboardModal(onboardIndex);
  })

  $("#btn-sound").click(function () {
    playSound("soundClick");
    isMute = !isMute;
    toggleMute(isMute);
  });

  $("#btn-sound-small").click(function () {
    playSound("soundClick");
    isMute = !isMute;
    toggleMute(isMute);
  });

  $("#btn-asc").click(function () {
    playSound("soundClick");
    // console.log("ascending");
  });

  $("#btn-desc").click(function () {
    playSound("soundClick");
    // console.log("descending");
  });

  $("#replayBtn").click(function () {
    playSound("soundClick");
    goPage("level");
  });

  $("#facebookShareBtn").click(function () {
    share("facebook");
  });

  $("#twitterShareBtn").click(function () {
    share("twitter");
  });
});

function hideKeyboard() {
  //this set timeout needed for case when hideKeyborad
  //is called inside of 'onfocus' event handler
  setTimeout(function() {

    //creating temp field
    var field = document.createElement('input');
    field.setAttribute('type', 'text');
    //hiding temp field from peoples eyes
    //-webkit-user-modify is nessesary for Android 4.x
    field.setAttribute('style', 'position:absolute; top: 0px; opacity: 0; -webkit-user-modify: read-write-plaintext-only; left:0px;');
    document.body.appendChild(field);

    //adding onfocus event handler for out temp field
    field.onfocus = function(){
      //this timeout of 200ms is nessasary for Android 2.3.x
      setTimeout(function() {

        field.setAttribute('style', 'display:none;');
        setTimeout(function() {
          document.body.removeChild(field);
          document.body.focus();
        }, 14);

      }, 200);
    };
    //focusing it
    field.focus();

  }, 50);
}

if(windowsize < 1025){
  console.log('Ipad')
  hideKeyboard();
}

function isIOS() {
  if (/iPad|iPhone|iPod/.test(navigator.platform)) {
    return true;
  } else {
    return navigator.maxTouchPoints &&
      navigator.maxTouchPoints > 2 &&
      /MacIntel/.test(navigator.platform);
  }
}

function isIpadOS() {
  return navigator.maxTouchPoints &&
    navigator.maxTouchPoints > 2 &&
    /MacIntel/.test(navigator.platform);
}

function isIpadPro() {
  var ratio = window.devicePixelRatio || 1;
  var screen = {
      width : window.screen.width * ratio,
      height : window.screen.height * ratio
  };
  return (screen.width === 2048 && screen.height === 2732) || (screen.width === 2732 && screen.height === 2048) || (screen.width === 1536 && screen.height === 2048) || (screen.width === 2048 && screen.height === 1536);
}

console.log(isIpadOS())

if (isIOS() || isIpadOS || isIpadPro()){
  console.log('IOS')
  hideKeyboard();
}