// TODO: if answer wrong, then reset note, if undo, get back the resetted note

let gameHistory;

$(() => {
  gameHistory = [];
  $(undoButton).addClass("disabled");
});

const checkHistory = () => {
  if (gameHistory.length > 0) {
    $(undoButton).removeClass("disabled");
  } else {
    setTimeout(() => {
      $(undoButton).addClass("disabled");
    }, 1000);
  }
  checkNoteIndicator();
};

const addHistory = (type, oldVal, newVal, curCell) => {
  let action = {};
  action.type = type;
  action.cell = curCell;
  action.oldValue = oldVal;
  action.newValue = newVal;
  action.time = new Date();

  if (
    !(action.type == "resetNote" &&
    action.oldValue.length == 0 &&
    action.newValue == "")
  ) {
    gameHistory.push(action);
  } 

  checkHistory();
};

const undo = () => {
  let lastAction = gameHistory[gameHistory.length - 1];
  let prevAction = gameHistory[gameHistory.length - 2];
  cellClick(lastAction.cell);

  switch (lastAction.type) {
    case "cell":
      noteMode = false;
      hideNote();
      resetCell();
      cellInput(lastAction.cell, lastAction.oldValue);
      break;

    case "note":
      noteMode = true;
      showNote();
      noteInput(lastAction.newValue, lastAction.oldValue);
      break;

    case "resetNote":
      noteMode = true;
      showNote();
      lastAction.oldValue.forEach((val) => {
        noteInput(val);
      });
      break;

    case "resetNoteWrong":
      noteMode = true;
      showNote();
      lastAction.oldValue.forEach((val) => {
        noteInput(val);
      });
      break;

    default:
      break;
  }

  gameHistory.pop();

  if (gameHistory.length > 0 && prevAction.type != lastAction.type) {
    console.log(prevAction.type, lastAction.type);
    if (lastAction.type == "note" && prevAction.type == "cell") {
      hideNote();
      noteMode = false;
    }

    if (lastAction.type == "resetNoteWrong" && prevAction.type == "cell") {
      showNote();
      noteMode = true;
    }
  }
  checkHistory();
};

const removeCorrectHistory = () => {
  gameHistory = gameHistory.filter((x) => x.cell != selectedCell);
};
