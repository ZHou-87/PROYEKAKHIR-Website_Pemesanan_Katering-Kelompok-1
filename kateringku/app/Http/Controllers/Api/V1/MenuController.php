<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\HandlesMenuImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class MenuController extends Controller
{
    use HandlesMenuImage;

    public function index(Request $request): AnonymousResourceCollection
    {
        $category = $request->query('category');
        $search = trim((string) $request->query('search', ''));

        $allowedSorts = ['name', 'category', 'price', 'min_order', 'created_at'];
        $sort = in_array($request->query('sort'), $allowedSorts, true)
            ? $request->query('sort')
            : 'name';
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $menus = Menu::query()
            ->withCount('orderItems')
            ->when(
                $request->boolean('active_only'),
                fn ($query) => $query->where('is_active', true)
            )
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

        return MenuResource::collection($menus);
    }

    public function show(Menu $menu): MenuResource
    {
        $menu->loadCount('orderItems');

        return new MenuResource($menu);
    }

    public function store(StoreMenuRequest $request): JsonResponse
    {
        Gate::authorize('create', Menu::class);

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleMenuImageUpload($request);
        }

        $menu = Menu::create($data);
        $menu->loadCount('orderItems');

        return (new MenuResource($menu))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateMenuRequest $request, Menu $menu): MenuResource
    {
        Gate::authorize('update', $menu);

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleMenuImageUpload($request, $menu->image);
        } else {
            unset($data['image']);
        }

        $menu->update($data);
        $menu->loadCount('orderItems');

        return new MenuResource($menu);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        Gate::authorize('delete', $menu);

        $this->deleteMenuImageFromS3($menu->image);
        $menu->delete();

        return response()->json([
            'message' => 'Menu berhasil dihapus.',
        ]);
    }
}
