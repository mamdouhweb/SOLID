<?php

/*
 * SOLID Framework 2014
 */



return array(
    'rootLogger' => array(
        'appenders' => array('default'),
    ),
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderFile',
            'layout' => array(
                'class' => 'LoggerLayoutSimple'
            ),
            'params' => array(
            	'file' => '/var/log/solid.log',
            	'append' => true
            )
        )
    )
);