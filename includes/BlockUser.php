<?php
$body = json_decode(file_get_contents('php://input'), true);

if (isset($body['block-user-submit'])) {
    session_start();
    if (!isset($_SESSION['userid'])) {
        header('Location: ./MainMenu.php');
        die();
    }

    $userToBlock = $_POST['userToBlock'];
    if (empty($userToBlock) || $userToBlock == "") {
        header("Location: ../ProfilePage.php");
        exit();
    }

    require 'dbh.inc.php';

    $conn = GetDBConnection();
    $sql = "SELECT usersId FROM users WHERE usersUid=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error preparing the blocked user lookup query.";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $userToBlock);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result->fetch_assoc();
        if (!$row) {
            echo "The user you are trying to block could not be found in the database.";
            exit();
        } else {
            $sql = "INSERT INTO blocklist (blockingPlayer, blockedPlayer) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "There was an error preparing the blocklist insert query.";
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $_SESSION['userid'], $row['usersId']);
                mysqli_stmt_execute($stmt);
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo "You have successfully blocked " . $userToBlock . ".";
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                } else {
                    echo "Error inserting data into blocklist table.";
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
