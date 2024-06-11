<?php
namespace App\Http\Traits;
use \Illuminate\Database\QueryException;
trait TryDelete
{
    public function tryDelete()
    {
        try{
            $this->forceDelete();
            return true;
        }catch(QueryException $e)
        {
            return false;
        }
    }
}