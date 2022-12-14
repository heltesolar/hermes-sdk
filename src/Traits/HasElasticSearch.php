<?php

namespace Helte\HermesSdk\Traits;

use Helte\HermesSdk\Observers\ElasticSearchObserver;
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
        if (property_exists(self::class, 'elasticSoftDeletes') && self::$elasticSoftDeletes) {
            $body = $this->toElasticsearchDocumentArray();
            $body['deleted_at'] = now()->toIso8601String();

            HermesService::dispatchJob('IndexDocument', [
                'private_params' => [
                    'index' => $this->getTable(),
                    'id' => $this->getKey(),
                    'body' => $body
                ],
                'namespace' => "App\Integrations\ElasticSearch\Jobs"
            ]);
        }else{
            HermesService::dispatchJob('DeleteDocument', [
                'private_params' => [
                    'index' => $this->getTable(),
                    'id' => $this->getKey()
                ],
                'namespace' => "App\Integrations\ElasticSearch\Jobs"
            ]);
        }
    }

    abstract public function toElasticsearchDocumentArray(): array;
}