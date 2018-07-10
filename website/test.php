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
		array_push($contents, '"http://127.0.1.1:5000"');
		for ($i=0; $i<count($contents); $i++)
		{
			echo(explode('"',$contents[$i])[1]);
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