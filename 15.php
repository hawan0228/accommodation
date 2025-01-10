<?php
    include("connection.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // 取得表單資料
        $type = $_GET["type"];
        $name = $_GET["name"];
        $district = $_GET["district"];
        $address = $_GET["address"];
        $phone = $_GET["phone"];
        $mail = $_GET["mail"];
        $URL = $_GET["URL"];
        $room_num = $_GET["room_num"];
        $equip_type = $_GET["equip_type"];
        $review = $_GET["review"];

        // 查詢資料表中最大的 accom_id
        $sql_max_id = "SELECT MAX(accom_id) AS max_id FROM accom_data";
        $result = mysql_query($sql_max_id);
        if ($result) {
            $row = mysql_fetch_assoc($result);
            $max_id = $row['max_id'];
            // 設定新的 accom_id 為最大值 + 1
            $new_id = $max_id + 1;
        } else {
            // 如果查詢最大ID失敗，可以設置初始值
            $new_id = 1;
        }

        // 插入資料表，手動設置 accom_id
        $sql_query = "INSERT INTO accom_data (accom_id, type, name, district, address, phone, mail, URL, room_num, equip_type, review)
                      VALUES ('$new_id', '$type', '$name', '$district', '$address', '$phone', '$mail', '$URL', '$room_num', '$equip_type', '$review')";
        
        // 執行插入操作
        if (mysql_query($sql_query)) {
            echo "新增住宿資料成功！";
        } else {
            echo "資料新增失敗：" . mysql_error();
        }
    }
?>
