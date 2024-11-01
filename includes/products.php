<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require plugin_dir_path( __FILE__ ) .'../Classes/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class StoreMigrationWooCommerce_Products{

	public $numberOfRows=1;
	public $keyword='';
	public $posts_per_page='';
	public $sale_price='';
	public $regular_price='';
	public $price_selector='';
	public $sale_price_selector='';
	public $sku='';
	public $offset='';
	public $plugin = 'eshopMigrationWooCommerce';
	public $proUrl = 'https://extend-wp.com/product/products-reviews-orders-customers-woocommerce-migration-excel';	


	public function custom_product_fields(){
					
			global $wpdb;
			$post_type = 'product';
			$query = "
				SELECT DISTINCT($wpdb->postmeta.meta_key) 
				FROM $wpdb->posts 
				LEFT JOIN $wpdb->postmeta 
				ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
				WHERE $wpdb->posts.post_type = '%s' 
				AND $wpdb->postmeta.meta_key != '' 
				AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' 
				AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
			";
			$meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));
			set_transient('meta_keys', $meta_keys, 60*60*24); # create 1 Day Expiration
			if(!empty($meta_keys)){		
				return 	$meta_keys;
			}
		
		
	}
	
	
	public function exportProductsDisplay(){?>
		<h2>
			<?php esc_html_e( 'EXPORT SIMPLE PRODUCTS. For variable get ', 'procsme' ) ?><a target='_blank' href='<?php print esc_url($this->proUrl); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>
		</h2>
		<p>
			<i><?php esc_html_e( 'Important Note: always save the generated export file in xlsx format to a new excel for import use.', 'procsme' ) ?></i>
		</p>
	   <div>	
			<?php  print "<div class='result'>". $this->exportProductsForm()."</div>"; ?>
	   </div>
	   <?php
	}

	public function vocabularySelect(){
		$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );	?>
		<form id='selectTaxonomy' action= "<?php print admin_url( 'admin.php?page=store-migration-woocommerce&tab=exportProducts') ;?>" >
			<select name='vocabularySelect' id='vocabularySelect' disabled readonly>
				<option value='' >
					<?php esc_html_e('Select..', 'procsme');?>
				</option>
				<?php
				foreach( $taxonomy_objects as $voc){ ?>
					<option value='<?php print esc_attr($voc->name);?> '>
						<?php print esc_html($voc->name) ;?>
					</option>
				<?php
				} ?>
			</select>
	<?php
	}
	
	public function exportProductsForm(){
	
		$query = new WP_Query( array(
			'post_type' => 'product',				
			'posts_per_page' => '-1',								
		) );
		if($query ->have_posts()){	
		?>
				<p class='exportToggler button button-secondary warning   btn btn-danger'><i class='fa fa-eye '></i> 
					<?php _e('Filter & Fields to Show', 'procsme');?>
				</p>
				
				<form name='selectTaxonomy' id='selectTaxonomy' method='post' action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=exportProducts' ); ?>" >
					<table class='wp-list-table widefat fixed table table-bordered'>	
						<tr>
							<td  class='proVersion'><?php esc_html_e('filter by Taxonomy - PRO', 'procsme');?></td>
							<td><?php  $this->vocabularySelect() ; ?></td>
						</tr>
					</table>
				</form>
				
				<form name='exportProductsForm' id='exportProductsForm' method='post' action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=exportProducts'); ?>" >	
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
							<td><?php esc_html_e('SKU', 'procsme');?></td>
							<td>
								<input type='text' name='sku' id='sku' placeholder='<?php esc_html_e('Search by SKU', 'procsme');?>'/>
							</td>
							<td></td><td></td>
						</tr>
						<tr class='proVersion'>
							<td>
								<?php esc_html_e('Regular Price', 'procsme');?><?php esc_html_e(' - PRO','procsme' ); ?>
							</td>
							<td>
								<input type='number' readonly disabled placeholder='<?php esc_html_e('Regular Price', 'procsme');?>'/>
							</td>
							<td>
								<?php esc_html_e('Regular Price Selector', 'procsme');?><?php esc_html_e(' - PRO','procsme' ); ?>
							</td>
							<td>
								<select readonly disabled>
									<option value=">">></option>
									<option value=">=">>=</option>
									<option value="<="><=</option>
									<option value="<"><</option>
									<option value="==">==</option>
									<option value="!=">!=</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class='proVersion'>
								<?php esc_html_e('Sale Price', 'procsme');?><?php esc_html_e(' - PRO','procsme' ); ?>
							</td>
							<td class='proVersion'>
								<input type='number' readonly disabled placeholder='<?php esc_html_e('Sale Price', 'procsme');?>'/>
							</td>
							
							<td class='proVersion'>
								<?php esc_html_e('Sale Price Selector', 'procsme');?><?php esc_html_e(' - PRO','procsme' ); ?>
							</td>
							<td class='proVersion'>
								<select readonly disabled >
									<option value=">">></option>
									<option value=">=">>=</option>
									<option value="<="><=</option>
									<option value="<"><</option>
									<option value="==">==</option>
									<option value="!=">!=</option>						
								</select>	
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
						
						<?php $taxonomy_objects = get_object_taxonomies( 'product', 'objects' ); ?>
					</table>
					
					<?php $taxonomy_objects = get_object_taxonomies( 'product', 'objects' ); ?>


					<table class='wp-list-table widefat fixed table table-bordered tax_checks'>
						<legend>
							<h2>
								<?php esc_html_e('TAXONOMIES TO SHOW', 'procsme');?>
							</h2>
						</legend>
						<tr>
							<td>
								<input type="checkbox" name="check_all1" id="check_all1" ><label for="check_all1"><?php esc_html_e('Check All', 'procsme');?></label>
							</td>
						</tr>						
						<tr>
							<?php $cols = array();
							$checked = '';
							foreach( $taxonomy_objects as $voc){
									
								
								
								if( $voc->name =='product_cat' || $voc->name =='product_tag' ){
									print "<td style='float:left'>
									<input type='checkbox' class='fieldsToShow' ".$checked." name='toShow".esc_attr($voc->name)."' value='1'/>
								<label for='".str_replace('_',' ',esc_attr($voc->name))."'>". str_replace('_',' ',esc_attr($voc->name)). "</label>
								</td>";
								}else{
									print "<td style='float:left'>
									<input type='checkbox' disabled readonly class='fieldsToShow proVersion' ".$checked." name='".esc_attr($voc->name)."' value='1'/>
								<label class='proVersion' for='".str_replace('_',' ',esc_attr($voc->name))."'>". str_replace('_',' ',esc_attr($voc->name)). " - PRO VERSION</label>
								</td>";									
								}
								
								array_push($cols,esc_attr($voc->name));					
							}?>
						</tr>
					</table>
					
					
					<table class='wp-list-table widefat fixed table table-bordered fields_checks'>
						<legend>
							<h2>
								<?php esc_html_e('FIELDS TO SHOW', 'procsme');?>
							</h2>
						</legend>
						<tr>
							<td>
								<input type="checkbox" name="check_all2" id="check_all2" ><label for="check_all2"><?php _e('Check All', 'procsme');?></label>
							</td>
						</tr>						
						<?php
						$cols = array("title","description","excerpt","post_name","_variation_description",'_sku','_regular_price','_sale_price','_weight','_stock','_stock_status','_width','_length','_height','_downloadable','_download_limit','_download_expiry','_virtual','_purchase_note','_upsell_ids','_crosssell_ids','_thumbnail_id','_product_image_gallery','_sold_individually','_backorders','_featured','_tax_status','_tax_class');	?>
						
						<tr>
						
						<?php $checked = '';
						foreach( $cols as $col){					
							print "<td style='float:left'>
								<input type='checkbox' class='fieldsToShow'  name='toShow".$col."' value='1'/>
								<label for='".$col."'>". $col. "</label>
								</td>";
						} 
						
						
						
						if(!empty($this->custom_product_fields())){
							foreach($this->custom_product_fields() as $meta){
								echo "<td style='float:left'>
									<input type='checkbox' class='fieldsToShow proVersion' readonly  name='".esc_attr($meta)."' value='1'/>
									<label class='proVersion' for='".esc_attr($meta)."'>". esc_html($meta). "- PRO VERSION</label>
									</td>";
							}
						}
						
						
						?>
						
						</tr>
					</table>			
							
					<input type='hidden' name='columnsToShow' value='1'  />
					<input type='hidden' id='action' name='action' value='export_process' />
					<?php wp_nonce_field('columnsToShow'); ?>

					<?php submit_button(esc_html__( 'Search', 'procsme' ),'primary','Search'); ?>

				</form>
			
			<div class='resultExport'>
				<?php $this->exportProducts(); ?>
			</div>
		<?php			
		}else print esc_html__("No Products to display...","procsme"); //end of checking for products
	}	
	
	public function exportProducts(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') && $_REQUEST['columnsToShow'] ){
			
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );
		
			$meta_query = array();
			
			
					 $cat_query = array(
						array(
						'taxonomy' => 'product_type',
						'field' => 'name',
						'terms'    => 'simple',
						 ),
					);							
			

			if(!empty($_POST['sku'])){ 
				$this->sku = sanitize_text_field( $_POST['sku'] );
				$sku = array('key'     => '_sku','value'   => $this->sku,'compare' => 'LIKE');
				
				array_push($meta_query,$sku );
			}else $this->sku='';
			
			if(!empty($_POST['keyword']))  $this->keyword = sanitize_text_field( $_POST['keyword'] );
			

			
			if(!empty($_POST['posts_per_page'])){
				$this->posts_per_page = (int)$_POST['posts_per_page'] ;
			}else $this->posts_per_page = '-1';
			
			if(!empty($_POST['offset'])){
				$this->offset = (int)$_POST['offset'];
			}else $this->offset = '-1';
			
			$query = new WP_Query( array(
				'post_type' => 'product',
				's' => $this->keyword,
				'meta_and_tax' => TRUE,
				'tax_query'  => $cat_query,			
				'meta_query' => $meta_query,				
				'posts_per_page' => (int)$this->posts_per_page,	
				'offset' => (int)$this->offset,
				) );
				
				
			if ( $query ->have_posts() ){
				
				$query->the_post();
			
				$i=0;
				?>
				<p class='message error'>
					<?php esc_html_e( 'Wait... Download is loading...', 'procsme' );?>
					<b class='totalPosts'  ><?php print esc_html($query->post_count);?></b>					
				</p>

				<?php		
				if($query->post_count <= 500){
					$start=0;
				}else $start=500;
				print " <b class='startPosts'>".esc_html($start)."</b>";
			
			}
			$arrayIDs = array();

			$column_name = array(esc_html__( 'TITLE', 'procsme' ),esc_html__( 'DESCRIPTION', 'procsme' ),esc_html__( 'EXCERPT', 'procsme' ),esc_html__( 'POST NAME', 'procsme' )," ".esc_html__( 'VARIATION DESCRIPTION', 'procsme' )," ".esc_html__( 'SKU', 'procsme' )," ".esc_html__( 'REGULAR PRICE', 'procsme' )," ".esc_html__( 'SALE PRICE', 'procsme' )," ".esc_html__( 'WEIGHT', 'procsme' )," ".esc_html__( 'STOCK', 'procsme' )," ".esc_html__( 'STOCK STATUS', 'procsme' )," ".esc_html__( 'WIDTH', 'procsme' )," ".esc_html__( 'LENGTH', 'procsme' )," ".esc_html__( 'HEIGHT', 'procsme' )," ".esc_html__( 'DOWNLOADABLE', 'procsme' )," ".esc_html__( 'DOWNLOAD LIMIT', 'procsme' )," ".esc_html__( 'DOWNLOAD EXPIRY', 'procsme' )," ".esc_html__( 'VIRTUAL', 'procsme' )," ".esc_html__( 'PURCHASE NOTE', 'procsme' )," ".esc_html__( 'UPSELL IDS', 'procsme' ) ," ".esc_html__( 'CROSSSELL IDS', 'procsme' )," ".esc_html__( 'THUMBNAIL ID', 'procsme' )," ".esc_html__( 'PRODUCT IMAGE GALLERY', 'procsme' )," ".esc_html__( 'SOLD INDIVIDUALLY', 'procsme' )," ".esc_html__( 'BACKORDERS', 'procsme' )," ".esc_html__( 'FEATURED', 'procsme' ) ," ".esc_html__( 'TAX STATUS', 'procsme' )," ".esc_html__( 'TAX CLASS', 'procsme' ) );

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
							$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );								
							
							foreach($taxonomy_objects as $tax){									
								if(!strstr($tax->name,'pa_')){
									if(isset($_REQUEST["toShow".$tax->name]) ){ //show columns according to what is checked
										array_push($column_name,esc_html($tax->name) );
									}
								}	
							}
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
			
		}//check request						
	}


	public function export_process(){

		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator') ){
					
			check_admin_referer( 'columnsToShow' );
			check_ajax_referer( 'columnsToShow' );
			

			$meta_query = array();
			
			
					 $cat_query = array(
						array(
						'taxonomy' => 'product_type',
						'field' => 'name',
						'terms'    => 'simple',
						 ),
					);	
			if(!empty($_POST['sku'])){ 
				$sku = sanitize_text_field( $_POST['sku'] );
				$sku = array('key'     => '_sku','value'   => $sku,'compare' => '=');
				
				array_push($meta_query,$sku );
			}else $sku='';
			if(!empty($_POST['keyword'])) {
				$keyword = sanitize_text_field( $_POST['keyword'] );
			}

			if(!empty($_POST['posts_per_page'])){
				$posts_per_page = (int)$_POST['posts_per_page'];
			}else $posts_per_page = '-1';
				
			if(!empty($_POST['offset'])){
				$offset = (int)$_POST['offset'] ;
			}else $offset = '0';
				
			$query = new WP_Query( 
				array(
					'post_type' => 'product',
					's' => $keyword,			
					'posts_per_page' => (int)$posts_per_page,	
					'offset' => (int)$offset,
					'meta_and_tax' => TRUE,
					'tax_query'  => $cat_query,			
					'meta_query' => $meta_query,					
				) 
			);				
				
				
				
			if ( $query ->have_posts() ){
						
				$post_meta = array('_sku','_regular_price','_sale_price','_weight','_stock','_stock_status','_width','_length','_height','_downloadable','_download_limit','_download_expiry','_virtual','_purchase_note','_upsell_ids','_crosssell_ids','_thumbnail_id','_product_image_gallery','_sold_individually','_backorders','_featured','_tax_status','_tax_class');	
				
				while ( $query->have_posts() ){
									
					$query->the_post();
					global $product;
					global $woocommerce;
					
					
					 ?>						
						<tr>
							<td><?php print esc_attr(get_the_ID()) ;?></td>					
							<?php if($_REQUEST["toShowtitle"] ){ ?>								 
								<td><?php esc_html(the_title());?></td>
							<?php } ?>					
							<?php if(isset($_REQUEST["toShowdescription"]) ){ ?>									
								<td>
									<?php print esc_html(get_post_field('post_content', get_the_ID()) )  ; ?>
								</td>
							<?php } ?>
							<?php if(isset($_REQUEST["toShowexcerpt"]) ){ ?>								 
								<td>
									<?php print esc_html(get_post_field('post_excerpt', get_the_ID()) )  ; ?>							
								</td>
							<?php } ?>	
							<?php if(isset($_REQUEST["toShowpost_name"]) ){ ?>								 
									<td>
										<?php print  esc_html($post->post_name) ;?>
										<?php //print esc_html(get_post_field('post_name', get_the_ID()) )  ; ?>
									</td>
							<?php } ?>						
							<?php if(isset($_REQUEST["toShow_variation_description"]) ){ ?>
								<td></td><?php 									
							} ?>
										
							<?php foreach($post_meta as $meta){
								if(isset($_REQUEST["toShow".$meta]) ){
									if($meta !=  '_upsell_ids' && $meta !=  '_crosssell_ids' && $meta != '_thumbnail_id' && $meta != '_product_image_gallery' && $meta !== '_tax_class'){ ?>
										<td><?php print esc_html(get_post_meta(get_the_ID(), $meta, true ) ); ?></td>
									<?php }elseif($meta === '_upsell_ids' || $meta === '_crosssell_ids'){ 
											$ids   =   get_post_meta( get_the_ID(), $meta, true );
											if(!empty($ids)) $ids    =   implode(",",$ids);										
											?>
											<td><?php print esc_html($ids); ?></td>
									<?php }elseif($meta === '_thumbnail_id'){
										$imageUrl = wp_get_attachment_url( get_post_meta(get_the_ID(), $meta, true ) ); ?>
										<td><?php print esc_html($imageUrl); ?></td>
									<?php }elseif($meta === '_product_image_gallery'){
											$gallery = array();
											$ids   =  get_post_meta( get_the_ID(), $meta, true );
											$ids = explode(",",$ids);
											foreach($ids as $id){
												$id = wp_get_attachment_url( $id); 
												array_push($gallery,$id);
											}
											$gallery = implode(",",$gallery);
											?>
											<td><?php print esc_html($gallery); ?></td>
									<?php }elseif($meta === '_tax_class'){
										 ?>
										<td><?php print esc_html( str_replace("-rate","",get_post_meta(get_the_ID(), $meta, true ) ) ); ?></td>
									<?php } ?>
								<?php }
							} 
							
							$terms = get_post_taxonomies( get_the_ID());
							foreach($terms as $tax){									
								$term = get_the_terms( get_the_ID(), $tax );
									if(isset($_REQUEST["toShow".$tax]) ){//show columns according to what is checked
										if(!strstr($tax,'pa_')){
											$countTerms = count($term);
											$i=0;
											print "<td>";
											$myterm = array();
											while($i<$countTerms){
												if(!empty($term[$i]->name)){
													array_push($myterm, $term[$i]->name);
												}
												$i++;
											}
											$terms = implode(',',$myterm);
											print esc_html($terms);
											print "</td>";
										}
									}
							}
							
							
							
						print "</tr>";		
					
				}//end while

				die;												
			}else print "<p class='warning' >".esc_html__('No Product Found', 'procsme')."</p>";//end if						
		}//check request						
	}	
	
	
	public function importProductsDisplay(){?>
		<h2>
		<?php esc_html_e("IMPORT / UPDATE PRODUCTS","procsme");?>
		</h2>	
			
		<p>
			<?php esc_html_e("Download the sample excel file, save it and add your Simple products. You can add your Custom Columns. Upload it using the form below.","procsme");?> <br/>
			<a href='<?php echo plugins_url( '../example_excel/import-simple-products.xlsx', __FILE__ ); ?>'>
				<?php esc_html_e("Simple Products Sample","procsme");?>
			</a>			
		</p>
							
		<p>
			<span class='button button-secondary warning imageHandling' >
				<i class='fa fa-exclamation'></i> <?php esc_html_e("IMAGE HANDLING","procsme");?>
			</span>
			<span class='button button-secondary warning productHandling' >  
				<?php esc_html_e("SIMPLE vs VARIABLE Products","procsme");?>
			</span>		
			<span class='button button-secondary warning updateHandling' >  
				<?php esc_html_e("UPDATE Info","procsme");?>
			</span>					
		</p>

		<div class='imageinfo'>
			<p>  
				<?php esc_html_e("You want to import Product Images? get ","procsme");?> <a target='_blank' href='<?php print esc_url($this->proUrl); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>
			</p>			
		</div>
		<div class='productInfo'>
			<p>
				<?php esc_html_e("Importing Products means 3 options:","procsme");?>
			</p>
			
			<p>
				1) <?php esc_html_e("Simple Products with NO Atrributes  - 1 product (defined by unique title) per Excel row. Only one needed in the excel file.","procsme");?>
			</p>
			<p>  
				2) <?php esc_html_e("Simple Products with Atrributes (eg color) NO Variations - 1 Row per product Attribute (title of product is the same)  for Each Attribute.get ","procsme");?> <b><a target='_blank' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a></b> 
			</p>	
			<p>  
				3) <?php esc_html_e("You want to import Variable Products? get ","procsme");?> <a target='_blank' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>
			</p>
			<p>  
				4) <?php _e("Variable Products with Attributes (eg color,size)  comma separated from Only 1 excel row. This means the variations share the same meta data such as prices, sku, stock, dimensions, images etc . Get ","procsmepro");?> <a target='_blank' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>
			</p>			
		</div>
		
		<div class='updateinfo'>
			<p>
				<b>1)<?php esc_html_e("UPDATING VIA EXPORT SCREEN EXCEL","procsme");?></b>
			</p>
		    <p>
				<?php esc_html_e("Export from export screen selecting ID, SKU and/or TITLE. ","procsme");?>
			</p>
		    <p>
				<i>
					<?php esc_html_e("You need to click 'update only' and select the identifier for update(sku,title or ID).","procsme");?>
				</i>
			</p>		
		</div>
		  
		<div>			
			<form method="post" id='product_import' enctype="multipart/form-data" action= "<?php echo admin_url( 'admin.php?page=store-migration-woocommerce&tab=main' ); ?>">

				<table class="form-table">
						<tr valign="top">
							<td>
								<?php wp_nonce_field('excel_upload'); ?>
								<input type="hidden"   name="importProducts" value="1" />
								<div class="uploader">
									<img src="" class='userSelected'/>
									<input type="file"  required name="file" class="procsmeFile"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
								</div>						
							</td>
						</tr>
				</table>
				<?php submit_button( esc_html__( 'Upload', 'procsme' ) ,'primary','upload'); ?>
			</form>	
			<div class='result'>
				<?php  $this->importProducts(); ?>
			</div>					
		</div>
	<?php
	}



	
	public function importProducts(){
		
		if($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('administrator')  && isset($_POST['importProducts']) ){
		
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
					
					$post_meta=array('ID','post_title','post_author','post_name','post_status','post_content','post_excerpt','_sku','_weight','_regular_price','_sale_price','_stock','_length','_width','_height','_variation_description','_downloadable','download_name','download_file','_download_limit','_download_expiry','_virtual','_purchase_note','_upsell_ids','_crosssell_ids','_sold_individually','_backorders','_featured','_tax_status', '_tax_class');
					?>
					<span class='thisNum'></span>
					<div class='ajaxResponse'></div>
						
					<div class='woo-form-wrapper'>
						<form method='POST' id ='product_process' action= "<?php print admin_url( 'admin.php?page=store-migration-woocommerce' );?>">

							<p>
								<?php esc_html_e( 'DATA MAPPING: Drag and drop excel columns on the right to product properties on the left.', 'procsme' ); ?>
							</p>
							<p class='proVersion'>
								<i ><b > <?php esc_html_e( 'Auto Match Columns - PRO', 'procsme' ) ?> <input type='checkbox' disabled  /> </b></i>
							</p>
							
							
							<div class='columns3 border'>	

								<p class=' update_onlyField'>
									<input type='checkbox' name='update_only' id='update_only' value='yes'  /> <b><i> <?php esc_html_e( 'UPDATE ONLY', 'procsme' ) ?></i> </b>
									<select name='updateby' id='productupdateBy'>
										<option ><?php esc_html_e( 'update by...', 'procsme' ) ?></option>
										<option value='id'><?php esc_html_e( 'ID', 'procsme' ) ?></option>
										<option readonly disabled><?php esc_html_e( 'SKU - PRO VERSION', 'procsme' ) ?></option>
										<option value='title'><?php esc_html_e( 'TITLE', 'procsme' ) ?></option>
									</select>									
									
								</p>

									
								
								
								<p class='hideOnUpdateById'>
									<input type='checkbox' name='add_always_new' id='add_always_new' value='yes'  /> <b> <?php esc_html_e( 'Always add new Products even if title is the same / will not work for variable products', 'procsme' ) ?> </b>
								</p>							

								<p class=''>
									<input type='checkbox' name='selectparent' id='selectparent' value='yes'  /> <b> <?php esc_html_e( 'Select Parent Categories as well ', 'procsme' ) ?></b>
								</p>								

								<p class='hideOnUpdateById'>
									<input type='checkbox' disabled readonly  /> <b> <?php esc_html_e( 'Create only Simple Product with Attributes, if any  ', 'procsme' ) ?><strong><?php esc_html_e( 'ONLY,', 'procsme' ) ?> <?php esc_html_e( 'Not Variations ', 'procsme' ) ?> <a target='_blank' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>
										</strong>									
									</b>
									
								</p>
								<p class='hideOnUpdateById'>
									<input type='checkbox' disabled readonly  /> <b> <?php esc_html_e( 'Set Last Variation as Default (Variable Products)', 'procsme' ) ?> 	<a target='_blank' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>															
									</b>
								</p>
								<p class='hideOnUpdateById' title='<?php esc_html_e( 'used in cases for adding multiple attribute combinations without having double rows for same attribute value that has been already imported', 'procsme' ) ?>' >
									<input type='checkbox' disabled readonly /> <b> <?php esc_html_e( 'Assign Attribute to product even if value is null', 'procsme' ) ?> <a target='_blank' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>
																											
									</b>
								</p>

								<p class='hideOnUpdateById' title='<?php esc_html_e( 'used for variable products cases where all variations share same  prices, dimensions, descriptions, shipping, tax, images and in general data', 'procsme' ) ?>'>
									<input type='checkbox' disabled readonly  /> <b> <?php esc_html_e( 'Add Variable product from 1 row with attrributes comma separated ', 'procsme' ) ?> <a target='_blank' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a>
																		
									</b>
								</p>

							
								<h2>
									<?php esc_html_e( 'EXCEL COLUMNS', 'procsme' ) ?>
								</h2>
								
								<p>
									<?php foreach ($cellIterator as $cell) {
										echo "<input type='button' class='draggable' style='min-width:100px;background:#000;color:#fff' key ='".sanitize_text_field($cell->getColumn())."' value='". sanitize_text_field($cell->getValue()) ."' />  <br/>";
									} ?>				
								</p>
								
		   
							   <input type='hidden' name='importfinalupload' value='<?php print esc_attr( $total );?>' />
							   <input type='hidden' name='start' value='2' />
							   <input type='hidden' name='action' value='import_process' />
							   <?php 
									wp_nonce_field( 'excel_process','secNonce' );
									submit_button( esc_html__( 'Upload', 'procsme' ),'primary','check' ); 
								?>								
								
							</div>
						
							<div class='columns3'>
					
								<h2>
									<?php esc_html_e( 'PRODUCT FIELDS', 'procsme' ) ?>
								</h2>

							
								<?php foreach($post_meta as $meta){
									if($meta === 'ID'){
										echo "<p>
											<b>".esc_html__( 'ID (for update purpose only) ', 'procsme' )." </b> <input type='text' name='ID' required readonly class='droppable' placeholder='Drop here column' />
										</p>";									
									}elseif($meta === 'post_name'){
										echo "<p class=''>
											<b>".esc_html__( 'URL/SLUG ', 'procsme' )." </b> <input type='text' name='post_name' required readonly class='droppable' placeholder='Drop here column' />
										</p>";									
									}elseif($meta === 'post_title' ){
										echo "<p class=''>
											<b>".strtoupper(str_replace('_',' ',esc_attr($meta)))."</b> <input type='text' name='".esc_attr($meta)."' required readonly class='droppable' placeholder='Drop here column' />
										</p>";									
									}else{
										echo "<p>
										<b>".strtoupper(str_replace('_',' ',esc_attr($meta)))."</b> <input type='text'  name='".esc_attr($meta)."' required readonly class='droppable' placeholder='Drop here column'  />
									</p>";
									}
								}
								?>

								<p class='proVersion'>
									<b><?php esc_html_e( 'IMAGE - PRO', 'procsme' ) ?></b>
								</p>
							
								<p class='proVersion'>
									<b><?php esc_html_e( 'IMAGE GALLERY - PRO', 'procsme' ) ?></b>
								</p>

							</div>
							
							
							<div class='columns3'>
																
									<h2 class='proVersion' >
										<?php esc_html_e( 'CUSTOM FIELDS - PRO VERSION', 'procsme' ) ?>
									</h2>
									<?php 
									if(!empty( $this->custom_product_fields()) ){
										foreach($this->custom_product_fields() as $meta){
											echo "<p>
													<b>".strtoupper(str_replace('_',' ',esc_html($meta)))."</b> 
											</p>";
										}
									}else print "<i>". esc_html__('You dont have any custom fields associated to any product. If there are, please Just create a test product from WordPress backend -add just title and press Publish-. After this action, you will be able to view here any custom field, from Plugins like ACF. In the end you can then delete this test product.',"procsme") ."</i>"; 
									?>
									
								
								<h2>
									<?php esc_html_e( 'PRODUCT TAXONOMIES - ATTRIBUTES', 'procsme' ) ?>
								</h2>
							
								<?php 
								$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );			
								foreach( $taxonomy_objects as $voc){
									if($voc->name !== 'product_type'   ){
										if(!strstr($voc->name, 'pa_')){	
											if($voc->name === 'product_cat' || $voc->name === 'product_tag'  ){
												echo "<p><b>". strtoupper(str_replace('_',' ',esc_html($voc->name))). "</b> <input type='text' style='min-width:200px' name='".$voc->name."' required readonly class='droppable' placeholder='Drop here column' key /></p>";												
											}else{
												echo "<p><b>". strtoupper(str_replace('_',' ',esc_html($voc->name))). esc_html( '- PRO VERSION', 'procsme' )."</b></p>";												
											}
									
										}else{
											echo "<p class='proVersion'><b>". strtoupper(str_replace('_',' ',esc_html($voc->name))). esc_html( '- PRO VERSION', 'procsme' )."</b> </p>";									
										}
									}
								}
								?>

								
							</div>				
						</form>
					</div>
					
					<?php move_uploaded_file($_FILES["file"]["tmp_name"], plugin_dir_path( __FILE__ ).'importProducts.xlsx');
				
				}else   "<h3>". esc_html__('Invalid File:Please Upload Excel File', 'procsme')."</h3>";				
				
			}
		}
	
	}
	
	public function greeklish($Name){
			$greek   = array('α','ά','Ά','Α','β','Β','γ', 'Γ', 'δ','Δ','ε','έ','Ε','Έ','ζ','Ζ','η','ή','Η','θ','Θ','ι','ί','ϊ','ΐ','Ι','Ί', 'κ','Κ','λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','ό','Ο','Ό','π','Π','ρ','Ρ','σ','ς', 'Σ','τ','Τ','υ','ύ','Υ','Ύ','φ','Φ','χ','Χ','ψ','Ψ','ω','ώ','Ω','Ώ',' ',"'","'",',','.');
			$english = array('a', 'a','A','A','b','B','g','G','d','D','e','e','E','E','z','Z','i','i','I','th','Th', 'i','i','i','i','I','I','k','K','l','L','m','M','n','N','x','X','o','o','O','O','p','P' ,'r','R','s','s','S','t','T','u','u','Y','Y','f','F','ch','Ch','ps','Ps','o','o','O','O','-','-','-','-','-');
			$string  = str_replace($greek, $english, $Name);
			return $Name;
	}

	public function import_process(){
		
		if(isset($_POST['importfinalupload']) && current_user_can('administrator')){
			
			$time_start = microtime(true);

			check_admin_referer( 'excel_process','secNonce' );
			check_ajax_referer( 'excel_process' ,'secNonce');
					
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			
			$filename = plugin_dir_path( __FILE__ ).'importProducts.xlsx';
				
			$objPHPExcel = IOFactory::load($filename);
			
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$data = count($allDataInSheet);  // Here get total count of row in that Excel sheet	
			$images=array();
			$gallery_images = array();
			$finalIdsArray=array();
			$finalVarIdsArray=array();			
			$multiarray= array();
			$idsArray = array();
			
			//parameters for running with ajax - no php timeouts
			$i=sanitize_text_field($_POST['start']);
			$start = $i -1;

			//SANITIZE AND VALIDATE title and description	
			$status = sanitize_text_field($allDataInSheet[$i][$_POST['post_status']]);
			if(!empty($allDataInSheet[$i][$_POST['post_status']])){
				$status = sanitize_text_field($allDataInSheet[$i][$_POST['post_status']]);
			}else $status = 'publish';
				
			$sku = sanitize_text_field($allDataInSheet[$i][$_POST['_sku']]);					
			if ( !$sku && !empty($allDataInSheet[$i][$_POST['_sku']]) ) {
				$sku = '';
			}				
			
			$title = sanitize_text_field($allDataInSheet[$i][$_POST['post_title']]);
			
			$post_id = (int)$allDataInSheet[$i][$_POST['ID']];
			
			if( !empty($_POST['update_only']) && !empty( $_POST['updateby'] ) ){
				if( $_POST['updateby'] == 'title' ) {
					$post_id = post_exists($title);
				}elseif( $_POST['updateby'] == 'id' ) {
					$post_id = (int)$allDataInSheet[$i][$_POST['ID']];	
				}				
			}elseif ($post_id === 0 && empty($_POST['update_only']) ){
				
				if( $sku == '' && $title !=='' ) {				
					$post_id = post_exists($title);			
				}		
			}else{
				$post_id = (int)$allDataInSheet[$i][$_POST['ID']];			
			}
			
			
			$excerpt = wp_specialchars_decode($allDataInSheet[$i][$_POST['post_excerpt']], $quote_style = ENT_QUOTES  );
			$excerpt =  preg_replace('#<script(.*?)>(.*?)</script>#is', '', $excerpt);						
						
			$content = wp_specialchars_decode($allDataInSheet[$i][$_POST['post_content']], $quote_style = ENT_QUOTES  );
			$content =  preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);	
						
			$author = sanitize_text_field($allDataInSheet[$i][$_POST['post_author']]);

			if(!empty($allDataInSheet[$i][$_POST['post_name']])){
				$url = sanitize_title_with_dashes($allDataInSheet[$i][$_POST['post_name']]);
			}


							
			//SANITIZE AND VALIDATE meta data
			$sale_price = sanitize_text_field($allDataInSheet[$i][$_POST['_sale_price']]);					
			if ( !$sale_price  && !empty($allDataInSheet[$i][$_POST['_sale_price']]) ) {
				$sale_price = '';
				if(!empty($sale_price)){
					print esc_html__( 'For Sale price of', 'procsme' ) . " ". esc_html($title). " ". esc_html__( 'you need numbers entered', 'procsme' ).".<br/>";
				}
			}
			$regular_price = sanitize_text_field($allDataInSheet[$i][$_POST['_regular_price']]);
			if ( !$regular_price  && !empty($allDataInSheet[$i][$_POST['_regular_price']])) {
				$regular_price = '';
				if(!empty($regular_price)){
					print esc_html__( 'For Regular price of', 'procsme' ) . " ". esc_html($title). " ". esc_html__( 'you need numbers entered', 'procsme' ).".<br/>";
				}
			}
			

			
			$weight = sanitize_text_field($allDataInSheet[$i][$_POST['_weight']]);					
			if ( !$weight  && !empty($allDataInSheet[$i][$_POST['_weight']]) ) {
				$weight = '';
				if(!empty($weight)){
					print esc_html__( 'For Weight of', 'procsme' ) . " ". esc_html($title). " ". esc_html__( 'you need numbers entered', 'procsme' ).".<br/>";
				}			  
			}	
			
			$stock = sanitize_text_field($allDataInSheet[$i][$_POST['_stock']]);					
			if ( !$stock  && !empty($allDataInSheet[$i][$_POST['_stock']]) ) {
				$stock = '';					  
				if(!empty($stock)){
					print esc_html__( 'For Stock of', 'procsme' ) . " ". esc_html($title). " ". esc_html__( 'you need numbers entered', 'procsme' ).".<br/>";
				}
			}	
			$varDescription = sanitize_text_field($allDataInSheet[$i][$_POST['_variation_description']]);					
			if ( !$varDescription  && !empty($_POST['_variation_description']) ) {
				$varDescription = '';					  
			}					
			$length = (int)$allDataInSheet[$i][$_POST['_length']];					
			if ( !$length  && !empty($allDataInSheet[$i][$_POST['_length']]) ) {
				$length = '';
				if(!empty($weight)){
					print esc_html__( 'For Length of', 'procsme' ) . " ". esc_html($title). " ". esc_html__( 'you need numbers entered', 'procsme' ).".<br/>";
				}			  
			}	
			
			$width = sanitize_text_field($allDataInSheet[$i][$_POST['_width']]);					
			if ( !$width  && !empty($allDataInSheet[$i][$_POST['_width']]) ) {
				$width = '';					  
				if(!empty($width)){
					print esc_html__( 'For Width of', 'procsme' ) . " ".esc_html($title). " ". esc_html__( 'you need numbers entered', 'procsme' ).".<br/>";
				}
			}	
			
			$height = sanitize_text_field($allDataInSheet[$i][$_POST['_height']]);					
			if ( !$height  && !empty($_POST['_height']) ) {
				$height = '';					  
			}

			$downloadable = sanitize_text_field($allDataInSheet[$i][$_POST['_downloadable']]);					
			if ( !$downloadable  && !empty($allDataInSheet[$i][$_POST['_downloadable']]) ) {
				$downloadable = '';					  
			}						

			$downloadable_files = sanitize_text_field($allDataInSheet[$i][$_POST['_downloadable_files']]);					
			if ( !$downloadable_files  && !empty($allDataInSheet[$i][$_POST['_downloadable_files']]) ) {
				$downloadable_files = '';					  
			}
			
			$downdloadArray =array('name'=>sanitize_text_field($allDataInSheet[$i][$_POST['download_name']]), 'file' => sanitize_text_field($allDataInSheet[$i][$_POST['download_file']]) );
			$_file_paths[  $file_path  ] = $downdloadArray;

			$downloadlimit = sanitize_text_field($allDataInSheet[$i][$_POST['_download_limit']]);				
			if ( !$downloadlimit  && !empty($allDataInSheet[$i][$_POST['_download_limit']]) ) {
				$downloadlimit = '';					  
			}						

			$downloadexpiry = sanitize_text_field($allDataInSheet[$i][$_POST['_download_expiry']]);				
			if ( !$downloadlimit  && !empty($allDataInSheet[$i][$_POST['_download_expiry']]) ) {
				$downloadexpiry = '';					  
			}
			
			$virtual = sanitize_text_field($allDataInSheet[$i][$_POST['_virtual']]);		
			if ( !$virtual  && !empty($allDataInSheet[$i][$_POST['_virtual']]) ) {
				$virtual = '';					  
			}
			
				

			if (!empty($allDataInSheet[$i][$_POST['_upsell_ids']]) ) {
				$upsell = sanitize_text_field($allDataInSheet[$i][$_POST['_upsell_ids']]);	
				$upsell = explode(",",$upsell);
			}
			
				
			if (!empty($allDataInSheet[$i][$_POST['_crosssell_ids']]) ) {
				$crosssell = sanitize_text_field($allDataInSheet[$i][$_POST['_crosssell_ids']]);
				$crosssell = explode(",",$crosssell);			
			}

			if (!empty($allDataInSheet[$i][$_POST['_crosssell_ids']]) ) {
				$crosssell = sanitize_text_field($allDataInSheet[$i][$_POST['_crosssell_ids']]);
				$crosssell = explode(",",$crosssell);			
			} 
			
			$backorders = sanitize_text_field($allDataInSheet[$i][$_POST['_backorders']]);
			if (!$backorders && !empty($allDataInSheet[$i][$_POST['_backorders']]) ) {
				$backorders = '';	
			} 		
			$sold_individually = sanitize_text_field($allDataInSheet[$i][$_POST['_sold_individually']]);
			if (!$sold_individually && !empty($allDataInSheet[$i][$_POST['_sold_individually']]) ) {
				$sold_individually = '';	
			} 
			$featured = sanitize_text_field($allDataInSheet[$i][$_POST['_featured']]);
			if (!$featured && !empty($allDataInSheet[$i][$_POST['_featured']]) ) {
				$featured = '';	
			} 
			
			$purchaseNote = sanitize_text_field($allDataInSheet[$i][$_POST['_purchase_note']]);		
			if ( !$purchaseNote  && !empty($allDataInSheet[$i][$_POST['_purchase_note']]) ) {
				$purchaseNote = '';					  
			}

			$taxstatus = sanitize_text_field($allDataInSheet[$i][$_POST['_tax_status']]);
			if (!$taxstatus && !empty($allDataInSheet[$i][$_POST['_tax_status']]) ) {
				$taxstatus = '';	
			
			}
			$taxrate = sanitize_text_field($allDataInSheet[$i][$_POST['_tax_class']]); 		
			if (!$taxrate && !empty($allDataInSheet[$i][$_POST['_tax_class']]) ) {
				$taxrate = '';	
			} 
			
			$x=0;
			$attributes=array();
			$attrVal = array();
			$concat=array();

			if ($post_id === 0 ) { // POST ID NOT USED 

				//check if post exists
				if( ( post_exists($title) === 0 || !empty($_POST['add_always_new']) ) && empty($_POST['update_only']) ){
					$post = array(
						'post_author'   => $author,
						'post_title'   => $title,
						'post_content' => $content,
						'post_status'  => $status,
						'post_excerpt' => $excerpt,
						'post_name'    => $url,
						'post_type'    => 'product'
					);									
								
					//create
					$id = wp_insert_post( $post, $wp_error );
					print "<p class='success'>
								<a href='".esc_url( get_permalink($id))."' target='_blank'>".esc_html($title)."</a> 
								".esc_html__( 'created', 'procsme' )."
							.</p>";
					//add at least zero for price in case prices are not configured so we can later get the product on export based on WooCommerce product class
					update_post_meta( $id, '_regular_price', 0 );
					update_post_meta( $id, '_price', 0 );				
					if(in_array($id,$idsArray)){							
					}else array_push($idsArray,$id);
					
				}else{
					$id = post_exists($title);
					$pid = wp_get_post_parent_id( $id );
					
					if(in_array($id,$idsArray)){
						//do nothing
					}else array_push($idsArray,$id);				
					
										
					if($content !== '' ){
						if (get_post_type( $id)==='product'){	
							$post = array(
								'ID' 		   => $id,
								'post_author'   => $author,
								'post_title'   => $title,
								'post_content' => $content,
								'post_status'  => $status,
								'post_excerpt' => $excerpt,
								'post_name'    => $url,
							);									
						}else{
							$post = array(
								'ID' 		   => $id,
								'post_author'   => $author,
								'post_title'   => $title,
								'post_status'  => $status,
								'post_excerpt' => $excerpt,
							);	
						}
						wp_update_post($post, $wp_error );
					}
					
					print "<p class='warning'><a href='".esc_url( get_permalink($id))."' target='_blank'>".esc_html($title)."</a> ".esc_html__( 'already exists. Updated', 'procsme' ).".</p>";							
				}
			}else{
				
				if(FALSE === get_post_status( $post_id )){
					print "<p class='warning'>".esc_html__($post_id)."</a> ".esc_html__( 'Does not exist. You need to import based on titles', 'procsme' ).".</p>";
					
				}else{
								
				if (get_post_type( $post_id )=== 'product_variation'){
					
					print "<p class='warning'><a href='".esc_url( get_permalink($pid))."' target='_blank'>".esc_html($pid)."</a> ".esc_html__( 'already exists. ', 'procsme' ).".</p>";	
										
					
				}else{

					if( !empty($_POST['update_only'] && $_POST['updateBy']!=='title' && $title !=='' )){
						if($content !='' ){
							$post = array(
								'ID' 		   => $post_id,
								'post_author'   => $author,
								'post_content' => $content,
								'post_title'   => $title,
								'post_status'  => $status,
								'post_excerpt' => $excerpt,
								'post_name'    => $url,
							);									
						}else{
							$post = array(
								'ID' 		   => $post_id,
								'post_author'   => $author,
								'post_status'  => $status,
								'post_title'   => $title,
								'post_excerpt' => $excerpt,
								'post_name'    => $url,
							);	
						}
					}else{
						if($content !='' ){
							$post = array(
								'ID' 		   => $post_id,
								'post_author'   => $author,
								'post_content' => $content,
								'post_status'  => $status,
								'post_excerpt' => $excerpt,
								'post_name'    => $url,
							);									
						}else{
							$post = array(
								'ID' 		   => $post_id,
								'post_author'   => $author,
								'post_status'  => $status,
								'post_excerpt' => $excerpt,
								'post_name'    => $url,
							);	
						}						
					}

					wp_update_post($post, $wp_error );
					print "<p class='warning'><a href='".esc_url( get_permalink($post_id))."' target='_blank'>".esc_html($post_id)."</a> ".esc_html__( 'already exists. Updated', 'procsme' ).".</p>";
					
					$id = $post_id;
					
				}
				
				}
			}
			
				//UPDATE POST META	

				if (get_post_type( $id)==='product_variation'){	
				
				}else{
					
					if($sku!=='')update_post_meta( $id, '_sku', $sku );
					if($weight!=='')update_post_meta( $id, '_weight',$weight );
					if($regular_price!=='')update_post_meta( $id, '_regular_price', $regular_price );
					if($sale_price!=='')update_post_meta( $id, '_sale_price', $sale_price );
					if($stock!=='')update_post_meta( $id, '_stock', $stock );
					update_post_meta( $id, '_visibility', 'visible' );
					
					if($sale_price!==''){
						update_post_meta( $id, '_price', $sale_price );	
					}elseif($regular_price!==''){
						update_post_meta( $id, '_price', $regular_price );
					}
					
					if( $stock !==''){
						if($stock==='0' ){
							update_post_meta( $id, '_stock_status', 'outofstock');
						}else update_post_meta( $id, '_stock_status', 'instock');
						update_post_meta( $id, '_manage_stock', 'yes');
					}else update_post_meta( $id, '_stock_status', 'instock');
					if($length!=='')update_post_meta( $id, '_length', $length );
					if($width!=='')update_post_meta( $id, '_width', $width );
					if($height!=='')update_post_meta( $id, '_height', $height );									
					if($downloadable!=='')update_post_meta( $id, '_downloadable', $downloadable );
					if($downloadlimit!=='')update_post_meta( $id, '_download_limit', $downloadlimit );
					if($downloadexpiry!=='')update_post_meta( $id, '_download_expiry', $downloadexpiry );
					if($virtual!=='')update_post_meta( $id, '_virtual', $virtual );
					if($downdloadArray!=='')update_post_meta( $id, '_downloadable_files', $_file_paths); 	
					if($taxstatus!=='')update_post_meta( $id, '_tax_status', $taxstatus );
					if($taxrate!=='')update_post_meta( $id, '_tax_class', $taxrate.'-rate' );
		
					if(isset($upsell)){
						delete_post_meta( $id, '_upsell_ids' );
						update_post_meta( $id, '_upsell_ids', $upsell );			
					}
					if(isset($crosssell)){
						delete_post_meta( $id, '_crosssell_ids' );
						update_post_meta( $id, '_crosssell_ids', $crosssell );
					}
					if($purchaseNote!=='')update_post_meta( $id, '_purchase_note', $purchaseNote );	
					if($featured!=='' && $featured ==='yes'){
						update_post_meta( $id, '_featured', $featured);
						wp_set_object_terms ( $id,'featured','product_visibility');
					}				
					if($backorders!=='')update_post_meta( $id, '_backorders', $backorders );
					if($sold_individually!=='')update_post_meta( $id, '_sold_individually', $sold_individually);				
				}
				

								
				wc_delete_product_transients( $id );						
				//AFTER UPDATING POST META CHECK AGAIN FOR THE REST ELEMENTS TO UPDATE WITH CORRECT ID			
				if ($post_id === 0 ) { // POST ID NOT USED
					//do nothing
				}else{	
					if(FALSE === get_post_status( $post_id )){			
					}else{
						if( isset($pid) ) $id = $pid;
					}
				}
				

				$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );		
				foreach( $taxonomy_objects as $voc){
					if(isset($allDataInSheet[$i][$_POST[$voc->name]])){
						
							$taxToImport =  explode(',',sanitize_text_field($allDataInSheet[$i][$_POST[$voc->name]]));
							foreach($taxToImport as $taxonomy){
								wp_set_object_terms( $id,$taxonomy,sanitize_text_field($voc->name),true); //true is critical to append the values
												
								// GET ALL ASSIGNED TERMS AND ADD PARENT FOR PRODUCT_CAT TAXONOMY!!! 
								
								if($_POST['selectparent']){
									$terms = wp_get_post_terms($id, $voc->name );
									foreach($terms as $term){
										while($term->parent != 0 && !has_term( $term->parent, sanitize_text_field($voc->name), $post )){
											// move upward until we get to 0 level terms
											wp_set_object_terms($id, array($term->parent), sanitize_text_field($voc->name), true);								
											$term = get_term($term->parent, esc_attr($voc->name));
										}
									}								
								} 
											
							}
												
					}
				}// end for each taxonomy

			$product = wc_get_product( $id );	
			$product->set_stock_quantity(  get_post_meta($id,"_stock",true) );
			$product->set_stock_status( 'instock' );
			if( get_post_meta($id,"_stock",true) =='0') $product->set_stock_status( 'outofstock' );
			$product->set_price(  get_post_meta($id,"_price",true) );
			$product->set_sale_price(  get_post_meta($id,"_sale_price",true) );
			$product->set_regular_price(  get_post_meta($id,"_regular_price",true) );
			$product->save();		
					
			if($i === $_REQUEST['importfinalupload']){						
				$tota = $_REQUEST['importfinalupload']-1;
				print "<div class='importMessageSussess'><h2>".esc_html($i)." / ".esc_html($_REQUEST['importfinalupload'])." ".esc_html__('- JOB DONE!', 'procsme'). " <a href='".esc_url(admin_url( 'edit.php?post_type=product' ))."' target='_blank'><i class='fa fa-eye'></i> ".esc_html__('GO VIEW YOUR PRODUCTS!', 'procsme')."</a></h2></div>";
					
				unlink($filename);
			}else{
				
				print "<div class='importMessage'>
							<h2>".esc_html($i)." / ".esc_html($_REQUEST['importfinalupload'])." ".esc_html__('Please dont close this page... Loading...', 'procsme')."</h2>
								<p>
									<img  src='".esc_url(plugins_url( '../images/loading.gif', __FILE__ ))."' />
								</p>
						</div>";
			}
			die;										
		}
	}	
	
	
}
	