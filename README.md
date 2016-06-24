# Ductible

Ductible is a Laravel package that deliver a mid-level Elastic Search client.

## Installation

You can install ductible via composer cli:

```sh
composer install krisanalfa/ductible
```

## Configuration

Register `DuctibleServiceProvider` to your configuration:

```php
'providers' => [
    // ...

    Zeek\Ductible\DuctibleServiceProvider::class,
],
```

### Optional

Register Facade alias to your configuration:

```php
'aliases' => [
    // ...

    'Ductible' => Zeek\Ductible\Facades\Ductible::class,
],
```

### Publishing Configuration

By default, you can use Ductible with zero configuration, assuming your Elasticsearch run in http://localhost:9200.
If you want something different, you may publish Ductible configuration and make some changes from it:

```sh
php artisan vendor:publish --provider="Zeek\Ductible\DuctibleServiceProvider"
```

### Configuration Explained

You can read from inline docs there. It currently supports many aspect of Elasticsearch client configuration, such as:

- `host` (The most common configuration is telling the client about your cluster. read below to know how to configure multiple hosts.).
- `retries` (When the client runs out of retries, it will throw the last exception that it received.).
- `log` (Where to store your elasticsearch log.).
- `handler` (Elasticsearch-PHP uses an interchangeable HTTP transport layer called RingPHP. You may customize your handler here.).
- `pool` (The connection pool is an object inside the client that is responsible for maintaining the current list of nodes.).
- `selector` (The connection pool manages the connections to your cluster.).
- `serializer` (The response serializer.).
- `client.ignores` (The library attempts to throw exceptions for common problems. These exceptions match the HTTP response code provided by Elasticsearch.)
- `client.verbose` (If you require more information, you can tell the client to return a more verbose response.).

You can set multiple hosts by configuring in your `env` file like so:

```
ELASTICSEARCH_HOSTS="localhost:9200|192.168.1.10:9200|http://myserver.com:9200"
```

## Usage

### Basic

Ductible provides a low level client interaction, which you can explore as much as you want.

#### Indexing a Document

```php
$result = Ductible::index([
    'index' => 'myIndex',
    'type' => 'myType',
    'id' => 1,
    'body' => [
        'fieldFoo' => 'Foo',
        'fieldBar' => 'Bar',
        'fieldBaz' => 'Baz',
    ],
]);
```

#### Getting Document Index

```php
$index = Ductible::get([
    'index' => 'myIndex',
    'type' => 'type',
    'id' => 1,
]); // The result is an array
```

#### Update Document Index

```php
$result = Ductible::index([
    'index' => 'myIndex',
    'type' => 'myType',
    'id' => 1,
    'body' => [
        'fieldFoo' => 'Foo Foo',
        'fieldBar' => 'Bar Bar',
        'fieldBaz' => 'Baz Baz',
    ],
]);
```

#### Delete Document Index

```php
$index = Ductible::delete([
    'index' => 'myIndex',
    'type' => 'type',
    'id' => 1,
]); // The result is an array
```

#### Searching

```php
$result = Ductible::search($searchParams);
```

#### Bulk Indexing Documents

```php
$result = Ductible::bulk($searchParams);
```

### Using Eloquent

**To Be Defined**

### ToDo

- [ ] Separate indexing operation based on eloquent model in Ductible main class
- [ ] More unit testing
