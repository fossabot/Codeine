<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Inject', function ($Call)
    {
        if(isset($Call['Title']))
            $Call['Value'][]
                = array (
                'Place' => 'Title',
                'Type'  => 'Page.Title',
                'Value' => $Call['Title']
            );

        if (isset($Call['Description']))
            $Call['Value'][]
                = array (
                'Place' => 'Meta',
                'Type'  => 'Page.Description',
                'Value' => $Call['Description']
            );

        if (isset($Call['Keywords']))
            $Call['Value'][]
                = array (
                'Place' => 'Meta',
                'Type'  => 'Page.Keywords',
                'Value' => $Call['Keywords']
            );


         return $Call;
     });