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
        a{
            text-decoration: none;
            color: black;
        }
    </style>
<body>
<?php
    $id = $_GET['id'];
    include("connection.php");
    $select_db = @mysql_select_db("accommodation");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
    } else {
        $sql_query = "SELECT * FROM transaction WHERE name = '$id'";
        $result = mysql_query($sql_query);
        if (mysql_num_rows($result) > 0) {
            echo "<h1>查詢訂單</h1>";
            echo "<center><table border=0>";
            echo "<tr><th>訂單編號</th><th>姓名</th><th>電話</th><th>E-mail</th><th>住宿名稱</th><th>房號</th><th>入住時間</th><th>退房時間</th><th>需付金額</th><th>付款狀態</th></tr>";
            while ($row = mysql_fetch_array($result)) {
                // 查詢 accom_data 表，根據 transaction 表中的 accom_id (row[4])
                $accom_id = $row[4]; // 住宿編號
                $sql_qry = "SELECT name FROM accom_data WHERE accom_id = '$accom_id'";
                $result01 = mysql_query($sql_qry);
                $row01 = mysql_fetch_array($result01); // 取得住宿名稱
                $accom_name = $row01 ? $row01['name'] : "未知的住宿名稱";

                echo "<tr>";
                echo "<td align='left'>{$row[0]}</td>";
                echo "<td align='left'>{$row[1]}</td>";
                echo "<td align='left'>{$row[2]}</td>";
                echo "<td align='left'>{$row[3]}</td>";
                echo "<td align='left'>{$accom_name}</td>"; // 住宿名稱
                echo "<td align='left'>{$row[5]}</td>";
                echo "<td align='left'>{$row[6]}</td>";
                echo "<td align='left'>{$row[7]}</td>";
                echo "<td align='left'>{$row[8]}</td>";
                echo "<td align='left'>" . ($row[9] == 1 ? "已付款" : "未付款") . "</td>";
                echo "</tr>";
            }
            echo "</table></center>";
        } else {
            echo "<br>查無資料!";
        }
    }
?>
</body>