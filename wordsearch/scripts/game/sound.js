let mute = false;

const playSound = (sound) => {
  stopSound();
  if (!mute) {
    createjs.Sound.play(sound);

  }
};

function stopSound() {
  createjs.Sound.stop();
}

const toggleMute = () => {
  mute = !mute;
  document.cookie = "wordsearch-isMute=" + mute + ";path=/";
  createjs.Sound.setMute(mute);
  setMute();
};

const setMute = () => {
  if (mute) {
    $(btnSound).find("img").attr("src", "./img/icon/unmute-icon.svg");
    $(btnSound).find(".tooltip").html("Unmute");
  } else {
    $(btnSound).find("img").attr("src", "./img/icon/mute-icon.svg");
    $(btnSound).find(".tooltip").html("Mute");
  }
};

const checkMute = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(";");
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split("=");
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == "wordsearch-isMute") {
      mute = cVal == "true";
      setMute();
      createjs.Sound.setMute(mute);
    }
  }
};
