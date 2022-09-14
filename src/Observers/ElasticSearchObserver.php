<?php

namespace Helte\HermesSdk\Observers;

class ElasticSearchObserver
{
    public function __construct()
    {
        // ...
    }

    public function saved($model)
    {
        $model->elasticSearchIndex();
    }

    public function deleted($model)
    {
        $model->elasticSearchDelete();
    }
}