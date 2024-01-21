<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "zangiadb";

$conn = new mysqli($servername, $username, $password, $dbname);

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
    <title>question</title>
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
    <div>
        <h2>Шалгалтын асуултын жагсаалт</h2>
        <a href="question.php?lessonid=<?php echo $_GET["lessonid"]; ?>" role="button">Шалгалтын асуулт нэмэх</a>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Шалгалтын асуулт</th>
                    <th>Зураг</th>
                    <th>Аудио</th>
                    <th>Видео</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $lessonid = $_GET["lessonid"];
                
                $sql = "SELECT * FROM `question` WHERE lessonid=$lessonid";
                $result = $conn->query($sql);

                if(!$result){
                    die("Invalid query: " . $connection->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[questiontext]</td>
                        <td>$row[questionimage]</td>
                        <td>$row[questionaudio]</td>
                        <td>$row[questionvideo]</td>
                        <td>
                            <a href='/question/questionedit.php?questionid=$row[questionid]&lessonid=$lessonid'>Засах</a>
                            <a href='/question/questiondelete.php?questionid=$row[questionid]'>Устгах</a>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>