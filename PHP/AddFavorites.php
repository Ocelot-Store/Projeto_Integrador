<?php
include('../PHP/Protect.php');
include('../PHP/Connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_button'])) {
    $shoeId = $_POST['shoe_id'];
    $userId = $_SESSION['id'];

    
    $checkFavoriteQuery = $connection->query("SELECT * FROM favorites WHERE user_id = '$userId' AND shoe_id = '$shoeId'");
    if ($checkFavoriteQuery->num_rows > 0) {
        
        $connection->query("DELETE FROM favorites WHERE user_id = '$userId' AND shoe_id = '$shoeId'");
        echo "Removido dos favoritos";
    } else {
        
        $connection->query("INSERT INTO favorites (user_id, shoe_id) VALUES ('$userId', '$shoeId')");
        echo "Adicionado aos favoritos";
    }

    
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>

