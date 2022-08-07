<?php
require "Header.php";
?>

<?php

if (isset($_POST['update_profile'])) {

  $user_id = $_SESSION['userid'];
  $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
  $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

  if ($update_name != $_SESSION['useruid'] || $update_email != $_SESSION['useremail']) {
    mysqli_query($conn, "UPDATE users SET usersUid = '$update_name', usersEmail = '$update_email' WHERE usersId = '$user_id'") or die('query failed');
    $_SESSION['useruid'] = $update_name;
    $_SESSION['useremail'] = $update_email;
  }

  $old_pass = mysqli_real_escape_string($conn, $_POST['old_pass']);
  $update_pass = mysqli_real_escape_string($conn, $_POST['update_pass']);
  $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
  $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

  if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
    // Check if the two new passwords are the same
    if ($new_pass != $confirm_pass) {
      $message[] = "New password doesn't matched!";
      // Verify that the password is the same as the hashed pwd in the database
    } elseif (password_verify($update_pass, $old_pass)) {
      // Hash new password for security
      $confirmed_hashedPwd = password_hash($confirm_pass, PASSWORD_DEFAULT);
      mysqli_query($conn, "UPDATE users SET usersPwd = '$confirmed_hashedPwd' WHERE usersId = '$user_id'") or die('query failed');
      $_SESSION['userspwd'] = $confirmed_hashedPwd;
      $message[] = "Your password was updated!";
    } else {
      $message[] = "Old password doesn't matched!";
    }
  }
  $message[] = "Profile saved!";

  // $update_image = $_FILES['update_image']['name'];
  // $update_image_size = $_FILES['update_image']['size'];
  // $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
  // $update_image_folder = 'uploaded_img/'.$update_image;
  //
  // if(!empty($update_image)){
  //    if($update_image_size > 2000000){
  //       $message[] = 'image is too large';
  //    }else{
  //       $image_update_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$user_id'") or die('query failed');
  //       if($image_update_query){
  //          move_uploaded_file($update_image_tmp_name, $update_image_folder);
  //       }
  //       $message[] = 'image updated succssfully!';
  //    }
  // }

}
?>

<section class="profile-form">
  <h2>Welcome <?php echo $_SESSION['useruid'] ?>!</h2>

  <?php
  $uidExists = getUInfo($conn, $_SESSION['useruid']);
  $_SESSION["useremail"] = $uidExists["usersEmail"];
  $_SESSION["userspwd"] = $uidExists["usersPwd"];
  ?>

  <div class="wrapper" style='overflow-y:scroll;'>
    <div class="profile-form-form">
      <form action="Profile.php" method="post">

        <!-- Profile Picture placeholder -->
        <style>
          img {
            border: 3px solid #1a1a1a;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 230px;
            height: 200px;
            border-radius: 50%;
          }
        </style>
        <img src="Images/default-avatar.jpg" alt="Avatar">

        <div>Username:</div>
        <input type="text" name="update_name" value="<?php echo $_SESSION['useruid']; ?>">
        <div>Your email:</div>
        <input type="email" name="update_email" value="<?php echo $_SESSION['useremail']; ?>">

        <!-- <div>Update your avatar :</div>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png"> -->

        <input type="hidden" name="old_pass" value="<?php echo $_SESSION['userspwd']; ?>">
        <div>Old password:</div>
        <input type="password" name="update_pass" placeholder="Enter Password">
        <div>New password:</div>
        <input type="password" name="new_pass" placeholder="Enter New Password">
        <div>Confirm password:</div>
        <input type="password" name="confirm_pass" placeholder="Confirm New Password">
        <button type="submit" name="update_profile">Update Profile</button>

        <?php
        if (isset($message)) {
          foreach ($message as $message) {
            echo '<p>' . $message . '</p>';
          }
        }




          // This example shows how to have your users log in via Patreon, and acquire access and refresh tokens after logging in

        //  require_once './Assets/patreon-php-master/vendor/autoload.php';

          //use Patreon\API;
          //use Patreon\OAuth;

          $client_id = 'ZUg4PrZuOwdahOIqG8YP-OrEV3KTxgCWCmFa9eYKv1iKOgOoCIooooUZh9llfEZj';      // Replace with your data
          $client_secret = 'kU1g4JpVzEEK28bgDHLFRAiL0UBRa6-wWzvGV3cjELnG2o0-VfzOwbeiOGArYTpJ';  // Replace with your data

          // Set the redirect url where the user will land after oAuth. That url is where the access code will be sent as a _GET parameter. This may be any url in your app that you can accept and process the access code and login

          // In this case, say, /patreon_login request uri
          $redirect_uri = "https://www.fleshandbloodonline.com/FaBOnline/PatreonLogin.php"; // Replace http://mydomain.com/patreon_login with the url at your site which is going to receive users returning from Patreon confirmation

          // Generate the oAuth url
          $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

          // You can send an array of vars to Patreon and receive them back as they are. Ie, state vars to set the user state, app state or any other info which should be sent back and forth.
          // for example lets set final page which the user needs to land at - this may be a content the user is unlocking via oauth, or a welcome/thank you page
          // Lets make it a thank you page

          $state = array();

          $state['final_page'] = 'http://fleshandbloodonline.com/FaBOnline/MainMenu.php'; // Replace http://mydomain.com/thank_you with the url that has your thank you page

          // Add any number of vars you need to this array by $state['key'] = variable value

          // Prepare state var. It must be json_encoded, base64_encoded and url encoded to be safe in regard to any odd chars
          $state_parameters = '&state=' . urlencode( base64_encode( json_encode( $state ) ) );

          // Append it to the url

          $href .= $state_parameters;

          // Now place the url into a login link. Below is a very simple login link with just text. in assets/images folder, there is a button image made with official Patreon assets (login_with_patreon.php). You can also use this image as the inner html of the <a> tag instead of the text provided here

          // Scopes! You must request the scopes you need to have the access token.
          // In this case, we are requesting the user's identity (basic user info), user's email
          // For example, if you do not request email scope while logging the user in, later you wont be able to get user's email via /identity endpoint when fetching the user details
          // You can only have access to data identified with the scopes you asked. Read more at https://docs.patreon.com/#scopes

          // Lets request identity of the user, and email.

          $scope_parameters = '&scope=identity%20identity'.urlencode('[email]');

          $href .= $scope_parameters;

          // Simply echoing it here. You can present the login link/button in any other way.

          echo '<a href="'.$href.'">Click here to login via Patreon</a>';





        ?>

    </div>
  </div>
  </form>
</section>

<?php
require "Footer.php";
?>
