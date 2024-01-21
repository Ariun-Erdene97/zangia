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
$questiontext = "";
$questionimage = "";
$questionaudio = "";
$questionvideo = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lessonid = $_GET["lessonid"];
    $questiontext = $_POST['questiontext'];
    $questionimage = $_POST["questionimage"];
    $questionaudio = $_POST["questionaudio"];
    $questionvideo = $_POST["questionvideo"];

    do {
        if (empty($questiontext) and empty($questionimage) and empty($questionaudio) and empty($questionvideo)) {
            $errorMessage = "Шалгалтын асуулт оруулаагүй байна.";
            break;
        }
        $sql = "INSERT INTO `question`(`lessonid`,`questiontext`, `questionimage`, `questionaudio`, `questionvideo`) 
        VALUES ('$lessonid','$questiontext','$questionimage','$questionaudio','$questionvideo')";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }
        $questionid = $conn->insert_id;


        foreach ($_POST['input'] as $value) {

                $sql = "INSERT INTO `answer`(`questionid`, `answer`) 
                VALUES ('$questionid','$value')";
                $result = $conn->query($sql);
            
        }

        $questiontext = "";
        $questionimage = "";
        $questionaudio = "";
        $questionvideo = "";

        $successMessage = "Амжилттай хадгаллаа.";

         header("location: /question/questionlist.php?lessonid=$lessonid");
         exit;

    } while (false);
}
?>

<script language="JavaScript" type="text/javascript" src="/js/jquery-1.2.6.min.js"></script>

<script>
    function add() {
        var new_chq_no = parseInt($('#total_chq').val()) + 1;
        var new_input = "<div><input type = 'text' name='input[" + new_chq_no + "]' id='input" + new_chq_no + "' ><button id='btn" + new_chq_no + "' type='button' onclick='remove(" + new_chq_no + ")'>Устгах</button></div> ";

        $('#new_chq').append(new_input);
        $('#total_chq').val(new_chq_no)
    }
    function remove(last_chq_no) {
        if (last_chq_no > 1) {
            $('#input' + last_chq_no + '').remove();
            $('#btn' + last_chq_no + '').remove();
        }
    }
</script>

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
        <h2>Шалгалтын асуулт бүртгэх</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "$errorMessage";
        }
        ?>

        <form method="POST">
            <div>
                <label>Шалгалтын асуулт</label>
                <div>
                    <input type="text" name="questiontext" value="<?php echo $questiontext; ?>">
                </div>
            </div>
            <div>
                <label>Зураг</label>
                <div>
                    <input type="file" name="questionimage" value='<?php echo $questionimage; ?>'>
                </div>
            </div>
            <div>
                <label>Аудио</label>
                <div>
                    <input type="file" name="questionaudio" value='<?php echo $questionaudio; ?>'>
                </div>
            </div>
            <div>
                <label>Видео</label>
                <div>
                    <input type="file" name="questionvideo" value='<?php echo $questionvideo; ?>'>
                </div>
            </div>
            <div>
                <div>
                    <label>Хариулт</label>
                </div>
            </div>
            <div id="new_chq">
                <div>
                    <input type="hidden" value="1" id="total_chq">
                </div>
            </div>
            <div>
                <button type="button" onclick="add()">Хариулт нэмэх</button>
            </div>
            <?php
            if (!empty($successMessage)) {
                echo "$successMessage";
            }
            ?>

            <div>
                <div>
                    <button type="submit">Хадгалах</button>
                </div>
                <div>
                    <a href='/question/questionlist.php?lessonid=$lessonid'>Болих</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>