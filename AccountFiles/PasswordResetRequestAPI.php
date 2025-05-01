<?php
include '../includes/functions.inc.php';
include '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';

SetHeaders();

$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);
$userEmail = isset($_POST["email"]) ? $_POST["email"] : null;
if (empty($userEmail)) {
    $response->error = "Email is required.";
    echo(json_encode($response));
    exit;
}

$selector = bin2hex(random_bytes(8));
$token = random_bytes(32);

$url = "https://talishar.net/user/login/reset-password?selector=" . $selector . "&validator=" . bin2hex($token);

$expires = date("U") + 1800;


// Delete any existing entries.
$conn = GetDBConnection();
$sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  $response->message = "There was an error deleting the old password reset requests.";
  echo(json_encode($response));
  exit;
} else {
  mysqli_stmt_bind_param($stmt, "s", $userEmail);
  mysqli_stmt_execute($stmt);
}

//Insert token into the database
$sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  $response->message = "There was an error creating the password reset token.";
  echo(json_encode($response));
  exit;
} else {
  // Here we also hash the token to make it unreadable, in case a hacker accessess our database.
  $hashedToken = password_hash($token, PASSWORD_DEFAULT);
  mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
  mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

SendEmailAPI($userEmail, $url);

$response->message = "Password reset email sent.";
echo(json_encode($response));

exit;

?>
