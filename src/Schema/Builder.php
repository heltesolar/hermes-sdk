<?php

namespace Helte\HermesSdk\Schema;

use Exception;
use Helte\HermesSdk\Services\HermesService;
use Illuminate\Database\Eloquent\Model;
use Helte\DevTools\Services\Json;
use Illuminate\Support\Collection;

class Builder
{
    protected $user_id;
    protected $user_type;

    protected $base_uri;

    private $relationships;
    private $limit = 10;
    private $page = 1;
    private $filters = [];
    private $sorting = [];

    public function __construct($uri = null)
    {
        $this->setUri($uri);
    }

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

    public function get() : Collection{
        $response = $this->request($this->getUri());

        return collect($response['data']);
    }

    public function find($id) : array{
        $endpoint = $this->getUri()."/$id";
        
        $response = $this->request($endpoint);

        return $response;
    }

    public function with($relationship) : Builder{
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

    public function limit(int $limit) : Builder{
        $this->limit = $limit;

        return $this;
    }

    public function page(int $page) : Builder{
        $this->page = $page;
        
        return $this;
    }

    public function of(Model $user) : Builder{
        $this->user_type = $user->getMorphClass();
        $this->user_id = $user->id;

        return $this;
    }

    public function from($uri){
        $this->setUri($uri);

        return $this;
    }

    public function where(string $column, $operator = null, $value = null) : Builder{
        //Operação de igualdade básica
        if(is_null($value)){
            $this->filters[] = [
                "operation" => "where_basic",
                "parameters" => [$column, $operator]
            ];
        }else{
            $this->filters[] = [
                "operation" => "where_operator",
                "parameters" => [$column, $operator,$value]
            ];
        }

        return $this;
    }

    public function whereIn(string $column, array $array) : Builder{
        $this->filters[] = [
            "operation" => "where_in",
            "parameters" => [$column, $array]
        ];
        
        return $this;
    }

    public function whereBetween(string $column, $start, $end) : Builder{
        $this->filters[] = [
            "operation" => "where_between",
            "parameters" => [$column, $start, $end]
        ];

        return $this;
    }

    public function whereNull(string $column) : Builder{
        $this->filters[] = [
            "operation" => "where_null",
            "parameters" => [$column, true]
        ];

        return $this;
    }

    public function whereNotNull(string $column) : Builder{
        $this->filters[] = [
            "operation" => "where_null",
            "parameters" => [$column, false]
        ];

        return $this;
    }

    public function orderBy($column, $order='asc') : Builder{
        $this->sorting = [
            'column' => is_array($column) ? implode(',',$column) : $column,
            'order' => $order
        ];
        
        return $this;
    }

    protected function setUri($uri) : Builder{
        $this->base_uri = $uri;
        
        return $this;
    }

    public function getUri() {
        return $this->base_uri;
    }

    protected function getHeaders() : array{
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

    protected function buildParams() : array{
        $params = [
            'perpage' => $this->limit,
            'page' => $this->page,
            'include' => $this->relationships
        ];

        $filters = $this->renderFilters();
        $sorting = $this->renderSorting();

        $params = array_merge($params, $filters, $sorting);

        return $params;
    }

    private function renderSorting() : array{
        $params = [];

        if($this->sorting){
            $params['f_params'] = [
                'orderBy' => [
                    'field' => $this->sorting['column'],
                    'type' => $this->sorting['order']
                ]
            ];
        }

        return $params;
    }

    private function renderFilters() : array{
        $params = [];

        if($this->filters){
            foreach($this->filters as $f){
                $type = $f['operation'];
                $filter = $f['parameters'];
                switch($type){
                    case "where_basic":
                        $params[$filter[0]] = $filter[1];
                        break;
                    case "where_operator":
                        $params[$filter[0]] = [
                            "operator" => $filter[1],
                            "value" => $filter[2]
                        ];
                        break;
                    case "where_in":
                        $params[$filter[0]] = $filter[1];
                        break;
                    case "where_between":
                        $params[$filter[0]] = [
                            "start" => $filter[1],
                            "end" => $filter[2]
                        ];
                        break;
                    case "where_null":
                        $params[$filter[0]] = [
                            "null" => $filter[1]
                        ];
                        break;
                }
            }
        }

        return $params;
    }
}