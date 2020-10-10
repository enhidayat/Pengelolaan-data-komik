<?php

namespace App\Controllers;

use App\Models\KomikModel;



class Komik extends BaseController
{

    protected $komikModel; //agar bisa diakses kelas ini dan turunanya
    public function __construct()
    {
        //untuk akses ke model
        $this->komikModel = new KomikModel(); //agar sekali bikin bisa diakses semua metod(index update dll) #note: properti harus sudah di deklarasikan sebelum constructor

    }

    public function index()
    {
        // $komik      = $this->komikModel->findAll(); //(findAll = mt bawaan untuk menampilkan  semua isi field pd tb)
        $data = [
            'title' => 'Daftar Komik',
            'komik' =>  $this->komikModel->getKomik() //komikModel diambil dr method pd model KomikModel
        ];
        return view('komik/index', $data);
    }

    public function detail($slug)
    {
        $komik = $this->komikModel->getKomik($slug);
        $data = [
            'title' =>  'Detail Kontak',
            'komik' =>  $this->komikModel->getKomik($slug)
        ];

        //jika komik tidak ada pada tabel db
        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik ' . $slug . ' tidak ditemukan');
        }

        return view('komik/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Tambah Data Komik'

        ];
        return view('komik/create', $data);
    }

    public function save()
    {
        //membua String pada url berdasarkan field judul jk ada spasi diganti dg tanda minus dan semua karakter jd lowercase
        $slug   = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug'  => $slug, //nama lain judul yg unik
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul'),
        ]);
        //membuat pesan berhasil .session sementara langsung ilang
        session()->setFlashdata('pesan', 'Data berhasil di tambahkan');
        return redirect()->to('/komik/');
    }
}
