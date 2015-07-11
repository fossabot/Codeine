<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['No Remote']['Enabled'])
            && $Call['No Remote']['Enabled']
            && preg_match_all('@a href="(https?://.*)"@SsUu',$Call['Output'], $Links))
        {
            foreach ($Links[1] as $IX => $Link)
            {
                $NoRemote = true;

                $LinkHost = parse_url($Link, PHP_URL_HOST);

                if (in_array($Link, $Call['No Remote']['Excluded']))
                    $NoRemote = false;

                foreach ($Call['Project']['Hosts'] as $Host)
                    if (preg_match('/'.$Host.'$/', $LinkHost))
                    {
                        $NoRemote = false;
                        break;
                    }

                if ($NoRemote)
                    $Call['Output'] = str_replace($Links[0][$IX], 'a href="/go/'.$Link.'"',$Call['Output']);
            }
        }

        return $Call;
    });