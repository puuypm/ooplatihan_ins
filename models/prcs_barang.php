<?php
require_once "../config/+koneksi.php";
require_once "../models/database.php";
require_once "../models/m_barang.php";

$conn = new Database($host, $user, $pass, $database);
$brg = new Barang($conn);

$id = filter_input(INPUT_POST, "idedt", FILTER_SANITIZE_NUMBER_INT);
$nm_brg = filter_input(INPUT_POST, "nm_brg", FILTER_SANITIZE_SPECIAL_CHARS);
$hrg_brg = filter_input(INPUT_POST, "hrg_brg", FILTER_SANITIZE_NUMBER_FLOAT);
$stc_brg = filter_input(INPUT_POST, "stc_brg", FILTER_SANITIZE_NUMBER_INT);

$pict = $_FILES['gbr_brg']['name'];
$gbr_brg = '';

if (!empty($pict)) {
    $old_data = $brg->tampil($id)->fetch_assoc();
    $old_gbr_brg = $old_data['gbr_brg'];

    $extensi = explode(".", $pict);
    $gbr_brg = "brg-" . round(microtime(true)) . "." . end($extensi);
    $sumber = $_FILES['gbr_brg']['tmp_name'];
    $upload_path = "../assets/img/barang/" . $gbr_brg;

    if (move_uploaded_file($sumber, $upload_path)) {
        if ($old_gbr_brg && file_exists("../assets/img/barang/" . $old_gbr_brg)) {
            unlink("../assets/img/barang/" . $old_gbr_brg);
        }
        $brg->edit("UPDATE tb_barang SET nama_brg = ?, harga_brg = ?, stock_brg = ?, gbr_brg = ? WHERE id_brg = ?", [
            $nm_brg,
            $hrg_brg,
            $stc_brg,
            $gbr_brg,
            $id
        ]);
        echo "<script>window.location = '?page=barang'</script>";
        exit;
    } else {
        echo "Gagal mengungah gambar.";
        exit;
    }
} else {
    $brg->edit("UPDATE tb_barang SET nama_brg = ?, harga_brg = ?, stock_brg = ? WHERE id_brg = ?", [
        $nm_brg,
        $hrg_brg,
        $stc_brg,
        $id
    ]);
    echo "<script>window.location = '?page=barang'</script>";
    exit;
}
