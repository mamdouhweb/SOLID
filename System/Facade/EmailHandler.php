<?php

/**
 * SOLID Frameword 2014
 */
namespace Facade;
use Mailgun\Mailgun;
/**
 * Description of EmailHandler
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */
class EmailHandler {
    private $configurationHandler;
    private $message;
    private $subject;
    private $contacts;
    private $configuration;
    private $transport;
    private $mailer;
    const MAIL_CONFIGURATION_PATH = "Application/Configuration/Mail";
    
    public function __construct(ConfigurationHandler $configrationHandler, $configurationName = 'generic') {
        $this->configurationHandler = $configrationHandler;
        $this->setConfiguration($configurationName);
    }
    
    public function send(ConfigurationHandler $configurationHandler = NULL, $configurationName = 'generic'){
        if(!empty($configurationHandler)){
            $this->configurationHandler = $configurationHandler;
            $this->setConfiguration($configurationName);
        }
        $this->initMailer();
        
        return $this->mailer->sendMessage($this->configuration->get('server'), array('from' => $this->configuration->get('from'), 
                                'to'      => $this->contacts, 
                                'subject' => $this->subject, 
                                'text'    => $this->message));
    }
    
    public function to($contacts){
        $this->contacts = $contacts;
        return $this;
    }
    
    public function setMessage($message){
        $this->message = $message;
        return $this;
    }
    
    public function setSubject($subject){
        $this->subject = $subject;
        return $this;
    }
    
    private function initTransport(){
        return true;
        // no transporter with mailgun
//        $isSSL = $this->configuration->get('ssl');
//        
//        $this->transport = \Swift_SmtpTransport::newInstance($this->configuration->get('server'), $this->configuration->get('port'))
//                    ->setUsername($this->configuration->get('username'))
//                    ->setPassword($this->configuration->get('password'));
//        if($isSSL){
//            $this->transport->setEncryption('ssl');
//        }
        
    }
    
    private function initMailer(){
        $this->mailer = new Mailgun($this->configuration->get('key'));
    }
    
    private function setConfiguration($configurationName){
        
        $fullPath = ROOT . DOCUMENT_SEPARATOR . APPNAME . DOCUMENT_SEPARATOR . self::MAIL_CONFIGURATION_PATH;
        
        $this->configurationHandler->loadConfiguration($fullPath, TRUE);
        
        $this->configuration = $this->configurationHandler->getConfigurationNode($configurationName);
    }
}
