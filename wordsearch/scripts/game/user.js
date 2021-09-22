const checkUser = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(";");
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split("=");
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == "wordsearch-userId") {
      gameData.userId = cVal;
    }
  }

  if (!gameData.userId) {
    generateUser();
  } else {
    let resdata = {
    userId: gameData.userId,
  };

  $.ajax({
    url: "./api/question/get-game-result.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
    success: function (e) {
      if (e.result == null) {
        generateUser();
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
  }

  setTimeout(() => {
    checkOnboardSetting();
  }, 1000);
};

const setUser = (uid) => {
  document.cookie = "wordsearch-userId=" + uid + ";path=/";
  gameData.userId = uid;
};

const generateUser = () => {
  $.ajax({
    url: "./api/question/generate-user-id.php",
    dataType: "json",
    type: "get",
    success: function (e) {
      console.log('generate user', e)
      setUser(e.result.userId);
    },
    error: function (err) {
      console.log(err);
    },
  })
   
};
