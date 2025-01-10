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


<?php
//$filename = $_GET["id"];
include("connection.php");
$select_db = @mysql_select_db( "accommodation");
if (!$select_db) {
    echo '<br>找不到資料庫!<br>';
} else {
    $sql_query = "select * from accom_data ";
    $result = mysql_query($sql_query);
    if (mysql_num_rows($result) > 1) {
        echo "<h1>資訊</h1>";
            echo "<center><table border=0>";
            echo "<tr>
            <th>住宿編號</th>
            <th>住宿類型</th>
            <th>名稱</th>
            <th>地區</th>
            <th>地址</th>
            <th>電話</th>
            <th>信箱</th>
            <th>官網</th>
            <th>房間數量</th>
            <th>配置</th>
            <th>評分</th>
            </tr>";
            $count = 1;
            while ($row = mysql_fetch_array($result)) {
                $id = $row[0];
                echo "<tr onclick=\"window.location='details.php?id=$id'\">";
    ?>
                    <td align="left"><?php echo$row[0] ?></td>
                    <td align="left"><?php echo$row[1] ?></td>
                    <td align="left"><?php echo $row[2]//name ?></td>
                    <td align="left"><?php echo $row[3]//district ?></td>
                    <td align="left"><?php echo$row[4] ?></td>
                    <td align="left"><?php echo$row[5] ?></td>
                    <td align="left"><?php echo$row[6] ?></td>
                    <td align="left"><?php echo$row[7] ?></td>
                    <td align="left"><?php echo$row[8] ?></td>
                    <td align="left"><?php echo$row[9] ?></td>
                    <td align="left"><?php echo $row[10]//review ?></td>
                </tr>
<?php
        }
        echo '</table></center>';
    } else {
        echo '<br>你的賬號不存在';
    }
}
?>

<body>
</body>

</html>