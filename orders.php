<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <style>
         body {
         background-color: gray;
         background-size: cover; /* Cover the entire viewport */
         background-position: center; /* Center the image */
      }
        table {
            width: 100%;
            border-collapse: collapse;
            height:50px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 40px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .empty {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="placed-orders">

    <h1 class="title">Placed Orders</h1>

    <div class="box-container">
        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $select_orders->execute([$user_id]);

        if ($select_orders->rowCount() > 0) {
            echo '<table>';
            echo '<thead>
                    <tr>
                        <th>Placed On</th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Payment Method</th>
                        <th>Your Orders</th>
                        <th>Total Price</th>
                        <th>Payment Status</th>
                    </tr>
                  </thead>';
            echo '<tbody>';
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>
                        <td>' . $fetch_orders['placed_on'] . '</td>
                        <td>' . $fetch_orders['name'] . '</td>
                        <td>' . $fetch_orders['number'] . '</td>
                        <td>' . $fetch_orders['email'] . '</td>
                        <td>' . $fetch_orders['address'] . '</td>
                        <td>' . $fetch_orders['method'] . '</td>
                        <td>' . $fetch_orders['total_products'] . '</td>
                        <td>₱' . $fetch_orders['total_price'] . '/-</td>
                        <td style="color:' . ($fetch_orders['payment_status'] == 'pending' ? 'red' : 'green') . ';">' . $fetch_orders['payment_status'] . '</td>
                      </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
