<?php

/**
 * Almost every aspect of the client is configurable.
 * Most users will only need to configure a few parameters to suit their needs,
 * but it is possible to completely replace much of the internals if required.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_configuration.html
 */
return [

    /**
     * The most common configuration is telling the client about your cluster:
     * how many nodes, their addresses and ports. If no hosts are specified,
     * the client will attempt to connect to localhost:9200.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_configuration.html#_host_configuration
     */

    'hosts' => env('ELASTICSEARCH_HOSTS', 'localhost:9200'),

    /**
     * When the client runs out of retries, it will throw the last exception that it received.
     * For example, if you have ten alive nodes, and setRetries(5),
     * the client will attempt to execute the command up to five times.
     * If all five nodes result in a connection timeout (for example),
     * the client will throw an OperationTimeoutException.
     * Depending on the Connection Pool being used, these nodes may also be marked dead.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_configuration.html#_set_retries
     */

    'retries' => env('ELASTICSEARCH_RETRIES', 0),

    /**
     * Elasticsearch-PHP supports logging, but it is not enabled by default for performance reasons.
     * If you wish to enable logging, you need to select a logging implementation, install it,
     * then enable the logger in the Client. The recommended logger is Monolog,
     * but any logger that implements the PSR / Log interface will work.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_configuration.html#enabling_logger
     */

    'log' => env('ELASTICSEARCH_LOG', storage_path('logs/ductible.log')),

    /**
     * Elasticsearch-PHP uses an interchangeable HTTP transport layer called RingPHP.
     * This allows the client to construct a generic HTTP request,
     * then pass it to the transport layer to execute.
     * The actual execution details are hidden from the client and modular,
     * so that you can choose from several HTTP handlers depending on your needs.
     *
     * Available handler: 'default', 'single', 'multi', 'AnyCustomHandler'
     *
     * @see https://guzzle.readthedocs.io/en/latest/handlers.html
     */

    'handler' => 'default',

    /**
     * The connection pool is an object inside the client that is responsible for maintaining the current list of nodes.
     * Theoretically, nodes are either dead or alive.
     *
     * Available pool:
     *  - Elasticsearch\ConnectionPool\StaticNoPingConnectionPool
     *  - Elasticsearch\ConnectionPool\StaticConnectionPool
     *  - Elasticsearch\ConnectionPool\SimpleConnectionPool
     *  - Elasticsearch\ConnectionPool\SniffingConnectionPool
     *  - MyCustomConnectionPool
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_connection_pool.html
     */

    'pool' => Elasticsearch\ConnectionPool\StaticNoPingConnectionPool::class,

    /**
     * The connection pool manages the connections to your cluster,
     * but the Selector is the logic that decides which connection should be used for the next API request.
     * There are several selectors that you can choose from.
     *
     * Available connection selector:
     *  - Elasticsearch\ConnectionPool\Selectors\RoundRobinSelector
     *  - Elasticsearch\ConnectionPool\Selectors\StickyRoundRobinSelector
     *  - Elasticsearch\ConnectionPool\Selectors\RandomSelector
     *  - MyCustomSelector
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_selectors.html
     */

    'selector' => Elasticsearch\ConnectionPool\Selectors\RoundRobinSelector::class,

    /**
     * Requests are given to the client in the form of associative arrays,
     * but Elasticsearch expects JSON. The Serializer’s job is to serialize PHP objects into JSON.
     * It also de-serializes JSON back into PHP arrays. This seems trivial,
     * but there are a few edgecases which make it useful for the serializer to remain modular.
     *
     * Available serializer:
     *  - Elasticsearch\Serializers\SmartSerializer
     *  - Elasticsearch\Serializers\ArrayToJSONSerializer
     *  - Elasticsearch\Serializers\EverythingToJSONSerializer
     *  - MyCustomSerializer
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_serializers.html
     */

    'serializer' => Elasticsearch\Serializers\SmartSerializer::class,

    /**
     * There are several configurations that can be set on a per-request basis,
     * rather than at a connection - or client-level.
     * These are specified as part of the request associative array.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_per_request_configuration.html
     */

    'client' => [

        /**
         * The library attempts to throw exceptions for common problems.
         * These exceptions match the HTTP response code provided by Elasticsearch.
         * For example, attempting to GET a nonexistent document will throw a MissingDocument404Exception.
         *
         * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_per_request_configuration.html#_ignoring_exceptions
         */
        'ignores' => [],

        /**
         * By default, the client will only return the response body.
         * If you require more information (e.g. stats about the transfer, headers, status codes, etc),
         * you can tell the client to return a more verbose response.
         * This is enabled via the verbose parameter in the client options.
         *
         * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_per_request_configuration.html#_increasing_the_verbosity_of_responses
         */

        'verbose' => env('ELASTICSEARCH_DEBUG', false),

    ],

];
