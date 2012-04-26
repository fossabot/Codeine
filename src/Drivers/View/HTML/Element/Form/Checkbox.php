<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run ('View', 'LoadParsed', $Call,
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/HTML/Form/Checkbox',
                                'Data'  => $Call
                           ));
     });