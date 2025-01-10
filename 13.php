<html>

<head>
    <title>會員註冊</title>
    <style>
        table {
            width: 80%; 
            height: 500px;
            border-collapse: collapse;
            margin: 0 auto;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2; /* 灰色背景 */
        }

        tr:nth-child(odd) {
            background-color: #ffffff; /* 白色背景 */
        }

        input[type="text"], select {
            width: 100%;
            padding: 5px;
        }

        input[type="reset"], input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="reset"]:hover, input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-header {
            text-align: center;
            font-size: 20px;
        }

        .form-container {
            width: 50%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form method="get" action="15.php">
            <p class="form-header">新增住宿</p>
            <hr>
            <table>
                <tr>
                    <td align="right">住宿類型: </td>
                    <td align="left"><input type="text" maxLength="64" size="10" name="type"></td>
                </tr>
                <tr>
                    <td align="right">住宿店名: </td>
                    <td align="left"><input type="text" maxLength="64" size="10" name="name"></td>
                </tr>
                <tr>
                    <td align="right">地區: </td>
                    <td align="left"><input type="text" size="64" name="district"></td>
                </tr>
                <tr>
                    <td align="right">地址: </td>
                    <td align="left"><input type="text" size="64" name="address"></td>
                </tr>
                <tr>
                    <td align="right" width="20%">電話:</td>
                    <td align="left"><input maxLength="64" size="10" name="phone" type="text"></td>
                </tr>
                <tr>
                    <td align="right">信箱: </td>
                    <td align="left"><input type="text" size="64" name="mail"></td>
                </tr>
                <tr>
                    <td align="right">官網: </td>
                    <td align="left"><input type="text" size="64" name="URL"></td>
                </tr>
                <tr>
                    <td align="right">房間數量: </td>
                    <td align="left"><input type="text" size="64" name="room_num"></td>
                </tr>
                <tr>
                    <td align="right">配備:</td>
                    <td align="left">
                        <select name="equip_type">
                            <option selected value='A'>A</option>
                            <option value='B'>B</option>
                            <option value='C'>C</option>
                            <option value='D'>D</option>
                            <option value='E'>E</option>
                            <option value='F'>F</option>
                            <option value='G'>G</option>
                            <option value='H'>H</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">評價: </td>
                    <td align="left"><input type="text" size="64" name="review"></td>
                </tr>
            </table>
            <p align="center">
                <input value="取消 " type="reset">
                <input value="新增住宿" type="submit">
            </p>
        </form>
    </div>
</body>

</html>
