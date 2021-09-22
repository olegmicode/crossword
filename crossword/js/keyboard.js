// keyboard virtual
let Keyboard = window.SimpleKeyboard.default;
// Keyboard.setOptions({
//   layout: {
//   'default': [
//     '` 1 2 3 4 5 6 7 8 9 0 - = {bksp}',
//     '{tab} q w e r t y u i o p [ ] \\',
//     '{lock} a s d f g h j k l ; \' {enter}',
//     '{shift} z x c v b n m , . / {shift}',
//   ],
//   'shift': [
//     '~ ! @ # $ % ^ & * ( ) _ + {bksp}',
//     '{tab} Q W E R T Y U I O P { } |',
//     '{lock} A S D F G H J K L : " {enter}',
//     '{shift} Z X C V B N M < > ? {shift}',
//   ]
// }
// })
let myKeyboard = new Keyboard({
  onChange: input => keyboardOnChange(input),
  onKeyPress: button => keyboardOnPress(button),
  layout: {
  'default': [
    'q w e r t y u i o p',
    'a s d f g h j k l',
    '{close} z x c v b n m',
    ],
  },
  display: {
    '{close}': 'close'
  }
});


// function onChange(input) {
//   key_interceptor.val(input);
//    key_interceptor.select();
  // document.querySelector("#key_interceptor") = input;
  // console.log($("#key_interceptor").value)
  // console.log("Input changed", input);
// }


// function onKeyPress(button) {
//   console.log("Button pressed", button);
// }