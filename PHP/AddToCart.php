<?php
include('../PHP/Protect.php');
include('../PHP/Connection.php');

// Verifique se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $sql_query = $connection->query("SELECT * FROM shoe WHERE id = {$_GET['id']}") or die($connection->error);
    $row = $sql_query->fetch_assoc();

    
    $shoeId = $_GET['id'];
    $userId = $_SESSION['id'];
    $quantity = 1; // Pode adicionar um campo de quantidade no formulário se necessário

    $checkCartQuery = $connection->query("SELECT * FROM cart WHERE user_id = '$userId' AND shoe_id = '$shoeId'");

    if ($checkCartQuery->num_rows > 0) {
        $sql_query2 = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$userId' AND shoe_id = '$shoeId'";
    }
    else{
        $sql_query2 = "INSERT INTO cart (shoe_id, user_id, quantity) VALUES ($shoeId, $userId, $quantity)";
    }

    // Insira os dados na tabela de carrinho (substitua 'cart' pelo nome da tabela correta)
    
    
    if ($connection->query($sql_query2) === TRUE) {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();

    } else {
        echo "Erro ao adicionar o tênis ao carrinho: " . $connection->error;
    }
} else {
    echo "Método de requisição inválido.";
    echo $_SERVER["REQUEST_METHOD"];
}

// Feche a conexão com o banco de dados
$connection->close();
?>
