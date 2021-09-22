let mute = false;

const playSound = (sound) => {
  stopSound();
  createjs.Sound.play(sound);
};

function stopSound() {
  createjs.Sound.stop();
}

const toggleMute = () => {
  mute = !mute;
  console.log(mute);
  document.cookie = "crossword-isMute=" + mute + ";path=/";
  createjs.Sound.setMute(mute);

  if (mute) {
    $(btnSound).find("img").attr("src", "./img/icon/unmute-icon.svg");
    $(btnSound).find(".tooltip").html("UNMUTE");
  } else {
    $(btnSound).find("img").attr("src", "./img/icon/mute-icon.svg");
    $(btnSound).find(".tooltip").html("MUTE");
  }
};

const checkMute = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(";");
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split("=");
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == "sudoku-isMute") {
      mute = cVal == "true";
    }
  }
};
