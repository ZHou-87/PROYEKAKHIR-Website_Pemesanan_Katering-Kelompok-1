<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman beranda publik dengan daftar menu aktif.
     */
    public function index(): View
    {
        $activeMenus = Menu::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $makananMenus = $activeMenus->where('category', 'makanan')->values();
        $minumanMenus = $activeMenus->where('category', 'minuman')->values();

        return view('welcome', compact('makananMenus', 'minumanMenus'));
    }
}
