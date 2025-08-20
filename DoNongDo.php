<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đo nồng độ cồn</title>
    <link rel="stylesheet" href="cssDoNongDo.css">
</head>

<body>
    <div class="main_box">
        <h2 style="margin-bottom: 10px; margin-top: 20px; margin-left: 18px;">Tính nồng độ cồn</h2>
        <form name="Form_Nong_Do" method="POST" action="ketqua.php">
            <!--  box1  -->
            <div class="box1">
                <!--  Giới tính  -->
                <div class="GioiTinh">
                    <label for=""><strong>Giới tính</strong></label>
                    <div class="">
                        <input type="radio" id="nam" name="GioiTinh" value="nam" required>
                        <label class="btn" for="nam">Nam</label>
                        <input type="radio" id="nu" name="GioiTinh" value="nu" required>
                        <label class="btn" for="nu">Nữ</label>
                    </div>
                </div>

                <!--  Cân nặng  -->
                <div class="CanNang">
                    <label for=""><strong>Cân nặng</strong></label>
                    <div class="in">
                        <input type="number" min="30" name="CanNang" id="CanNang" required> <span>kg</span>
                    </div>
                </div>
            </div>

            <!--  box2  -->
            <div class="box2">

                <!--  Loại đồ uống  -->
                <div class="DoUong">
                    <label for=""><strong>Loại đồ uống</strong></label>
                    <div class="">
                        <input type="radio" id="bia" name="LoaiDoUong" value="bia" required>
                        <label class="btn" for="bia">Bia</label>
                        <input type="radio" id="ruoumanh" name="LoaiDoUong" value="ruoumanh" required>
                        <label class="btn" for="ruoumanh">Rượu mạnh</label>
                        <input type="radio" id="ruouvang" name="LoaiDoUong" value="ruouvang" required>
                        <label class="btn" for="ruouvang">Rượu vang</label>
                    </div>
                </div>

                <!--  Số lượng đã uống  -->
                <div class="SoLuong">
                    <label for=""><strong>Số lượng đã uống</strong></label>
                    <div class="in">
                        <input type="number" min="1" name="SoLuong" id="SoLuong" required>
                        <label for=""> (Lon, chén, ly) </label>
                    </div>
                </div>
            </div>

            <!--  box3  -->
            <div class="box3">

                <!--  Phương tiện  -->
                <div class="Phuongtien">
                    <label for=""><strong>Phương tiện</strong></label>
                    <div class="">

                        <input type="radio" id="xemay" name="Phuongtien" value="xemay" required>
                        <label class="btn" for="xemay">Xe máy</label>
                        <input type="radio" id="oto" name="Phuongtien" value="oto" required>
                        <label class="btn" for="oto">Ô tô</label>
                        <input type="radio" id="xethoso" name="Phuongtien" value="xethoso" required>
                        <label class="btn" for="xethoso">Xe thô sơ</label>
                    </div>
                </div>
            </div>
            <button class="btn_sup" type="submit"><Strong>Xem kết quả</Strong></button>
        </form>
    </div>
</body>

</html>