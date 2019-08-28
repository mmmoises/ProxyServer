$(".sidebar-dropdown > a").click(function() {
  $(".sidebar-submenu").slideUp(200);
  if (
    $(this)
      .parent()
      .hasClass("active")
  ) {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .parent()
      .removeClass("active");
  } else {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .next(".sidebar-submenu")
      .slideDown(200);
    $(this)
      .parent()
      .addClass("active");
  }
});
$(".page-wrapper").removeClass("toggled");

$("#close-sidebar").click(function() {
  $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function() {
  $(".page-wrapper").addClass("toggled");
});

var modal = document.getElementById("myModalCsgk");
var btn = document.getElementById("myBtnCgsk");
var span = document.getElementsByClassName("closeCsgk")[0];

btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


var modalSQL = document.getElementById("myModalCsgkSQL");
var btnSQL = document.getElementById("myBtnCgskSQL");
var spanSQL = document.getElementsByClassName("closeCsgkSQL")[0];

btnSQL.onclick = function() {
  modalSQL.style.display = "block";
}
spanSQL.onclick = function() {
  modalSQL.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modalSQL) {
    modalSQL.style.display = "none";
  }
}


var modalCook = document.getElementById("myModalCook");
var btnCook = document.getElementById("myBtnCookies");
var spanCook = document.getElementsByClassName("closeCook")[0];

btnCook.onclick = function() {
  modalCook.style.display = "block";
}
spanCook.onclick = function() {
  modalCook.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modalCook) {
    modalCook.style.display = "none";
  }
}

