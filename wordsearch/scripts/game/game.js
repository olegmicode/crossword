const gameWrapper = $("#gameWrapper");
const boardWrapper = $("#boardWrapper");
const board = $("#board");
const wordWrapper = $("#words");
const boardCanvas = $("#boardCanvas");

let cells;
let shuffledDirections;
let sortedClues;
let words;
let clueMap;
let cellMap;
let tryCount;
let boardSetting = {
  directions: [
    "toRight",
    "toLeft",
    "toBottom",
    "toTop",
    "toRightTop",
    "toRightBottom",
    "toLeftTop",
    "toLeftBottom",
  ],
  chars: [
    "a",
    "b",
    "c",
    "d",
    "e",
    "f",
    "g",
    "h",
    "i",
    "j",
    "k",
    "l",
    "m",
    "n",
    "o",
    "p",
    "r",
    "s",
    "t",
    "u",
    "v",
    "w",
    "y",
  ],
  loopCount: 30,
  colorClass: [
    "#fa3737",
    "#1dca4d",
    "#379cfa",
    "#fab837",
    // "purple",
    // "pink",
    // "tosca",
    // "navy",
    // "orange",
  ],
};
let clicking;
let selectedCells = [];
let cellSelectionStart = null;
let boxSize = 0;

let timeOut;

let correctCellsList = [];

$(() => {
  initBoardEvents();
  // if (windowWidth < 768) {
  initMobileBoardEvents();
  // }
});

const initBoard = () => {
  show(loader);
  tryCount = 0;
  clueMap = [];
  cellMap = [];
  words = [];
correctCellsList = [];
  sortedClues = [];
  gameData.words.forEach((w, i) => {
    words.push(w.name.split(" ").join(""));
  });
  initGrid();
};

const initGrid = () => {
  board.empty();
  for (let y = 0; y < parseInt(gameData.boardSize); y++) {
    for (let x = 0; x < parseInt(gameData.boardSize); x++) {
      let cell = {
        x: x,
        y: y,
      };
      let cellElement =
        '<div class="board__cell d-flex align-items-center justify-content-center" data-x=' +
        x +
        " data-y=" +
        y +
        "></div>";
      board.append(cellElement);
      cellMap.push(cell);
    }
  }
  cells = $(".board__cell");

  resizeBoard();
  setTimeout(() => {
    initClues();
  }, 500);
};

const resetBoardSize = () => {
  gameWrapper.css("height", "100%");
  $(".board__cell").width("").height("");
  board.width("").height("");
  boardWrapper.height("");
  boardWrapper.width("");
  boardWrapper.addClass("flex-fill");
};

const resizeBoard = () => {
  const maxHeight = boardWrapper.height() - 2;
  const maxWidth = boardWrapper.width() - 2;
  cells = $(".board__cell");

  if (windowWidth < 768) {
    boardWrapper.removeClass("flex-fill");
  }
  if (maxHeight >= maxWidth) {
    board.width(maxWidth).height(maxWidth);
    boardWrapper.width(maxWidth + 2).height(maxWidth + 2);
    boxSize = maxWidth / parseInt(gameData.boardSize) - 2;
  } else {
    board.width(maxHeight).height(maxHeight);
    boardWrapper.width(maxHeight + 2).height(maxHeight + 2);
    boxSize = maxWidth / parseInt(gameData.boardSize) - 2;
  }

  let canvas = document.getElementById("boardCanvas");
  canvas.style.width = "100%";
  canvas.style.height = "100%";
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
  cells.width(boxSize).height(boxSize);
  gameWrapper.css("height", "fit-content");
  
  correctCellsList.forEach(x => {
    drawCorrectLine(
    x.startX,
    x.startY,
    x.endX,
    x.endY,
    x.color
  );
  })
};

const initClues = () => {
  shuffledDirections = shuffleArray([...boardSetting.directions]);
  sortedClues = sortArrayByLength([...words], false);

  fillClues();
};

const fillClues = () => {
  for (let c = 0; c < sortedClues.length; c++) {
    for (let d = 0; d < shuffledDirections.length; d++) {
      let randX = Math.floor(Math.random() * parseInt(gameData.boardSize));
      let randY = Math.floor(Math.random() * parseInt(gameData.boardSize));
      let randCell = cellMap.find((x) => x.x == randX && x.y == randY);
      if (checkFit(sortedClues[c], randCell, shuffledDirections[d])) {
        fillCell(sortedClues[c], randCell, shuffledDirections[d]);
        sortedClues.splice(c, 1, null);
        break;
      }
    }
  }

  if (
    sortedClues.filter((x) => x != null).length > 0 &&
    tryCount <= boardSetting.loopCount
  ) {
    tryCount++;
    sortedClues = sortedClues.filter((x) => x != null);
    fillClues();
  } else {
    fillEmptyCells();
    initClueButtons();
  }
};

const fillEmptyCells = () => {
  let emptyCells = $(cells).filter(function () {
    return $(this).html() == "";
  });
  emptyCells.each((i, x) => {
    let randChar =
      boardSetting.chars[Math.floor(Math.random() * boardSetting.chars.length)];
    $(x).html(randChar.toUpperCase());
  });
};

const clearBoard = () => {
  $(cells).html("");
};

const checkFit = (word, startCell, directions) => {
  let isFit = true;

  switch (directions) {
    case "toRight":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x + i, startCell.y);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    case "toLeft":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x - i, startCell.y);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    case "toTop":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x, startCell.y - i);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    case "toBottom":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x, startCell.y + i);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    case "toRightTop":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x + i, startCell.y - i);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    case "toRightBottom":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x + i, startCell.y + i);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    case "toLeftTop":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x - i, startCell.y - i);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    case "toLeftBottom":
      for (let i = 0; i < word.length; i++) {
        let cellElement = getCell(startCell.x - i, startCell.y + i);
        if (cellElement == undefined || $(cellElement).html() != "") {
          isFit = false;
        }
      }
      break;

    default:
      break;
  }
  return isFit;
};

const fillCell = (word, startCell, directions) => {
  let charArray = word.split("");
  let clueCell = {
    word: word,
    direction: directions,
    cells: [],
    answered: false,
  };
  switch (directions) {
    case "toRight":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x + i,
          y: startCell.y,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x + i, startCell.y);
        $(cellElement).html(c);
      });

      break;

    case "toLeft":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x - i,
          y: startCell.y,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x - i, startCell.y);
        $(cellElement).html(c);
      });
      break;

    case "toTop":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x,
          y: startCell.y - i,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x, startCell.y - i);
        $(cellElement).html(c);
      });
      break;

    case "toBottom":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x,
          y: startCell.y + i,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x, startCell.y + i);
        $(cellElement).html(c);
      });
      break;

    case "toRightTop":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x + i,
          y: startCell.y - i,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x + i, startCell.y - i);
        $(cellElement).html(c);
      });
      break;

    case "toRightBottom":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x + i,
          y: startCell.y + i,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x + i, startCell.y + i);
        $(cellElement).html(c);
      });
      break;

    case "toLeftTop":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x - i,
          y: startCell.y - i,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x - i, startCell.y - i);
        $(cellElement).html(c);
      });
      break;

    case "toLeftBottom":
      charArray.forEach((c, i) => {
        let cell = {
          x: startCell.x - i,
          y: startCell.y + i,
        };
        clueCell.cells.push(cell);
        let cellElement = getCell(startCell.x - i, startCell.y + i);
        $(cellElement).html(c);
      });
      break;

    default:
      break;
  }

  clueMap.push(clueCell);
};

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}

const sortArrayByLength = (array, isAsc = true) => {
  if (isAsc) {
    array.sort((a, b) => a.length - b.length);
  } else {
    array.sort((a, b) => b.length - a.length);
  }

  return array;
};

const getCell = (x, y) => {
  return $(board).find(`[data-x=${x}][data-y=${y}]`)[0];
};

const initClueButtons = () => {
  $(".btn--words").remove();
  let shuffledClueWords = shuffleArray([...clueMap]);
  shuffledClueWords.forEach((c, i) => {
    let clueButton =
      '<div class="btn btn--words mr-1 mb-1 mr-md-2 mb-md-2" data-val=' +
      c.word +
      ">" +
      c.word +
      "</div>";
    $(wordWrapper).append(clueButton);
  });
  updateWordsRemain();
  gameWrapper.removeClass("hide");
  timerButton.removeClass("hide");
  hide(loader);
  
};

const initBoardEvents = () => {
  board
    .mousedown((e) => {
      clicking = true;
    })
    .mouseup(() => {
      clicking = false;
      cells.removeClass("select");
      cellSelectionStart = null;
      checkSelection();
    })
    .mouseleave(() => {
      clicking = false;
      cells.removeClass("select");
      cellSelectionStart = null;
    });

  $(document).on("mousedown", ".board__cell", function (e) {
    if (cellSelectionStart == null) {
      cellSelectionStart = $(e.currentTarget);
      cellSelectionStart.addClass("select");
    }
  });

  $(document).on("mouseenter", ".board__cell", function (e) {
    if (clicking) {
      let direction = getDirections(cellSelectionStart, $(e.currentTarget));
      if (direction != null) {
        getCellsOnDirection(cellSelectionStart, $(e.currentTarget), direction);
      }
    }
  });
};

const initMobileBoardEvents = () => {
  $(board).on("touchstart", (e) => {
    e.preventDefault();
    e.stopPropagation();
    clicking = true;
    if (cellSelectionStart == null) {
      cellSelectionStart = $(e.target);
      cellSelectionStart.addClass("select");
    }
  });

  $(board).on("touchmove", (e) => {
    e.preventDefault();
    e.stopPropagation();
    var myLocation = e.originalEvent.changedTouches[0];
    var realTarget = document.elementFromPoint(
      myLocation.clientX,
      myLocation.clientY
    );
    if (clicking) {
      let direction = getDirections(cellSelectionStart, $(realTarget));
      if (direction != null) {
        getCellsOnDirection(cellSelectionStart, $(realTarget), direction);
      }
    }
  });

  $(board).on("touchend", (e) => {
    e.preventDefault();
    e.stopPropagation();
    clicking = false;
    cells.removeClass("select");
    cellSelectionStart = null;
    checkSelection();
  });
};

const getDirections = (startCell, endCell) => {
  let startX = $(startCell).attr("data-x");
  let startY = $(startCell).attr("data-y");
  let endX = $(endCell).attr("data-x");
  let endY = $(endCell).attr("data-y");
  let direction = "";
  let hDistance = endY - startY;
  let vDistance = endX - startX;

  if (vDistance < 0) {
    if (hDistance == 0) {
      direction = "toLeft";
    } else if (vDistance == hDistance) {
      direction = "toLeftTop";
    } else if (Math.abs(vDistance) == hDistance) {
      direction = "toLeftBottom";
    }
  } else if (vDistance > 0) {
    if (hDistance == 0) {
      direction = "toRight";
    } else if (vDistance == hDistance) {
      direction = "toRightBottom";
    } else if (vDistance == Math.abs(hDistance)) {
      direction = "toRightTop";
    }
  } else if (vDistance == 0) {
    if (hDistance > 0) {
      direction = "toBottom";
    } else if (hDistance < 0) {
      direction = "toTop";
    }
  } else {
    direction = null;
  }

  return direction;
};

const getCellsOnDirection = (startCell, endCell, direction) => {
  let startX = parseInt($(startCell).attr("data-x"));
  let startY = parseInt($(startCell).attr("data-y"));
  let endX = parseInt($(endCell).attr("data-x"));
  let endY = parseInt($(endCell).attr("data-y"));
  selectedCells.forEach((x) => {
    $(x).removeClass("select");
  });
  selectedCells = [];
  cellSelectionStart.addClass("select");

  switch (direction) {
    case "toRight":
      for (let x = startX; x <= endX; x++) {
        let cell = getCell(x, startY);
        selectedCells.push($(cell));
      }
      break;

    case "toRightBottom":
      for (let x = 0; x + parseInt(startX) <= endX; x++) {
        let cell = getCell(x + parseInt(startX), x + parseInt(startY));
        selectedCells.push($(cell));
      }
      break;

    case "toRightTop":
      for (let x = 0; x + parseInt(startX) <= endX; x++) {
        let cell = getCell(x + parseInt(startX), x * -1 + parseInt(startY));
        selectedCells.push($(cell));
      }
      break;

    case "toLeft":
      for (let x = startX; x >= endX; x--) {
        let cell = getCell(parseInt(x), parseInt(startY));
        selectedCells.push($(cell));
      }
      break;

    case "toLeftBottom":
      for (let x = 0; x + parseInt(startX) >= endX; x--) {
        let cell = getCell(x + parseInt(startX), x * -1 + parseInt(startY));
        selectedCells.push($(cell));
      }
      break;

    case "toLeftTop":
      for (let x = 0; x + parseInt(startX) >= endX; x--) {
        let cell = getCell(x + parseInt(startX), x + parseInt(startY));
        selectedCells.push($(cell));
      }
      break;

    case "toBottom":
      for (let y = startY; y <= endY; y++) {
        let cell = getCell(startX, y);
        selectedCells.push($(cell));
      }
      break;

    case "toTop":
      for (let y = startY; y >= endY; y--) {
        let cell = getCell(startX, y);
        selectedCells.push($(cell));
      }
      break;

    default:
      break;
  }

  selectedCells.forEach((x, i) => {
    $(x).addClass("select");
  });
};

const checkSelection = () => {
  let answer = "";
  selectedCells.forEach((c) => {
    answer = answer + $(c).html();
  });

  let correctCell = clueMap.find(
    (x) => x.word == answer && x.answered == false
  );

   //if wrong then reverse answer and check again
   if(!correctCell){
    let charStacks = answer.split("");
    answer = "";
    for(let i=charStacks.length-1; i>=0; i--){
      answer += charStacks[i];
    }
    correctCell = clueMap.find(
      (x) => x.word == answer && x.answered == false
    );
  }

  if (correctCell) {
    correctCell.answered = true;
    $(".btn--words[data-val='" + answer + "']").addClass("disabled");
    setCorrect(correctCell.cells);
    
  }
};

const setCorrect = (correctCells, isReveal = false) => {
  if (isReveal) {
    setScore(false)
  } else {
    setScore(true);
  }
  playSound("soundCorrect");
  updateWordsRemain();

  let corrects = clueMap.filter((x) => x.answered == true).length;
  let color =
    boardSetting.colorClass[corrects % boardSetting.colorClass.length];
  let crCell = {
    startX:  correctCells[0].x,
    startY: correctCells[0].y,
    endX: correctCells[correctCells.length - 1].x,
    endY: correctCells[correctCells.length - 1].y,
    color: color
  };

  correctCellsList.push(crCell);
  drawCorrectLine(
    correctCells[0].x,
    correctCells[0].y,
    correctCells[correctCells.length - 1].x,
    correctCells[correctCells.length - 1].y,
    color
  );
  correctCells.forEach((map, i) => {
    let cell = getCell(map.x, map.y);
    $(cell).addClass("correct");
  });

  clearTimeout(timeOut);
  resetHintMode();
  $(cells).removeClass("hint");
  $(".btn--words").removeClass("active");

  setTimeout(() => {
    checkFinish();
  }, 2000);
};

const checkFinish = () => {
  if (clueMap.filter((x) => x.answered == false).length == 0) {
    clearTimeout(timeOut);
    resetHintMode();
    stopGame();
  }
};

const getCellCenter = (x, y) => {
  const cell = getCell(x, y);
  const pos = $(cell).position();
  const width = $(cell).width() + 2;
  const height = $(cell).height() + 2;

  return {
    x: pos.left + width / 2,
    y: pos.top + height / 2,
  };
};

const drawLine = (start, end, color) => {
  let canvas = document.getElementById("boardCanvas");

  if (canvas.getContext) {
    let ctx = canvas.getContext("2d");
    ctx.fillStyle = color;
    ctx.strokeStyle = color;

    ctx.beginPath();
    ctx.lineCap = "round";
    ctx.moveTo(start.x, start.y);
    ctx.lineTo(end.x, end.y);
    ctx.lineWidth = 25;
    ctx.stroke();
  } else {
    console.log("cant get canvas context");
  }
};

const drawCorrectLine = (startX, startY, endX, endY, color) => {
  drawLine(getCellCenter(startX, startY), getCellCenter(endX, endY), color);
};

const checkWord = () => {
  if (gameData.checkWordCount > 0) {
    checkWordMode = true;
    $(".btn--words").addClass("active");
    checkLetterButton.addClass("disabled");
    checkWordButton.addClass("disabled");
    hintText.html("HINT ACTIVE. SELECT WORD TO SEE THE PATTERN.");
    
    hintTextWrapper.show();
  } else {
    clearTimeout(timeOut);
    hintText.html("ALL OF YOUR HINT HAS BEED USED.");
    hintTextWrapper.show();
  }

  timeOut = setTimeout(() => {
    resetHintMode();
  }, 5000);
};

const checkLetter = () => {
  if (gameData.checkLetterCount > 0) {
    $(".btn--words").addClass("active");
    checkLetterMode = true;
    checkLetterButton.addClass("disabled");
    checkWordButton.addClass("disabled");
    hintText.html("HINT ACTIVE. SELECT WORD TO SEE THE PATTERN.");
    hintTextWrapper.show();
    timeOut = setTimeout(() => {
      resetHintMode();
    }, 5000);
  } else {
    clearTimeout(timeOut);

    hintText.html("ALL OF YOUR HINT HAS BEED USED.");
    hintTextWrapper.show();
  }

  timeOut = setTimeout(() => {
    resetHintMode();
  }, 5000);
};

$(document).on("click", ".btn--words", function (e) {
  if (checkLetterMode) {
    gameData.checkLetterCount--;
    setHintCount();
    let word = $(e.currentTarget).html();
    let firstLetter = word.split("")[0];
    $(cells).filter(function () {
      if ($(this).html() == firstLetter && !$(this).hasClass("correct")) {
        $(this).addClass("hint");
      }
    });
    $(e.currentTarget).addClass("clicked");
    clearTimeout(timeOut);
    resetHintMode();
    setTimeout(() => {
      $(cells).removeClass("hint");
      $(".btn--words").removeClass("clicked");
    }, 5000);
  }

  if (checkWordMode) {
    gameData.checkWordCount--;
    setHintCount();
    let correctCell = clueMap.find((x) => x.word == $(e.currentTarget).html());
    correctCell.answered = true;
    $(e.currentTarget).addClass("disabled");
    setCorrect(correctCell.cells, true);
    clearTimeout(timeOut);
    resetHintMode();
  }
});

const resetHintMode = () => {
  hintTextWrapper.hide();
  checkLetterMode = false;
  checkWordMode = false;
  checkLetterButton.removeClass("disabled");
  checkWordButton.removeClass("disabled");
  $(".btn--words").removeClass("active");
};

const updateWordsRemain = () => {
  let remain = $(".btn--words:not(.disabled)");
  wordCount.text(remain.length)
}