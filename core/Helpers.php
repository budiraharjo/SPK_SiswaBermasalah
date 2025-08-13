<?php

function base_url($path = '')
{
    return rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/') . '/' . ltrim($path, '/');
}