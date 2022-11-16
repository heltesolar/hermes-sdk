<?php

namespace Helte\HermesSdk\Services;

class HermesOnPremiseService extends HermesService
{
    private static function getQueue(){
        return config('hermes.onPremise.queue');
    }
}