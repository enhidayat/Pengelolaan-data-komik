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
        // session(); = menerima inputan n validation dr save()
        // session(); //agar tidak lupa ditaruh di Basecontroller biar otomatis load
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
            //sampe sini validation sudah smpe di halaman create

        ];
        return view('komik/create', $data);
    }

    public function save()
    {
        //membuat validasi
        if (!$this->validate([
            // 'judul'     => 'required|is_unique[komik.judul]',
            'judul'     => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required'  => '{field} komik harus diisi',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]

            ],

            'penulis'   => 'required',
            'penerbit'  =>  'required',
            'sampul'    =>  'required'
        ])) {
            //membuat pesan kesalahan
            $validation = \Config\Services::validation();
            $data['validation'] = $validation;
            // return view('/komik/create', $data);

            //cara 1 
            // withInput = utk mengirimkan semua input ke create() dan jg memiliki old method(agar form tidak otomatis terhapus jk salah inputt ) dan akan disimpan pd session
            // with = akan mengirimkan validasi ke  create()
            return redirect()->to('/komik/create')->withInput()->with('validation', $validation);

            //cara 2 
            // $data['validation'] = $validation;
            // return view('/komik/create', $data);
        }



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
