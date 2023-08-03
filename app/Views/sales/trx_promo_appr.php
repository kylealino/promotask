<?php 

$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');
$mytrxpurch = model('App\Models\MySalesModel');
$cuser = $mylibzdb->mysys_user();
$mpw_tkn = $mylibzdb->mpw_tkn();
$mytxtsearchrec_appr = $request->getVar('txtsearchedrec');
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
<?=form_open('','class="needs-validation-search" id="myfrmsearchrec_appr" ');?>
    <div class="col-md-6 mb-1">
        <div class="input-group input-group-sm">
            <label class="input-group-text fw-bold" for="search">Search:</label>
            <input type="text" id="mytxtsearchrec_appr" class="form-control form-control-sm" name="mytxtsearchrec_appr" placeholder="Search" />
           	<button type="submit" class="btn btn-dgreen btn-sm"><i class="bi bi-search"></i></button>
        </div>
    </div>
<?=form_close();?> <!-- end of ./form -->


<div class="col-md-8">
	<?=$mylibzsys->mypagination($npage_curr,$npage_count,'__myredirected_rsearch_appr','');?>
</div>

<!-- START APPROVAL HEADER -->

<div class="table-responsive">
	<div class="col-md-12 col-md-12 col-md-12">
		<table class="table table-condensed table-hover table-bordered table-sm">
			<thead>
				<tr>
					<th class="text-center">
						Status
					</th>
					<th>Promo Discount No.</th>
					<th>Branch Code</th>
					<th>Start Date</th>
					<th>Time</th>
					<th>End Date</th>
					<th>Time</th>
					<th>Is fixed price?</th>
					<th>Date Encoded</th> 		
				</tr>
			</thead>

			<!-- END HEADER  -->

			<!-- START DETAILS VALUE -->

			<tbody>
				<?php 
				if($rlist !== ''):
					$nn = 1;
					foreach($rlist as $row): 
						
						$bgcolor = ($nn % 2) ? "#EAEAEA" : "#F2FEFF";
						$on_mouse = " onmouseover=\"this.style.backgroundColor='#97CBFF';\" onmouseout=\"this.style.backgroundColor='" . $bgcolor  . "';\"";	
						$mtkn_recid = hash('sha384', $row['recid'] . $mpw_tkn);
						$dis = ($row['is_approved'] == 'Y' ? "disabled" : '');
						
					?>
					<tr bgcolor="<?=$bgcolor;?>" <?=$on_mouse;?>>
						<td>
							<button class="btn btn-dgreen btn-sm" Style="background-color: #167F92; color: #FFF; padding: 2px 6px 2px 6px; " onclick="javascript:save_for_approve_promo('<?=$mtkn_recid;?>');" <?=$dis;?>> <i class="bi bi-print"></i> For Approved</button>
						</td>
						<td nowrap><?=$row['promo_trxno'];?></td>
						<td nowrap><?=$row['branch_code'];?></td>
						<td nowrap><?=$row['start_date'];?></td>
						<td nowrap><?=$row['start_time'];?></td>
						<td nowrap><?=$row['end_date'];?></td>
						<td nowrap><?=$row['end_time'];?></td>
						<td nowrap><?=$row['is_fixed_price'];?></td>
						<td nowrap><?= $mylibzsys->mydate_mmddyyyy($row['encd_date']);?></td>
					</tr>
					<?php 
						$nn++;
						endforeach;
						else:
					?>
					<tr>
						<td colspan="18">No data was found.</td>
					</tr>
				<?php endif; ?>
			</tbody>
			
			<!-- END DETAILS VALUE -->

		</table>
	</div>
</div>
	
<script type="text/javascript"> 

	function __myredirected_rsearch_appr(mobj) { 
		try { 
			__mysys_apps.mepreloader('mepreloaderme',true);
			var txtsearchedrec = jQuery('#mytxtsearchrec_appr').val();
			
            //mytrx_sc/mndt_sc2_recs
			var mparam = { 
				txtsearchedrec: txtsearchedrec,
				mpages: mobj 
			};	
			jQuery.ajax({ // default declaration of ajax parameters
			type: "POST",
			url: '<?=site_url();?>me-promo-appr',
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
	
	
	jQuery('#mytxtsearchrec_appr').keypress(function(event) { 
		if(event.which == 13) { 
			event.preventDefault(); 
			try { 
				__mysys_apps.mepreloader('mepreloaderme',true);
				var txtsearchedrec = jQuery('#mytxtsearchrec_appr').val();

				var mparam = {
					txtsearchedrec: txtsearchedrec,
					mpages: 1 
				};	

				jQuery.ajax({ // default declaration of ajax parameters
				type: "POST",
				url: '<?=site_url();?>me-promo-appr',
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
						var txtsearchedrec = jQuery('#mytxtsearchrec_appr').val();

						var mparam = {
							txtsearchedrec: txtsearchedrec,
							mpages: 1 
						};	
						
						jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: '<?=site_url();?>me-promo-appr',
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
	function save_for_approve_promo(mtkn_recid){
		try { 
            __mysys_apps.mepreloader('mepreloaderme',true);
            
                    var mparam = {
                        mtkn_recid: mtkn_recid

                    }; 
                   //console.log(mparam);
                  jQuery.ajax({ // default declaration of ajax parameters
                    type: "POST",
                    url: '<?=site_url();?>me-promo-appr-save',
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
