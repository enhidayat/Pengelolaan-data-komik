<?php

namespace App\Models; //untuk memastikan kelas model biar gk salah dg kelas model yg lain jk ada(kalo ini dihapus di kontoller gak kelihatan) 

use CodeIgniter\Model; //agar bisa  mewarisi kelas Model 

class KomikModel extends Model
{
    protected $table      = 'komik';
    protected $useTimestamps = true;
    protected $allowedFields = ['judul', 'slug', 'penulis', 'penerbit', 'sampul'];


    //membuat komik methode multifungsi(menampilkan semua data & berdasarkan field id/slug)
    public function getKomik($slug = false)
    {
        if ($slug == false) {
            //kalau slugnya kosong cari semua
            return  $this->findAll();
        }
        //kalau slugnya ada tampilkan detail yg dipilih 
        return $this->where(['slug' => $slug])->first();
    }
}
