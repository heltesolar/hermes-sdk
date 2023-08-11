<?php

namespace Helte\HermesSdk\Traits;

use Helte\HermesSdk\Services\HermesService;
trait DealsTrait{

  public function createDealCRM($content)
  {    
    
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'createDeal', 'content' => $content]
    ]);
  }

  public function deleteDealCRM($content)
  {    
    
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'deleteDeal', 'content' => $content]
    ]);
  }

  public function sendDealCRM($content)
  {    
    
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'sendDeal', 'content' => $content]
    ]);
  }

  public function updateDealCRM($content)
  {    
    
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'updateDeal', 'content' => $content]
    ]);
  }

  public function scheduleDealSubmit($content)
  {    
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'scheduleDealSubmit', 'content' => $content]
    ]);
  }

  public function submitSupportRequest($content)
  {   
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'submitSupportRequest', 'content' => $content]
    ]);
  }

  public function winDeals($content)
  {   
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'winDeal', 'content' => $content]
    ]);
  }

  public function forceUpdateDeal($content)
  {   
    HermesService::dispatchJob('CrmHelteHandleJob', [
      'private_params' => ['actionHash' => 'forceUpdateDeal', 'content' => $content]
    ]);
  }
}