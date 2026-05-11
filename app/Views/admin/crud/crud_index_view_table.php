        <div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<?php 
						foreach( $fields_show as $field ) :
							if ( is_array($field) ) 
								echo '<th>'. ( array_key_exists( $field['text'], $fields_name) ? $fields_name[$field['text']] : lang( 'FieldsText.' . $field['text'] ,[],'fa') ) .'</th>';
							else
								echo '<th>'.  ( array_key_exists( $field, $fields_name) ? $fields_name[$field] : lang( 'FieldsText.' . $field ,[],'fa') ) .'</th>';
						endforeach ;
						
						if ( isset( $fields_view ) && is_array($fields_view) ) :
							foreach( $fields_view as $field_view ) :
								echo '<th>' . ( array_key_exists( $field_view['text'], $fields_name) ? $fields_name[$field_view['text']] : lang( 'FieldsText.' . $field_view['text'] ,[],'fa') ) . '</th>';
							endforeach ;
						endif ;

						if ( isset( $actions ) && is_array($actions)) :
							foreach( $actions as $action_name => $action ) :
								echo '<th>'.$action['text'].'</th>';
							endforeach ;
						endif ;

						if ( isset( $edit_pk ) ) :
						?>
                        <th>ویرایش</th>
						<?php 
						endif ;
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach( $rowset as $row ) :
					?>
					<tr>
						<?php 
						foreach( $fields_show as $field ) :
							if ( is_array($field) )
								echo '<td>' . view( $field['view'], array_merge( $field['data'], ['row' => $row] ) ) . '</td>';
							else
								echo '<td>' . $row[$field] . '</td>';
						endforeach ;

						if ( isset( $actions ) && is_array($actions)) :
							foreach( $actions as $action_name => $action ) :
								$inputs = array_intersect_key( $row , array_flip( $action['inputs'] ) );
								echo '<td><button class="btn btn-success" data-toggle="modal" data-target="#action-modal" onclick="'."showAction('" . $action['url'] . "','" . http_build_query($inputs) . "');". '">' . $action['text'] . '</button></td>' ;
							endforeach ;
						endif ;			
						
						if ( isset( $edit_pk ) ) :
						?>
						<td><a href="<?php echo site_url( $className .'/edit/' . $row[ $edit_pk ] ); ?>" class="btn btn-warning"><i class="icon-pencil position-left"></i>ویرایش</a></td>
						<?php
						endif ;
						?>
					</tr>
					<?php
					endforeach ;
					?>
				</tbody>
			</table>
		</div>

        <?php 
        echo( $pagination ); 
        ?>
		
