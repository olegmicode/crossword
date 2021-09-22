let timer;
let sec = 0;
let min = 0;
let hrs = 0;
const timerText = $("#timerText");

const startTimer = () => {
  timerButton.attr("src", "./img/icon/pause-icon.svg");
  timer = setInterval(tick, 1000);
  timerText.removeClass("text-red");
};

const tick = () => {
  sec++;

  if (parseInt(sec) % multiplierDecrementTime == 0) {
    multiplierDecrement();
  }

  if (sec >= 60) {
    sec = 0;
    min++;
    if (min >= 60) {
      min = 0;
      hrs++;
    }
  }

  let secText = sec;
  let mntText = min;
  let hrsText = hrs;

  if (sec < 10) {
    secText = "0" + secText;
  } else {
    secText = sec;
  }

  if (min < 10) {
    mntText = "0" + mntText;
  } else {
    mntText = min;
  }

  if (hrs < 10) {
    hrsText = "0" + hrsText;
  } else {
    hrsText = hrs;
  }
  timerText.text(hrsText + ":" + mntText + ":" + secText);
};

const pauseTimer = () => {
  timerButton.attr("src", "./img/icon/play-icon.svg");
  clearInterval(timer);
  timerText.addClass("text-red");
};

const stopTimer = () => {
  pauseTimer();
  sec = 0;
  mnt = 0;
  hrs = 0;
  timerText.text("00:00:00");
};
