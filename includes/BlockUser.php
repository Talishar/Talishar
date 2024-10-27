<?php

if (isset($_POST['block-user-submit'])) {
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
        if (!$row = mysqli_fetch_assoc($result)) {
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
                echo "You have successfully blocked " . $userToBlock . ".";
                exit();
            }
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Block User</title>
</head>
<body>
    <form className="form-resetpwd" id="block-user-form">
        <input type="text" name="userToBlock" placeholder="User to block" />
        <button type="submit" name="block-user-submit">Block</button>
        <p id="block-user-message"></p>
    </form>

    <script>
        const form = document.getElementById('block-user-form');
        const messageElement = document.getElementById('block-user-message');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const userInput = document.querySelector('input[name="userToBlock"]').value;
            const formData = new FormData();
            formData.append('userToBlock', userInput);

            fetch('includes/BlockUser.php', {
                method: 'POST',
                body: formData,
            })
            .then((response) => response.text())
            .then((message) => {
                messageElement.innerText = message;
            })
            .catch((error) => {
                console.error(error);
            });
        });
    </script>
</body>
</html>