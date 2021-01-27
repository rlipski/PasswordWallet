$(document).ready(function () {
  var modal = document.getElementById("myModal");
  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  $(".btn-decryptPassword").click(function (e) {

    let id = e.target.dataset.passwordId;
    $.ajax({  //create an ajax request to display.php
      type: "GET",
      url: `password/decryptPassword/${id}`,
      success: function (data) {
        modal.style.display = "block";
        $("#decryptedPassword").html(data);
      }
    });
  });

  // When the user clicks on <span> (x), close the modal
  span.onclick = function () {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }


});