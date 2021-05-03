<?php
if (!function_exists('successResponse')) {
    function successResponse($args = []): \Illuminate\Http\JsonResponse
    {
        return response()->json(array_merge([
            "message" => "Successfully Done",
            "variant" => "success",
            "title" => "Success"
        ], $args));
    }
}
