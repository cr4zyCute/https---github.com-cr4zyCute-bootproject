<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $rowCount = $stmt->rowCount();  

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($rowCount > 0){

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      }else{
         $message[] = 'no user found!';
      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

   <style>
      body {
         margin: 0;
         padding: 0;
         height: 100vh;
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .home-bg {
         position: relative;
         width: 100%;
         height: 100vh; 
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .home-bg img {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         object-fit: cover;
         z-index: -1; /* Ensure image stays behind the form */
      }


      .form-container input {
         width: 100%;
         padding: 10px;
         margin: 10px 0;
         border: 1px solid #ccc;
         border-radius: 4px;
      }

      .form-container .btn {
         background-color: #4CAF50;
         color: white;
         border: none;
         cursor: pointer;
      }

      .form-container .btn:hover {
         background-color: #ccc;
      }

      .message {
         background-color: #f44336;
         color: white;
         padding: 15px;
         margin: 10px 0;
         border-radius: 5px;
         display: flex;
         justify-content: space-between;
         align-items: center;
      }

      .message i {
         cursor: pointer;
      }
   </style>

</head>
<body>

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

<div class="home-bg">
   <!-- Image background inside the body -->
   <img src="images/nn.png" alt="Background Image">

   <div class="form-container">
      <form action="" method="POST">
         <h3>Login Now</h3>
         <input type="email" name="email" class="box" placeholder="Enter your email" required>
         <input type="password" name="pass" class="box" placeholder="Enter your password" required>
         <input type="submit" value="Login Now" class="btn" name="submit">
         <p>Don't have an account? <a href="register.php">Register Now</a></p>
      </form>
   </div>
</div>

</body>
</html>