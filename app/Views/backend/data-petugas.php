<?= $this->extend('extend/backend'); ?>

<?= $this->section('content'); ?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Admin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url();?>/admin/dashboard">Admin</a></div>
                <div class="breadcrumb-item">Data Admin</div>
            </div>
        </div>

        <div class="section-body">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a type="btn" class="btn btn-primary btn-lg" href="<?= base_url(); ?>/admin/tambah-petugas">Tambah Admin</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-1" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            No
                                        </th>
                                        <th class="text-center">
                                            Kode Admin
                                        </th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Status</th>
                                        <th>Level</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach($petugas as $row): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $row['id'] ?>
                                        </td>
                                        <td><?= $row['nama']; ?></td>
                                        <td><?= $row['username']; ?></td>
                                        <td><?= $row['jenis_kelamin']; ?></td>
                                        <td><div class="badge <?= ($row['status'] == 'Aktif') ? 'badge-success' : 'badge-danger'; ?>"><?= $row['status'] ?></div></td>
                                        <td><?= $row['level']; ?></td>
                                        <td>
                                            <?php if ($row['foto']): ?>
                                                <img src="<?= base_url('uploads/Admin/' . $row['foto']); ?>" class="img-fluid" alt="Foto Admin">
                                            <?php else: ?>
                                                <img src="<?= base_url('assets/img/avatar/default.jpg'); ?>" class="img-fluid" alt="Default Foto">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit m-1"></i>Edit</a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#detail<?= $row['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-eye m-1"></i>Detail</a>
                                            <a href="#" onclick="konfirmasi('<?= $row['id']; ?>');" class="btn btn-danger btn-sm"><i class="fa fa-trash m-1"></i>Hapus</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php foreach($petugas as $row): ?>
<!-- Modal -->  
<div class="modal fade" id="staticBackdrop<?= $row['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light" id="staticBackdropLabel">Edit Petugas</h5>
                <button type="button" class="btn btn-danger mb-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
            </div>
            <form method="POST" action="<?= base_url(); ?>/auth/updatePetugas/<?= $row['id']; ?>">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="<?= $row['status']; ?>" selected><?= $row['status'] ?></option>
                            <option value="Nonaktif">Nonaktif</option>
                            <option value="Aktif">Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Level</label>
                        <select class="form-control" name="level">
                            <option value="<?= $row['level']; ?>" selected>Pilih Level</option>
                            <option value="Petugas">Petugas</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary" id="simpanU">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-md" id="detail<?= $row['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light" id="staticBackdropLabel">Detail Petugas</h5>
                <button type="button" class="btn btn-danger mb-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th class="table-active">Nama</th>
                        <td><?= $row['nama'] ?></td>
                    </tr>
                    <tr>
                        <th class="table-active">Username</th>
                        <td><?= $row['username'] ?></td>
                    </tr>
                    <tr>
                        <th class="table-active">Jenis Kelamin</th>
                        <td><?= $row['jenis_kelamin'] ?></td>
                    </tr>
                    <tr>
                        <th class="table-active">Status</th>
                        <td><div class="badge <?= ($row['status'] == 'Aktif') ? 'badge-success' : 'badge-danger'; ?>"><?= $row['status'] ?></div></td>
                    </tr>
                    <tr>
                        <th class="table-active">Level</th>
                        <td><?= $row['level']; ?></td>
                    </tr>
                    <tr>
                        <th class="table-active">Foto</th>
                        <td>
                            <?php if ($row['foto']): ?>
                                <img src="<?= base_url('uploads/Admin/' . $row['foto']); ?>" class="img-fluid" alt="Foto Admin">
                            <?php else: ?>
                                <img src="<?= base_url('assets/img/avatar/default.jpg'); ?>" class="img-fluid" alt="Default Foto">
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php endforeach; ?>


<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if (session()->getFlashdata('icon')){ ?>
    Swal.fire({
        title: '<?= session()->getFlashdata('title'); ?>',
        showConfirmButton: false,
        iconColor:'#6777ef',
        timer: 1500,
        icon: '<?= session()->getFlashdata('icon'); ?>'
    });
<?php } ?>

function konfirmasi(parameter_id){
    Swal.fire({
        title: 'Apakah Yakin?',
        text: "Data Petugas Akan Terhapus!",
        icon: 'warning',
        showCancelButton: true,
        iconColor:'red',
        confirmButtonColor: '#6777ef',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href="<?= base_url();?>/auth/hapusPetugas/"+parameter_id;
        }
    });
}
</script>

<?= $this->endsection('content'); ?>
</body>
</html>
