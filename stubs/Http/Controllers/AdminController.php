<?php

namespace App\Http\Controllers;

use App\Assets\Utils;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        return view('appbuilder', [
            "settings" => [
                "user" => auth()->user()->only(['id', 'name']),
                "permissions" => [...Utils::$branchPermissions, ...Utils::$adminPermissions],
                "roles" => Role::query()->select('id', 'name')->get(),
            ]]);
    }

    public function fileUpload(Request $request)
    {
        try {
            $request->validate([
                "the_file" => [
                    "required",
                    "file"
                ]
            ]);
            return $request->file('the_file')->store('appbuilder_files', 'public');
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
