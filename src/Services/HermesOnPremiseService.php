<?php

namespace Helte\HermesSdk\Services;

class HermesOnPremiseService extends HermesService
{
    protected static function getQueue(){
        return config('hermes.onPremise.queue');
    }
}