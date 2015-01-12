<?php


namespace ApplicationControllers;


use ApplicationFacade\HomeFacade;

class HomeController {

    private $homeFacade;

    public function __construct(HomeFacade $homeFacade){
        $this->homeFacade = $homeFacade;
    }

    public function test($someValue){
        echo $someValue;

    }

}