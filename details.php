<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>

<body>
    <?php
    $id = $_GET['id'] ;
    //echo 'data:'.$filename.'<br>';
    include("connection.php");
    $select_db = @mysql_select_db("accommodation");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
    } else {
        $sql_query = "SELECT * FROM accom_data WHERE accom_id = '$id'";
        $result = mysql_query($sql_query);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)){
                echo "<h1>詳細資料</h1>";
                echo '<td width=20%><center><img src=../test/pic/'.$row[0].'.jpg width=100 hight=100><br>';
                echo "<table border='1' style='width: 80%; margin: 0 auto;'>";
                echo "<tr><th>類型</th><td>" . $row[1] . "</td></tr>";
                echo "<tr><th>名稱</th><td>" . $row[2] . "</td></tr>";
                echo "<tr><th>地區(高雄市)</th><td>" . $row[3] . "</td></tr>";
                echo "<tr><th>地址</th><td>" . $row[4] . "</td></tr>";
                echo "<tr><th>電話</th><td>" . $row[5] . "</td></tr>";
                echo "<tr><th>郵件</th><td>" . $row[6] . "</td></tr>";
                echo "<tr><th>官網</th><td>" . $row[7] . "</td></tr>";

                echo "</table>";
            }
        } else {
            echo "<br>查無資料!";
        }
    }
    ?>



</body>

</html>