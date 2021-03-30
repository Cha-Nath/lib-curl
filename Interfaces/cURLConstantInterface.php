<?php

namespace nlib\cURL\Interfaces;

interface cURLConstantInterface {
    
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';

    const JSON = 'JSON';
    const _STRING = 'string';
    const _ARRAY = 'array';

    const APPLICATION = 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8;';
    const MULTIPART = 'Content-Type: multipart/form-data;charset=UTF-8;';
    const TEXT = 'Content-Type: text/plain;charset=UTF-8;';
    const APPLICATION_JSON = 'Content-Type: application/json;charset=UTF-8;';

    const APPLICATION_JSON_ACCEPT = 'accept: application/json';

    const METHODS = [self::GET, self::POST, self::PUT, self::PATCH, self::DELETE];
    const ENCODINGS = [self::JSON, self::_STRING, self::_ARRAY];   
    const RESPONSES = [self::JSON];
    const CONTENT_TYPES = [self::APPLICATION, self::MULTIPART, self::TEXT, self::APPLICATION_JSON];
}