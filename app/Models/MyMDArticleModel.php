<?php
namespace App\Models;
use CodeIgniter\Model;
class MyMDArticleModel extends Model
{
	public function __construct()
	{ 
		parent::__construct();
		$this->request = \Config\Services::request();
		$this->mydbname = model('App\Models\MyDBNamesModel');
		$this->db_erp = $this->mydbname->medb(0);
		$this->mylibzdb = model('App\Models\MyLibzDBModel');
		$this->dbx = $this->mylibzdb->dbx;
	}	
	
	public function view_recs($npages = 1,$npagelimit = 30,$msearchrec='') {
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		if(!isset($cuser)) {
			//die();
		}

		$str_optn = "";
		if(!empty($msearchrec)) { 
			$msearchrec = $this->dbx->escapeString($msearchrec);
			$str_optn = " where (ART_CODE like '%$msearchrec%' or ART_DESC like '%$msearchrec%' or 
			ART_BARCODE1 like '%$msearchrec%') ";
		}
		
		$strqry = "
		select aa.*,
		IF(aa.`ART_ISDISABLE` = 1, 'Inactive','Active') _ART_ISDISABLE,
		sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_arttr 
		 from {$this->db_erp}.`mst_article` aa {$str_optn} 
		";
		
		
		$str = "
		select count(*) __nrecs from ({$strqry}) oa
		";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $qry->getRowArray();
		$npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
		$nstart = ($npagelimit * ($npages - 1));
		
		
		$npage_count = ceil(($rw['__nrecs'] + 0) / $npagelimit);
		$data['npage_count'] = $npage_count;
		$data['npage_curr'] = $npages;
		$str = "
		SELECT * from ({$strqry}) oa limit {$nstart},{$npagelimit} ";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		if($qry->resultID->num_rows > 0) { 
			$data['rlist'] = $qry->getResultArray();
		} else { 
			$data = array();
			$data['npage_count'] = 1;
			$data['npage_curr'] = 1;
			$data['rlist'] = '';
		}
		return $data;
    }  //end view_recs
    
    public function profile_save() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		
		$mtkn_etr = $this->request->getVar('mtkn_etr');
		$maction = $this->request->getVar('maction');
		$meprodlc = $this->dbx->escapeString($this->request->getVar('meprodlc'));
		$mematcode = $this->dbx->escapeString($this->request->getVar('mematcode'));
		$mebarcode = $this->dbx->escapeString($this->request->getVar('mebarcode'));
		$mematdesc = $this->dbx->escapeString($this->request->getVar('mematdesc'));
		$mepartnumber = $this->dbx->escapeString($this->request->getVar('mepartnumber'));
		$flexSwitchCheckArtRecActive = $this->request->getVar('flexSwitchCheckArtRecActive');
		$meprodt = $this->dbx->escapeString($this->request->getVar('meprodt'));
		$meprodcat = $this->dbx->escapeString($this->request->getVar('meprodcat'));
		$meprodscat = $this->dbx->escapeString($this->request->getVar('meprodscat'));
		$meunitc = (empty($this->request->getVar('meunitc')) ? 0 : ($this->request->getVar('meunitc') + 0));
		$meunitp = (empty($this->request->getVar('meunitp')) ? 0 : ($this->request->getVar('meunitp') + 0));
		$meunitpack = $this->request->getVar('meunitpack');
		$meuom = $this->request->getVar('meuom');
		$megweight = (empty($this->request->getVar('megweight')) ? 0 : ($this->request->getVar('megweight') + 0));
		$meconvf = (empty($this->request->getVar('meconvf')) ? 0 : ($this->request->getVar('meconvf') + 0));
		
		//updating of records
		if(!empty($mtkn_etr)) { 
			$str = "select recid,ART_CODE from {$this->db_erp}.`mst_article` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_etr'";
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
			if($q->getNumRows() > 0) { 
				$rw = $q->getRowArray();
				if($mematcode == $rw['ART_CODE']) { 
					$adataz = array();
					$adataz[] = "ART_DESCxOx'{$mematdesc}'";
					$adataz[] = "ART_PARTNOxOx'{$mepartnumber}'";
					$adataz[] = "ART_HIERC1xOx'{$meprodcat}'";
					$adataz[] = "ART_HIERC2xOx'{$meprodt}'";
					$adataz[] = "ART_HIERC3xOx'{$meprodscat}'";
					$adataz[] = "ART_SKUxOx'{$meunitpack}'";
					$adataz[] = "ART_UOMxOx'{$meuom}'";
					$adataz[] = "ART_BARCODE1xOx'{$mebarcode}'";
					$adataz[] = "ART_ISDISABLExOx'{$flexSwitchCheckArtRecActive}'";
					$adataz[] = "ART_PRODLxOx'{$meprodlc}'";
					$adataz[] = "ART_NCONVFxOx'{$meconvf}'";
					$adataz[] = "ART_GWEIHGTxOx'{$megweight}'";
					$adataz[] = "ART_UPPRICExOx'{$meunitp}'";
					$adataz[] = "ART_UCOSTxOx'{$meunitc}'";
					$str = " recid = {$rw['recid']} ";
					$this->mylibzdb->logs_modi_audit($adataz,$this->db_erp,'`mst_article`','MATITEM_UREC',$mematcode,$str);
					$str = "update {$this->db_erp}.`mst_article` set ART_DESC = '$mematdesc',
					`ART_PARTNO` = '$mepartnumber',
					`ART_HIERC1` = '$meprodcat',
					`ART_HIERC2` = '$meprodt',
					`ART_HIERC3` = '$meprodscat',
					`ART_SKU` = '$meunitpack',
					`ART_UOM` = '$meuom',
					`ART_BARCODE1` = '$mebarcode',
					`ART_ISDISABLE` = '$flexSwitchCheckArtRecActive',
					`ART_PRODL` = '$meprodlc',
					`ART_NCONVF` = '$meconvf',
					`ART_GWEIHGT` = '$megweight',
					`ART_UPPRICE` = '$meunitp',
					`ART_UCOST` = '$meunitc'
					where recid = {$rw['recid']} ";
					$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
					$this->mylibzdb->user_logs_activity_module($this->db_erp,'MATITEM_UREC',$mematcode,$mematcode,$str,'');
					echo "Changes successfuly done!!!";
				} else { 
					echo "Material Code conflict for update!!!";
				}
			} 
		} else { 
			//adding of records
			$str = "select recid,ART_CODE from {$this->db_erp}.`mst_article` aa where `ART_CODE` = '$mematcode'";
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
			if($q->getNumRows() > 0) { 
				$rw = $q->getRowArray();
				echo "Material Code already EXISTS!!!";
				die();
			} else { 
				if($maction == 'A_REC') { 
					$adataz = array();
					$adataz[] = "ART_CODExOx'{$mematcode}'";
					$adataz[] = "ART_DESCxOx'{$mematdesc}'";
					$adataz[] = "ART_PARTNOxOx'{$mepartnumber}'";
					$adataz[] = "ART_HIERC1xOx'{$meprodcat}'";
					$adataz[] = "ART_HIERC2xOx'{$meprodt}'";
					$adataz[] = "ART_HIERC3xOx'{$meprodscat}'";
					$adataz[] = "ART_SKUxOx'{$meunitpack}'";
					$adataz[] = "ART_UOMxOx'{$meuom}'";
					$adataz[] = "ART_BARCODE1xOx'{$mebarcode}'";
					$adataz[] = "ART_ISDISABLExOx'{$flexSwitchCheckArtRecActive}'";
					$adataz[] = "ART_PRODLxOx'{$meprodlc}'";
					$adataz[] = "ART_NCONVFxOx'{$meconvf}'";
					$adataz[] = "ART_GWEIHGTxOx'{$megweight}'";
					$adataz[] = "ART_UPPRICExOx'{$meunitp}'";
					$adataz[] = "ART_UCOSTxOx'{$meunitc}'";
					$str = " ART_CODE = '$mematcode' ";
					$this->mylibzdb->logs_modi_audit($adataz,$this->db_erp,'`mst_article`','MATITEM_AREC',$mematcode,$str);
					$str = "
					insert into {$this->db_erp}.`mst_article` (
					`ART_CODE`,`ART_DESC`,`ART_HIERC1`,`ART_HIERC2`,`ART_HIERC3`,`ART_PRODL`,
					`ART_SKU`,`ART_UOM`,`ART_BARCODE1`,`ART_ISDISABLE`,`ART_NCONVF`,`ART_PARTNO`,
					`ART_GWEIHGT`,`ART_UPPRICE`,`ART_UCOST`,`MUSER`,`ENCD`
					) values (
					'$mematcode','$mematdesc','$meprodcat','$meprodt','$meprodscat','$meprodlc',
					'$meunitpack','$meuom','$mebarcode','$flexSwitchCheckArtRecActive','$meconvf','$mepartnumber',
					'$megweight','$meunitp','$meunitc','$cuser',now()
					)
					";
					$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
					$this->mylibzdb->user_logs_activity_module($this->db_erp,'MATITEM_AREC',$mematcode,$mematcode,$str,'');
					echo "Records successfuly added!!!";
				} else { //end A_REC validation 
					echo "INVALID OPERATION!!!";
				}
			}
		} //end mtkn_etr validation 
		
	} //end profile_save

//walter start
    public function view_wrecs($npages = 1,$npagelimit = 30,$msearchrec='') {
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		if(!isset($cuser)) {
			//die();
		}

		$str_optn = "";
		if(!empty($msearchrec)) { 
			$msearchrec = $this->dbx->escapeString($msearchrec);
			$str_optn = " where (ART_CODE like '%$msearchrec%' or ART_DESC like '%$msearchrec%' or 
			ART_BARCODE1 like '%$msearchrec%') ";
		}
		
		$strqry = "
		select aa.*,
		IF(aa.`ART_ISDISABLE` = 1, 'Inactive','Active') _ART_ISDISABLE,
		sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_arttr 
		 from {$this->db_erp}.`w_articlemaster` aa {$str_optn} 
		";
		
		
		$str = "
		select count(*) __nrecs from ({$strqry}) oa
		";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $qry->getRowArray();
		$npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
		$nstart = ($npagelimit * ($npages - 1));
		
		
		$npage_count = ceil(($rw['__nrecs'] + 0) / $npagelimit);
		$data['npage_count'] = $npage_count;
		$data['npage_curr'] = $npages;
		$str = "
		SELECT * from ({$strqry}) oa limit {$nstart},{$npagelimit} ";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		if($qry->resultID->num_rows > 0) { 
			$data['rlist'] = $qry->getResultArray();
		} else { 
			$data = array();
			$data['npage_count'] = 1;
			$data['npage_curr'] = 1;
			$data['rlist'] = '';
		}
		return $data;
    }  //end view_wrecs
    
   public function Wprof_save() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		
		$mtkn_etr = $this->request->getVar('mtkn_etr');
		$maction = $this->request->getVar('maction');
		$meprodlc = $this->dbx->escapeString($this->request->getVar('meprodlc'));
		$mematcode = $this->dbx->escapeString($this->request->getVar('mematcode'));
		$mebarcode = $this->dbx->escapeString($this->request->getVar('mebarcode'));
		$mematdesc = $this->dbx->escapeString($this->request->getVar('mematdesc'));
		$mepartnumber = $this->dbx->escapeString($this->request->getVar('mepartnumber'));
		$flexSwitchCheckArtRecActive = $this->request->getVar('flexSwitchCheckArtRecActive');
		$meprodt = $this->dbx->escapeString($this->request->getVar('meprodt'));
		$meprodcat = $this->dbx->escapeString($this->request->getVar('meprodcat'));
		$meprodscat = $this->dbx->escapeString($this->request->getVar('meprodscat'));
		$meunitc = (empty($this->request->getVar('meunitc')) ? 0 : ($this->request->getVar('meunitc') + 0));
		$meunitp = (empty($this->request->getVar('meunitp')) ? 0 : ($this->request->getVar('meunitp') + 0));
		$meunitpack = $this->request->getVar('meunitpack');
		$meuom = $this->request->getVar('meuom');
		$megweight = (empty($this->request->getVar('megweight')) ? 0 : ($this->request->getVar('megweight') + 0));
		$meconvf = (empty($this->request->getVar('meconvf')) ? 0 : ($this->request->getVar('meconvf') + 0));
		
		//updating of records
		if(!empty($mtkn_etr)) { 
			$str = "select recid,ART_CODE from {$this->db_erp}.`w_articlemaster` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_etr'";
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
			if($q->getNumRows() > 0) { 
				$rw = $q->getRowArray();
				if($mematcode == $rw['ART_CODE']) { 
					$adataz = array();
					$adataz[] = "ART_DESCxOx'{$mematdesc}'";
					$adataz[] = "ART_PARTNOxOx'{$mepartnumber}'";
					$adataz[] = "ART_HIERC1xOx'{$meprodcat}'";
					$adataz[] = "ART_HIERC2xOx'{$meprodt}'";
					$adataz[] = "ART_HIERC3xOx'{$meprodscat}'";
					$adataz[] = "ART_SKUxOx'{$meunitpack}'";
					$adataz[] = "ART_UOMxOx'{$meuom}'";
					$adataz[] = "ART_BARCODE1xOx'{$mebarcode}'";
					$adataz[] = "ART_ISDISABLExOx'{$flexSwitchCheckArtRecActive}'";
					$adataz[] = "ART_PRODLxOx'{$meprodlc}'";
					$adataz[] = "ART_NCONVFxOx'{$meconvf}'";
					$adataz[] = "ART_GWEIHGTxOx'{$megweight}'";
					$adataz[] = "ART_UPPRICExOx'{$meunitp}'";
					$adataz[] = "ART_UCOSTxOx'{$meunitc}'";
					$str = " recid = {$rw['recid']} ";
					$this->mylibzdb->logs_modi_audit($adataz,$this->db_erp,'`w_articlemaster`','MATITEM_UREC',$mematcode,$str);
					$str = "update {$this->db_erp}.`w_articlemaster` set ART_DESC = '$mematdesc',
					`ART_PARTNO` = '$mepartnumber',
					`ART_HIERC1` = '$meprodcat',
					`ART_HIERC2` = '$meprodt',
					`ART_HIERC3` = '$meprodscat',
					`ART_SKU` = '$meunitpack',
					`ART_UOM` = '$meuom',
					`ART_BARCODE1` = '$mebarcode',
					`ART_ISDISABLE` = '$flexSwitchCheckArtRecActive',
					`ART_PRODL` = '$meprodlc',
					`ART_NCONVF` = '$meconvf',
					`ART_GWEIHGT` = '$megweight',
					`ART_UPPRICE` = '$meunitp',
					`ART_UCOST` = '$meunitc'
					where recid = {$rw['recid']} ";
					$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
					$this->mylibzdb->user_logs_activity_module($this->db_erp,'MATITEM_UREC',$mematcode,$mematcode,$str,'');
					echo "Changes successfuly done!!!";
				} else { 
					echo "Material Code conflict for update!!!";
				}
			} 
		} else { 
			//adding of records
			$str = "select recid,ART_CODE from {$this->db_erp}.`w_articlemaster` aa where `ART_CODE` = '$mematcode'";
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
			if($q->getNumRows() > 0) { 
				$rw = $q->getRowArray();
				echo "Material Code already EXISTS!!!";
				die();
			} else { 
				if($maction == 'A_REC') { 
					$adataz = array();
					$adataz[] = "ART_CODExOx'{$mematcode}'";
					$adataz[] = "ART_DESCxOx'{$mematdesc}'";
					$adataz[] = "ART_PARTNOxOx'{$mepartnumber}'";
					$adataz[] = "ART_HIERC1xOx'{$meprodcat}'";
					$adataz[] = "ART_HIERC2xOx'{$meprodt}'";
					$adataz[] = "ART_HIERC3xOx'{$meprodscat}'";
					$adataz[] = "ART_SKUxOx'{$meunitpack}'";
					$adataz[] = "ART_UOMxOx'{$meuom}'";
					$adataz[] = "ART_BARCODE1xOx'{$mebarcode}'";
					$adataz[] = "ART_ISDISABLExOx'{$flexSwitchCheckArtRecActive}'";
					$adataz[] = "ART_PRODLxOx'{$meprodlc}'";
					$adataz[] = "ART_NCONVFxOx'{$meconvf}'";
					$adataz[] = "ART_GWEIHGTxOx'{$megweight}'";
					$adataz[] = "ART_UPPRICExOx'{$meunitp}'";
					$adataz[] = "ART_UCOSTxOx'{$meunitc}'";
					$str = " ART_CODE = '$mematcode' ";
					$this->mylibzdb->logs_modi_audit($adataz,$this->db_erp,'`w_articlemaster`','MATITEM_AREC',$mematcode,$str);
					$str = "
					insert into {$this->db_erp}.`w_articlemaster` (
					`ART_CODE`,`ART_DESC`,`ART_HIERC1`,`ART_HIERC2`,`ART_HIERC3`,`ART_PRODL`,
					`ART_SKU`,`ART_UOM`,`ART_BARCODE1`,`ART_ISDISABLE`,`ART_NCONVF`,`ART_PARTNO`,
					`ART_GWEIHGT`,`ART_UPPRICE`,`ART_UCOST`,`MUSER`,`ENCD`
					) values (
					'$mematcode','$mematdesc','$meprodcat','$meprodt','$meprodscat','$meprodlc',
					'$meunitpack','$meuom','$mebarcode','$flexSwitchCheckArtRecActive','$meconvf','$mepartnumber',
					'$megweight','$meunitp','$meunitc','$cuser',now()
					)
					";
					$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
					$this->mylibzdb->user_logs_activity_module($this->db_erp,'MATITEM_AREC',$mematcode,$mematcode,$str,'');
					echo "Records successfuly added!!!";
				} else { //end A_REC validation 
					echo "INVALID OPERATION!!!";
				}
			}
		} //end mtkn_etr validation 
		
	} //end profile_save
	
    //walter end

	//kyle start article model

	//kyle start
    public function view_krecs($npages = 1,$npagelimit = 30,$msearchrec='') {
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		if(!isset($cuser)) {
			//die();
		}

		$str_optn = "";
		if(!empty($msearchrec)) { 
			$msearchrec = $this->dbx->escapeString($msearchrec);
			$str_optn = " where (ART_CODE like '%$msearchrec%' or ART_DESC like '%$msearchrec%' or 
			ART_BARCODE1 like '%$msearchrec%') ";
		}
		
		$strqry = "
		select aa.*,
		IF(aa.`ART_ISDISABLE` = 1, 'Inactive','Active') _ART_ISDISABLE,
		sha2(concat(aa.recid,'{$mpw_tkn}'),384) mtkn_arttr 
		 from {$this->db_erp}.`mst_articles` aa {$str_optn} 
		";
		
		
		$str = "
		select count(*) __nrecs from ({$strqry}) oa
		";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		$rw = $qry->getRowArray();
		$npagelimit = ($npagelimit > 0 ? $npagelimit : 30);
		$nstart = ($npagelimit * ($npages - 1));
		
		
		$npage_count = ceil(($rw['__nrecs'] + 0) / $npagelimit);
		$data['npage_count'] = $npage_count;
		$data['npage_curr'] = $npages;
		$str = "
		SELECT * from ({$strqry}) oa limit {$nstart},{$npagelimit} ";
		$qry = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
		
		if($qry->resultID->num_rows > 0) { 
			$data['rlist'] = $qry->getResultArray();
		} else { 
			$data = array();
			$data['npage_count'] = 1;
			$data['npage_curr'] = 1;
			$data['rlist'] = '';
		}
		return $data;
    }  //end view_Krecs
    
   public function Kprof_save() { 
		$cuser = $this->mylibzdb->mysys_user();
		$mpw_tkn = $this->mylibzdb->mpw_tkn();
		
		$mtkn_etr = $this->request->getVar('mtkn_etr');
		$maction = $this->request->getVar('maction');
		$meprodlc = $this->dbx->escapeString($this->request->getVar('meprodlc'));
		$mematcode = $this->dbx->escapeString($this->request->getVar('mematcode'));
		$mebarcode = $this->dbx->escapeString($this->request->getVar('mebarcode'));
		$mematdesc = $this->dbx->escapeString($this->request->getVar('mematdesc'));
		$mepartnumber = $this->dbx->escapeString($this->request->getVar('mepartnumber'));
		$flexSwitchCheckArtRecActive = $this->request->getVar('flexSwitchCheckArtRecActive');
		$meprodt = $this->dbx->escapeString($this->request->getVar('meprodt'));
		$meprodcat = $this->dbx->escapeString($this->request->getVar('meprodcat'));
		$meprodscat = $this->dbx->escapeString($this->request->getVar('meprodscat'));
		$mecustname = $this->dbx->escapeString($this->request->getVar('mecustname'));
		$meunitc = (empty($this->request->getVar('meunitc')) ? 0 : ($this->request->getVar('meunitc') + 0));
		$meunitp = (empty($this->request->getVar('meunitp')) ? 0 : ($this->request->getVar('meunitp') + 0));
		$meunitpack = $this->request->getVar('meunitpack');
		$meuom = $this->request->getVar('meuom');
		$megweight = (empty($this->request->getVar('megweight')) ? 0 : ($this->request->getVar('megweight') + 0));
		$meconvf = (empty($this->request->getVar('meconvf')) ? 0 : ($this->request->getVar('meconvf') + 0));
		
		//updating of records
		if(!empty($mtkn_etr)) { 
			$str = "select recid,ART_CODE from {$this->db_erp}.`mst_article` aa where sha2(concat(aa.recid,'{$mpw_tkn}'),384) = '$mtkn_etr'";
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
			if($q->getNumRows() > 0) { 
				$rw = $q->getRowArray();
				if($mematcode == $rw['ART_CODE']) { 
					$adataz = array();
					$adataz[] = "ART_DESCxOx'{$mematdesc}'";
					$adataz[] = "ART_PARTNOxOx'{$mepartnumber}'";
					$adataz[] = "ART_HIERC1xOx'{$meprodcat}'";
					$adataz[] = "ART_HIERC2xOx'{$meprodt}'";
					$adataz[] = "ART_HIERC3xOx'{$meprodscat}'";
					$adataz[] = "ART_SKUxOx'{$meunitpack}'";
					$adataz[] = "ART_UOMxOx'{$meuom}'";
					$adataz[] = "ART_BARCODE1xOx'{$mebarcode}'";
					$adataz[] = "ART_ISDISABLExOx'{$flexSwitchCheckArtRecActive}'";
					$adataz[] = "ART_PRODLxOx'{$meprodlc}'";
					$adataz[] = "ART_NCONVFxOx'{$meconvf}'";
					$adataz[] = "ART_GWEIHGTxOx'{$megweight}'";
					$adataz[] = "ART_UPPRICExOx'{$meunitp}'";
					$adataz[] = "ART_UCOSTxOx'{$meunitc}'";
					$str = " recid = {$rw['recid']} ";
					$this->mylibzdb->logs_modi_audit($adataz,$this->db_erp,'`mst_articles`','MATITEM_UREC',$mematcode,$str);
					$str = "update {$this->db_erp}.`mst_article` set ART_DESC = '$mematdesc',
					`ART_PARTNO` = '$mepartnumber',
					`ART_HIERC1` = '$meprodcat',
					`ART_HIERC2` = '$meprodt',
					`ART_HIERC3` = '$meprodscat',
					`ART_SKU` = '$meunitpack',
					`ART_UOM` = '$meuom',
					`ART_BARCODE1` = '$mebarcode',
					`ART_ISDISABLE` = '$flexSwitchCheckArtRecActive',
					`ART_PRODL` = '$meprodlc',
					`ART_NCONVF` = '$meconvf',
					`ART_GWEIHGT` = '$megweight',
					`ART_UPPRICE` = '$meunitp',
					`ART_UCOST` = '$meunitc'
					where recid = {$rw['recid']} ";
					$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
					$this->mylibzdb->user_logs_activity_module($this->db_erp,'MATITEM_UREC',$mematcode,$mematcode,$str,'');
					echo "Changes successfuly done!!!";
				} else { 
					echo "Material Code conflict for update!!!";
				}
			} 
		} else { 
			//adding of records
			$str = "select recid,ART_CODE from {$this->db_erp}.`mst_article` aa where `ART_CODE` = '$mematcode'";
			$q = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
			if($q->getNumRows() > 0) { 
				$rw = $q->getRowArray();
				echo "Material Code already EXISTS!!!";
				die();
			} else { 
				if($maction == 'A_REC') { 
					$adataz = array();
					$adataz[] = "ART_CODExOx'{$mematcode}'";
					$adataz[] = "ART_DESCxOx'{$mematdesc}'";
					$adataz[] = "ART_PARTNOxOx'{$mepartnumber}'";
					$adataz[] = "ART_HIERC1xOx'{$meprodcat}'";
					$adataz[] = "ART_HIERC2xOx'{$meprodt}'";
					$adataz[] = "ART_HIERC3xOx'{$meprodscat}'";
					$adataz[] = "ART_SKUxOx'{$meunitpack}'";
					$adataz[] = "ART_UOMxOx'{$meuom}'";
					$adataz[] = "ART_BARCODE1xOx'{$mebarcode}'";
					$adataz[] = "ART_ISDISABLExOx'{$flexSwitchCheckArtRecActive}'";
					$adataz[] = "ART_PRODLxOx'{$meprodlc}'";
					$adataz[] = "ART_NCONVFxOx'{$meconvf}'";
					$adataz[] = "ART_GWEIHGTxOx'{$megweight}'";
					$adataz[] = "ART_UPPRICExOx'{$meunitp}'";
					$adataz[] = "ART_UCOSTxOx'{$meunitc}'";
					$str = " ART_CODE = '$mematcode' ";
					$this->mylibzdb->logs_modi_audit($adataz,$this->db_erp,'`mst_articles`','MATITEM_AREC',$mematcode,$str);
					$str = "
					insert into {$this->db_erp}.`mst_article` (
					`ART_CODE`,`ART_DESC`,`ART_HIERC1`,`ART_HIERC2`,`ART_HIERC3`,`ART_PRODL`,
					`ART_SKU`,`ART_UOM`,`ART_BARCODE1`,`ART_ISDISABLE`,`ART_NCONVF`,`ART_PARTNO`,
					`ART_GWEIHGT`,`ART_UPPRICE`,`ART_UCOST`,`MUSER`,`ENCD`
					) values (
					'$mematcode','$mematdesc','$meprodcat','$meprodt','$meprodscat','$meprodlc',
					'$meunitpack','$meuom','$mebarcode','$flexSwitchCheckArtRecActive','$meconvf','$mepartnumber',
					'$megweight','$meunitp','$meunitc','$cuser',now()
					)
					";
					$this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__  . chr(13) . chr(10) . 'User: ' . $cuser);
					$this->mylibzdb->user_logs_activity_module($this->db_erp,'MATITEM_AREC',$mematcode,$mematcode,$str,'');
					echo "Records successfuly added!!!";
				} else { //end A_REC validation 
					echo "INVALID OPERATION!!!";
				}
			}
		} //end mtkn_etr validation 
		
	} //end profile_save
	
    //kyle end




    } //end main class
