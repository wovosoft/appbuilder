<?php


namespace App\Assets;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class Datatable
{
    public static function render(Builder $builder, $options = null): LengthAwarePaginator
    {
        $params = [
            'perPage' => 15,
            'currentPage' => 1,
            'filter' => null,
            'sortBy' => 'id',
            'sortDesc' => false,
            'apiUrl' => null
        ];
        if (!$options) {
            $params = array_merge($params, request()->only([
                'perPage',
                'currentPage',
                'filter',
                'sortBy',
                'sortDesc',
                'apiUrl'
            ]));
        } else {
            $params = $options;
        }
        if ($params['sortBy']) {
            $builder->orderBy($params['sortBy'], $params['sortDesc'] ? 'desc' : 'asc');
        }

        return $builder
            ->paginate($params['perPage']);
    }
}
