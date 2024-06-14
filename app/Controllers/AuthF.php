<?php

namespace App\Controllers;

use \App\Models\M_frontend;
use \App\Models\M_token;

class AuthF extends BaseController
{
    // Function to register a user
    public function daftarUser()
    {
        $validate = \Config\Services::validation();

        $pw1 = $this->request->getPost('password1');
        $pw2 = $this->request->getPost('password2');

        if ($pw1 !== $pw2) {
            session()->setFlashdata('pesan', '<div class="alert alert-primary">Password tidak sama!</div>');
            return redirect()->to('/register');
        }

        $val = $this->validate([
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama belum diisi'
                ]
            ],
            'nis' => [
                'rules' => 'required|min_length[8]|max_length[9]|is_unique[tb_user.nis]',
                'errors' => [
                    'required' => 'NIS belum diisi',
                    'min_length' => 'NIS terlalu pendek',
                    'max_length' => 'NIS terlalu panjang',
                    'is_unique' => 'NIS sudah dipakai'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[tb_user.email]',
                'errors' => [
                    'required' => 'Email belum diisi',
                    'valid_email' => 'Email tidak valid!',
                    'is_unique' => 'Email sudah dipakai'
                ]
            ],
            'jenis_kelamin' => [
                'rules' => 'required|in_list[Laki-laki,Perempuan]',
                'errors' => [
                    'required' => 'Jenis kelamin belum dipilih',
                    'in_list' => 'Pilihan jenis kelamin tidak valid'
                ]
            ],
            'jurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jurusan belum diisi'
                ]
            ],
            'kelas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kelas belum diisi'
                ]
            ],
            'no_hp' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nomor HP belum diisi'
                ]
            ],
            'foto' => [
                'rules' => 'uploaded[foto]|max_size[foto,1024]|is_image[foto]',
                'errors' => [
                    'uploaded' => 'Foto belum diupload',
                    'max_size' => 'Ukuran foto terlalu besar (max 1MB)',
                    'is_image' => 'File yang diupload bukan gambar'
                ]
            ],
            'password1' => [
                'rules' => 'required|min_length[8]|max_length[12]',
                'errors' => [
                    'required' => 'Password belum diisi',
                    'min_length' => 'Password terlalu pendek',
                    'max_length' => 'Password terlalu panjang'
                ]
            ],
            'password2' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Konfirmasi password belum diisi'
                ]
            ],
        ]);

        if (!$val) {
            session()->setFlashdata('errors', $validate->getErrors());
            return redirect()->to('/register')->withInput();
        }

        $email = $this->request->getPost('email');

        // Process photo upload
        $foto = $this->request->getFile('foto');
        if ($foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/Anggota/', $newName);
            $fotoName = $newName;
        } else {
            session()->setFlashdata('pesan', '<div class="alert alert-danger">Gagal upload foto</div>');
            return redirect()->to('/register')->withInput();
        }

        // Save user data to database
        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama' => $this->request->getPost('nama'),
            'password' => password_hash($this->request->getPost('password1'), PASSWORD_DEFAULT),
            'email' => $email,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'jurusan' => $this->request->getPost('jurusan'),
            'kelas' => $this->request->getPost('kelas'),
            'no_hp' => $this->request->getPost('no_hp'),
            'foto' => $fotoName,
        ];

        $model = new M_frontend();
        $model->insert($data);

        $modelt = new M_token();

        // Generate token
        $token = base64_encode(random_bytes(32));
        $user_token = [
            'email' => $email,
            'token' => $token,
            'date_created' => time()
        ];

        $modelt->insert($user_token);

        // Send verification email
        $this->_sendEmail($token, 'verify');

        session()->setFlashdata('icon', 'success');
        session()->setFlashdata('title', 'Selamat Anda Telah Berhasil Mendaftar!');
        session()->setFlashdata('text', 'Silahkan cek email untuk mengaktivasi akunmu!');
        return redirect()->to('/login');
    }

    // Function to login a user
    public function loginUser()
    {
        $model = new M_frontend();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $row = $model->where('email', $email)->first();

        if (!$row) {
            session()->setFlashdata('email', '<p class="text-danger text-small">*Email tidak ditemukan</p>');
            return redirect()->to('/login');
        }

        if ($row['status'] === 'Nonaktif') {
            session()->setFlashdata('icon', 'warning');
            session()->setFlashdata('title', 'Akun kamu belum bisa login');
            session()->setFlashdata('text', 'Kamu harus menunggu petugas mengaktifkan akun kamu. Jika ingin lebih cepat silahkan datangi petugas di perpustakaan!');
            return redirect()->to('/login');
        }

        if (password_verify($password, $row['password'])) {
            $data = [
                'login' => 'Ya',
                'status' => $row['status'],
                'nama' => $row['nama'],
                'level' => $row['level'],
            ];
            session()->set($data);
            return redirect()->to('/');
        }

        session()->setFlashdata('pesanPassword', '<p class="text-danger text-small">*Password salah!</p>');
        return redirect()->to('/login');
    }

    // Function to send email
    private function _sendEmail($token, $type)
    {
        $this->email = \Config\Services::email();

        if ($type === 'verify') {
            $this->email->setFrom('reihan.tdn@gmail.com', 'Perpustakaan Digital');
            $this->email->setTo($this->request->getPost('email'));
            $this->email->setSubject('Konfirmasi Email Perpustakaan Digital');
            $this->email->setMessage('<p>Selamat akunmu berhasil terdaftar!</p><p>Silahkan verifikasi melalui link ini <a href="' . base_url() . 
                '/authF/verify?email=' . $this->request->getPost('email') . '&token=' . urlencode($token) . '">Verifikasi Email disini</a></p>');

            if (!$this->email->send()) {
                return false;
            }
        }

        if ($type === 'password') {
            $this->email->setFrom('reihan.tdn@gmail.com', 'Perpustakaan Digital');
            $this->email->setTo($this->request->getPost('email'));
            $this->email->setSubject('Ganti Password');
            $this->email->setMessage('<p>Silahkan ubah passwordmu melalui link ini <a href="' . base_url() . 
                '/authF/ubahpassword?email=' . $this->request->getPost('email') . '&token=' . urlencode($token) . '">Ubah Password</a></p>');

            if (!$this->email->send()) {
                return false;
            }
        }

        return true;
    }

    // Function to verify user
    public function verify()
    {
        $model = new M_frontend();
        $modelt = new M_token();

        $email = $this->request->getGet('email');
        $token = $this->request->getGet('token');

        $user = $model->where('email', $email)->first();
        $user_token = $modelt->where('token', $token)->first();

        if ($user && $user_token) {
            $dibuat = $user_token['date_created'];
            $idv = $user_token['id'];
            $ide = $user['id'];

            if (time() - $dibuat < 1800) {
                $model->update($ide, ['status' => 'Nonaktif']);
                $modelt->delete($idv);
                session()->setFlashdata('icon', 'success');
                session()->setFlashdata('title', 'Selamat!');
                session()->setFlashdata('text', 'Akunmu telah berhasil teraktivasi. Tunggu petugas mengaktifkan akunmu!');
                return redirect()->to('/login');
            }

            $model->delete($ide);
            $modelt->delete($idv);
            session()->setFlashdata('icon', 'error');
            session()->setFlashdata('title', 'Aktivasi akun gagal!');
            session()->setFlashdata('text', 'Token kadaluarsa. Silahkan daftar ulang!');
            return redirect()->to('/login');
        }

        session()->setFlashdata('icon', 'error');
        session()->setFlashdata('title', 'Aktivasi akun gagal!');
        session()->setFlashdata('text', 'Token tidak valid');
        return redirect()->to('/login');
    }

    // Function to reset password
    public function lupapassword()
    {
        $model = new M_frontend();
        $modelt = new M_token();
        $validate = \Config\Services::validation();

        $val = $this->validate([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email belum diisi',
                    'valid_email' => 'Email tidak valid!'
                ]
            ]
        ]);

        if (!$val) {
            session()->setFlashdata('errors', $validate->getErrors());
            return redirect()->to('/forgot-password')->withInput();
        }

        $email = $this->request->getPost('email');
        $user = $model->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('email', '<p class="text-danger text-small">*Email belum terdaftar</p>');
            return redirect()->to('/forgot-password');
        }

        $token = base64_encode(random_bytes(32));
        $user_token = [
            'email' => $email,
            'token' => $token,
            'date_created' => time()
        ];

        $modelt->insert($user_token);
        $this->_sendEmail($token, 'password');

        session()->setFlashdata('icon', 'success');
        session()->setFlashdata('title', 'Permintaan Ganti Password!');
        session()->setFlashdata('text', 'Silahkan cek email untuk reset password!');
        return redirect()->to('/forgot-password');
    }

    // Function to change password
    public function ubahpassword()
    {
        $model = new M_frontend();
        $modelt = new M_token();
        $email = $this->request->getGet('email');
        $token = $this->request->getGet('token');

        $user = $model->where('email', $email)->first();
        $user_token = $modelt->where('token', $token)->first();

        if ($user && $user_token) {
            if (time() - $user_token['date_created'] < 1800) {
                session()->set('reset_email', $email);
                return redirect()->to('/ganti-password');
            }

            $modelt->delete($user_token['id']);
            session()->setFlashdata('icon', 'error');
            session()->setFlashdata('title', 'Gagal ganti password!');
            session()->setFlashdata('text', 'Token kadaluarsa. Silahkan buat ulang!');
            return redirect()->to('/forgot-password');
        }

        session()->setFlashdata('icon', 'error');
        session()->setFlashdata('title', 'Gagal ganti password!');
        session()->setFlashdata('text', 'Token tidak valid');
        return redirect()->to('/forgot-password');
    }

    // Function to change password (Step 2)
    public function gantiPassword()
    {
        $model = new M_frontend();
        $validate = \Config\Services::validation();

        $val = $this->validate([
            'password1' => [
                'rules' => 'required|min_length[8]|max_length[12]',
                'errors' => [
                    'required' => 'Password belum diisi',
                    'min_length' => 'Password terlalu pendek',
                    'max_length' => 'Password terlalu panjang'
                ]
            ],
            'password2' => [
                'rules' => 'required|matches[password1]',
                'errors' => [
                    'required' => 'Konfirmasi password belum diisi',
                    'matches' => 'Konfirmasi password tidak sesuai'
                ]
            ],
        ]);

        if (!$val) {
            session()->setFlashdata('errors', $validate->getErrors());
            return redirect()->to('/ganti-password')->withInput();
        }

        $password = password_hash($this->request->getPost('password1'), PASSWORD_DEFAULT);
        $email = session()->get('reset_email');

        $model->where('email', $email)->set(['password' => $password])->update();
        session()->remove('reset_email');
        session()->setFlashdata('icon', 'success');
        session()->setFlashdata('title', 'Password berhasil diganti');
        session()->setFlashdata('text', 'Silahkan login dengan password baru!');
        return redirect()->to('/login');
    }

    // Function to log out a user
    public function logout()
    {
        session()->remove(['login', 'status', 'nama', 'level']);
        session()->setFlashdata('pesanLogout', '<div class="alert alert-primary">Kamu berhasil Logout!</div>');
        return redirect()->to('/login');
    }
}