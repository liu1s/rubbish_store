<?php
class Util_MultiPager {
    public static function multiPage($uriparams,$kw,$totalrow,$pagesize,$shownum,$uriprefix,$simple = false) {
        $kw = urlencode($kw);
        $shownum = $shownum - 1;
        if($totalrow > $pagesize) {
            $pagenum = ceil($totalrow/$pagesize);
        } else {
            $pagenum = 1;
        }
        if(isset($uriparams[Const_Listing::PARAM_N_PAGE])) {
            $page = $uriparams[Const_Listing::PARAM_N_PAGE];
        } else {
            $page = 1;
        }
        $startnum = $page - floor($shownum/2);
        if($startnum < 1) {
            $startnum = 1;
        }
        $endnum = $startnum + $shownum;
        if($endnum > $pagenum) {
            $endnum = $pagenum;
            $startnum = $endnum - $shownum;
        }
        if($startnum < 1) {
            $startnum = 1;
        }
        $list = array();
        for($i=$startnum;$i<=$endnum;$i++) {
            $uriparams[Const_Listing::PARAM_N_PAGE] = $i;
            if ($uriparams[Const_Listing::PARAM_N_PAGE] <= 1) {
            	unset($uriparams[Const_Listing::PARAM_N_PAGE]);
            }
            $row['link'] = $uriprefix.$kw.APF_Util_StringUtils::encode_seo_parameters($uriparams);
            $row['page'] = $i;
            if($row['page'] == $page) {
                $row['selected'] = 1;
            } else {
                $row['selected'] = 0;
            }
            $list[] = $row;
        }
        $multipage = array("list"=>$list);
        if(!$simple) {
            if($startnum>1) {
                $multipage['firstpage'] = self::getPage(1,$uriparams,$uriprefix,$kw);
            } else {
                $multipage['firstpage'] = null;
            }
        }
        if($page > 1) {
            $multipage['prexpage'] = self::getPage($page - 1,$uriparams,$uriprefix,$kw);
        } else {
            $multipage['prexpage'] = null;
        }
        if($page < $pagenum) {
            $multipage['nextpage'] = self::getPage($page + 1,$uriparams,$uriprefix,$kw);
        } else {
            $multipage['nextpage'] = null;
        }
        if(!$simple) {
            if($endnum < $pagenum) {
                $multipage['endpage'] = self::getPage($pagenum,$uriparams,$uriprefix,$kw);
            } else {
                $multipage['endpage'] = null;
            }
        }
        if(!$simple) {
            $multipage['totalrow'] = $totalrow;
            $multipage['totalpage'] = $pagenum;
            $multipage['page'] = $page;
        }
        unset($uriparams[Const_Listing::PARAM_N_PAGE]);
        if(!$simple) {
            $uriparams[Const_Listing::PARAM_N_PAGE] = "";
            $multipage['jumplink'] = $uriprefix.$kw.APF_Util_StringUtils::encode_seo_parameters($uriparams);
        }
        return $multipage;
    }
    public static function getPage($page , $uriparams ,$uriprefix , $kw) {
        $firstpage = array();
        $firstpage['page'] = $page ;
        $uriparams[Const_Listing::PARAM_N_PAGE] = $page;
        if ($uriparams[Const_Listing::PARAM_N_PAGE] <= 1) {
        	unset($uriparams[Const_Listing::PARAM_N_PAGE]);
        }
        $firstpage['link'] = $uriprefix.$kw.APF_Util_StringUtils::encode_seo_parameters($uriparams);
        return $firstpage;
    }

    public static function getNextPage($page , $uriparams, $uriprefix , $kw) {
        $nextpage = array();
        return $nextpage;
    }
}
?>