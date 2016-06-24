<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

try {
    (new Dotenv(dirname(dirname(__DIR__))))->load();
} catch (InvalidPathException $e) {
    //
}
