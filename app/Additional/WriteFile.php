<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 23:06
 */
namespace App\Additional;

class WriteFile
{
    private $array = [];
    private $lang = "";
    private $file = "";
    private $realArray = [];

    public function __construct($lang,$file){
        $this->array = [];
        $this->lang = $lang;
        $this->file = $file;
        if (file_exists(dirname(__DIR__)."/../resources/lang/".$lang."/".$file)){
            $handle = fopen(dirname(__DIR__)."/../resources/lang/".$lang."/".$file,"r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (!empty($line)) $this->array[] = $line;
                }
                fclose($handle);
            }
            unset($this->array[count($this->array)-1]);
        }
        else{
            $this->array = ["<?php \n","return [\n"];
        }

    }

    public function add($array){
        for ($i=0;$i<count($array);$i++)
            $this->array[] = $array[$i];
        $array = $this->array;
        $array[] = "];";
        file_put_contents(dirname(__DIR__)."/../resources/lang/".$this->lang."/".$this->file, implode("",$array));
//        chmod(dirname(__DIR__)."/../resources/lang/".$this->lang."/".$this->file, 0777);
    }

    public function remove($key){
        for($i=0;$i<count($this->array);$i++){
            $explode = explode("'",$this->array[$i]);
            if ($explode[1]==$key)
                unset($this->array[$i]);
        }
        $array = $this->array;
        $array[] = "];";
        file_put_contents(dirname(__DIR__)."/../resources/lang/".$this->lang."/".$this->file, implode("",$array));
    }

    public function changeKey($keyOld,$keyNew, $value = ""){
        for($i=0;$i<count($this->array);$i++){
            $explode = explode("'",$this->array[$i]);
            if (isset($explode[1])&&isset($explode[3])&&$explode[1]==$keyOld) {
                if (empty($value)) $value = $explode[3];
                unset($this->array[$i]);
            }
        }
        $this->add(["\t'$keyNew'=>'$value',\n"]);
    }

    public function changeValue($key,$value){
        for($i=0;$i<count($this->array);$i++){
            $explode = explode("'",$this->array[$i]);
            if (isset($explode[1])&&isset($explode[3])&&$explode[1]==$key){
                unset($this->array[$i]);
            }
        }
        $this->add(["\t'".$key."'=>'".$value."',\n"]);
    }

    private function performValues(){
        for($i=0;$i<count($this->array);$i++){
            $explode = explode("'",$this->array[$i]);
            if (isset($explode[1])&&isset($explode[3])) {
                $this->realArray[$explode[1]] = $explode[3];
            }
        }
    }

    public function getValues(){
        if (empty($this->realArray))
            $this->performValues();
        return $this->realArray;
    }

    public function getKeys(){
        if (empty($this->realArray))
            $this->performValues();
        return array_keys($this->realArray);
    }

    public function getKeysWithFilename(){
        if (empty($this->realArray))
            $this->performValues();
        $array = array_keys($this->realArray);
        $file = explode('.',$this->file);
        unset($file[count($file)-1]);
        for($i=0;$i<count($array);$i++){
            $array[$i] = implode('',$file).'.'.$array[$i];
        }
        return $array;
    }

}