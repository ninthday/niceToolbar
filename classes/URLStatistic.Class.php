<?php

/**
 * 分析 URL 的類別
 *
 * @author ninthday <jeffy@ninthday.info>
 * @version 1.0
 * @copyright (c) 2014, Jeffy Shih
 * @version 1.0
 */

namespace niceToolbar;

class URLStatistic {

    private $dbh = NULL;

    /**
     * 建構子包含連線設定
     * @param \Floodfire\myPDOConn $pdoConn myPDOConn object
     */
    public function __construct(\niceToolbar\myPDOConn $pdoConn) {
        $this->dbh = $pdoConn->dbh;
    }
    
    /**
     * 取得資料庫中資料表名稱為 _urls 結尾的資料表名稱，不包含 _urls
     * 
     * @return array 0:資料表名稱
     * @throws \Exception
     * @access public
     * @since version 1.0
     */
    public function getAllURLTableName() {
        $aryRtn = array();
        $sql = "SHOW TABLES LIKE '%_urls' ";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();

            $rs = $stmt->fetchAll(\PDO::FETCH_NUM);
            foreach ($rs as $row) {
                array_push($aryRtn, str_replace('_urls', '', $row[0]));
            }
        } catch (\PDOException $exc) {
            throw new \Exception($exc->getMessage());
        }
        return $aryRtn;
    }

    /**
     * 資料表的基本統計資料
     * 
     * @param array $aryTables 所有要統計的資料表名稱
     * @return array 資料的狀態
     * @access public
     * @since version 1.0
     */
    public function getTablesStatus(array $aryTables) {
        $aryRtn = array();
        foreach ($aryTables as $strTableName) {
            $aryDuration = $this->getDuration($strTableName);
            $aryBasic = $this->getBasicNum($strTableName);
            array_push($aryRtn, array(
                'table_name' => $strTableName,
                'duration' => $aryDuration,
                'basic' => $aryBasic
            ));
        }
        return $aryRtn;
    }

    /**
     * 取得指定資料表最早和最晚一筆的時間
     * 
     * @param type $strTableName 資料表名稱
     * @return type
     * @throws \Exception
     * @access private
     * @since version 1.0
     */
    private function getDuration($strTableName) {
        $sql = "SELECT MIN(`created_at`) AS `begin`, MAX(`created_at`) AS `end` FROM `" . $strTableName . "_urls`";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $rs = $stmt->fetch(\PDO::FETCH_ASSOC);
            $aryRtn = array(
                'begin' => $rs['begin'],
                'end' => $rs['end']
            );
        } catch (\PDOException $exc) {
            throw new \Exception($exc->getMessage());
        }
        return $aryRtn;
    }

    /**
     * 指定資料表的基本資料
     * 
     * @param string $strTableName 資料表名稱
     * @return array total: 總筆數, unshorten: 已短網址還原避暑
     * @throws \Exception
     */
    private function getBasicNum($strTableName) {
        $aryRtn = array();
        try {
            // 取得資料表資料總數
            $sql_total = "SELECT COUNT(*) FROM `" . $strTableName . "_urls`";
            $stmt = $this->dbh->prepare($sql_total);
            $stmt->execute();
            $rs = $stmt->fetch(\PDO::FETCH_NUM);
            $aryRtn['total'] = $rs[0];

            // 取得資料表成功反解短網址的數量
            $sql_code200 = "SELECT COUNT(*) FROM `" . $strTableName . "_urls` WHERE `error_code` = 200";
            $stmt = $this->dbh->prepare($sql_code200);
            $stmt->execute();
            $rs = $stmt->fetch(\PDO::FETCH_NUM);
            $aryRtn['unshorten'] = $rs[0];
        } catch (\PDOException $exc) {
            throw new \Exception($exc->getMessage());
        }
        return $aryRtn;
    }

    /**
     * 解構子歸還資源
     */
    public function __destruct() {
        $this->dbh = NULL;
    }

}
