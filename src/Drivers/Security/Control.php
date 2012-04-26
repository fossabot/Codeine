<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Output']['Sidebar'][] =
              array(
                  'Type' => 'Button',
                  'Value' => 'Hash.Test',
                  'Action' => '/control/Security/HashTest'
              );

        return $Call;
    });

    self::setFn('HashTest', function ($Call)
    {
        $Options = F::loadOptions('Security.Hash');

        foreach ($Options['Algos'] as $Key=> $Hash)
        {
            $ST = microtime(true);

            F::Run('Security.Hash.'.$Hash, 'Get', array('Value' => time()));

            $ST = microtime(true)-$ST;

            $Call['Output']['Content'][] =
              array(
                  'Type' => 'Block',
                  'Class' => 'alert alert-success',
                  'Value' => $Key.' ('.$Hash.') '.round(1/$ST).' hash/sec'
              );
        }

        return $Call;
    });