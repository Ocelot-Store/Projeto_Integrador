<?php
    include('../PHP/Protect.php');
    include('../PHP/Connection.php');

    $item_id = $_POST['item_id'];

    $sql = "DELETE FROM cart WHERE id = $item_id;";

    $connection->query($sql);

    header("Location:../Pages/ShoppingCart.php");
    exit(); 
?>