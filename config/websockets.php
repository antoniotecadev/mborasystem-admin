<?php

use BeyondCode\LaravelWebSockets\Dashboard\Http\Middleware\Authorize;

return [

    /*
     * Set a custom dashboard configuration
     */
    'dashboard' => [
        'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001),
    ],

    /*
     * Este pacote vem com multi locação fora da caixa. Aqui você pode
     * configure os diferentes aplicativos que podem usar o servidor webSockets.
     *
     * Opcionalmente, você especifica a capacidade para que você possa limitar o máximo
     * conexões simultâneas para um aplicativo específico.
     *
     * Opcionalmente, você pode desativar eventos de clientes para que os clientes não possam enviar
     * mensagens entre si através dos webSockets.
     */
    'apps' => [
        [
            'id' => env('PUSHER_APP_ID'),
            'name' => env('APP_NAME'),
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'path' => env('PUSHER_APP_PATH'),
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],

    /*
     * Esta classe é responsável por encontrar os aplicativos. O provedor padrão
     * usará os aplicativos definidos neste arquivo de config.
     *
     * Você pode criar um provedor personalizado implementando o
     * Interface 'AppProvider'.
     */
    'app_provider' => BeyondCode\LaravelWebSockets\Apps\ConfigAppProvider::class,

    /*
     * Esta matriz contém os hosts dos quais você deseja permitir solicitações recebidas.
     * Deixe isso vazio se quiser aceitar pedidos de todos os anfitriões.
     */
    'allowed_origins' => [
        //
    ],

    /*
     * O tamanho máximo de solicitação em kilobytes que é permitido para uma solicitação webSocket de entrada.
     */
    'max_request_size_in_kb' => 250,

    /*
     * Este caminho será usado para registrar as rotas necessárias para o pacote.
     */
    'path' => 'laravel-websockets',

    /*
     * Dashboard Routes Middleware
     *
     * Esses utensílios médios serão atribuídos a todas as rotas do painel, dando-lhe
     * a chance de adicionar seu próprio middleware a esta lista ou alterar qualquer um dos
     * o middleware existente. Ou, você pode simplesmente ficar com esta lista.
     */
    'middleware' => [
        'web',
        Authorize::class,
    ],

    'statistics' => [
        /*
         * Este modelo será usado para armazenar as estatísticas do WebSocketsServer.
         * A única exigência é que o modelo se estenda
         * 'WebSocketsStatisticsEntry' fornecido por este pacote.
         */
        'model' => \BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry::class,

        /**
         * O Logger de Estatísticas vai, por padrão, lidar com as estatísticas recebidas, armazená-las
         * e, em seguida, liberá-los no banco de dados em cada intervalo definido abaixo.
         */
        'logger' => BeyondCode\LaravelWebSockets\Statistics\Logger\HttpStatisticsLogger::class,

        /*
         * Aqui você pode especificar o intervalo em segundos em que as estatísticas devem ser registradas.
         */
        'interval_in_seconds' => 60,

        /*
         * Quando o comando limpo é executado, todas as estatísticas registradas mais antigas do que
         * o número de dias especificados aqui será excluído.
         */
        'delete_statistics_older_than_days' => 60,

        /*
         * Use um resolução DNS para fazer as solicitações ao madeireiro de estatísticas
         * padrão é resolver tudo para 127.0.0.1.
         */
        'perform_dns_lookup' => false,
    ],

    /*
     * Defina o contexto SSL opcional para suas conexões WebSocket.
     * Você pode ver todas as opções disponíveis em: http://php.net/manual/en/context.ssl.php
     */
    'ssl' => [
        /*
         * Caminho para o arquivo de certificado local no sistema de arquivos. Deve ser um arquivo codificado PEM que
         * contém seu certificado e chave privada. Ele pode, opcionalmente, conter o
         * cadeia de certificados de emissores. A chave privada também pode ser contida
         * em um arquivo separado especificado por local_pk.
         */
        'local_cert' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT', null),

        /*
         * Caminho para arquivo de chave privada local no sistema de arquivos em caso de arquivos separados para
         * certificado (local_cert) e chave privada.
         */
        'local_pk' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_PK', null),

        /*
         * Senha para o seu arquivo local_cert.
         */
        'passphrase' => env('LARAVEL_WEBSOCKETS_SSL_PASSPHRASE', null),
    ],

    /*
     * Gerente de Canal
     * Esta classe lida com a forma como a persistência do canal é tratada.
     * Por padrão, a persistência é armazenada em uma matriz pelo servidor web em execução.
     * O único requisito é que a classe implemente
     * Interface 'ChannelManager' fornecida por este pacote.
     */
    'channel_manager' => \BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManagers\ArrayChannelManager::class,
];
