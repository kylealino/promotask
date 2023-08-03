<?php

namespace App\Controllers;
use App\Models\MyDBNamesModel;
use App\Models\MyLibzDBModel;
use App\Models\MyLibzSysModel;

class MySearchData extends BaseController
{
	public function __construct()
	{
		$this->mydbname = new MyDBNamesModel();
		$this->db_erp = $this->mydbname->medb(0);
		$this->mylibz =  new MyLibzSysModel();
		$this->mylibzdb =  new MyLibzDBModel();
	}

	//start materials article
	public function mat_article() { 
		$mpw_tkn            = $this->mylibzdb->mpw_tkn();
		//$term               = $this->mylibzdb->dbx->escapeString($this->request->getVar('term'));
		$term               = $this->mylibzdb->dbx->escapeString(urldecode($this->request->getVar('term')));
		$autoCompleteResult = array();
		$str = "
		SELECT
			aa.	recid,trim(aa.`ART_CODE`) __mdata, aa.`ART_DESC`, aa.`ART_BARCODE1`,aa.`ART_UPRICE`, aa.`ART_UCOST`,
			sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_rid 
		FROM {$this->db_erp}.`mst_article` aa 
		WHERE (aa.ART_CODE like '%$term%' or aa.ART_DESC like '%$term%')
		ORDER BY aa.`recid` desc LIMIT 100
		";
		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) { 
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn); 
				array_push($autoCompleteResult,array(
					"value" => $row['__mdata'], 
					"mtkn_rid" => $mtkn_rid,
					"ART_DESC" => $row['ART_DESC'],
					"ART_BARCODE1" => $row['ART_BARCODE1'],
					"ART_UPRICE" => $row['ART_UPRICE'],
					"ART_UCOST" => $row['ART_UCOST']
					));
			endforeach;
		}
		$q->freeResult();
		echo json_encode($autoCompleteResult);
      //  }
    } //end mat_article	

	//start hover plant

	//start hover branch
	public function companybranch_v(){
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');

		$autoCompleteResult = array();

		$str = "
		SELECT
		aa.`recid`,
		aa.`BRNCH_OCODE2`,
		aa.`BRNCH_NAME`,
		bb.`COMP_NAME`

		FROM 
		`mst_companyBranch` aa
		JOIN
		mst_company bb
		ON
		aa.`COMP_ID` = bb.`recid`
		

		WHERE aa.`BRNCH_OCODE2` like '%{$term}%'
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['BRNCH_NAME'],
					"mtkn_rid" => $mtkn_rid,
					"BRNCH_NAME"=>$row['BRNCH_NAME'],
					"BRNCH_OCODE2" => $row['BRNCH_OCODE2'],
					"COMP_NAME" => $row['COMP_NAME']

				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	}// end companybranch

	public function company(){
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');

		$autoCompleteResult = array();

		$str = "
		SELECT
		aa.`recid`,
		aa.`COMP_NAME`
		FROM {$this->db_erp}.`mst_company` aa

		WHERE aa.`COMP_NAME` like '%{$term}%'
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['COMP_NAME'],
				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	}// end companybranch

	public function deposit(){
		$cuser   = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		$term    = $this->request->getVar('term');
		$mtkn_brnch  = urldecode($this->request->getVar('mtkn_brnch'));
		$autoCompleteResult = array();

		$str = "
		SELECT
		aa.`recid`,
		aa.`bankName`,
		aa.`acctNO`
		FROM {$this->db_erp}.`mst_depositBranchAcct` aa
		JOIN
		mst_companyBranch bb
		on
		aa.`brnchID` = bb.`recid`
		WHERE aa.`bankName` like '%{$term}%' and sha2(concat(aa.`brnchID`,'{$mpw_tkn}'),384) = '$mtkn_brnch'
		";

		$q =  $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		if($q->getNumRows() > 0) {
			$rrec = $q->getResultArray();
			foreach($rrec as $row):
				$mtkn_rid = hash('sha384', $row['recid'] . $mpw_tkn);
				array_push($autoCompleteResult,array("value" => $row['bankName'],
				"acctNO"=>$row['acctNO'],
				));
			endforeach;
		}

		$q->freeResult();
		echo json_encode($autoCompleteResult);
		
	}// end companybranch

} //end main class
