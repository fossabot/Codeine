<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Check', function ($Call)
    {
        if (time()%$Call['Period'] == 0)
        {
            sleep(1);
            return $Call;
        }
        else
            null;
    });