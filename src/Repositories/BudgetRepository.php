<?php

namespace Helte\HermesSdk\Repositories;

use Illuminate\Database\Eloquent\Model;

class BudgetRepository extends SchemaRepository
{
    public function __construct()
    {
        $this->setUri('api/v1/budgets');
    }

    public function from($uri){
        return $this;
    }
}