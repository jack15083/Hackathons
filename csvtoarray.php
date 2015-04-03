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
        $arr = array('��' => '0', '��' => '1', '��' => '2', '��' => '3', '��' => '4',
        '��' => '5', '��' => '6', '��' => '7', '��' => '8', '��' => '9',
        '��' => 'A', '��' => 'B', '��' => 'C', '��' => 'D', '��' => 'E',
        '��' => 'F', '��' => 'G', '��' => 'H', '��' => 'I', '��' => 'J',
        '��' => 'K', '��' => 'L', '��' => 'M', '��' => 'N', '��' => 'O',
        '��' => 'P', '��' => 'Q', '��' => 'R', '��' => 'S', '��' => 'T',
        '��' => 'U', '��' => 'V', '��' => 'W', '��' => 'X', '��' => 'Y',
        '��' => 'Z', '��' => 'a', '��' => 'b', '��' => 'c', '��' => 'd',
        '��' => 'e', '��' => 'f', '��' => 'g', '��' => 'h', '��' => 'i',
        '��' => 'j', '��' => 'k', '��' => 'l', '��' => 'm', '��' => 'n',
        '��' => 'o', '��' => 'p', '��' => 'q', '��' => 'r', '��' => 's',
        '��' => 't', '��' => 'u', '��' => 'v', '��' => 'w', '��' => 'x',
        '��' => 'y', '��' => 'z',
        '��' => '(', '��' => ')', '��' => '[', '��' => ']', '��' => '[',
        '��' => ']', '��' => '[', '��' => ']', '��' => '[', '��' => ']',
        '��' => '[', '��' => ']', '��' => '{', '��' => '}', '��' => '<',
        '��' => '>',
        '��' => '%', '��' => '+', '��' => '-', '��' => '-', '��' => '-',
        '��' => ':', '��' => '.', '��' => ',', '��' => '.', '��' => '.',
        '��' => ',', '��' => '?', '��' => '!', '��' => '-', '��' => '|',
        '��' => '"', '��' => '`', '��' => '`', '��' => '|', '��' => '"',
        '��' => ' ','��'=>'$','��'=>'@','��'=>'#','��'=>'^','��'=>'&','��'=>'*');
    
        return strtr($str, $arr);
    }
    
    function __destruct(){
        fclose($this->handler);
    }
}
