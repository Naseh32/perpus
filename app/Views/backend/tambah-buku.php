<?= $this->extend('extend/backend') ?>

<?= $this->section('content') ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tambah Buku</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/dashboard">Admin</a></div>
              <div class="breadcrumb-item">Tambah Buku</div>
            </div>
          </div>

          <div class="section-body">
            <div class="card card-primary">
              <div class="text-center mt-3">
                <h1 style="font-weight:bold;">Tambah Buku</h1>
                <hr>
              </div>
              <div class="card-body">
                <?= session()->getFlashdata('pesan'); ?>
                <form method="POST" action="<?= base_url(); ?>/auth/tambahBuku" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="namaL">Judul</label>
                        <input type="text" name="judul" class="form-control <?= ($validate->hasError('gambar')) ? 'is-invalid' : '' ; ?>" placeholder="Judul" required>
                        <div id="namaK" class="m-1"></div>
                         <div class="invalid-feedback">
                           <?= $validate->getError('judul'); ?>
                         </div>
                    </div>
                    <div class="form-group">
                        <label for="namaL">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" placeholder="Pengarang">
                    </div>
                      <div class="form-group">
                        <label>Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" placeholder="Penerbit">
                        <div id="kon1" class="m-1"></div>
                      </div>
                      <div class="d-flex">
                        <div class="form-group col-md-6 pe-4">
                            <label>Tahun Terbit</label>
                            <input type="text" id="te" name="tahun_t" class="form-control" placeholder="Tahun Terbit">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Kategori</label>
                            <input type="text" name="kategori" class="form-control">
                        </div>
                      </div>
                      <div class="d-flex">
                        <div class="form-group col-md-6 pe-4">
                            <label >ISBN</label>
                            <input type="text" name="isbn" class="form-control"  placeholder="ISBN" >
                        </div>
                        <div class="form-group col-md-6">
                            <label >Jumlah Buku</label>
                            <input type="text" name="j_buku" class="form-control" placeholder="Jumlah Buku" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label >Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="from-control"></textarea>
                    </div>
                    <label for="gambar" class="my-2" style="font-weight:bold;">Sampul Buku</label>
                    <div class="from-group row">
                      <div class="form-group col-md-3">
                          <img src="<?= base_url('gambar-sampul/default.jpg') ?>"  class="img-thumbnail img-preview">
                        </div>
                      <div class="form-group col-md-6">
                        <input type="file" name="gambar" id="sampul" onchange="preview()" class="form-control custom-file-input <?= ($validate->hasError('gambar')) ? 'is-invalid' : '' ; ?>" placeholder="Masukkan gambar" >
                        <div class="invalid-feedback ">
                          <?= $validate->getError('gambar'); ?>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex my-3">
                        <div class="form-group m-1">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary btn-lg">Simpan</button>
                        </div>
                  </form>
                        <div class="form-group m-1">
                            <button type="submit" id="batal" class="btn btn-danger btn-lg">Batal</button>
                        </div>
                    </div>
            </div>
          </div>
        </section>
      </div>
      <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

      <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
      <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      
      <script>
         ClassicEditor.create(document.querySelector('#deskripsi'));
        </script>
      <script>
        $(document).ready(function(){
          $('#batal').click(function(){
            Swal.fire({
              title: 'Apakah Yakin?',
              text: "Kamu akan keluar dari halaman tambah buku!",
              icon: 'warning',
              showCancelButton: true,
              iconColor:'red',
              confirmButtonColor: '#6777ef',
              cancelButtonColor: 'red',
              confirmButtonText: 'Keluar',
              cancelButtonText: 'Batal'
            }).then((result) => {
              if (result.isConfirmed) {
              window.location= '<?= base_url(); ?>/admin/data-buku';
              }
            });
          });
            $("#te").keypress(function(e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

        });	

        function preview(){
            const sampul = document.querySelector('#sampul');
            const preview = document.querySelector('.img-preview');

            const fileSampul = new FileReader();
            fileSampul.readAsDataURL(sampul.files[0]);

            fileSampul.onload = function(e){
              preview.src = e.target.result;
            }
        }

    </script>
  
<?= $this->endSection('content') ?>
