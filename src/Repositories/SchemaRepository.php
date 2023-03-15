<?php

namespace Helte\HermesSdk\Repositories;

use Exception;
use Helte\HermesSdk\Services\HermesService;
use Illuminate\Database\Eloquent\Model;
use Helte\DevTools\Services\Json;

class SchemaRepository
{
    protected $user_id;
    protected $user_type;

    protected $base_uri;

    private $relationships;
    private $limit = 10;
    private $page = 1;

    protected function request($endpoint, $method = 'GET', $body = []){
        $hermes_service = new HermesService();

        $response = $hermes_service->request(
            $endpoint,
            $method,
            $body,
            $this->getHeaders(),
            $this->buildParams()
        );

        return Json::decode($response->getBody()->getContents());
    }

    public function get(){
        $response = $this->request($this->getUri());

        return collect($response['data']);
    }

    public function find($id){
        $endpoint = $this->getUri()."/$id";
        
        $response = $this->request($endpoint);

        return $response;
    }

    public function with($relationship){
        if($relationship){
            if(is_array($relationship) ){
                $this->relationships = implode(',',$relationship);
            }else if(is_string($relationship)){
                $this->relationships = $relationship;
            }else{
                throw new Exception('Passed invalid relationship type');
            }
        }
        return $this;
    }

    public function limit(int $limit){
        $this->limit = $limit;

        return $this;
    }

    public function page(int $page){
        $this->page = $page;
        
        return $this;
    }

    public function of(Model $user){
        $this->user_type = $user->getMorphClass();
        $this->user_id = $user->id;

        return $this;
    }

    public function from($uri){
        $this->setUri($uri);

        return $this;
    }

    protected function setUri($uri){
        $this->base_uri = $uri;
        
        return $this;
    }

    public function getUri(){
        return $this->base_uri;
    }

    protected function getHeaders(){
        $headers = [];
        if($this->user_type){
            switch($this->user_type){
                case('App\Models\Client'):
                    $headers['X-Company'] = $this->user_id;
                    break;
                default:
                    throw new Exception('Unsupported user type on schema building');
            }
        }

        return $headers;
    }

    protected function buildParams(){
        $params = [
            'perpage' => $this->limit,
            'page' => $this->page,
            'include' => $this->relationships
        ];

        return $params;
    }
}