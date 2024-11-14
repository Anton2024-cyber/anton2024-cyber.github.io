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



function onClick(event) {
  event.preventDefault();
  alert("form send");
}
window.addEventListener('DOMContentLoaded', function (event) {
  console.log("DOM fully loaded and parsed");
  let b = document.getElementById("button1");
  b.addEventListener("click", onClick);
});

const state = { url: "/", title: "Home", decription: "Home Page" };
history.pushState(state, state.title, state.url);    
console.log(state); 
