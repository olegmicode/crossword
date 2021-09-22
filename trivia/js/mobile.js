////////////////////////////////////////////////////////////
// MOBILE
////////////////////////////////////////////////////////////

/*!
 *
 * START MOBILE CHECK - This is the function that runs for mobile event
 *
 */
function checkMobileEvent() {
  // if ($.browser.mobile || isTablet) {
  //   if (!viewportMode.enable) {
  //     $('#rotateHolder').hide();
  //     return;
  //   }
  //   $(window)
  //     .off('orientationchange')
  //     .on('orientationchange', function (event) {
  //       $('#orientationWarning').hide();
  //       setTimeout(function () {
  //         checkOrientation();
  //       }, 200);
  //     });
  //   checkOrientation();
  // }
}

function checkIframe() {
  iFramed = false;
  if (window.location !== window.parent.location) {
    iFramed = true;
  }

  console.log('iframe mode: ' + iFramed);
}
/*!
 *
 * MOBILE ORIENTATION CHECK - This is the function that runs to check mobile orientation
 *
 */
function checkOrientation() {
  checkIframe();
  console.log('checkOrientation');
  console.log(ua.os.family);
  console.log('window.innerWidth: '+ window.innerWidth);
  console.log('window.innerHeight: '+ window.innerHeight);
  
  if (!iFramed) {
    if (
      ua.os.family == 'iOS' ||
      /^(?!.*chrome).*safari/i.test(navigator.userAgent)
    ) {
      console.log('iOS detected');
      
      if (window.matchMedia("(orientation: landscape)").matches) {
        console.log('landscape');
        $('#orientationWarning').removeClass('d-none');
        $('#orientationWarning').addClass('d-flex');
      } else {
        console.log('not landscape');
        $('#orientationWarning').addClass('d-none');
        $('#orientationWarning').removeClass('d-flex');
      }
    } else if (ua.os.family == 'Android') {
      console.log('Android detected');
      if (screen.orientation.angle == 0) {
        $('#orientationWarning').addClass('d-none');
        $('#orientationWarning').removeClass('d-flex');
      } else {
        $('#orientationWarning').removeClass('d-none');
        $('#orientationWarning').addClass('d-flex');
      }
    } else {
      console.log('else detected');
      $('#orientationWarning').addClass('d-none');
      $('#orientationWarning').removeClass('d-flex');
    }
  }
  else {
    $('#orientationWarning').addClass('d-none');
    $('#orientationWarning').removeClass('d-flex');
  }

}

function checkMobileOrientation() {
  console.log('checkMobileOrientation');
  var o = window.orientation;
  var isLandscape = false;

  if (window.innerWidth > window.innerHeight) {
    isLandscape = true;
    console.log(isLandscape);
  }

  $('#rotateHolder .rotateImg').removeClass('rotatePortrait');
  $('#rotateHolder .rotateImg').removeClass('rotateLandscape');

  var display = false;
  if (!isLandscape) {
    //Portrait
    $('#rotateHolder .rotateImg').addClass('rotateLandscape');
    if (viewportMode.viewport == 'portrait') {
      display = true;
    }
  } else {
    //Landscape
    $('#rotateHolder .rotateImg').addClass('rotatePortrait');
    if (!viewportMode.viewport == 'portrait') {
      display = true;
    }
  }

  if (!display) {
    $('#rotateHolder span').html(viewportMode.text);
    toggleRotate(true);
  } else {
    toggleRotate(false);
  }
}

/*!
 *
 * TOGGLE ROTATE MESSAGE - This is the function that runs to display/hide rotate instruction
 *
 */
function toggleRotate(con) {
  if (con) {
    $('#rotateHolder').fadeIn();
    $('#mainHolder').fadeOut();
  } else {
    $('#rotateHolder').fadeOut();
    $('#mainHolder').fadeIn();
    var height = $('#mainLoader').height();
    var top = $('#mainLoader').css('top');
    $('#logoHolder').height(height);
    $('#logoHolder').css('top', top);
  }
  resizeGameFunc();
}
