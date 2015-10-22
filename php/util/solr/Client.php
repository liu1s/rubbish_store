<?php

class Util_Solr_Client
{
    const DELETE_BY_ID = 1;
    const DELETE_BY_QUERY = 2;

    private $options;
    private static $instances = array();

    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * 获取Solr服务实例
     *
     * @param $service
     * @return self
     */
    public static function getInstance($service)
    {
        if (!isset(self::$instances[$service])) {
            self::$instances[$service] = new self(self::loadConfig($service));
        }

        return self::$instances[$service];
    }

    /**
     * 获取Solr服务的配置信息
     *
     * @param $service
     *
     * @return array
     */
    public static function loadConfig($service)
    {
        $config = APF::get_instance()->get_config($service, 'solr');
        if (!$config) {
            throw new Util_Solr_Exception('无法找到Solr服务的配置');
        }

        return $config;
    }

    /**
     * 获取Solr查询接口的链接
     */
    public function getSelectUrl()
    {
        return sprintf('%s%s:%s/%s/%s/',
            $this->getScheme(),
            $this->getHost(),
            $this->getPort(),
            $this->getService(),
            $this->getSelectPath()
        );
    }

    /**
     * 获取Solr更新索引的链接
     */
    private function getUpdateUrl()
    {
        return sprintf('%s%s:%s/%s/%s/',
            $this->getScheme(),
            $this->getHost(),
            $this->getPort(),
            $this->getService(),
            $this->getUpdatePath()
        );
    }

    public function getScheme()
    {
        return 'http://';
    }

    public function getHost()
    {
        return $this->options['host'];
    }

    public function getPort()
    {
        return $this->options['port'];
    }

    public function getService()
    {
        return $this->options['service'];
    }

    public function getSelectPath()
    {
        return isset($this->options['select_path']) ? $this->options['select_path'] : 'select';
    }

    public function getUpdatePath()
    {
        return isset($this->options['update_path']) ? $this->options['update_path'] : 'update';
    }

    public function createQuery()
    {
        return new Util_Solr_Query();
    }

    public function query(Util_Solr_Query $query)
    {
        $url = $this->getSelectUrl() . '?' . $query->toString(true);

        $curl = new Util_Http_Curl();
        $response = $curl->setDefaults()
            ->get($url);

        $httpResponse = new Util_Solr_Response();
        $httpResponse->setTransferInfo($curl->getTransferInfo())
            ->setQuery($query);

        if ($response === false) {
            $httpResponse->fail();
        } else {
            if ($curl->getTransferInfo('http_code') == '200') {
                $httpResponse->succeed()
                    ->setResponse($response);
            } else {
                $httpResponse->fail()
                    ->setResponse($response);
            }
        }

        return $httpResponse;
    }

    public function add($docs)
    {
        if (!is_array($docs)) {
            $docs = array($docs);
        }

        $url = $this->getUpdateUrl() . '?wt=json';
        $data = $this->buildAddXml($docs);

        return $this->post($url, $data);
    }

    public function commit()
    {
        $url = $this->getUpdateUrl() . '?wt=json';
        $data = $this->buildCommitXml();

        return $this->post($url, $data);
    }

    public function optimize()
    {
        $url = $this->getUpdateUrl() . '?wt=json';
        $data = $this->buildOptimizeXml();

        return $this->post($url, $data);
    }

    public function rollback()
    {
        $url = $this->getUpdateUrl() . '?wt=json';
        $data = $this->buildRollbackXml();

        return $this->post($url, $data);
    }

    public function deleteById($id)
    {
        return $this->deleteByIds(array($id));
    }

    public function deleteByIds(array $ids)
    {
        $url = $this->getUpdateUrl() . '?wt=json';
        $data = self::buildDeleteXml($ids, self::DELETE_BY_ID);

        return $this->post($url, $data);
    }

    public function deleteByQuery($query)
    {
        return $this->deleteByQueries(array($query));
    }

    public function deleteByQueries(array $queries)
    {
        $url = $this->getUpdateUrl() . '?wt=json';
        $data = self::buildDeleteXml($queries, self::DELETE_BY_QUERY);

        return $this->post($url, $data);
    }

    private function post($url, $data)
    {
        $curl = new Util_Http_Curl();
        $response = $curl->setDefaults()
            ->addHeader('Content-Type', 'application/xml')
            ->addOption(CURLOPT_USERAGENT, $this->getUserAgent())
            ->addOption(CURLOPT_POSTFIELDS, $data)
            ->post($url);

        $httpResponse = new Util_Solr_Response();
        $httpResponse->setTransferInfo($curl->getTransferInfo());

        if ($response === false) {
            $httpResponse->fail();
        } else {
            if ($curl->getTransferInfo('http_code') == '200') {
                $httpResponse->succeed()
                    ->setResponse($response);
            } else {
                $httpResponse->fail()
                    ->setResponse($response);
            }
        }

        return $httpResponse;
    }

    /**
     * 生成 UserAgent
     *
     * 例如：UtilSolrClient/1.0 (Jacks-MacBook-Pro.local/192.168.202.106; PHP/5.4.30; CURL/7.30.0;)
     *
     * @return string
     */
    public function getUserAgent()
    {
        $curlVersion = curl_version();
        return sprintf('%s/1.0 (%s/%s; PHP/%s; CURL/%s;)',
            str_replace('_', '', __CLASS__),
            gethostname(),
            gethostbyname(gethostname()),
            phpversion(),
            $curlVersion['version']
        );
    }

    private function buildCommitXml($attributes = array())
    {
        $xml = '<commit';
        foreach ($attributes as $attribute => $value) {
            $value = $value ? 'true' : 'false';
            $xml .= ' ' . $attribute . '="' . $value . '"';
        }
        $xml .= '/>';

        return $xml;
    }

    private function buildRollbackXml()
    {
        return '<rollback/>';
    }

    private function buildAddXml(array $docs)
    {
        // TODO
        $xml = '<add>';

        foreach ($docs as $doc) {
            if (!($doc instanceof Util_Solr_Document)) {
                throw new Util_Solr_Exception('Invalid document, need objects of Util_Solr_Document.');
            }

            $xml .= $this->buildDocXml($doc);

        }

        $xml .= '</add>';

        return $xml;
    }

    private function buildDocXml(Util_Solr_Document $doc)
    {
        $fields = $doc->getFields();

        $xml = '<doc>';

        foreach ($fields as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $multiVal) {
                    $xml .= $this->buildFieldXml($name, $multiVal);
                }
            } else {
                $xml .= $this->buildFieldXml($name, $value);
            }
        }

        $xml .= '</doc>';

        return $xml;
    }

    /**
     * @param array $items
     * @param int $type
     *
     * @return string
     */
    private function buildDeleteXml(array $items, $type)
    {
        $xml = '<delete>';
        foreach ($items as $item) {
            switch ($type) {
                case self::DELETE_BY_ID:
                    $xml .= '<id>' . htmlspecialchars($item, ENT_NOQUOTES, 'UTF-8') . '</id>';
                    break;
                case self::DELETE_BY_QUERY:
                    $xml .= '<query>' . htmlspecialchars($item, ENT_NOQUOTES, 'UTF-8') . '</query>';
                    break;
            }
        }
        $xml .= '</delete>';

        return $xml;
    }

    private function buildFieldXml($name, $value)
    {
        $xml = '<field name="' . $name . '">';
        $xml .= htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
        $xml .= '</field>';

        return $xml;
    }

    private function buildOptimizeXml($attributes = array())
    {
        $xml = '<optimize';
        foreach ($attributes as $attribute => $value) {
            $value = $value ? 'true' : 'false';
            $xml .= ' ' . $attribute . '="' . $value . '"';
        }
        $xml .= '/>';

        return $xml;
    }
}
