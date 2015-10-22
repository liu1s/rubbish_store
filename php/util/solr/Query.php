<?php

/**
 * Solr查询参数
 */
class Util_Solr_Query
{
    private $params;

    public function __construct()
    {
        $this->params['q'] = '*:*';
        $this->params['wt'] = 'json';
    }

    public function get($name)
    {
        return $this->getParam($name);
    }

    public function getParam($name)
    {
        return $this->params[$name];
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getPreparedParams()
    {
        $params = $this->params;

        foreach ($params as &$param) {
            if (is_array($param)) {
                foreach ($param as &$subParam) {
                    $subParam = rawurlencode($subParam);
                }
            } else {
                $param = rawurlencode($param);
            }
        }

        return $params;
    }

    public function add($name, $value)
    {
        return $this->addParam($name, $value);
    }

    public function addParam($name, $value)
    {
        $this->params[$name][] = $value;
        return $this;
    }

    public function set($name, $value)
    {
        return $this->setParam($name, $value);
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /************** specific *****************/

    public function q($value = null)
    {
        if (is_null($value)) {
            return $this->getQuery();
        }

        return $this->setQuery($value);
    }

    public function setQuery($value)
    {
        return $this->setParam('q', $value);
    }

    public function getQuery()
    {
        return $this->getParam('query');
    }

    public function fq($value = null)
    {
        if (is_null($value)) {
            return $this->getFilterQuery();
        }

        return $this->addFilterQuery($value);
    }

    public function addFilterQuery($value)
    {
        return $this->addParam('fq', $value);
    }

    public function setFilterQuery($value)
    {
        return $this->setParam('fq', $value);
    }

    public function getFilterQuery()
    {
        return $this->getParam('fq');
    }

    public function sort($value = null)
    {
        if (is_null($value)) {
            return $this->getSort();
        }

        return $this->addSort($value);
    }

    public function addSort($value)
    {
        return $this->addParam('sort', $value);
    }

    public function setSort($value)
    {
        return $this->setParam('sort', $value);
    }

    public function getSort()
    {
        return $this->getParam('sort');
    }

    public function fl($value = null)
    {
        if (is_null($value)) {
            return $this->getField();
        }

        return $this->addField($value);
    }

    public function addField($value)
    {
        return $this->addParam('fl', $value);
    }

    public function setField($value)
    {
        return $this->setField($value);
    }

    public function getField()
    {
        return $this->getParam('fl');
    }

    public function isDebugEnabled()
    {
        return $this->getParam('debug') == 'true';
    }

    public function enableDebug()
    {
        return $this->setParam('debug', 'true');
    }

    public function disableDebug()
    {
        return $this->setParam('debug', 'false');
    }

    public function isCacheEnabled()
    {
        return $this->getParam('cache') == 'true';
    }

    public function enableCache()
    {
        return $this->setParam('cache', 'true');
    }

    public function disableCache()
    {
        return $this->setParam('cache', 'false');
    }

    public function timeAllowed($value = null)
    {
        if (is_null($value)) {
            return $this->getTimeAllowed();
        }

        return $this->setTimeAllowed($value);
    }

    public function setTimeAllowed($value)
    {
        return $this->setParam('timeAllowed', $value);
    }

    public function getTimeAllowed()
    {
        return $this->getParam('timeAllowed');
    }

    public function isOmitHeader()
    {
        return $this->getParam('omitHeader') == 'true';
    }

    public function enableOmitHeader()
    {
        return $this->setParam('omitHeader', 'true');
    }

    public function disableOmitHeader()
    {
        return $this->setParam('omitHeader', 'false');
    }

    public function wt($value = null)
    {
        if (is_null($value)) {
            return $this->getResponseFormat();
        }

        return $this->setResponseFormat($value);
    }

    public function setResponseFormat($value)
    {
        if (!in_array($value, array('json'))) {
            throw new Util_Solr_Exception('暂时只支持JSON格式');
        }

        return $this->setParam('wt', $value);
    }

    public function getResponseFormat()
    {
        return $this->getParam('wt');
    }

    public function toString($urlEncode = false)
    {
        $params = $urlEncode ? $this->getPreparedParams() : $this->params;

        $pairs = array();
        foreach ($params as $name => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            $pairs[] = sprintf('%s=%s', $name, $value);
        }

        return implode('&', $pairs);
    }

}
