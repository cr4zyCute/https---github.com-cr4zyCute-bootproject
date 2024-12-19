<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
};

if (isset($_POST['update_profile'])) {

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $admin_id]);

   $image = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;
   $old_image = $_POST['old_image'];

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $admin_id]);
         if ($update_image) {
            move_uploaded_file($image_tmp_name, $image_folder);
            if (file_exists('uploaded_img/' . $old_image)) {
               unlink('uploaded_img/' . $old_image);
            }
            $message[] = 'Image updated successfully!';
         }
      }
   }

   $old_pass = $_POST['old_pass'];
   $update_pass = password_hash($_POST['update_pass'], PASSWORD_DEFAULT);
   $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
   $confirm_pass = password_hash($_POST['confirm_pass'], PASSWORD_DEFAULT);

   if (!empty($_POST['update_pass']) && !empty($_POST['new_pass']) && !empty($_POST['confirm_pass'])) {
      if (!password_verify($_POST['update_pass'], $old_pass)) {
         $message[] = 'old password not matched!';
      } elseif (!password_verify($_POST['new_pass'], $confirm_pass)) {
         $message[] = 'confirm password not matched!';
      } else {
         $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass_query->execute([$confirm_pass, $admin_id]);
         $message[] = 'password updated successfully!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Admin Profile</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/components.css">

   
</head>
<body>
   

<?php include 'headerr.php'; ?>
<section class="update-profile">

   <h1 class="title">Update Profile</h1>

   <?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
   ?>

   <form action="" method="POST" enctype="multipart/form-data">
      <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
      <div class="flex">
         <div class="inputBox">
            <span>Username :</span>
            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="Update username" required class="box">
            <span>Email :</span>
            <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="Update email" required class="box">
            <span>Update pic :</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
            <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
            <span>Old Password :</span>
            <input type="password" name="update_pass" placeholder="Enter previous password" class="box">
            <span>New Password :</span>
            <input type="password" name="new_pass" placeholder="Enter new password" class="box">
            <span>Confirm Password :</span>
            <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
         </div>
      </div>
      <div class="flex-btn">
         <input type="submit" class="btn" value="Update Profile" name="update_profile">
         <a href="admin_page.php" class="option-btn">Go Back</a>
      </div>
   </form>

</section>

<script src="js/script.js"></script>

</body>
</html>
