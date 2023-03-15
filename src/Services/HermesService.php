<?php

namespace Helte\HermesSdk\Services;

use Helte\DevTools\Services\HttpApiClient;
use Helte\DevTools\Services\SettingService;

class HermesService
{
    /**
     * Dispatch Job
     * 
     * en-us:
     * 
     * This method dispatches a job into Herme's SQS Queue. Use this carefully, as the specified job must exist in the Hermes instance that catches the job.
     * 
     * Side-note: Yes, this is ugly. No, I didn't want to do this, but ,unfortunatelly, this was necessary.
     * 
     * The $options parameters might contain:
     * - namespace (defaul: 'App\Jobs') - This is used to specify the job's class' namespace in case it is needed to be created in runtime
     * - uses (default: []) - This is used to specify the job's class' uses in case it is needed to be created in runtime
     * - public_params (default: []) - This is used to specify the job's class' public properties in case it is needed to be created in runtime
     * - private_params (default: []) - This is used to specify the job's class' private properties in case it is needed to be created in runtime
     * - protected_params (default: []) - This is used to specify the job's class' protected properties in case it is needed to be created in runtime
     * 
     * pt-br:
     * 
     * Esse método despacha um job para a fila SQS da Hermes. Use isso com cuidado, pois o job deve existir na instância da Hermes que captura esse job.
     * 
     * Nota: Sim, isso é feio. Não, eu não queria fazer isso, mas, infelizmente, era necessário.
     * 
     * The $options parameters might contain:
     * - namespace (padrão: 'App\Jobs') - Usado para especificar o namespace do job caso seja necessário que o mesmo seja criado em runtime
     * - uses (padrãot: []) - Usado para especificar os uses do job caso seja necessário que o mesmo seja criado em runtime
     * - public_params (padrão: []) - Usado para especificar as propriedades public do job caso seja necessário que o mesmo seja criado em runtime
     * - private_params (padrão: []) - Usado para especificar as propriedades private do job caso seja necessário que o mesmo seja criado em runtime
     * - protected_params (padrão: []) - Usado para especificar as propriedades protected do job caso seja necessário que o mesmo seja criado em runtime
     * 
     * @param string|object $job - In case this is a string, the fakeObject will be called and an instance of job will be created, and it's class will be
     *  created in runtime in case it doens't exist.
     * @param array $options - Described above
     * 
     * @return mixed - Job's identification. If it's a database job, it'll be an integer which is the job's id in the database, in case it was dispached to
     *  the SQS, it will be a string which is the job's uuid
     */
    public static function dispatchJob($job, array $options = [])
    {
        $default_options = [
            'namespace' => 'App\Jobs',
            'uses' => [],
            'public_params' => [],
            'private_params' => [],
            'protected_params' => []
        ];

        $options = array_merge($default_options, $options);

        extract($options);

        $hermes_job = $job;

        if(is_string($job)){
            $hermes_job = fakeObject($namespace, $job, $uses, $public_params, $private_params, $protected_params);
        }

        $hermes_queue = static::getQueue();

        return HermesQueuer::pushOn($hermes_queue, $hermes_job);
    }

    protected static function getQueue(){
        return config('hermes.queue');
    }

    /**
     * Makes API call to an endpoint on Hermes.
     *
     * @param string $endpoint - Endpoint to be called.
     * @param string $method - Request method (case insensitive)
     * @param array $body - HTTP body
     * @param array $headers - HTTP extra headers (case insensitive)
     * @param array $params - URL query params
     * @param boolean $http_errors - Decides if HTTP exceptions are thrown
     * @param string $body_type - Defines type of body
     * @return Illuminate\Http\Client\Response
     * @throws \GuzzleHttp\Exception\ClientException
     */
    public function request($endpoint, $method = 'GET', $body = [], $headers = [], $params = [], $http_errors = true, $body_type = 'json') {
        $token = $this->handleHermesToken();
        
        $hermes_headers = [
            'Authorization' => "Bearer $token",
        ];

        $headers = array_merge($hermes_headers, $headers ?? []);

        $hermes_api_url = config('hermes.url');
        $url = "$hermes_api_url/api/v1/$endpoint";

        return HttpApiClient::call($url, $method, $body, $headers, $params, $http_errors, $body_type);
    }

    protected function handleHermesToken($force_refresh = false)
    {
        $token_validity = \Carbon\Carbon::parse(SettingService::getSetting(config('hermes.authentication.token_settings.token_until_index')));

        /** Verifica se o token ainda é valido, senão atualiza o mesmo */
        if ($force_refresh || 
                                \Carbon\Carbon::now()->diffInSeconds($token_validity, false) <= 60 || 
                                is_null(SettingService::getSetting(config('hermes.authentication.token_settings.token_until_index'))) || 
                                is_null(SettingService::getSetting(config('hermes.authentication.token_settings.token_index')))) {

            $hermes_api_url = config('hermes.url');

            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            $body = [
                "grant_type" => "client_credentials",
                "client_id" => config('hermes.authentication.client_credentials.client_id'),
                "client_secret" => config('hermes.authentication.client_credentials.client_secret'),
                "scope" => "*"
            ];

            $endpoint = "$hermes_api_url/oauth/token";

            $response_data = HttpApiClient::call($endpoint, "post", $body, $headers);

            $token = $response_data['access_token'];

            SettingService::setSetting(config('hermes.authentication.token_settings.token_index'), $token);
            $until = \Carbon\Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
            SettingService::setSetting(config('hermes.authentication.token_settings.token_until_index'), $until);

            return $token;
        }else{
            return SettingService::getSetting(config('hermes.authentication.token_settings.token_index'));
        }
    }
}