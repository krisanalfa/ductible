<?php

namespace Zeek\Ductible;

use Elasticsearch\Client;
use Illuminate\Support\Collection;
use Zeek\Ductible\Contracts\Searchable;
use Illuminate\Contracts\Config\Repository;

class Ductible
{
    /**
     * Elasticsearch low level client.
     *
     * @var \Elasticsearch\Client
     */
    protected $client;

    /**
     * Application configuration repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Class constructor.
     *
     * @param \Elasticsearch\Client                   $client
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct(Client $client, Repository $config)
    {
        $this->client = $client;

        $this->config = $config;
    }

    /**
     * Store a new index.
     *
     * @param array $params
     *
     * @return array
     */
    public function index(array $params)
    {
        return $this->client->index($this->prepareParams($params));
    }

    /**
     * Get a document index.
     *
     * @param array $params
     *
     * @return array
     */
    public function get(array $params)
    {
        return $this->client->get($this->prepareParams($params));
    }

    /**
     * Update a document index.
     *
     * @param array $params
     *
     * @return array
     */
    public function update(array $params)
    {
        return $this->client->update($this->prepareParams($params));
    }

    /**
     * Delete an index.
     *
     * @param array $params
     *
     * @return array
     */
    public function delete(array $params)
    {
        return $this->client->delete($this->prepareParams($params));
    }

    /**
     * Search a query.
     *
     * @param array $params
     *
     * @return array
     */
    public function search(array $params)
    {
        return $this->client->search($this->prepareParams($params));
    }

    /**
     * Insert index with bulk strategy.
     *
     * @param array $params
     *
     * @return array
     */
    public function bulk(array $params)
    {
        return $this->client->bulk($params);
    }

    /**
     * Get low level Elasticsearch client.
     *
     * @return \Elasticsearch\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sync an object that implementing Searchable interface to Elasticsearch.
     *
     * @param \Zeek\Ductible\Contracts\Searchable $model
     *
     * @return array
     */
    public function sync(Searchable $model)
    {
        return $this->index([
            'index' => $model->getIndexableName(),
            'type' => $model->getIndexableType(),
            'id' => $model->getIndexableId(),
            'body' => $model->getIndexableBody(),
        ]);
    }

    /**
     * Same as sync, but with bulk strategy.
     *
     * @param \Illuminate\Support\Collection $models
     *
     * @return array
     */
    public function syncMany(Collection $models)
    {
        $params = ['body' => []];

        foreach ($models as $model) {
            $params['body'][] = [
                'index' => [
                    '_index' => $model->getIndexableName(),
                    '_type' => $model->getIndexableType(),
                    '_id' => $model->getIndexableId(),
                ],
            ];

            $params['body'][] = $model->getIndexableBody();
        }

        return $this->bulk($params);
    }

    /**
     * Remove index from object that implementing Searchable interface to Elasticsearch.
     *
     * @param \Zeek\Ductible\Contracts\Searchable $model
     *
     * @return array
     */
    public function prune(Searchable $model)
    {
        return $this->delete([
            'index' => $model->getIndexableName(),
            'type' => $model->getIndexableType(),
            'id' => $model->getIndexableId(),
        ]);
    }

    /**
     * Delete all index based on an eloquent model instance.
     *
     * @param  Searchable $model
     *
     * @return array
     */
    public function nuke(Searchable $model)
    {
        return $this->client->deleteByQuery([
            'index' => $model->getIndexableName(),
            'type' => $model->getIndexableType(),
            'body' => [
                'query' => [
                    'match_all' => [],
                ]
            ],
        ]);
    }

    /**
     * Prepare params to be sent by the low level client.
     *
     * @param array $params
     *
     * @return array
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_per_request_configuration.html
     */
    protected function prepareParams(array $params)
    {
        return [
            'client' => [
                'ignore' => $this->config->get('ductible.client.ignores', []),
                'verbose' => $this->config->get('ductible.client.verbose', false),
            ],
        ] + $params;
    }
}
