# Hermes SDK
Para importar a biblioteca utilize o comando:

    composer require helte/hermes-sdk
Em seguida registre o *Provider* necessário para realizar a instalação do SDK. Para isso adicione o seguinte código em **config/app.php**.

```
'providers'  => [

	// Other Service Providers

	Helte\HermesSdk\Providers\HermesProvider::class,

],
```
Por último, rode o comando para instalar as configurações necessárias para o SDK:

    php artisan hermes:install

Esse comando irá registar o arquivo **hermes.php** na pasta **app/config**. Esse arquivo especifica as configurações necessárias para utilizar os recursos do SDK, portanto, adicione em seu **.env** as seguintes chaves:
```
HERMES_API_URL=
HERMES_QUEUE=
HERMES_ONPREMISE_QUEUE=
HERMES_ELASTICSEARCH_ENABLED=
HERMES_CLIENT_ID=
HERMES_CLIENT_SECRET=
```

## Exemplos
Através dos Repositories presentes no SDK, é possível consultar dados presentes na Hermes sem a necessidade de estruturar uma requisição HTTP para a API. Respositories que derivam do SchemaRepository são compatíveis com o trait HasSchema da Hermes, permitindo fazer consultas utilizando FILTER, SORT e PAGINATE como se fossem métodos do Eloquent.

O exemplo a seguir demonstra uma consulta na Hermes de Budgets pertencentes ao usuário de ID 23219:

    use Helte\HermesSdk\Repositories\BudgetRepository;

    Helte\HermesSdk\Repositories\BudgetRepository::of(User::find(23219))->get();

