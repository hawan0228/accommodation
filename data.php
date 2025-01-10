<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住宿資訊</title>
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
    include("connection.php");
    $select_db = @mysql_select_db("accommodation");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
    } else {
        $sql_query = "SELECT * FROM accom_data";
        $result = mysql_query($sql_query);
        if (mysql_num_rows($result) > 0) {
            echo "<h1>住宿資訊</h1>";
            echo "<center><table>";
            echo "<tr><th>圖片</th><th>名稱</th><th>地區</th><th>地址</th><th>評分</th><th>詳細資訊</th><th>預訂</th></tr>";
            while ($row = mysql_fetch_array($result)) {
                $id = $row[0];
                $equip_type = $row['equip_type'];  // 取得 equip_type
                
                // 查詢配備資料
                $equip_sql = "SELECT * FROM equip WHERE equip_type = '$equip_type'";
                $equip_result = mysql_query($equip_sql);
                $equip_data = mysql_fetch_array($equip_result);
                
                echo "<tr>";
                ?>
                <td><img src="../project/picture/<?php echo $row[0]; ?>.jpg" alt="住宿圖片"></td>
                <td><?php echo $row[2];  ?></td>
                <td><?php echo $row[3];  ?></td>
                <td><?php echo $row[4];  ?></td>
                <td><?php echo $row[10];  ?></td>
                <td>
                    <!-- 詳細資訊按鈕 -->
                    <button class="btn-details" onclick="toggleDetails(<?php echo $row[0]; ?>)">詳細資訊</button>
                </td>
                <td>
                    <!-- 修改表單target，讓其顯示在同一個iframe中 -->
                    <form action="booking.php" method="get" target="dataFrame">
                        <input type="hidden" name="accom_id" value="<?php echo $row[0]; ?>">
                        <button type="submit" class="btn-book">預訂</button>
                    </form>
                </td>
                </tr>
                <!-- 顯示詳細資訊的行 -->
                <tr class="details-row" id="details-<?php echo $row[0]; ?>">
                    <td colspan="7">
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
            echo "<br>查無資料!";
        }
    }
    ?>

    <script>
        // 切換顯示詳細資訊
        function toggleDetails(id) {
            var detailsRow = document.getElementById('details-' + id);
            if (detailsRow.style.display === "none" || detailsRow.style.display === "") {
                detailsRow.style.display = "table-row"; // 顯示詳細資訊
            } else {
                detailsRow.style.display = "none"; // 隱藏詳細資訊
            }
        }
    </script>
</body>
