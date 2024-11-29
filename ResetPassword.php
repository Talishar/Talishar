<?php
require 'MenuBar.php';
?>

<main>
  <div>
    <section class="signup-form">
      <br>
      <h1>Reset your password.</h1>
      <h3>An e-mail will be send to you with instructions on how to reset your password.</h3>
      <form class="form-resetpwd" action="includes/reset-request.inc.php" method="post">
        <p>
          <label for="email">Enter your email address...
            <input type="text" name="email" placeholder="name@example.com">
          </label>
        </p>
        <button type="submit" name="reset-request-submit">Receive new password by mail</button>
      </form>

      <?php
      if (isset($_GET["reset"])) {
        if ($_GET["reset"] == "success") {
          echo '<p class="signupsuccess">Check your e-mail!</p>';
        }
      }
      ?>
    </section>
  </div>
</main>
