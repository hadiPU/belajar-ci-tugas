<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user sudah login
        if (!session()->has('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        // Cek apakah ada pengecekan role
        if ($arguments) {
            $requiredRole = $arguments[0]; // contoh: 'admin'
            $userRole = session('role');

            if ($userRole !== $requiredRole) {
                // Jika bukan role yang diminta, arahkan ke halaman lain
                return redirect()->to(site_url('/'))->with('error', 'Akses ditolak.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi khusus setelah request
    }
}
