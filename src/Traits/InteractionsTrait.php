<?php

namespace Helte\HermesSdk\Traits;

use Helte\HermesSdk\Services\HermesService;

trait InteractionsTrait{

  public function createInteraction($content)
  {  
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'createInteraction', 'content' => $content]
    ]);
  }

  public function unBlockInteraction($content)
  {  
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'unBlockInteraction', 'content' => $content]
    ]);
  }
}