<?php

namespace App\Http\Controllers;

use App\Assets\Datatable;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public static function routes()
    {
        Route::prefix('/users')
            ->name('users.')
            ->group(function () {
                Route::post('/', [self::class, 'index'])->name('list');
                Route::post('/store', [self::class, 'store'])->name('store');
            });
    }


    public function index(Request $request)
    {
        return Datatable::render(
            User::query()
                ->select([
                    'id',
                    'name',
                    'role_id',
                    'email',
                    'created_at',
                    'updated_at'
                ])
                ->when($request->post('filter'), function ($users) use ($request) {
                    $users->where('id', '=', $request->post('filter'))
                        ->orWhere('name', 'LIKE', "%" . $request->post('filter') . "%")
                        ->orWhere('email', 'LIKE', "%" . $request->post('filter') . "%");
                })
                ->with([
                    'role:id,name'
                ])
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        \DB::beginTransaction();
        try {
            $request->validate([
                "id" => [
                    "numeric"
                ],
                "name" => [
                    "required",
                    "string"
                ],
                "email" => [
                    "required",
                    "email"
                ],
                "password" => [
                    "min:6"
                ],
                "role_id" => "required|numeric",
                "branch_id" => "required|numeric"
            ]);
            $item = User::query()->findOrNew($request->post('id'));
            $item->forceFill($request->only(['name', 'email', 'branch_id', 'role_id']));
            if ($request->has('password') && $request->post('password')) {
                $item->password = Hash::make($request->post('password'));
            }
            $item->saveOrFail();
            \DB::commit();
            return response()->json([
                "title" => "Success",
                "variant" => "success",
                "message" => "Successfully Done",
            ]);

        } catch (\Throwable $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }
}
