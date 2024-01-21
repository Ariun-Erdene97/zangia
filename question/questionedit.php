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

<?php
$image = "";
$lessonname = "";
$description = "";

$errorMessage = "";
$successMessage = "";

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET["lessonid"])) {
        header("location: /lessonlist.php");
        exit;
    }

    $lessonid = $_GET["lessonid"];

    $sql = "SELECT * FROM `lesson` WHERE lessonid=$lessonid";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if(!$row) {
        header("location: /lessonlist.php");
        exit;
    }

    $image = $row["image"];
    $lessonname = $row["lessonname"];
    $description = $row["description"];
} else {
    $lessonid = $_POST["lessonid"];
    $image = $_POST["image"];;
    $lessonname = $_POST["lessonname"];
    $description = $_POST["description"];

    do {
        if (empty($image) || empty($lessonname) || empty($description)) {
            $errorMessage = "Дутуу мэдээлэл оруулсан байна.";
            break;
        }

        $sql = "UPDATE `lesson` SET `lessonname`='$lessonname',`image`='$image',`description`='$description' WHERE `lessonid`='$lessonid'";
        $result = $conn->query($sql);

        if(!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $successMessage = "Амжилттай хадгаллаа.";

        header("location: /lessonlist.php");
        exit;

    } while (false);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lesson</title>
    <style>
        input,
        button,
        table {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        input:focus,
        button:focus,
        table:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Add more specific styles for different input types if needed */
        input[type="checkbox"],
        input[type="radio"] {
            margin: 0;
        }

        /* Customize the appearance of checkboxes and radios */
        input[type="checkbox"],
        input[type="radio"] {
            width: 1rem;
            height: 1rem;
        }

        /* Additional styles for buttons */
        button {
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Additional styles for tables */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
        }

        table,
        th,
        td {
            border: 1px solid #ced4da;
        }

        th,
        td {
            padding: 0.75rem;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .drawer {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .drawer-content {
            background-color: #fff;
            padding: 20px;
            width: 50%;
            margin: 20px auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .close-btn {
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .deleteDraw {
            display: flex;
            width: 206px;
            height: 139px;
            flex-direction: column;
            align-items: stretch;
        }
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2>Хичээл засах</h2>

        <?php
        if(!empty($errorMessage)) {
            echo "$errorMessage";
        }
        ?>

        <form method="POST">
            <input type="hidden" name="lessonid" value="<?php echo $lessonid; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Зураг</label>
                <div class="col-sm-6">
                    <input type="file" name="image" value="<?php echo $image; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Хичээлийн нэр</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="lessonname" value='<?php echo $lessonname; ?>'>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Хичээлийн мэдээлэл</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="description" value='<?php echo $description; ?>'>
                </div>
            </div>

            <?php
            if(!empty($successMessage)) {
                echo "$successMessage";
            }
            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Хадгалах</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="lessonlist.php">Болих</a>
                </div>
            </div>

        </form>
    </div>
</body>
</html>