let score = 0;
let scoreData = [];
let multiplier = 20;
let multiplierDecrementTime = 15; //decrement multiplier every 15second

const multiplierDecrement = () => {
  if (multiplier > 1) {
    multiplier--;
  }
};

const setScore = (correct) => {
  let point = 5 * multiplier;
  if (correct) {
    score += point;
  } else {
    if (score > 0) {
      score -= point;

      if (score < 0) {
        score = 0;
      }
    }
  }
  updateScore();
};

const updateScore = () => {
  scoreText.text(score);
};

const resetScore = () => {
  score = 0;
  scoreText.text(score);
};

const checkScoreBonus = () => {
  if (min < 6) {
    let restSec = 360 - (min * 60 + sec);
    let scoreBonus = 500 + restSec * 2;
    score += scoreBonus;
  }
};

const postScore = () => {
  let resdata = {
    score: score.toString(),
    levelId: gameLevel.toString(),
    userId: userId.toString(),
  };

  $.ajax({
    url: "./api/question/set-game-result.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
  })
    .done((e) => {
      // console.log(e);
    })
    .error((err) => {
      console.log(err);
    });
};

const getScore = (uid) => {
  let resdata = {
    userId: uid,
  };

  $.ajax({
    url: "./api/question/get-game-result.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
  })
    .done((e) => {
      // console.log(e);
      if (e.result?.data?.scores) {
        scoreData = e.result.data.scores;
      }
      initLevels();
    })
    .error((err) => {
      console.log(err);
    });
};
