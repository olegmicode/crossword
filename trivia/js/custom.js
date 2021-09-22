function setTopMainLoader() {
  var height = (window.innerHeight - $('#mainLoader').height()) / 2 + 25 + 'px';
  $('#mainLoader').css('top', height);
}

function setQuestionWidth() {
  // $('.question').width($('#questionHolder').width() + 'px');
}

function pauseTimeTick() {
  console.log('pause');
  toggleGameTimer(false);
  isPaused = true;

  $('.time-box .inner-time').addClass('paused');

  $('.time-action .cs-tooltip').html('Play');
  $('#time-trigger-pause').addClass('d-none');
  $('#time-trigger-start').removeClass('d-none');
}

function startTimeTick() {
  storeData.status = false;
  toggleGameTimer(true);
  storeData.status = true;
  isPaused = false;

  $('.time-box .inner-time').removeClass('paused');

  $('.time-action .cs-tooltip').html('Pause');
  $('#time-trigger-start').addClass('d-none');
  $('#time-trigger-pause').removeClass('d-none');
}

function resetProgress() {
  gameData.questionNum = 0;
  gameData.sequenceNum = 0;
  gameData.sequence_arr.length = 0;
  gameData.targetAnswerSequence = null;
  gameData.targetArray.length = 0;

  toggleGameTimer(false);
  isPaused = false;
  ccValueCorrect = 0;
  ccValueWrong = 0;
  $('.time-box .inner-time').removeClass('paused');
}

$(window).resize(function () {
  setQuestionWidth();
  checkOrientation();
});

$(document).on('click', '#chooseCategoryBtn', function () {
  playSound('soundClick');
  goPage('category');
});

// $(document).on("click", "#playRandom", function () {
//   fetchRandomQuestion(function () {
//     playSound("soundClick");
//     goPage("game");
//     $("#btn-hint").removeClass("d-none");
//     $("#navbar-main").addClass("d-none");
//     $("#navbar-score").removeClass("d-none");
//   });
// });

$(document).on('click', '#btn-hint', function () {
  playSound('soundClick');
  hintWrongButton();
});

$(document).on('click', '#btn-reset', function (e) {
  e.preventDefault();
  playSound('soundClick');
  pauseTimeTick();

  $('#modal-reset').modal('show');
});

$(document).on('click', '#btn-reset-no', function (e) {
  e.preventDefault();
  playSound('soundClick');
  startTimeTick();

  $('#modal-reset').modal('hide');
});

$(document).on('click', '#btn-reset-yes', function (e) {
  e.preventDefault();
  playSound('soundClick');
  if (gameModePage) {
    goPage('gameMode');
  } else {
    goPage('category');
  }

  resetProgress();

  $('#overlay-answer').addClass('d-none');
  $('#modal-reset').modal('hide');

  $('#btn-hint').addClass('d-none');

  $('#time-trigger-start').addClass('d-none');
  $('#time-trigger-pause').removeClass('d-none');

  $('#navbar-main').removeClass('d-none');
  $('#navbar-score').addClass('d-none');
});

$(document).on('click', '#time-trigger-start', function (e) {
  playSound('soundClick');
  startTimeTick();
});

$(document).on('click', '#time-trigger-pause', function (e) {
  playSound('soundClick');
  pauseTimeTick();

  $('#modal-pause').modal('show');
});

$(document).on('click', '#btn-continue', function (e) {
  playSound('soundClick');
  startTimeTick();

  $('#modal-pause').modal('hide');
  $('.time-action .cs-tooltip').html('Pause');

  $('#time-trigger-start').addClass('d-none');
  $('#time-trigger-pause').removeClass('d-none');
});

$(document).on('click', '#btn-report', function (e) {
  playSound('soundClick');
  $('#btn-report').addClass('disabled');

  TweenMax.to($(this), 0, {
    scale: 3,
    overwrite: false,
    ease: Elastic.easeOut,
  });

  TweenMax.to($(this), 1, {
    scale: 1,
    overwrite: false,
    ease: Elastic.easeOut,
    onComplete: () => {
      questionStatistic.reported++;
      sendStatistic();
      $('#modal-funfact').modal('hide');
      $('#modal-report').modal('show');
    },
  });
});

$(document).on('click', '.btn-next', function (e) {
  playSound('soundClick');
  $(this).addClass('disabled');
  TweenMax.to($(this), 0, {
    scale: 1.2,
    overwrite: false,
    ease: Elastic.easeOut,
  });

  TweenMax.to($(this), 1, {
    scale: 1,
    overwrite: false,
    ease: Elastic.easeOut,
    onComplete: () => {
      displayQuestionResult();
    },
  });
});

$(document).on('click', '#btn-surrender', function (e) {
  playSound('soundClick');

  $('#modal-pause').modal('hide');
  goPage('result');
  resetProgress();

  $('#overlay-answer').addClass('d-none');
  $('.time-action .cs-tooltip').html('Pause');

  $('#time-trigger-start').addClass('d-none');
  $('#time-trigger-pause').removeClass('d-none');
});

function removeA(arr) {
  var what,
    a = arguments,
    L = a.length,
    ax;
  while (L > 1 && arr.length) {
    what = a[--L];
    while ((ax = arr.indexOf(what)) !== -1) {
      arr.splice(ax, 1);
    }
  }
  return arr;
}

$(document).on('click', '.cc-game-report', function (e) {
  // var indexQuestion = $(e.currentTarget).data("index-question");
  playSound('soundClick');

  questionStatistic.reported++;
  sendStatistic();
  $('#modal-report').modal('show');
});

$(document).on('click', '.cc-game-edit', function (e) {
  var id = $(e.currentTarget).data('question-id');
  var url = './edit.php?id=';

  window.open(url + id);
});

$(document).on('click', '#btn-close-report', function (e) {
  playSound('soundClick');

  $('#modal-report').modal('hide');
  prepareNextQuestion();
});

$(document).on('click', '.funfactShareBtn', function (e) {
  playSound('soundClick');

  shareFunfact();
});

$(document).on('click', '.cc-back', function (e) {
  playSound('soundClick');

  goPage('gameMode');
});

$(document).on('click', '#btn-like', function (e) {
  e.preventDefault();
  playSound('soundClick');
  $('#btn-like').addClass('cs-disabled');
  $('#btn-dislike').addClass('cs-disabled');

  TweenMax.to($(this), 0, {
    scale: 1.2,
    overwrite: false,
    ease: Elastic.easeOut,
  });

  TweenMax.to($(this), 1, {
    scale: 1,
    overwrite: false,
    ease: Elastic.easeOut,
    onComplete: () => {
      var postData = {
        id: gameData.targetArray[gameData.sequenceNum].question_id,
      };

      $.ajax({
        type: 'POST',
        url:
          'https://seniorsdiscountclub.com.au/games/trivia/api/question/like-question.php',
        data: JSON.stringify(postData),
        success: function (result) {},
      });
    },
  });
});

$(document).on('click', '#btn-dislike', function (e) {
  e.preventDefault();
  playSound('soundClick');
  $('#btn-like').addClass('cs-disabled');
  $('#btn-dislike').addClass('cs-disabled');

  TweenMax.to($(this), 0, {
    scale: 1.2,
    overwrite: false,
    ease: Elastic.easeOut,
  });

  TweenMax.to($(this), 1, {
    scale: 1,
    overwrite: false,
    ease: Elastic.easeOut,
    onComplete: () => {
      var postData = {
        id: gameData.targetArray[gameData.sequenceNum].question_id,
      };

      $.ajax({
        type: 'POST',
        url:
          'https://seniorsdiscountclub.com.au/games/trivia/api/question/dislike-question.php',
        data: JSON.stringify(postData),
        success: function (result) {},
      });
    },
  });
});

//__info: this needed in order to make the main main loader to be centered vertically when first page load
$(document).on('ready', function () {
  $('#main-loader-logo').load(function () {
    setTopMainLoader();
  });
});

/*!
 *
 * JSON - This is the function that runs to load word from json
 *
 */
function loadJson() {
  $('.preloadText').show();
  $('#buttonStart').hide();

  $.ajax({
    url: 'sample.json',
    type: 'GET',
    dataType: 'json',
    success: function (result) {
      if ($.editor.enable) {
        edit.xmlFile = result;
      }
      //__info: this line intended for pretending the respond
      //from server which is usually stringified
      result = JSON.stringify(result);
      var resultParsed = JSON.parse(result);

      resultParsed.questions.category.forEach(function (catElement, catIndex) {
        gameData.categoryThumb_arr.push({
          src: catElement.thumb,
          name: catElement.name,
        });
      });

      //__info: Check if 10 question already filled in
      var limiter = 10,
        curCategory,
        filtered;

      resultParsed.questions.item.forEach(function (
        questionElement,
        questionIndex
      ) {
        if (curCategory != questionElement.category) {
          curCategory = questionElement.category;
        }

        filtered = _.filter(quesLandscape_arr, function (o) {
          return o.category == curCategory;
        });

        if (filtered.length < limiter) {
          pushDataArrayJson(questionIndex, questionElement);
        }
      });

      loadJsonComplete();
    },
  });
}

function fetchAllCategory() {
  $('.preloadText').show();
  $('#buttonStart').hide();

  $.ajax({
    url: './api/category/read.php',
    type: 'GET',
    dataType: 'json',
    success: function (result) {
      result.records.forEach(function (catElement, catIndex) {
        console.log(catElement);
        //hide all category for now
        if (
          parseInt(catElement.totalQuestion) > 0 &&
          catElement.name != 'All'
        ) {
          // if(catElement.name != 'All') {
          gameData.categoryThumb_arr.push({
            src: 'assets/gamepad.svg',
            name: catElement.name,
            id: catElement.id,
          });

          if (catElement.name != '') {
            gameData.category_arr.push(catElement.name);
          }
        }
      });

      loadJsonComplete();
    },
  });
}

function fetchQuestionByCategory(cat, callback) {
  $.ajax({
    url: './api/question/questions-set.php',
    type: 'POST',
    dataType: 'json',
    contentType: 'text/plain',
    data: JSON.stringify({ category: cat }),
    success: function (result) {
      //__info: Check if 10 question already filled in
      var limiter = 10,
        curCategory,
        filtered;

      result.records.forEach(function (questionElement, questionIndex) {
        if (curCategory != questionElement.category_id) {
          curCategory = questionElement.category_id;
        }

        var catData = _.find(gameData.categoryThumb_arr, function (i) {
          return i.id == curCategory;
        });
        // filtered = _.filter(quesLandscape_arr, function(o) { return o.category == curCategory; });

        if (catData) {
          questionElement.category = catData.name;
        }

        // console.log("fetch question call api check", gameData.targetArray);
        // if(filtered.length < limiter) {
        if (questionIndex < limiter) {
          pushDataArrayFetched(questionIndex, questionElement);
        }
      });

      if (typeof callback === 'function') {
        callback();
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}

function fetchRandomQuestion(callback) {
  $.ajax({
    url: './api/question/questions-set.php',
    type: 'POST',
    dataType: 'json',
    contentType: 'text/plain',
    data: JSON.stringify({ category: 'All' }),
    success: function (result) {
      var limiter = 10,
        curCategory,
        filtered;

      result.records.forEach(function (questionElement, questionIndex) {
        if (curCategory != questionElement.category_id) {
          curCategory = questionElement.category_id;
        }

        var catData = _.find(gameData.categoryThumb_arr, function (i) {
          return i.id == curCategory;
        });

        if (catData) {
          questionElement.category = catData.name;
        }

        if (questionIndex < limiter) {
          pushDataArrayFetched(questionIndex, questionElement);
        }
      });

      if (typeof callback === 'function') {
        callback();
      }
    },
    error: function (err) {
      console.log(err);
    },
  });
}

function pushDataArrayFetched(questionIndex, questionElement) {
  var curCategory = questionElement.category;
  var indexAnswer;
  var queLandTemp;
  var quePortTemp;
  var answers = [];

  //__info: the first one is always true
  answers.push(questionElement.correct_answer);
  answers.push(questionElement.incorrect_answer1);
  answers.push(questionElement.incorrect_answer2);
  answers.push(questionElement.incorrect_answer3);

  //landscape
  var landscapeElement = questionElement;

  queLandTemp = {
    category: curCategory,
    category_id: questionElement.category_id,
    question_id: questionElement.id,
    question: landscapeElement.question,
    image: questionElement.scrap_image,
    title: questionElement.scrap_title,
    info: questionElement.answer_info,
    info: questionElement.answer_info,
    correct_answer: questionElement.correct_answer,
    correct_count: questionElement.correct_count,
    incorrect_count: questionElement.incorrect_count,
    source: questionElement.question_source,
    slug: questionElement.slug,

    fontSize: defaultStyle.question.landscape.fontSize,
    lineHeight: defaultStyle.question.landscape.lineHeight,

    // color:$(landscapeElement).find('question').attr('color'),
    color: '',
    // align:$(landscapeElement).find('question').attr('align'),
    align: '',

    top: defaultStyle.question.landscape.top,
    left: defaultStyle.question.landscape.left,
    width: defaultStyle.question.landscape.width,
    height: defaultStyle.question.landscape.height,

    // type:$(landscapeElement).find('question').attr('type'),
    type: '',
    correctAnswer: '1', //_info: the first index of answer always true
    // drag:$(landscapeElement).find('answers').attr('drag'),
    drag: '',
    groups: [],
    videos: [],
    answer: [],
    input: [],
    // audio:$(landscapeElement).find('question').attr('audio'),
    audio: undefined,
    // explanation:$(landscapeElement).find('explanation').text(),
    explanation: '',

    // explanationFontSize:$(landscapeElement).find('explanation').attr('fontSize'),
    // explanationLineHeight:$(landscapeElement).find('explanation').attr('lineHeight'),
    // explanationColor:$(landscapeElement).find('explanation').attr('color'),
    // explanationAlign:$(landscapeElement).find('explanation').attr('align'),
    // explanationTop:$(landscapeElement).find('explanation').attr('top'),
    // explanationLeft:$(landscapeElement).find('explanation').attr('left'),
    // explanationWidth:$(landscapeElement).find('explanation').attr('width'),
    // explanationHeight:$(landscapeElement).find('explanation').attr('height'),
    // explanationType:$(landscapeElement).find('explanation').attr('type'),
    // explanationAudio:$(landscapeElement).find('explanation').attr('audio'),
    // background:$(landscapeElement).find('background').text(),
    // backgroundTop:$(landscapeElement).find('background').attr('top'),
    // backgroundLeft:$(landscapeElement).find('background').attr('left'),
    // backgroundWidth:$(landscapeElement).find('background').attr('width'),
    // backgroundHeight:$(landscapeElement).find('background').attr('height'),
    explanationFontSize: '',
    explanationLineHeight: '',
    explanationColor: '',
    explanationAlign: '',
    explanationTop: '',
    explanationLeft: '',
    explanationWidth: '',
    explanationHeight: '',
    explanationType: '',
    explanationAudio: '',
    background: '',
    backgroundTop: '',
    backgroundLeft: '',
    backgroundWidth: '',
    backgroundHeight: '',
  };

  indexAnswer = 0;
  answers.forEach(function (answerElement, answerIndex) {
    queLandTemp.answer.push({
      text: answerElement,
      // submit:$(answerElement).attr('submit'),
      submit: undefined,
      // type:$(answerElement).attr('type'),
      type: '',

      width: defaultStyle.answer.landscape[indexAnswer].width,
      height: defaultStyle.answer.landscape[indexAnswer].height,
      top: defaultStyle.answer.landscape[indexAnswer].top,
      left: defaultStyle.answer.landscape[indexAnswer].left,
      fontSize: defaultStyle.answer.landscape[indexAnswer].fontSize,
      lineHeight: defaultStyle.answer.landscape[indexAnswer].lineHeight,

      // color:$(answerElement).attr('color'),
      color: '',
      // align:$(answerElement).attr('align'),
      align: '',
      // audio:$(answerElement).attr('audio'),
      audio: undefined,
      // offsetTop:$(answerElement).attr('offsetTop'),
      offsetTop: '',

      // dropLabelText:$(answerElement).attr('dropLabelText'),
      // dropLabelType:$(answerElement).attr('dropLabelType'),
      // dropLabelWidth:$(answerElement).attr('dropLabelWidth'),
      // dropLabelHeight:$(answerElement).attr('dropLabelHeight'),
      // dropLabelTop:$(answerElement).attr('dropLabelTop'),
      // dropLabelLeft:$(answerElement).attr('dropLabelLeft'),
      // dropLabelFontSize:$(answerElement).attr('dropLabelFontSize'),
      // dropLabelLineHeight:$(answerElement).attr('dropLabelLineHeight'),
      // dropLabelColor:$(answerElement).attr('dropLabelColor'),
      // dropLabelAlign:$(answerElement).attr('dropLabelAlign'),
      // dropLabelOffsetTop:$(answerElement).attr('dropLabelOffsetTop'),
      dropLabelText: '',
      dropLabelType: '',
      dropLabelWidth: '',
      dropLabelHeight: '',
      dropLabelTop: '',
      dropLabelLeft: '',
      dropLabelFontSize: '',
      dropLabelLineHeight: '',
      dropLabelColor: '',
      dropLabelAlign: '',
      dropLabelOffsetTop: '',

      // dragEnable:$(answerElement).attr('dragEnable'),
      // dropEnable:$(answerElement).attr('dropEnable'),
      // dropLeft:$(answerElement).attr('dropLeft'),
      // dropTop:$(answerElement).attr('dropTop'),
      // dropWidth:$(answerElement).attr('dropWidth'),
      // dropHeight:$(answerElement).attr('dropHeight'),
      // dropOffLeft:$(answerElement).attr('dropOffLeft'),
      // dropOffTop:$(answerElement).attr('dropOffTop'),
      dragEnable: '',
      dropEnable: '',
      dropLeft: '',
      dropTop: '',
      dropWidth: '',
      dropHeight: '',
      dropOffLeft: '',
      dropOffTop: '',
    });

    indexAnswer++;
  });

  quesLandscape_arr.push(queLandTemp);

  //portrait
  var portraitElement = questionElement;

  quePortTemp = {
    category: curCategory,
    category_id: questionElement.category_id,
    question_id: questionElement.id,
    question: portraitElement.question,
    image: questionElement.scrap_image,
    title: questionElement.scrap_title,
    info: questionElement.answer_info,
    correct_count: questionElement.correct_count,
    incorrect_count: questionElement.incorrect_count,
    correct_answer: questionElement.correct_answer,
    source: questionElement.question_source,
    slug: questionElement.slug,

    fontSize: defaultStyle.question.portrait.fontSize,
    lineHeight: defaultStyle.question.portrait.lineHeight,

    align: $(portraitElement).find('question').attr('align'),

    top: defaultStyle.question.portrait.top,
    left: defaultStyle.question.portrait.left,
    width: defaultStyle.question.portrait.width,
    height: defaultStyle.question.portrait.height,

    // type:$(portraitElement).find('question').attr('type'),
    type: '',
    // correctAnswer:$(portraitElement).find('answers').attr('correctAnswer'),
    correctAnswer: '1', //_info: the first index of answer always true
    // drag:$(portraitElement).find('answers').attr('drag'),
    drag: '',
    // color:$(portraitElement).find('answers').attr('color'),
    color: '',
    groups: [],
    videos: [],
    answer: [],
    input: [],

    // audio:$(portraitElement).find('question').attr('audio'),
    // explanation:$(portraitElement).find('explanation').text(),
    // explanationFontSize:$(portraitElement).find('explanation').attr('fontSize'),
    // explanationLineHeight:$(portraitElement).find('explanation').attr('lineHeight'),
    // explanationColor:$(portraitElement).find('explanation').attr('color'),
    // explanationAlign:$(portraitElement).find('explanation').attr('align'),
    // explanationTop:$(portraitElement).find('explanation').attr('top'),
    // explanationLeft:$(portraitElement).find('explanation').attr('left'),
    // explanationWidth:$(portraitElement).find('explanation').attr('width'),
    // explanationHeight:$(portraitElement).find('explanation').attr('height'),
    // explanationType:$(portraitElement).find('explanation').attr('type'),
    // explanationAudio:$(portraitElement).find('explanation').attr('audio'),
    // background:$(portraitElement).find('background').text(),
    // backgroundTop:$(portraitElement).find('background').attr('top'),
    // backgroundLeft:$(portraitElement).find('background').attr('left'),
    // backgroundWidth:$(portraitElement).find('background').attr('width'),
    // backgroundHeight:$(portraitElement).find('background').attr('height'),
    audio: undefined,
    explanation: '',
    explanationFontSize: '',
    explanationLineHeight: '',
    explanationColor: '',
    explanationAlign: '',
    explanationTop: '',
    explanationLeft: '',
    explanationWidth: '',
    explanationHeight: '',
    explanationType: '',
    explanationAudio: '',
    background: '',
    backgroundTop: '',
    backgroundLeft: '',
    backgroundWidth: '',
    backgroundHeight: '',
  };

  indexAnswer = 0;
  answers.forEach(function (answerElement, answerIndex) {
    quePortTemp.answer.push({
      text: answerElement,

      // submit:$(answerElement).attr('submit'),
      submit: undefined,
      // type:$(answerElement).attr('type'),
      type: '',

      width: defaultStyle.answer.portrait[indexAnswer].width,
      height: defaultStyle.answer.portrait[indexAnswer].height,
      top: defaultStyle.answer.portrait[indexAnswer].top,
      left: defaultStyle.answer.portrait[indexAnswer].left,
      fontSize: defaultStyle.answer.landscape[indexAnswer].fontSize,
      lineHeight: defaultStyle.answer.landscape[indexAnswer].lineHeight,

      // color:$(answerElement).attr('color'),
      color: '',
      // align:$(answerElement).attr('align'),
      align: '',
      // audio:$(answerElement).attr('audio'),
      audio: undefined,
      // offsetTop:$(answerElement).attr('offsetTop'),
      offsetTop: '',

      // dropLabelText:$(answerElement).attr('dropLabelText'),
      // dropLabelType:$(answerElement).attr('dropLabelType'),
      // dropLabelWidth:$(answerElement).attr('dropLabelWidth'),
      // dropLabelHeight:$(answerElement).attr('dropLabelHeight'),
      // dropLabelTop:$(answerElement).attr('dropLabelTop'),
      // dropLabelLeft:$(answerElement).attr('dropLabelLeft'),
      // dropLabelFontSize:$(answerElement).attr('dropLabelFontSize'),
      // dropLabelLineHeight:$(answerElement).attr('dropLabelLineHeight'),
      // dropLabelColor:$(answerElement).attr('dropLabelColor'),
      // dropLabelAlign:$(answerElement).attr('dropLabelAlign'),
      // dropLabelOffsetTop:$(answerElement).attr('dropLabelOffsetTop'),
      dropLabelText: '',
      dropLabelType: '',
      dropLabelWidth: '',
      dropLabelHeight: '',
      dropLabelTop: '',
      dropLabelLeft: '',
      dropLabelFontSize: '',
      dropLabelLineHeight: '',
      dropLabelColor: '',
      dropLabelAlign: '',
      dropLabelOffsetTop: '',

      // dragEnable:$(answerElement).attr('dragEnable'),
      // dropEnable:$(answerElement).attr('dropEnable'),
      // dropLeft:$(answerElement).attr('dropLeft'),
      // dropTop:$(answerElement).attr('dropTop'),
      // dropWidth:$(answerElement).attr('dropWidth'),
      // dropHeight:$(answerElement).attr('dropHeight'),
      // dropOffLeft:$(answerElement).attr('dropOffLeft'),
      // dropOffTop:$(answerElement).attr('dropOffTop'),
      dragEnable: '',
      dropEnable: '',
      dropLeft: '',
      dropTop: '',
      dropWidth: '',
      dropHeight: '',
      dropOffLeft: '',
      dropOffTop: '',
    });

    indexAnswer++;
  });

  quesPortrait_arr.push(quePortTemp);
}

//__info: this method only assume that all the question type is textual, no image, input or video parsed,
// if there's change in the future that need to load another type of question,
// please refer to pushDataArray to implement all of it's feature.
function pushDataArrayJson(questionIndex, questionElement) {
  var curCategory = questionElement.category;
  var indexAnswer;
  var queLandTemp;
  var quePortTemp;

  if (curCategory != '') {
    gameData.category_arr.push(curCategory);
  }

  //landscape
  var landscapeElement = questionElement.landscape;

  queLandTemp = {
    category: curCategory,
    question: landscapeElement.question,

    fontSize: defaultStyle.question.landscape.fontSize,
    lineHeight: defaultStyle.question.landscape.lineHeight,

    // color:$(landscapeElement).find('question').attr('color'),
    color: '',
    // align:$(landscapeElement).find('question').attr('align'),
    align: '',

    top: defaultStyle.question.landscape.top,
    left: defaultStyle.question.landscape.left,
    width: defaultStyle.question.landscape.width,
    height: defaultStyle.question.landscape.height,

    // type:$(landscapeElement).find('question').attr('type'),
    type: '',
    correctAnswer: landscapeElement.correctAnswer,
    // drag:$(landscapeElement).find('answers').attr('drag'),
    drag: '',
    groups: [],
    videos: [],
    answer: [],
    input: [],
    // audio:$(landscapeElement).find('question').attr('audio'),
    audio: undefined,
    // explanation:$(landscapeElement).find('explanation').text(),
    explanation: '',

    // explanationFontSize:$(landscapeElement).find('explanation').attr('fontSize'),
    // explanationLineHeight:$(landscapeElement).find('explanation').attr('lineHeight'),
    // explanationColor:$(landscapeElement).find('explanation').attr('color'),
    // explanationAlign:$(landscapeElement).find('explanation').attr('align'),
    // explanationTop:$(landscapeElement).find('explanation').attr('top'),
    // explanationLeft:$(landscapeElement).find('explanation').attr('left'),
    // explanationWidth:$(landscapeElement).find('explanation').attr('width'),
    // explanationHeight:$(landscapeElement).find('explanation').attr('height'),
    // explanationType:$(landscapeElement).find('explanation').attr('type'),
    // explanationAudio:$(landscapeElement).find('explanation').attr('audio'),
    // background:$(landscapeElement).find('background').text(),
    // backgroundTop:$(landscapeElement).find('background').attr('top'),
    // backgroundLeft:$(landscapeElement).find('background').attr('left'),
    // backgroundWidth:$(landscapeElement).find('background').attr('width'),
    // backgroundHeight:$(landscapeElement).find('background').attr('height'),
    explanationFontSize: '',
    explanationLineHeight: '',
    explanationColor: '',
    explanationAlign: '',
    explanationTop: '',
    explanationLeft: '',
    explanationWidth: '',
    explanationHeight: '',
    explanationType: '',
    explanationAudio: '',
    background: '',
    backgroundTop: '',
    backgroundLeft: '',
    backgroundWidth: '',
    backgroundHeight: '',
  };

  indexAnswer = 0;
  landscapeElement.answers.forEach(function (answerElement, answerIndex) {
    queLandTemp.answer.push({
      text: answerElement,
      // submit:$(answerElement).attr('submit'),
      submit: undefined,
      // type:$(answerElement).attr('type'),
      type: '',

      width: defaultStyle.answer.landscape[indexAnswer].width,
      height: defaultStyle.answer.landscape[indexAnswer].height,
      top: defaultStyle.answer.landscape[indexAnswer].top,
      left: defaultStyle.answer.landscape[indexAnswer].left,
      fontSize: defaultStyle.answer.landscape[indexAnswer].fontSize,
      lineHeight: defaultStyle.answer.landscape[indexAnswer].lineHeight,

      // color:$(answerElement).attr('color'),
      color: '',
      // align:$(answerElement).attr('align'),
      align: '',
      // audio:$(answerElement).attr('audio'),
      audio: undefined,
      // offsetTop:$(answerElement).attr('offsetTop'),
      offsetTop: '',

      // dropLabelText:$(answerElement).attr('dropLabelText'),
      // dropLabelType:$(answerElement).attr('dropLabelType'),
      // dropLabelWidth:$(answerElement).attr('dropLabelWidth'),
      // dropLabelHeight:$(answerElement).attr('dropLabelHeight'),
      // dropLabelTop:$(answerElement).attr('dropLabelTop'),
      // dropLabelLeft:$(answerElement).attr('dropLabelLeft'),
      // dropLabelFontSize:$(answerElement).attr('dropLabelFontSize'),
      // dropLabelLineHeight:$(answerElement).attr('dropLabelLineHeight'),
      // dropLabelColor:$(answerElement).attr('dropLabelColor'),
      // dropLabelAlign:$(answerElement).attr('dropLabelAlign'),
      // dropLabelOffsetTop:$(answerElement).attr('dropLabelOffsetTop'),
      dropLabelText: '',
      dropLabelType: '',
      dropLabelWidth: '',
      dropLabelHeight: '',
      dropLabelTop: '',
      dropLabelLeft: '',
      dropLabelFontSize: '',
      dropLabelLineHeight: '',
      dropLabelColor: '',
      dropLabelAlign: '',
      dropLabelOffsetTop: '',

      // dragEnable:$(answerElement).attr('dragEnable'),
      // dropEnable:$(answerElement).attr('dropEnable'),
      // dropLeft:$(answerElement).attr('dropLeft'),
      // dropTop:$(answerElement).attr('dropTop'),
      // dropWidth:$(answerElement).attr('dropWidth'),
      // dropHeight:$(answerElement).attr('dropHeight'),
      // dropOffLeft:$(answerElement).attr('dropOffLeft'),
      // dropOffTop:$(answerElement).attr('dropOffTop'),
      dragEnable: '',
      dropEnable: '',
      dropLeft: '',
      dropTop: '',
      dropWidth: '',
      dropHeight: '',
      dropOffLeft: '',
      dropOffTop: '',
    });

    indexAnswer++;
  });

  quesLandscape_arr.push(queLandTemp);

  //portrait
  var portraitElement = questionElement.portrait;

  quePortTemp = {
    category: curCategory,
    question: portraitElement.question,

    fontSize: defaultStyle.question.portrait.fontSize,
    lineHeight: defaultStyle.question.portrait.lineHeight,

    align: $(portraitElement).find('question').attr('align'),

    top: defaultStyle.question.portrait.top,
    left: defaultStyle.question.portrait.left,
    width: defaultStyle.question.portrait.width,
    height: defaultStyle.question.portrait.height,

    // type:$(portraitElement).find('question').attr('type'),
    type: '',
    // correctAnswer:$(portraitElement).find('answers').attr('correctAnswer'),
    correctAnswer: portraitElement.correctAnswer,
    // drag:$(portraitElement).find('answers').attr('drag'),
    drag: '',
    // color:$(portraitElement).find('answers').attr('color'),
    color: '',
    groups: [],
    videos: [],
    answer: [],
    input: [],

    // audio:$(portraitElement).find('question').attr('audio'),
    // explanation:$(portraitElement).find('explanation').text(),
    // explanationFontSize:$(portraitElement).find('explanation').attr('fontSize'),
    // explanationLineHeight:$(portraitElement).find('explanation').attr('lineHeight'),
    // explanationColor:$(portraitElement).find('explanation').attr('color'),
    // explanationAlign:$(portraitElement).find('explanation').attr('align'),
    // explanationTop:$(portraitElement).find('explanation').attr('top'),
    // explanationLeft:$(portraitElement).find('explanation').attr('left'),
    // explanationWidth:$(portraitElement).find('explanation').attr('width'),
    // explanationHeight:$(portraitElement).find('explanation').attr('height'),
    // explanationType:$(portraitElement).find('explanation').attr('type'),
    // explanationAudio:$(portraitElement).find('explanation').attr('audio'),
    // background:$(portraitElement).find('background').text(),
    // backgroundTop:$(portraitElement).find('background').attr('top'),
    // backgroundLeft:$(portraitElement).find('background').attr('left'),
    // backgroundWidth:$(portraitElement).find('background').attr('width'),
    // backgroundHeight:$(portraitElement).find('background').attr('height'),
    audio: undefined,
    explanation: '',
    explanationFontSize: '',
    explanationLineHeight: '',
    explanationColor: '',
    explanationAlign: '',
    explanationTop: '',
    explanationLeft: '',
    explanationWidth: '',
    explanationHeight: '',
    explanationType: '',
    explanationAudio: '',
    background: '',
    backgroundTop: '',
    backgroundLeft: '',
    backgroundWidth: '',
    backgroundHeight: '',
  };

  indexAnswer = 0;
  portraitElement.answers.forEach(function (answerElement, answerIndex) {
    quePortTemp.answer.push({
      text: answerElement,

      // submit:$(answerElement).attr('submit'),
      submit: undefined,
      // type:$(answerElement).attr('type'),
      type: '',

      width: defaultStyle.answer.portrait[indexAnswer].width,
      height: defaultStyle.answer.portrait[indexAnswer].height,
      top: defaultStyle.answer.portrait[indexAnswer].top,
      left: defaultStyle.answer.portrait[indexAnswer].left,
      fontSize: defaultStyle.answer.landscape[indexAnswer].fontSize,
      lineHeight: defaultStyle.answer.landscape[indexAnswer].lineHeight,

      // color:$(answerElement).attr('color'),
      color: '',
      // align:$(answerElement).attr('align'),
      align: '',
      // audio:$(answerElement).attr('audio'),
      audio: undefined,
      // offsetTop:$(answerElement).attr('offsetTop'),
      offsetTop: '',

      // dropLabelText:$(answerElement).attr('dropLabelText'),
      // dropLabelType:$(answerElement).attr('dropLabelType'),
      // dropLabelWidth:$(answerElement).attr('dropLabelWidth'),
      // dropLabelHeight:$(answerElement).attr('dropLabelHeight'),
      // dropLabelTop:$(answerElement).attr('dropLabelTop'),
      // dropLabelLeft:$(answerElement).attr('dropLabelLeft'),
      // dropLabelFontSize:$(answerElement).attr('dropLabelFontSize'),
      // dropLabelLineHeight:$(answerElement).attr('dropLabelLineHeight'),
      // dropLabelColor:$(answerElement).attr('dropLabelColor'),
      // dropLabelAlign:$(answerElement).attr('dropLabelAlign'),
      // dropLabelOffsetTop:$(answerElement).attr('dropLabelOffsetTop'),
      dropLabelText: '',
      dropLabelType: '',
      dropLabelWidth: '',
      dropLabelHeight: '',
      dropLabelTop: '',
      dropLabelLeft: '',
      dropLabelFontSize: '',
      dropLabelLineHeight: '',
      dropLabelColor: '',
      dropLabelAlign: '',
      dropLabelOffsetTop: '',

      // dragEnable:$(answerElement).attr('dragEnable'),
      // dropEnable:$(answerElement).attr('dropEnable'),
      // dropLeft:$(answerElement).attr('dropLeft'),
      // dropTop:$(answerElement).attr('dropTop'),
      // dropWidth:$(answerElement).attr('dropWidth'),
      // dropHeight:$(answerElement).attr('dropHeight'),
      // dropOffLeft:$(answerElement).attr('dropOffLeft'),
      // dropOffTop:$(answerElement).attr('dropOffTop'),
      dragEnable: '',
      dropEnable: '',
      dropLeft: '',
      dropTop: '',
      dropWidth: '',
      dropHeight: '',
      dropOffLeft: '',
      dropOffTop: '',
    });

    indexAnswer++;
  });

  quesPortrait_arr.push(quePortTemp);
}

function loadJsonComplete() {
  loadXMLComplete();
}

//remove question when resize
// $( window ).on( "resize", function( event ) {
//     if(gameData.page == 'game') {
//         $('#questionHolder').html('');
//     }
// });

function debounce(func){
  var timer;
  return function(event){
    if(timer) clearTimeout(timer);
    timer = setTimeout(func,100,event);
  };
}