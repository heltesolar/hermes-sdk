<?php

namespace Helte\HermesSdk\Repositories;

use Helte\HermesSdk\Schema\Builder;
use Illuminate\Database\Eloquent\Model;
use Helte\HermesSdk\Schema\SchemaRepository;

class BudgetRepository extends SchemaRepository
{
    protected static $base_uri = 'budgets';

    public static function from($uri) : Builder{
        return self::query();
    }
}