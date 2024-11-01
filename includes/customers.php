<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require plugin_dir_path( __FILE__ ) .'../Classes/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class StoreMigrationWooCommerce_Customers{
	

	public $numberOfRows=1;
	public $posts_per_page='';
	public $role='';
	public $offset='';
	public $countrycodes = array("Afghanistan"=>"AF","Åland Islands"=>"AX","Albania"=>"AL","Algeria"=>"DZ","American Samoa"=>"AS","Andorra"=>"AD","Angola"=>"AO","Anguilla"=>"AI","Antarctica"=>"AQ","Antigua and Barbuda"=>"AG","Argentina"=>"AR","Armenia"=>"AM","Aruba"=>"AW","Australia"=>"AU","Austria"=>"AT","Azerbaijan"=>"AZ","Bahrain"=>"BH","Bahamas"=>"BS","Bangladesh"=>"BD","Barbados"=>"BB","Belarus"=>"BY","Belgium"=>"BE","Belize"=>"BZ","Benin"=>"BJ","Bermuda"=>"BM","Bhutan"=>"BT","Bolivia, Plurinational State of"=>"BO","Bonaire, Sint Eustatius and Saba"=>"BQ","Bosnia and Herzegovina"=>"BA","Botswana"=>"BW","Bouvet Island"=>"BV","Brazil"=>"BR","British Indian Ocean Territory"=>"IO","Brunei Darussalam"=>"BN","Bulgaria"=>"BG","Burkina Faso"=>"BF","Burundi"=>"BI","Cambodia"=>"KH","Cameroon"=>"CM","Canada"=>"CA","Cape Verde"=>"CV","Cayman Islands"=>"KY","Central African Republic"=>"CF","Chad"=>"TD","Chile"=>"CL","China"=>"CN","Christmas Island"=>"CX","Cocos (Keeling) Islands"=>"CC","Colombia"=>"CO","Comoros"=>"KM","Congo"=>"CG","Congo, the Democratic Republic of the"=>"CD","Cook Islands"=>"CK","Costa Rica"=>"CR","Côte d'Ivoire"=>"CI","Croatia"=>"HR","Cuba"=>"CU","Curaçao"=>"CW","Cyprus"=>"CY","Czech Republic"=>"CZ","Denmark"=>"DK","Djibouti"=>"DJ","Dominica"=>"DM","Dominican Republic"=>"DO","Ecuador"=>"EC","Egypt"=>"EG","El Salvador"=>"SV","Equatorial Guinea"=>"GQ","Eritrea"=>"ER","Estonia"=>"EE","Ethiopia"=>"ET","Falkland Islands (Malvinas)"=>"FK","Faroe Islands"=>"FO","Fiji"=>"FJ","Finland"=>"FI","France"=>"FR","French Guiana"=>"GF","French Polynesia"=>"PF","French Southern Territories"=>"TF","Gabon"=>"GA","Gambia"=>"GM","Georgia"=>"GE","Germany"=>"DE","Ghana"=>"GH","Gibraltar"=>"GI","Greece"=>"GR","Greenland"=>"GL","Grenada"=>"GD","Guadeloupe"=>"GP","Guam"=>"GU","Guatemala"=>"GT","Guernsey"=>"GG","Guinea"=>"GN","Guinea-Bissau"=>"GW","Guyana"=>"GY","Haiti"=>"HT","Heard Island and McDonald Islands"=>"HM","Holy See (Vatican City State)"=>"VA","Honduras"=>"HN","Hong Kong"=>"HK","Hungary"=>"HU","Iceland"=>"IS","India"=>"IN","Indonesia"=>"ID","Iran, Islamic Republic of"=>"IR","Iraq"=>"IQ","Ireland"=>"IE","Isle of Man"=>"IM","Israel"=>"IL","Italy"=>"IT","Jamaica"=>"JM","Japan"=>"JP","Jersey"=>"JE","Jordan"=>"JO","Kazakhstan"=>"KZ","Kenya"=>"KE","Kiribati"=>"KI","Korea, Democratic People's Republic of"=>"KP","Korea, Republic of"=>"KR","Kuwait"=>"KW","Kyrgyzstan"=>"KG","Lao People's Democratic Republic"=>"LA","Latvia"=>"LV","Lebanon"=>"LB","Lesotho"=>"LS","Liberia"=>"LR","Libya"=>"LY","Liechtenstein"=>"LI","Lithuania"=>"LT","Luxembourg"=>"LU","Macao"=>"MO","Macedonia, the Former Yugoslav Republic of"=>"MK","Madagascar"=>"MG","Malawi"=>"MW","Malaysia"=>"MY","Maldives"=>"MV","Mali"=>"ML","Malta"=>"MT","Marshall Islands"=>"MH","Martinique"=>"MQ","Mauritania"=>"MR","Mauritius"=>"MU","Mayotte"=>"YT","Mexico"=>"MX","Micronesia, Federated States of"=>"FM","Moldova, Republic of"=>"MD","Monaco"=>"MC","Mongolia"=>"MN","Montenegro"=>"ME","Montserrat"=>"MS","Morocco"=>"MA","Mozambique"=>"MZ","Myanmar"=>"MM","Namibia"=>"NA","Nauru"=>"NR","Nepal"=>"NP","Netherlands"=>"NL","New Caledonia"=>"NC","New Zealand"=>"NZ","Nicaragua"=>"NI","Niger"=>"NE","Nigeria"=>"NG","Niue"=>"NU","Norfolk Island"=>"NF","Northern Mariana Islands"=>"MP","Norway"=>"NO","Oman"=>"OM","Pakistan"=>"PK","Palau"=>"PW","Palestine, State of"=>"PS","Panama"=>"PA","Papua New Guinea"=>"PG","Paraguay"=>"PY","Peru"=>"PE","Philippines"=>"PH","Pitcairn"=>"PN","Poland"=>"PL","Portugal"=>"PT","Puerto Rico"=>"PR","Qatar"=>"QA","Réunion"=>"RE","Romania"=>"RO","Russian Federation"=>"RU","Rwanda"=>"RW","Saint Barthélemy"=>"BL","Saint Helena, Ascension and Tristan da Cunha"=>"SH","Saint Kitts and Nevis"=>"KN","Saint Lucia"=>"LC","Saint Martin (French part)"=>"MF","Saint Pierre and Miquelon"=>"PM","Saint Vincent and the Grenadines"=>"VC","Samoa"=>"WS","San Marino"=>"SM","Sao Tome and Principe"=>"ST","Saudi Arabia"=>"SA","Senegal"=>"SN","Serbia"=>"RS","Seychelles"=>"SC","Sierra Leone"=>"SL","Singapore"=>"SG","Sint Maarten (Dutch part)"=>"SX","Slovakia"=>"SK","Slovenia"=>"SI","Solomon Islands"=>"SB","Somalia"=>"SO","South Africa"=>"ZA","South Georgia and the South Sandwich Islands"=>"GS","South Sudan"=>"SS","Spain"=>"ES","Sri Lanka"=>"LK","Sudan"=>"SD","Suriname"=>"SR","Svalbard and Jan Mayen"=>"SJ","Swaziland"=>"SZ","Sweden"=>"SE","Switzerland"=>"CH","Syrian Arab Republic"=>"SY","Taiwan, Province of China"=>"TW","Tajikistan"=>"TJ","Tanzania, United Republic of"=>"TZ","Thailand"=>"TH","Timor-Leste"=>"TL","Togo"=>"TG","Tokelau"=>"TK","Tonga"=>"TO","Trinidad and Tobago"=>"TT","Tunisia"=>"TN","Turkey"=>"TR","Turkmenistan"=>"TM","Turks and Caicos Islands"=>"TC","Tuvalu"=>"TV","Uganda"=>"UG","Ukraine"=>"UA","United Arab Emirates"=>"AE","United Kingdom"=>"GB","United States"=>"US","United States Minor Outlying Islands"=>"UM","Uruguay"=>"UY","Uzbekistan"=>"UZ","Vanuatu"=>"VU","Venezuela, Bolivarian Republic of"=>"VE","Viet Nam"=>"VN","Virgin Islands, British"=>"VG","Virgin Islands, U.S."=>"VI","Wallis and Futuna"=>"WF","Western Sahara"=>"EH","Yemen"=>"YE","Zambia"=>"ZM","Zimbabwe"=>"ZW");		
	
	
	
	public function importUsersDisplay(){?>
		<h2>
		<?php esc_html_e("IMPORT / UPDATE Customers","procsme");?>
		</h2>	
		<p>
			<?php esc_html_e("Download the sample excel file, save it and add your Customers. Upload it using the form below.","procsme");?> 
			<a href='<?php echo plugins_url( '../example_excel/import-customers.xlsx', __FILE__ ); ?>'>
				<?php esc_html_e("Customers Excel Sample","procsme");?>
			</a>		
		</p>

		<?php


		
		?>		
		<div>			
			<form method="post" id='user_import' enctype="multipart/form-data" action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=customers' ); ?>">

				<table class="form-table">
						<tr valign="top">
							<td>
								<?php wp_nonce_field('excel_upload'); ?>
								<input type="hidden"   name="importUsers" value="1" />
								<div class="uploader">
									<img src="" class='userSelected'/>
									<input type="file"  required name="file" class="usersImportFile"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
								</div>						
							</td>
						</tr>
				</table>
				<?php submit_button( esc_html__( 'Upload', 'procsme' ) ,'primary','upload'); ?>
			</form>	
			<div class='result'>
				<?php  $this->importUsers(); ?>
			</div>					
		</div>
	<?php
	}

	public function customers_meta_keys(){
		$meta_keys =  array_keys( get_user_meta( get_current_user_id() ) );
		set_transient('meta_keys', $meta_keys, 60*60*24); # create 1 Day Expiration
		if(!empty($meta_keys)){		
			return 	$meta_keys;
		}
	}

	public function importUsers(){
		
		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator')  && isset($_POST['importUsers']) ){
		
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
						<form method='POST' id ='user_process' action= "<?php print admin_url( 'admin.php?page=store-migration-woocommerce&tab=customers' );?>">

							<p>
								<?php esc_html_e( 'DATA MAPPING: Drag and drop excel columns on the right to customer properties on the left.', 'procsme' ); ?>
							</p>
							<p class='proVersion'>
								<i ><b > <?php esc_html_e( 'Auto Match Columns - PRO', 'procsme' ) ?> <input type='checkbox' disabled  /> </b></i>
							</p>
							
							<div class='columns3 border'>	

								<p class='proVersion'>
									<input type='checkbox'  disabled readonly  /> <b> <?php esc_html_e( 'Send Email to newly created Users - PRO Version', 'procsme' ) ?> </b>
								</p>
								<p class='proVersion'>
									<input type='checkbox' disabled readonly /> <b> <?php esc_html_e( 'Update Existing Users - PRO Version', 'procsme' ) ?> </b>
								</p>							
								<h2>
									<?php _e( 'EXCEL COLUMNS', 'procsme' ) ?>
								</h2>
								
								<p>
									<?php foreach ($cellIterator as $cell) {
										echo "<input type='button' class='draggable' style='min-width:100px;background:#000;color:#fff' key ='".sanitize_text_field($cell->getColumn())."' value='". sanitize_text_field($cell->getValue()) ."' />  <br/>";
									} ?>				
								</p>
								
		   
							   <input type='hidden' name='customersfinalupload' value='<?php print esc_attr( $total );?>' />
							   <input type='hidden' name='start' value='2' />
							   <input type='hidden' name='action' value='importCustomers_process' />
							   <?php 
									wp_nonce_field('excel_process','secNonce');
									submit_button( esc_html( 'Upload', 'procsme' ),'primary','check'); 
								?>								
								
							</div>
							
							<?php
								$dontUse = array('syntax_highlighting','rich_editing','comment_shortcuts','admin_color','wp_user_level','dismissed_wp_pointers','show_welcome_panel','wp_dashboard_quick_press_last_post_id','session_tokens','use_ssl','show_admin_bar_front','_woocommerce_persistent_cart_1','closedpostboxes_dashboard','dismissed_store_notice_setting_moved_notice','dismissed_no_secure_connection_notice','jetpack_tracks_anon_id','last_update','_woocommerce_tracks_anon_id','tgmpa_dismissed_notice_tgm_foody_pro','dismissed_wc_admin_notice','paying_customer','erp_hr_disable_notification','erp_hr_disable_notification','wp_user-settings-time','wp_user-settings','wc_last_active','metaboxhidden_dashboard','wp_capabilities','locale');
							?>
							<div class='columns2'>
							
								<h2>
									<?php esc_html_e( 'CUSTOMER FIELDS', 'procsme' ) ?>
								</h2>
								
								<p class=''>
									<b><?php esc_html_e( 'EMAIL ', 'procsme' ) ?></b><input type='text'  name='email' required readonly class='droppable' placeholder='email'  />
								</p>
								<p class=''>
									<b><?php esc_html_e( 'USERNAME ', 'procsme' ) ?></b><input type='text'  name='username' required readonly class='droppable' placeholder='username'  />
								</p>

								<p class=''>
									<b><?php esc_html_e( 'PASSWORD ', 'procsme' ) ?></b><input type='text'  name='password' required readonly class='droppable' placeholder='password'  />
								</p>	
								<p class=''>
									<b><?php esc_html_e( 'URL - WEBSITE ', 'procsme' ) ?></b><input type='text'  name='url' required readonly class='droppable' placeholder='url'  />
								</p>								
								<?php 
								if(!empty( $this->customers_meta_keys()) ){
									foreach($this->customers_meta_keys() as $meta ){
										if(!in_array($meta,$dontUse) ){
										echo "<p>
												<b>".strtoupper(str_replace('_',' ',esc_html($meta)))."</b> <input type='text'  name='".esc_attr($meta)."' required readonly class='droppable' placeholder='Drop here column'  />
										</p>";
										}
									}
								}
								?>


								
							</div>	
							
							
			
						</form>
					</div>
					
					<?php move_uploaded_file($_FILES["file"]["tmp_name"], plugin_dir_path( __FILE__ ).'import_customers.xlsx');
				
				}else   "<h3>". _e('Invalid File:Please Upload Excel File', 'procsme')."</h3>";				
				
			}
		}
	
	}

	public function importCustomers_process(){
		
		if(isset($_POST['customersfinalupload']) && current_user_can('administrator')){
			
			$time_start = microtime(true);

			check_admin_referer( 'excel_process','secNonce' );
			check_ajax_referer( 'excel_process' ,'secNonce');
					
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			
			
			
			$filename = plugin_dir_path( __FILE__ ).'import_customers.xlsx';
			
			$objPHPExcel = IOFactory::load($filename);
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$data = count($allDataInSheet);  // Here get total count of row in that Excel sheet	

			
			//parameters for running with ajax - no php timeouts
			$i=sanitize_text_field($_POST['start']);
			$start = $i -1;

			//SANITIZE AND VALIDATE title and description	
			$email = sanitize_text_field($allDataInSheet[$i][$_POST['email']]);
			$url = sanitize_text_field($allDataInSheet[$i][$_POST['url']]);
			$name = sanitize_text_field($allDataInSheet[$i][$_POST['first_name']]);
			$nickname = sanitize_text_field($allDataInSheet[$i][$_POST['nickname']]);
			$username = sanitize_text_field($allDataInSheet[$i][$_POST['username']]);
			$password = sanitize_text_field($allDataInSheet[$i][$_POST['password']]);
			
			$x=0;
			
				if( null == email_exists( $email ) ) {

					  // Generate the password and create the user
					 // $password = wp_generate_password( 12, false );
					  $id = wp_create_user( $username, $password, $email );

					  // Set the nickname
					  wp_update_user(
						array(
						  'ID'          =>    $id,
						  'user_nicename'    =>    $nickname,
						  'user_login'   =>    $username,
						  'display_name'	=> $username,
						  'user_url'	=> $url,
						)
					  );

					  // Set the role
						if(!empty($roles)){
							
							// Set the rol
							$user = new WP_User( $id );
							$user->add_role( 'customer' );

						}
							
					print "<p class='success'>".$username . esc_html__( ' created', 'procsme' ).".</p>";
							

				}else{
					print "<p class='warning'>".esc_html__( 'User with ', 'procsme' ) . $email . esc_html__( ' already exists. ', 'procsme' ).".</p>";
					
					
				}
				
				add_action('user_register', array( $this,'customers_meta_keys') );    
				add_action('personal_options_update', array( $this,'customers_meta_keys') );    
				add_action('edit_user_profile_update', array( $this,'customers_meta_keys') );

				//CUSTOM FIELDS UPDATE START
				if( !empty( $this->customers_meta_keys() )){
					foreach( $this->customers_meta_keys() as $meta){
						if( isset($allDataInSheet[$i][$_POST[$meta]] ) && !empty($allDataInSheet[$i][$_POST[$meta]]) ){
							
							if( $meta == 'billing_country' || $meta == 'shipping_country' || $meta == 'country' ){
							
							if( strlen( $allDataInSheet[$i][$_POST[$meta]] ) == 2 ) {								
								update_user_meta($id, $meta, sanitize_text_field( $allDataInSheet[$i][$_POST[$meta]] ) );
							}else{
								update_user_meta($id, $meta, sanitize_text_field( $this->countrycodes[ $allDataInSheet[$i][$_POST[$meta]] ] ) );
								
							}								
								
							}else update_user_meta($id, $meta, sanitize_text_field( $allDataInSheet[$i][$_POST[$meta]] ) );
							
							
						}
					}
				}
				//CUSTOM FIELDS UPDATE END	


					
			if($i === $_REQUEST['customersfinalupload']){						
				$tota = $_REQUEST['customersfinalupload']-1;
				print "<div class='importMessageSussess'><h2>".esc_html($i)." / ".esc_html($_REQUEST['customersfinalupload'])." ".esc_html__('- JOB DONE!', 'procsme'). " <a href='".esc_url(admin_url( 'users.php?role=customer' ))."' target='_blank'><i class='fa fa-eye'></i> ".esc_html__('View Customers', 'procsme')."</a></h2></div>";
					
				unlink($filename);
			}else{
				
				print "<div class='importMessage'>
							<h2>".esc_html($i)." / ".esc_html($_REQUEST['customersfinalupload'])." ".esc_html__('Please dont close this page... Loading...', 'procsme')."</h2>
								<p>
									<img  src='".esc_url(plugins_url( '../images/loading.gif', __FILE__ ))."' />
								</p>
						</div>";
			}
			die;										
		}
	}


	public function exportUsersForm(){
		?>
				<p class='exportToggler button button-secondary warning   btn btn-danger'><i class='fa fa-eye '></i> 
					<?php esc_html_e('Filter & Fields to Show', 'procsme');?>
				</p>
				
				<form name='exportUsersForm' id='exportUsersForm' method='post' action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=exportCustomers'); ?>" >	
					<table class='wp-list-table widefat fixed table table-bordered'>

					<tr>

							
							<td>
							<?php esc_html_e('From Creation Date', 'procsme');?>
							</td>	
							<td>
							<input type='date' style='width:100%;'  name='fromDate' id='fromDate' placeholder='<?php esc_html_e('registration date', 'procsme');?>' />
							</td>	
							<td>
							<?php esc_html_e('To Creation Date', 'procsme');?>
							</td>	
							<td>
							<input type='date' style='width:100%;'  name='toDate' id='toDate' placeholder='<?php esc_html_e('registration date', 'procsme');?>' />
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
							
							$cols = array('user_pass','user_login','user_url','user_email','role');
							
							print "<tr>";
							$checked = 'checked';
							foreach( $cols as $col){					
								print "<td style='float:left'><input type='checkbox' class='fieldsToShow'  name='toShow".$col."' value='1'/><label for='".$col."'>". $col. "</label></td>";
								//array_push($cols,$col );	
							}
							if(!empty($this->customers_meta_keys())){

								$dontUse = array('syntax_highlighting','rich_editing','comment_shortcuts','admin_color','wp_user_level','dismissed_wp_pointers','show_welcome_panel','wp_dashboard_quick_press_last_post_id','session_tokens','use_ssl','show_admin_bar_front','_woocommerce_persistent_cart_1','closedpostboxes_dashboard','dismissed_store_notice_setting_moved_notice','dismissed_no_secure_connection_notice','jetpack_tracks_anon_id','last_update','_woocommerce_tracks_anon_id','tgmpa_dismissed_notice_tgm_foody_pro','dismissed_wc_admin_notice','paying_customer','erp_hr_disable_notification','erp_hr_disable_notification','wp_user-settings-time','wp_user-settings','wc_last_active','metaboxhidden_dashboard','wp_capabilities','locale');							
								foreach($this->customers_meta_keys() as $meta){
									if(!in_array($meta , $dontUse) ){
										
											echo "<td style='float:left'>
												<input type='checkbox' class='fieldsToShow'  name='toShow".$meta."' value='1'/>
												<label for='".$meta."'>". $meta. "</label>
												</td>";										
									}

								}
								array_push($cols,$meta);					
							}?>
						</tr>
					</table>
		
							
					<input type='hidden' name='columnsToShow' value='1'  />
					<input type='hidden' id='action' name='action' value='exportUsers_process' />
					<?php wp_nonce_field('columnsToShow'); ?>

					<?php submit_button(__( 'Search', 'procsme' ),'primary','Search'); ?>

				</form>
			
			<div class='resultExport'>
				<?php $this->exportUsers(); ?>
			</div>
		<?php			
	}	

	public function exportUsers(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') && $_REQUEST['columnsToShow'] ){
			
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );	

			if(!empty($_POST['role'])){
				$this->role = sanitize_text_field($_POST['role']);
			}else $this->role='customer';

			
			if(!empty($_POST['fromDate'])){
				$args = array (
					'role'          => 'customer',
					'date_query'    => array(
						array(
							'after'     => date(sanitize_text_field($_POST['fromDate'])),
							'before' => date(sanitize_text_field($_POST['toDate'])),
							'inclusive' => true,
						),
					 ),
				);
			}else{
				$args = array(
					'role'          => 'customer',
				);				
			}
			
			// Custom query.
			$my_user_query = new WP_User_Query( $args );
			 
			// Get query results.
			$users = $my_user_query->get_results();
			 
			// Check for users
			if ( ! empty( $users ) ) {	

				$i=0;
				?>
				<p class='message error'>
					<?php esc_html_e( 'Wait... Download is loading...', 'procsme' );?>
					<b class='totalPosts'  >
						<?php print esc_html(count($users) );?>
					</b>					
				</p>

				<?php		
				if(count($users) <= 500){
					$start=0;
				}else $start=500;
				print " <b class='startPosts'>".esc_html($start)."</b>";

				$column_name =  array('user_pass','user_login','user_url','user_email','role');
				
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
									
									$dontUse = array('syntax_highlighting','rich_editing','comment_shortcuts','admin_color','wp_user_level','dismissed_wp_pointers','show_welcome_panel','wp_dashboard_quick_press_last_post_id','session_tokens','use_ssl','show_admin_bar_front','_woocommerce_persistent_cart_1','closedpostboxes_dashboard','dismissed_store_notice_setting_moved_notice','dismissed_no_secure_connection_notice','jetpack_tracks_anon_id','last_update','_woocommerce_tracks_anon_id','tgmpa_dismissed_notice_tgm_foody_pro','dismissed_wc_admin_notice','paying_customer','erp_hr_disable_notification','erp_hr_disable_notification','wp_user-settings-time','wp_user-settings','wc_last_active','metaboxhidden_dashboard','wp_capabilities','locale');							
									foreach($this->customers_meta_keys() as $meta){
										if(!in_array($meta , $dontUse) ){
											if(isset($_REQUEST["toShow".$meta]) ){ //show columns according to what is checked
												//array_push($column_name,esc_html($meta) );
												//print "<th>".esc_html($meta)."</th>";
												if( is_array($meta) ){
													print "<th>".esc_html(implode(",",$meta))."</th>";
												}else print "<th>".esc_html($meta)."</th>";
											}
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
					<?php esc_html_e( 'No Customers found.', 'procsme' );?>
				</p>
					<?php			
			}
		}//check request						
	}
	
	
	public function exportUsers_process(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') ){
					
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );
			
				if(!empty($_POST['role'])){
					$role = sanitize_text_field($_POST['role']);
				}else $role='customer';
				
				if(!empty($_POST['fromDate'])){
					$args = array (
						'role'          => 'customer',
						'date_query'    => array(
							array(
								'after'     => date(sanitize_text_field($_POST['fromDate'])),
								'before' => date(sanitize_text_field($_POST['toDate'])),
								'inclusive' => true,
							),
						 ),
					);
				}else{
					$args = array(
						'role'          => 'customer',
					);				
				}
			 
			// Custom query.
			$my_user_query = new WP_User_Query( $args );
			 
			// Get query results.
			$users = $my_user_query->get_results();
			 
			// Check for users
			if ( ! empty( $users ) ) {	
				
				$cols = array('user_pass','user_login','user_url','user_email','role');			
				 foreach ( $users as $user ) {				 
					 $user = get_userdata( $user->ID );
					 $user_roles = $user->roles;
					 ?>						
						<tr>
							<td><?php print esc_attr($user->ID) ;?></td>					
							<?php foreach($cols as $meta){
								if(isset($_REQUEST["toShow".$meta]) ){
										if($meta =='role'){?>
											<td><?php print esc_html(implode(',',$user->roles) ); ?></td>
										<?php }else{
										?>
										<td><?php print esc_html($user->$meta); 
										} ?></td>									
								<?php }
							}									
							if( !empty($this->customers_meta_keys() )){
								foreach($this->customers_meta_keys() as $meta){
									if(isset($_REQUEST["toShow".$meta]) ){
										print "<td>".esc_html(get_user_meta( $user->ID, $meta, true ))."</td>";									
									}
								}
							}	
						print "</tr>";		

				}//end while
				die;												
			}else print "<p class='warning' >".esc_html__('No Customers Found', 'procsme')."</p>";//end if						
		}//check request						
	}
	
}