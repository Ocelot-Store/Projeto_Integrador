<?php
    include('../PHP/Protect.php');
    include('../PHP/Connection.php');

    $item_id = $_POST['item_id'];
    $action = $_POST['action'];

    // Verifica se o ID do item é um número inteiro válido
    if (filter_var($item_id, FILTER_VALIDATE_INT) === false) {
        die("ID de item inválido.");
    }

    // Verifica se a ação é válida (increase ou decrease)
    if ($action !== 'increase' && $action !== 'decrease') {
        die("Ação inválida.");
    }

    // Atualiza a quantidade do item no carrinho
    if ($action === 'increase') {
        // Aumenta a quantidade
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = $item_id";
    } elseif ($action === 'decrease') {
        // Diminui a quantidade, evitando valores negativos
        $sql = "UPDATE cart SET quantity = GREATEST(quantity - 1, 0) WHERE id = $item_id";
    }

    if ($connection->query($sql) === TRUE) {
        // Redireciona de volta para a página do carrinho após atualização
        header("Location:../Pages/ShoppingCart.php");
        exit();
    } else {
        echo "Erro ao atualizar a quantidade: " . $connection->error;
    }

?>
