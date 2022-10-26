<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;

trait SyncValidator
{

    protected function checkPivotAttribute(Model $model, array $data, string $value)
    {
        if(empty($data)){
            return [];
        }
        return $model->whereIn($value,$data)
            ->pluck($value)->toArray();
    }



}
