<?php
	
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require plugin_dir_path( __FILE__ ) .'../Classes/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class StoreMigrationWooCommerce_Orders{

	public $numberOfRows=1;
	public $posts_per_page='';
	public $offset='';
	public $keyword='';
	public $status='wc-completed';
	public $proUrl = 'https://extend-wp.com/product/products-reviews-orders-customers-woocommerce-migration-excel';	
	public $countrycodes = array("Afghanistan"=>"AF","Åland Islands"=>"AX","Albania"=>"AL","Algeria"=>"DZ","American Samoa"=>"AS","Andorra"=>"AD","Angola"=>"AO","Anguilla"=>"AI","Antarctica"=>"AQ","Antigua and Barbuda"=>"AG","Argentina"=>"AR","Armenia"=>"AM","Aruba"=>"AW","Australia"=>"AU","Austria"=>"AT","Azerbaijan"=>"AZ","Bahrain"=>"BH","Bahamas"=>"BS","Bangladesh"=>"BD","Barbados"=>"BB","Belarus"=>"BY","Belgium"=>"BE","Belize"=>"BZ","Benin"=>"BJ","Bermuda"=>"BM","Bhutan"=>"BT","Bolivia, Plurinational State of"=>"BO","Bonaire, Sint Eustatius and Saba"=>"BQ","Bosnia and Herzegovina"=>"BA","Botswana"=>"BW","Bouvet Island"=>"BV","Brazil"=>"BR","British Indian Ocean Territory"=>"IO","Brunei Darussalam"=>"BN","Bulgaria"=>"BG","Burkina Faso"=>"BF","Burundi"=>"BI","Cambodia"=>"KH","Cameroon"=>"CM","Canada"=>"CA","Cape Verde"=>"CV","Cayman Islands"=>"KY","Central African Republic"=>"CF","Chad"=>"TD","Chile"=>"CL","China"=>"CN","Christmas Island"=>"CX","Cocos (Keeling) Islands"=>"CC","Colombia"=>"CO","Comoros"=>"KM","Congo"=>"CG","Congo, the Democratic Republic of the"=>"CD","Cook Islands"=>"CK","Costa Rica"=>"CR","Côte d'Ivoire"=>"CI","Croatia"=>"HR","Cuba"=>"CU","Curaçao"=>"CW","Cyprus"=>"CY","Czech Republic"=>"CZ","Denmark"=>"DK","Djibouti"=>"DJ","Dominica"=>"DM","Dominican Republic"=>"DO","Ecuador"=>"EC","Egypt"=>"EG","El Salvador"=>"SV","Equatorial Guinea"=>"GQ","Eritrea"=>"ER","Estonia"=>"EE","Ethiopia"=>"ET","Falkland Islands (Malvinas)"=>"FK","Faroe Islands"=>"FO","Fiji"=>"FJ","Finland"=>"FI","France"=>"FR","French Guiana"=>"GF","French Polynesia"=>"PF","French Southern Territories"=>"TF","Gabon"=>"GA","Gambia"=>"GM","Georgia"=>"GE","Germany"=>"DE","Ghana"=>"GH","Gibraltar"=>"GI","Greece"=>"GR","Greenland"=>"GL","Grenada"=>"GD","Guadeloupe"=>"GP","Guam"=>"GU","Guatemala"=>"GT","Guernsey"=>"GG","Guinea"=>"GN","Guinea-Bissau"=>"GW","Guyana"=>"GY","Haiti"=>"HT","Heard Island and McDonald Islands"=>"HM","Holy See (Vatican City State)"=>"VA","Honduras"=>"HN","Hong Kong"=>"HK","Hungary"=>"HU","Iceland"=>"IS","India"=>"IN","Indonesia"=>"ID","Iran, Islamic Republic of"=>"IR","Iraq"=>"IQ","Ireland"=>"IE","Isle of Man"=>"IM","Israel"=>"IL","Italy"=>"IT","Jamaica"=>"JM","Japan"=>"JP","Jersey"=>"JE","Jordan"=>"JO","Kazakhstan"=>"KZ","Kenya"=>"KE","Kiribati"=>"KI","Korea, Democratic People's Republic of"=>"KP","Korea, Republic of"=>"KR","Kuwait"=>"KW","Kyrgyzstan"=>"KG","Lao People's Democratic Republic"=>"LA","Latvia"=>"LV","Lebanon"=>"LB","Lesotho"=>"LS","Liberia"=>"LR","Libya"=>"LY","Liechtenstein"=>"LI","Lithuania"=>"LT","Luxembourg"=>"LU","Macao"=>"MO","Macedonia, the Former Yugoslav Republic of"=>"MK","Madagascar"=>"MG","Malawi"=>"MW","Malaysia"=>"MY","Maldives"=>"MV","Mali"=>"ML","Malta"=>"MT","Marshall Islands"=>"MH","Martinique"=>"MQ","Mauritania"=>"MR","Mauritius"=>"MU","Mayotte"=>"YT","Mexico"=>"MX","Micronesia, Federated States of"=>"FM","Moldova, Republic of"=>"MD","Monaco"=>"MC","Mongolia"=>"MN","Montenegro"=>"ME","Montserrat"=>"MS","Morocco"=>"MA","Mozambique"=>"MZ","Myanmar"=>"MM","Namibia"=>"NA","Nauru"=>"NR","Nepal"=>"NP","Netherlands"=>"NL","New Caledonia"=>"NC","New Zealand"=>"NZ","Nicaragua"=>"NI","Niger"=>"NE","Nigeria"=>"NG","Niue"=>"NU","Norfolk Island"=>"NF","Northern Mariana Islands"=>"MP","Norway"=>"NO","Oman"=>"OM","Pakistan"=>"PK","Palau"=>"PW","Palestine, State of"=>"PS","Panama"=>"PA","Papua New Guinea"=>"PG","Paraguay"=>"PY","Peru"=>"PE","Philippines"=>"PH","Pitcairn"=>"PN","Poland"=>"PL","Portugal"=>"PT","Puerto Rico"=>"PR","Qatar"=>"QA","Réunion"=>"RE","Romania"=>"RO","Russian Federation"=>"RU","Rwanda"=>"RW","Saint Barthélemy"=>"BL","Saint Helena, Ascension and Tristan da Cunha"=>"SH","Saint Kitts and Nevis"=>"KN","Saint Lucia"=>"LC","Saint Martin (French part)"=>"MF","Saint Pierre and Miquelon"=>"PM","Saint Vincent and the Grenadines"=>"VC","Samoa"=>"WS","San Marino"=>"SM","Sao Tome and Principe"=>"ST","Saudi Arabia"=>"SA","Senegal"=>"SN","Serbia"=>"RS","Seychelles"=>"SC","Sierra Leone"=>"SL","Singapore"=>"SG","Sint Maarten (Dutch part)"=>"SX","Slovakia"=>"SK","Slovenia"=>"SI","Solomon Islands"=>"SB","Somalia"=>"SO","South Africa"=>"ZA","South Georgia and the South Sandwich Islands"=>"GS","South Sudan"=>"SS","Spain"=>"ES","Sri Lanka"=>"LK","Sudan"=>"SD","Suriname"=>"SR","Svalbard and Jan Mayen"=>"SJ","Swaziland"=>"SZ","Sweden"=>"SE","Switzerland"=>"CH","Syrian Arab Republic"=>"SY","Taiwan, Province of China"=>"TW","Tajikistan"=>"TJ","Tanzania, United Republic of"=>"TZ","Thailand"=>"TH","Timor-Leste"=>"TL","Togo"=>"TG","Tokelau"=>"TK","Tonga"=>"TO","Trinidad and Tobago"=>"TT","Tunisia"=>"TN","Turkey"=>"TR","Turkmenistan"=>"TM","Turks and Caicos Islands"=>"TC","Tuvalu"=>"TV","Uganda"=>"UG","Ukraine"=>"UA","United Arab Emirates"=>"AE","United Kingdom"=>"GB","United States"=>"US","United States Minor Outlying Islands"=>"UM","Uruguay"=>"UY","Uzbekistan"=>"UZ","Vanuatu"=>"VU","Venezuela, Bolivarian Republic of"=>"VE","Viet Nam"=>"VN","Virgin Islands, British"=>"VG","Virgin Islands, U.S."=>"VI","Wallis and Futuna"=>"WF","Western Sahara"=>"EH","Yemen"=>"YE","Zambia"=>"ZM","Zimbabwe"=>"ZW");
	
	public function get_product_by_sku( $sku ) {
	  global $wpdb;

	  $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

	  if ( $product_id ) return $product_id;

	  return null;
	}
	
	public function importOrdersDisplay(){
		?>
		<h2>
		<?php esc_html_e("IMPORT / UPDATE Orders","procsme");?>
		</h2>	
		
		<h3><?php print esc_html("You can import Orders for Simple Products.","procsme");?> <?php print esc_html("If you need to import Orders with Variable products go for the ","procsme");?><a target='_blank' href='<?php print esc_url($this->proUrl); ?>'><?php print esc_html("PRO VERSION","procsme");?></a></h3>		
		<p>
			<?php _e("Download the sample excel file, save it and add your Orders. Upload it using the form below.","procsme");
			?> 
			<a href='<?php echo plugins_url( '../example_excel/import_orders.xlsx', __FILE__ ); ?>'>
				<?php _e("Orders Excel Sample","procsme");?>
			</a>		
		</p>
								  
		<div>			
			<form method="post" id='orders_import' enctype="multipart/form-data" action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=orders' ); ?>">

				<table class="form-table">
						<tr valign="top">
							<td>
								<?php wp_nonce_field('excel_upload'); ?>
								<input type="hidden"   name="importOrders" id="importOrders" value="1" />
								<div class="uploader">
									<img src="" class='userSelected'/>
									<input type="file"  required name="file" class="ordersImportFile"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
								</div>						
							</td>
						</tr>
				</table>
				<?php submit_button( __( 'Upload', 'procsme' ) ,'primary','upload'); ?>
			</form>	
			<div class='result'>
				<?php  $this->importOrders(); ?>
			</div>					
		</div>
	<?php
	}

	public function importOrders(){
		
		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator')  && isset($_POST['importOrders']) ){
		
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
						<form method='POST' id ='orders_process' action= "<?php print admin_url( 'admin.php?page=store-migration-woocommerce&tab=orders' );?>">

							<p>
								<?php esc_html_e( 'DATA MAPPING: Drag and drop excel columns on the right to orders properties on the left.', 'procsme' ); ?>
							</p>
							<p class='proVersion'>
								<i ><b > <?php esc_html_e( 'Auto Match Columns - PRO', 'procsme' ) ?> <input type='checkbox' disabled  /> </b></i>
							</p>
							
							<div class='columns3 border'>	

								<p class=''>
									<input type='checkbox' name='same_address' id='same_address' value='yes'  /> <b> <?php esc_html_e( 'Shipping same as Billing address', 'procsme' ) ?> </b>
								</p>
								<p class='proVersion'>
									<input type='checkbox' disabled readonly /> <b> <?php esc_html_e( 'Create user if not exist - PRO', 'procsme' ) ?> </b>
								</p>
								<p class=''>
									<b> <?php esc_html_e( 'Product to Orders by', 'procsme' ) ?> </b>
									<select name='product_by' id='productBy' required >
										<option value=''><?php esc_html_e( 'select by...', 'procsme' ) ?></option>
										<option value='id'><?php esc_html_e( 'ID', 'procsme' ) ?></option>
										<option value='sku'><?php esc_html_e( 'SKU', 'procsme' ) ?></option>
										<option value='title'><?php esc_html_e( 'TITLE', 'procsme' ) ?></option>
									</select>	
								</p>
								
								<h2>
									<?php _e( 'EXCEL COLUMNS', 'procsme' ) ?>
								</h2>
								
								<p>
									<?php foreach ($cellIterator as $cell) {
										echo "<input type='button' class='draggable' style='min-width:100px;background:#000;color:#fff' key ='".sanitize_text_field($cell->getColumn())."' value='". sanitize_text_field($cell->getValue()) ."' />  <br/>";
									} ?>				
								</p>
								
		   
							   <input type='hidden' name='ordersfinalupload' value='<?php print esc_attr( $total );?>' />
							   <input type='hidden' name='start' value='2' />
							   <input type='hidden' name='action' value='importOrders_process' />
							   <?php 
									wp_nonce_field('excel_process','secNonce');
									submit_button(__( 'Upload', 'procsme' ),'primary','check'); 
								?>								
								
							</div>
							

							<div class='columns2'>
							
								<h2>
									<?php esc_html_e( 'ORDERS FIELDS', 'procsme' ) ?>
								</h2>
								
								
								<?php 
								
								$post_meta=array('order_date','order_pay_date','order_status','product_ids','quantities','first_name','last_name','company','email','phone','address_1','address_2','city','state','postcode','country','shipping_method','shipping_amount', 'payment_method','coupon_code');
					
								foreach( $post_meta as $meta ){
									if($meta === 'product_ids'){
										echo "<p>
											<b>".esc_html( 'PRODUCT ID, TITLE OR SKU', 'procsme' )." </b> <input type='text' name='product_ids' required readonly class='droppable' placeholder='Drop here column' />
										</p>";
									}elseif($meta === 'coupon_code'){
										echo "<p class='proVersion'>
											<b>".esc_html( 'Coupons - PRO VERSION', 'procsme' )." </b> 
										</p>";										
									}else{									
										echo "<p>
												<b>".strtoupper( str_replace('_',' ',esc_attr($meta)) )."</b> <input type='text'  name='".esc_attr($meta)."' required readonly class='droppable' placeholder='Drop here column'  />
										</p>";
									}
								}
								
								?>


								
							</div>	
							
							
			
						</form>
					</div>
					
					<?php move_uploaded_file($_FILES["file"]["tmp_name"], plugin_dir_path( __FILE__ ).'import_orders.xlsx');
				
				}else   "<h3>". _e('Invalid File:Please Upload Excel File', 'procsme')."</h3>";				
				
			}
		}
	
	}


	public function importOrders_process(){
				
		if(isset($_POST['ordersfinalupload']) && current_user_can('administrator')){
			
			$time_start = microtime(true);

			check_admin_referer( 'excel_process','secNonce' );
			check_ajax_referer( 'excel_process' ,'secNonce');
					
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			
			
			
			$filename = plugin_dir_path( __FILE__ ).'import_orders.xlsx';
			
			$objPHPExcel = IOFactory::load($filename);
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$data = count($allDataInSheet);  // Here get total count of row in that Excel sheet	

			
			//parameters for running with ajax - no php timeouts
			$i=sanitize_text_field($_POST['start']);
			$start = $i -1;
			
			
						
			$email = sanitize_text_field($allDataInSheet[$i][$_POST['email']]);
			
			$default_password = wp_generate_password();
			if ( !$user = get_user_by('email', $email) ) {
				
			
			}else{
				$user = get_user_by( 'email', $email );
				$userID = (int)$user->ID;
				$user_id = (int)$user->ID; //FOR ADDRESS USE ONLY
			}
			
			$date = date('Y-m-d H:i:s',strtotime(sanitize_text_field($allDataInSheet[$i][$_POST['order_date']])) );
			$order_pay_date = date('Y-m-d H:i:s',strtotime(sanitize_text_field($allDataInSheet[$i][$_POST['order_pay_date']])) );
			
			$first_name = sanitize_text_field($allDataInSheet[$i][$_POST['first_name']]);
			$last_name = sanitize_text_field($allDataInSheet[$i][$_POST['last_name']]);
			$company = sanitize_text_field($allDataInSheet[$i][$_POST['company']]);
			$phone = sanitize_text_field($allDataInSheet[$i][$_POST['phone']]);
			$address_1 = sanitize_text_field($allDataInSheet[$i][$_POST['address_1']]);
			$address_2 = sanitize_text_field($allDataInSheet[$i][$_POST['address_2']]);
			$city = sanitize_text_field($allDataInSheet[$i][$_POST['city']]);
			$state = sanitize_text_field($allDataInSheet[$i][$_POST['state']]);
			$postcode = sanitize_text_field($allDataInSheet[$i][$_POST['postcode']]);
			
			

			if( strlen( $allDataInSheet[$i][$_POST['country']] ) == 2 ) {								
				$country = sanitize_text_field($allDataInSheet[$i][$_POST['country']]) ;
			}else{
				$country = $this->countrycodes[ sanitize_text_field($allDataInSheet[$i][$_POST['country']]) ];								
			}
							

			

			
			if( isset($allDataInSheet[$i][$_POST['order_status']] ) && $allDataInSheet[$i][$_POST['order_status']] !=='' ) {
				$status = sanitize_text_field($allDataInSheet[$i][$_POST['order_status']]);
			}else $status = '';
			
			$payment_method = sanitize_text_field($allDataInSheet[$i][$_POST['payment_method']]);
			
			
			global $woocommerce;
			
			if( isset( $user_id ) ) { //we found user preexisting
			
				if( !empty(get_user_meta( $user_id, 'first_name', true )) ){
					$ufirst= get_user_meta( $user_id, 'first_name', true ); 
				}else  $ufirst = $first_name;
				
				if( !empty(get_user_meta( $user_id, 'last_name', true ))){ 
					$ulast= get_user_meta( $user_id, 'last_name', true ); 
				}else  $ulast = $last_name;
				
				if( !empty(get_user_meta( $user_id, 'billing_company', true ))){
					$ubilling_company= get_user_meta( $user_id, 'billing_company', true ) ;
				}else  $ubilling_company = $company;
				
				if( !empty(get_user_meta( $user_id, 'billing_email', true ))){ 
					$ubilling_email= get_user_meta( $user_id, 'billing_email', true ); 
				}else  $ubilling_email = $email;
				
				if( !empty(get_user_meta( $user_id, 'billing_phone', true ))){ $ubilling_phone= get_user_meta( $user_id, 'billing_phone', true ); }else  $ubilling_phone = $phone;
				
				if( !empty(get_user_meta( $user_id, 'billing_address_1', true ))){ $ubilling_address_1= get_user_meta( $user_id, 'billing_address_1', true ) ;}else  $ubilling_address_1 = $address_1;
				
				if( !empty(get_user_meta( $user_id, 'billing_address_2', true ))){ $ubilling_address_2= get_user_meta( $user_id, 'billing_address_2', true ); }else  $ubilling_address_2 = $address_2;

				if( !empty(get_user_meta( $user_id, 'billing_city', true ))){ $ubilling_city= get_user_meta( $user_id, 'billing_city', true ) ; }else  $ubilling_city = $city;

				if( !empty(get_user_meta( $user_id, 'billing_state', true ))){ $ubilling_state= get_user_meta( $user_id, 'billing_state', true ); }else  $ubilling_state = $state;

				if( !empty(get_user_meta( $user_id, 'billing_postcode', true ))){ $ubilling_postcode= get_user_meta( $user_id, 'billing_postcode', true ); }else  $ubilling_postcode = $postcode;

				if( !empty(get_user_meta( $user_id, 'billing_country', true ))){ $ubilling_country= get_user_meta( $user_id, 'billing_country', true ); }else  $ubilling_country = $country;				
				
				$address = array(
					'first_name' => $ufirst,
					'last_name'  => $ulast,
					'company'    => $ubilling_company,
					'email'      => $ubilling_email,
					'phone'      => $ubilling_phone,
					'address_1'  => $ubilling_address_1,
					'address_2'  => $ubilling_address_2,
					'city'       => $ubilling_city,
					'state'      => $ubilling_state,
					'postcode'   => $ubilling_postcode,
					'country'    => $ubilling_country
				);				
			}else{
				$address = array(
					'first_name' => $first_name,
					'last_name'  => $last_name,
					'company'    => $company,
					'email'      => $email,
					'phone'      => $phone,
					'address_1'  => $address_1,
					'address_2'  => $address_2, 
					'city'       => $city,
					'state'      => $state,
					'postcode'   => $postcode,
					'country'    => $country
				);				
			}
			

			if( !empty($userID) ){
				$order = wc_create_order(array('customer_id' =>  $userID ));
			}else $order = wc_create_order();	
			
			$products =  explode(',',sanitize_text_field( str_replace(".",",",$allDataInSheet[$i][$_POST['product_ids']] ) ) );							
			$quantities =  explode(',',sanitize_text_field( str_replace(".",",",$allDataInSheet[$i][$_POST['quantities']] ) ) );

			$numberProducts = count( $products );
			
			$productss ='';
			for ($x = 0; $x < $numberProducts; $x++) {

				if( $_POST['product_by'] == 'sku' ) {
					$products[$x] = $this->get_product_by_sku( $products[$x] );
				}elseif( $_POST['product_by'] == 'title' ) {
					$products[$x] = post_exists( sanitize_text_field( $products[$x] ) );
				}elseif( $_POST['product_by'] == 'id' ) {
					$products[$x] = (int)$products[$x];	
				}
				
			
								
				
				if( !empty($products[$x]) || $products[$x] !=0 ){
					
					global $woocommerce;
					$products[$x] = wc_get_product( $products[$x] );

					if( $products[$x] instanceof WC_Product && $products[$x]->is_type( 'simple' ) ) {
						$productss = 'yes';
						$order->add_product( $products[$x], (int)$quantities[$x] );
					
					}
								
					
				}
				
				
			}
			

			if( $productss !=''  ){
				
				$order->set_address( $address, 'billing' );
				
				if( !empty ( $_POST['same_address']) ) {
					$order->set_address( $address, 'shipping' );
				}
				
				//SET SHIPPING
				
				// Get the customer country code
				$country_code = $order->get_shipping_country();
				// Set the array for tax calculations
				$calculate_tax_for = array(
					'country' => $this->countrycodes[$country_code],
					'state' => '', // Can be set (optional)
					'postcode' => '', // Can be set (optional)
					'city' => '', // Can be set (optional)
				);
				// set a total shipping amount
				$shipping_amount = sanitize_text_field($allDataInSheet[$i][$_POST['shipping_amount']]);
				// Get a new instance of the WC_Order_Item_Shipping Object
				$item = new WC_Order_Item_Shipping();
				$item->set_method_title( sanitize_text_field($allDataInSheet[$i][$_POST['shipping_method']]) );
				//$item->set_method_id( "flat_rate:14" ); // set an existing Shipping method rate ID
				$item->set_total( $shipping_amount ); // (optional)
				$item->calculate_taxes($calculate_tax_for);

				$order->add_item( $item );
				

				$order->calculate_totals();
				// Set payment gateway
				$payment_gateways = WC()->payment_gateways->payment_gateways();
				$order->set_payment_method( $payment_gateways[ $payment_method ] );		

				$order->update_status( $status, 'Imported order', TRUE);
				
				wp_update_post(array ('ID' => $order->get_id(),'post_date'=> $date,'post_date_gmt' => get_gmt_from_date( $date )));
				
				$order->set_date_paid( $order_pay_date );
				
				$order->calculate_totals();
			
			}
			
			if( $productss==''  ){
				print "<p class='warning'>".esc_html__('No Simple product found for creating an order - add your products first', 'procsme').".</p>";			
				if($order)wp_delete_post($order->id,true);
			}else{
				print "<p class='success'>
				<a href='".admin_url( "/post.php?post=" . $order->id . "&action=edit")."' target='_blank'>Order ".$order->id." </a> ".esc_html__( 'created','procsme' ).".</p>";				
			}

				
			if($i === $_REQUEST['ordersfinalupload']){						
				$tota = $_REQUEST['ordersfinalupload']-1;
				print "<div class='importMessageSussess'><h2>".esc_html($i)." / ".esc_html($_REQUEST['ordersfinalupload'])." ".esc_html__('- JOB DONE!', 'procsme'). " <a href='".esc_url(admin_url( '/admin.php?page=wc-orders' ))."' target='_blank'><i class='fa fa-eye'></i> ".esc_html__('View Orders', 'procsme')."</a></h2></div>";
					
				unlink($filename);
			}else{
				
				print "<div class='importMessage'>
							<h2>".esc_html($i)." / ".esc_html($_REQUEST['ordersfinalupload'])." ".esc_html__('Please dont close this page... Loading...', 'procsme')."</h2>
								<p>
									<img  src='".esc_url(plugins_url( '../images/loading.gif', __FILE__ ))."' />
								</p>
						</div>";
			}
			die;										
		}
	}



	public function exportOrdersForm(){
		
		?>
				<p class='exportToggler button button-secondary warning   btn btn-danger'><i class='fa fa-eye '></i> 
					<?php esc_html_e('Filter & Fields to Show', 'procsme');?>
				</p>
				
				<form name='exportOrdersForm' id='exportOrdersForm' method='post' action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=exportOrders'); ?>" >	
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
								<?php esc_html_e('Status', 'procsme');?>
							</td>
							<td>
								<select name='status' id='status' >
									<option value=''><?php esc_html_e('All', 'procsme');?></option>
									<option value='wc-completed' selected><?php esc_html_e('Completed', 'procsme');?></option>
									<option value='wc-hold'><?php esc_html_e('Hold', 'procsme');?></option>
									<option value='wc-refunded'><?php esc_html_e('Refunded', 'procsme');?></option>
									<option value='wc-trash'><?php esc_html_e('Trash', 'procsme');?></option>
								</select>								
							</td>
							<td></td><td></td>
						</tr>
						
						<tr>
							<td>
							<?php esc_html_e('From Creation Date', 'procsme');?>
							</td>	
							<td>
							<input type='date' style='width:100%;'  name='fromDate' id='fromDate' placeholder='<?php esc_html_e('FROM date', 'procsme');?>' />
							</td>							
							<td>
							<?php esc_html_e('To Creation Date', 'procsme');?>
							</td>	
							<td>
							<input type='date' style='width:100%;'  name='toDate' id='toDate' placeholder='<?php esc_html_e('TO date', 'procsme');?>' />
							</td>						
						</tr>
						
						<tr>
							<td>
							<?php esc_html_e('Limit Results', 'procsme');?>
							</td>
							<td>
								<input type='number' min="1" max="100000" style='width:100%;'  name='posts_per_page' id='posts_per_page' placeholder='<?php esc_html_e('Number to display..', 'procsme');?>' />
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
							
						
						$cols=array('get_id()','get_status()','get_date_created()','get_date_completed()','get_date_paid()','get_product_id()','get_name()','get_quantity()','get_customer_id()','get_billing_first_name()','get_billing_last_name()','get_billing_email()','get_billing_phone()','get_billing_country()','get_billing_state()','get_billing_city()','get_billing_address1()','get_billing_address2()','get_billing_postcode()','get_shipping_first_name()','get_shipping_last_name()','get_shipping_country()','get_shipping_state()','get_shipping_city()','get_shipping_address1()','get_shipping_address2()','get_shipping_postcode()','get_subtotal()' ,'get_total_refunded()','get_total()','get_total_tax()','get_total_discount()','get_shipping_total()','get_payment_method_title()' );
				
							print "<tr>";
							$checked = 'checked';
							foreach( $cols as $col){
								$colname = str_replace("()","",$col);
								$colname = str_replace("get","",$colname);
								$colname = str_replace("_"," ",$colname);
								
								print "<td style='float:left'><input type='checkbox' class='fieldsToShow'  name='toShow".esc_attr($col)."' value='1'/><label for='".esc_attr($col)."'>". esc_html($colname). "</label></td>";
								

								
							}
							?>
						</tr>
					</table>
		
							
					<input type='hidden' name='columnsToShow' value='1'  />
					<input type='hidden' id='action' name='action' value='exportOrders_process' />
					<?php wp_nonce_field('columnsToShow'); ?>

					<?php submit_button(esc_html__( 'Search', 'procsme' ),'primary','Search'); ?>

				</form>
			
			<div class='resultExport'>
				<?php $this->exportOrders(); ?>
			</div>
		<?php			
	}	

	public function exportOrders(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') && $_REQUEST['columnsToShow'] ){
			
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );	
			
			if(!empty($_POST['posts_per_page'])){
				$this->posts_per_page = (int)$_POST['posts_per_page'] ;
			}else $this->posts_per_page = '-1';
			
			$args = array(
				'limit' => $this->posts_per_page,
				'type' => 'shop_order',
				'orderby' => 'date', //has no effect as its a meta field 
				'order' => 'DESC',
			);
			
			if(!empty($_POST['fromDate'])){
				
				$from = sanitize_text_field($_POST['fromDate']);
				$to = sanitize_text_field($_POST['toDate']);
				$args['date_created'] = $from."...".$to;
			}

			
			if(!empty($_POST['offset'])){
				$this->offset = (int)$_POST['offset'];
			}else $this->offset = '-1';
			
			if(!empty($_POST['keyword']))  {
				$this->keyword = sanitize_text_field( $_POST['keyword'] );
				$args['s'] = $this->keyword	;
			}
			if(!empty($_POST['status'])) {
				$this->status = sanitize_text_field( $_POST['status'] );
				$args['status'] = $this->status	;
			}
			

			$getorders = wc_get_orders( $args );
		
			
			// Check for orders
			if ( $getorders  ){
				
				
				$i=0;
				?>
				<p class='message error'>
					<?php esc_html_e( 'Wait... Download is loading...', 'procsme' );?>
					<b class='totalPosts'  >
						<?php print esc_html( count($getorders) );?>
					</b>					
				</p>

				<?php		
				if( count($getorders) <= 500 ){
					$start=0;
				}else $start=500;
				print " <b class='startPosts'>".esc_html($start)."</b>";
							

				$column_name=array('get_id()','get_status()','get_date_created()','get_date_completed()','get_date_paid()','get_product_id()','get_name()','get_quantity()','get_customer_id()','get_billing_first_name()','get_billing_last_name()','get_billing_email()','get_billing_phone()','get_billing_country()','get_billing_state()','get_billing_city()','get_billing_address1()','get_billing_address2()','get_billing_postcode()','get_shipping_first_name()','get_shipping_last_name()','get_shipping_country()','get_shipping_state()','get_shipping_city()','get_shipping_address1()','get_shipping_address2()','get_shipping_postcode()','get_subtotal()' ,'get_total_refunded()','get_total()','get_total_tax()','get_total_discount()','get_shipping_total()','get_payment_method_title()' );			
				
				?>
				

				
				<div id="myProgress">
					 <div id="myBar"></div>
				</div>
				<div class='exportTableWrapper'>
					<table id='toExport'>
						<thead>
							<tr> 
								<?php
									foreach($column_name as $d){
										
										if(isset($_REQUEST["toShow".strtolower(str_replace(" ","_",$d))] ) ){
											$d = strtoupper(str_replace("_"," ",$d));
											$d = str_replace("()","",$d);
											$d = str_replace("GET","",$d);
											

											print "<th>".esc_html( $d )."</th>";
																						
											
																					
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
					<?php esc_html_e( 'No Orders found.', 'procsme' );?>
				</p>
					<?php			
			}
		}//check request						
	}
	
	
	public function exportOrders_process(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') ){
					
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );
			
			if(!empty($_POST['posts_per_page'])){
				$this->posts_per_page = (int)$_POST['posts_per_page'] ;
			}else $this->posts_per_page = '-1';
			
			$args = array(
				'limit' => $this->posts_per_page,
				'type' => 'shop_order',
				'orderby' => 'date', //has no effect as its a meta field 
				'order' => 'DESC',
			);
			
			if(!empty($_POST['fromDate'])){
				
				$from = sanitize_text_field($_POST['fromDate']);
				$to = sanitize_text_field($_POST['toDate']);
				$args['date_created'] = $from."...".$to;
			}
			

			
			if(!empty($_POST['offset'])){
				$this->offset = (int)$_POST['offset'];
			}else $this->offset = '-1';
			
			if(!empty($_POST['keyword']))  {
				$this->keyword = sanitize_text_field( $_POST['keyword'] );
				$args['s'] = $this->keyword	;
			}
			if(!empty($_POST['status'])) {
				$this->status = sanitize_text_field( $_POST['status'] );
				$args['status'] = $this->status	;
			}
			
			$getorders = wc_get_orders( $args );
		
			
			// Check for orders
			if ( $getorders  ){
								
				
				$cols=array('get_id()','get_status()','get_date_created()','get_date_completed()','get_date_paid()','get_product_id()','get_name()','get_quantity()','get_customer_id()','get_billing_first_name()','get_billing_last_name()','get_billing_email()','get_billing_phone()','get_billing_country()','get_billing_state()','get_billing_city()','get_billing_address1()','get_billing_address2()','get_billing_postcode()','get_shipping_first_name()','get_shipping_last_name()','get_shipping_country()','get_shipping_state()','get_shipping_city()','get_shipping_address1()','get_shipping_address2()','get_shipping_postcode()','get_subtotal()' ,'get_total_refunded()','get_total()','get_total_tax()','get_total_discount()','get_shipping_total()','get_payment_method_title()' );
				
				foreach( $getorders as $order ){
					
					 ?>						
						<tr>
							<?php foreach($cols as $meta){ 
								if(isset($_REQUEST["toShow".$meta]) ){
							?>
								
								<?php if($meta ==='get_id()') { ?>
									<td><?php print esc_html( $order->get_id() );  ?></td>
								<?php } ?>
								<?php if($meta ==='get_transaction_id()') { ?>
									<td><?php print esc_html( $order->get_transaction_id());  ?></td>
								<?php } ?>
								<?php if($meta ==='get_status()') { ?>
									<td><?php print esc_html( $order->get_status() );  ?></td>
								<?php } ?>
								
								<?php if($meta ==='get_date_created()') { ?>
									<td><?php print esc_html( date("d M Y h:i:s",strtotime($order->get_date_created() ) ));  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_date_completed()') { ?>
									<td><?php print esc_html( date("d M Y h:i:s",strtotime($order->get_date_completed() ) ));  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_date_paid()') { ?>
									<td><?php print esc_html( date("d M Y h:i:s",strtotime($order->get_date_paid() ) ));  ?></td>
								<?php } ?>									

								<?php if($meta ==='get_customer_id()') { ?>
									<td><?php print esc_html( $order->get_customer_id());  ?></td>
								<?php } ?>	
								
								<?php if($meta ==='get_billing_first_name()') { ?>
									<td><?php print esc_html( $order->get_billing_first_name());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_last_name()') { ?>
									<td><?php print esc_html( $order->get_billing_last_name() );  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_country()') { ?>
									<td><?php print esc_html( $order->get_billing_country());  ?></td>
								<?php } ?>
								<?php if($meta ==='get_billing_email()') { ?>
									<td><?php print esc_html( $order->get_billing_email());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_city()') { ?>
									<td><?php print esc_html( $order->get_billing_city());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_state()') { ?>
									<td><?php print esc_html( $order->get_billing_state());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_postcode()') { ?>
									<td><?php print esc_html( $order->get_billing_postcode());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_phone()') { ?>
									<td><?php print esc_html( $order->get_billing_phone());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_address1()') { ?>
									<td><?php print esc_html( $order->get_billing_address_1());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_billing_address2()') { ?>
									<td><?php print esc_html( $order->get_billing_address_2());  ?></td>
								<?php } ?>	
								
								<?php if($meta ==='get_shipping_first_name()') { ?>
									<td><?php print esc_html( $order->get_shipping_first_name());  ?></td>
								<?php } ?>
								<?php if($meta ==='get_address()') { ?>
									<td><?php print esc_html( $order->get_address());  ?></td>
								<?php } ?>								
								<?php if($meta ==='get_shipping_last_name()') { ?>
									<td><?php print esc_html( $order->get_shipping_last_name());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_shipping_country()') { ?>
									<td><?php print esc_html( $order->get_shipping_country());  ?></td>
								<?php } ?>

								<?php if($meta ==='get_shipping_city()') { ?>
									<td><?php print esc_html( $order->get_shipping_city());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_shipping_state()') { ?>
									<td><?php print esc_html( $order->get_shipping_state());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_shipping_postcode()') { ?>
									<td><?php print esc_html( $order->get_shipping_postcode());  ?></td>
								<?php } ?>	

								<?php if($meta ==='get_shipping_address1()') { ?>
									<td><?php print esc_html( $order->get_shipping_address_1());  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_shipping_address2()') { ?>
									<td><?php print esc_html( $order->get_shipping_address_2() );  ?></td>
								<?php } ?>


								<?php if($meta ==='get_total_tax()') { ?>
									<td><?php print $order->get_total_tax();  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_total_discount()') { ?>
									<td><?php print $order->get_total_discount();  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_shipping_total()') { ?>
									<td><?php print $order->get_shipping_total();  ?></td>
								<?php } ?>	

								<?php if($meta ==='get_total_refunded()') { ?>
									<td><?php print $order->get_total_refunded();  ?></td>
								<?php } ?>								
								<?php if($meta ==='get_subtotal()') { ?>
									<td><?php print $order->get_subtotal();  ?></td>
								<?php } ?>	
								<?php if($meta ==='get_total()') { ?>
									<td><?php print $order->get_total();  ?></td>
								<?php } ?>
	
								<?php if($meta ==='get_payment_method_title()') { ?>
									<td><?php print $order->get_payment_method_title();  ?></td>
								<?php } ?>	
								
								
								
								<?php if($meta ==='get_product_id()') { 
									$productIds=array();
									foreach ( $order->get_items() as $item_id => $item ) {
										
										$product = wc_get_product( $item->get_product_id() );
											$product_id = $item->get_product_id();
											array_push($productIds,$item->get_product_id());
									
									}
									
								?>
									<td><?php print implode(",",$productIds);  ?></td>
								<?php } ?>	
								

								<?php if($meta ==='get_name()') { 
									$productnames=array();
									foreach ( $order->get_items() as $item_id => $item ) {

									   array_push($productnames,$item->get_name());
									}
									
								?>
									<td><?php print implode(",",$productnames);  ?></td>
								<?php } ?>	

								<?php if($meta ==='get_quantity()') { 
									$productQuantities=array();
									foreach ( $order->get_items() as $item_id => $item ) {
										
									   array_push($productQuantities,$item->get_quantity());
																		
									}
									
								?>
									<td><?php print implode(",",$productQuantities);  ?></td>
								<?php } ?>									
							
								<?php
								
							}
								
							}									
	
						print "</tr>";	
					
				}
			
	
				die;
															
			}else print "<p class='warning' >".esc_html__('No Orders Found', 'procsme')."</p>";//end if	

				
			
		}//check request						
	}
	
}