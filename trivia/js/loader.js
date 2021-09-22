////////////////////////////////////////////////////////////
// CANVAS LOADER
////////////////////////////////////////////////////////////

/*!
 *
 * START CANVAS PRELOADER - This is the function that runs to preload canvas asserts
 *
 */
function initPreload() {
  toggleLoader(true);

  $(window).resize(function () {
    resizeGameFunc();
  });
  resizeGameFunc();

  //__info: handle enter and exit fullscreen mode layout broken in iframe mode
  $(window).resize(debounce(function(e) {

      if(gameData.page == 'game' && gameData.initScreenH != $(window).height()) {
        gameData.initScreenH = $(window).height();
        buildQuestion();
      }
  }));

  setTopMainLoader();

  setTimeout(function () {
    setTopMainLoader();
  }, 400);

  loader = new createjs.LoadQueue(false);
  manifest = [
    { src: 'img/logo.svg', id: 'logo' },
    {
      src: 'assets/item_timer.svg',
      id: 'itemTimer',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/item_question.svg',
      id: 'itemQuestion',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/item_cup_over.svg',
      id: 'itemCupOver',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/item_cup.svg',
      id: 'itemCup',
      type: createjs.LoadQueue.IMAGE,
    },
    { src: 'assets/bg_pixel.png', id: 'bgPixel' },

    {
      src: 'assets/button_facebook.svg',
      id: 'buttonFacebook',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_twitter.svg',
      id: 'buttonTwitter',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_whatsapp.svg',
      id: 'buttonWhatsapp',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_fullscreen.svg',
      id: 'buttonFullscreen',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_sound_on.svg',
      id: 'buttonSoundOn',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_sound_off.svg',
      id: 'buttonSoundOff',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_replay.svg',
      id: 'buttonReplay',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_start.svg',
      id: 'buttonStart',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_submit.svg',
      id: 'buttonSubmit',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_prev.svg',
      id: 'buttonPrev',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_preview.svg',
      id: 'buttonPreview',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_replay.svg',
      id: 'buttonReplay',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_save.svg',
      id: 'buttonSave',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_score.svg',
      id: 'buttonScore',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_ok.svg',
      id: 'buttonOk',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_next.svg',
      id: 'buttonNext',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_exit.svg',
      id: 'buttonExit',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_cancel.svg',
      id: 'buttonCancel',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_back.svg',
      id: 'buttonBack',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_option.svg',
      id: 'buttonOption',
      type: createjs.LoadQueue.IMAGE,
    },
    {
      src: 'assets/button_option_close.svg',
      id: 'buttonOptionClose',
      type: createjs.LoadQueue.IMAGE,
    },
  ];

  soundOn = true;
  if ($.browser.mobile || isTablet) {
    if (!enableMobileSound) {
      soundOn = false;
    }
  }

  if (soundOn) {
    manifest.push({
      src: 'assets/sounds/selectAnswer.ogg',
      id: 'soundSelectAnswer',
    });
    manifest.push({
      src: 'assets/sounds/selectWrong.ogg',
      id: 'soundSelectWrong',
    });
    manifest.push({
      src: 'assets/sounds/answerCorrect.ogg',
      id: 'soundAnswerCorrect',
    });
    manifest.push({
      src: 'assets/sounds/answerWrong.ogg',
      id: 'soundAnswerWrong',
    });
    manifest.push({ src: 'assets/sounds/click.ogg', id: 'soundClick' });
    manifest.push({ src: 'assets/sounds/result.ogg', id: 'soundResult' });
    manifest.push({ src: 'assets/sounds/scoreCount.mp3', id: 'soundCounter' });
    manifest.push({ src: 'assets/sounds/success.mp3', id: 'soundCelebrate' });
    manifest.push({ src: 'assets/sounds/fail.mp3', id: 'soundFail' });

    createjs.Sound.alternateExtensions = ['mp3'];
    loader.installPlugin(createjs.Sound);
  }

  loader.addEventListener('complete', handleComplete);
  loader.addEventListener('fileload', fileComplete);
  loader.addEventListener('error', handleFileError);
  loader.on('progress', handleProgress, this);
  loader.loadManifest(manifest);
}

/*!
 *
 * CANVAS FILE COMPLETE EVENT - This is the function that runs to update when file loaded complete
 *
 */
function fileComplete(evt) {
  var item = evt.item;
  //console.log("Event Callback file loaded ", evt.item.id);
}

/*!
 *
 * CANVAS FILE HANDLE EVENT - This is the function that runs to handle file error
 *
 */
function handleFileError(evt) {
  //console.log("error ", evt);
}

/*!
 *
 * CANVAS PRELOADER UPDATE - This is the function that runs to update preloder progress
 *
 */
function handleProgress() {
  var load = Math.round((loader.progress / 1) * 100);
  var percentage = load + '%';
  $('.cc-progress-percentage').html(percentage);

  $('#cc-loading-indicator .cc-progress-bar').css({
    width: percentage,
  });
}

/*!
 *
 * CANVAS PRELOADER COMPLETE - This is the function that runs when preloader is complete
 *
 */
function handleComplete() {
  toggleLoader(false);
  initMain();
}

/*!
 *
 * TOGGLE LOADER - This is the function that runs to display/hide loader
 *
 */
function toggleLoader(con) {
  if (con) {
    $('#mainLoader').show();
    $('#mainHolder').hide();
  } else {
    var height = $('#mainLoader').height();
    var top = $('#mainLoader').css('top');

    setTimeout(() => {
      $('#mainLoader').hide();
      $('#mainHolder').show();
    }, 1000);

    $('#logoHolder').height(height);
    $('#logoHolder').css('top', top);
  }
}
