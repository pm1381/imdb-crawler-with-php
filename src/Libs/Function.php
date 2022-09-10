<?php
$startTimer = microtime(true);
$defaultTimer = 0;
$debug = [];

function print_f($data, $exit = false)
{
    echo '<pre style="direction:ltr; text-align:left; white-space:break-spaces;">';
    print_r($data);
    echo '</pre>';

    if($exit)
        exit();
}

function timer($value = "Default")
{
    global $defaultTimer;

    $now = microtime(true);

    $time = 0;
    if($defaultTimer)
        $time = $now - $defaultTimer;

    $defaultTimer = $now;

    print_f($value.": ".$now." => ".$time);
}

function resetFileAddress($value)
{
    $value = explode("/", $value);
    $lastName = ucwords($value[count($value)-1]);
    array_pop($value);

    return strtolower(implode("/", $value)).'/'.$lastName;
}

function addDebug($start, $end, $query)
{
    global $debug;

    $debug[] = [
        'start' => $start,
        'end' => $end,
        'query' => $query
    ];
}

function runDebug()
{
    global $debug;
    global $startTimer;

    krsort($debug);

    $htm = $file = 'No Query';
    if(count($debug) > 0)
    {
        $htm = '
            <script>
                function showDebug(){
                    var x = document.getElementById("debugSystem");
                    if(x.style.display === "none")
                        x.style.display = "block";
                    else
                        x.style.display = "none";
                }
            </script>
            <div style="font:normal 12px tahoma;cursor:pointer;color:#fff;padding:2px 5px;position:fixed;left:45px;bottom:10px;background:#f00;z-index:99999999;opacity:0.7;" onClick="showDebug()">QT: '.number_format($debug[count($debug)-1]['end'] - $debug[0]['start'], 15).' , QC: '.count($debug).' , LT: '.number_format(microtime(true) - $startTimer, 15).'</div>
            <a href="https://'.$_SERVER['SERVER_NAME'].'/'.FOLDER.'debug.log" target="_blank" style="font:normal 12px tahoma;cursor:pointer;color:#fff;padding:2px 5px;position:fixed;left:10px;bottom:10px;background:#f00;z-index:99999999;opacity:0.7;">Log</a>
            <div id="debugSystem" style="position:fixed;left:0;top:0;background:#000;z-index:99999998;width:100%;height:100%;display:none;overflow:scroll;">
                <div style="background:#338fd0;line-height:16px;padding:10px;direction:ltr;text-align:left;font:bold 12px tahoma;margin:5px 5px 0;color:#fff;">
                    Query Time: '.number_format($debug[count($debug)-1]['end'] - $debug[0]['start'], 15).' , 
                    Query Count: '.count($debug).' , 
                    Load Time: '.number_format(microtime(true) - $startTimer, 15).'
                </div>
        ';

        $file = "
            Query Time: ".number_format($debug[count($debug)-1]['end'] - $debug[0]['start'], 15)."
            Query Count: ".count($debug)."
            Load Time: ".number_format(microtime(true) - $startTimer, 15)."
        ";
        
        foreach($debug as $output)
        {
            $htm .= '
                <div style="background:#232323;line-height:16px;padding:10px;direction:ltr;text-align:left;font:normal 12px tahoma;margin:5px 5px 0;color:#fff;">
                    Query: '.$output['query'].'<br>
                    Time: '.number_format($output['end'] - $output['start'], 15).'
                </div>
            ';

            $file .= "
                <!------- *** -------!>

                Query: ".$output['query']."
                Time: ".number_format($output['end'] - $output['start'], 15)."
            ";
        }

        $htm .= '</div>';
    }

    $isJson = false;
    foreach(headers_list() as $header)
        if(strpos($header, "application/json") !== false)
            $isJson = true;
    
    if(!$isJson)
        echo $htm;
        
    file_put_contents('debug.log', trim(preg_replace('/^ +/m', '', $file)));
}
?>