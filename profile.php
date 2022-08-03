<?php
  require "Header.php";
?>

<?php

if(isset($_POST['update_profile'])){

   $user_id = $_SESSION['userid'];
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

   if($update_name != $_SESSION['useruid'] || $update_email != $_SESSION['useremail']){
     mysqli_query($conn, "UPDATE users SET usersUid = '$update_name', usersEmail = '$update_email' WHERE usersId = '$user_id'") or die('query failed');
     $_SESSION['useruid'] = $update_name;
     $_SESSION['useremail'] = $update_email;
   }

   $old_pass = mysqli_real_escape_string($conn, $_POST['old_pass']);
   $update_pass = mysqli_real_escape_string($conn, $_POST['update_pass']);
   $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
   $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

   if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)){
     // Check if the two new passwords are the same
     if($new_pass != $confirm_pass){
        $message[] = "New password doesn't matched!";
      // Verify that the password is the same as the hashed pwd in the database
      }elseif(password_verify($update_pass, $old_pass)){
        // Hash new password for security
        $confirmed_hashedPwd = password_hash($confirm_pass, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET usersPwd = '$confirmed_hashedPwd' WHERE usersId = '$user_id'") or die('query failed');
        $_SESSION['userspwd'] = $confirmed_hashedPwd;
        $message[] = "Your password was updated!";
      }else{
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
  <h2>Welcome <?php echo $_SESSION['useruid']?>!</h2>

      <?php
        $uidExists = getUInfo($conn, $_SESSION['useruid']);
        $_SESSION["useremail"] = $uidExists["usersEmail"];
        $_SESSION["userspwd"] = $uidExists["usersPwd"];
      ?>

      <div class="wrapper">
          <div class="profile-form-form">
              <form action="Profile.php" method="post">

            <!-- Profile Picture placeholder -->
              <style>
                img {border: 3px solid #1a1a1a; display: block; margin-left: auto; margin-right: auto; width: 230px; height: 200px; border-radius: 50%; }
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
              if(isset($message)){
                foreach($message as $message){
                  echo '<p>'.$message.'</p>';
                }
              }
            ?>

         </div>
      </div>
   </form>
</section>

<?php
  require "Footer.php";
?>
