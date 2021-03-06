<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Store', function ($Call)
    {
        if (isset($Call['Query']))
        {
            $Query = F::Run('Entity', 'Read',
            [
                'Entity' => 'Search.Query',
                'Where'  =>
                [
                    'Query' => $Call['Query']
                ],
                'One'    => true
            ]);

            if (empty($Query))
            {
                F::Run('Entity', 'Create',
                [
                    'Entity' => 'Search.Query',
                    'Data'  =>
                    [
                        'Query' => $Call['Query'],
                        'Hits' => $Call['Hits'],
                        'Count' => 1
                    ]
                ]);
            }
            else
            {
                F::Run('Entity', 'Update',
                [
                    'Entity' => 'Search.Query',
                    'Where'  =>
                    [
                        'Query' => $Call['Query']
                    ],
                    'Data'  =>
                    [
                        'Hits' => $Call['Hits'],
                        'Count' => $Query['Count']+1
                    ]
                ]);
            }
        }

        return $Call;
    });