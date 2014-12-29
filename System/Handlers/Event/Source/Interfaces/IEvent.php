<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Event\Source\Interfaces;
use Handlers\Event\Source\Classes\Event;

interface IEvent {
   
    public function update(Event $event);
    
    public function getInstances(Event $event);
    
}
