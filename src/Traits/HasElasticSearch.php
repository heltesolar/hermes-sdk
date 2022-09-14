<?php

namespace Helte\HermesSdk\Services\Traits;

use App\Integrations\ElasticSearch\Jobs\DeleteDocument;
use App\Integrations\ElasticSearch\Jobs\IndexDocument;
use App\Integrations\ElasticSearch\Observers\ElasticsearchObserver;

trait HasElasticSearch
{
    public static function bootHasElasticSearch()
    {
        if (config('services.elasticsearch.enabled')) {
            static::observe(ElasticSearchObserver::class);
        }
    }

    public function elasticsearchIndex()
    {
        IndexDocument::dispatch($this->getTable(),$this->getKey(),$this->toElasticsearchDocumentArray());
    }

    public function elasticsearchDelete()
    {
        DeleteDocument::dispatch($this->getTable(),$this->getKey());
    }

    abstract public function toElasticsearchDocumentArray(): array;
}