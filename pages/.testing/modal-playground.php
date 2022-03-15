<?php
include '../../includes/dbconfig.php';
session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a2501cd80b.js" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    <link rel="stylesheet" type="text/css" href="../../styles/private-common.css">
    <link rel="stylesheet" type="text/css" href="../../styles/history.css">
    <title>Modal Playground</title>
</head>

<body>
    <button type="button" id="modal-open">Open Modal</button>
    <script>
        $('#modal-open').click(function() {
            $('#userInfo').modal('show');
        });
    </script>

    <div id="userInfo" class="modal" style="max-width: 70vw;">
        <div class="modal-title"><h3>User Information</h3></div>
        <div class="modal-body">
            <div class="photoName">
                <div class="photo" style="width: 20%">
                    <img src="" alt="">
                </div>
                <div class="info" style="width: 80%">
                    <p>Name: </p>
                    <p>UID: </p>
                    <span>Birthday: </span>
                    <span>Contact Number: </span>
                    <p>Email: </p>
                </div>
            </div>
            <table>
                <tr>
                    <td><h4>Address</h4></td>
                </tr>
                <tr>
                    <td>House Number/Street Name</td>
                    <td>Barangay</td>
                    <td>City</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Province</td>
                    <td>Country</td>
                    <td>Zip code</td>
                </tr>
            </table>
            <span>Health Status: </span>
            <button id="healthToggle" class="btn-primary">Toggle</button> <br>
            <span>Vaccination Status: </span>
            <button id="vaccToggle" class="btn-primary">View Application</button> <br>
            <h3>User files</h3>
            <table>
                <tr>
                    <th>File Name</th>
                    <th>Category</th>
                    <th>View</th>
                </tr>
                <tr>
                    <td>.</td>
                    <td>.</td>
                    <td>.</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-primary">View History</button>
            <button type="button" class="btn-error">Disable</button>
        </div>
    </div>
</body>
</html>