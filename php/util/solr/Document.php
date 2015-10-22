<?php

/**
 * Solr查询结果文档
 */
class Util_Solr_Document implements ArrayAccess
{
    private $fields;

    public static function instance($fields = array())
    {
        return new self($fields);
    }

    public function __construct($fields = array())
    {
        $this->setFields($fields);
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function addField($name, $value)
    {
        $this->fields[$name] = $value;

        return $this;
    }

    public function removeField($name)
    {
        unset($this->fields[$name]);

        return $this;
    }

    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->fields[$offset];
        } else {
            return null;
        }
    }

    public function offsetSet($offset, $value)
    {
        $this->fields[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->fields[$offset]);
    }
}
