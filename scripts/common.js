// Load notification data
const currentDate = new Date();
$.ajax({
    url: "../../functions/notificationHandler.php",
    type: "POST",
    data: {
        "ts": currentDate.getTime() / 1000
    }
}).done(function (data) {
    $(".notification_ul").html(data);
});

// Onclick of Notif bell
$(".notifications .icon_wrap").click(function () {
    $(this).parent().toggleClass("actived");
    $(".notification_dd").toggleClass("show");
});

// Open URL in new window
function openDocument(url) {
    w = 700;
    h = 500;
    LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
    TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
    settings = 'toolbar=no,location=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,';
    settings += 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition;
    window.open(url, 'Supporting Documents', settings);
}

// Change password
function change() {
    var old = $.trim($('#old-pw').val()),
      newP = $.trim($('#new-pw').val()),
      conf = $.trim($('#conf-pw').val());
    if (old != newP) {
      if (newP == conf) {
        var frm = $('#frmPass');

        $.ajax({
          type: "POST",
          url: "../change.php",
          data: frm.serialize(),
          success: function(result){
            // $("#newid").html(result);
            console.log(result);
          }
        });

      } else {
        // Not same
      }
    } else {
      // Can't be same
    }
  }

