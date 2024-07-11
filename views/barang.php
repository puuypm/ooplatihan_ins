<?php
require_once "models/m_barang.php";
$brg = new Barang($conn);


if (@$_GET['act'] == '') {
?>

  <div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Data Barang</h1>
    <!--11.  -->
    <div class="row">
      <div class="col-lg-12">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped">
            <!--12.Buat Database nya db_latihan.sql -->
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Harga Barang</th>
                <th>Stock Barang</th>
                <th>Gambar Barang</th>
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $tampil = $brg->tampil();

              while ($isi = $tampil->fetch_object()) :
              ?>
                <tr class="text-center">
                  <td><?= $no++ ?></td>
                  <td><?= $isi->nama_brg ?></td>
                  <td><?= $isi->harga_brg ?></td>
                  <td><?= $isi->stock_brg ?></td>
                  <td><img src="assets/img/barang/<?= $isi->gbr_brg ?>" width="80" alt=""></td>
                  <td>
                    <a id="edit_brg" data-toggle="modal" data-target="#edit" data-idedt="<?= $isi->id_brg ?>" data-nama="<?= $isi->nama_brg ?>" data-harga="<?= $isi->harga_brg ?>" data-stc="<?= $isi->stock_brg ?>" data-gbr="<?= $isi->gbr_brg ?>">
                      <button class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button></a>
                    <a href="?page=barang&act=del&id=<?= base64_encode($isi->id_brg) ?>" onclick="return confirm('Apakah ingin menghapus data ini ?')">
                      <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i> Tambah Data</button>
        <a class="btn btn-default" data-toggle="modal" data-target="#cetakpdf" style="margin-bottom: 5px"><i class="fa fa-print"></i> Print PDF</a>
        <!-- Modal Tambah -->
        <div id="tambah" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Tambah Data Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                  <div class="form-group">
                    <label for="nm_brg" class="form-label">Nama Barang</label>
                    <input type="text" name="nm_brg" class="form-control" id="nm_brg" required>
                  </div>
                  <div class="form-group">
                    <label for="hrg_brg" class="form-label">Harga Barang</label>
                    <input type="number" name="hrg_brg" class="form-control" id="hrg_brg" required>
                  </div>
                  <div class="form-group">
                    <label for="stc_brg" class="form-label">Stock Barang</label>
                    <input type="text" name="stc_brg" class="form-control" id="stc_brg" required>
                  </div>
                  <div class="form-group">
                    <label for="gbr_brg" class="form-label">Gambar Barang</label>
                    <input type="file" name="gbr_brg" class="form-control" id="gbr_brg" accept=".png, .jpg, .jpeg, .gif, .svg, .webp" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="reset" class="btn btn-danger">Reset</button>
                  <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
                </div>
              </form>
              <?php
              if (isset($_POST['tambah'])) {
                $nm_brg =  filter_input(INPUT_POST, "nm_brg", FILTER_SANITIZE_SPECIAL_CHARS);
                $hrg_brg = filter_input(INPUT_POST, "hrg_brg", FILTER_SANITIZE_NUMBER_FLOAT);
                $stc_brg = filter_input(INPUT_POST, "stc_brg", FILTER_SANITIZE_NUMBER_INT);

                if (isset($_FILES['gbr_brg']) && $_FILES['gbr_brg']['error'] === UPLOAD_ERR_OK) {
                  $file_name = $_FILES['gbr_brg']['name'];
                  $file_tmp = $_FILES['gbr_brg']['tmp_name'];
                  $file_size = $_FILES['gbr_brg']['size'];
                  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                  $allowed_ext = array("png", "jpg", "jpeg", "gif", "svg", "webp");

                  if (in_array($file_ext, $allowed_ext)) {
                    $gbr_brg = "brg-" . round(microtime(true)) . "." . $file_ext;
                    $upload_path = "assets/img/barang/" . $gbr_brg;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                      $result = $brg->tambah($nm_brg, $hrg_brg, $stc_brg, $gbr_brg);
                      if ($result) {
                        echo "<script>alert('Data Barang Berhasil Ditambah!');</script>";
                        echo "<script>window.location = '?page=barang';</script>";
                        exit();
                      } else {
                        echo "<script>alert('Data Barang Gagal Ditambah!');</script>";
                        echo "<script>window.location = '?page=barang';</script>";
                        exit();
                      }
                    } else {
                      echo "<script>alert('Data Mengungah Gambar!');</script>";
                      echo "<script>window.location = '?page=barang';</script>";
                      exit();
                    }
                  } else {
                    echo "<script>alert('Tipe File yang di unggah tidak di izinkan!');</script>";
                    echo "<script>window.location = '?page=barang';</script>";
                    exit();
                  }
                } else {
                  echo "<script>alert('Data Terjadi Kesalahan saat mengungah file!');</script>";
                  echo "<script>window.location = '?page=barang';</script>";
                  exit();
                }
              }
              ?>
            </div>
          </div>
        </div>
        <!-- Modal Edit -->
        <div id="edit" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Edit Data Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form id="formEdit" enctype="multipart/form-data">
                <div class="modal-body" id="modalBody_">
                  <div class="form-group">
                    <input type="hidden" id="idedt" name="idedt">
                    <label for="nm_brg" class="form-label">Nama Barang</label>
                    <input type="text" name="nm_brg" class="form-control" id="nm_brg" required>
                  </div>
                  <div class="form-group">
                    <label for="hrg_brg" class="form-label">Harga Barang</label>
                    <input type="number" name="hrg_brg" class="form-control" id="hrg_brg" required>
                  </div>
                  <div class="form-group">
                    <label for="stc_brg" class="form-label">Stock Barang</label>
                    <input type="text" name="stc_brg" class="form-control" id="stc_brg" required>
                  </div>
                  <div class="form-group">
                    <label for="gbr_brg" class="form-label">Gambar Barang</label>
                    <div class="my-2">
                      <img src="" alt="" width="80" id="pict">
                    </div>
                    <input type="file" name="gbr_brg" class="form-control" id="gbr_brg" accept=".png, .jpg, .jpeg, .gif, .svg, .webp">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" name="edit">Edit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <script src="assets/vendor/jquery/jquery.min.js" type="text/javascript"></script>
        <script>
          $(document).on("click", "#edit_brg", function() {
            var idbrg = $(this).data('idedt');
            var nama = $(this).data('nama');
            var hrgbrg = $(this).data('harga');
            var stcbrg = $(this).data('stc');
            var gbrbrg = $(this).data('gbr');

            $("#modalBody_ #idedt").val(idbrg);
            $("#modalBody_ #nm_brg").val(nama);
            $("#modalBody_ #hrg_brg").val(hrgbrg);
            $("#modalBody_ #stc_brg").val(stcbrg);
            $("#modalBody_ #pict").attr("src", "assets/img/barang/" + gbrbrg);
          })
          // process :
          $(document).ready(function(e) {
            $("#formEdit").on("submit", (function(e) {
              e.preventDefault();
              $.ajax({
                url: 'models/prcs_barang.php',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(msg) {
                  $('.table').html(msg);
                }
              })
            }))
          })
        </script>

        <div id="cetakpdf" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Cetak PDF</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="reports/reportPDF.php" method="POST" target="_blank">
                  <table>
                    <tr>
                      <td>
                        <div class="form-group">Dari Tanggal</div>
                      </td>
                      <td align="center" width=5%>
                        <div class="form-group">:</div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="date" class="form-control" name="tgl_a" required>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="form-group">Sampai Tanggal</div>
                      </td>
                      <td align="center" width=5%>
                        <div class="form-group">:</div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="date" class="form-control" name="tgl_b" required>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>
                        <input type="submit" name="cetak_barang" class="btn btn-primary btn-sm" value="Print per-periode">
                      </td>
                    </tr>
                  </table>
                </form>
              </div>
              <div class="modal-footer">
                <a href="reports/exportPdf.php" target="_blank" class="btn btn-primary btn-sm">Cetak Semua Data</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
} else if (@$_GET['act'] == 'del') {
  $iddecode = base64_decode($_GET['id']);
  echo "id nya :" . $iddecode;
  $gbr_awal = $brg->tampil($iddecode)->fetch_object()->gbr_brg;

  unlink("assets/img/barang/" . $gbr_awal);
  $brg->hapus($iddecode);

  echo "<script>window.location = '?page=barang';</script>";
  exit();
}
?>