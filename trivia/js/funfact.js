$(document).ready(() => {
  $("#shareFacebookBtn").click(() => {
    share("facebook");
  });

  $("#shareTwitterBtn").click(() => {
    share("twitter");
  });

  $("#likeBtn").click((e) => {
    e.preventDefault();
    TweenMax.to($("#likeBtn"), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });
    TweenMax.to($("#likeBtn"), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        like();
      },
    });
  });

  $("#dislikeBtn").click((e) => {
    e.preventDefault();
    TweenMax.to($("#dislikeBtn"), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });
    TweenMax.to($("#dislikeBtn"), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        dislike();
      },
    });
  });

  $("#reportBtn").click((e) => {
    e.preventDefault();
    TweenMax.to($("#reportBtn"), 0, {
      scale: 1.2,
      overwrite: false,
      ease: Elastic.easeOut,
    });
    TweenMax.to($("#reportBtn"), 1, {
      scale: 1,
      overwrite: false,
      ease: Elastic.easeOut,
      onComplete: () => {
        report();
        toggleModal(true);
      },
    });
  });

  $("#modalCloseBtn").click(() => {
    toggleModal(false);
  });
});

const share = (action) => {
  // gtag("event", "click", { event_category: "share", event_label: action });

  const shareloc =
    "https://seniorsdiscountclub.com.au/games/trivia/fun-fact/index.php?slug=" + slug;
  const loc = "https://seniorsdiscountclub.com.au/games/trivia/";

  let shareurl = "";

  switch (action) {
    case "twitter":
      let tweetText = question;
      if (question.length > 250) {
        // 280 - 23 (twitter alter the link to 23 chars) - 7 (safe size + '...')
        tweetText = question.substring(250, 0).concat("...");
      }

      shareurl =
        "https://twitter.com/intent/tweet?url=" +
        shareloc +
        "&text=" +
        tweetText;

      break;

    case "facebook":
      shareurl =
        "https://www.facebook.com/sharer/sharer.php?u=" +
        encodeURIComponent(
          loc +
            "share.php?desc=" +
            info +
            " " +
            loc +
            "&title=" +
            answer +
            "&url=" +
            shareloc
        );
      break;

    default:
      break;
  }

  window.open(shareurl);
};

const report = () => {
  statistic.reported++;
  $.ajax({
    url:
      "https://seniorsdiscountclub.com.au/games/trivia/api/question/set-statistic.php",
    dataType: "json",
    type: "post",
    data: JSON.stringify(statistic),
  })
    .done((e) => {
      $("#reportBtn").addClass("disabled");
    })
    .error((err) => {
      console.log(err);
    });
};

const like = () => {
  var postData = {
    id: parseInt(statistic.question_id),
  };

  $.ajax({
    type: "POST",
    url:
      "https://seniorsdiscountclub.com.au/games/trivia/api/question/like-question.php",
    data: JSON.stringify(postData),
    success: function (result) {
      $("#likeBtn").addClass("disabled");
      $("#dislikeBtn").addClass("disabled");
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const dislike = () => {
  var postData = {
    id: parseInt(statistic.question_id),
  };

  $.ajax({
    type: "POST",
    url:
      "https://seniorsdiscountclub.com.au/games/trivia/api/question/dislike-question.php",
    data: JSON.stringify(postData),
    success: function (result) {
      $("#likeBtn").addClass("disabled");
      $("#dislikeBtn").addClass("disabled");
    },
    error: function (err) {
      console.log(err);
    },
  });
};

const toggleModal = (con) => {
  if (con) {
    $("body").addClass("modal-active");
    $("#modalBackdrop").show();
    $("#reportModal").show();
  } else {
    $("body").removeClass("modal-active");
    $("#modalBackdrop").hide();
    $("#reportModal").hide();
  }
};
