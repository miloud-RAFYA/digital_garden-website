<?php
include('config/database.php');
session_start();
$error = null;
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET["username"]) && isset($_GET["username"])) {
        $name =$_GET['username'];
        $pwd =$_GET['password'];
        $stm = $cnx->prepare("SELECT * FROM users WHERE username = ? ");
        $stm->bind_param("s", $name);
        $stm->execute();
        $resultat=$stm->get_result();
        var_dump($resultat) ;
         if($resultat->num_rows > 0) {
        $usres=$resultat->fetch_assoc();
        print_r($usres) ;
        if (password_verify( $pwd,$usres["password"])) {
            $_SESSION['user_id']= $usres['id'];
            $_SESSION['username']=$usres['fName'];
            $_SESSION['date_inscription']=$usres['created_at'];
            $_SESSION['login_time'] = date('H:i:s');
            header("location: dashboard.php");
            exit();
        }else{
            $error = "mot de passe incorecte";
            exit();
        }

        
    }
    $error = "username incorecte";
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Garden</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <div class="container">
        <div class="form-box" id="login-form">
            <form action="login.php" method="GET">
                <h2>login</h2>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="password" required>
                <button type="submit" name="login">Login</button>
                <?php if($error)?><p></p>
                <p>Don't have an account ?<a href="register.php">Register</a></p>
            </form>
        </div>
    </div>

</body>

</html>
