<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['JS']['Scripts']))
        {
            $Call['JS']['Scripts'] = [
                'Combined' => implode(';'.PHP_EOL, $Call['JS']['Scripts'])
            ];
        }

        return $Call;
    });