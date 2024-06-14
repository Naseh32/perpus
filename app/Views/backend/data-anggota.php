<?= $this->extend('extend/backend'); ?>

<?= $this->section('content'); ?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Anggota</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url(); ?>/admin/dashboard">Admin</a></div>
                <div class="breadcrumb-item">Data Anggota</div>
            </div>
        </div>

        <div class="section-body">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <a type="btn" class="btn btn-primary btn-lg" href="<?= base_url('register'); ?>">Tambah Anggota</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-1" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Id Anggota</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Jurusan</th>
                                        <th>Kelas</th>
                                        <th>No. HP</th>
                                        <th>Status</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($anggota as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= $row['id'] ?></td>
                                        <td><?= $row['nama']; ?></td>
                                        <td><?= $row['email']; ?></td>
                                        <td><?= $row['jenis_kelamin']; ?></td>
                                        <td><?= $row['jurusan']; ?></td>
                                        <td><?= $row['kelas']; ?></td>
                                        <td><?= $row['no_hp']; ?></td>
                                        <td>
                                            <div class="badge <?= ($row['status'] == 'Aktif') ? 'badge-success' : 'badge-danger' ?>">
                                                <?= $row['status'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <img src="<?= base_url('uploads/Anggota/' . $row['foto']); ?>"
                                                 class="img-fluid rounded-circle" style="max-width: 50px;">
                                        </td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>"
                                               class="btn btn-primary btn-sm"><i class="fas fa-edit m-1"></i>Edit</a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id']; ?>"
                                               class="btn btn-success btn-sm"><i class="fa fa-eye m-1"></i>Detail</a>
                                            <a href="#" onclick="konfirmasi('<?= $row['id']; ?>');"
                                               class="btn btn-danger btn-sm"><i class="fa fa-trash m-1"></i>Hapus</a>
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

<?php foreach ($anggota as $row): ?>
<!-- Modal Edit Anggota -->
<div class="modal fade" id="editModal<?= $row['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light" id="editModalLabel">Edit Anggota</h5>
                <button type="button" class="btn btn-danger mb-2" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-times"></i></button>
            </div>
            <form method="POST" action="<?= base_url('/auth/updateAnggota/'); ?>/<?= $row['id']; ?>">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="<?= $row['status']; ?>" selected>Status</option>
                            <option value="Nonaktif">Non Aktif</option>
                            <option value="Aktif">Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin">
                            <option value="Laki-laki" <?= ($row['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Perempuan" <?= ($row['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jurusan</label>
                        <select class="form-control" name="jurusan">
                            <option value="Teknik Komputer Jaringan" <?= ($row['jurusan'] == 'Teknik Komputer Jaringan') ? 'selected' : ''; ?>>Teknik Komputer Jaringan</option>
                            <option value="Teknik Kendaraan Ringan" <?= ($row['jurusan'] == 'Teknik Kendaraan Ringan') ? 'selected' : ''; ?>>Teknik Kendaraan Ringan</option>
                            <option value="Akuntansi" <?= ($row['jurusan'] == 'Akuntansi') ? 'selected' : ''; ?>>Akuntansi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kelas</label>
                        <input type="text" class="form-control" name="kelas" value="<?= $row['kelas']; ?>">
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" class="form-control" name="no_hp" value="<?= $row['no_hp']; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Anggota -->
<div class="modal fade modal-md" id="detailModal<?= $row['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light" id="detailModalLabel">Detail Anggota</h5>
                <button type="button" class="btn btn-danger mb-2" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?= base_url('uploads/Anggota/' . $row['foto']); ?>"
                             class="img-fluid rounded" alt="Foto Profil">
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <th class="table-active">Nis</th>
                                <td><?= $row['nis'] ?></td>
                            </tr>
                            <tr>
                                <th class="table-active">Nama</th>
                                <td><?= $row['nama'] ?></td>
                            </tr>
                            <tr>
                                <th class="table-active">Email</th>
                                <td><?= $row['email'] ?></td>
                            </tr>
                            <tr>
                                <th class="table-active">Status</th>
                                <td>
                                    <div class="badge <?= ($row['status'] == 'Aktif') ? 'badge-success' : 'badge-danger'; ?>">
                                        <?= $row['status'] ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-active">Jenis Kelamin</th>
                                <td><?= $row['jenis_kelamin'] ?></td>
                            </tr>
                            <tr>
                                <th class="table-active">Jurusan</th>
                                <td><?= $row['jurusan'] ?></td>
                            </tr>
                            <tr>
                                <th class="table-active">Kelas</th>
                                <td><?= $row['kelas'] ?></td>
                            </tr>
                            <tr>
                                <th class="table-active">No. HP</th>
                                <td><?= $row['no_hp'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if (session()->getFlashdata('icon')) { ?>
    Swal.fire({
        title: '<?= session()->getFlashdata('title'); ?>',
        showConfirmButton: false,
        iconColor: '#6777ef',
        timer: 1500,
        icon: '<?= session()->getFlashdata('icon'); ?>'
    });
    <?php } ?>

    function konfirmasi(parameter_id) {
        Swal.fire({
            title: 'Apakah Yakin?',
            text: "Data Anggota Akan Terhapus!",
            icon: 'warning',
            showCancelButton: true,
            iconColor: 'red',
            confirmButtonColor: '#6777ef',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url(); ?>/auth/hapusAnggota/" + parameter_id;
            }
        });
    }
</script>

<?= $this->endsection('content'); ?>
