<?php
include('../PHP/Protect.php');
include('../PHP/Connection.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping Cart</title>
    <link rel="icon" href="../Assets/Ocelot.ico" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/ShoppingCart.css">
    <link rel="stylesheet" href="../CSS/Global.css">
</head>
<body>
    <?php
        include('../PHP/HorizontalMenu.php');

        $sql_query = $connection->query("SELECT * FROM cart WHERE user_id = {$_SESSION['id']}") or die($connection->error);
        $row = $sql_query->fetch_assoc();

        if($sql_query->num_rows > 0){
            $id = $_SESSION['id'];
            $sql = "SELECT c.id, s.model AS shoe_name, s.price, c.quantity
            FROM cart c
            INNER JOIN shoe s ON c.shoe_id = s.id
            WHERE c.user_id = $id";
        
        
            $result = $connection->query($sql);
            ?>
            <h2>Meu Carrinho</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <?php $quantity = $row['quantity']; ?>
                            <td><?php echo $row['shoe_name']; ?></td>
                            <td>R$ <?php echo number_format($row['price'] * $quantity, 2, ',', '.'); ?></td>
                            <td>
                                <form action="../PHP/UpdateQuantity.php" method="post">
                                    <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="decrease">-</button>
                                    <?php echo $row['quantity']; ?>
                                    <button type="submit" name="action" value="increase">+</button>
                                </form>
                            </td>
                            <td>
                                <form action="../PHP/RemoveItem.php" method="post">
                                    <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php
        }
        else{
            echo"
            
            <div class=\"heading\">
                Você não possui nada em seu carrinho!
            </div>
            <div class=\"text-container\">
                <div class=\"text\">
                    Continue navegando até encontrar algo que você gosta e adicione ao carrinho para encontra-lo aqui
                </div>
            </div>
            <div class=\"img\">
                <img src=\"../Assets/Cart.png\" alt=\"\">
            </div>
            <div class=\"turnback\">
                <a href=\"ViewShoes.php\"><h1>Voltar</h1><p>_________</p></a>
                
            </div>
            
            ";
        }
        
    ?>
    


<?php


?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <body>
        
    </body>
    </html>

