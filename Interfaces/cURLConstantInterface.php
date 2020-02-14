<?php

namespace nlib\cURL\Interfaces;

interface cURLConstantInterface {
    
    const GET = 'GET';
    const POST = 'POST';
    const JSON = 'JSON';
    const _STRING = 'string';
    const _ARRAY = 'array';

    const METHODS = [self::GET, self::POST];
    const ENCODINGS = [self::JSON, self::_STRING, self::_ARRAY];   
    const RESPONSES = [self::JSON];
}