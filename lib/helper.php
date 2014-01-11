<?php
class BindParam{
    private $values = array(), $types = '';
   
    public function add( $type, &$value ){
        $this->values[] = $value;
        $this->types .= $type;
    }
   
    public function get(){
        return array_merge(array($this->types), $this->values);
    }
} 

function getReferences($arr) {
	$ref = array();
	foreach ($arr as $key => $value)
		$ref[$key] = &$arr[$key];
	return $ref;
}
?>