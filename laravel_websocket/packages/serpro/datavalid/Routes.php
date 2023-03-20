<?php

namespace Serpro\Datavalid;

use Serpro\Datavalid\Anonymous;

class Routes
{      
    /**
     * @return \Serpro\Datavalid\Anonymous
     */
    public static function status()
    {
        $anonymous = new Anonymous();

        $anonymous->base = static function () {
            return "datavalid/v3/status";
        }; 

        return $anonymous;
    } 
    
    /**
     * @return \Serpro\Datavalid\Anonymous
     */
    public static function document()
    {
        $anonymous = new Anonymous();

        $anonymous->base = static function () {
            return "datavalid/v3/validate/pf-basica";
        }; 

        return $anonymous;
    } 
}