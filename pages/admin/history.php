<?php
include '../../functions/checkSession.php';

$uid = (isset($_GET['uid']))? $_GET['uid'] : $_SESSION["uid"];
$infoRef = $database->getReference("Users/" . $uid . "/info");
$userHisRef = $database->getReference('Users/' . $uid . '/history');
$historyRef = $database->getReference('History');

if (!isset($_SESSION['name'])) {
  $_SESSION["name"] = $infoRef->getChild("name")->getValue();
  $_SESSION["branch"] = $infoRef->getChild("branch")->getValue();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
  <link rel="stylesheet" type="text/css" href="../../styles/history.css">
  <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
  <title>History | REaCT</title>
</head>

<body style="background-color: #f3f3f3; background-image: unset;">
    <div class="Content" style="min-height: unset;">
      <div id="data">
        <table>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Name</th>
            <th>Backend Account</th>
          </tr>
          <tr>
              <td colspan="4">
                <h2 style="text-align: center;">Loading Data...</h2>
              </td>
            <tr>
      </div>
    </div>

  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
  <!-- jQuery Modal -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

  <script>
    loadPage(1);

    function loadPage(page) {
      $.ajax({
        url: "../../functions/history-handler.php",
        type: "POST",
        data: {
          "page": page,
          "uid" : '<?php echo $uid; ?>'
        }
      }).done(function(data) {
        console.log(data);
        $("#data").html(data);
      });
    }

    function searchData() {
      var frm = $('#frmSearch');
      frm.validate({
        rules: {
          search: "required",
          sType: "required"
        },
        messages: {
          search: '',
          sType: ''
        },
        errorLabelContainer: '#error',
        showErrors: function(errorMap, errorList) {
          $("#error").html("Please enter required information.");
          this.defaultShowErrors();
        },
        highlight: function(element) {
          $(element).addClass('has-error');
        },
        unhighlight: function(element) {
          $(element).removeClass('has-error');
        },
        submitHandler: function(frm) {
          event.preventDefault();
          $.ajax({
            url: '../../functions/account-handler.php',
            type: 'POST',
            data: $('#frmSearch').serialize()
          }).done(function(data) {
            $("#data").html(data);
          });
        }
      });
    }
  </script>

</body>

</html>