<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
    $filename = $_GET["name"];  // 用戶名
    $pwd = $_GET["pwd"];        // 密碼

    include("connection.php");
    $select_db = @mysql_select_db("accommodation");
    
    if (!$select_db) {
        echo '<br>找不到資料庫!<br>';
    } else {
        // 查詢帳號是否存在
        $sql_query = "SELECT * FROM admin WHERE name = '".$filename."'";
        $result = mysql_query($sql_query);
        
        if (mysql_num_rows($result) == 1) {
            $row = mysql_fetch_array($result);
            // 比對密碼
            if ($pwd == $row['psw']) {
                // 密碼正確
                echo '
                <center>
                    <p><br>
                    <p><br>
                    <font size=6 color="blue">住宿維護系統</font>
                            <li><a href="12.php">查詢住宿<br><p></a></li>
                            <li><a href="13.php">新增住宿<br><p></a></li>
                            <li><a href="14.php">修改住宿</a></li>
                        </tr></center>
                    </table>
                </center>';
            } else {
                // 密碼錯誤
                echo '<br>你的密碼錯誤';
            }
        } else {
            // 帳號不存在
            echo '<br>你的帳號不存在';
        }
    }
?>
<body>
</body>
</html>
