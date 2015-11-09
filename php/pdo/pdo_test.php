<?php
/**
 * Author: 疯牛
 * Time: 15/11/9 下午4:41
 * Email: liu1s0404@outlook.com
 */

/**
 * $pdo->beginTransaction();
 * $pmi = new PDOMultiLineInserter($pdo, "foo", array("a","b","c","e"), 10);
 * $pmi->insertRow($data);
 * ....
 * $pmi->insertRow($data);
 * $pmi->purgeRemainingInserts();
 * $pdo->commit();
 *
 */
class PDOMultiLineInserter {
    private $_purgeAtCount;
    private $_bigInsertQuery, $_singleInsertQuery;
    private $_currentlyInsertingRows  = array();
    private $_currentlyInsertingCount = 0;
    private $_numberOfFields;
    private $_error;
    private $_insertCount = 0;

    function __construct(\PDO $pdo, $tableName, $fieldsAsArray, $bigInsertCount = 100) {
        $this->_numberOfFields = count($fieldsAsArray);
        $insertIntoPortion = "INSERT INTO `$tableName` (`".implode("`,`", $fieldsAsArray)."`) VALUES";
        $questionMarks  = " (?".str_repeat(",?", $this->_numberOfFields - 1).")";

        $this->_purgeAtCount = $bigInsertCount;
        $this->_bigInsertQuery    = $pdo->prepare($insertIntoPortion.$questionMarks.str_repeat(", ".$questionMarks, $bigInsertCount - 1));
        $this->_singleInsertQuery = $pdo->prepare($insertIntoPortion.$questionMarks);
    }

    function insertRow($rowData) {
        // @todo Compare speed
        // $this->_currentlyInsertingRows = array_merge($this->_currentlyInsertingRows, $rowData);
        foreach($rowData as $v) array_push($this->_currentlyInsertingRows, $v);
        //
        if (++$this->_currentlyInsertingCount == $this->_purgeAtCount) {
            if ($this->_bigInsertQuery->execute($this->_currentlyInsertingRows) === FALSE) {
                $this->_error = "Failed to perform a multi-insert (after {$this->_insertCount} inserts), the following errors occurred:".implode('<br/>', $this->_bigInsertQuery->errorInfo());
                return false;
            }
            $this->_insertCount++;

            $this->_currentlyInsertingCount = 0;
            $this->_currentlyInsertingRows = array();
        }
        return true;
    }

    function purgeRemainingInserts() {
        while ($this->_currentlyInsertingCount > 0) {
            $singleInsertData = array();
            // @todo Compare speed - http://www.evardsson.com/blog/2010/02/05/comparing-php-array_shift-to-array_pop/
            // for ($i = 0; $i < $this->_numberOfFields; $i++) $singleInsertData[] = array_pop($this->_currentlyInsertingRows); array_reverse($singleInsertData);
            for ($i = 0; $i < $this->_numberOfFields; $i++) array_unshift($singleInsertData, array_pop($this->_currentlyInsertingRows));

            if ($this->_singleInsertQuery->execute($singleInsertData) === FALSE) {
                $this->_error = "Failed to perform a small-insert (whilst purging the remaining rows; the following errors occurred:".implode('<br/>', $this->_singleInsertQuery->errorInfo());
                return false;
            }
            $this->_currentlyInsertingCount--;
        }
    }

    public function getError() {
        return $this->_error;
    }
}