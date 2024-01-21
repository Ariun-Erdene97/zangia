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
                    <input type="text" name="username" required id="username" placeholder="Утасны дугаар эсвэл и-мэйл"
                        oninvalid="this.setCustomValidity('Утасны дугаар эсвэл и-мэйл-ээ оруулна уу!')"
                        oninput="this.setCustomValidity('')" size="21">
                </div>
                <div>
                    <input type="password" name="password" required placeholder="Нууц үг"
                        oninvalid="this.setCustomValidity('Нууц үг талбарын утга хоосон байна!')"
                        oninput="this.setCustomValidity('')" size="21">
                </div>
                <div>
                    <input type="submit" name="login" value="Нэвтрэх">
                </div>
                <div>
                    <a href="/login/register.php">Бүртгүүлэх</a>
                </div>
            </div>
        </form>
        <?php
        if (isset($_POST['login'])) {
            $sql = "SELECT * FROM `user` WHERE email='" . $_POST['username'] . "'or mobilenumber='" . $_POST['username'] . "'";
            $data = $conn->query($sql);
            if ($data->num_rows > 0) {
                while ($row = $data->fetch_assoc()) {
                    if ($_POST['password'] == $row['password']) {
                        setcookie('userid', $row['userid']);
                        echo "Амжилттай нэвтэрлээ.";
                        if ($row['roleid'] == 1)
                            header("Location: lesson.php");
                        else
                            header("Location: exam/examlist.php");
                    } else
                        echo "Нууц үг буруу байна.";
                }
            } else
                echo "И-мэйл буруу байна.";
        }
        ?>
    </div>
</body>

</html>