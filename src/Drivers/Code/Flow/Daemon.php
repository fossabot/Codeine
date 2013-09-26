<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y

    setFn('Run', function ($Call)
    {
        declare(ticks = 1);

        $SH = function ($Signal) {return F::Run('Code.Flow.Daemon', 'Signal', ['Signal' => $Signal]);};

        foreach ($Call['Signals'] as $SigID => $Hook)
            if (!pcntl_signal(constant($SigID), $SH))
                F::Log('Signal '.$SigID.' not handled', LOG_ERR);

        $PIDFile = F::Run(null, 'PIDFile', $Call);

        if (F::Run(null, 'Running?', $Call))
        {
            F::Log('Daemon already active', LOG_CRIT);
            exit;
        }
        else
        {
            if (file_put_contents($PIDFile, getmypid()) != false)
                F::Log('PID file created: '.$PIDFile, LOG_WARNING);
            else
                F::Log('PID file failed: '.$PIDFile, LOG_ERR);

        }

        $Ungrateful = [];

        while (F::getLive())
        {
            if ((count($Ungrateful) < $Call['MaxChilds']))
            {
                $PID = pcntl_fork();

                if ($PID == -1)
                {
                    F::Log('Daemon: Fork failed', LOG_CRIT);
                    return -1;
                } //TODO: ошибка - не смогли создать процесс
                elseif ($PID)
                {
                    $Ungrateful[$PID] = true;
                    F::Log('Daemon: Forked '.$PID, LOG_INFO);
                }
                else
                {
                    $Result = F::Live($Call['Execute']);
                    if ($Result !== null)
                        F::Log(getmypid().': '.json_encode($Result), LOG_WARNING);

                    exit;
                }
            }

            while ($Signaled = pcntl_waitpid(-1, $Status, WNOHANG))
            {
                if ($Signaled == -1)
                {
                    $Ungrateful = [];
                    break;
                }
                else
                {
                    unset($Ungrateful[$Signaled]);
                    F::Log('Daemon: Dead '.$Signaled, LOG_WARNING);
                }
            }

            sleep(1);
        }

        unlink($PIDFile);

        return $Call;
    });

    setFn('Running?', function ($Call)
    {
        $PIDFile = F::Run(null, 'PIDFile', $Call);

        if (file_exists($PIDFile))
        {
            //проверяем на наличие процесса
            if (posix_kill((int) file_get_contents($PIDFile), 1))
                return true; //демон уже запущен
            else //pid-файл есть, но процесса нет
            {
                if (posix_get_last_error() == 1) /* EPERM */
                    return true;

                if (is_writable($PIDFile) && !unlink($PIDFile))
                    return (-1);
            } //не могу уничтожить pid-файл. ошибка
        }
        else
        {

        }

        return false;
    });

    setFn('PIDFile', function ($Call)
    {
        return $Call['PID']['Prefix'].$Call['Execute']['Service'].$Call['PID']['Postfix'];
    });

    setFn('Stop', function ($Call)
    {
        return F::setLive(false);
    });

    setFn('Signal', function ($Call)
    {
        if (isset($Call['Codes'][$Call['Signal']]))
        {
            $Signal = $Call['Codes'][$Call['Signal']];

            if (isset($Call['Signals'][$Signal]))
                return F::Live($Call['Signals'][$Signal]);
            // или Нет обработчиков
        }
        // или неизвестный код

        return null;
    });

    setFn('Flush', function ($Call)
    {
        F::reloadOptions();
        F::Log('Core flushed', LOG_WARNING);
        return true;
    });