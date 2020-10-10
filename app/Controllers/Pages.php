<?php

namespace App\Controllers;

class Pages extends BaseController
{

    public function index()
    {
        $data = [
            'title' => 'Home | COMICU'
        ];

        return view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About | COMICU'
        ];
        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact',
            'alamat' => [
                [
                    'tipe'  =>  'Rumah',
                    'jalan' =>  'Jl. blokagung No.111',
                    'kota'  =>  'Banyuwangi'
                ],
                [
                    'tipe'  =>  'Kantor',
                    'jalan' =>  'Maju terus',
                    'kota'  =>  'Jember'
                ]
            ]
        ];
        return view('pages/contact', $data);
    }
}
