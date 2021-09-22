const boardWrapper = $("#boardWrapper");
const board = $("#board");
const numberSelector = $(".btn-number-selector");

let grids;
let cells;
let cellMap = [];
let originalGameData;
const gridGap = 10;
let gridSize;
let cellSize;
let selectedCell;
let selectedGrid;
let selectedRow;
let selectedCol;
let selectedClue;
let selectedValue;
let noteMode = false;
let gameLevel;
let undoMode = false;

$(window).on("resize", function () {
  windowWidth = $(window).width();
  setBoardSize();
});

const initBoard = () => {
  board.empty();
  cellMap = [];
  gameHistory = [];
  initCells();
  initGrids();
  selectedCell = null;
  noteMode = false;
  checkNoteIndicator();
};

const resetBoard = () => {
  board.empty();
  gameData = originalGameData;
  deselectCell();
  selectedCell = null;
  noteMode = false;
  checkNoteIndicator();
  cellMap = [];
  initCells();
  initGrids();
};

const setBoardSize = () => {
  if (windowWidth <= 768) {
    boardWrapper.css("flex-grow", "1");
  }
  grids = $(".game__board__grid");
  cells = $(".game__board__cell");
  const maxWidth = boardWrapper.width() - gridGap;
  const maxHeight = boardWrapper.height() - gridGap;
  const sqrt = Math.sqrt(9);

  const boxWidth = parseInt(maxWidth / sqrt);
  const boxHeight = parseInt(maxHeight / sqrt);

  if (boxWidth >= boxHeight) {
    gridSize = boxHeight;
    board.width(boardWrapper.height());
    board.height(boardWrapper.height());
  } else {
    gridSize = boxWidth;
    board.width(boardWrapper.width());
    board.height(boardWrapper.width());
  }

  cellSize = Math.floor(gridSize / sqrt) - 2; // 2 is for border
  grids.height(gridSize).width(gridSize);
  cells.height(cellSize).width(cellSize);

  if (windowWidth <= 768) {
    boardWrapper.css("flex-grow", "0");
  }
};

const initGrids = () => {
  gameData.grid.forEach((gridData, i) => {
    let row = Math.floor(i / 3);
    let col = i % 3;
    let grid =
      '<div class="game__board__grid d-flex flex-wrap"' +
      'data-row="' +
      row +
      '"data-col="' +
      col +
      '"></div>';
    board.append(grid);
  });

  cellMap.forEach((cellData, i) => {
    let x = parseInt(cellData.attr("data-gridX"));
    let y = cellData.attr("data-gridY");
    let grid = getGrid(x, y);
    grid.append(cellData);
  });

  setBoardSize();
};

const initCells = () => {
  let data = gameData.grid;
  for (let i = 0; i < data.length; i++) {
    for (let j = 0; j < data[i].length; j++) {
      let CellNote = '<div class="game__board__cell__note fadeIn">';
      for (let index = 1; index <= 9; index++) {
        let noteCell = '<div data-note="' + index + '"></div>';
        CellNote += noteCell;
      }
      CellNote += "</div>";

      let val = data[i][j] > 0 ? data[i][j] : "";
      let dataVal = val == "" ? 0 : val;
      let cls = val == "" ? "clickable" : "disabled";
      let cell =
        '<div class="game__board__cell d-flex justify-content-center align-items-center text-bold ' +
        cls +
        '" data-gridX="' +
        i +
        '" data-gridY="' +
        j +
        '" data-value="' +
        dataVal +
        '" data-noteMode=' +
        false +
        ">" +
        '<span class="fadeIn">' +
        val +
        "</span>" +
        CellNote +
        "</div>";
      cellMap.push($(cell));
    }
  }
};

const cellClick = (cell) => {
  resetSelector();
  let x = cell.attr("data-gridX");
  let y = cell.attr("data-gridY");

  selectedRow = getRow(x);
  selectedCol = getCol(y);
  selectedCell = cell;
  selectedGrid = getGrid(x, y);
  selectedClue = getClue(x, y);
  selectedValue = getValue();

  setSelected();
  setHighlight();
};

const cellInput = (cell, val) => {
  selectedCell = cell;
  if (noteMode) {
    showNote();
    noteInput(val);
  } else {
    hideNote();
    if (!undoMode) {
      addHistory("cell", getValue(), val, selectedCell);
    }
    setSelectedValue(val);
    checkCurrentCell();
    checkBoard();
  }
};

const noteInput = (nVal, oVal = "") => {
  let noteChild;
  let noteCell = selectedCell.find(".game__board__cell__note");
  if (!undoMode) {
    noteChild = noteCell.children()[nVal - 1];
    let oldV;
    let newV;

    if ($(noteChild).html() == "") {
      oldV = "";
      newV = nVal;
      $(noteChild).html(nVal);
    } else {
      oldV = nVal;
      newV = "";
      $(noteChild).html("");
    }

    addHistory("note", oldV, newV, selectedCell);
  } else {
    let val;
    if (oVal != "") {
      val = oVal;
      noteChild = noteCell.children()[oVal - 1];
    } else {
      val = nVal;
      noteChild = noteCell.children()[nVal - 1];
    }

    if ($(noteChild).html() == "") {
      $(noteChild).html(val);
    } else {
      $(noteChild).html("");
    }
  }
};

const getClue = (x, y) => {
  return gameData.clue[x][y];
};

const getGrid = (x, y) => {
  return $(
    `[data-row="${Math.floor(x / 3)}"][data-col="${Math.floor(y / 3)}"] `
  );
};

const getRow = (x) => {
  return cells.filter(`[data-gridX="${x}"]`);
};

const getCol = (y) => {
  return cells.filter(`[data-gridY="${y}"]`);
};

const getValue = () => {
  return $(selectedCell).find("span").html();
};

const setHighlight = () => {
  cells.not(selectedCell).removeClass("selected");
  grids.not(selectedGrid).children().removeClass("highlight");
  selectedGrid.children().addClass("highlight");
  cells.not(selectedCell).removeClass("selected");
  selectedRow.addClass("highlight");
  selectedCol.addClass("highlight");
};

const setSelected = () => {
  cells.not(selectedCell).removeClass("selected");
  selectedCell.addClass("selected");
};

const setSelectedValue = (val) => {
  $(selectedCell).find("span").html(val);
  selectedValue = val;
};

const setValue = (val) => {
  $(selectedCell).find("span").html(val);
  selectedValue = val;
};

const setDisabled = () => {
  selectedCell.addClass("disabled");
};

const setCorrect = () => {
  selectedCell.removeClass("wrong");
  selectedCell.removeClass("highlight");
  selectedCell.addClass("correct");
  selectedCell.addClass("dirty");
};


const setCorrectWithoutColor = () => {
  selectedCell.removeClass("wrong");
  selectedCell.removeClass("correct");
  selectedCell.removeClass("highlight");
  selectedCell.removeClass("dirty");
};

const setWrong = () => {
  selectedCell.addClass("wrong");
  selectedCell.removeClass("highlight");
  selectedCell.addClass("dirty");
};

const resetCell = () => {
  cleanCell();
  setSelected();
  if (!undoMode) {
    addHistory("cell", getValue(), "", selectedCell);
  }
  setValue("");
  checkBoard();
};

const cleanCell = () => {
  selectedCell.removeClass("disabled");
  selectedCell.removeClass("correct");
  selectedCell.removeClass("wrong");
};

const revealCurrentCell = () => {
  hideNote();
  $(selectedCell).find("span").html(selectedClue);
  // setCorrect();
  setCorrectWithoutColor();
  setDisabled();
  removeCorrectHistory();
  // deselectCell();
  checkBoard();
  checkFinish();
  if (!undoMode) {
    playSound("soundCorrect");
  }
};

const checkCurrentCell = () => {
  selectedCell.removeClass("showNote");
  const correctVal = getClue(
    selectedCell.attr("data-gridX"),
    selectedCell.attr("data-gridY")
  );
  let isDirty = selectedCell.hasClass("dirty");
  if (selectedValue == "") {
    resetCell();
  } else {
    let isCorrect = false;
    if (selectedValue == correctVal) {
      isCorrect = true;
      removeCorrectHistory();
      resetNote();
      // setCorrect();
      setCorrectWithoutColor();
      setDisabled();
      if (!undoMode) {
        playSound("soundCorrect");
      }
      checkFinish();
    } else {
      isCorrect = false;
      if (!undoMode) {
        playSound("soundWrong");
      }

      resetNote(true);
      setWrong();
    }

    if (!isDirty) {
      setScore(isCorrect);
    }
  }
};

const checkBoard = () => {
  let clues = cellMap.filter((x) => $(x).attr("data-value") != 0);
  clues.forEach((c) => {
    $(c).removeClass("clue-wrong");
    let cX = $(c).attr("data-gridX");
    let cY = $(c).attr("data-gridY");
    let clue = getClue(cX, cY);
    let cRow = getRow(cX);
    let cCol = getCol(cY);
    let cGrid = getGrid(cX, cY).children();

    cRow.each(function () {
      if (
        $(this).attr("data-value") == 0 &&
        $(this).find("span").html() == clue
      ) {
        $(c).addClass("clue-wrong");
      }
    });

    cCol.each(function () {
      if (
        $(this).attr("data-value") == 0 &&
        $(this).find("span").html() == clue
      ) {
        $(c).addClass("clue-wrong");
      }
    });

    cGrid.each(function () {
      if (
        $(this).attr("data-value") == 0 &&
        $(this).find("span").html() == clue
      ) {
        $(c).addClass("clue-wrong");
      }
    });
  });
};

const deselectCell = () => {
  selectedCell = undefined;
  cells.removeClass("highlight");
};

const resetSelector = () => {
  numberSelector.removeClass("clicked");
};

const checkFinish = () => {
  let count = 0;
  cells.each(function () {
    let x = $(this).attr("data-gridX");
    let y = $(this).attr("data-gridY");
    let val = $(this).find("span").html();

    if (gameData.clue[x][y] == val) {
      count++;
    }
  });

  if (count == 81) {
    checkScoreBonus();
    setTimeout(() => {
      stopGame();
    
    }, 2000);
    // console.log("finish");
  } else {
    selectNextCell();
    // console.log("not finish");
  }
};

const selectNextCell = () => {
  let isGridComplete = true;
  let isRowComplete = true;

  selectedGrid.children().each((i, cell) => {
    let clue = getClue($(cell).attr("data-gridX"), $(cell).attr("data-gridY"));

    if (
      $(cell).attr("data-value") == 0 &&
      $(cell).find("span").html() != clue
    ) {
      cellClick($(cell));
      isGridComplete = false;
      return false;
    }
  });

  if (isGridComplete) {
    selectedRow.each((i, cell) => {
      let clue = getClue(
        $(cell).attr("data-gridX"),
        $(cell).attr("data-gridY")
      );

      if (
        $(cell).attr("data-value") == 0 &&
        $(cell).find("span").html() != clue
      ) {
        cellClick($(cell));
        isRowComplete = false;
        return false;
      }
    });
  }

  if (isRowComplete) {
    selectedCol.each((i, cell) => {
      let clue = getClue(
        $(cell).attr("data-gridX"),
        $(cell).attr("data-gridY")
      );

      if (
        $(cell).attr("data-value") == 0 &&
        $(cell).find("span").html() != clue
      ) {
        cellClick($(cell));
        isColComplete = false;
        return false;
      }
    });
  }
};

const toggleNote = () => {
  noteMode = !noteMode;

  checkNoteIndicator();
};

const checkNoteIndicator = () => {
  if (noteMode) {
    noteIndicator.html("ON");
  } else {
    noteIndicator.html("OFF");
  }
};

const showNote = () => {
  selectedCell.addClass("showNote");
  selectedCell.find("span").hide();
};

const hideNote = () => {
  selectedCell.removeClass("showNote");
  selectedCell.find("span").show();
};

const resetNote = (isAddHistory = false, isAfterWrong = false) => {
  let noteCell = selectedCell.find(".game__board__cell__note");

  if (isAddHistory) {
    let type = "";
    let showNote = [];

    if (isAfterWrong) {
      type = "resetNoteWrong";
    } else {
      type = "resetNote";
    }

    $(noteCell)
      .children()
      .each((i, x) => {
        if ($(x).html() != "") {
          showNote.push($(x).html());
        }
      });
    addHistory(type, showNote, "", selectedCell);
  }

  $(noteCell).children().html("");
};
