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



<script>
    var countDownTimer = new Date().getTime() + 605000;
    // Update the count down every 1 second
    var interval = setInterval(function () {
        var current = new Date().getTime();
        var diff = countDownTimer - current;
        var days = Math.floor(diff / (1000 * 60 * 60 * 24));
        var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('durationmin').value = minutes;
        document.getElementById("counter").innerHTML = minutes + "m " + seconds + "s ";
        // Display Expired, if the count down is over
        if (diff < 0) {
            clearInterval(interval);
            document.getElementById("counter").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>exam</title>
</head>

<body>
    <div>
        <h2>Шалгалт</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "$errorMessage";
        }
        ?>

        <form method="POST" id="examform">

            <input type="hidden" id="durationmin" name="durationmin" value="">

            <?php
            if (isset($_GET["lessonid"])) {
                $lessonid = $_GET["lessonid"];
                $sql = "SELECT * FROM `question` WHERE lessonid=$lessonid ORDER BY RAND() LIMIT 10";
                $result = $conn->query($sql);
                $counter = 1;

                while ($row = $result->fetch_assoc()) {

                    $sql = "SELECT * FROM `answer` WHERE questionid=$row[questionid] ORDER BY RAND()";
                    $answerdata = $conn->query($sql);
                    $answerlist = "";

                    while ($answerrow = $answerdata->fetch_assoc()) {
                        $answerlist = "$answerlist  <input type='radio' id='$answerrow[answerid]' name='input[$row[questionid]]' value='$row[questionid];$answerrow[answerid]'>
                  <label for='$answerrow[answerid]'>$answerrow[answer]</label>";
                    }

                    echo "
            <div>
                <div>
                    <label>$counter.</label>
                    <label>$row[questiontext]</label>
                </div>
                <div>
                    $answerlist
                </div>
            </div>
            <br>
            ";
                    $counter = $counter + 1;
                }
            }
            ?>
            <div>
                <button type="submit">Дуусгах</button>
            </div>
            <div>
                <h4 id='counter'></h4>
            </div>

        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['input'])) {
            $duration = 10 - $_POST['durationmin'];
            $lessonid = $_GET["lessonid"];
            $userid = $_COOKIE['userid'];
            $successcount = 0;
            $wrongcount = 0;
            $totalanswer = 0;
            foreach ($_POST['input'] as $value) {
                $strlist = explode(';', $value);
                if (count($strlist) == 2) {
                    $sql = "SELECT min(answerid) answerid FROM `answer` WHERE questionid = $strlist[0]";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();

                        if ($strlist[1] == $row['answerid'])
                            $successcount = $successcount + 1;
                        else
                            $wrongcount = $wrongcount + 1;
                        $totalanswer = $totalanswer + 1;

                    }
                }
            }

            $sql = "INSERT INTO `exam`(`userid`, `duration`, `successcount`, `wrongcount`, `totalanswer`, `lessonid`) 
              VALUES ('$userid',' $duration','$successcount','$wrongcount','$totalanswer','$lessonid')";
            $result = $conn->query($sql);
echo " <div>
<div>
    <p>Шалгалт өгсөн хугацаа: $duration </p>
    <p>Нийт хариулсан асуулт: $totalanswer </p>
    <p>Зөв хариулсан:  $successcount </p>
    <p>буруу хариулсан: $wrongcount </p>
</div>
<div>
    <a href='examlist.php'>Буцах</a>
</div>
</div>";
        }
        ?>
       
    </div>
</body>

</html>