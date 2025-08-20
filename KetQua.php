<?php
include 'connect.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ form
$gioitinh = isset($_POST['GioiTinh']) ? $_POST['GioiTinh'] : '';
$cannang = isset($_POST['CanNang']) ? (float) $_POST['CanNang'] : 0;
$loaidouong = isset($_POST['LoaiDoUong']) ? $_POST['LoaiDoUong'] : '';
$soluong = isset($_POST['SoLuong']) ? (int) $_POST['SoLuong'] : 0;
$phuongtien = isset($_POST['Phuongtien']) ? $_POST['Phuongtien'] : '';

// Định lượng ml cồn theo loại đồ uống
switch ($loaidouong) {
    case "bia":
        $ml_con = 330 * 0.05; // 330ml bia 5% cồn
        break;
    case "ruouvang":
        $ml_con = 100 * 0.135; // 100ml rượu vang 13.5% cồn
        break;
    case "ruoumanh":
        $ml_con = 30 * 0.4; // 30ml rượu mạnh 40% cồn
        break;
    default:
        $ml_con = 0;
        break;
}

// Tổng lượng cồn uống vào (ml)
$tong_con_ml = $ml_con * $soluong;

// Chuyển lượng cồn sang gram (g) (vì 1ml cồn ~ 0.789g)
$tong_con_gram = $tong_con_ml * 0.789;

// Tính nồng độ cồn trong máu theo công thức Widmark
$he_so_phannhuc = ($gioitinh == "nam") ? 0.68 : 0.55;

// Nồng độ cồn trong máu (g/100ml)
$nong_do_con_mau = $tong_con_gram / ($cannang * $he_so_phannhuc);

// Chuyển sang mg/100ml
$nong_do_con_mg_100ml = $nong_do_con_mau * 100;

// Nồng độ khí thở (mg/lít)
$nong_do_con_khi_tho = $nong_do_con_mg_100ml / 210;

// Tính thời gian đào thải cồn (giờ)
$ty_le_dao_thai = 0.1; // Tỷ lệ đào thải trung bình: 0.1g/kg cơ thể/giờ
$thoi_gian_het_con = ($tong_con_gram / $cannang) / $ty_le_dao_thai;

// Mức phạt
$muc_phat = "";
$diem_tru = 0;

if ($phuongtien == "xemay") {
    if ($nong_do_con_mg_100ml > 0 && $nong_do_con_mg_100ml <= 50) {
        $muc_phat = "2 - 3 triệu đồng";
        $diem_tru = -4;
        $hinh_phat_bo_sung = null;
    } elseif ($nong_do_con_mg_100ml > 50 && $nong_do_con_mg_100ml <= 80) {
        $muc_phat = "6 - 8 triệu đồng";
        $diem_tru = -10;
        $hinh_phat_bo_sung = null;
    } elseif ($nong_do_con_mg_100ml > 80) {
        $muc_phat = "8 - 10 triệu đồng";
        $diem_tru = null;
        $hinh_phat_bo_sung = "Tước quyền sử dụng GPLX từ 22 đến 24 tháng";
    }
} elseif ($phuongtien == "oto") {
    if ($nong_do_con_mg_100ml > 0 && $nong_do_con_mg_100ml <= 50) {
        $muc_phat = "6 - 8 triệu đồng";
        $diem_tru = -4;
        $hinh_phat_bo_sung = null;
    } elseif ($nong_do_con_mg_100ml > 50 && $nong_do_con_mg_100ml <= 80) {
        $muc_phat = "18 - 20 triệu đồng";
        $diem_tru = -10;
        $hinh_phat_bo_sung = null;
    } elseif ($nong_do_con_mg_100ml > 80) {
        $muc_phat = "30 - 40 triệu đồng";
        $diem_tru = null;
        $hinh_phat_bo_sung = "Tước quyền sử dụng GPLX từ 22 đến 24 tháng";
    }
} else { // xe đạp, xe thô sơ
    if ($nong_do_con_mg_100ml > 0 && $nong_do_con_mg_100ml <= 50) {
        $muc_phat = "100.000 - 200.000 đồng";
        $diem_tru = null;
        $hinh_phat_bo_sung = null;
    } elseif ($nong_do_con_mg_100ml > 50 && $nong_do_con_mg_100ml <= 80) {
        $muc_phat = "300.000 - 400.000 đồng";
        $diem_tru = null;
        $hinh_phat_bo_sung = null;
    } elseif ($nong_do_con_mg_100ml > 80) {
        $muc_phat = "400.000 - 600.000 đồng";
        $diem_tru = null;
        $hinh_phat_bo_sung = null;
    }
}
    // Chuyển giá trị về dạng phù hợp với DB
$gioitinh_db = ($gioitinh == 'nam') ? 'Nam' : 'Nu';
$glplx = ($diem_tru != null) ? $diem_tru . " Điểm" : $hinh_phat_bo_sung;

// Câu lệnh INSERT
$sql = "INSERT INTO thongtin (gioi_tinh, can_nang, phuong_tien, nong_do_con, tg_het_con, muc_phat, glplx)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// Chuẩn bị câu lệnh
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisiiis", $gioitinh_db, $cannang, $phuongtien, 
                  $nong_do_con_mg_100ml, $thoi_gian_het_con, $muc_phat, $glplx);

// Thực thi
if (!$stmt->execute()) {
    echo "Lỗi khi lưu dữ liệu: " . $stmt->error;
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Kết quả tính nồng độ cồn</title>
    <link rel="stylesheet" href="cssKetQua.css">
</head>

<body>

    <div class="container">
        <h2>Tính nồng độ cồn</h2>
            <div class="item">
                <div class="mot">
                    <strong style="display: inline;">Giới tính:</strong> <?php echo ucfirst($gioitinh); ?> 
                    <br>
                    <strong style="display: inline;">Cân nặng:</strong> <?php echo $cannang; ?> kg
                </div>

                <div class="hai">
                    <strong style="display: inline;">Đã uống:</strong> <?php echo $soluong; ?>
                    <?php echo ($loaidouong == 'bia') ? 'lon bia' : (($loaidouong == 'ruouvang') ? 'ly rượu vang' : 'chén rượu mạnh'); ?>
                    <br>
                    <strong style="display: inline;">Phương tiện:</strong>
                    <?php echo ($phuongtien == 'xemay') ? 'Xe máy' : (($phuongtien == 'oto') ? 'Ô tô' : 'Xe thô sơ'); ?>
                </div>


            </div>

            <hr>

            <div class="ketqua">
                <h2 style="margin-bottom: 30px;">Kết quả</h2>
                <p><strong>Nồng độ cồn:</strong>
                    <?php echo number_format($nong_do_con_mg_100ml, 1); ?> mg/100 ml máu,
                    tương đương <?php echo number_format($nong_do_con_khi_tho, 3); ?> mg/lít khí thở.
                </p>
            </div>

            <div class="item">
                <div class="thoigian">
                    <strong>Thời gian hết cồn</strong>
                    <div style="color: #c92a57; font-size: 24px;"><?php echo number_format($thoi_gian_het_con, 1); ?> giờ
                    </div>
                </div>

                <div class="mucphat">
                    <strong>Phạt tiền</strong>
                    <div style="color: #c92a57; font-size: 24px;"><?php echo $muc_phat ?: 'Không bị phạt'; ?></div>
                </div>

                <?php if ($phuongtien != "xethoso") { ?>
                    <div class="gplx">
                        <strong>GPLX</strong>
                        <div style="color: #c92a57; font-size: 24px;">
                            <?php echo ($diem_tru != null) ? $diem_tru . " Điểm" : $hinh_phat_bo_sung; ?></div>
                    </div>
                <?php } ?>
            </div>

        <p style="color: grey; font-size: 13px;">Thông tin có tính tham khảo</p>

        <form action="NongDoCon.php">
            <button type="submit" class="btn_thulai">Thử lại</button>
        </form>
    </div>

</body>

</html>