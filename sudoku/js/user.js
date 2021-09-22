let userId;

const checkUser = () => {
  let decodedCookie = decodeURIComponent(document.cookie).split(";");
  for (let i = 0; i < decodedCookie.length; i++) {
    const c = decodedCookie[i].split("=");
    const cId = c[0]?.trim();
    const cVal = c[1]?.trim();

    if (cId == "sudoku-userId") {
      userId = cVal;
    }
  }

  if (!userId) {
    generateUser();
  } else {
    let resdata = {
    userId: userId,
  };

  $.ajax({
    url: "./api/question/get-game-result.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(resdata),
  })
    .done((e) => {
      // console.log(e);
      if (e.result == null) {
        generateUser();
      }
    })
    .error((err) => {
      console.log(err);
    });
  }

  setTimeout(() => {
    checkOnboardSetting();
  }, 1000);
};

const setUser = (uid) => {
  document.cookie = "sudoku-userId=" + uid + ";path=/";
  userId = uid;
};

const generateUser = () => {
  $.ajax({
    url: "./api/question/generate-user-id.php",
    dataType: "json",
    type: "get",
  })
    .done((e) => {
      // console.log(e);
      setUser(e.result.userId);
    })
    .error((err) => {
      console.log(err);
    });
};
