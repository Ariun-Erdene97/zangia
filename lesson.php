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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lessonid = $_POST["lessonid"];
    $lessonname = $_POST["lessonname"];
    $description = $_POST["description"];

    if ($_FILES != null && $_FILES["image"]["error"] == 0) {
        $targetDirectory = "/images/";
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }
      
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            if ($lessonid > 0) {

                $sql = "UPDATE `lesson` SET `lessonname`='$lessonname', `image`='$targetFile', `description`='$description' WHERE `lessonid`='$lessonid'";
                $result = $conn->query($sql);
            } else {
                $sql = "INSERT INTO `lesson`(`lessonname`, `image`, `description`) 
                VALUES ('$lessonname','$targetFile','$description')";
                $result = $conn->query($sql);

                $lessonid = $conn->insert_id;
                $_POST["lessonid"] = $lessonid;
            }
            if ($result) {
                $successMessage = "Амжилттай хадгаллаа.";
                header("Content-type: image/jpeg");
                exit;
            } else {
                $errorMessage = "Invalid query: " . $conn->error;
            }
        } else {
            $errorMessage = "Failed to move uploaded file.";
        }
    } else {
        $errorMessage = "Дутуу мэдээлэл оруулсан байна.";
    }
}

if (isset($_POST['lessonidToDelete'])) {

    $lessonidToDelete = $_POST['lessonidToDelete'];

    // Perform the delete query
    $deleteQuery = "DELETE FROM `lesson` WHERE `lessonid`='$lessonidToDelete'";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
        echo "Lesson with ID $lessonidToDelete has been deleted.";
    } else {
        echo "Error deleting lesson: " . $conn->error;
    }
    exit;
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <title>lesson</title>
</head>

<body>
    <div class="drawer" id="drawer">
        <div class="drawer-content">
            <span class="close-btn" onclick="closeDrawer()">X</span>
            <div class="container my-5">
                <h2>Хичээл засах</h2>

                <?php
                if (!empty($errorMessage)) {
                    echo "$errorMessage";
                }
                ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="lessonid" name="lessonid" value="">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Зураг</label>
                        <div class="col-sm-6">
                            <input type="file" id="image" name="image">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Хичээлийн нэр</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="lessonname" name="lessonname"'>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Хичээлийн мэдээлэл</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                    </div>

                    <?php
                    if (!empty($successMessage)) {
                        echo "$successMessage";
                    }
                    ?>

                    <div class="row mb-3">
                        <div class="offset-sm-3 col-sm-3 d-grid">
                            <button type=' button' class="btn btn-primary" onclick=' lessonSave()''>Хадгалах</button>
                        </div>
                        <div class="col-sm-3 d-grid">
                            <a class="btn btn-outline-primary" href="lesson.php">Болих</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="drawer" id="drawerdelete">
        <div class="deleteDraw drawer-content">
            <p>Утгахдаа итгэлтэй байна уу?</p>
            <div>
                <button onclick="deleteLesson()" id="delete_btn">Тийм</button>
            </div>
            <div>
                <button onclick=' closeDrawer()'>Үгүй</button>
                        </div>
                    </div>
            </div>
            <div class="container my-5">
                <h2>Хичээлийн жагсаалт</h2>
                <div style='width: 200px'>
                    <button class="btn btn-primary" onclick="openDrawerNew()" role="button">Хичээл нэмэх</button>
                </div>


                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Зураг</th>
                            <th>Хичээлийн нэр</th>
                            <th>Хичээлийн мэдээлэл</th>
                            <th>Үйлдэл</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `lesson`";
                        $result = $conn->query($sql);

                        if (!$result) {
                            die("Invalid query: " . $connection->error);
                        }

                        while ($row = $result->fetch_assoc()) {
                            echo "
                    <tr>
                        <td>
                            <a href='/question/questionlist.php?lessonid=$row[lessonid]'><img src='$row[image]'/></a>
                        </td>
                        <td>$row[lessonname]</td>
                        <td>$row[description]</td>
                        <td>
                            <a href='#' onclick='openDrawer(\"$row[lessonid]\", \"$row[image]\", \"$row[lessonname]\", \"$row[description]\"); return false;'>Засах</a>
                            <a href='#' onclick='deleteDrawer(\"$row[lessonid]\"); return false;'>Устгах</a>
                        </td>
                    </tr>
                    ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <script>
                // Function to open the drawer with lesson details
                function openDrawer(lessonid, image, lessonname, description) {

                    document.getElementById('drawer').style.display = 'block';
                    // Set values for the form fields in the drawer
                    document.getElementById('lessonid').value = lessonid
                 


                    let file = new File([image], image, { type: "image/jpeg", lastModified: new Date().getTime() }, 'utf-8');

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);//your file(s) reference(s)

                    document.getElementById('image').files = dataTransfer.files;
                    document.getElementById('lessonname').value = lessonname;
                    document.getElementById('description').value = description;
                }

                function openDrawerNew() {
                    openDrawer(-1, "", "", "");
                }
                function deleteDrawer(lessonid) {
                    document.getElementById('drawerdelete').style.display = 'block';

                    document.getElementById('lessonid').value = lessonid;
                }

                // Function to close the drawer
                function closeDrawer() {
                    document.getElementById('drawer').style.display = 'none';
                    document.getElementById('drawerdelete').style.display = 'none';
                }

                function lessonSave() {
                    var formData = new FormData(document.forms[0]);

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                // Handle the response if needed
                                console.log(xhr.responseText);
                                closeDrawer();
                                location.reload(); // or update the page content as needed
                            } else {
                                // Handle error
                                console.error(xhr.statusText);
                            }
                        }
                    };

                    xhr.open("POST", "lesson.php", true);
                    xhr.send(formData);
                }


                function deleteLesson() {
                    var lessonidToDelete = document.getElementById('lessonid').value;

                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                // Handle the response if needed
                                console.log(xhr.responseText);
                                closeDrawer();
                                location.reload(); // or update the page content as needed
                            } else {
                                // Handle error
                                console.error(xhr.statusText);
                            }
                        }
                    };

                    xhr.open("POST", "lesson.php", true);
                    // Pass the lessonid as a parameter for the delete action
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send("lessonidToDelete=" + lessonidToDelete);
                }




            </script>
</body>

</html>