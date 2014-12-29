<?php

/* 
 * SOLID Framework 2014
 */

$configuration = array();

$configuration['key'] = 'test123';

$configuration['savepath'] = '';

$configuration['session_type'] = 'cookie'; //we only have cookie session implementation for now

$configuration['ttl'] = 15000; //session time to live

$configuration['encrypt'] = TRUE;

$configuration['encryptMethod'] = 'BLOWFISH';

$configuration['rounds'] = 20;

$configuration['enabled'] = TRUE;

$configuration['secure'] = TRUE;

return $configuration;

