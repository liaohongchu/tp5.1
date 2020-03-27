<?php
namespace app\index\controller;

class Test
{
    public function index()
    {
    	$username = "墨明智";

    	$val = $this->hashCode($username);//方案一
    	$val2 = $this->hashCode($username);//方案二

    	echo "val = ".$val."<br/>";
    	echo "val2 = ".$val2."<br/>";

        return 'test ';
    }

    //全角字符转变成半角字符
	public function replace_DBC2SBC($str) {
	    $DBC = Array(
	        '０' , '１' , '２' , '３' , '４' ,
	        '５' , '６' , '７' , '８' , '９' ,
	        'Ａ' , 'Ｂ' , 'Ｃ' , 'Ｄ' , 'Ｅ' ,
	        'Ｆ' , 'Ｇ' , 'Ｈ' , 'Ｉ' , 'Ｊ' ,
	        'Ｋ' , 'Ｌ' , 'Ｍ' , 'Ｎ' , 'Ｏ' ,
	        'Ｐ' , 'Ｑ' , 'Ｒ' , 'Ｓ' , 'Ｔ' ,
	        'Ｕ' , 'Ｖ' , 'Ｗ' , 'Ｘ' , 'Ｙ' ,
	        'Ｚ' , 'ａ' , 'ｂ' , 'ｃ' , 'ｄ' ,
	        'ｅ' , 'ｆ' , 'ｇ' , 'ｈ' , 'ｉ' ,
	        'ｊ' , 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,
	        'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' ,
	        'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,
	        'ｙ' , 'ｚ' , '－' , '　' , '：' ,
	        '．' , '，' , '／' , '％' , '＃' ,
	        '！' , '＠' , '＆' , '（' , '）' ,
	        '＜' , '＞' , '＂' , '＇' , '？' ,
	        '［' , '］' , '｛' , '｝' , '＼' ,
	        '｜' , '＋' , '＝' , '＿' , '＾' ,
	        '￥' , '￣' , '｀'
	    );
	    $SBC = Array( // 半角
	        '0', '1', '2', '3', '4',
	        '5', '6', '7', '8', '9',
	        'A', 'B', 'C', 'D', 'E',
	        'F', 'G', 'H', 'I', 'J',
	        'K', 'L', 'M', 'N', 'O',
	        'P', 'Q', 'R', 'S', 'T',
	        'U', 'V', 'W', 'X', 'Y',
	        'Z', 'a', 'b', 'c', 'd',
	        'e', 'f', 'g', 'h', 'i',
	        'j', 'k', 'l', 'm', 'n',
	        'o', 'p', 'q', 'r', 's',
	        't', 'u', 'v', 'w', 'x',
	        'y', 'z', '-', ' ', ':',
	        '.', ',', '/', '%', '#',
	        '!', '@', '&', '(', ')',
	        '<', '>', '"', '\'','?',
	        '[', ']', '{', '}', '\\',
	        '|', '+', '=', '_', '^',
	        '$', '~', '`'
	    );
	    return str_replace($DBC, $SBC, $str);  // 全角到半角
	}

	//字符转换为ascii码
	public function asc_encode($c)
	{
	    $len = strlen($c);
	    $a = 0;
	    $scill = "";
	     while ($a < $len)
	     {
	        $ud = 0;
	         if (ord($c{$a}) >=0 && ord($c{$a})<=127)
	         {
	            $ud = ord($c{$a});
	            $a += 1;
	         }
	         else if (ord($c{$a}) >=192 && ord($c{$a})<=223)
	         {
	            $ud = (ord($c{$a})-192)*64 + (ord($c{$a+1})-128);
	            $a += 2;
	         }
	         else if (ord($c{$a}) >=224 && ord($c{$a})<=239)
	         {
	            $ud = (ord($c{$a})-224)*4096 + (ord($c{$a+1})-128)*64 + (ord($c{$a+2})-128);
	            $a += 3;
	         }
	         else if (ord($c{$a}) >=240 && ord($c{$a})<=247)
	         {
	            $ud = (ord($c{$a})-240)*262144 + (ord($c{$a+1})-128)*4096 + (ord($c{$a+2})-128)*64 + (ord($c{$a+3})-128);
	            $a += 4;
	         }
	         else if (ord($c{$a}) >=248 && ord($c{$a})<=251)
	         {
	            $ud = (ord($c{$a})-248)*16777216 + (ord($c{$a+1})-128)*262144 + (ord($c{$a+2})-128)*4096 + (ord($c{$a+3})-128)*64 + (ord($c{$a+4})-128);
	            $a += 5;
	         }
	         else if (ord($c{$a}) >=252 && ord($c{$a})<=253)
	         {
	            $ud = (ord($c{$a})-252)*1073741824 + (ord($c{$a+1})-128)*16777216 + (ord($c{$a+2})-128)*262144 + (ord($c{$a+3})-128)*4096 + (ord($c{$a+4})-128)*64 + (ord($c{$a+5})-128);
	            $a += 6;
	         }
	         else if (ord($c{$a}) >=254 && ord($c{$a})<=255)
	         { //error
	            $ud = 0;
	            $a++;
	         }else{
	             $ud = 0;
	             $a++;
	         }
	         $scill .= "$ud";
	     }
	     return $scill;
	}

    //将字符串转变成hashcode  方案一
	public function hashCode($s){
	    $arr_str = str_split($s);
	    $len = count($arr_str);
	    $hash = 0;
	    for($i=0; $i<$len; $i++){
	        if(ord($arr_str[$i])>127){
	            $ac_str = $arr_str[$i].$arr_str[$i+1].$arr_str[$i+2];
	            $i+=2;
	        }else{
	            $ac_str = $arr_str[$i];
	        }
	       
	        $hash = (int)($hash*31 + $this->asc_encode($ac_str));
	        //64bit下判断符号位
	        if(($hash & 0x80000000) == 0) {
	             //正数取前31位即可
	             $hash &= 0x7fffffff;
	        }
	        else{
	             //负数取前31位后要根据最小负数值转换下
	             $hash = ($hash & 0x7fffffff) - 2147483648;
	        }
	    }
	    return $hash;
	}

	//将字符串转变成hashcode  方案e二
	public function BKDRHash($str)
	{
		$seed = 131; // 31 131 1313 13131 131313 etc..
		$hash = 0;
		
		$cnt = strlen($str);

		for($i = 0; $i < $cnt; $i++)
		{
			
			$hash = ((floatval($hash * $seed) & 0x7FFFFFFF) + ord($str[$i])) & 0x7FFFFFFF;
			
		}
		return ($hash & 0x7FFFFFFF);
	}

}
