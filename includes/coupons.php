<?php
	
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require plugin_dir_path( __FILE__ ) .'../Classes/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class StoreMigrationWooCommerce_Coupons{

	public $numberOfRows=1;
	public $posts_per_page='';
	public $offset='';
	public $keyword='';
	
	public function importCouponsDisplay(){?>
		<h2>
		<?php esc_html_e("IMPORT / UPDATE Coupons","procsme");?>
		</h2>	
			
		<p>
			<?php esc_html_e("Download the sample excel file, save it and add your Product Reviews. Upload it using the form below.","procsme");
			?> 
			<a href='<?php echo plugins_url( '../example_excel/import_coupons.xlsx', __FILE__ ); ?>'>
				<?php esc_html_e("Coupons Excel Sample","procsme");?>
			</a>		
		</p>
								  
		<div>			
			<form method="post" id='coupons_import' enctype="multipart/form-data" action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=coupons' ); ?>">

				<table class="form-table">
						<tr valign="top">
							<td>
								<?php wp_nonce_field('excel_upload'); ?>
								<input type="hidden"   name="importCoupons" id="importCoupons" value="1" />
								<div class="uploader">
									<img src="" class='userSelected'/>
									<input type="file"  required name="file" class="couponsImportFile"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
								</div>						
							</td>
						</tr>
				</table>
				<?php submit_button( esc_html__( 'Upload', 'procsme' ) ,'primary','upload'); ?>
			</form>	
			<div class='result'>
				<?php  $this->importCoupons(); ?>
			</div>					
		</div>
	<?php
	}

	public function importCoupons(){
		
		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator')  && isset($_POST['importCoupons']) ){
		
			check_admin_referer( 'excel_upload' );
			check_ajax_referer( 'excel_upload' );	
			
			$filename=$_FILES["file"]["tmp_name"];
							
			if($_FILES["file"]["size"] > 0 ){
				if($_FILES["file"]["type"] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
					
					
					$objPHPExcel = IOFactory::load($filename);
					
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$data = count($allDataInSheet);  // Here get total count of row in that Excel sheet
					$total =  $data;
					$totals =  $total-1;

					$rownumber=1;
					$row = $objPHPExcel->getActiveSheet()->getRowIterator($rownumber)->current();
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
										
					$titleArray = array();	
					
					?>
					<span class='thisNum'></span>
					<div class='ajaxResponse'></div>
						
					<div class='woo-form-wrapper'>
						<form method='POST' id ='coupons_process' action= "<?php print admin_url( 'admin.php??page=store-migration-woocommerce-pro&tab=reviews' );?>">

							<p>
								<?php esc_html_e( 'DATA MAPPING: Drag and drop excel columns on the right to reviews properties on the left.', 'procsme' ); ?>
							</p>
							<p class='proVersion'>
								<i ><b > <?php esc_html_e( 'Auto Match Columns - PRO', 'procsme' ) ?> <input type='checkbox' disabled  /> </b></i>
							</p>
							
							<div class='columns3 border'>	
							
								<h2>
									<?php esc_html_e( 'EXCEL COLUMNS', 'procsme' ) ?>
								</h2>
								
								<p>
									<?php foreach ($cellIterator as $cell) {
										echo "<input type='button' class='draggable' style='min-width:100px;background:#000;color:#fff' key ='".sanitize_text_field($cell->getColumn())."' value='". sanitize_text_field($cell->getValue()) ."' />  <br/>";
									} ?>				
								</p>
								
		   
							   <input type='hidden' name='couponsfinalupload' value='<?php print esc_attr( $total );?>' />
							   <input type='hidden' name='start' value='2' />
							   <input type='hidden' name='action' value='importCoupons_process' />
							   <?php 
									wp_nonce_field('excel_process','secNonce');
									submit_button(__( 'Upload', 'procsme' ),'primary','check'); 
								?>								
								
							</div>
							

							<div class='columns2'>
							
								<h2>
									<?php esc_html_e( 'COUPONS FIELDS', 'procsme' ) ?>
								</h2>
								
								
								<?php 
								
								$post_meta=array('coupon_code','coupon_amount','coupon_description','status','author','discount_type','individual_use','product_ids','exclude_product_ids','usage_limit','expiry_date','apply_before_tax','free_shipping');
					
								foreach( $post_meta as $meta ){
									if($meta === 'coupon_code'){
										echo "<p>
											<b>".esc_html( 'COUPON TITLE', 'procsme' )." </b> <input type='text' name='coupon_code' required readonly class='droppable' placeholder='Drop here column' />
										</p>";
									}elseif($meta === 'coupon_description'){
										echo "<p>
											<b>".esc_html( 'COUPON DESCRIPTION', 'procsme' )." </b> <input type='text' name='coupon_description' required readonly class='droppable' placeholder='Drop here column' />
										</p>";
									}elseif($meta === 'post_author'){
										echo "<p>
											<b>".esc_html( 'COUPON AUTHOR', 'procsme' )." </b> <input type='text' name='author' required readonly class='droppable' placeholder='Drop here column' />
										</p>";
									}elseif($meta === 'status'){
										echo "<p>
											<b>".esc_html( 'COUPON STATUS', 'procsme' )." </b> <input type='text' name='status' required readonly class='droppable' placeholder='Drop here column' />
										</p>";
									
									}else{
										echo "<p>
												<b>".strtoupper(str_replace('comment','', str_replace('_',' ',esc_attr($meta))) )."</b> <input type='text'  name='".esc_attr($meta)."' required readonly class='droppable' placeholder='Drop here column'  />
										</p>";
									}
								}
								
								?>


								
							</div>	
							
							
			
						</form>
					</div>
					
					<?php move_uploaded_file($_FILES["file"]["tmp_name"], plugin_dir_path( __FILE__ ).'import_coupons.xlsx');
				
				}else   "<h3>". esc_html__('Invalid File:Please Upload Excel File', 'procsme')."</h3>";				
				
			}
		}
	
	}


	public function importCoupons_process(){
		
		if(isset($_POST['couponsfinalupload']) && current_user_can('administrator')){
			
			$time_start = microtime(true);

			check_admin_referer( 'excel_process','secNonce' );
			check_ajax_referer( 'excel_process' ,'secNonce');
					
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			
			
			
			$filename = plugin_dir_path( __FILE__ ).'import_coupons.xlsx';
			
			$objPHPExcel = IOFactory::load($filename);
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$data = count($allDataInSheet);  // Here get total count of row in that Excel sheet	

			
			//parameters for running with ajax - no php timeouts
			$i=sanitize_text_field($_POST['start']);
			$start = $i -1;

					
			$description = sanitize_text_field($allDataInSheet[$i][$_POST['coupon_description']]);
			
			if( !empty( $allDataInSheet[$i][$_POST['status']]) ){
				$status = sanitize_text_field($allDataInSheet[$i][$_POST['status']]);
			}else $status ='publish';
			
			if( !empty( $allDataInSheet[$i][$_POST['author']]) ){
				$post_author = (int)$allDataInSheet[$i][$_POST['author']];
			}else $post_author =1;
			
			$coupon_code = sanitize_text_field($allDataInSheet[$i][$_POST['coupon_code']]);
			$coupon_amount = (int)$allDataInSheet[$i][$_POST['coupon_amount']];
			$discount_type = sanitize_text_field($allDataInSheet[$i][$_POST['discount_type']]);
			$individual_use = sanitize_text_field($allDataInSheet[$i][$_POST['individual_use']]);
			$product_ids = sanitize_text_field($allDataInSheet[$i][$_POST['product_ids']]);
			$exclude_product_ids = sanitize_text_field($allDataInSheet[$i][$_POST['exclude_product_ids']]);
			$usage_limit = sanitize_text_field($allDataInSheet[$i][$_POST['usage_limit']]);
			$apply_before_tax = sanitize_text_field($allDataInSheet[$i][$_POST['apply_before_tax']]);
			$free_shipping = sanitize_text_field($allDataInSheet[$i][$_POST['free_shipping']]);
			//$expiry_date = date('Y-m-d H:i:s',strtotime(sanitize_text_field($allDataInSheet[$i][$_POST['expiry_date']])) );
			$expiry_date = date('Y-m-d',strtotime(sanitize_text_field($allDataInSheet[$i][$_POST['expiry_date']])) );
				
			if( !empty( $coupon_code ) && !empty( $coupon_amount ) ){

				$coupon = array(
					'post_title' => $coupon_code,
					'post_status' => $status,
					'post_excerpt' => $description,
					'post_author' => $post_author,
					'post_type'     => 'shop_coupon'
				);    

				$new_coupon_id = wp_insert_post( $coupon );

				// Add meta
				if( !empty($discount_type) )update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				if( !empty($coupon_amount) )update_post_meta( $new_coupon_id, 'coupon_amount', $coupon_amount );
				if( !empty($individual_use) )update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
				if( !empty($product_ids) )update_post_meta( $new_coupon_id, 'product_ids', $product_ids );
				if( !empty($exclude_product_ids) )update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude_product_ids );
				if( !empty($usage_limit) )update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
				if( !empty($expiry_date) ) update_post_meta( $new_coupon_id, 'expiry_date', $expiry_date );
				if( !empty($apply_before_tax) )update_post_meta( $new_coupon_id, 'apply_before_tax', $apply_before_tax );
				if( !empty($free_shipping) )update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
				
			}

					
			if($i === $_REQUEST['couponsfinalupload']){						
				$tota = $_REQUEST['couponsfinalupload']-1;
				print "<div class='importMessageSussess'><h2>".esc_html($i)." / ".esc_html($_REQUEST['couponsfinalupload'])." ".esc_html__('- JOB DONE!', 'procsme'). " <a href='".esc_url(admin_url( 'edit.php?post_type=shop_coupon' ))."' target='_blank'><i class='fa fa-eye'></i> ".esc_html__('View Coupons', 'procsme')."</a></h2></div>";
					
				unlink($filename);
			}else{
				
				print "<div class='importMessage'>
							<h2>".esc_html($i)." / ".esc_html($_REQUEST['couponsfinalupload'])." ".esc_html__('Please dont close this page... Loading...', 'procsme')."</h2>
								<p>
									<img  src='".esc_url(plugins_url( '../images/loading.gif', __FILE__ ))."' />
								</p>
						</div>";
			}
			die;										
		}
	}



	public function exportCouponsForm(){
		?>
				<p class='exportToggler button button-secondary warning   btn btn-danger'><i class='fa fa-eye '></i> 
					<?php esc_html_e('Filter & Fields to Show', 'procsme');?>
				</p>
				
				<form name='exportCouponsForm' id='exportCouponsForm' method='post' action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=exportCoupons'); ?>" >	
					<table class='wp-list-table widefat fixed table table-bordered'>

						<tr>
							<td>
								<?php esc_html_e('Keywords', 'procsme');?>
							</td>
							<td>
								<input type='text' name='keyword' id='keyword' placeholder='<?php esc_html_e('Search term', 'procsme');?>'/>
							</td>
							<td></td><td></td>
						</tr>
						<tr>
							<td>
							<?php esc_html_e('Limit Results', 'procsme');?>
							</td>
							<td>
								<input type='number' min="1" max="100000" style='width:100%;'  name='posts_per_page' id='posts_per_page' placeholder='<?php _e('Number to display..', 'procsme');?>' />
							</td>
							
							<td>
							<?php esc_html_e('To Creation Date', 'procsme');?>
							</td>	
							<td>
							<input type='date' style='width:100%;'  name='toDate' id='toDate' placeholder='<?php esc_html_e('registration date', 'procsme');?>' />
							</td>							
							
							<input type='hidden' name='offset' style='width:100%;' id='offset' placeholder='<?php esc_html_e('Start from..', 'procsme');?>' />
							<input type='hidden' name='start' /><input type='hidden' name='total' />
							
							<td></td><td></td>
						</tr>
						
					</table>

					<table class='wp-list-table widefat fixed table table-bordered tax_checks'>
						<legend>
							<h2>
								<?php esc_html_e('FIELDS TO SHOW', 'procsme');?>
							</h2>
						</legend>
						<tr>
							<td>
								<input type="checkbox" name="check_all1" id="check_all1" ><label for="check_all1"><?php esc_html_e('Check All', 'procsme');?></label>
							</td>
						</tr>						
						<tr>
							<?php $cols = array();
							
							$checked = 'checked';
							
							$cols=array('coupon_code','coupon_amount','coupon_description','status','author','discount_type','individual_use','product_ids','exclude_product_ids','usage_limit','expiry_date','apply_before_tax','free_shipping');
							
							print "<tr>";
							$checked = 'checked';
							foreach( $cols as $col){					
								print "<td style='float:left'><input type='checkbox' class='fieldsToShow'  name='toShow".esc_attr($col)."' value='1'/><label for='".esc_html($col)."'>". esc_html($col). "</label></td>";
							}
							?>
						</tr>
					</table>
		
							
					<input type='hidden' name='columnsToShow' value='1'  />
					<input type='hidden' id='action' name='action' value='exportCoupons_process' />
					<?php wp_nonce_field('columnsToShow'); ?>

					<?php submit_button(esc_html__( 'Search', 'procsme' ),'primary','Search'); ?>

				</form>
			
			<div class='resultExport'>
				<?php $this->exportCoupons(); ?>
			</div>
		<?php			
	}	

	public function exportCoupons(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') && $_REQUEST['columnsToShow'] ){
			
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );	
			
			
			$date_query =1;
			if(!empty($_POST['fromDate'])){
				$date_query = array (
					'date_query'    => array(
						array(
							'after'     => date(sanitize_text_field($_POST['fromDate'])),
							'before' => date(sanitize_text_field($_POST['toDate'])),
							'inclusive' => true,
						),
					 ),
				);
			}
			if(!empty($_POST['posts_per_page'])){
				$this->posts_per_page = (int)$_POST['posts_per_page'] ;
			}else $this->posts_per_page = '-1';
			
			if(!empty($_POST['offset'])){
				$this->offset = (int)$_POST['offset'];
			}else $this->offset = '-1';
			
			if(!empty($_POST['keyword']))  $this->keyword = sanitize_text_field( $_POST['keyword'] );
			
			$query = new WP_Query( array(
				'post_type' => 'shop_coupon',
				's' => $this->keyword,
				'date_query' => $date_query,				
				'posts_per_page' => (int)$this->posts_per_page,	
				'offset' => (int)$this->offset,
				) );
			 
			// Check for coupons
			if ( $query ->have_posts() ){
				
				$query->the_post();

				$i=0;
				?>
				<p class='message error'>
					<?php esc_html_e( 'Wait... Download is loading...', 'procsme' );?>
					<b class='totalPosts'  >
						<?php print esc_html( $query->post_count );?>
					</b>					
				</p>

				<?php		
				if( $query->post_count <= 500 ){
					$start=0;
				}else $start=500;
				print " <b class='startPosts'>".esc_html($start)."</b>";

				$column_name =   array('coupon_code','coupon_description','status','author','coupon_amount','discount_type','individual_use','product_ids','exclude_product_ids','usage_limit','expiry_date','apply_before_tax','free_shipping');
				
				?>
				
				<div id="myProgress">
					 <div id="myBar"></div>
				</div>
				<div class='exportTableWrapper'>
					<table id='toExport'>
						<thead>
							<tr> 
								<th>
									<?php esc_html_e('ID', 'procsme');?>
								</th>
								<?php
									foreach($column_name as $d){
										

										if(isset($_REQUEST["toShow".strtolower(str_replace(" ","_",$d))] ) ){
											$d = strtoupper(str_replace("_"," ",$d));
											print "<th>".esc_html($d)."</th>";										
										}
									}								
								
								?>
							</tr>
						</thead>
						<tbody class='tableExportAjax'>
						</tbody>	
					</table>
				</div>	
			
			<?php	
			}else{?>
				<p class='message error'>
					<?php esc_html_e( 'No Users found.', 'procsme' );?>
				</p>
					<?php			
			}
		}//check request						
	}
	
	
	public function exportCoupons_process(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') ){
					
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );
			
			$date_query = 1;
			if(!empty($_POST['fromDate'])){
				$date_query = array (
					'date_query'    => array(
						array(
							'after'     => date(sanitize_text_field($_POST['fromDate'])),
							'before' => date(sanitize_text_field($_POST['toDate'])),
							'inclusive' => true,
						),
					 ),
				);
			}
			if(!empty($_POST['posts_per_page'])){
				$this->posts_per_page = (int)$_POST['posts_per_page'] ;
			}else $this->posts_per_page = '-1';
			
			if(!empty($_POST['offset'])){
				$this->offset = (int)$_POST['offset'];
			}else $this->offset = '-1';
			
			if(!empty($_POST['keyword']))  $this->keyword = sanitize_text_field( $_POST['keyword'] );
			
			$query = new WP_Query( array(
				'post_type' => 'shop_coupon',
				's' => $this->keyword,
				'date_query' => $date_query,				
				'posts_per_page' => (int)$this->posts_per_page,	
				'offset' => (int)$this->offset,
				) );	
			

			if ( $query ->have_posts() ){
				
				
				$cols= array('coupon_amount','discount_type','individual_use','product_ids','exclude_product_ids','usage_limit','expiry_date','apply_before_tax','free_shipping');
				
				
				while ( $query->have_posts() ){
									
					$query->the_post();
					 ?>						
						<tr>
							<td><?php print esc_attr( get_the_ID() ) ;?></td>

							<?php if($_REQUEST["toShowcoupon_code"] ){ ?>								 
								<td><?php esc_html(the_title());?></td>
							<?php } ?>					
							<?php if(isset($_REQUEST["toShowcoupon_description"]) ){ ?>									
								<td>
									<?php print esc_html(get_post_field('post_excerpt', get_the_ID()) )  ; ?>
								</td>
							<?php } ?>
							<?php if(isset($_REQUEST["toShowstatus"]) ){ ?>								 
								<td>
									<?php print esc_html(get_post_field('post_status', get_the_ID()) )  ; ?>							
								</td>
							<?php } ?>	
							<?php if(isset($_REQUEST["toShowauthor"]) ){ ?>								 
								<td>
									<?php print esc_html(get_post_field('post_author', get_the_ID()) )  ; ?>							
								</td>
							<?php } ?>								
							<?php foreach($cols as $meta){
								if(isset($_REQUEST["toShow".$meta])  && $_REQUEST["toShow".$meta] !=='toShowcoupon_description' && $_REQUEST["toShow".$meta] !=='toShowstatus' && $_REQUEST["toShow".$meta] !=='toShowauthor' && $_REQUEST["toShow".$meta] !=='toShowcoupon_code'  ){ ?>
															
										<td><?php print esc_html( get_post_meta(get_the_ID(), $meta, true ) ); 
										 ?></td>									
								<?php
								
								}
							}									
	
						print "</tr>";						
				}
			}else print "<p class='warning' >".esc_html__('No Coupons Found', 'procsme')."</p>";//end if	
	
			die;
			
		}//check request						
	}

	
}