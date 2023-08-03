<?php
namespace App\Models;
use CodeIgniter\Model;

class MyLibzDBModel extends Model
{
    protected $dbx;
	protected $meqry;
	
    public function __construct()
    {
        parent::__construct();
        $this->dbx = \Config\Database::connect();
        // OR $this->db = db_connect();
        $this->mydbname = model('App\Models\MyDBNamesModel');
    }

    public function myoa_sql_exec($str,$cmisc='') { 
        $qry = $this->dbx->query($str);
        $cuser = $this->mysys_user();
        $error = $this->dbx->error();
        if(!$qry) {             
            $strq =  $error['message'] . chr(13) . chr(10) . 
            'error code: ' . $error['code'] . chr(13) . chr(10) . 
            dirname(__FILE__) . chr(13) . chr(10) . 
            $cmisc . chr(13) . chr(10) . 
            $str;
            $db_name = $this->mydbname->medb(0);
            $strq = $this->dbx->escapeString($strq);  
            //$medbx = \Config\Database::connect();            
            $str = "insert into {$db_name}.`syslogs` (LOG_USER,LOG_SQLEXEC,LOG_MODULE,LOG_DATE,LOG_IPADDR) 
            values('$cuser','$strq','SYSINS',now(),'" . $this->get_ip_address() . "')";
            //$q = $medbx->query($str);
            $q = $this->dbx->query($str);
            $meerror = $this->dbx->error();
            if (!$q) {
                $cerr =  "<div style=\"display: block;
                    padding: 3px;
                    width: auto;
                    height: auto;
                    font: bold 14px Tahoma;
                    text-decoration: blink;
                    background-color: red;
                    color: white;
                    border-radius: 5px;
                    -moz-border-radius:0.4em;
                    -khtml-border-radius:0.4em;
                    -webkit-border-radius:0.4em;
                    -ms-border-radius:0.4em;
                    -o-border-radius:0.4em;
                    border: 1px solid #2D657E;
                    -moz-box-shadow: 5px 5px 5px grey;
                    -webkit-box-shadow: 5px 5px 5px grey;
                    -ms-box-shadow: 5px 5px 5px grey;
                    box-shadow: 5px 5px 5px grey;                   
                    \">
                        {$str} </br>
                        Error Syslogs: {$meerror['message']}
                    </div>";
                echo $cerr;
                die();
            } else {
                echo "
                <div class=\"alert alert-danger mb-0\"><strong>Error.</strong> Pls see System Logs...</div>
                ";
                die();
            }
        }
        $this->meqry = $qry;
        return $qry;
    }
    
    public function  get_ip_address() { 
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }  
    //end get_ip_address

    public function user_logs_activity_module($dbname,$cmoduletag='',$cka='',$ckmb='',$cremk1='',$cremk2='') { 
        $cuser = $this->mysys_user();
        $xstr1 = $this->dbx->escapeString($cremk1);
        $xstr2 = $this->dbx->escapeString($cremk2);
        $cmoduletag = $this->dbx->escapeString($cmoduletag);
        $str = "select now() __xcurdatetime";
        $q = $this->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        $rw = $q->getRowArray();
        $xcurdatetime = $rw['__xcurdatetime'];
        $q->freeResult();
        
        $cka = substr($cka,0,60);
        $ckmb = substr($ckmb,0,60);
        
        $str = "
        insert into {$dbname}.ualam (
        `LOG_USER`,`LOG_MODULE`,
        `LOG_KA_KEYREC`,`LOG_MB_KEYREC`,`LOG_REMK1`,
        `LOG_REMK2`,`LOG_IPADDR`
        ) 
        values('$cuser','{$cmoduletag}','$cka','$ckmb','$xstr1','$xstr2','" . $this->get_ip_address() . "') 
        ";
        $this->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
        
        return array($cmoduletag,$xcurdatetime);
    } //end user_logs_activity_module

    public function logs_modi_audit($adatums=array(),$dbname='',$dbtbl='',$cmodule='',$ckeydata='',$optn='',$crecmark='',$cdblogs='') {
        $cdbm = $dbname;
        $dbmlogs = (!empty($cdblogs) ? $cdblogs : $cdbm);
        $cuser = $this->mysys_user();
        $cipaddr = $this->get_ip_address();
        $colddata = "";
        
        for($ii = 0;$ii < count($adatums); $ii++) {
            $mdata = explode("xOx",$adatums[$ii]);
            $cfld = $mdata[0];
            $cdata = $mdata[1];
            
            $cstr = "SELECT {$cfld} from {$cdbm}.{$dbtbl} where {$optn} limit 1"; 
            $mmqry = $this->myoa_sql_exec($cstr,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($mmqry->resultID->num_rows == 0) {
                $cchanges = "A";
                $colddata = "";
            } else {
                $cchanges = "U";
                $rsmm = $mmqry->getRowArray();
                if(!empty($rsmm[$cfld])) {
					$colddata = $this->dbx->escapeString($rsmm[$cfld]);
				} else { 
					$colddata = '';
				}
            }
            $mmqry->freeResult();
            
            //record for deletion
            if($crecmark == 'DEL_REC') { 
                $cchanges = "D";
            }
            
            
            $cstr = "SELECT {$cfld} from {$cdbm}.{$dbtbl} where {$optn} and {$cfld} = $cdata";
            $strq = $this->dbx->escapeString($cstr);
            //$strq = addslashes($cstr);
            //var_dump($this->dbx);
            //$strq = mysqli_real_escape_string($this->dbx,$cstr);
            //$strq = $cstr;
            //$strq = '';
            $mmqry2 = $this->myoa_sql_exec($cstr,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            if($mmqry2->resultID->num_rows == 0) { 
                $cstr = "insert into {$dbmlogs}.`auditlogs` ( 
                LOG_USER,LOG_MODULE,LOG_DATE,LOG_TIME,LOG_TBL,LOG_KEYREC,LOG_ENUMB,LOG_FIELD,LOG_OLDVAL,LOG_NEWVAL,
                LOG_CHANGE,LOG_IPADDR,LOG_SQLEXEC) 
                 values ('$cuser','$cmodule',now(),current_time(),'$dbtbl','$ckeydata','$ckeydata','$cfld','$colddata',$cdata,
                 '$cchanges','$cipaddr','$strq')";
                $this->myoa_sql_exec($cstr,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            }
            $mmqry2->freeResult();
            if($crecmark == 'DEL_REC') { 
                $cstr = "insert into {$dbmlogs}.`auditlogs` ( 
                LOG_USER,LOG_MODULE,LOG_DATE,LOG_TIME,LOG_TBL,LOG_KEYREC,LOG_ENUMB,LOG_FIELD,LOG_OLDVAL,LOG_NEWVAL,
                LOG_CHANGE,LOG_IPADDR,LOG_SQLEXEC) 
                 values ('$cuser','$cmodule',now(),current_time(),'$dbtbl','$ckeydata','$ckeydata','$cfld','$colddata',$cdata,
                 '$cchanges','$cipaddr','$strq')";
                $this->myoa_sql_exec($cstr,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
            }
        }
    }  //end logs_modi_audit    

    public function mysys_user() { 
        $session = session();
        return $session->get('__xsysx_myuserz__');
    }   
    
    public function msys_pw_salt() { 
        return "mysyztemmy";
    } 
    
    public function msys_is_logged() { 
        $session = session();
        return $session->get('__xsysx_myuserz_is_logged__');
    }
    
    public function mpw_tkn() { 
        return self::mysys_user() . self::msys_pw_salt();
    }
}  //end main MyLibzDBModel
