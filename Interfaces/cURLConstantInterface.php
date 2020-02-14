<?php

namespace nlib\cURL\Interfaces;

interface cURLConstantInterface {
    
    const GET = 'GET';
    const POST = 'POST';

    const JSON = 'JSON';
    const _STRING = 'string';
    const _ARRAY = 'array';

    const APPLICATION = 'Content-Type: application/x-www-form-urlencoded';
    const MULTIPART = 'Content-Type: multipart/form-data';
    const TEXT = 'Content-Type: text/plain';

    const METHODS = [self::GET, self::POST];
    const ENCODINGS = [self::JSON, self::_STRING, self::_ARRAY];   
    const RESPONSES = [self::JSON];
    const CONTENT_TYPES = [self::APPLICATION, self::MULTIPART, self::TEXT];
}