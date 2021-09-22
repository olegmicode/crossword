let scoreData = [];
let point = 10;

const setScore = (correct) => {
  if (correct) {
    gameData.score += point;
  }
  else {
    if (gameData.score > 0) {
      gameData.score -= point;

      if (gameData.score < 0) {
        gameData.score = 0;
      }
    }
  }
  updateScore();
};

const scoreDeduction = () => {
  if (gameData.score > 0) {
    gameData.score -= parseInt(gameData.pointDeduction);
    updateScore();
  }
};

const updateScore = () => {
  scoreText.text(gameData.score);
};

const resetScore = () => {
  gameData.score = gameData.initPoint;
  scoreText.text(gameData.score);
};

// const checkScoreBonus = () => {
//   if (min < 6) {
//     let restSec = 360 - (min * 60 + sec);
//     let scoreBonus = 500 + restSec * 2;
//     gameData.score += scoreBonus;
//   }
// };

const postScore = () => {
  let resdata = {
    score: gameData.score.toString(),
    levelId: gameData.categoryId.toString(),
    userId: gameData.userId.toString(),
  };

  $.ajax({
    url: "./api/question/set-game-result.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
    success: function (e) {
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const getScore = () => {
  let resdata = {
    userId: gameData.userId,
  };

  $.ajax({
    url: "./api/question/get-game-result.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
    success: function (e) {
      if (e.result?.data?.scores) {
        scoreData = e.result.data.scores;
      }
      getCategories();
    },
    error: function (err) {
      console.log(err);
    },
  });
};
