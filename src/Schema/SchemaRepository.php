<?php

namespace Helte\HermesSdk\Schema;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SchemaRepository
{
    protected $base_uri;

    public static function query($uri = null) : Builder{
        if($uri){
            return new Builder($uri);
        }
        return new Builder(self::$base_uri);
    }

    public static function get() : Collection{
        return self::query()->get();
    }

    public static function find($id) : array{
        return self::query()->find($id);
    }

    public static function with($relationship) : Builder{
        return self::query()->with($relationship);
    }

    public static function limit(int $limit) : Builder{
        return self::query()->limit($limit);
    }

    public static function page(int $page) : Builder{
        return self::query()->page($page);
    }

    public static function of(Model $user) : Builder{
        return self::query()->of($user);
    }

    public static function from($uri) : Builder{
        return self::query()->of($uri);
    }

    public static function where(string $column, $operator = null, $value = null): Builder{
        return self::query()->where($column, $operator, $value);
    }

    public static function whereIn(string $column, array $array) : Builder{
        return self::query()->whereIn($column, $array);
    }

    public static function whereBetween(string $column, $start, $end) : Builder{
        return self::query()->whereBetween($column, $start, $end);
    }

    public static function whereNull(string $column) : Builder{
        return self::query()->whereNull($column);
    }

    public static function whereNotNull(string $column) : Builder{
        return self::query()->whereNotNull($column);
    }

    public function setUri($uri){
        $this->base_uri = $uri;
        
        return $this;
    }

    public function getUri(){
        return $this->base_uri;
    }
}