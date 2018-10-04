<?php

//Doctrine Connection Settings
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => [
                    'host'     => 'localhost',
                    'user'     => 'root',
                    'password' => '1turr1',
                    'dbname'   => 'ma_drbfm',
                    'charset'  => 'utf8',
                ]
            ]
        ]
    ],

    //'service_manager' => [
    //    'factories' => [
    //        'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
    //    ]
    //],
];
