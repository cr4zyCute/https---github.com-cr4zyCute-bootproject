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
<style>
   .header{
   background-color: beige;
   position: sticky;
   top:0; left:0; right:0;
   z-index: 1000;
   box-shadow: var(--box-shadow);
}

.header .flex{
   display: flex;
   align-items: center;
   justify-content: space-between;
   padding:2rem;
   margin: 0 auto;
   max-width: 1200px;
   position: relative;
}

.header .flex .logo{
   font-size: 2.5rem;
   color:var(--black);
}

.header .flex .logo span{
   color:var(--green);
}

.header .flex .navbar a{
   margin:0 1rem;
   font-size: 2rem;
   color:var(--light-color);
}

.header .flex .navbar a:hover{
   text-decoration: underline;
   color:var(--green);
}

.header .flex .icons > *{
   font-size: 2.5rem;
   color:var(--light-color);
   cursor: pointer;
   margin-left: 1.5rem;
}

.header .flex .icons > *:hover{
   color:var(--green);
}

.header .flex .icons a span,
.header .flex .icons a i{
   color:var(--light-color);
}

.header .flex .icons a:hover span,
.header .flex .icons a:hover i{
   color:var(--green);
}

.header .flex .icons a span{
   font-size: 2rem;
}

#menu-btn{
   display: none;
}

.header .flex .profile{
   position: absolute;
   top:120%; right:2rem;
   box-shadow: var(--box-shadow);
   border:var(--border);
   border-radius: .5rem;
   padding:2rem;
   text-align: center;
   background-color: var(--white);
   width: 33rem;
   display: none;
   animation: fadeIn .2s linear;
}

.header .flex .profile.active{
   display: inline-block;
}

.header .flex .profile img{
   height: 15rem;
   width: 15rem;
   margin-bottom: 1rem;
   border-radius: 50%;
   object-fit: cover;
}

.header .flex .profile p{
   padding:.5rem 0;
   font-size: 2rem;
   color:var(--light-color);
}

</style>

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">PlantPedia<span>.</span></a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="shop.php">Shop</a>
         <a href="orders.php">Orders</a>
         <a href="contact.php">Contact</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"style="color: red;"></div>
         <div id="user-btn" class="fas fa-user"style="color: blue;"></div>
         <a href="search_page.php" class="fas fa-search"style="color: black;"></a>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
         ?>
         <a href="wishlist.php"><i class="fas fa-heart"style="color: red;"></i><span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"style="color: orange;"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <a href="user_profile_update.php" class="btn">update profile</a>
         <a href="logout.php" class="delete-btn">logout</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </div>

</header>