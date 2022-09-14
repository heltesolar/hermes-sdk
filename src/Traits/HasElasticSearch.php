<?php

namespace Helte\HermesSdk\Traits;

use App\Integrations\ElasticSearch\Observers\ElasticsearchObserver;
use Helte\HermesSdk\Services\HermesService;

trait HasElasticSearch
{
    public static function bootHasElasticSearch()
    {
        if (config('hermes.elasticsearch.enabled')) {
            static::observe(ElasticSearchObserver::class);
        }
    }

    public function elasticsearchIndex()
    {
        HermesService::dispatchJob('IndexDocument', [
            'private_params' => [
                'index' => $this->getTable(),
                'id' => $this->getKey(),
                'body' => $this->toElasticsearchDocumentArray()
            ],
            'namespace' => "App\Integrations\ElasticSearch\Jobs"
        ]);
    }

    public function elasticsearchDelete()
    {
        HermesService::dispatchJob('DeleteDocument', [
            'private_params' => [
                'index' => $this->getTable(),
                'id' => $this->getKey()
            ],
            'namespace' => "App\Integrations\ElasticSearch\Jobs"
        ]);
    }

    abstract public function toElasticsearchDocumentArray(): array;
}