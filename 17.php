<?php
    // 獲取表單資料
    $fileid = $_GET["oid"];
    $filetype = $_GET["type"];
    $filename = $_GET["name"];
    $filedistrict = $_GET["district"];
    $fileaddress = $_GET["address"];
    $filephone = $_GET["phone"];
    $filemail = $_GET["mail"];
    $fileURL = $_GET["URL"];
    $fileroom = $_GET["room_num"];
    $fileequip = $_GET["equip_type"];
    $filereview = $_GET["review"];

    echo "id: " . $fileid;
    echo "<br>type: " . $filetype;
    echo "<br>name: " . $filename;
    echo "<br>district: " . $filedistrict;
    echo "<br>address: " . $fileaddress;
    echo "<br>phone: " . $filephone;
    echo "<br>mail: " . $filemail;
    echo "<br>URL: " . $fileURL;
    echo "<br>room_num: " . $fileroom;
    echo "<br>equip_type: " . $fileequip;
    echo "<br>review: " . $filereview;

    // 連接資料庫
    include("connection.php");
    $select_db = @mysql_select_db("accommodation");
    if (!$select_db) {
        echo '<br>找不到資料庫!<br>';
    } else {
        // 刪除舊的資料
        $delete_query = "DELETE FROM accom_data WHERE accom_id = '$fileid'";
        if (mysql_query($delete_query)) {
            // 插入新資料
            $insert_query = "
                INSERT INTO accom_data (accom_id, type, name, district, address, phone, mail, URL, room_num, equip_type, review) 
                VALUES ('$fileid', '$filetype', '$filename', '$filedistrict', '$fileaddress', '$filephone', '$filemail', 
                        '$fileURL', '$fileroom', '$fileequip', '$filereview')";
            
            if (mysql_query($insert_query)) {
                echo "<br>住宿修改成功。";
            } else {
                echo "<br>新增資料失敗：" . mysql_error();
            }
        } else {
            echo "<br>刪除資料失敗：" . mysql_error();
        }
    }
?>
