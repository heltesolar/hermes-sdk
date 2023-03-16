<?php

namespace Helte\HermesSdk\Repositories;

use Helte\HermesSdk\Schema\Builder;
use Helte\HermesSdk\Schema\SchemaRepository;

class OrderRepository extends SchemaRepository
{
    protected static $base_uri = 'orders';

    public static function from($uri) : Builder{
        return self::query();
    }
}