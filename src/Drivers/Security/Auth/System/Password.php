<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Identificate', function ($Call)
    {
        return $Call;
    });

    setFn('Authenticate', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read',
                     [
                          'Entity' => 'User',
                          'Where' =>
                          [
                              $Call['Determinant'] => $Call['Request'][$Call['Determinant']]
                          ],
                          'One' => true
                     ]);

        $Challenge = F::Run('Security.Hash', 'Get',
                                     [
                                          'Mode' => 'Password',
                                          'Value' => $Call['Request']['Password'],
                                          'Salt' => isset($Call['User']['Salt'])? $Call['User']['Salt']: ''
                                     ]);

        if ($Call['User']['Password'] != $Challenge)
        {
            F::Log('Passwords don\'t match', LOG_INFO);
            F::Log('User password hash is '.$Call['User']['Password'], LOG_INFO);
            F::Log('Request password hash is '.$Challenge, LOG_INFO);

            $Call['Output']['Content'][]
                = [
                        'Type' => 'Template',
                        'Scope' => 'User/Authenticate',
                        'ID' => 'Incorrect'
                  ];

            unset($Call['User']);
        }

        return $Call;
    });