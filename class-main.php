<?php
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require plugin_dir_path( __FILE__ ) .'/Classes/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
 
 class StoreMigrationWooCommerceInit{
	
	public $tab;
	public $activeTab;
	public $importUrl = 'importUrl';
	public $importFrequency = 'importFrequency';
	public $autoimport	='autoimport';
	public $importTime	='importTime';
	public $numberOfRows=1;
	public $keyword='';
	public $posts_per_page='';
	public $sale_price='';
	public $regular_price='';
	public $price_selector='';
	public $sale_price_selector='';
	public $sku='';
	public $offset='';
	public $productCustomFields	='productCustomFields';
	
	public function adminHeader(){
			
		print "<h1>";
			if( !isset( $_GET['tab'] ) || $this->activeTab == 'intro' ){				
			}else echo "<a class='arrows' href='?page=".esc_html( $this->slug )."'><i class='arrows fa fa-arrow-left'></i></a>";		
		print $this->name."</h1>";
	}

	public function adminTabs(){
		
		
		
			$this->tab = array( 'importProducts'=>'Products','importCategories'=>'Import Terms - PRO','customers'=>'Customers','reviews'=>'Reviews - PRO','coupons'=>'Coupons','orders'=>'Orders','importSubscriptions'=>'Import Subscriptions - PRO','faq' => 'Faq','goPro' => 'Buy PRO');
			
			if( isset( $_GET['tab'] ) ){
				$this->activeTab = esc_html( $_GET['tab'] ) ;
			}else $this->activeTab = '';
			echo '<h2 class="nav-tab-wrapper" >';
			foreach( $this->tab as $tab => $name ){
				$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
				
				if( $tab == 'importCategories' ||  $tab == 'reviews' ||  $tab == 'importSubscriptions' ){
					echo "<a class='proVersion nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}elseif( $tab == 'goPro' ){
					echo "<a class='proVersionBuy nav-tab". esc_html( $class )." contant' target='_blank' href='".esc_html( $this->proUrl )."'>".esc_html( $name )."</a>";
				}else echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				
			}?>
			<?php
			echo '</h2>';	
			

			
	}
	
	public function adminFaq(){
		?>
					<div id="accordion">
						
						<h3><?php esc_html_e('Migration Process Sequence','procsme'); ?></h3>
						
						<div>
							<p><?php esc_html_e('Prerequisite for creating an order is products import.','procsme'); ?></p>
							<p><?php esc_html_e('Example:','procsme'); ?></p>
							 <ol>
								<li><?php esc_html_e('Import Products','procsme'); ?></li>
								<li class='proVersion'><?php esc_html_e('Import Product Reviews - pro version','procsme'); ?></li>	
								<li><?php esc_html_e('Import Coupons - optional','procsme'); ?></li>
								<li><?php esc_html_e('Import Customers  - optional','procsme'); ?></li>
								<li><?php esc_html_e('Import Orders - assign to already created customer or guest','procsme'); ?></li>								
							 </ol>
							
						</div>

						<h3><?php esc_html_e('Should customers be added before orders?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('This is optional. You either:','procsme'); ?></p>
								 <ol>
									<li><?php esc_html_e('Import Customers  first, then the order','procsme'); ?></li>
									<li class='proVersion'><?php esc_html_e('PRO VERSION - Import directly the Orders and either create new customer while creating the Order or just check as guest','procsme'); ?></li>								
								 </ol>								

						</div>	
						<h3><?php esc_html_e('Am I required to first import products?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('You need to import products before trying to add any orders - during order import, product will not be created if not exist','procsme'); ?></p>
							

						</div>
						<h3><?php esc_html_e('How is Tax calculated in Orders?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('You can create the tax class, the edit existing products defining the Tax Class and Status, or while importing products from the excel file, define tax status and class for each imported product','procsme'); ?></p>
								<p><?php esc_html_e('Then, while importing Order tax will be calculated automatically','procsme'); ?></p>
							

						</div>	
						<h3><?php esc_html_e('How is Shipping added in Orders?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('You will add a shipping method ','procsme'); ?></p>
								<p><?php esc_html_e('Moreover you will define in the excel file the total shipping cost','procsme'); ?></p>
							

						</div>	

						<h3><?php esc_html_e('How to add Product Reviews?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('This feature is available in the ','procsme'); ?><a target='_blank' href='<?php print esc_url($this->proUrl); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a></p>
							
						</div>	

						<h3><?php esc_html_e('How to import Variable Products?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('This feature is available in the ','procsme'); ?><a target='_blank' href='<?php print esc_url($this->proUrl); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a></p>
							
						</div>	

						<h3><?php esc_html_e('How to import Orders with Variable Products?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('This feature is available in the ','procsme'); ?><a target='_blank' href='<?php print esc_url($this->proUrl); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a></p>
							
						</div>
						<h3><?php esc_html_e('How to import WooCommerce Subscriptions ?','procsme'); ?></h3>
						<div>
								<p><?php esc_html_e('This feature is available in the ','procsme'); ?><a target='_blank' href='<?php print esc_url($this->proUrl); ?>'><?php esc_html_e('PRO Version','procsme'); ?></a></p>
							
						</div>						
					</div>
		<?php
	
	}
	
	public function adminSettings(){
		
			$products = new StoreMigrationWooCommerce_Products;
			$users = new StoreMigrationWooCommerce_Customers;
			$orders = new StoreMigrationWooCommerce_Orders;
			$coupons = new StoreMigrationWooCommerce_Coupons;				
			
		
			$this->adminTabs();	
			if( isset( $_GET['tab'] ) ){
				$this->activeTab = $_GET['tab'] ;
			}else $this->activeTab = 'intro';			
			
			
			if( !isset( $_GET['tab'] ) || $this->activeTab == 'intro' ){
				?>
				<div class='procsmeIntro'>
				<h2><?php print esc_html("migration process","procsme");?></h2>
					
					<div class='flex'>
						<div><span class='sequence'>A)</span></div>
						<div><span class='sequence'>B)</span></div>
						<div><span class='sequence'>C)</span></div>
					</div>
				
					<div class='flex'>
						<div class=''>
							
							<div class='flex'>
								
								<a class='circular' href='?page=<?php print esc_html( $this->slug )."&tab=".esc_html( "importProducts","procsme" );?>'><?php print esc_html(  "products","procsme" );?></a> <i class='arrows fa fa-arrow-right'></i>
							</div>

							<a class='circular customers' href='?page=<?php print esc_html( $this->slug )."&tab=".esc_html( "customers","procsme" );?>'><?php print esc_html(  "customers","procsme" );?></a> 
							
							
						</div>

						<div>
							
							<a class='circular reviews proVersion' href='?page=<?php print esc_html( $this->slug )."&tab=".esc_html( "reviews","procsme" );?>'><?php print esc_html(  "reviews","procsme" );?></a>
							<a class='circular coupons' href='?page=<?php print esc_html( $this->slug )."&tab=".esc_html( "coupons","procsme" );?>'><?php print esc_html(  "coupons","procsme" );?></a>
						</div>
						<div class=''>
							 <i class='arrows fa fa-arrow-right' ></i>
						</div>
						<div>
							
							<a class='circular orders' href='?page=<?php print esc_html( $this->slug )."&tab=".esc_html( "orders","procsme" );?>'><?php print esc_html(  "orders","procsme" );?></a> 
						</div>
						<div>
							
							<a class='circular subscriptions' target='_blank' href='<?php print esc_url($this->proUrl); ?>' ><?php print esc_html(  "subscriptions","procsme" );?></a> 
						</div>						
					</div>
				</div>
				<?php
				
				
			}elseif(  $this->activeTab == 'faq' ) { ?>
				<div class='result'><?php $this->adminFaq(); ?> </div>
				<?php
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'importProducts' ){
				
				$this->tab = array( 'importProducts'=>'Import Products','exportProducts'=>'Export Products');			
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'importProducts';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>';				
				
				$products->importProductsDisplay();
				
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'exportProducts' ){

				$this->tab = array( 'importProducts'=>'Import Products','exportProducts'=>'Export Products');
				
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'exportProducts';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>'; 				
				
				$products->exportProductsDisplay();				
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'importCategories' ){
				?>
				<a target='_blank' class='proUrl' href='<?php print esc_url( $this->proUrl ); ?>'><img  src='<?php echo esc_url( plugins_url( 'images/'.esc_html( $this->slug ).'-pro.png', __FILE__ ) ); ?>'  /></a>
				<?php			
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'customers' ){

				$this->tab = array( 'customers'=>'Import Customers','exportCustomers'=>'Export Customers');
				
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'customers';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>';				
				
				$users->importUsersDisplay();	
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'exportCustomers' ){
				
				
				$this->tab = array( 'customers'=>'Import Customers','exportCustomers'=>'Export Customers');
				
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'exportCustomers';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>';	
				
				$users->exportUsersForm();	
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'exportCoupons' ){
				
				$this->tab = array( 'coupons'=>'Import Coupons','exportCoupons'=>'Export Coupons');
				
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'exportCoupons';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>';				
				
				$coupons->exportCouponsForm();	


			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'reviews' ){
				?>
				<a target='_blank' class='proUrl' href='<?php print esc_url( $this->proUrl ); ?>'><img  src='<?php echo esc_url( plugins_url( 'images/'.esc_html( $this->slug ).'-pro.png', __FILE__ ) ); ?>'  /></a>
				<?php
				
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'orders' ){
				
				$this->tab = array( 'orders'=>'Import Orders','exportOrders'=>'Export Orders');
				
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'orders';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>';				
				
				$orders->importOrdersDisplay();
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'exportOrders' ){
				
				$this->tab = array( 'orders'=>'Import Orders','exportOrders'=>'Export Orders');
				
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'exportOrders';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>';				
				
				$orders->exportOrdersForm();
				
			}elseif( !isset( $_GET['tab'] ) || $this->activeTab == 'coupons' ){
				
				$this->tab = array( 'coupons'=>'Import Coupons','exportCoupons'=>'Export Coupons');
				
				if( isset( $_GET['tab'] ) ){
					$this->activeTab = esc_html( $_GET['tab'] ) ;
				}else $this->activeTab = 'coupons';
				echo '<h2 class="nav-tab-wrapper" >';
				foreach( $this->tab as $tab => $name ){
					$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
					echo "<a class='nav-tab". esc_html( $class )." contant' href='?page=".esc_html( $this->slug )."&tab=".esc_html( $tab )."'>".esc_html( $name )."</a>";
				}?>
				<?php
				echo '</h2>';				
				
				$coupons->importCouponsDisplay();
				
			}
			
			
	}

	
	public function adminFooter(){ ?>	
		<hr>
		<a target='_blank' class='web_logo' href='https://extend-wp.com/wordpress-premium-plugins/'>
			<img  src='<?php echo plugins_url( 'images/extendwp.png', __FILE__ ); ?>' alt='<?php print esc_html("Get more plugins by extendWP","procsme"); ?>' title='<?php print esc_html("Get more plugins by extendWP","procsme"); ?>' />
		</a>
		<?php 
	}



	
	public function adminPanels(){
		add_settings_section($this->plugin."general", "", null, $this->plugin."general-options");		
	}
	
	
	public function adminProcessSettings(){
				
	}
		
	
 }