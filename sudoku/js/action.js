$(document).on("click", "#btnStart", () => {
  goPage("level")
  playSound("soundClick");
});

$(document).on("click", ".level__thumb", (e) => {
  goPage("game");
  startGame($(e.currentTarget).attr("data-level"));
  playSound("soundClick");

   if (onBoarding == 1) {
    startTimer();
  } else {
    showOnboardModal(0);
  }
});

$(document).on("click", ".game__board__cell", (e) => {
  cellClick($(e.currentTarget));
  playSound("soundClick");
});

$(document).on("click", ".btn-number-selector", (e) => {
  if (selectedCell) {
    undoMode = false;
    playSound("soundClick");
    const val = $(".btn-number-selector").index($(e.currentTarget)) + 1;
    $(e.currentTarget).addClass("clicked");

    setTimeout(() => {
      cellInput(selectedCell, val);
    }, 200);
    setTimeout(() => {
      $(e.currentTarget).removeClass("clicked");
    }, 500);
  } else {
    alert("Please select 1 cell");
  }
});

$(document).on("click", ".btn-tools", (e) => {
  playSound("soundClick");
  $(e.currentTarget).addClass("clicked");
  let src = $(e.currentTarget).find("img").attr("src");
  $(e.currentTarget)
    .find("img")
    .attr("src", src.split("-")[0] + "-active-icon.svg");
  setTimeout(() => {
    $(e.currentTarget).removeClass("clicked");
    if (src) {
      $(e.currentTarget).find("img").attr("src", src);
    }
  }, 500);
});

$(document).on("click", "#btnNote", () => {
  toggleNote();
});

$(document).on("click", "#btnUndo", () => {
  if (gameHistory.length > 0) {
    undoMode = true;
    undo();
  }
});

$(document).on("click", "#btnHint", () => {
  if (selectedCell) {
    setScore(false);
    revealCurrentCell();
  } else {
    alert("No cell selected, please click 1 cell");
  }
});

$(document).on("click", "#btnErase", () => {
  if (selectedCell) {
    if (noteMode) {
      resetNote(true);
    } else {
      resetCell();
    }
  } else {
    alert("No cell selected, please click 1 cell");
  }
});

$(document).on("click", "#btnTimer", () => {
  playSound("soundClick");
  pauseGame();
});

$(document).on("click", "#btnContinue", () => {
  playSound("soundClick");
  resumeGame();
});

$(document).on("click", "#btnReset", () => {
  playSound("soundClick");
  confirmReset();
});

$(document).on("click", "#btnResetYes", () => {
  playSound("soundClick");
  restartGame();
  // goPage("level");
});

$(document).on("click", "#btnResetNo", () => {
  playSound("soundClick");
  resumeGame();
});

$(document).on("click", "#btnSetting", () => {
  playSound("soundClick");
  toggleSetting();
});

$(document).on("click", "#btnSound", () => {
  playSound("soundClick");
  toggleMute();
});

$(document).on('click', '#btnHelp', () => {
  onBoardModalIndex = 0;
  showOnboardModal(onBoardModalIndex);
})

$(document).on("click", "#btnOnboardModalSkip", () => {
 closeModal();
 startTimer();
 closeSetting();
 setOnboardSetting();
});

$(document).on("click", "#btnOnboardModalNext", () => {
  onBoardModalIndex++;
  showOnboardModal(onBoardModalIndex);
});

$(document).on("click", "#btnOnboardModalStart", () => {
 closeModal();
 startTimer();
  closeSetting();
  setOnboardSetting();
});

$(document).on("click", ".modal--help__indicator__item", (e) => {
 let curIndex = $('.modal--help__indicator__item').index($(e.currentTarget));
 onBoardModalIndex = curIndex;
 showOnboardModal(curIndex);
});

$(document).on("click", "#btnReplay", () => {
  playSound("soundClick");
  resetGame();
  goPage("level");
});

$(document).on("click", "#btnSurrender", () => {
  playSound("soundClick");
  stopGame();
});

$(document).on('keypress', function(e) {
  let keycode = e.which || e.keycode;
  let isNumber = !isNaN(e.key);
  if (((keycode < 58 && keycode > 48) || (keycode < 106 && keycode > 96)) && isNumber) {
    if (selectedCell) {
    undoMode = false;
    playSound("soundClick");
    const val = parseInt(e.key);
    $(e.currentTarget).addClass("clicked");

    setTimeout(() => {
      cellInput(selectedCell, val);
    }, 200);
    setTimeout(() => {
      $(e.currentTarget).removeClass("clicked");
    }, 500);
  } else {
    alert("Please select 1 cell");
  }}
})
$(document).on("click", "#btnTwitter", () => {
  share("twitter");
});

$(document).on("click", "#btnFacebook", () => {
  share("facebook");
});

//prevent double click on all button
$(document).on("click", ".btn", (e) => {
  
  $(e.currentTarget).addClass("disabled");
  setTimeout(() => {
    $(e.currentTarget).removeClass("disabled");
  }, 500);
});
