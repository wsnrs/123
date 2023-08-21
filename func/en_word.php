<?php
/*
$text_plain = '5、综上而言，上述文章是一篇与论文查重与知网方面有关教程，可以做为论文查重与优点与PaperFree学习.
5、综上而言，上述文章是一篇与论文查重与知网方面有关教程，可以做为论文查重与优点与PaperFree学习.

5、综上而言，上述文章是一篇与论文查重与知网方面有关教程，可以做为论文查重与优点与PaperFree学习.


';

match_en($text_plain);*/

function match_en_and_replace($text_plain) {
	$reg_exUrl = '/[a-z]+/i';
	preg_match_all($reg_exUrl, $text_plain, $ens);

	if (isset($ens[0])) {
		foreach ($ens[0] as $value) {
			$text_plain = str_replace($value, 'baidu', $text_plain);
		}
	}

	return $text_plain;
}


?>