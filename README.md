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
HERMES_QUEUE=
HERMES_ELASTICSEARCH_ENABLED=
```