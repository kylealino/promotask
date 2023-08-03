<?php

//VARIABLE DECLARATIONS

$request = \Config\Services::request();
$mydbname = model('App\Models\MyDBNamesModel');
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MySalesModel');
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
                <label class="col-sm-3 form-label" for="voucher_trxno">Voucher Code:</label>
                <div class="col-sm-9">
                  <input type="text" id="voucher_trxno" name="voucher_trxno" placeholder="Voucher Code" style="background-color: #EAEAEA;" class="form-control form-control-sm" value="<?=$txt_buyxtakeytrxno;?>" readonly/>
                  <input type="hidden" id="__hmpromotrxnoid" name="__hmpromotrxnoid" class="form-control form-control-sm" value="<?=$buyxtakey_trxno;?>"/>
                </div>
              </div> 

              <div class="row mb-3">
                <label class="col-sm-3 form-label" for="branch_code">Branch:</label>
                <div class="col-sm-9">
                  <input type="text" data-id-brnch-name="<?=$branch_name;?>" placeholder="Branch Name" id="branch_name" name="branch_name" class="branch_name form-control form-control-sm " value="<?=$branch_name;?>" required/>
                  <input type="hidden" data-id-brnch="<?=$branch_code;?>" placeholder="Branch Name" id="branch_code" name="branch_code" class="branch_code form-control form-control-sm " value="<?=$branch_code;?>" required/>     
                </div>
              </div>
              
              <div class="row gy-2 offset-lg-3">
                <div class="col-sm-3">
                  <input type="date"  id="start_date" name="start_date" class="start_date form-control form-control-sm " value="<?=$start_date;?>" required/>
                  <label for="start_date">Start date</label>
                </div>

                <div class="col-sm-3">
                  <input type="time" id="start_time" name="start_time" class="start_time form-control form-control-sm " value="<?=$start_time;?>"  required/>
                  <label for="">Time</label>
                </div>

                <div class="col-sm-3">
                  <input type="date" id="end_date" name="end_date" class="end_date form-control form-control-sm " value="<?=$end_date;?>"  required/>
                  <label for="">End date</label>
                </div>

                <div class="col-sm-3">
                  <input type="time" id="end_time" name="end_time" class="end_time form-control form-control-sm " value="<?=$end_time;?>"  required/>
                  <label for="">Time</label>
                </div>

               
              </div> 
            </div>  
          </div>

          <!-- END HEADER DATA -->

          <!-- START DETAILS DATA -->
          <div class="row" >
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
                      <th nowrap="nowrap">Discount Value</th>
                      <th nowrap="nowrap">Voucher Code</th>

                    </tr>
                  </thead>
                  <tbody id="gwpo-recs">

                      <!-- TABLE ROW INSERTION -->

                      <tr style="display: none;">
                        <td></td>
                        <td nowrap="nowrap">
                          <button type="button" class="btn btn-xs btn-danger" style="font-size:15px; padding: 2px 6px 2px 6px; " onclick="$(this).closest('tr').remove();"><i class="bi bi-x"></i></button>
                          <input class="mitemrid" type="hidden" value=""/>
                          <input type="hidden" value=""/>
                        </td>
                        <td nowrap="nowrap"><input type="text" class="form-control form-control-sm" autocomplete="off"></td>
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
    echo $mylibzsys->memypreloader01('mepreloaderme');
    echo $mylibzsys->memsgbox1('memsgtestent','System Alert','...');
    ?>  
  </main>    
  <script type="text/javascript">

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
                jQuery('#branch_name').attr("data-id-brnch-name",ui.item.mtkn_rid);
                jQuery('#branch_code').val(ui.item.BRNCH_OCODE2);

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
            window.location = '<?=site_url();?>me-voucher-vw';
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
          try {
            
            var rowCount = jQuery('#tbl-promo tr').length;
            var mid =  (rowCount + 1);
            var clonedRow = jQuery('#tbl-promo tr:eq(' + (rowCount - 1) + ')').clone(); 

            jQuery(clonedRow).find('input[type=text]').eq(0).attr('id','mitemcode_' + mid);
            jQuery(clonedRow).find('input[type=text]').eq(1).attr('id','mitemdesc_' + mid);
            jQuery(clonedRow).find('input[type=hidden]').eq(0).attr('id','mitemrid_' + mid);
            
        
            jQuery('#tbl-promo tr').eq(rowCount - 1).before(clonedRow);
            jQuery(clonedRow).css({'display':''});
            var xobjArtItem= jQuery(clonedRow).find('input[type=text]').eq(0).attr('id');
            jQuery('#' + xobjArtItem).focus();
            $( '#tbl-promo tr').each(function(i) { 
              $(this).find('td').eq(0).html(i);
            });
            
          } catch(err) { 
            var mtxt = 'There was an error on this page.\\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\\nClick OK to continue.';
            alert(mtxt);
            return false;
      }  //end try 
    }
    

      $("#mbtn_mn_Save").click(function(e){
       
        try { 
          //__mysys_apps.mepreloader('mepreloaderme',true);
          var mtkn_mntr = jQuery('#__hmpromotrxnoid').val();
          var txt_buyxtakeytrxno = jQuery('#txt_buyxtakeytrxno').val();
          var branch_code = jQuery('#branch_code').val();
          var start_date = jQuery('#start_date').val();
          var start_time = jQuery('#start_time').val();
          var end_date = jQuery('#end_date').val();
          var end_time = jQuery('#end_time').val();
          var branch_code = jQuery('#branch_code').val();
          var mtkn_branch = jQuery('#branch_name').attr("data-id-brnch-name");
          var branch_name = jQuery('#branch_name').val();
          var rowCount1 = jQuery('#tbl-promo tr').length - 1;
          var adata1 = [];
          var adata2 = [];

          var mdata = '';
          var ninc = 0;

          for(aa = 1; aa < rowCount1; aa++) { 
            var clonedRow = jQuery('#tbl-promo tr:eq(' + aa + ')').clone(); 
            var disc = jQuery(clonedRow).find('input[type=text]').eq(0).val(); 
            var vcode = jQuery(clonedRow).find('input[type=text]').eq(1).val(); 

            var mitemc_tkn = jQuery(clonedRow).find('input[type=hidden]').eq(2).val(); 
            
            mdata = disc + 'x|x' + vcode + 'x|x' + mitemc_tkn;
            adata1.push(mdata);
            var mdat = jQuery(clonedRow).find('input[type=hidden]').eq(0).val();
            adata2.push(mdat);

            }  //end for

            var mparam = {
              mtkn_mntr:mtkn_mntr,
              txt_buyxtakeytrxno:txt_buyxtakeytrxno,
              start_date: start_date,
              start_time: start_time,
              end_date: end_date,
              end_time: end_time,
              branch_code:branch_code,
              branch_name:branch_name,
              mtkn_branch:mtkn_branch,
              adata1: adata1,
              adata2: adata2
            };  

            console.log(mparam);
            
            $.ajax({ 
              type: "POST",
              url: '<?=site_url();?>me-voucher-save',
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
          url: "<?=site_url();?>me-voucher-view",
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

  
  
</script>















