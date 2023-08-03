<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Models\MyDBNamesModel;
use App\Models\MyLibzDBModel;
use App\Models\MyLibzSysModel;

class MyDatumModel extends Model
{
	public function __construct()
	{
		$this->mydbname = new MyDBNamesModel();
		$this->db_erp = $this->mydbname->medb(0);
		$this->mylibz =  new MyLibzSysModel();
		$this->mylibzdb =  new MyLibzDBModel();
	}
	
	public function get_prod_line() { 
		$cuser              = $this->mylibzdb->mysys_user();
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		$adata = array();
		$str = "
		SELECT
			aa.	recid,concat(trim(aa.`PRODL_CODE`),'xOx',trim(aa.`PRODL_DESC`)) __mdata,  
			sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_rid 
		FROM {$this->db_erp}.`mst_product_line` aa
		WHERE `PRODL_RFLAG` = 'Y' ORDER BY aa.`PRODL_DESC`
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$adata[] = $row['__mdata'];
			endforeach;
		}
		$q->freeResult();
		return $adata;
	} //end get_prod_line

	public function store_df_tag() { 
		$adata=array();
		$adata[]="Sales" . "xOx" . "Sales";	
		$adata[]="Other Deposit" . "xOx" . "Other Deposit";	
		return $adata;		
	}
	
	public function get_ctr_6($dbname,$mfld='') { 
		$str = "
		CREATE TABLE if not exists {$dbname}.`myctr` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTRL_NO01` varchar(15) DEFAULT '00000000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "SELECT year(now()) XSYSYEAR";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$ryear = $q->getRowArray();
		$xsysyear = $ryear['XSYSYEAR'];
		$class = 'PBXTY';
		
		$str = "SELECT {$xfield} from {$dbname}.myctr WHERE CTR_YEAR = '$xsysyear' limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '00001';
			$str = "insert into {$dbname}.myctr (CTR_YEAR,{$xfield}) values('$xsysyear','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "SELECT {$xfield} MYFIELD from {$dbname}.myctr WHERE CTR_YEAR = '$xsysyear' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == ''){ 
				$xnumb = '00001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "SELECT ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,6,"0",STR_PAD_LEFT);
				$str = "UPDATE {$dbname}.myctr set {$xfield} = '{$xnumb}' WHERE CTR_YEAR = '$xsysyear'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return $class . $xnumb;
	} //end getctr6 

	public function get_ctr_new_dr($class,$supp,$dbname,$mfld='') { 
		$str = "
		CREATE TABLE if not exists {$dbname}.`myctr_stkcode` (
		  `CTR_YEAR` varchar(4) DEFAULT '0000',
		  `CTR_MONTH` varchar(2) DEFAULT '00',
		  `CTR_DAY` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(15) DEFAULT '00000000',
		  `CTRL_NO02` varchar(15) DEFAULT '00000000',
		  `CTRL_NO03` varchar(15) DEFAULT '00000000',
		  `CTRL_NO04` varchar(15) DEFAULT '00000000',
		  `CTRL_NO05` varchar(15) DEFAULT '00000000',
		  `CTRL_NO06` varchar(15) DEFAULT '00000000',
		  `CTRL_NO07` varchar(15) DEFAULT '00000000',
		  `CTRL_NO08` varchar(15) DEFAULT '00000000',
		  `CTRL_NO09` varchar(15) DEFAULT '00000000',
		  `CTRL_NO10` varchar(15) DEFAULT '00000000',
		  `CTRL_NO11` varchar(15) DEFAULT '00000000',
		  `SS_CTR` varchar(15) DEFAULT '000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`,`CTR_MONTH`,`CTR_DAY`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "select date(now()) XSYSDATE";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rdate = $q->getRowArray();
		$xsysdate = $rdate['XSYSDATE'];
		$xsysdate_exp = explode('-', $xsysdate);
		$xsysyear =  $xsysdate_exp[0];
		$xsysmonth = $xsysdate_exp[1];
		$xsysday = $xsysdate_exp[2];
		
		$str = "select {$xfield} from {$dbname}.myctr_stkcode WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday'  limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '0000000001';
			$str = "insert into {$dbname}.myctr_stkcode (CTR_YEAR,CTR_MONTH,CTR_DAY,{$xfield}) values('$xsysyear','$xsysmonth','$xsysday','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "select {$xfield} MYFIELD from {$dbname}.myctr_stkcode WHERE CTR_YEAR = '$xsysyear' AND CTR_MONTH = '$xsysmonth' AND CTR_DAY = '$xsysday' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '0000000001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "select ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,10,"0",STR_PAD_LEFT);
				$str = "update {$dbname}.myctr_stkcode set {$xfield} = '{$xnumb}'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return  $class. substr($xsysyear, -2, 2) . $xsysmonth . $xsysday . $xnumb;//.$supp
	} 

	public function get_ctr_barcoding($dbname,$mfld='') { 
		$str = "
		CREATE TABLE if not exists {$dbname}.`myctr_barcoding` (
		  `CTR_YEAR` varchar(2) DEFAULT '00',
		  `CTRL_NO01` varchar(8) DEFAULT '00000000',
		  UNIQUE KEY `ctr01` (`CTR_YEAR`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		$xfield = (empty($mfld) ? 'CTRL_NO01' : $mfld);
		
		$str = "select year(now()) XSYSYEAR";
		$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$ryear = $q->getRowArray();
		$xsysyear = substr($ryear['XSYSYEAR'], -2, 2);
		
		$str = "select {$xfield} from {$dbname}.myctr_barcoding WHERE CTR_YEAR = '$xsysyear' limit 1";
		$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($qctr->getNumRows() == 0) {
			$xnumb = '00000001';
			$str = "insert into {$dbname}.myctr_barcoding (CTR_YEAR,{$xfield}) values('$xsysyear','$xnumb')";
			$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$qctr->freeResult();
		} else {
			$qctr->freeResult();
			$str = "select {$xfield} MYFIELD from {$dbname}.myctr_barcoding WHERE CTR_YEAR = '$xsysyear' limit 1";
			$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			$rctr = $qctr->getRowArray();
			if(trim($rctr['MYFIELD'],' ') == '') { 
				$xnumb = '00000001';
			} else {
				$xnumb = $rctr['MYFIELD'];
				$str = "select ('{$xnumb}' + 1) XNUMB";
				$qctr = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
				$rctr = $qctr->getRowArray();
				$xnumb = trim($rctr['XNUMB'],' ');
				$xnumb = str_pad($xnumb + 0,10,"0",STR_PAD_LEFT);
				$str = "update {$dbname}.myctr_barcoding set {$xfield} = '{$xnumb}'";
				$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
			}
		}
		return $xsysyear . $xnumb;
	}


	
} //end main class
