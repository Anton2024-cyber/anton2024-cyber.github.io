const dialog = document.getElementById('myDialog')
const dialogOpener = document.querySelector('.openDialogBtn')
const dialogCloser = dialog.querySelector('.closeDialogBtn')

function closeOnBackDropClick({ currentTarget, target }) {
  const dialog = currentTarget
  const isClickedOnBackDrop = target === dialog
  if (isClickedOnBackDrop) {
    close()
  }
}

function openModalAndLockScroll() {
  dialog.showModal()
  document.body.classList.add('scroll-lock')
}

function returnScroll() {
  document.body.classList.remove('scroll-lock')
}

function close() {
  dialog.close()
  returnScroll()
}

dialog.addEventListener('click', closeOnBackDropClick)
dialog.addEventListener('cancel', (event) => {
  returnScroll()
});
dialogOpener.addEventListener('click', openModalAndLockScroll)
dialogCloser.addEventListener('click', (event) => {
  event.stopPropagation()
  close()
})


$(document).ready(function () {
    // manage popup state
    var poped = false;
    $('.popup-link').click(function () {
        // prevent unwanted state changtes
        if(!poped){
            showPopup();
        }
    });

    $('.popup-close').click(function () {
        // prevent unwanted state changtes
        if(poped){
            hidePopup();
        }
    });

    function showPopup() {
        poped = true;
        $('.popup').addClass('active');
        // push a new state. Also note that this does not trigger onpopstate
        window.history.pushState({'poped': poped}, null, '');
    }

    function hidePopup() {
        poped = false;
        // go back to previous state. Also note that this does not trigger onpopstate
        history.back();
        $('.popup').removeClass('active');
    }
});

// triggers when browser history is changed via browser
window.onpopstate = function(event) {
    // show/hide popup based on poped state
    if(event.state && event.state.poped){
        $('.popup').addClass('active');
    } else {
        $('.popup').removeClass('active');
    }
};

function onClick(event) {
  event.preventDefault();
  alert("form send");
}
window.addEventListener('DOMContentLoaded', function (event) {
  console.log("DOM fully loaded and parsed");
  let b = document.getElementById("button1");
  b.addEventListener("click", onClick);
});
