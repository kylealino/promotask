<?php 

$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxfgpack = model('App\Models\MySalesModel');

$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();

$mytxtsearchrec = $request->getVar('txtsearchedrec');


$data = array();
$mpages = (empty($mylibzsys->oa_nospchar($request->getVar('mpages'))) ? 0 : $mylibzsys->oa_nospchar($request->getVar('mpages')));
$mpages = ($mpages > 0 ? $mpages : 1);
$apages = array();
$mpages = $npage_curr;
$npage_count = $npage_count;
for($aa = 1; $aa <= $npage_count; $aa++) {
	$apages[] = $aa . "xOx" . $aa;
}


?>
<style>
table.memetable, th.memetable, td.memetable {
  border: 1px solid #F6F5F4;
  border-collapse: collapse;
}
thead.memetable, th.memetable, td.memetable {
  padding: 6px;
}
</style>

<main id="main">
    
    <div class="row mb-3 me-form-font">
        <span id="__me_numerate_wshe__" ></span>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">OAHAHAHAHAHAHAHAH</h1>
                    <div class="row">
                        <div class="col-xxl-3 col-md-3">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Active Promos</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        </div>
                                        <div class="ps-3">
                                            <h4>200</h4>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                        </div> <!-- End Sales Card --> 
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card sales-card">       
                                <div class="card-body">
                                    <h5 class="card-title">Upcoming expirations <span>| This Week</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center height:100vh justify-content-center">
                                        </div>
                                        <div class="ps-3">
                                            <h4>ABRA - PD2301060000000081 - <span style="color:red">January 2, 2023</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Sales Card --> 
                        <div class="col-xxl-3 col-md-3">
                            <div class="card info-card sales-card">
                            
                                <div class="card-body">
                                    <h5 class="card-title">Expired Promos <span>| This month</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        </div>
                                        <div class="ps-3">
                                            <h4>1400</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Sales Card --> 
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?=form_open('me-fg-packing-recs','class="needs-validation-search" id="myfrmsearchrec" ');?>
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text fw-bold" for="search">Search:</label>
                                    <input type="text" id="mytxtsearchrec" class="form-control form-control-sm" name="mytxtsearchrec" placeholder="Search" />
                                    <button type="submit" class="btn btn-dgreen btn-sm" style="background-color:#167F92; color:#fff;"><i class="bi bi-search"></i></button>
                                </div>
                            <?=form_close();?> <!-- end of ./form -->
                        </div>
                        <div class="col-md-2">
  
                            <input type="date"  id="start_date" name="start_date" class="start_date form-control form-control-sm " required/>
                            <label for="start_date" class="mt-1">Start date</label>
                        </div>
                        <div class="col-md-2">
                            
                            <input type="date"  id="end_date" name="end_date" class="end_date form-control form-control-sm " required/>
                            <label for="end_date" class="mt-1">End date</label>
                             
                        </div>
                    </div>
                 
                    <div class="table-responsive">
                        <div class="col-md-12 col-md-12 col-md-12">
                            <table class="table table-condensed table-hover table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Branch Name</th>
                                        <th>Branch Code</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($rlist !== ''):
                                        $nn = 1;
                                        foreach($rlist as $row): 
                                            
                                            $bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
                                            $on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
                                            
                                        ?>
                                        <tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
                                            <td nowrap></td>
                                            <td nowrap><?=$row['branch_code'];?></td>
                                            <td nowrap><?=$row['start_date'];?></td>
                                            <td nowrap><?=$row['end_date'];?></td>     
                                        </tr>
                                        <?php 
                                        $nn++;
                                        endforeach;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="18">No data was found.</td>
                                        </tr>
                                    <?php 
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end table-reponsive -->

                    <div class="col-md-12">
	                    <?=$mylibzsys->mypagination($npage_curr,$npage_count,'__myredirected_rsearch','');?>
	                </div>
                </div>
            </div>
        </div>
    </div>
</main>






	
<script type="text/javascript"> 

    // function meSetCellPadding () {
    //     var metable = document.getElementById ("tbldata_cust");
    //     metable.cellPadding = 6;
    //     metable.style.border = "1px solid #C0BCB6";
    //     var tabletd = metable.getElementsByTagName("td");
    // }
    // meSetCellPadding();

	function __myredirected_rsearch(mobj) { 
		try { 
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec = jQuery('#mytxtsearchrec').val();
			


            //mytrx_sc/mndt_sc2_recs
			var mparam = { 
				txtsearchedrec: txtsearchedrec,
				mpages: mobj 
			};	
			jQuery.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>me-promo-recs',
			context: document.body,
			data: eval(mparam),
			global: false,
			cache: false,
				success: function(data)  { //display html using divID
						__mysys_apps.mepreloader('mepreloaderme',false);
						$('#packlist').html(data);
						
						return false;
				},
				error: function() { // display global error on the menu function
					__mysys_apps.mepreloader('mepreloaderme',false);
					alert('error loading page...');
					
					return false;
				}	
			});			
								
		} catch(err) {
			var mtxt = 'There was an error on this page.\n';
			mtxt += 'Error description: ' + err.message;
			mtxt += '\nClick OK to continue.';
			__mysys_apps.mepreloader('mepreloaderme',false);
			alert(mtxt);
			return false;

		}  //end try
	}	
	
	
	jQuery('#mytxtsearchrec').keypress(function(event) { 
		if(event.which == 13) { 
			event.preventDefault(); 
			try { 
				__mysys_apps.mepreloader('mepreloaderme',true);
				var txtsearchedrec = jQuery('#mytxtsearchrec').val();

				var mparam = {
					txtsearchedrec: txtsearchedrec,
					mpages: 1 
				};	

				jQuery.ajax({ // default declaration of ajax parameters
				type: "POST",
				url: '<?=site_url();?>me-promo-recs',
				context: document.body,
				data: eval(mparam),
				global: false,
				cache: false,
					success: function(data)  { //display html using divID
							jQuery('#packlist').html(data);
							__mysys_apps.mepreloader('mepreloaderme',false);
							return false;
					},
					error: function() { // display global error on the menu function
						__mysys_apps.mepreloader('mepreloaderme',false);
						alert('error loading page...');
						return false;
					}	
				});	
			} catch(err) { 
				var mtxt = 'There was an error on this page.\n';
				mtxt += 'Error description: ' + err.message;
				mtxt += '\nClick OK to continue.';
				__mysys_apps.mepreloader('mepreloaderme',false);
				alert(mtxt);
				return false;
			}  //end try	
			
		}
	});	
	

	(function () {
		'use strict'

		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.needs-validation-search')
		// Loop over them and prevent submission
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				form.classList.add('was-validated') 

				try {
					event.preventDefault();
          			event.stopPropagation();


					//start here
					try { 
						__mysys_apps.mepreloader('mepreloaderme',true);
						var txtsearchedrec = jQuery('#mytxtsearchrec').val();

						var mparam = {
							txtsearchedrec: txtsearchedrec,
							mpages: 1 
						};	
						
						jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: '<?=site_url();?>me-promo-recs',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
							success: function(data)  { //display html using divID
									__mysys_apps.mepreloader('mepreloaderme',false);
									jQuery('#packlist').html(data);
									
							},
							error: function() { // display global error on the menu function
								__mysys_apps.mepreloader('mepreloaderme',false);
								alert('error loading page...');
								
							}	
						});			
									
					} catch(err) { 
						__mysys_apps.mepreloader('mepreloaderme',false);
						var mtxt = 'There was an error on this page.\n';
						mtxt += 'Error description: ' + err.message;
						mtxt += '\nClick OK to continue.';
						alert(mtxt);
					}  //end try

					//end here



				} catch(err) { 
					__mysys_apps.mepreloader('mepreloaderme',false);
					var mtxt = 'There was an error on this page.\n';
					mtxt += 'Error description: ' + err.message;
					mtxt += '\nClick OK to continue.';
					alert(mtxt);
					return false;
				}  //end try					
			}, false)
		})
	})();	
	function fg_pack_bcode_gen(mtkn_fgpacktr){
		try { 
            __mysys_apps.mepreloader('mepreloaderme',true);
            
                    var mparam = {
                        mtkn_fgpacktr: mtkn_fgpacktr

                    }; 
                   //console.log(mparam);
                  jQuery.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>me-fg-packing-bar-generate',
                    context: document.body,
                    data: eval(mparam),
                    global: false,
                    cache: false,

                    success: function(data)  { //display html using divID
                        __mysys_apps.mepreloader('mepreloaderme',false);
                        jQuery('#memsgtestent_bod').html(data);
           				jQuery('#memsgtestent').modal('show');
                        return false;
                    },
                    error: function() { // display global error on the menu function
                        alert('error loading page...');
                       __mysys_apps.mepreloader('mepreloaderme',false);
                        return false;
                    }   
        }); 
        } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
             __mysys_apps.mepreloader('mepreloaderme',false);
            return false;
        }  //end try            
	}
	function __mbtn_promo_bdownload(promo_trxno){
		try { 
            __mysys_apps.mepreloader('mepreloaderme',true);
            
                    var mparam = {
                        promo_trxno: promo_trxno

                    }; 
                   //console.log(mparam);
                  jQuery.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>me-promo-barcode-dl',
                    context: document.body,
                    data: eval(mparam),
                    global: false,
                    cache: false,

                    success: function(data)  { //display html using divID
                        __mysys_apps.mepreloader('mepreloaderme',false);
                        jQuery('#memsgtestent_bod').html(data);
           				jQuery('#memsgtestent').modal('show');
                        return false;
                    },
                    error: function() { // display global error on the menu function
                        alert('error loading page...');
                       __mysys_apps.mepreloader('mepreloaderme',false);
                        return false;
                    }   
        }); 
        } catch(err) {
            var mtxt = 'There was an error on this page.\n';
            mtxt += 'Error description: ' + err.message;
            mtxt += '\nClick OK to continue.';
            alert(mtxt);
             __mysys_apps.mepreloader('mepreloaderme',false);
            return false;
        }  //end try            
	}
	
	
</script>
