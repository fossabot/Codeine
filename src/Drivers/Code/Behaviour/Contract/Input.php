<?php

    /* Codeine
     * @author BreathLess
     * @description: Argument checker
     * @package Codeine
     * @version 6.0
     * @date 29.07.11
     * @time 23:51
     */

    self::Fn('Run', function ($Call)
    {
        $Argumentors = array('Required', 'Type');

        if (isset($Call['Value']['Function'][($Call['Value']['_F'])]['Input']))
        {

            $Args = $Call['Value']['Function'][$Call['Value']['_F']]['Input'];

            foreach ($Args as $Name => $Argument)
            {
                foreach ($Argumentors as $Key)
                {
                    $Call['Value'] = F::Run(
                            array(
                                '_N' => $Call['_N'].'.'.$Key,
                                '_F' => 'Run',
                                'Value' => $Call['Value'],
                                'Name' => $Name,
                                'Input' => $Argument
                            )
                        );
                }
            }
        }
        return $Call['Value'];
    });
