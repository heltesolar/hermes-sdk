<?php

namespace Helte\HermesSdk\Integrations;

use Helte\HermesSdk\Services\HermesService;

class TakeBlipService
{
    public static function sendWhatsAppMessage(int $client_id, array $message_params, $phone, $template, $force = false){
        HermesService::dispatchJob(
            'SendTakeBlipMessage',
            [
                'namespace' => 'App\Integrations\TakeBlip\Jobs',
                'private_params' => ['client_id' => $client_id, 'message_params' => $message_params, 'phone' => $phone, 'template' => $template, 'force' => $force]
            ]
        );
    }

    public static function sendWhatsAppMessageWithPostState(int $client_id, array $message_params, $phone, $template, $state, $flow, $force = false){
        HermesService::dispatchJob(
            'SendTakeBlipMessage',
            [
                'namespace' => 'App\Integrations\TakeBlip\Jobs',
                'private_params' => 
                    ['client_id' => $client_id, 
                    'message_params' => $message_params, 
                    'phone' => $phone, 
                    'template' => $template, 
                    'force' => $force, 
                    'transition' => [
                        'moment' => 'post',
                        'state' => $state,
                        'flow' => $flow
                    ]
                ]
            ]
        );
    }
}