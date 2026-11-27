<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $search = trim((string) $request->query('search', ''));

        $allowedSorts = ['name', 'price', 'min_order', 'created_at'];
        $sort = in_array($request->query('sort'), $allowedSorts, true)
            ? $request->query('sort')
            : 'name';
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $menus = Menu::query()
            ->where('is_active', true)
            ->when(
                in_array($category, ['makanan', 'minuman'], true),
                fn ($query) => $query->where('category', $category)
            )
            ->when(
                $search !== '',
                fn ($query) => $query->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($search) . '%'])
            )
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('catalog.index', compact('menus', 'category', 'search', 'sort', 'direction'));
    }

    public function order(Menu $menu): RedirectResponse
    {
        return redirect()->to('/customer/orders/create?menu_id=' . $menu->id);
    }
}
