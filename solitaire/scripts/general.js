let userId;
let userScore = 0;
let onBoardIndex = 0;
let onBoardState = 0;
let soundEnabled = true;
let isStarted = false;
let draw3 = false;

$(() => {
  checkMute();
  initPreload();
});


const setParentURL = () => {
  loc = document.referrer + 'fun/games/solitaire/';
  document.cookie = 'parentUrl=' + loc + ';path=/';
}

const playSound = (sound) => {
  stopSound();
  if (soundEnabled) {
    createjs.Sound.play(sound);
  }
};

function stopSound() {
  createjs.Sound.stop();
}

const toggleMute = () => {
  soundEnabled = !soundEnabled;
  document.cookie = 'solitaire-soundenabled=' + soundEnabled + ';path=/';
  setMute();
};

const setMute = () => {
  if (!soundEnabled) {
    $('#imgSound').attr('src', 'images/btn-sound-on.svg');
    $('#btnSound div').html('Unmute');
  } else {
    $('#imgSound').attr('src', 'images/btn-sound-off.svg');
    $('#btnSound div').html('Mute');
  }

  console.log(soundEnabled);
};

const checkMute = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(';');
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split('=');
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == 'solitaire-soundenabled') {
      soundEnabled = cVal == 'true';
      setMute();
    }
  }
};

const initPreload = () => {
  var loader = new createjs.LoadQueue();
  var manifest = [
    {
      src: 'app/assets/sound/success.mp3',
      id: 'soundSuccess',
    },
    {
      src: 'app/assets/sound/click2.mp3',
      id: 'soundClick',
    },
    {
      src: 'app/assets/sound/scoreCount.mp3',
      id: 'soundCounter',
    },
    {
      src: 'app/assets/sound/fail.mp3',
      id: 'soundFail',
    },
  ];

  setTimeout(() => {
    createjs.Sound.alternateExtensions = ['mp3'];
    loader.installPlugin(createjs.Sound);
    loader.loadManifest(manifest);
    loader.on('complete', handleComplete);
  }, 200);

  const handleComplete = (e) => {
    // console.log("file load");
  };
};

const checkUser = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(';');
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split('=');
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == 'solitaire-userId') {
      userId = cVal;
    }
  }

  if (!userId) {
    generateUser();
  } else {
    checkSetting();
  }
};

const setUser = (uid) => {
  document.cookie = 'solitaire-userId=' + uid + ';path=/';
  userId = uid;
};

const generateUser = () => {
  $.ajax({
    url: './api/question/generate-user-id.php',
    dataType: 'json',
    type: 'get',
    success: function (e) {
      console.log(e);
      setUser(e.result.userId);
      checkSetting();
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const postScore = () => {
  let resdata = {
    score: userScore.toString(),
    levelId: '1',
    userId: userId.toString(),
  };

  $.ajax({
    url: './api/question/set-game-result.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log(e);
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const checkSetting = () => {
  let resdata = {
    user_key: userId.toString(),
    setting_param: 'onboarding_state',
  };

  $.ajax({
    url: './api/setting/get-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log(e);
      if (e.onboarding_state) {
        onBoardState = e.onboarding_state;
      } else {
        onBoardState = 0;
        // showOnboard(); //called directly
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const setSetting = () => {
  let resdata = {
    user_key: userId.toString(),
    setting_param: 'onboarding_state',
    setting_value: 1,
  };

  $.ajax({
    url: './api/setting/set-setting.php',
    dataType: 'json',
    type: 'post',
    data: JSON.stringify(resdata),
    success: function (e) {
      console.log(e);
      if (e.status == 'success') {
        onBoardState = 1;
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const showOnboard = () => {
  $('#onboard-modal').modal('show');
  $('.onboard__item').css('display', 'none');
  $('.onboard__item').eq(onBoardIndex).css('display', 'flex');
  $('#btnStart').hide();

  if (onBoardIndex == $('.onboard__item').length - 1) {
    $('#btnStart').show();
    $('#btnSkip').hide();
    $('#btnNext').hide();
  } else {
    $('#btnStart').hide();
    $('#btnSkip').show();
    $('#btnNext').show();
  }

  $('.onboard__indicator__item').removeClass('active');
  $('.onboard__indicator__item').eq(onBoardIndex).addClass('active');
};

const openSettingPopup = () => {
  var settingPopup = document.getElementById('settingPopup');
  if (settingPopup.style.display === 'none') {
    settingPopup.style.display = 'block';
  } else {
    settingPopup.style.display = 'none';
  }
};

$('#pauseAction').click(function (e) {
  playSound('soundClick');
  $('#pause-modal').modal('show');
});

$('#btnRefresh').click(function (e) {
  playSound('soundClick');
  $('#reset-modal').modal('show');
});

$('#btnReset').click(function () {
  playSound('soundClick');
  $('#reset-modal').modal('hide');
});

$('#cancelReset').click(function () {
  playSound('soundClick');
  $('#reset-modal').modal('hide');
});

$('#resumeAction').click(function () {
  playSound('soundClick');
});

$('#surrender').click(function (e) {
  playSound('soundClick');
  e.preventDefault();
  let time = $('#html5-solitaire-timer').html();
  window.location.replace(
    'finish.html?score=' + userScore + '&time_spent=' + time
  );
});

$('#html5-solitaire-undo').click(function () {
  playSound('soundClick');
});

$('#btnSetting').click(function () {
  playSound('soundClick');
  openSettingPopup();
});

$('#btnNext').click(function () {
  playSound('soundClick');
  onBoardIndex++;
  showOnboard();
});

$('#btnStart').click(function () {
  playSound('soundClick');
  onBoardIndex = 0;
  $('#onboard-modal').modal('hide');

  if (onBoardState == 0) {
    setSetting();
  }
});

$('#btnStartGame').click(function () {
  $('#intro').addClass('d-none').removeClass('d-flex');
  $('#level').addClass('d-flex').removeClass('d-none');
  playSound('soundClick');
});

$('#btnSkip').click(function () {
  playSound('soundClick');
  onBoardIndex = 0;
  $('#onboard-modal').modal('hide');

  if (onBoardState == 0) {
    setSetting();
  }
});

$('#draw1').click(function (e) {
  playSound('soundClick');
  location.href = location.pathname + 'game.html?draw3=false';
});

$('#draw3').click(function (e) {
  playSound('soundClick');
  location.href = location.pathname + 'game.html?draw3=true';
});

$('.onboard__indicator__item').click(function (e) {
  playSound('soundClick');
  let curIndex = $('.onboard__indicator__item').index($(e.currentTarget));
  onBoardIndex = curIndex;
  showOnboard();
});

$('#btnHelp').click(function () {
  playSound('soundClick');
  showOnboard();
  // openSettingPopup();
});

$('#btnSound').click(function () {
  toggleMute();
});
