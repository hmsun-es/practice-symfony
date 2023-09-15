<?php

defined('BASEPATH') || exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    /**
     * 
     * @var CI_Benchmark
     */
    public $benchmark;

    /**
     * 
     * @var CI_Controller
     */
    public $controller;

    /**
     * 
     * @var CI_Exceptions
     */
    public $exceptions;

    /**
     * 
     * @var CI_Hooks
     */
    public $hooks;

    /**
     * 
     * @var CI_Input
     */
    public $input;

    /**
     * 
     * @var CI_Lang
     */
    public $lang;

    /**
     * 
     * @var CI_Loader
     */
    public $load;

    /**
     * 
     * @var CI_Log
     */
    public $log;

    /**
     * 
     * @var CI_Output
     */
    public $output;

    /**
     * 
     * @var CI_Router
     */
    public $router;

    /**
     * 
     * @var CI_Security
     */
    public $security;

    /**
     * 
     * @var CI_URI
     */
    public $uri;

    /**
     * 
     * @var CI_Utf8
     */
    public $utf8;

    /**
     * 
     * @var CI_DB_driver
     */
    public $db;
}
