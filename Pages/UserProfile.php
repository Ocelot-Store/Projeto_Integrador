<?php
include('../PHP/Protect.php');
include('../PHP/Connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'];

    if(isset($_FILES['profile_image_file'])) {
        $file = $_FILES['profile_image_file'];

        if($file['error'])
            echo "
            <!-- The Modal -->
            <div id=\"myModal\" class=\"modal\">
            <!-- Modal content -->
            <div class=\"modal-content\">
                <span class=\"close\">&times;</span>
                <div class=\"modal-content-minor\">
                    <img src=\"../Assets/Alert.png\" alt=\"\">
                    <p>Falha ao carregar arquivo!</p>
                </div>
            </div>
            </div>
            <script src=\"../JS/Modal.js\"></script>
            ";

        if($file['size'] > 2097152)
            die("Arquivo muito grande! Max: 2MB");

        $folder = "../Assets/ImageFiles/";
        $fileName = $file['name'];
        $newFileName = uniqid();
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if($extension != "jpg" && $extension != "jpeg" && $extension != "png" )
        echo "
        <!-- The Modal -->
        <div id=\"myModal\" class=\"modal\">
        <!-- Modal content -->
        <div class=\"modal-content\">
            <span class=\"close\">&times;</span>
            <div class=\"modal-content-minor\">
                <img src=\"../Assets/Alert.png\" alt=\"\">
                <p>Tipo de arquivo não aceito (somente jpg ou png)</p>
            </div>
        </div>
        </div>
        <script src=\"../JS/Modal.js\"></script>
        ";

        $path = $folder . $newFileName . "." . $extension;

        $upload_validation = move_uploaded_file($file["tmp_name"], $path);
        if($upload_validation){
            $connection->query("UPDATE user SET file_name='$fileName', path='$path' WHERE id = $userId") or die ($connection->error);
            echo "<script>alert('Arquivo enviado com sucesso!')</script>";

            // Atualize a variável $userRow para exibir a imagem atualizada
            $userRow['path'] = $path;
        } else {
            echo "
            <!-- The Modal -->
            <div id=\"myModal\" class=\"modal\">
            <!-- Modal content -->
            <div class=\"modal-content\">
                <span class=\"close\">&times;</span>
                <div class=\"modal-content-minor\">
                    <img src=\"../Assets/Alert.png\" alt=\"\">
                    <p>Falha ao enviar arquivo</p>
                </div>
            </div>
            </div>
            <script src=\"../JS/Modal.js\"></script>
            ";
        }
    }
}


$userId = $_SESSION['id'];
$userRows = $connection->query("SELECT * FROM user WHERE id = '$userId'") or die($connection->error);
$userRow = $userRows->fetch_assoc();
$shoe_query = $connection->query("SELECT * FROM shoe where user_id = '$userId'") or die($connection->error);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="icon" href="../Assets/Ocelot.ico" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/HorizontalMenuProfile.css">
    <link rel="stylesheet" href="../CSS/UserProfile.css">
    <link rel="stylesheet" href="../CSS/Global.css">

</head>
<body>

<nav class="menu">
    <ul>
        <li><a href="ViewShoes.php"><img src="../Assets/BlackLogo.png" alt="Home"></a></li>
        <li><a href="Favorites.php"><img src="../Assets/Favorites.png" alt="Favorites"></a></li>
        <li><a href="ShoppingCart.php"><img src="../Assets/Cart.png" alt="Shopping Cart"></a></li>
        <li><a href="ViewUsers.php"><img src="../Assets/Users.png" alt="Users"></a></li>
        <li><a href="UserProfile.php"><img src="../Assets/DarkUser.png" alt="User"></a></li>
    </ul>
    <div class="user-buttons">
        <a href="../PHP/Logout.php" class="logout">Logout</a>
        <a href="../PHP/DeleteUser.php" class="delete-user">Delete User </a>
    </div>
</nav>

    <div class="top-half">
        <img src="../Assets/fundo_index.jpg" alt="Imagem Superior" class="top-half-img">
    </div>
            <form method="POST" enctype="multipart/form-data">
                <label for="profile-image">
                    <?php
                        if (!empty($userRow['path'])) {
                            echo "
                            <img src='".$userRow['path']."' alt='Imagem do Perfil' class=\"round-image\">
                            ";
                        } else {
                            echo "
                                <div class=\"empty-image\">
                                    <img src='../Assets/AddImage.png' alt='Adicionar Imagem' class=\"round-image\">
                                </div>
                                ";
                        }
                    ?>
                </label>
                <input type="file" id="profile-image" name="profile_image_file" style="display: none;">
                <input type="submit" value="Salvar Imagem" style="z-index: 1000; background-color:rgb(176, 176, 176);">
            </form>
            
    <div class="bottom-half">
        <div class="image-space"></div>
        <div class="profile-info-text">
            <h1 style="margin:10px"><?php echo $_SESSION['name']; ?> </h1>
        </div>


        <div class="products">
            <h1>Produtos a venda:</h1>
            <a href="AddShoes.php">Adicionar produto +</a>
        </div>

        <div class="shoe-container">
            <?php
            while ($image_file = $shoe_query->fetch_assoc()) {
                $brand_query = $connection->query("SELECT name FROM brand WHERE id = {$image_file['brand_id']}");
                $brand = $brand_query->fetch_assoc();
                ?>
                <div class="shoe-item">
                    <a href="Shoe.php?id=<?php echo $image_file['id']; ?>">
                        <img height="150" src="<?php echo $image_file['path']; ?>" alt="">
                        <div class="shoe-info">
                            <div>
                                <span class="model"><?php echo $image_file['model']; ?></span> <br>
                                <span class="brand"><?php echo $brand['name']; ?></span> <br>
                                <span class="price">R$<?php echo $image_file['price']; ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
        


    
    <script>
        const profileImageInput = document.getElementById('profile-image');

        profileImageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function (event) {
                const imgElement = document.querySelector('.round-image');
                imgElement.src = event.target.result;
            };

            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>
