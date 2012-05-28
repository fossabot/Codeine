<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions('Entity.'.$Call['Entity']));

        $Call = F::Hook('beforeEntityCreate', $Call);

        $Call['Locales'][] = $Call['Entity'];

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Main'
                );

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Create'
                );

        if (isset($Call['URL']))
            $Call['Output']['Content']['Form']['Action'] = $Call['URL'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Call['Element'] = F::Run('Entity', 'Create',
                array (
                      'Entity' => $Call['Entity'],
                      'Data' => isset($Call['Data'])? F::Merge($Call['Data'], $Call['Request']): $Call['Request']
                ));


            $Call = F::Hook('afterEntityCreate', $Call);

            return $Call;
        }

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Widgets']['Write']))
                $Call['Output']['Form'][] = F::Merge($Node['Widgets']['Write'],
                    array('Name' => $Name,
                          'Entity' => $Call['Entity'],
                          'Value' => isset($Node['Default'])? F::Live($Node['Default']): ''));
        }

        return $Call;
    });