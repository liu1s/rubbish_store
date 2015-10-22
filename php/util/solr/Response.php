<?php

class Util_Solr_Response
{
    private $response;
    private $responseHeader = array();

    private $query = null;
    private $numFound = 0;
    private $start = 0;
    private $documents = array();

    private $success = true;
    private $transferInfo = array();

    public function isSucceeded()
    {
        return $this->success;
    }

    public function isFailed()
    {
        return !$this->success;
    }

    public function succeed()
    {
        $this->success = true;
        return $this;
    }

    public function fail()
    {
        $this->success = false;
        return $this;
    }

    public function setTransferInfo($transferInfo)
    {
        $this->transferInfo = $transferInfo;
        return $this;
    }

    public function getTransferInfo()
    {
        return $this->transferInfo;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;

        if (is_string($response)) {
            $response = json_decode($response, true);
            // 解析JSON成功
            if($response) {
                $this->response = $response;

                if (isset($response['responseHeader'])) {
                    $this->setResponseHeader($response['responseHeader']);
                }

                if (isset($response['response'])) {
                    $this->setNumFound($response['response']['numFound']);
                    $this->setStart($response['response']['start']);
                    $this->setDocuments($response['response']['docs']);
                }
            }
        }
        return $this;
    }

    public function setQuery(Util_Solr_Query $query)
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getResponseHeader()
    {
        return $this->responseHeader;
    }

    public function setResponseHeader($responseHeader)
    {
        $this->responseHeader = $responseHeader;

        return $this;
    }

    public function setNumFound($numFound)
    {
        $this->numFound = $numFound;
        return $this;
    }

    public function getNumFound()
    {
        return $this->numFound;
    }

    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getDocs()
    {
        return $this->getDocuments();
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function addDoc($doc) {
        return $this->addDocument($doc);
    }

    public function addDocument($document)
    {
        if (is_array($document)) {
            $document = new Util_Solr_Document($document);
        } elseif (!($document instanceof Util_Solr_Document)) {
            throw new Util_Solr_Exception('不合法的参数：支持数组和Util_Solr_Document对象');
        }

        $this->documents[] = $document;
        return $this;
    }

    public function setDocs(array $docs)
    {
        return $this->setDocs($docs);
    }

    public function setDocuments(array $documents)
    {
        foreach ($documents as $document) {
            $this->addDocument($document);
        }

        return $this;
    }
}
