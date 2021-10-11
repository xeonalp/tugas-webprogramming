<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mobil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
    }
    //manajemen Mobil
    public function index()
    {
        $data['judul'] = 'Data Mobil';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['mobil'] = $this->ModelMobil->tampil()->result_array();
        $data['kategori'] = $this->ModelMobil->getKategori()->result_array();
        $this->form_validation->set_rules('kode_mobil', 'Nama Mobil', 'required|min_length[3]', [
            'required' => 'Nama Mobil harus diisi',
            'min_length' => 'Nama Mobil terlalu pendek'
        ]);
        $this->form_validation->set_rules(
            'id_kategori',
            'Kategori',
            'required',
            [
                'required' => 'Nama Kategori harus diisi',
            ]
        );
        $this->form_validation->set_rules(
            'merek',
            'Merek',
            'required|min_length[3]',
            [
                'required' => 'Merek harus diisi',
                'min_length' => 'Merek terlalu pendek'
            ]
        );
        $this->form_validation->set_rules(
            'warna',
            'Warna',
            'required|min_length[3]',
            [
                'required' => 'Warna harus diisi',
                'min_length' => 'Warna terlalu pendek'
            ]
        );
        $this->form_validation->set_rules('tahun_pembuatan', 'Tahun Pembuatan', 'required|min_length[3]|max_length[4]|numeric', [
            'required' => 'Tahun pembuatan harus diisi',
            'min_length' => 'Tahun pembuatan terlalu pendek',
            'max_length' => 'Tahun pembuatan terlalu panjang',
            'numeric' => 'Hanya boleh diisi angka'
        ]);
        $this->form_validation->set_rules(
            'plat_no',
            'Plat Nomor',
            'required|min_length[3]',
            [
                'required' => 'Plat Nomor harus diisi',
                'min_length' => 'Plat Nomor terlalu pendek'
            ]
        );
        $this->form_validation->set_rules(
            'stok',
            'Stok',
            'required|numeric',
            [
                'required' => 'Stok harus diisi',
                'numeric' => 'Yang anda masukan bukan angka'
            ]
        );
        //konfigurasi sebelum gambar diupload
        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1000';
        $config['file_name'] = 'img' . time();
        $this->load->library('upload', $config);
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('mobil/index', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data();
                $gambar = $image['file_name'];
            } else {
                $gambar = '';
            }
            $data = [
                'kode_mobil' => $this->input->post(
                    'kode_mobil',
                    true
                ),
                'id_kategori' => $this->input->post(
                    'id_kategori',
                    true
                ),
                'merek' => $this->input->post('merek', true),
                'warna' => $this->input->post('warna', true),
                'tahun_pembuatan' => $this->input->post('tahun_pembuatan', true),
                'plat_no' => $this->input->post('plat_no', true),
                'stok' => $this->input->post('stok', true),
                'dipinjam' => 0,
                'dibooking' => 0,
                'image' => $gambar
            ];
            $this->ModelMobil->simpanMobil($data);
            redirect('mobil');
        }
    }
    public function ubahMobil()
    {
        $data['judul'] = 'Ubah Data Mobil';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['admin'] = $this->ModelMobil->mobilWhere(['id' =>
        $this->uri->segment(3)])->result_array();
        $kategori = $this->ModelMobil->joinKategoriMobil(['mobil.id'
        => $this->uri->segment(3)])->result_array();
        foreach ($kategori as $k) {
            $data['id'] = $k['id_kategori'];
            $data['k'] = $k['kategori'];
        }
        $data['kategori'] = $this->ModelMobil->getKategori()->result_array();
        $this->form_validation->set_rules('kode_mobil', 'Nama 
Mobil', 'required|min_length[3]', [
            'required' => 'Nama Mobil harus diisi',
            'min_length' => 'Nama Mobil terlalu pendek'
        ]);
        $this->form_validation->set_rules(
            'id_kategori',
            'Kategori',
            'required',
            [
                'required' => 'Nama kategori harus diisi',
            ]
        );
        $this->form_validation->set_rules(
            'merek',
            'Merek',
            'required|min_length[3]',
            [
                'required' => 'Merek harus diisi',
                'min_length' => 'Merek terlalu pendek'
            ]
        );
        $this->form_validation->set_rules(
            'warna',
            'Warna',
            'required|min_length[3]',
            [
                'required' => 'Warna harus diisi',
                'min_length' => 'Warna terlalu pendek'
            ]
        );
        $this->form_validation->set_rules('tahun_pembuatan', 'Tahun 
 Pembuatan', 'required|min_length[3]|max_length[4]|numeric', [
            'required' => 'Tahun pembuatan harus diisi',
            'min_length' => 'Tahun pembuatan terlalu pendek',
            'max_length' => 'Tahun pembuatan terlalu panjang',
            'numeric' => 'Hanya boleh diisi angka'
        ]);
        $this->form_validation->set_rules(
            'plat_no',
            'Plat Nomor',
            'required|min_length[3]',
            [
                'required' => 'Plat Nomor harus diisi',
                'min_length' => 'Plat Nomor terlalu pendek'
            ]
        );
        $this->form_validation->set_rules(
            'stok',
            'Stok',
            'required|numeric',
            [
                'required' => 'Stok harus diisi',
                'numeric' => 'Yang anda masukan bukan angka'
            ]
        );
        //konfigurasi sebelum gambar diupload
        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1000';
        $config['file_name'] = 'img' . time();
        //memuat atau memanggil library upload
        $this->load->library('upload', $config);
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('mobil/ubah_mobil', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data();
                unlink('assets/img/upload/' . $this->input->post('old_pict', TRUE));
                $gambar = $image['file_name'];
            } else {
                $gambar = $this->input->post('old_pict', TRUE);
            }
            $data = [
                'kode_mobil' => $this->input->post('kode_mobil', true),
                'id_kategori' => $this->input->post('id_kategori', true),
                'merek' => $this->input->post('merek', true),
                'warna' => $this->input->post('warna', true),
                'tahun_pembuatan' => $this->input->post('tahun_pembuatan', true),
                'plat_no' => $this->input->post('plat_no', true),
                'stok' => $this->input->post('stok', true),
                'image' => $gambar
            ];
            $this->ModelMobil->updateMobil($data, ['id' => $this->input->post('id')]);
            redirect('mobil');
        }
    }
    public function hapusMobil()
    {
        $where = ['id' => $this->uri->segment(3)];
        $this->ModelMobil->hapusMobil($where);
        redirect('mobil');
    }
}
