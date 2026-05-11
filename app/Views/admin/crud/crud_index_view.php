<?php

use Faker\Extension\CountryExtension;

$this->extend('backend/_layout_/layout');
$this->section('content');
?>
    <div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">جستجوی موارد ثبت شده</h5>
			<div class="heading-elements">
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            	</ul>
        	</div>
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12">
					<form action="" method="post" enctype="multipart/form-data">
						<?php 
						helper('form');
						$extra = ['class' => 'form-control search-input' ];
						
						foreach( $search_fields as $search_field => $input ) :
						?>
						<div class="col-md-3">
							<label><?php echo array_key_exists( $search_field, $fields_name) ? $fields_name[$search_field] : lang( 'FieldsText.' . $search_field ,[],'fa') ; ?></label>
							<?php
							if ( $input['input'] == 'form_input' ) :
								echo form_input( array_merge($input['data'], ['id'=>$search_field, 'name'=>$search_field] ) , '', $extra, $input['type']);
							elseif( $input['input'] == 'form_dropdown' ) :
								echo form_dropdown( $search_field, [ (array_key_exists(0,$input['options']) ? '' :  0 )  => 'انتخاب کنید' ] + $input['options'], null, array_merge( $extra, $input['data'], ['id'=>$search_field] ) );
							elseif( $input['input'] == 'form_jdate_input' ) :
								echo form_input( array_merge($input['data'], ['id'=>$search_field, 'name'=>$search_field] ) , '', [ 'class' => $extra['class'] . ' persian-date-picker' ], $input['type']);
							endif ;
							?>
						</div>
						<?php
						endforeach ;
						
						if( isset($excel_btn) && $excel_btn ):
						?>
						<div class="col-md-2">
						<label> &nbsp </label>
							<button onclick="showPage(null, true);" type="button" class="btn btn-primary form-control"><i class="icon-download position-left"></i>دریافت اکسل</button>
						</div>		
						<?php
						endif ;
						?>
						<div class="col-md-2">
						<label> &nbsp </label>
							<button onclick="showPage();" type="button" class="btn btn-success form-control"><i class="icon-eye position-left"></i>جستجو</button>
						</div>		
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">موارد ثبت شده</h5>
			<?php 
			if ( isset( $create_btn ) ) : 
				if ( count($create_btn) > 0 ) :
				endif;
			else:
			?>
			<div class="heading-elements col-md-2">
				<a href="<?php echo site_url( $className .'/create'  ); ?>" type="button" class="btn btn-primary pull-left"><i class="icon-add position-left"></i>افزودن</a>
			</div>
			<?php 
			endif ;
			?>
			<div class="heading-elements">
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            	</ul>
        	</div>
		</div>

		<div id="search-result" class="panel-body">
		<?php  echo $this->include('backend/crud/crud_index_view_table'); ?>
		</div>
	</div>
	
	<div id="action-modal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" id="action-modal-content">
				
			</div>
		</div>
	</div>

    <script>
	$(document).ready(function() {
		$(".persian-date-picker").pDatepicker({
			"initialValue": false,
			"format": "YYYY/MM/DD",
			"timePicker": {
				"enabled": false,
				"step": 1,
				"hour": {
					"enabled": true,
					"step": null
				},
				"minute": {
					"enabled": true,
					"step": null
				},
				"second": {
					"enabled": true,
					"step": null
				},
				"meridian": {
					"enabled": false
				}
			},
		});
	});
	
	function showPage( url = null, excel = false )
	{
        var searchInputs = document.getElementsByClassName("search-input");
		
		if ( excel == false )
		{
			var varsArray = [] ;
		}
		else
		{
			var varsArray = [ 'excel=1' ] ;
		}

        for ( var i = 0 ; i < searchInputs.length; i++ ) 
	    {
	        if ( searchInputs[i].value.length > 0 ) 
	        {
	        	varsArray.push( searchInputs[i].name + '=' + searchInputs[i].value );
	        }
	    }

        if ( url == null )
        {
            url = location.protocol + '//' + location.host + location.pathname ;
        }
        
	    var vars = varsArray.join('&');

		var hr = new XMLHttpRequest();
			
		hr.open("POST", url, true);
        
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	    hr.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
	    hr.setRequestHeader('Pragma', 'no-cache');
	    hr.setRequestHeader('Expires', '0');

        hr.onreadystatechange = function ()
        {
            if ( hr.readyState == 4 )
            {
            	var returnedData = hr.responseText;
            	
            	if ( hr.status == 200 )
	        	{
					if ( excel == true )
					{
						returnedDataJSON = JSON.parse(returnedData)
						var ep = new ExcelPlus();
						ep.createFile("Book1").write({ "content": returnedDataJSON }).saveAs("report.xlsx");
					}
					else
					{
						document.getElementById('search-result').innerHTML = returnedData ;
						window.history.pushState(null, null, url );	
					}
	          	}
            	else
            	{
					
				}
            }
        }

        hr.send(vars);
	}
	
	function remittanceSetCancel(url, outboundFactorId)
    {
        var vars = 'task=remittanceSetCancel' ;
        var cancel_confirmation_code = document.getElementById('cancel_confirmation_code').value ;
        var cancel_input_confirmation_code = document.getElementById('cancel_input_confirmation_code').value ;

        if ( cancel_confirmation_code != cancel_input_confirmation_code )
        {
            document.getElementById('remittance_set_cancel_msg').innerHTML = 'لطفا کد امنیتی را به درستی وارد کنید';
            return false ;
        }
        
        vars = vars + '&outbound_factor_id=' + outboundFactorId ;

        var hr = new XMLHttpRequest();

		hr.open("POST", url, true);
        
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	    hr.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
	    hr.setRequestHeader('Pragma', 'no-cache');
	    hr.setRequestHeader('Expires', '0');

        hr.onreadystatechange = function ()
        {
            if ( hr.readyState == 4 )
            {
            	var returnedData = hr.responseText;
            	var returnedJSON = JSON.parse(returnedData);

            	if ( hr.status == 200 )
	        	{
                    document.getElementById('remittance_set_cancel_msg').innerHTML = returnedJSON.msg ;
	          	}
            	else
            	{
                    document.getElementById('remittance_set_cancel_msg').innerHTML = returnedJSON.msg ;
				}
            }
        }

        hr.send(vars);
    }
	
	function checkPassed(url)
	{
		var vars = 'task=checkPassed' ;
        var check_id = document.getElementById('passed_check_id').value ;
        var check_passed_tracking_no = document.getElementById('check_passed_tracking_no').value ;
        var check_passed_jdate = document.getElementById('check_passed_jdate').value ;
        var check_passed_jtime = document.getElementById('check_passed_jtime').value ;
        
        vars = vars + '&check_id=' + check_id ;
        vars = vars + '&check_passed_tracking_no=' + check_passed_tracking_no ;
        vars = vars + '&check_passed_jdate=' + check_passed_jdate ;
        vars = vars + '&check_passed_jtime=' + check_passed_jtime ;

        var hr = new XMLHttpRequest();

		hr.open("POST", url, true);
        
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	    hr.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
	    hr.setRequestHeader('Pragma', 'no-cache');
	    hr.setRequestHeader('Expires', '0');

        hr.onreadystatechange = function ()
        {
            if ( hr.readyState == 4 )
            {
            	var returnedData = hr.responseText;
            	var returnedJSON = JSON.parse(returnedData);

            	if ( hr.status == 200 )
	        	{

					document.getElementById('check_id').value = check_id ;
					document.getElementById('check-passed-result').innerHTML = returnedJSON.msg ;
					showPage();
	          	}
            	else
            	{
                    document.getElementById('check-passed-result').innerHTML = returnedJSON.msg ;
				}
            }
        }

        hr.send(vars);
	}

	function cardex(url, productPriceId)
	{
		var stock_id = document.getElementById('stock_id').value ;
		var jdate_fr = document.getElementById('jdate_fr').value ;
		var jdate_to = document.getElementById('jdate_to').value ;

		var searchInputs = document.getElementsByClassName("search-input");
		
		var varsArray = [ 'excel=1' ] ;
			varsArray.push( 'stock_id=' + stock_id );
			varsArray.push( 'jdate_fr=' + jdate_fr );
			varsArray.push( 'jdate_to=' + jdate_to );
			varsArray.push( 'product_price_id=' + productPriceId );

	    var vars = varsArray.join('&');

		var hr = new XMLHttpRequest();
			
		hr.open("POST", url, true);
        
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	    hr.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
	    hr.setRequestHeader('Pragma', 'no-cache');
	    hr.setRequestHeader('Expires', '0');

        hr.onreadystatechange = function ()
        {
            if ( hr.readyState == 4 )
            {
            	var returnedData = hr.responseText;
            	
            	if ( hr.status == 200 )
	        	{
					returnedDataJSON = JSON.parse(returnedData)
					var ep = new ExcelPlus();
					ep.createFile("Book1").write({ "content": returnedDataJSON }).saveAs("report.xlsx");
					
	          	}
            }
        }

        hr.send(vars);
	}

	</script>
<?php
$this->endSection() ; 
?>