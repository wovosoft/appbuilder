<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

trait ControllerTraits
{
    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $item = $this->model::findOrFail($id);
            $item->delete();
            DB::commit();
            return successResponse();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
