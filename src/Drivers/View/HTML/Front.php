<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Layouts', function ($Call)
    {

        if (!isset($Call['Layouts']))
            $Call['Layouts'] = array();

        if (F::isCall($Call))
        {
            $Slices = explode('.', $Call['Service']);

            $sz = sizeof($Slices);

            $Asset = $Slices[0];

            $IDs = array('Main');
            for ($ic = 1; $ic < $sz; $ic++)
                $IDs[] = implode('/', array_slice($Slices, 1, $ic));

            foreach ($IDs as $ID)
               array_unshift($Call['Layouts'], array ('Scope' => $Asset, 'ID'    => $ID));
        }

        return $Call;
     });