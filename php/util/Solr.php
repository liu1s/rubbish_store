<?php
class Util_Solr {
    public $query_params = array();

    public function set_query_q ($value) {
        $this->query_params['q'] = $value;
    }

    public function add_query_fq ($value) {
        $this->query_params['fq'][] = $value;
    }

    public function set_dismax_model () {
        $this->query_params['qt'] = "dismax";
    }

    public function set_query_qf ($value) {
        $this->query_params['qf'] = $value;
    }

    public function set_query_sort ($value) {
        $this->query_params['sort'] = $value;
    }

    public function set_query_start ($value) {
        $this->query_params['start'] = $value;
    }

    public function set_query_rows ($rows) {
        $this->query_params['rows'] = $rows;
    }

    public function set_query_wt ($value) {
        $this->query_params['wt'] = $value;
    }
    
	public function set_facet_block($limit, $facet_field,  $facet="true", $mincount = 0) {		// lewis added at 20090817 for t2541
    	$this->query_params['facet'] = $facet;
    	$this->query_params['facet.field'] = $facet_field;
    	$this->query_params['facet.limit'] = $limit;
        $this->query_params['facet.mincount'] = $mincount;
    	
    }
    
    public function set_facet_areacode($areacode) {				// lewis added at 20090817 for t2541
    	if($areacode){
    		$this->query_params['facet.query'][] = "areacode:$areacode*";
    	}
    }
    
    public function set_facet_blockcode($blockcode) {				// lewis added at 20090817 for t2541
    	$this->query_params['fq'][] = "areacode:$blockcode";
    }

    public function build_query_uri($fl=''){
        $url = "fl=$fl"; //var_dump($this->query_params);
        foreach($this->query_params as $key => $value) {
            if(is_array($value)) {
                foreach($value as $val) {
                    $url .= "&".urlencode($key)."=".urlencode($val);
                }
            } else {
                $url .= "&".urlencode($key)."=".urlencode($value);
            }
        }
        return $url;
    }

    /**
     * 当你因为房源信息不能正常展示而跟进到这个方法的时候,也许你得到的列表数据不尽人意. 
     * 但是恭喜你,你已经找到了简单而快捷的解决方法(由于系统更换了二手房房源solr存储方式,并且调用的是好租api来获取好租房源,这种方法能在很多地方实用). 
     * 请尝试下面的方式修复数据吧:
     * 
     * 如果是获取租房列表或$rank=false,在得到返回结果的时候,请在后面加上:
	 * apf_require_class("Search_SolrPropDataHandler");
	 * $converter = new Search_SolrPropDataHandler();
     * $resArray["response"]["docs"] = $converter->convertHaozuApiToProps($resArray["response"]["docs"]);
     * 
     * 如果是获取二手房列表或$rank=true,在得到返回结果的时候,请在后面加上:
	 * apf_require_class("Search_SolrPropDataHandler");
	 * $converter = new Search_SolrPropDataHandler();
	 * $resArray = $converter->convertPropToCacheSearchData($resArray);
	 *
	 * 然后继续使用完整而原始的$resArray吧!!!
	 * 
	 * $solr_url 获取方式参考:app-anjuke/classes/solr/Properties.php#build_solr_url($rank = false)
     */
    public static function get_solr_data ($solr_url) {
        $curl = curl_init($solr_url);
        APF::get_instance ()->debug ( urldecode($solr_url) );
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type:text/xml; charset=utf-8"));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl , CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        $res = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        //print_r($info);
        if ($info['http_code'] != 200) {
            return array();
        }
        $resArray = json_decode($res,true);
        return $resArray;
    }

    public static function get_solr_data2 ($solr_url) {
        apf_require_class("APF_Http_Client_Factory");
        $curl = APF_Http_Client_Factory::get_instance()->get_curl();
        $curl->set_url ($solr_url);
        $r = $curl->execute();
        $res = $curl->get_response_text();
        $resArray = json_decode($res,true);
        if ($r) {
            return $resArray;
        } else {
            return array();
        }
    }

    public static function build_update_xml ($row) {
        $xw = new xmlWriter();
        $xw->openMemory();
        $xw->startDocument('1.0', 'UTF-8');
        $xw->startElement('add');
        $xw->startElement('doc');
        foreach ($row as $key=>$value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $xw->startElement('field');
                    $xw->writeAttribute('name', $key);
                    $xw->writeCdata($v);
                    $xw->endElement();
                }
            } else {
                $xw->startElement('field');
                $xw->writeAttribute('name', $key);
                $xw->writeCdata($value);
                $xw->endElement();
            }
        }
        $xw->endElement();
        $xw->endElement();
        $xw->endDocument();
        $xml = $xw->outputMemory(true);
        return $xml;
    }
    
    public function set_metro_id ($metro_id) {
        if ($metro_id) {
            $this->query_params['fq'][] = "metro_id:" . $metro_id;
        }
    }
    
    public function set_metro_station_id ($station_id) {
        if ($station_id) {
            $this->query_params['fq'][] = "metro_station_id:" . $station_id;
        }
    }
    
    public function set_metro_distance ($metro_id, $station_id, $start, $end) {
        if ($station_id) {
            $this->query_params['fq'][] = "metro_station_distance:[$start TO $end]";
        } elseif ($metro_id) {
            $this->query_params['fq'][] = "metro_line_distance:[$start TO $end]";
        } else {
            $this->query_params['fq'][] = "metro_distance:[$start TO $end]";
        }
    }
    
    public function set_min_propnum($propnum){
    	$this->query_params['fq'][] = "sale_num:[$propnum TO *]";
    }
}
?>
