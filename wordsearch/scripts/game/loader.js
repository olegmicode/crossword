let loaderProgress = 0;

const initPreload = () => {
  checkIframe();
  show(introPage);
  var loader = new createjs.LoadQueue();
  var manifest = [
    {
      src: 'sounds/click.ogg',
      id: 'soundClick',
    },
    {
      src: 'sounds/answerCorrect.ogg',
      id: 'soundCorrect',
    },
    {
      src: 'sounds/answerWrong.ogg',
      id: 'soundWrong',
    },
    {
      src: 'sounds/result.ogg',
      id: 'soundResult',
    },
    {
      src: 'sounds/fail.mp3',
      id: 'soundFail',
    },
    {
      src: 'sounds/success.mp3',
      id: 'soundSuccess',
    },
    {
      src: 'sounds/scoreCount.mp3',
      id: 'soundCount',
    },
  ];

  createjs.Sound.alternateExtensions = ['mp3'];
  setTimeout(() => {
    loader.installPlugin(createjs.Sound);
    loader.loadManifest(manifest);
    loader.on('complete', handleComplete);
    loader.on('fileLoad', fileComplete);
    loader.on('error', handleError);
    loader.on('progress', handleProgress, this);
  }, 1000);

  windowWidth = $(window).width();
  checkMute();
  checkUser();
};

const fileComplete = (e) => {
  // console.log("file load");
};

const handleComplete = () => {
  // console.log("complete");
  setTimeout(() => {
    incrementLoader('finish');
  }, 500);
};

const handleError = () => {
  console.log('error on loader');
};

const handleProgress = () => {
  // console.log("progress");
  setTimeout(() => {
    incrementLoader('progress');
  }, 500);
};

const incrementLoader = (incString) => {
  var loader, maxInc;

  switch (incString) {
    case 'progress':
      maxInc = loaderProgress + 10;
      loader = setInterval(function () {
        frameLoader(maxInc, loader);
      }, 20);
      break;

    case 'finish':
      maxInc = loaderProgress + 100;
      loader = setInterval(function () {
        frameLoader(maxInc, loader);
      }, 20);
      break;

    default:
      break;
  }
};

const frameLoader = (maxInc, loader) => {
  progressBar.addClass('load');
  if (loaderProgress >= 100) {
    clearInterval(loader);
    setTimeout(function () {
      // progressWrapper.hide();
      // startButton.show();
      // topBar.show();
      permalinkHandler();
    }, 500);
  } else {
    if (loaderProgress < maxInc) {
      loaderProgress++;
      progressBar.width(loaderProgress + '%');
      progressPercentage.html(loaderProgress + '%');
    } else {
      clearInterval(loader);
    }
  }
};

const permalinkHandler = async() => {
  let urlParams = new URLSearchParams(window.location.search);
  let permalinkParam = urlParams.get('l');

  if(permalinkParam){
    let category = await getCategoryByPermalink(permalinkParam);
    if(category.result_code == 1){
      goPage('game');
      let catId = category.id
      startGame(catId, category);
      playSound('soundClick');
      return;
    }
  }
  progressWrapper.hide();
  startButton.show();
  topBar.show();
}
