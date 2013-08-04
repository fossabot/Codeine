<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Code', function ($Call)
    {
        // FIX THIS SHIT
    // TODO Realize "Code" function
        $chars = $Call['Alphabet']; // Задаем символы, используемые в капче. Разделитель использовать не надо.
          $length = rand(4, 4); // Задаем длину капчи, в нашем случае - от 4 до 7
          $numChars = strlen($chars); // Узнаем, сколько у нас задано символов
          $str = '';
          for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, rand(1, $numChars) - 1, 1);
          } // Генерируем код

        // Перемешиваем, на всякий случай
            $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
            srand ((float)microtime()*1000000);
            shuffle ($array_mix);
        // Возвращаем полученный код
        return implode("", $array_mix);
    });

    setFn('Widget', function ($Call)
    {
        $Code = F::Run(null, 'Code', $Call);

        $Call['Image'] = F::Run(
            'Image', 'Create', $Call
        );

        $Call['Image'] = F::Run(
            'Image', 'Pipeline', $Call,
            [
                'Steps' =>
                [
                    [
                        'Process' => 'Draw.Fill.Solid:Dot',
                        'X' => 0,
                        'Y' => 0,
                        'Color' =>
                            [
                                'R' => 255,
                                'G' => 255,
                                'B' => 255
                            ]
                    ],
                    [
                        'Process' => 'Text.Line:Add',
                        'X' => 34,
                        'Y' => 6,
                        'Size' => 16,
                        'Text' => $Code,
                        'Color' =>
                            [
                                'R' => 0,
                                'G' => 0,
                                'B' => 0
                            ]
                    ]
                ]
            ]
        );

        F::Run('Image', 'Save', $Call,
            [
                'ID' => '/tmp/cache/'.$Call['RHost'].'/captcha/'.sha1($Code).'.png'
            ]); // FIXME

        $Call['Session']['CAPTCHA'] = sha1($Code);

        return $Call;
    });

    setFn('Check', function ($Call)
    {
        if (file_exists('/tmp/cache/'.$Call['RHost'].'/captcha/'.$Call['Request']['CAPTCHA']['Challenge'].'.png'))
            unlink('/tmp/cache/'.$Call['RHost'].'/captcha/'.$Call['Request']['CAPTCHA']['Challenge'].'.png');

        if (!isset($Call['Session']['User']['ID']) && $Call['Request']['CAPTCHA']['Challenge'] != sha1($Call['Request']['CAPTCHA']['Answer']))
        {
            $Call['Failure'] = true;
            $Call = F::Hook('CAPTCHA.Failed', $Call);
        }

        return $Call;
    });