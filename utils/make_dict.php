<?php

$word_file = $argv[1];
$dict_file = $argv[2];

if($argc!=3) {
echo "Usage: php make_dict.php words_file dict_file\n\n";
echo "Thanks for choosing XSplit~\n";

exit();
}
echo "Preparing...";
//we use triditional way to read date from file line by line
$handle = fopen($word_file,"r");
if($handle) {
        while(!feof($handle)) {
                $line = fgets($handle);
                $line =  trim($line);
                $items = explode("\t", $line);
		if($items[0]&&$items[1])
			$words[$items[0]]=$items[1];
	}
}

//now we build a dictionary file with specified name
if(!xs_build($words, $dict_file)) {
	die('Failed to build dictionary!\n');
} else {
	echo "Dictionary was built successfully!\n";
}


