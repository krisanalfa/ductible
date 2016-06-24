<?php

namespace Zeek\Ductible\Searchable;

use App;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Doctrine\DBAL\Types\StringType;
use Zeek\Ductible\Contracts\Searchable as SearchableContract;

trait Elastic
{
    /**
     * Contains which column(s) should be search in full text search.
     *
     * @var array
     */
    protected static $searchableColumns = [];

    /**
     * Contains index type prepared by an automation logic.
     *
     * @var string
     */
    protected static $preparedIndexType = '';

    /**
     * Get index name.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return string.
     */
    public function getIndexableName()
    {
        return property_exists($this, 'indexName')
            ? $this->indexName
            : 'ductible';
    }

    /**
     * Get index type.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return string
     */
    public function getIndexableType()
    {
        return property_exists($this, 'indexType')
            ? $this->indexType
            : $this->fetchIndexableType();
    }

    /**
     * Get index ID.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return string|int
     */
    public function getIndexableId()
    {
        return $this->getKey();
    }

    /**
     * Get document body.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return array
     */
    public function getIndexableBody()
    {
        return $this->toArray();
    }

    /**
     * Perform indexing on a model.
     *
     * @return array
     */
    public function index()
    {
        return App::make('ductible')->sync($this);
    }

    /**
     * Determine whether a model has been indexed.
     *
     * @return bool
     */
    public function indexed()
    {
        return App::make('ductible')->getClient()->exists([
            'id' => $this->getIndexableId(),
            'index' => $this->getIndexableName(),
            'type' => $this->getIndexableType(),
        ]);
    }

    /**
     * Perform index on all records stored in a table.
     *
     * @return Collection
     */
    public static function indexAll()
    {
        return Collection::make(
            Arr::get(
                App::make('ductible')->syncMany(static::all()),
                'items',
                []
            )
        );
    }

    /**
     * Perform deleting index from a model.
     *
     * @return array
     */
    public function prune()
    {
        return App::make('ductible')->prune($this);
    }

    /**
     * Perform search on a term.
     *
     * @param  string $term   Term / keyword you want to search.
     * @param  array  $fields An array contains which fields you want to lookup upon a search.
     *
     * @return Collection
     */
    public static function search($term, array $fields = [])
    {
        return Collection::make(
            Arr::get(
                App::make('ductible')->search(
                    $this->buildSearchQuery(new static())
                ),
                'hits.hits',
                []
            )
        );
    }

    /**
     * Build search query.
     *
     * @param  SearchableContract $model  It's a model.
     * @param  string             $term   Term / keyword you want to search.
     * @param  array              $fields An array contains which fields you want to lookup upon a search.
     *
     * @return array
     */
    protected function buildSearchQuery(
        SearchableContract $model,
        $term,
        array $fields = []
    ) {
        return [
            'index' => $model->getIndexableName(),
            'type' => $model->getIndexableType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $term,
                        'fields' => (($fields === [])
                            ? $model->getSearchableColumns()
                            : $fields),
                    ],
                ],
            ]
        ];
    }

    /**
     * Get a searchable columns that we want to use against searching.
     *
     * @return array
     */
    public function getSearchableColumns()
    {
        return (static::$searchableColumns === [])
            ? $this->fetchSearchableColumns() // If it's empty, fetch automatically
            : static::$searchableColumns; // Otherwise, return it value
    }

    /**
     * Automatically fetch database information to determine which columns we can use for searching.
     *
     * @return array
     */
    protected function fetchSearchableColumns()
    {
        return static::$searchableColumns = Collection::make(
            App::make('ductible.dbal.schema')
                ->listTableDetails($this->getTable())
                ->getColumns()
        )->filter(function ($column) {
            return ($column->getType() instanceof StringType);
        })->keys();
    }

    /**
     * Automatically get table name for index type.
     *
     * @return string
     */
    protected function fetchIndexableType()
    {
        return (static::$preparedIndexType)
            ? static::$preparedIndexType // If it's not empty, return it
            : (static::$preparedIndexType = strtolower($this->getTable())); // Otherwise, fetch automatically
    }
}
