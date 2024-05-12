<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class ApiFilter
{
    // ignore
    protected $request;
    protected $allowParamsFilter = []; // Allowed filter parameters
    protected $allowParamsSort = []; // Allowed sort parameters
    protected $columnMap = []; // Mapping request parameters to database columns

    public function __construct(Request $request, $allowParamsFilter = ['query'], $allowParamsSort = ['sortBy'], $columnMap = [])
    {
        $this->request = $request;
        $this->allowParamsFilter = $allowParamsFilter;
        $this->allowParamsSort = $allowParamsSort;
        $this->columnMap = $columnMap;
    }

    public abstract function apply(Builder $query);

    protected function applyFilters(Builder $query)
    {
        // Apply filter parameters
        foreach ($this->allowParamsFilter as $param) {
            if ($this->request->has($param)) {
                $value = $this->request->input($param);
                $column = $this->getColumn($param);
                $query->where($column, $value);
            }
        }

        // Apply sort parameters
        if ($this->request->has('sortBy')) {
            $sortField = $this->request->input('sortBy');
            if (in_array($sortField, $this->allowParamsSort)) {
                $column = $this->getColumn($sortField);
                $query->orderBy($column);
            }
        }

        return $query;
    }

    protected function getColumn($param)
    {
        return $this->columnMap[$param] ?? $param;
    }
}
