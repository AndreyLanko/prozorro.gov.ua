<?php namespace Perevorot\Longread\Classes;

use ArrayAccess;

class AttachFileRelationArray implements ArrayAccess
{
    private $container=[];
    private $original;
    private $data;
    private $type;

    public function __construct($original, $data, $type)
    {
        $this->original=$original;
        $this->data=$data;
        $this->type=$type;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
            $this->container[]=$value;
        else
            $this->container[$offset]=$value;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->original) || $this->checkField($offset);
    }

    public function offsetUnset($offset)
    {
        unset($this->original[$offset]);
    }

    public function offsetGet($offset)
    {
        if(!empty($this->original[$offset]))
            return $this->original[$offset];
        elseif($this->checkField($offset))
            return $this->data;
        else
            return null;
    }

    private function checkField($offset)
    {
        return starts_with($offset, '__longread_'.$this->type.'_');
    }
}
