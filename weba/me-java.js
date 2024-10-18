function click1() {
    let f1 = document.getElementsByName("num1");
      let f2 = document.getElementsByName("num2");
      let r = document.getElementById("result");
      var sum=0;
      var por=0;
      function do_it()
    { 
      var ch1 = document.getElementById("mycheckbox1");
      var ch2 = document.getElementById("mycheckbox2");
      var ch3 = document.getElementById("mycheckbox3");
      if ( ch1.checked && ch3.checked && ch2.checked)
      sum += 150;
      else if (ch1.checked && ch3.checked)
      sum += 100;
      else if (ch1.checked )
      sum += 40;
      else if (ch2.checked )
      sum += 50;
      else if (ch3.checked )
      sum += 60;
    else if (ch1.checked && ch2.checked)
    sum+=90;
    else if (ch2.checked && ch3.checked)
    sum+=110;
      return sum;
      }
      function do_ti()
    { 
      var ra1 = document.getElementById("myradio1");
      var ra2 = document.getElementById("myradio2");
      var ra3 = document.getElementById("myradio3");
      if ( ra1.checked && ra3.checked && ra2.checked)
      por += 60;
      else if (ra1.checked && ra3.checked)
      por += 40;
      else if (ra1.checked )
      por += 10;
      else if (ra2.checked )
      por += 20;
      else if (ra3.checked )
      por += 30;
    else if (ra1.checked && ra2.checked)
    por+=30;
    else if (ra2.checked && ra3.checked)
    por+=50;
      return por;
      }
      var rer=do_it()+f2[0].value+do_ti();
      r.innerHTML = f1[0].value * rer/10;
      return r;

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
