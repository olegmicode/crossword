const initPreload = () => {
  var loader = new createjs.LoadQueue();
  var manifest = [
    {
      src: 'sounds/click.ogg',
      id: 'soundClick',
    },
    {
      src: 'sounds/result.ogg',
      id: 'soundResult',
    },
    {
      src: 'sounds/answerCorrect.ogg',
      id: 'soundCorrect',
    },
    {
      src: 'sounds/fail.mp3',
      id: 'soundFail',
    },
    {
      src: 'sounds/success.mp3',
      id: 'soundCelebrate',
    },
    {
      src: 'sounds/scoreCount.mp3',
      id: 'soundCounter',
    },
  ];

  createjs.Sound.alternateExtensions = ['mp3'];
  loader.installPlugin(createjs.Sound);
  loader.loadManifest(manifest);
  loader.on('complete', handleComplete);
  loader.on('fileLoad', fileComplete);
  loader.on('error', handleError);
  loader.on('progress', handleProgress, this);
};

const handleComplete = (e) => {
  // loadJsonFile();
  incrementLoader('quarter');
  setupConfiguration();
};

const fileComplete = (e) => {
  // loadJsonFile();
};

const handleError = (e) => {
  console.log('Error', e);
};

const handleProgress = () => {
  // console.log(loader.progress);
};

const loadJsonFile = () => {
  incrementLoader('quarter');
  $.getJSON('data.json?r=' + Math.random())
    .done(jsonLoaded)
    .fail(function () {
      alert('failed to load json');
    });
};

const jsonLoaded = (data) => {
  puzzle = data;
  acrossClues = puzzle.acrossClues;
  downClues = puzzle.downClues;
  gameInit();
  startGame();
  goPage('game');
};

//__info: additional function for loading progress bar
const incrementLoader = (incString) => {
  var loader, maxInc;
  switch (incString) {
    case 'quarter':
      maxInc = loaderPosition + 20;
      loader = setInterval(function () {
        frameLoader(maxInc, loader);
      }, 20);
      break;
    case 'half':
      maxInc = loaderPosition + 50;
      loader = setInterval(function () {
        frameLoader(maxInc, loader);
      }, 20);
      break;
    case 'full':
      maxInc = loaderPosition + 100;
      loader = setInterval(function () {
        frameLoader(maxInc, loader);
      }, 20);
      break;
  }
};

const frameLoader = (maxInc, loader) => {
  if (loaderPosition >= 100) {
    clearInterval(loader);
    setTimeout(function () {
      permalinkHandler();
    }, 1000);
  } else {
    if (loaderPosition < maxInc) {
      loaderPosition++;
      $('#cc-loading-indicator .cc-progress-bar').width(loaderPosition + '%');
      $('.cc-progress-percentage').html(loaderPosition + '%');
    } else {
      clearInterval(loader);
    }
  }
};

const checkUser = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(';');
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split('=');
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == 'crossword-userId') {
      playerData.userId = cVal;
    }
  }

  playerData.onBoarding = 0;
  if (!playerData.userId) {
    generateUser();
  } else {
    checkOnboardSetting();
    var param = { userId: playerData.userId };
    // console.log(JSON.stringify(param));
    $.ajax({
      url:
        'https://seniorsdiscountclub.com.au/games/crossword/api/question/get-game-result.php',
      type: 'POST',
      dataType: 'json',
      data: JSON.stringify(param),
      success: function (e) {
        // console.log(result);
        if (e.result == null) {
          generateUser();
        }
      },
      error: function (err) {
        console.log(err);
      },
    });
  }
};

const checkOnboardSetting = () => {
  console.log('check onboard setting');
  let resdata = {
    user_key: playerData.userId,
    setting_param: 'onboarding_state',
  };

  $.ajax({
    url: './api/setting/get-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log(e, resdata);
      if (e.onboarding_state) {
        playerData.onBoarding = e.onboarding_state;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const setOnboardSetting = () => {
  let resdata = {
    user_key: playerData.userId,
    setting_param: 'onboarding_state',
    setting_value: 1,
  };

  $.ajax({
    url: './api/setting/set-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log(e, resdata);
      if (e.status == 'success') {
        playerData.onBoarding = 1;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const permalinkHandler = () => {
  let urlParams = new URLSearchParams(window.location.search);
  let permalinkParam = urlParams.get('l');

  let gameLevel = null;
  if(permalinkParam){
    if(gameLevels){
      gameLevels.forEach((v) => {
        if(v.permalink == permalinkParam && v.status == 1 && v.deleted == 0){
          gameLevel = v;
        }
      })
    }
  }

  if(gameLevel){
    getScore();

    difficulty.id = parseInt(gameLevel.id);
    difficulty.grid = parseInt(gameLevel.width);
    fetchQuestions(parseInt(gameLevel.id), parseInt(gameLevel.width));
    gameData.levelId = parseInt(gameLevel.id);
    gameData.boardJSON = gameLevel.board_json;

    let loaderTimer = setInterval(() => {
      if(isOnTheGame){
        $('#loader').fadeOut('fast', function () {});
        clearInterval(loaderTimer);
      }
    }, 1000);
  }else {
    goPage('intro');
  }
}