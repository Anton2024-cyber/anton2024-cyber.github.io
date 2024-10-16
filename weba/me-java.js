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
window.addEventListener('DOMContentLoaded', function (event) {
    let s = document.getElementsByName("select1");
    s[0].addEventListener("change", function(event) {
      let select = event.target;
      let radios = document.getElementById("myradios");
      let checkboxes = document.getElementById("mycheckboxs");
      console.log(select.value);
      if (select.value == "tovar2") {
        radios.style.display = "none";
      }
      else {
        radios.style.display = "block";
      }
      if (select.value == "tovar3") {
        checkboxes.style.display = "none";
      }
      else {
        checkboxes.style.display = "block";
      }
      if (select.value == "tovar4") {
        checkboxes.style.display = "none";
        radios.style.display = "none";
      }
    });
    
    let r = document.querySelectorAll(".myradios input[type=radio]");
    r.forEach(function(radio) {
      radio.addEventListener("change", function(event) {
        let r = event.target;
        console.log(r.value);
      });    
    });
    
  });
