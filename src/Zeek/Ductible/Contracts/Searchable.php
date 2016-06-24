<?php

namespace Zeek\Ductible\Contracts;

interface Searchable
{
    /**
     * Get index name.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return string.
     */
    public function getIndexableName();

    /**
     * Get index type.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return string
     */
    public function getIndexableType();

    /**
     * Get index ID.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return string|int
     */
    public function getIndexableId();

    /**
     * Get document body.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-index_.html
     *
     * @return array
     */
    public function getIndexableBody();
}
