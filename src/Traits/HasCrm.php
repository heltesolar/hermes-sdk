<?php

namespace Helte\HermesSdk\Traits;

use Helte\HermesSdk\Services\HermesService;

trait HasCrm
{
    public function onCrmAction(string $action, array $body){
        HermesService::dispatchJob('CRMJob', [
            'private_params' => [
                'action' => $action,                
                'body' => $body
            ],
        ]);
    }
}