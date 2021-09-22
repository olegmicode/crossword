var confettiElement = document.getElementById('confettiWrapper');
var confettiSettings = {
  target: confettiElement,
  size: 2,
  animate: true,
  respawn: true,
  clock: 30,
  rotate: true,
};
var confetti = new ConfettiGenerator(confettiSettings);

$(() => {
  countResultScore();
});

$('#btnReplay').click(() => {
  // function getBaseUrl() {
  let root = window.location.href.match(/^.*\//);
  window.location.replace(root + '?replay=true');
  // }
});

const countResultScore = (score) => {
  // playSound("soundCount");
  showRateStar();
  // let scoreStep = scoresData.map((x) => x.minScore);

  // if (score >= Math.min(...scoreStep)) {
  //   $({ Counter: 0 }).animate(
  //     {
  //       Counter: score,
  //     },
  //     {
  //       duration: 2000,
  //       easing: "swing",
  //       step: function () {
  //         scoreResultText.html(Math.ceil(this.Counter));
  //       },
  //       complete: function () {
  //         stopSound();
  //         setTimeout(() => {
  //           playSound("soundSuccess");
  //           $("#confettiWrapper").show();
  //         }, 500);
  //       },
  //     }
  //   );
  // } else {
  //   playSound("soundFail");
  // }
};

const showRateStar = () => {
  // var shine = "./img/icon/result-star-shine-icon.svg";
  // for (let index = 0; index < scoresData.length; index++) {
  //   if (score >= scoresData[index].minScore) {
  //     stars.each((i, el) => {
  //       if (i < scoresData[index].star)
  //         $(el)
  //           .delay(300 * (i + 1))
  //           .queue(() => {
  //             $(el).attr("src", shine);
  //             $(el).addClass("bounce").dequeue();
  //           });
  //     });
  //   }
  // }
};

const resetStars = () => {
  let dim = './img/icon/result-star-icon.svg';
  stars.each((i, el) => {
    $(el).attr('src', dim);
    $(el).removeClass('bounce');
  });
};
