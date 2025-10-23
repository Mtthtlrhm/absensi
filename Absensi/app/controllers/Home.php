<?php

class Home extends Controller
{
    public function index()
    {
        $data['judul'] = 'Login';
        $this->view('templates/header', $data);
        $this->view('login/index'); // <-- Ubah baris ini
        $this->view('templates/footer');
    }
}