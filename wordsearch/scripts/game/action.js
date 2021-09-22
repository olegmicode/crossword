$(document).on('click', '#btnStart', () => {
  show(loader);
  goPage('category');
});

$(document).on('click', '#btnSkipOnboard', () => {
  show(loader);
  goPage('category');
  setOnboardSetting();
});

$(document).on('click', '#btnNextOnboard', () => {
  onBoardIndex++;
  showOnboard(onBoardIndex);
});

$(document).on('click', '#btnStartOnboard', () => {
  show(loader);
  goPage('category');
  setOnboardSetting();
});

$(document).on('click', '.onboard__pagination__index', (e) => {
  let curIndex = $('.onboard__pagination__index').index($(e.currentTarget));
  onBoardIndex = curIndex;
  showOnboard(curIndex);
});

$(document).on('click', '#btnNextIndex', (e) => {
  nextCategoryPage();
});

$(document).on('click', '#btnPrevIndex', (e) => {
  prevCategoryPage();
});

$(document).on('click', '.category__thumb', (e) => {
  goPage('game');
  show(loader);
  let catId = $(e.currentTarget).attr('data-categoryId');
  startGame(catId);
  playSound('soundClick');
});

$(document).on('click', '#btnTimer', () => {
  pauseGame();
});

$(document).on('click', '#btnCheckWord', () => {
  checkWord();
});

$(document).on('click', '#btnCheckLetter', () => {
  checkLetter();
});

$(document).on('click', '#btnContinue', () => {
  resumeGame();
});

$(document).on('click', '#btnReset', () => {
  confirmReset();
});

$(document).on('click', '#btnResetYes', () => {
  resetGame();
  goPage('category');
});

$(document).on('click', '#btnResetNo', () => {
  resumeGame();
});

$(document).on('click', '#btnSetting', () => {
  toggleSetting();
});

$(document).on('click', '#btnHelp', () => {
  onBoardModalIndex = 0;
  showOnboardModal(onBoardModalIndex);
});

$(document).on('click', '#btnOnboardModalSkip', () => {
  closeModal();
  startTimer();
  closeSetting();
  setOnboardSetting();
});

$(document).on('click', '#btnOnboardModalNext', () => {
  onBoardModalIndex++;
  showOnboardModal(onBoardModalIndex);
});

$(document).on('click', '#btnOnboardModalStart', () => {
  closeModal();
  startTimer();
  closeSetting();
  setOnboardSetting();
});

$(document).on('click', '.modal--help__indicator__item', (e) => {
  let curIndex = $('.modal--help__indicator__item').index($(e.currentTarget));
  onBoardModalIndex = curIndex;
  showOnboardModal(curIndex);
});

$(document).on('click', '#btnSound', () => {
  toggleMute();
});

$(document).on('click', '#btnReplay', () => {
  removePermalinkParam();
  resetGame();
  goPage('category');
});

$(document).on('click', '#btnSurrender', () => {
  stopGame();
});

$(document).on('click', '#btnTwitter', () => {
  share('twitter');
});

$(document).on('click', '#btnFacebook', () => {
  share('facebook');
});

//prevent double click on all button
$(document).on('click', '.btn', (e) => {
  playSound('soundClick');
  if (
    !$(e.currentTarget).hasClass('btn--words') &&
    !$(e.currentTarget).hasClass('btn--tools')
  ) {
    $(e.currentTarget).addClass('disabled');
    setTimeout(() => {
      $(e.currentTarget).removeClass('disabled');
    }, 500);
  }

  // if (
  //   !$(e.currentTarget).is("#btnSetting") ||
  //   !$(e.currentTarget).is("#btnSound")
  // ) {
  //   closeSetting();
  // }
});

$(document).on('click', '.tooltiped', (e) => {
  if (windowWidth < 768) {
    $(e.currentTarget).find('.tooltip').show();
    setTimeout(() => {
      $(e.currentTarget).find('.tooltip').hide();
    }, 3000);
  }
});
