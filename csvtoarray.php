<?php

class csvtoarray
{
    private $counter;
    private $handler;
    private $length;
    private $file;
    private $seprator;
    private $specialhtml;
    private $removechar = 'manual';
    private $csvDataArr;
    private $csvData = array();

    function __construct($file = '', $csvDataArr = '', $specialhtml = true, $length = 1000, $seprator = ',')
    {
        $this->counter = 0;
        $this->length = $length;
        $this->file = $file;
        $this->seprator =  $seprator;
        $this->specialhtml =  $specialhtml;
        $this->csvDataArr = is_array($csvDataArr) ? $csvDataArr : array();
        $this->handler = fopen($this->file, "r");
    }

    function get_array()
    {
        $getCsvArr = array();
        $csvDataArr = array();
        while(($data = fgetcsv($this->handler, $this->length, $this->seprator)) != FALSE)
        {
            $num = count($data);
            $getCsvArr[$this->counter] = $data;
            $this->counter++;
        }
        if(count($getCsvArr) > 0)
        {
            $csvDataArr = array_shift($getCsvArr);
            if($this->csvDataArr) $csvDataArr = $this->csvDataArr;
            
            $counter = 0;
            foreach($getCsvArr as $csvValue)
            {
                $totalRec = count($csvValue);
                for($i = 0; $i < $totalRec ; $i++)
                {
                    $key = $this->csvDataArr ? $csvDataArr[$i] : $this->remove_char($csvDataArr[$i]);
                    $this->csvData[$counter][$key] = $this->put_special_char($csvValue[$i]);
                }
                $counter++;
            }
        }
        return $this->csvData;
    }
    
    function put_special_char($value)
    {
        return $this->specialhtml ? str_replace(array('&','" ','\'','<','>'),array('&amp;','&quot;','&#039;','&lt;','&gt;'),$value) : $value;
    }
    
    function remove_char($value)
    {
        $result = $this->removechar == 'manual' ? $this->remove_char_manual($value) : $this->remove_char_auto($value);
        return str_replace(' ','_',trim($result));
    }
    
    private function remove_char_manual($value)
    {
        return str_replace(array('&','"','\'','<','>','(',')','%'),'',trim($value));
    }

    private function remove_char_auto($str,$x=0)
    {
        $x==0 ? $str=$this->make_semiangle($str) : '' ; 
        eregi('[[:punct:]]',$str,$arr);
        $str = str_replace($arr[0],'',$str);
    
        return eregi('[[:punct:]]',$str) ? $this->remove_char_auto($str,1) : $str;
    }
    
    private function make_semiangle($str)
    {
        $arr = array('£°' => '0', '£±' => '1', '£²' => '2', '£³' => '3', '£´' => '4',
        '£µ' => '5', '£¶' => '6', '£·' => '7', '£¸' => '8', '£¹' => '9',
        '£Á' => 'A', '£Â' => 'B', '£Ã' => 'C', '£Ä' => 'D', '£Å' => 'E',
        '£Æ' => 'F', '£Ç' => 'G', '£È' => 'H', '£É' => 'I', '£Ê' => 'J',
        '£Ë' => 'K', '£Ì' => 'L', '£Í' => 'M', '£Î' => 'N', '£Ï' => 'O',
        '£Ð' => 'P', '£Ñ' => 'Q', '£Ò' => 'R', '£Ó' => 'S', '£Ô' => 'T',
        '£Õ' => 'U', '£Ö' => 'V', '£×' => 'W', '£Ø' => 'X', '£Ù' => 'Y',
        '£Ú' => 'Z', '£á' => 'a', '£â' => 'b', '£ã' => 'c', '£ä' => 'd',
        '£å' => 'e', '£æ' => 'f', '£ç' => 'g', '£è' => 'h', '£é' => 'i',
        '£ê' => 'j', '£ë' => 'k', '£ì' => 'l', '£í' => 'm', '£î' => 'n',
        '£ï' => 'o', '£ð' => 'p', '£ñ' => 'q', '£ò' => 'r', '£ó' => 's',
        '£ô' => 't', '£õ' => 'u', '£ö' => 'v', '£÷' => 'w', '£ø' => 'x',
        '£ù' => 'y', '£ú' => 'z',
        '£¨' => '(', '£©' => ')', '¡²' => '[', '¡³' => ']', '¡¾' => '[',
        '¡¿' => ']', '¡¼' => '[', '¡½' => ']', '¡°' => '[', '¡±' => ']',
        '¡®' => '[', '¡¯' => ']', '£û' => '{', '£ý' => '}', '¡¶' => '<',
        '¡·' => '>',
        '£¥' => '%', '£«' => '+', '¡ª' => '-', '£­' => '-', '¡«' => '-',
        '£º' => ':', '¡£' => '.', '¡¢' => ',', '£¬' => '.', '¡¢' => '.',
        '£»' => ',', '£¿' => '?', '£¡' => '!', '¡­' => '-', '¡¬' => '|',
        '¡±' => '"', '¡¯' => '`', '¡®' => '`', '£ü' => '|', '¡¨' => '"',
        '¡¡' => ' ','¡ç'=>'$','£À'=>'@','££'=>'#','£Þ'=>'^','£¦'=>'&','£ª'=>'*');
    
        return strtr($str, $arr);
    }
    
    function __destruct(){
        fclose($this->handler);
    }
}
