<?php

namespace App\Http\Controllers;

use App\Assets\Datatable;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class RoleController extends Controller
{
    public static function routes()
    {
        Route::prefix('/roles')
            ->name('roles.')
            ->group(function () {
                Route::post('/', [self::class, 'index'])->name('list');
                Route::post('/store', [self::class, 'store'])->name('store');
            });
    }


    public function index(Request $request): LengthAwarePaginator
    {
        $items = Role::query()
            ->select([
                'id',
                'name',
                'permissions'
            ]);
        if ($request->post('filter')) {
            $items
                ->where('id', '=', $request->post('filter'))
                ->orWhere('name', 'LIKE', "%" . $request->post('filter') . "%");
        }
        return Datatable::render($items);
    }



    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $item = Role::query()
                ->findOrNew($request->post('id'));
            $item->name = $request->post("name");
            $item->permissions = $request->post("permissions");
            $item->saveOrFail();

            DB::commit();
            return successResponse();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
