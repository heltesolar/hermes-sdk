<?php

namespace Helte\HermesSdk\Repositories;

use Helte\HermesSdk\Schema\Builder;
use Illuminate\Database\Eloquent\Model;
use Helte\HermesSdk\Schema\SchemaRepository;

class BudgetRepository extends SchemaRepository
{
    protected $base_uri = 'budgets';

    public function __construct()
    {
        $this->setUri('budgets');
    }

    public static function from($uri) : Builder{
        return self::query();
    }
}