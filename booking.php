<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預訂房間</title>
    <style>
        .hidden { display: none; }
    </style>
</head>

<body>
    <h1>預訂房間</h1>
    <?php
    include("connection.php");
    $select_db = @mysql_select_db("accommodation");

    $accom_id = $_GET['accom_id'];

    // 查詢住宿名稱
    $sql_query = "SELECT name FROM accom_data WHERE accom_id = '$accom_id'";
    $result = mysql_query($sql_query);

    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $accom_name = $row['name'];
        echo "<p>住宿: $accom_name</p>";
    } else {
        echo "<p>無此住宿資料。</p>";
        exit;
    }

    // 查詢房間資料，過濾 reserve = 0 的房間
    $sql_query = "
        SELECT r.room_id, c.type, c.bed, c.style, rc.remain, rc.price, r.reserve
        FROM room r
        JOIN room_class rc ON r.accom_id = rc.accom_id AND r.class_id = rc.class_id
        JOIN class c ON r.class_id = c.class_id
        WHERE r.accom_id = '$accom_id' AND r.reserve = 0
    ";
    $result = mysql_query($sql_query);

    if (mysql_num_rows($result) > 0) {
        $room_data = [];
        while ($row = mysql_fetch_array($result)) {
            $room_data[$row['style']][] = [
                'room_id' => $row['room_id'],
                'type' => $row['type'],
                'bed' => $row['bed'],
                'style' => $row['style'],
                'price' => $row['price']
            ];
        }

        echo "<form action='4.php' method='post' id='reservationForm'>";
        echo "<input type='hidden' name='accom_id' value='$accom_id'>";

        echo "<label for='style'>選擇房間風格：</label>";
        echo "<select name='style' id='style' onchange='updateRoomOptions()' required>";
        echo "<option value=''>--請選擇風格--</option>";
        foreach ($room_data as $style => $rooms) {
            echo "<option value='$style'>$style</option>";
        }
        echo "</select><br><br>";

        echo "<label for='room'>選擇房號：</label>";
        echo "<select name='room_id' id='room' onchange='updatePriceInfo()' required>";
        echo "<option value=''>--請選擇房號--</option>";
        echo "</select>";
        echo "<div id='price-info'></div><br>";

        echo "<label for='check_in'>入住日期：</label>";
        echo "<input type='date' id='check_in' name='check_in' onchange='updatePriceInfo()' required><br><br>";

        echo "<label for='check_out'>退房日期：</label>";
        echo "<input type='date' id='check_out' name='check_out' onchange='updatePriceInfo()' required><br><br>";

        echo "<div id='total-price'></div>";

        echo "<label for='name'>姓名：</label>";
        echo "<input type='text' id='name' name='name' required><br><br>";

        echo "<label for='email'>電子郵件：</label>";
        echo "<input type='email' id='email' name='email' required><br><br>";

        echo "<label for='phone'>電話：</label>";
        echo "<input type='text' id='phone' name='phone' required><br><br>";

        echo "<input type='hidden' id='price' name='price'>";
        echo "<button type='reset'>取消</button> <button type='submit'>送出</button>";
        echo "</form>";
    } else {
        echo "<p>查無可用房間。</p>";
    }
    ?>

    <script>
        const roomData = <?php echo json_encode($room_data); ?>;

        function calculateStayDays() {
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            if (checkIn && checkOut) {
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                const timeDiff = checkOutDate - checkInDate;
                return timeDiff > 0 ? timeDiff / (1000 * 3600 * 24) : 0;
            }
            return 0;
        }

        function updateRoomOptions() {
            const style = document.getElementById('style').value;
            const roomSelect = document.getElementById('room');
            roomSelect.innerHTML = "<option value=''>--請選擇房間--</option>";

            if (style && roomData[style]) {
                roomData[style].forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.room_id;
                    option.textContent = `房號: ${room.room_id}, 房型: ${room.type}, 床型: ${room.bed}`;
                    roomSelect.appendChild(option);
                });
            }
        }

        function updatePriceInfo() {
            const roomSelect = document.getElementById('room');
            const priceInfo = document.getElementById('price-info');
            const totalPriceElement = document.getElementById('total-price');
            const selectedRoomId = roomSelect.value;
            const days = calculateStayDays();
            priceInfo.innerHTML = "";
            totalPriceElement.innerHTML = "";

            if (selectedRoomId) {
                const style = document.getElementById('style').value;
                const selectedRoom = roomData[style].find(room => room.room_id === selectedRoomId);
                if (selectedRoom) {
                    priceInfo.innerHTML = `<br>價格/天: ${selectedRoom.price}元`;
                    if (days > 0) {
                        const totalPrice = selectedRoom.price * days;
                        document.getElementById('price').value = totalPrice;
                        totalPriceElement.innerHTML = `需付款: ${totalPrice}元<br><br>`;
                    }
                }
            }
        }
    </script>
</body>

</html>
