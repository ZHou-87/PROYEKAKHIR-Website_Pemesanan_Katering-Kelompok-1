<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesMenuImage;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MenuController extends Controller
{
    use HandlesMenuImage;

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Menu::class);

        $category = $request->query('category');
        $search = trim((string) $request->query('search', ''));

        $allowedSorts = ['name', 'category', 'price', 'min_order', 'created_at'];
        $sort = in_array($request->query('sort'), $allowedSorts, true)
            ? $request->query('sort')
            : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $menus = Menu::query()
            ->withCount('orderItems')
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

        return view('menus.index', compact('menus', 'category', 'search', 'sort', 'direction'));
    }

    public function create(): View
    {
        Gate::authorize('create', Menu::class);

        return view('menus.create');
    }

    public function store(StoreMenuRequest $request): RedirectResponse
    {
        Gate::authorize('create', Menu::class);

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleMenuImageUpload($request);
        }

        Menu::create($data);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        Gate::authorize('update', $menu);

        return view('menus.edit', compact('menu'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        Gate::authorize('update', $menu);

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleMenuImageUpload($request, $menu->image);
        } else {
            unset($data['image']);
        }

        $menu->update($data);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        Gate::authorize('delete', $menu);

        $this->deleteMenuImageFromS3($menu->image);
        $menu->delete();

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    public function apiClient(): View
    {
        Gate::authorize('viewAny', Menu::class);

        return view('menus.api-client');
    }
}
