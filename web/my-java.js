function click1() {
    let f1 = document.getElementsByName("num1");
    let f2 = document.getElementsByName("num2");
    let r = document.getElementById("result");
    r.innerHTML = f1[0].value * f2[0].value;
    let s = document.getElementsByName("select1");
    console.log(s[0].value);
    return false;
}
function onClick(event) {
    event.preventDefault();
    alert("click");
  }
  window.addEventListener('DOMContentLoaded', function (event) {
    console.log("DOM fully loaded and parsed");
    let b = document.getElementById("button1");
    b.addEventListener("click", onClick);
  });
