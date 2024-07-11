<?php
class Barang
{
    private $mysqli;

    function __construct($conn)
    {
        $this->mysqli = $conn;
    }
    public function tampil($id = null)
    {
        $db = $this->mysqli->conn;
        $sql = "SELECT * FROM tb_barang";
        if ($id != null) {
            $sql .= " WHERE id_brg = ?";
        }
        $stmt = $db->prepare($sql);
        if ($id != null) {
            $stmt->bind_param("i", $id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function tambah($nm_brg, $hrg_brg, $stc_brg, $gbr_brg)
    {
        $db = $this->mysqli->conn;
        $stmt = $db->prepare("INSERT INTO tb_barang (nama_brg, harga_brg, stock_brg, gbr_brg, tgl_publish) VALUES (?,?,?,?,now())");
        if ($stmt === false) {
            die("Stetmentnya error" . $db->error);
        }
        $stmt->bind_param("sdis", $nm_brg, $hrg_brg, $stc_brg, $gbr_brg);
        $result = $stmt->execute();
        if ($result === false) {
            die("INSERTNYA ERROR BROOO" . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function edit($sql, $params)
    {
        $db = $this->mysqli->conn;
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            die("PREPARE STATEMENT ERROR BRO" . $db->error);
        }
        $type = str_repeat('s', count($params));
        $stmt->bind_param($type, ...$params);
        $result = $stmt->execute();
        if ($result === false) {
            die("Update error" . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function hapus($id)
    {
        $db = $this->mysqli->conn;

        $stmt = $db->prepare("DELETE FROM tb_barang WHERE id_brg = ?");
        if ($stmt === false) {
            die("Prepare statementnya error" . $db->error);
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        if ($result === false) {
            die("Delete error" . $stmt->error);
        }
        $stmt->close();
        return $result;
    }
}
