console.log("Файл прочтён");

window.addEventListener('DOMContentLoaded', function (event) {
    console.log("DOM fully loaded and parsed");
  });

  let k1=0, k2=0;
  var num3=1;
  let flag;
let s = document.getElementsByName("goods");
s[0].addEventListener("change", function(event) {
    let select = event.target;
    let service1 = document.getElementById("not_at_all");
    let service2 = document.getElementById("not_all");
    let service3 = document.getElementById("all");
    let check1 = document.getElementById("check1");
    let check2 = document.getElementById("check2");
    if (select.value == "Mobile") 
    {
        service1.style.display = "none"; 
        service2.style.display = "none";
        service3.style.display = "none";
        check1.style.display = "none";
        check2.style.display = "none";
        check1.checked=false;
        check2.checked=false;
        document.getElementById("not_at_all").checked = false;
        document.getElementById("not_all").checked = false;
        document.getElementById("all").checked = false;
        k1 = 0; k2 = 0, flag="Mobile";
    }
    if (select.value=="Headsound") 
    {
        service1.style.display = "none";
        service2.style.display = "none";
        service3.style.display = "none";
        check1.style.display = "inline";
        check2.style.display = "inline";
        check1.checked=false;
        check2.checked=false;
        document.getElementById("not_at_all").checked = false;
        document.getElementById("not_all").checked = false;
        document.getElementById("all").checked = false;
        k1 = 0; k2 = 0, flag="Headsound";
    }
    if (select.value=="Tablet")
    {
        service1.style.display = "inline";
        service2.style.display = "inline";
        service3.style.display = "inline";
        check1.style.display = "none";
        check2.style.display = "none";
        check1.checked=false;
        check2.checked=false;
        document.getElementById("not_at_all").checked = false;
        document.getElementById("not_all").checked = false;
        document.getElementById("all").checked = false;
        k1 = 0; k2 = 0, flag="Tablet";
    }
    });
    checks.onclick = function()
  {
      if (check1.checked)
      {
          k1 = 300;
      }
      if (check2.checked)
      {
          k2 = 2000;
      }
      if (!check1.checked)
      {
          k1 = 0;
      }
      if (!check2.checked)
      {
          k2 = 0;
      }
  }

let g, h;
let r = document.querySelectorAll(".services input[type=radio]");
  r.forEach(function(radio) {
    radio.addEventListener("change", function(event1) {
      let r = event1.target;
      g = r.value;
    });    
  });

function count()
{
    var num1 = Number(document.getElementById("num1").value);
    if (isNaN(num1)==true)
    {
        alert("NO!");
        return 0;
    }
    console.log(num1);
    var num11=num1-num1%1;
    if (num11!=num1)
        {
            num11++;
            alert(" " + num11);
            num1=num11;
        }
        if (num1<=0)
            {
                alert("NO!");
        return 0;
    }
    var good = (document.getElementById("good").value);
    var num2, result;
    switch(good)
    {
        case "Mobile": num2 = 5000; break;
        case "Headsound": num2 = 15000; break;
        case "Tablet": num2 = 50000; break;
    }
    switch(g)
    {
        case "self": num3 = 1; break;
        case "delivery": num3 = 2; break;
        case "all_included": num3 = 2.5; break;
        default: num3=1;
    }
    console.log(typeof num1);
    console.log(" r = " + r);
    switch(flag)
    {
        case "Mobile": num3 = 1; break;
        case "Headsound": num3 = 1; break;
        case "Tablet": break;
    }
    result = num1*num2*num3+k1+k2;
    console.log("Все переменные: " + num1 + " " + num2 + " " + num3 + " " + k1 + " " + k2);
    document.getElementById("result").innerHTML = result;
}