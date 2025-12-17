<?php

namespace App\DTO;

class ResponseErrorDTO
{
    public string $reason = '';

    public string $message = '';
    
    public ?array $errors = null;
}