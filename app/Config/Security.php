<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    public string $csrfProtection = 'cookie';
    public bool   $tokenRandomize = false;
    public string $tokenName      = 'csrf_test_name';
    public string $headerName     = 'X-CSRF-TOKEN';
    public string $cookieName     = 'csrf_cookie_name';
    public int    $expires        = 7200;

    /**
     * Set to FALSE so the token stays valid across multiple AJAX calls
     * without needing a page reload between each request.
     */
    public bool $regenerate = false;

    public bool $redirect = (ENVIRONMENT === 'production');
}
