////////////////////////////////////////////////////////////
// SOUND
////////////////////////////////////////////////////////////
var enableMobileSound = true;
var soundOn;
var isMute = false;

function playSound(target, loop) {
  if (soundOn) {
    var isLoop;
    if (loop) {
      isLoop = -1;
      createjs.Sound.stop();
      musicLoop = createjs.Sound.play(
        target,
        createjs.Sound.INTERRUPT_NONE,
        0,
        0,
        isLoop,
        1
      );
      if (
        musicLoop == null ||
        musicLoop.playState == createjs.Sound.PLAY_FAILED
      ) {
        return;
      } else {
        musicLoop.removeAllEventListeners();
        musicLoop.addEventListener("complete", function (musicLoop) {});
      }
    } else {
      isLoop = 0;
      createjs.Sound.play(target);
    }
  }
}

function stopSound() {
  createjs.Sound.stop();
}

/*!
 *
 * PLAY MUSIC - This is the function that runs to play and stop music
 *
 */
$.sound = {};
function playSoundLoop(sound) {
  if (soundOn) {
    if ($.sound[sound] == null) {
      $.sound[sound] = createjs.Sound.play(sound);
      $.sound[sound].removeAllEventListeners();
      $.sound[sound].addEventListener("complete", function () {
        $.sound[sound].play();
      });
    }
  }
}

function stopSoundLoop(sound) {
  if (soundOn) {
    if ($.sound[sound] != null) {
      $.sound[sound].stop();
      $.sound[sound] = null;
    }
  }
}

function setSoundVolume(sound, vol) {
  if (soundOn) {
    if ($.sound[sound] != null) {
      $.sound[sound].volume = vol;
    }
  }
}

/*!
 *
 * TOGGLE MUTE - This is the function that runs to toggle mute
 *
 */
function checkMute() {
  var cookie = document.cookie.split(";");
  cookie.map((ck) => {
    var item = ck.split("=");
    if (item[0].trim() == "isMute") {
      isMute = item[1].trim() == "true";
    }
  });
  toggleMute(isMute);
}

function toggleMute(con) {
  document.cookie = "isMute=" + con + ";path=/";
  createjs.Sound.setMute(con);
}

/*!
 *
 * PLAY AUDIO - This is the function that runs to play questiona and answer audio
 *
 */
var audioQuestion = null;
function playAudio(audio) {
  if (audioQuestion == null) {
    audioQuestion = createjs.Sound.play(audio);
    audioQuestion.removeAllEventListeners();
    audioQuestion.addEventListener("complete", function (event) {
      audioQuestion = null;
      playAudioComplete();
    });
  }
}

function stopAudio() {
  if (audioQuestion != null) {
    audioQuestion.stop();
    audioQuestion = null;
  }
}
