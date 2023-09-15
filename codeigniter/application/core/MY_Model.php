<?php

use Doctrine\DBAL\Tools\DsnParser;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

defined('BASEPATH') || exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    private const LOAD_CHECK_KEY = 'CI_MODEL_LOADED';

    public function __construct(
        #[Autowire(env: 'DATABASE_URL')]
        string $dsn
    ) {
        if (!defined(self::LOAD_CHECK_KEY)) {
            $CI = &get_instance();
            $CI->load->database($this->parseCodeigniterConnection($dsn));

            define(self::LOAD_CHECK_KEY, true);
        }

        parent::__construct();
    }

    protected function qb(): CI_DB_query_builder
    {
        return get_instance()->db;
    }

    protected function parseCodeigniterConnection(string $dsn): array
    {
        $dsnParser = new DsnParser();
        $params = $dsnParser->parse($dsn);

        return [
            'hostname' => $params['host'],
            'username' => $params['user'],
            'password' => $params['password'] ?? '',
            'database' => $params['dbname'],
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => $params['charset'] ?? 'utf8mb4',
            'dbcollat' => ($params['charset'] ?? 'utf8mb4') . '_unicode_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE,
            'port' => $params['port']
        ];
    }

    protected function parseCodeigniterDriver(string $dsnDriverName): string
    {
        return str_replace('-', '_', $dsnDriverName);
    }
}
