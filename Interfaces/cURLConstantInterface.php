<?php

namespace nlib\cURL\Interfaces;

interface cURLConstantInterface {
    
    const GET = 'GET';
    const POST = 'POST';
    const JSON = 'JSON';
    const STRING = 'string';

    const METHODS = [self::GET, self::POST];
    const ENCODINGS = [self::JSON, self::STRING];   
    const RESPONSES = [self::JSON];
}