<?php
session_start();

$accom_id = $_POST['accom_id'];
$room_id = $_POST['room_id'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$price = $_POST['price'];
$paid = 0;

if (empty($accom_id) || empty($room_id) || empty($check_in) || empty($check_out) || empty($name) || empty($email) || empty($phone) || empty($price)) {
    die("所有欄位都必須填寫！");
}

include("connection.php");
$select_db = @mysql_select_db("accommodation");

// Start transaction
mysql_query("START TRANSACTION");

try {
    // Fetch the accommodation name based on accom_id
    $sql_accom_name = "SELECT name FROM accom_data WHERE accom_id = '$accom_id'";
    $result_accom_name = mysql_query($sql_accom_name);
    
    if (!$result_accom_name || mysql_num_rows($result_accom_name) == 0) {
        throw new Exception('住宿資料查詢失敗: ' . mysql_error());
    }
    
    $accom_name_row = mysql_fetch_array($result_accom_name);
    $accom_name = $accom_name_row['name'];

    // Check if the transaction table is empty and get the next transaction id
    $sql_check_transaction = "SELECT MAX(transaction_id) AS last_id FROM transaction";
    $result_check_transaction = mysql_query($sql_check_transaction);
    
    if (!$result_check_transaction) {
        throw new Exception('查詢訂單編號失敗: ' . mysql_error());
    }
    
    $last_transaction_row = mysql_fetch_array($result_check_transaction);
    $transaction_id = $last_transaction_row['last_id'] + 1;  // Starting from 1 if the table is empty

    // Insert the transaction data
    $sql_insert_transaction = "
        INSERT INTO transaction (transaction_id, name, phone, email, accom_id, room_id, check_in, check_out, price, paid)
        VALUES ('$transaction_id', '$name', '$phone', '$email', '$accom_id', '$room_id', '$check_in', '$check_out', '$price', '$paid')
    ";
    $result_insert_transaction = mysql_query($sql_insert_transaction);

    if (!$result_insert_transaction) {
        throw new Exception('訂單插入失敗: ' . mysql_error());
    }

    // Update the room reservation status
    $sql_update_room_reserve = "
        UPDATE room
        SET reserve = 1
        WHERE accom_id = '$accom_id' AND room_id = '$room_id'
    ";
    $result_update_room_reserve = mysql_query($sql_update_room_reserve);

    if (!$result_update_room_reserve) {
        throw new Exception('更新房間狀態失敗: ' . mysql_error());
    }

    // Decrease the remain count in room_class
    $sql_decrease_remain = "
        UPDATE room_class
        SET remain = remain - 1
        WHERE accom_id = '$accom_id' AND class_id = (
            SELECT class_id FROM room WHERE room_id = '$room_id' AND accom_id = '$accom_id'
        )
    ";
    $result_decrease_remain = mysql_query($sql_decrease_remain);

    if (!$result_decrease_remain) {
        throw new Exception('更新剩餘房間數量失敗: ' . mysql_error());
    }

    // Commit the transaction
    mysql_query("COMMIT");

    // Output success message
    echo "<h1>預定成功！</h1>";
    echo "訂單編號: $transaction_id<br>";
    echo "住宿名稱: $accom_name<br>";  // Use accommodation name from the query
    echo "房號: $room_id<br>";
    echo "入住日期: $check_in<br>";
    echo "退房日期: $check_out<br>";
    echo "姓名: $name<br>";
    echo "電話: $phone<br>";
    echo "電子郵件: $email<br>";
    echo "總價: $price<br>";

} catch (Exception $e) {
    // Rollback transaction in case of failure
    mysql_query("ROLLBACK");
    echo "訂單處理失敗，請稍後再試。<br>";
    echo "錯誤信息: " . $e->getMessage();
}
?>
