<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['HTML']))
            foreach($Call['Auditors'] as $Auditor)
                $Call = F::Live($Auditor, $Call);

        return $Call;
    });