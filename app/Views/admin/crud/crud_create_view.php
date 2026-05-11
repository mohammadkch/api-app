<?php
$this->extend('backend/_layout_/layout');
$this->section('content');
?>

<?php 
helper('form');
$extra = [ 'class' => 'form-control' ];
?>
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">افزودن</h5>
			<div class="heading-elements">
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            		<li><a data-action="reload"></a></li>
            		<li><a data-action="close"></a></li>
            	</ul>
        	</div>
		</div>

		<div class="panel-body">
			<div class="row">
				<form action="<?php echo site_url($form_action); ?>" method="post" enctype="multipart/form-data">
					<?php
					foreach( $inputs as $input_key => $input ) :
					?>
					<div class="col-lg-12">
						<div class="col-md-2">
							<span class="form-control bg-white border-white">
							<?php echo array_key_exists( $input_key, $fields_name ) ? $fields_name[$input_key] : lang( 'FieldsText.' . $input_key, [], 'fa'); ?>
							</span>
						</div>
						<div class="col-md-4">								
							<?php
							if ( $input['input'] == 'form_input' ) :
								echo form_input( array_merge($input['data'], ['id'=>$input_key, 'name'=>$input_key] ) , set_value( $input_key , isset( $edit_row[$input_key] ) ? $edit_row[$input_key]  : '' ), $extra, $input['type']);
							elseif( $input['input'] == 'form_dropdown' ) :
								echo form_dropdown( $input_key, $input['options'],set_value($input_key, isset( $edit_row ) ? $edit_row[$input_key] : '' ), array_merge( $extra, $input['data'], ['id'=>$input_key] ) );
							elseif( $input['input'] == 'form_upload' ) :
								if ( isset( $edit_row[$input_key] ) ) :
									echo img( base_url( $edit_row[$input_key] ) , false, [ 'style' => 'width:100%' ] );
								endif ;
								echo form_upload( array_merge($input['data'], ['id'=>$input_key, 'name'=>$input_key] ), '', $extra);
							elseif( $input['input'] == 'form_json' ) :
								foreach ( json_decode( $edit_row[$input_key] , true )   as $key => $val ) :
									echo '<div class="input-group">';
									echo '<input dir="ltr" type="text" class="form-control" value="'.$key.'">' ;
									echo '<span class="input-group-addon">:</span>';
									echo '<input dir="ltr" type="text" class="form-control" value="'.$val.'">' ;
									echo '<span class="input-group-addon">+</span>';
									echo '</div>';
								endforeach ;
							elseif( $input['input' == 'form_jdate_input'] ) :
							
							endif ;
							?>
						</div>
						<div class="col-md-6 text-danger">
						<?php
						if ( isset( $validation_errors ) )
						if ( array_key_exists( $input_key, $validation_errors ) ) :
							echo $validation_errors[$input_key] ;
						endif ;
						?>
						</div>
					</div>
					<?php 
					endforeach;
					?>
					<div class="col-lg-12">
						<hr/>
					</div>
					<div class="col-lg-12">
						<div class="col-md-2 content-left">
						</div>
						<div class="col-md-2 content-left">
							<button type="submit" class="btn btn-success form-control">تایید</button>
						</div>
						<div class="col-md-2 content-left">
							<a href="<?php echo site_url( $className ); ?>" type="button" class="btn btn-warning form-control">بازگشت</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>	
	<script>
		
		function removeOptions(selectElement) 
		{
			var i, L = selectElement.options.length - 1;
			
			for(i = L; i >= 0; i--) 
			{
				selectElement.remove(i);
			}
		}

		function updateCityOptions(url)
		{
			var vars = 'task=updateCityOptions' ;

			var state_element = document.getElementById('state_id');
			var city_element = document.getElementById('city_id');

			var state_id = state_element.value ;
			
			vars = vars + '&state_id=' + state_id ;
			
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
						removeOptions(city_element);
						

						returnedJSON.forEach(function (arrayItem) {	
							var option = document.createElement("option");
							
							option.value = arrayItem.city_id ;
							option.text = arrayItem.city_name ;

							city_element.add(option); 
						});
					}
					else
					{
						
					}
				}
			}

			hr.send(vars);
		}
	</script>
<?php
$this->endSection() ; 
?>