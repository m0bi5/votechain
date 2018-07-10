<?php
    $url = 'http://127.0.1.1:5000/display_neighbours';

    //Use file_get_contents to GET the URL in question.
    $contents = file_get_contents($url);

    //If $contents is not a boolean FALSE value.
    if($contents !== false)
    {
        //Print out the contents.
        $contents=explode('[',$contents)[1];
        $contents=explode(']',$contents)[0];
        $contents=explode(',',$contents);
        $r="";
        exec("python3 read.py",$r);
        array_push($contents, '"http://127.0.1.1:5000"');
        for ($i=0; $i<count($contents); $i++)
        {
            echo($_POST['voter_name']);
            $v=explode('"',$contents[$i])[1];
            $cmd='curl -H "Content-Type: application/json" -X POST -d \'{"voter":'.'"'.$_POST['voter_name'].'"'.',"UIDAI":'.'"'.$_POST['aadhar'].'"'.',"vote":'.'"'.$_POST['candidate'].'"'.'}\' '.$v.'/new_vote'.' >/dev/null 2>/dev/null &';
            $result="";
            exec($cmd,$result);
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>
        DATA
    </title>
</head>
<body>
asdsa
</body>
</html>