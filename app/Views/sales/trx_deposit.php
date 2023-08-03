<?php

//VARIABLE DECLARATIONS

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MySalesModel');
$mymelibsys =  model('App\Models\Mymelibsys_Model');
$mydataz = model('App\Models\MyDatumModel');
$this->dbx = $mylibzdb->dbx;
$this->db_erp = $mydbname->medb(0);
$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$branch_code = '';
$mtkn_txt_branch = '';
$branch_name = '';
$mencd_date       = date("Y-m-d");  
$buyxtakey_trxno = $request->getVar('buyxtakey_trxno');
$df_tag = $mydataz->store_df_tag();
$store_df_tag ='';
$mtkn_trxno = $request->getVar('mtkn_trxno');
$recid = '';
$nporecs = 0;
$txt_buyxtakeytrxno = '';
$branch_code = '';
$start_date = ''; 
$start_time = date('08:00');
$end_date = ''; 
$end_time = date('23:59');
$invalid_disc ='76';
$is_fixed_price ='';
$is_fixed_price_checked = '';
$is_discount_percent= '';
$is_discount_percent_checked = '';
$chkbox1 = '0';
$chkbox2 = '0';
$discount_value='';
$ART_DESC ='';
$ART_BARCODE1='';
$ART_UCOST='';
$cb_value = '';
$ART_UPRICE='';
$ART_CODE = '';
$comp_name = '';
$brnch_id = '';


//CHECK IF THERE'S A FORM OF RETRIEVAL

if(!empty($buyxtakey_trxno)) {
  $str = "
  select aa.`buyxtakey_trxno`,
  aa.`branch_code`,
  bb.`BRNCH_NAME`,
  aa.`start_date`,
  aa.`start_time`,
  aa.`end_date`,
  aa.`end_time`,
  if(aa.`is_fixed_price` = 1,1,2) p_is_fixed_price 
  from `gw_buyxtakey_hd` aa 
  join `mst_companyBranch` bb
  on aa.`branch_code` = bb.`BRNCH_OCODE2`
  join `gw_buyxtakey_dt` cc
  on aa.`buyxtakey_trxno` = cc.`buyxtakey_trxno`
  where aa.`buyxtakey_trxno` = '$buyxtakey_trxno' 
  ";

  $q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
  $rw = $q->getRowArray();
  //$mtkn_trxno = $rw['mtkn_trxno'];
  $txt_buyxtakeytrxno = $rw['buyxtakey_trxno'];
  $branch_code = $rw['branch_code'];
  $start_date = $rw['start_date'];
  $start_time = $rw['start_time'];
  $end_date = $rw['end_date'];
  $end_time = $rw['end_time'];
  $branch_name = $rw['BRNCH_NAME'];
  $is_fixed_price = ($rw['p_is_fixed_price'] == 1 ? 1 : 0);
  $is_fixed_price_checked = ($rw['p_is_fixed_price'] == 1 ? ' checked' : '');
  $is_discount_percent=  ($rw['p_is_fixed_price'] == 2 ? 2 : 0);
  $is_discount_percent_checked = ($rw['p_is_fixed_price'] == 2 ? ' checked' : '');


}


?>
<main id="main">
<?php $active_branch_data = $mymelibsys->getBranch($brnch_id);?>
  <div class="row mb-3 me-form-font">
    <span id="__me_numerate_wshe__" ></span>
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title"><i class="bi bi-pencil-square px-10"></i><span>  </span>Create Entry</h6>

          <!-- START HEADER DATA -->

          <div class="row mb-3">
            <div class="col-lg-12">

              <div class="row mb-3">
                <label class="col-sm-3 form-label" for="depctrlno_trx">Deposit Control Number:</label>
                <div class="col-sm-9">
                  <input type="text" id="depctrlno_trx" name="depctrlno_trx" placeholder="Deposit Control Number" style="background-color: #EAEAEA;" class="form-control form-control-sm"  readonly/>
                  <input type="hidden" id="__hmpromotrxnoid" name="__hmpromotrxnoid" class="form-control form-control-sm"/>
                </div>
              </div> 

              <div class="row mb-3">
                <label class="col-sm-3 form-label" for="comp_name">Company Name:</label>
                <div class="col-sm-9">
                  <input type="text" placeholder="Company Name" id="comp_name" name="comp_name" class="comp_name form-control form-control-sm " required/>  
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-3 form-label" for="branch_code">Branch:</label>
                <div class="col-sm-9">
                  <input type="text" placeholder="Branch Name" id="branch_name" name="branch_name" class="branch_name form-control form-control-sm " value="<?=$branch_name;?>" data-id="<?=$active_branch_data['mtkn_brnch']?>" required/>
                  <input type="hidden" data-id-brnch="<?=$branch_code;?>" placeholder="Branch Name" id="branch_code" name="branch_code" class="branch_code form-control form-control-sm " value="<?=$branch_code;?>" required/>     
                </div>
              </div>
              
              <div class="row gy-2 offset-lg-3">
                <div class="col-sm-4">
                  <input type="date"  id="start_date" name="start_date" class="start_date form-control form-control-sm " value="<?=$start_date;?>" required/>
                  <label for="start_date">Start date</label>
                </div>

                <div class="col-sm-4">
                  <div class="input-group">
                    <select id="opt_df" class="form-control form-control-sm">
                      <option value=""></option>
                      <option value="Draft">Draft</option>
                      <option value="Final">Final</option>
                    </select>
                  <div class="input-group-text px-1 py-0 "> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                  </div>
                  <label for="">D/F Tag</label>
                </div>

                <div class="col-sm-4">
                <div class="input-group">
                    <select id="opt_grp" class="form-control form-control-sm">
                      <option value=""></option>
                      <option value="Sales">Sales</option>
                      <option value="Other Deposit">Other Deposit</option>
                    </select>
                  <div class="input-group-text px-1 py-0 "> <i class=" bi bi-chevron-down text-dgreen"></i> </div>
                  </div>
                  <label for="">Group</label>
                </div> 
                <div class="col-sm-8">
                <form id="upload-form" action="<?php echo base_url('upload/do_upload'); ?>" method="post" enctype="multipart/form-data">
                  <div class="form-control">
                    <label for="userfile">Attach Deposit Slip</label><br>
                    <input type="file" name="userfile" id="userfile" style="display: inline-block">
                    <button type="button" id="upload-file-btn" class="btn btn-sm btn-primary">Upload File</button>
                  </div>
                </form>
                <table class="table table-striped" id="file-table">
                  <thead>
                    <tr>
                      <th>File Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($files as $file) { ?>
                      <tr>
                        <td><?php echo $file->filename; ?></td>
                        <td>
                          <button type="button" class="btn btn-info edit-file-btn" data-id="<?php echo $file->id; ?>">Edit</button>
                          <button type="button" class="btn btn-primary view-file-btn" data-bs-toggle="modal" data-bs-target="#view-file-modal" data-id="<?= $file->id; ?>">View</button>
                          <button type="button" class="btn btn-danger delete-file-btn" data-id="<?= $file->id; ?>">Delete</button>

                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              </div>  
             
            </div>  
          </div>

          <!-- END HEADER DATA -->

          <!-- START DETAILS DATA -->
          <div class="row">
            <div class="col-md-12">
              <div class=" table-responsive">
                <table class="table table-bordered table-hover table-sm text-center" id="tbl-promo">
                  
                  <!-- TABLE HEADER -->

                  <thead class="thead-light">
                    <tr>
                      <th nowrap="nowrap"></th>
                      <th nowrap="nowrap">
                        <button type="button" class="btn btn-dgreen btn-sm" onclick="javascript:my_add_line_item_promo();" >
                          <i class="bi bi-plus"></i>
                        </button>
                      </th>
                      <th nowrap="nowrap">Bank Name</th>
                      <th nowrap="nowrap">Account Number</th>
                      <th nowrap="nowrap">Date of Actual Deposit</th>
                      <th nowrap="nowrap">Sales</th>
                      <th nowrap="nowrap">Shopeepay</th>
                      <th nowrap="nowrap">Expense</th>
                      <th nowrap="nowrap">Amount Deposited</th>
                      <th nowrap="nowrap">Remarks</th>
                    </tr>
                  </thead>
                  <tbody id="gwpo-recs">

                      <!-- TABLE ROW INSERTION -->

                      <tr style="display: none; ma-5">
                        <td></td>
                        <td nowrap="nowrap">
                          <button type="button" class="btn btn-xs btn-danger" style="font-size:15px; padding: 2px 6px 2px 6px; " onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                          <input class="mitemrid" type="hidden" value=""/>
                          <input type="hidden" value=""/>
                        </td>
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm bankName" ></td>
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" autocomplete="off"></td>
                        <td nowrap="nowrap"><input type="date" class="form-control form-control-sm" ></td>
                        <td nowrap="nowrap"><input type="text" placeholder="0.00" class="form-control form-control-sm " ></td>
                        <td nowrap="nowrap"><input type="text" placeholder="0.00" class="form-control form-control-sm" ></td>
                        <td nowrap="nowrap"><input type="text" placeholder="0.00" class="form-control form-control-sm" ></td>
                        <td nowrap="nowrap"><input type="text" placeholder="0.00" class="form-control form-control-sm" ></td>
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" autocomplete="off"></td> 
                        
                        <!-- FOR RETRIEVAL OF EXISTING PROMO TRANSACTION NO. DATA -->

                        <?php if(!empty($buyxtakey_trxno)): 
                          
                          $str = "
                          select
                          aa.`buyxtakey_trxno`,
                          cc.`qty`,
                          cc.`take`,
                          cc.`prod_barcode_buy`,
                          cc.`prod_barcode_take`,
                          dd.`ART_CODE`,
                          dd.`ART_DESC`,
                          dd.`ART_BARCODE1`


                          from `gw_buyxtakey_hd` aa 
                          join `mst_companyBranch` bb
                          on aa.`branch_code` = bb.`BRNCH_OCODE2`
                          join `gw_buyxtakey_dt` cc
                          on aa.`buyxtakey_trxno` = cc.`buyxtakey_trxno`
                          join `mst_article` dd
                          on 
                          cc.`prod_barcode_buy` = dd.`ART_BARCODE1`
                          where aa.`buyxtakey_trxno` = '$buyxtakey_trxno' 
                          ";
                          
                          $q = $mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                          $rw = $q->getResultArray();
                          foreach ($rw as $data) {
                            $qty = $data['qty'];
                            $take = $data['take'];
                            $prod_barcode_take = $data['prod_barcode_take'];
                            $prod_barcode_buy = $data['prod_barcode_buy'];
                            $ART_CODE=$data['ART_CODE'];
                            $ART_DESC = $data['ART_DESC'];
                            $ART_BARCODE1 = $data['ART_BARCODE1'];
                            var_dump($data)
                            ?>
                            
                            
                            <!-- DISPLAY ROW WITH VALUE BASE ON PROMO TRX -->

                            <tr>
                              <td></td>
                              <td nowrap="nowrap">
                                <button type="button" class="btn btn-xs btn-danger" style="font-size:15px; padding: 2px 6px 2px 6px; " onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                                <input class="mitemrid" type="hidden" value=""/>
                                <input type="hidden" value=""/>
                              </td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm mitemcode" value="<?=$ART_CODE;?>"></td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" value="<?=$ART_DESC;?>" style="background-color: #EAEAEA;" readonly></td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" value="<?=$prod_barcode_buy;?>" style="background-color: #EAEAEA;" readonly></td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm mitemcode2" value="<?=$ART_CODE;?>"></td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" value="<?=$ART_DESC;?>" style="background-color: #EAEAEA;" readonly></td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" value="<?=$prod_barcode_take;?>" style="background-color: #EAEAEA;" readonly></td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" value="<?=$qty;?>" autocomplete="off"></td>
                              <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" value="<?=$take;?>" autocomplete="off"></td> 
                              
                            </tr>
                            <?php 
                          }
                          
                          ?>
                        <?php endif;?>    

                      </tbody>
                    </table>
                  </div>
                </div>
              </div> 

              <!-- END DETAILS DATA -->
              
              <div class="row gy-2 mb-3">
                <div class="col-sm-4">
                  <?php if(!empty($buyxtakey_trxno)):?>
                    <button id="mbtn_mn_Save" type="button" style="background-color: #167F92; color: #FFF;" class="btn btn-dgreen btn-sm" disabled>Posted</button> 
                  <?php else:?>
                    <button id="mbtn_mn_Save" type="button" style="background-color: #167F92; color: #FFF;" class="btn btn-dgreen btn-sm">Save</button>   
                  <?php endif;?>
                  <button id="mbtn_mn_NTRX" type="button" class="btn btn-primary btn-sm">New Entry</button>
                </div>
              </div> <!-- end Save Records -->
            </div> <!-- end card-body -->
          </div>
        </div>
        
        <!-- HEADER AND FOR APPROVAL PAGE TAB -->

        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Records</h6>
              <div class="pt-2 bg-dgreen mt-2" style="background-color: #167F92;"> 
               <nav class="nav nav-pills flex-column flex-sm-row  gap-1 px-2 fw-bold">
                <a id="anchor-list" class="flex-sm-fill text-sm-center mytab-item active p-2  rounded-top"  aria-current="page" href="#"> <i class="bi bi-ui-checks"> </i> Records</a>
                <a id="anchor-items" class=" flex-sm-fill text-sm-center mytab-item  p-2 rounded-top " href="#"><i class="bi bi-ui-radios"></i> For Approval</a>
              </nav>
            </div>
            
            <!-- DISPLAY OF RECORDS AND APPROVAL -->

            <div id="packlist" class="text-center p-2 rounded-3  mt-3 border-dotted bg-light p-4 ">
              <?php

              ?> 
            </div> 
          </div>
        </div>
      </div>
    </div> 
    <?php
    echo $mylibzsys->memsgbox1('memsgtestent_danger','<i class="bi bi-exclamation-circle"></i> System Alert','...','bg-pdanger');
    echo $mylibzsys->memsgbox1('memsgtestent_dangerr','<i class="bi bi-exclamation-circle"></i> System Alert','No branch is selected.','bg-pdanger');
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','');
    ?>  
  </main>    
  <script type="text/javascript">
    
    __my_item_lookup();
    

    jQuery('.branch_name')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-branch-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#branch_name').val(terms);
                jQuery('#branch_name').attr("data-id",ui.item.mtkn_rid);
                jQuery('#branch_code').val(ui.item.BRNCH_OCODE2);
                jQuery('#comp_name').val(ui.item.COMP_NAME);
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
              
            })
            
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    }); //whse

    jQuery('')
        // don't navigate away from the field on tab when selecting an item
        .bind( 'keydown', function( event ) {
          if ( event.keyCode === jQuery.ui.keyCode.TAB &&
            jQuery( this ).data( 'ui-autocomplete' ).menu.active ) {
            event.preventDefault();
        }
        if( event.keyCode === jQuery.ui.keyCode.TAB ) {
          event.preventDefault();
        }
      })
        .autocomplete({
          minLength: 0,
          source: '<?= site_url(); ?>get-comp-list',
          focus: function() {
                // prevent value inserted on focus
                return false;
              },
              select: function( event, ui ) {
                var terms = ui.item.value;
                
                jQuery('#comp_name').val(terms);
                jQuery(this).autocomplete('search', jQuery.trim(terms));

                return false;

                
              }
            })
        .click(function() {
          var terms = this.value;
          jQuery(this).autocomplete('search', jQuery.trim(terms));
          
    }); //whse

        
        $('#mbtn_mn_NTRX').click(function() { 
          var userselection = confirm("Are you sure you want to new transaction?");
          if (userselection == true){
            window.location = '<?=site_url();?>me-buyxtakey-vw';
          }
          else{
            $.hideLoading();
            return false;
          } 
        });

        function __do_makeid(){
          var text = '';
          var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

          for( var i=0; i < 7; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

          return text;
        }


        function my_add_line_item_promo() {  
          var brnch = jQuery('#branch_name').val();
          if (brnch == '') {
            jQuery('#memsgtestent_dangerr').modal('show');
            die();
          }
          else{
            try {
            
            var rowCount = jQuery('#tbl-promo tr').length;
            var mid =  (rowCount + 1);
            var clonedRow = jQuery('#tbl-promo tr:eq(' + (rowCount - 1) + ')').clone(); 

            jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','mitemcode_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','mitemdesc_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(2).attr('id','mitembcode_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(3).attr('id','mitemdisc_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(4).attr('id','mitemprice_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(5).attr('id','mitemdiscsrp_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(6).attr('id','mitemcost_' + mid);
            
            jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','mitemrid_' + mid);
            
            
            jQuery('#tbl-promo tr').eq(rowCount - 1).before(clonedRow);
            jQuery(clonedRow).css({'display':''});
            var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
            jQuery('#' + xobjArtItem).focus();
            $( '#tbl-promo tr').each(function(i) { 
              $(this).find('td').eq(0).html(i);
            });
            
            __my_item_lookup();
            
          } catch(err) { 
            var mtxt = 'There was an error on this page.\\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\\nClick OK to continue.';
            alert(mtxt);
            return false;
      }  //end try 
          }
          
    }
    
    
    function __my_item_lookup(){
      jQuery('.bankName' ) 
          // don't navigate away from the field on tab when selecting an item
          .bind( 'keydown', function( event ) {
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
              jQuery( this ).data( 'autocomplete' ).menu.active ) {
              event.preventDefault();
          }
          if( event.keyCode === jQuery.ui.keyCode.TAB ) {
            event.preventDefault();
          }
        })
          .autocomplete({
            minLength: 0,
            source: '<?= site_url(); ?> get-deposit-no',
            focus: function() {
                  // prevent value inserted on focus
                  return false;
                },
                search: function(oEvent, oUi) { 
                  var sValue = jQuery(oEvent.target).val();
                  var brnch = jQuery('#branch_name').attr("data-id");
                  jQuery(this).autocomplete('option', 'source', '<?=site_url();?>get-deposit-no/?mtkn_brnch=' + brnch); 
                  },
                select: function( event, ui ) {
                  var terms = ui.item.value;
                  
                  jQuery(this).attr('alt', jQuery.trim(ui.item.value));
                  jQuery(this).attr('title', jQuery.trim(ui.item.value));
                  
                  this.value = ui.item.value;
                  

                  var clonedRow = jQuery(this).parent().parent().clone();
                  var indexRow = jQuery(this).parent().parent().index();
                  var xobjitemrid = jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id'); //ID
                  var xobjitemdesc = jQuery(clonedRow).find('input[type=text]').eq(1).attr('id');/*DESC*/

                  
                  $('#' + xobjitemrid).val(ui.item.mtkn_rid);
                  $('#' + xobjitemdesc).val(ui.item.acctNO);

                  

                  return false;
                }
              })
          .click(function() { 

              //jQuery(this).keydown(); 
              var terms = this.value;
              //jQuery(this).autocomplete('search', '');
              jQuery(this).autocomplete('search', jQuery.trim(terms));
            });  
        }
        
        function __pack_totals() { 

          try { 
            var rowCount1 = jQuery('#tbl-promo tr').length - 1;
            var adata1 = [];
            var adata2 = [];
            var mdata = '';
            var ninc = 0;
            var nTAmount = 0;
            var nTQty = 0;
            for(aa = 1; aa < rowCount1; aa++) { 
              var clonedRow = jQuery('#tbl-promo tr:eq(' + aa + ')').clone(); 
              var qty = jQuery(clonedRow).find('input[type=text]').eq(4).val();
              var xTAmntId = jQuery(clonedRow).find('input[type=text]').eq(5).attr('id');

              var nqty = 0;
              var nprice = 0;
              
              if($.trim(qty) == '') { 
                nqty = 0;
              } else { 
                
                nqty = qty;
              }

              if($.trim(xTAmntId) == '') { 
                nprice2 = 0;
              } else { 
                nprice2 = xTAmntId;
              }
              
              var ntqty = parseFloat(nqty);
              if($('#' + xTAmntId).val()==''){
                var ntprice = parseFloat(ntqty * 1);
              }
              else{

                var ntprice = parseFloat(ntqty * 1);
              }

              if(!isNaN(ntprice) || ntprice > 0) { 
                $('#' + xTAmntId).val(ntprice);
              // console.log(xTAmntId);
            }
            }  //end for 
            
          } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
            $.hideLoading();
            return false;
        }  //end try            
      }

      $('#tbl-promo').on('keydown', "input", function(e) { 
        switch(e.which) {
          case 37: // left 
          break;

          case 38: // up
          var nidx_rw = jQuery(this).parent().parent().index();
          var nidx_td = $(this).parent().index();
          if(nidx_td == 3) { 
          } else { 
            var clonedRow = jQuery('#tbl-promo  tr:eq(' + (nidx_rw) + ')').clone(); 
            var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
            $('#' + el_id).focus();
          }
          
          break;

          case 39: // right
          break;

          case 40: // down
          var nidx_rw = jQuery(this).parent().parent().index();
          var nidx_td = $(this).parent().index();
          if(nidx_td == 3) { 
          } else { 
            var clonedRow = jQuery('#tbl-promo  tr:eq(' + (nidx_rw + 2) + ')').clone(); 
            var el_id = jQuery(clonedRow).find('td').eq(nidx_td).find('input[type=text]').eq(0).attr('id');
                  //alert(nidx_rw + ':' + nidx_td + ':' + el_id);
                  $('#' + el_id).focus();
                }
                
                break;
          default: return; // exit this handler for other keys
        }
      //e.preventDefault(); // prevent the default action (scroll / move caret)
    });

      

      $("#mbtn_mn_Save").click(function(e){
       
        try { 
          //__mysys_apps.mepreloader('mepreloaderme',true);
          var mtkn_mntr = jQuery('#__hmpromotrxnoid').val();
          var depctrlno_trx = jQuery('#depctrlno_trx').val();
          var branch_code = jQuery('#branch_code').val();
          var start_date = jQuery('#start_date').val();
          var branch_name = jQuery('#branch_name').val();
          var comp_name = jQuery('#comp_name').val();
          var opt_df = jQuery('#opt_df').val();
          var opt_grp = jQuery('#opt_grp').val();
          var rowCount1 = jQuery('#tbl-promo tr').length - 1;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
            var clonedRow = jQuery('#tbl-promo tr:eq(' + aa + ')').clone(); 
            var bname = jQuery(clonedRow).find('input[type=text]').eq(0).val(); 
            var acctno = jQuery(clonedRow).find('input[type=text]').eq(1).val(); 
            var salesdate = jQuery(clonedRow).find('input[type=text]').eq(2).val();
            var sales = jQuery(clonedRow).find('input[type=text]').eq(3).val(); 
            var shopeepay = jQuery(clonedRow).find('input[type=text]').eq(4).val(); 
            var expense = jQuery(clonedRow).find('input[type=text]').eq(5).val(); 
            var amountdeposit = jQuery(clonedRow).find('input[type=text]').eq(6).val(); 
            var rmks = jQuery(clonedRow).find('input[type=text]').eq(7).val(); 
            var mitemc_tkn = jQuery(clonedRow).find('input[type=hidden]').eq(8).val(); 
            
            mdata = bname + 'x|x' + acctno + 'x|x' + salesdate + 'x|x' + sales + 'x|x' + shopeepay + 'x|x' + expense + 'x|x' + amountdeposit + 'x|x' + rmks + 'x|x' + mitemc_tkn;
            adata1.push(mdata);
            var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
            adata2.push(mdat);

            }  //end for

            var mparam = {
              mtkn_mntr:mtkn_mntr,
              depctrlno_trx:depctrlno_trx,
              branch_code: branch_code,
              start_date: start_date,
              branch_name: branch_name,
              comp_name:comp_name,
              opt_df: opt_df,
              opt_grp:opt_grp,
              adata1: adata1,
              adata2: adata2
            };  

            console.log(mparam);
            
            $.ajax({ 
              type: "POST",
              url: '<?=site_url();?>me-deposit-save',
              context: document.body,
              data: eval(mparam),
              global: false,
              cache: false,
              success: function(data)  { 
                $(this).prop('disabled', false);
           // $.hideLoading();
           jQuery('#memsgtestent_bod').html(data);
           jQuery('#memsgtestent').modal('show');
           return false;
         },
         error: function() {
          alert('error loading page...');
         // $.hideLoading();
         return false;
       } 
     });

          } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
    }  //end try
    return false; 
  });

      __mysys_apps.mepreloader('mepreloaderme',false);

      $('#anchor-list').on('click',function(){
        $('#anchor-list').addClass('active');
        $('#anchor-items').removeClass('active');
        var mtkn_whse = '';
        mypack_view_recs(mtkn_whse);

      });

      function mypack_view_recs(mtkn_whse){ 
        var ajaxRequest;

        ajaxRequest = jQuery.ajax({
          url: "<?=site_url();?>me-buyxtakey-view",
          type: "post",
          data: {
            mtkn_whse: mtkn_whse
          }
        });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
    ajaxRequest.done(function(response, textStatus, jqXHR) {
      jQuery('#packlist').html(response);
      __mysys_apps.mepreloader('mepreloaderme',false);
    });
  };

  $('#anchor-items').on('click',function(){
    $('#anchor-items').addClass('active');
    $('#anchor-list').removeClass('active');
    var mtkn_whse = '';
    mypack_view_appr(mtkn_whse);

  });

  function mypack_view_appr(mtkn_whse){ 
    var ajaxRequest;

    ajaxRequest = jQuery.ajax({
      url: "<?=site_url();?>me-buyxtakey-view-appr",
      type: "post",
      data: {
        mtkn_whse: mtkn_whse
      }
    });

    // Deal with the results of the above ajax call
    __mysys_apps.mepreloader('mepreloaderme',true);
    ajaxRequest.done(function(response, textStatus, jqXHR) {
      jQuery('#packlist').html(response);
      __mysys_apps.mepreloader('mepreloaderme',false);
    });
  };

  let selectedtxt = 'sampletext';
  const txt = document.getElementById('output')
  const selectCb = (cbElement) => {
    const checkboxes = document.getElementsByName('cb')
    var lblfix = document.getElementsByName('fixedlbl');
    var is_fixed_price = jQuery('#is_fixed_price').prop('checked');
    var cb_value_fix = (is_fixed_price) ? (1) : (0);
    var is_discount_percent = jQuery('#is_discount_percent').prop('checked');;
    var cb_value_percent = (is_discount_percent) ? (1) : (0);
    checkboxes.forEach(cb =>  {
      
    })
    

  }

  $(document).ready(function() {
    $('#upload-file-btn').click(function(event) {
        event.preventDefault();
        var form = $('#upload-form')[0];
        var url = "<?php echo site_url('/upload/do_upload'); ?>";
        var formData = new FormData();

        formData.append('userfile', $('#userfile')[0].files[0]);
        
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('File uploaded successfully!');
                     // Update the table with the latest data
                $('#file-table tbody').html('');
                $.each(response.files, function(i, file) {
                    $('#file-table tbody').append('<tr><td>' + file.filename + '</td><td>' + file.description + '</td><td>' + file.age + '</td><td>' + file.uploaded_by + '</td><td>' + file.created_at + '</td><td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view-file-modal" data-id="' + file.id + '">View</button></td></tr>');
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('Error uploading file.');
            }
        });
    });
});


$(document).on('click', '.delete-file-btn', function() {
    var id = $(this).data('id');
    if (confirm('Are you sure you want to delete this file?')) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Meupload/delete_file"); ?>',
            data: { id: id },
            success: function(response) {
                if (response.success) {
                    // Reload the table to remove the deleted row
                    location.reload();
                }
            }
        });
    }
});


$(document).on('click', '.view-file-btn', function() {
    var id = $(this).data('id');
    $.ajax({
        type: 'POST',
        url: '<?php echo site_url("Meupload/view_file"); ?>',
        data: { id: id },
        success: function(response) {
            $('#file-id').text(response.id);
            $('#file-name').text(response.filename);
            $('#file-description').text(response.description);
            $('#img_file_src').attr('src','<?php echo base_url("uploads/meuploadhehe/");?>'+'/'+response.filename);
            $('#view-file-modal').modal('show');
        }
    });
});



$(document).on('click', '.edit-file-btn', function() {
  var fileId = $(this).data('id');
  var fileName = $(this).closest('tr').find('td:eq(1)').text();
  var fileDesc = $(this).closest('tr').find('td:eq(2)').text();

  $('#edit-file-id').val(fileId);
  $('#edit-file-name').val(fileName);

  $('#editFileModal').modal('show');
});
  
</script>















