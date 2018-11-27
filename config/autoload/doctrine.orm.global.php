<?php

//Doctrine Connection Settings
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => require 'ddbb.php', 
            ]
        ]
    ],

    //'service_manager' => [
    //    'factories' => [
    //        'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
    //    ]
    //],
];
