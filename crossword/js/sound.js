var isMute = false;

const playSound = (sound) => {
  stopSound();
  createjs.Sound.play(sound);
};

function stopSound() {
  createjs.Sound.stop();
}

const toggleMute = (mute) => {
  document.cookie = "crossword-isMute=" + mute + ";path=/";
  createjs.Sound.setMute(mute);

  mute
    ? $("#btn-sound img").attr("src", "./images/custom/sound_on.svg")
    : $("#btn-sound img").attr("src", "./images/custom/sound_off.svg");

  mute
    ? $("#btn-sound-small img").attr("src", "./images/custom/unmute.svg")
    : $("#btn-sound-small img").attr("src", "./images/custom/mute.svg");

  mute ? $("#sound-tooltip").html("Unmute") : $("#sound-tooltip").html("Mute");
};

const checkMute = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(";");
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split("=");
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == "crossword-isMute") {
      isMute = cVal == "true";
    }
  }

  toggleMute(isMute);
};
