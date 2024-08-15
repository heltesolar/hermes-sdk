<?php

namespace Helte\HermesSdk\Traits;

use Helte\HermesSdk\Services\HermesService;
trait ContractsTrait{

  public function syncContacts($content)
  {   
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'syncContacts', 'content' => $content]
    ]);
  }

  public function updateContacts($content)
  {       
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'updateContacts', 'content' => $content]
    ]);
  }
}