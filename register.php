<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "zangiadb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css">
    <style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    form {
        width: 300px;
        /* Adjust the width as needed */
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #4caf50;
        color: white;
        cursor: pointer;
    }

    a {
        text-decoration: none;
        color: #007bff;
    }

    a:hover {
        text-decoration: underline;
    }

    .login-p h2 {
        text-align: center;
    }
    </style>
</head>
<body>
    <div>
        <form method="POST">
            <div>
                <div class="login-p">
                <h2>Нэвтрэх</h2>
                    <input type="text" name="lastname" required id="lastname" placeholder = "Овог" oninvalid="this.setCustomValidity('Овог талбарын утга хоосон байна!')"
        oninput="this.setCustomValidity('')">
                </div>
                <div>
                    <input type="text" name="firstname" required placeholder = "Нэр" oninvalid="this.setCustomValidity('Нэр талбарын утга хоосон байна!')"
        oninput="this.setCustomValidity('')">
                </div>
                <div>
                    <input type="number" name="mobilenumber" required placeholder = "Утасны дугаар" oninvalid="this.setCustomValidity('Утасны дугаар талбарын утга хоосон байна!')"
        oninput="this.setCustomValidity('')" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==8) return false;">
                </div>
                <div>
                    <input type="email" name="email" required placeholder = "И-мэйл" oninvalid="this.setCustomValidity('И-мэйл талбарын утга хоосон байна!')"
        oninput="this.setCustomValidity('')">
                </div>
                <div>
                    <input type="password" name="password" required placeholder = "Нууц үг" oninvalid="this.setCustomValidity('Нууц үг талбарын утга хоосон байна!')"
        oninput="this.setCustomValidity('')">
                </div>
                <div>
                    <input type="submit" name="register" value="Бүртгүүлэх">
                </div>
                <div>
                    <a href="/login/index.php">Буцах</a>
                </div>
            </div>
        </form>
        <?php
        try{
            if(isset($_POST['register'])){
                $sql = "INSERT INTO `user`(`lastname`, `firstname`, `mobilenumber`, `email`, `password`, `roleid`) 
                        VALUES ('".$_POST['lastname']."', '".$_POST['firstname']."', '".$_POST['mobilenumber']."', '".$_POST['email']."', '".$_POST['password']."', 2)";
                if ($conn->query($sql) === TRUE) {
                    echo "Амжилттай бүртгэгдлээ";

                    header("location: /login/index.php");
                    exit;
                  } else {
                    echo  $conn->error;
                  }
            }
        } catch(Exception $e){
            if(str_contains($e->getMessage(),"'mobilenumber'")){
                echo "Бүртгэлтэй утасны дугаар байна.";
            }
            else if(str_contains($e->getMessage(),"'email'")){
                echo "Бүртгэлтэй И-мэйл байна.";
            }
            else{
                echo "error :", $e->getMessage();
            }
        }
        ?>
    </div>
</body>
</html>