<?php

namespace Helte\HermesSdk\Services;

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
}