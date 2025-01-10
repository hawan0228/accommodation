<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>篩選結果</title>
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

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            white-space: nowrap;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        img {
            width: 120px;
            height: 90px;
            object-fit: cover;
        }

        th:nth-child(1) {
            width: 140px;
        }

        th:nth-child(2),
        th:nth-child(3),
        th:nth-child(4) {
            width: 180px;
        }

        th:nth-child(5),
        th:nth-child(6) {
            width: 150px;
        }

        th:nth-child(7) {
            width: 200px;
        }

        th:nth-child(8) {
            width: 80px;
        }

        td {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .btn-book {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-book:hover {
            background-color: #45a049;
        }

        .btn-details {
            padding: 5px 10px;
            background-color: #2196F3;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-details:hover {
            background-color: #0b7dda;
        }

        .details-row {
            display: none; /* 默認隱藏詳細資訊 */
            background-color: #f9f9f9;
            text-align: left;
        }

        .details-row td {
            text-align: left;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <?php
    include("connection.php"); // 包含資料庫連接檔案

    // 檢查資料庫是否連接成功
    $select_db = @mysql_select_db("accommodation");
    if (!$select_db) {
        die("<br>找不到資料庫!<br>");
    }

    // 輸出前一頁傳遞的篩選條件
    if (isset($_GET['type']) && is_array($_GET['type'])) {
        echo "<p>住宿類型: " . implode(", ", $_GET['type']) . "</p>";
    }

    if (isset($_GET['facility']) && is_array($_GET['facility'])) {
        echo "<p>設備類型: " . implode(", ", $_GET['facility']) . "</p>";
    }

    if (isset($_GET['price']) && is_array($_GET['price'])) {
        echo "<p>價格範圍: " . implode(", ", $_GET['price']) . "</p>";
    }

    if (isset($_GET['star']) && is_array($_GET['star'])) {
        echo "<p>評價: " . implode(", ", $_GET['star']) . "</p>";
    }

    // 初始化篩選條件
    $conditions = [];

    // 設備類型篩選條件
    if (isset($_GET['facility']) && is_array($_GET['facility'])) {
        $facilityMap = [
            "WiFi" => "wifi",
            "停車場" => "parking",
            "供餐" => "meal",
            "獨立空調" => "air_conditioner",
            "接駁車" => "shuttle",
            "寵物友善" => "pets_allowed"
        ];
    
        $facilityConditions = [];
        foreach ($_GET['facility'] as $facility) {
            if (isset($facilityMap[$facility])) {
                // 將條件改為根據 equip.type 與 accom_data.type 的匹配
                $facilityConditions[] = "accom_data.accom_id IN (
                    SELECT accom_data.accom_id
                    FROM equip
                    JOIN accom_data ON equip.equip_type = accom_data.equip_type
                    WHERE equip.`$facilityMap[$facility]` = 1
                )";
            }
        }
    
        if (!empty($facilityConditions)) {
            $conditions[] = "(" . implode(" AND ", $facilityConditions) . ")";
        }
    }
    

    // 住宿類型篩選條件
    if (isset($_GET['type']) && is_array($_GET['type'])) {
        $validTypes = ['國際觀光旅館', '星級旅館', '旅館', '民宿']; // 定義有效的類型
        $typeConditions = [];
        
        foreach ($_GET['type'] as $type) {
            if (in_array($type, $validTypes)) {
                // 將類型條件轉換為accom_data的accom_id篩選
                $typeConditions[] = "accom_data.accom_id IN (
                    SELECT accom_id FROM accom_data WHERE type = '$type'
                )";
            }
        }
    
        if (!empty($typeConditions)) {
            // 用 OR 連接多個條件
            $conditions[] = "(" . implode(" OR ", $typeConditions) . ")";
        }
    }
    

    // 價格範圍篩選條件
    if (isset($_GET['price']) && is_array($_GET['price'])) {
        $priceConditions = [];
        foreach ($_GET['price'] as $price) {
            if ($price == "3000") {
                $priceConditions[] = "accom_data.accom_id IN (SELECT accom_id FROM room_class WHERE price <= 3000)";
            } elseif ($price == "5000") {
                $priceConditions[] = "accom_data.accom_id IN (SELECT accom_id FROM room_class WHERE price <= 5000)";
            } elseif ($price == "7000") {
                $priceConditions[] = "accom_data.accom_id IN (SELECT accom_id FROM room_class WHERE price <= 7000)";
            } elseif ($price == "9000") {
                $priceConditions[] = "accom_data.accom_id IN (SELECT accom_id FROM room_class WHERE price <= 9000)";
            } elseif ($price == "9001") {
                $priceConditions[] = "accom_data.accom_id IN (SELECT accom_id FROM room_class WHERE price > 9000)";
            }
        }

        if (!empty($priceConditions)) {
            $conditions[] = "(" . implode(" AND ", $priceConditions) . ")";
        }
    }

    // 星級篩選條件
    if (isset($_GET['star']) && is_array($_GET['star'])) {
        $starConditions = [];
        
        foreach ($_GET['star'] as $star) {
            if ($star == "5") {
                // 評價為 5 的 accom_id
                $starConditions[] = "accom_data.accom_id IN (
                    SELECT accom_id FROM accom_data WHERE review = 5
                )";
            } elseif ($star == "4.5") {
                // 評價大於等於 4.5 的 accom_id
                $starConditions[] = "accom_data.accom_id IN (
                    SELECT accom_id FROM accom_data WHERE review >= 4.5
                )";
            } elseif ($star == "4") {
                // 評價大於等於 4 的 accom_id
                $starConditions[] = "accom_data.accom_id IN (
                    SELECT accom_id FROM accom_data WHERE review >= 4
                )";
            }
        }
    
        if (!empty($starConditions)) {
            // 用 AND 連接條件，確保符合所有選中的篩選條件
            $conditions[] = "(" . implode(" AND ", $starConditions) . ")";
        }
    }
    
    // 合併所有條件
    $finalWhereCondition = implode(" AND ", $conditions);

    if (!empty($finalWhereCondition)) {
        // 查詢符合條件的 accom_id
        $query = "SELECT * FROM accom_data WHERE $finalWhereCondition";
    } else {
        // 如果沒有篩選條件，查詢所有 accom_id
        $query = "SELECT * FROM accom_data";
    }
    // 輸出結果 
    $result = mysql_query($query);
    echo "<h2>篩選結果</h2>";
    if (mysql_num_rows($result) > 0) {
        echo "<center><table>";
       echo "<tr><th>圖片</th><th>名稱</th><th>地區</th><th>地址</th><th>評分</th><th>詳細資訊</th><th>預訂</th></tr>";
       while ($row = mysql_fetch_array($result)) {
           $id = $row[0];
           // Fetch the equipment information for each accommodation
           $equip_type = $row['equip_type'];
           $equip_sql = "SELECT * FROM equip WHERE equip_type = '$equip_type'";
           $equip_result = mysql_query($equip_sql);
           $equip_data = mysql_fetch_array($equip_result);

           echo "<tr>";
           ?>
           <td><img src="../project/picture/<?php echo $row[0]; ?>.jpg" alt="住宿圖片"></td>
                <td><?php echo $row[2]; ?></td>
                <td><?php echo $row[3]; ?></td>
                <td><?php echo $row[4]; ?></td>
                <td><?php echo $row[10]; ?></td>
                <td>
                    <!-- 詳細資訊按鈕 -->
                    <button class="btn-details" onclick="toggleDetails(<?php echo $row[0]; ?>)">詳細資訊</button>
                </td>
                <td>
                    <!-- 預訂按鈕 -->
                    <form action="booking.php" method="get">
                        <input type="hidden" name="accom_id" value="<?php echo $row[0]; ?>">
                        <button type="submit" class="btn-book">預訂</button>
                    </form>
                </td>
                </tr>
                <!-- 顯示詳細資訊的行 -->
                <tr class="details-row" id="details-<?php echo $row[0]; ?>">
                    <td colspan="10">
                        <strong>住宿類型:</strong> <?php echo $row[1]; ?> <br>
                        <strong>電話:</strong> <?php echo $row[5]; ?> <br>
                        <strong>信箱:</strong> <?php echo $row[6]; ?> <br>
                        <strong>官網:</strong> <a href="<?php echo $row[7]; ?>" target="_blank"><?php echo $row[7]; ?></a><br>

                        <!-- 顯示配備資料，只顯示為 true 的配備項目 -->
                        <strong>配備:</strong>
                        <?php
                            $equipments = [];
                            if ($equip_data['wifi']) {
                                $equipments[] = 'WiFi';
                            }
                            if ($equip_data['parking']) {
                                $equipments[] = '停車場';
                            }
                            if ($equip_data['meal']) {
                                $equipments[] = '餐廳';
                            }
                            if ($equip_data['air_conditioner']) {
                                $equipments[] = '獨立空調';
                            }
                            if ($equip_data['shuttle']) {
                                $equipments[] = '接駁車';
                            }
                            if ($equip_data['pets_allowed']) {
                                $equipments[] = '寵物友善';
                            }
                            // 輸出配備項目
                            if (count($equipments) > 0) {
                                echo implode('、', $equipments);
                            } else {
                                echo '無配備';
                            }
                        ?>
                    </td>
                </tr>
           <?php
       }
       echo "</table></center>";
    } else {
        echo "<p>查無符合條件的住宿</p>";
    }
    ?>
<script>
    // Function to toggle the visibility of the details row
    function toggleDetails(id) {
        var detailsRow = document.getElementById('details-' + id);
        if (detailsRow.style.display === "none" || detailsRow.style.display === "") {
            detailsRow.style.display = "table-row"; // Show detailed information
        } else {
            detailsRow.style.display = "none"; // Hide detailed information
        }
    }
</script>
</body>

</html>
