<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
         foreach ($Call['Parsed']['Value'] as $Ix => $Match)
         {
             $Call['Output'] = str_replace ($Call['Parsed']['Match'][$Ix],
                 rawurlencode($Match)
                 ,$Call['Output']);
         }

        return $Call;
     });