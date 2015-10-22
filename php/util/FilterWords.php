<?php

class Util_FilterWords
{
    private $wordsArr = array();
    // 初始化分类数组容器
    private $classArr = array();

    public function __construct()
    {
        $strKey = 'Util_FilterWords_v2';
        $objMemcache = APF_Cache_Factory::get_instance()->get_memcache();
        $this->classArr = $objMemcache->get($strKey);
        if (empty($this->classArr)) {
            // 获取词库
            $this->wordsArr = $this->get_keywords();
            // 分别对每个关键词进行首字符分析分类
            foreach ($this->wordsArr as $v) {
                $v = trim($v['WORD']);
                $oneStr = mb_substr($v, 0, 1, 'UTF-8');
                if (!isset($this->classArr[$oneStr])) {
                    $this->classArr[$oneStr] = array();
                }
                if (!in_array($v, $this->classArr[$oneStr])) {
                    $this->classArr[$oneStr][] = $v;
                }
            }
            $objMemcache->set($strKey, $this->classArr, 0, 86400 * 30);
        }
    }

    public function get_keywords()
    {
        $pdo = APF_DB_Factory::get_instance()->get_pdo("bbs_slave");
        $sql = "select `WORD` from ban_words";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param string $content 要过滤的内容，字符串
     * @param string $replace 违禁词替换为＊号
     * @return mixed|string
     */
    public function exec($content, $replace = '*')
    {
        $filter = false;
        $content = str_replace(array("\r", "\n"), "", $content);
        if (class_exists('ZMQContext')) {
            $data = Util_StringUtils::patternsMatcher('stringmatch/stringMatchService/search', array($content), 500, false, 'multi_string_filter_host');
        } else {
            $data['status'] = 100;
        }

        if ($data['status'] == 200) {
            $filter = true;
            foreach ($data['reply'] as $v) {
                $content = str_replace($v[0], str_repeat($replace, mb_strlen($v[0], 'UTF-8')), $content);
            }
        }

        if (!$filter) $content = $this->replace($content, $replace);

        return $content;
    }

    public function replace($content, $replace = '*')
    {
        // 计算文本内容长度
        $cLength = mb_strlen($content, 'UTF-8');
        $words = array();
        // 对每个字符进行关键词检查
        for ($i = 0; $i < $cLength; $i++) {
            $words[] = mb_substr($content, $i, 1, 'UTF-8');
        }
        // 对每个字符进行关键词检查
        foreach ($words as $i => $word) {
            // 首字符是否在过滤关键词分类中存在
            if (isset($this->classArr[$word])) {
                // 如果存在则将该分类下的所有关键词遍历，依次对文本内容中与每一个关键词位置长度相对应的字符串进行比较
                foreach ($this->classArr[$word] as $v) {
                    // 过滤关键词长度
                    $filterWordLength = mb_strlen($v, 'UTF-8');
                    // 获取文本中对应位置，相同长度的字符串
                    $contentEqPostionWord = mb_substr($content, $i, $filterWordLength, 'UTF-8');
                    // 对两个字符串进行比较
                    if ($contentEqPostionWord == $v) {
                        // 如果相同，则将该段字符串替换成相应长度的 *
                        array_splice($words, $i, $filterWordLength, array_fill(0, $filterWordLength, $replace));
                    }
                }
            }
        }
        return implode('', $words);
    }

    public function replace_new($content, $replace = '*')
    {
        $result = '';
        $i = 0;
        // 计算文本内容长度
        $length = mb_strlen($content, 'UTF-8');

        // 对每个字符进行关键词检查
        while (true) {
            // 处理完所有的字符就退出
            if ($i >= $length) break;
            // 获取单个字
            $word = mb_substr($content, $i, 1, 'UTF-8');
            // 首字符是否在过滤关键词分类中存在
            if (isset($this->classArr[$word])) {
                // 如果存在则将该分类下的所有关键词遍历，依次对文本内容中与每一个关键词位置长度相对应的字符串进行比较
                foreach ($this->classArr[$word] as $v) {
                    // 过滤关键词长度
                    $filterWordLength = mb_strlen($v, 'UTF-8');
                    $n = $i;
                    $j = 1;
                    $str = $word;
                    while (true) {
                        // 达到获取指定的长度
                        if ($j >= $filterWordLength) break;
                        $next = mb_substr($content, ++$n, 1, 'UTF-8');
                        if (strlen($next) == 0) break;
                        if ($this->is_chinese($next)) {
                            $str .= $next;
                            $j++;
                        }
                        $i++;
                    }
                    // 对两个字符串进行比较
                    if ($str == $v) {
                        $result .= str_repeat($replace, mb_strlen($str, 'UTF-8'));
                    } else {
                        $result .= $str;
                    }
                    $i++;
                }
            } else {
                $result .= $word;
                $i++;
            }

        }

        return $result;
    }

    public function is_chinese($str)
    {
        return preg_match('%^(?:
              [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
            )*$%xs', $str);
    }
}
