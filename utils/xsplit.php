<?php
$br = (php_sapi_name() == "cli")? "":"<br>";

if(!extension_loaded('xsplit')) {
	dl('xsplit.' . PHP_SHLIB_SUFFIX);
}
$module = 'xsplit';
$functions = get_extension_funcs($module);
echo "Functions available in the test extension:$br\n";
foreach($functions as $func) {
    echo $func."$br\n";
}
echo "$br\n";

/**********建立词典*********/
$dict_file='dict.db';

$dwords['那']=100;
$dwords['只']=100;
$dwords['的']=100;
$dwords['我']=100;
$dwords['美丽']=100;
$dwords['在']=100;
$dwords['蝴蝶']=100;
$dwords['永远']=100;
$dwords['心中']=100;
$dwords['翩翩']=100;
$dwords['飞舞']=100;
$dwords['翩翩飞舞']=10;
$dwords['着']=100;

if(!xs_build($dwords, $dict_file)) {
    die('建立词典失败！');
}

/**********分词**********/

$text="那只美丽的蝴蝶永远在我心中翩翩飞舞着。";

xs_open($dict_file);

$words = xs_split($text, XS_SPLIT_MMSEG);  /* 此处没有指定词典资源，默认使用最后一次打开的词典 */

var_dump($words); /* 那 _ 只 _ 美丽 _ 的 _ 蝴蝶 _ 永远 _ 在 _ 我 _ 心中 _ 翩翩飞舞 _ 着 _ 。 */

/**********查找**********/

$word='翩翩飞舞';

$result=xs_search($word, XS_SEARCH_CP); /* common prefix search */

var_dump($result);

$result=xs_search($word, XS_SEARCH_EM); /* exact match search */

var_dump($result);

$result=xs_search($text, XS_SEARCH_ALL_SIMPLE);

var_dump($result);

$result=xs_search($text, XS_SEARCH_ALL_DETAIL);

var_dump($result);



//samples of calculating simhash and hamming distance

$text1="那只美丽的蝴蝶永远在我心中翩翩飞舞着。";
$text2="那只美丽的蝴蝶永远在我心中翩翩飞舞。";
$tokens1=xs_search($text1, XS_SEARCH_ALL_INDICT); /* 去掉标点等特殊符号，经过实验，计算simhash时，一些标点、换行、特殊符号等对效果影响较大 */
$tokens2=xs_search($text2, XS_SEARCH_ALL_INDICT);

$simhash1=xs_simhash($tokens1);
$simhash2=xs_simhash($tokens2);

echo "simhash1 is {$simhash1}\n";
echo "simhash2 is {$simhash2}\n";

$hamming_dist=xs_hdist($simhash1, $simhash2);

echo "bit-wise format:\n";
echo decbin(hexdec($simhash1)), "\n";
echo decbin(hexdec($simhash2)), "\n";

echo "hamming distance is {$hamming_dist}\n";


?>
