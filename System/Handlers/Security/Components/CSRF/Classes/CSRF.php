<?php

/*
 * Copyright 2015 mamdouh alramadan.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Handlers\Security\Components\CSRF\Classes;

/**
 * SOLID Frameword 2014
 */

/**
 * Description of CSRF
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */
class CSRF {
    
    private $session;
    private $configurationHandler;
    private $token = ['name'=>'','value'=>''];
    private $configuration;
    const CSRF_CONFIGURATION_PATH = "Application/Configuration/Security";
    
    public function __construct(\Facade\SessionHandler $sessionHandler, \Facade\ConfigurationHandler $configurationHandler) {
        $this->session = $sessionHandler;
        $this->configurationHandler = $configurationHandler;
        $this->readCSRFConfiguration();
        $this->initCSRFToken();
    }
    
    private function initCSRFToken(){
        $this->token['name'] = $this->configuration->get('tokenName');
        $this->token['age'] = !isset($this->token['age'])?time():$this->token['age'];
        $generateToken = empty($this->session->get($this->token['name']));
        $generateToken |= ($this->token['age'] + $this->configuration->get('tokenLifetime') < time());
        if($generateToken){
            $this->generateToken();
        }
    }
    
    private function readCSRFConfiguration(){
        $fullPath = ROOT . DOCUMENT_SEPARATOR . APPNAME . DOCUMENT_SEPARATOR . self::CSRF_CONFIGURATION_PATH;
        $this->configurationHandler->loadConfiguration($fullPath, TRUE);
        $this->configuration = $this->configurationHandler->getConfigurationNode('CSRF');
    }
    
    private function isTokenValid(){
        return $this->token['value'] === $this->session->get($this->token['name']);
    }
    
    public function getToken(){
        return $this->session->get($this->token['name']);
    }
    
    public function validateToken($token){
        $this->token['value'] = $token;
        $tokenStatus = $this->isTokenValid();
        if($tokenStatus == true){
            $this->regenerateToken();
        }
        return $tokenStatus;
    }
    
    public function regenerateToken(){
        $this->generateToken();
    }
    
    private function generateToken(){
        $token = str_shuffle(mt_srand(12) . openssl_random_pseudo_bytes(12));
        $this->session->set($this->token['name'], $token);
        $this->session->set($this->token['name'].'age', time());
    }
}
