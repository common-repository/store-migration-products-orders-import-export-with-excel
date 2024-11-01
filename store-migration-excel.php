<?php
/*
 * Plugin Name: Import Orders Export Orders WooCommerce Products Subscriptions with Excel 
 * Description: Migrate -import and/or export - your Products, Reviews, Customers, Orders to WooCommerce with Excel
 * Version: 1.9
 * Author: extendWP
 * Author URI: https://extend-wp.com
 *
 * WC requires at least: 2.2
 * WC tested up to: 8.4
 *
 * Requires PHP: 7.1 
 * License: GPL2
 * Created On: 09-09-2020
 * Updated On: 03-01-2024
 * Text Domain: procsme
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once( plugin_dir_path(__FILE__) ."/class-main.php");
require_once( plugin_dir_path(__FILE__) .'/includes/products.php');
require_once( plugin_dir_path(__FILE__) .'/includes/customers.php');
require_once( plugin_dir_path(__FILE__) .'/includes/orders.php');
require_once( plugin_dir_path(__FILE__) .'/includes/coupons.php');
 
class StoreMigrationWooCommerce extends StoreMigrationWooCommerceInit{
	
		public $plugin = 'eshopMigrationWooCommerce';		
		public $name = 'Store Migration Products Orders Import Export with Excel for WooCommerce';
		public $shortName = 'Store Migration';
		public $slug = 'store-migration-woocommerce';
		public $dashicon = 'dashicons-cart';
		public $proUrl = 'https://extend-wp.com/product/products-reviews-orders-customers-woocommerce-migration-excel';
		public $menuPosition ='50';
		public $localizeBackend;
		public $localizeFrontend;
		public $description = 'Migrate -import and/or export - your Products, Reviews, Customers, Orders to WooCommerce with Excel';
 
		public function __construct() {		
			
			add_action('plugins_loaded', array($this, 'translate') );
			add_action("admin_init", array($this, 'adminPanels') );	

			add_action('admin_enqueue_scripts', array($this, 'BackEndScripts') );
			
			add_action('admin_menu', array($this, 'SettingsPage') );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'Links') );
			
			add_action( 'before_woocommerce_init', function() {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
				}
			} );
			
			add_action("admin_footer", array($this,"proModal" ) );	
			
			
			register_activation_hook( __FILE__,  array($this, 'onActivation') );
			register_deactivation_hook( __FILE__,  array($this, 'onDeactivation') );
										
				
				$products = new StoreMigrationWooCommerce_Products;				
				$users = new StoreMigrationWooCommerce_Customers;									
				$orders = new StoreMigrationWooCommerce_Orders;	
				$coupons = new StoreMigrationWooCommerce_Coupons;	
				

				add_action( 'wp_ajax_nopriv_import_process', array($products,'import_process') );
				add_action( 'wp_ajax_import_process', array($products,'import_process') );				
				
				add_action( 'wp_ajax_export_process',  array($products,'export_process' ) );
				add_action( 'wp_ajax_nopriv_export_process',   array($products,'export_process' ) );

				add_action( 'wp_ajax_exportUsers_process',  array($users,'exportUsers_process' ) );
				add_action( 'wp_ajax_nopriv_exportUsers_process',   array($users,'exportUsers_process' ) );

				add_action( 'wp_ajax_exportCoupons_process',  array($coupons,'exportCoupons_process' ) );
				add_action( 'wp_ajax_nopriv_exportCoupons_process',   array($coupons,'exportCoupons_process' ) );

				add_action( 'wp_ajax_exportOrders_process',  array($orders,'exportOrders_process' ) );
				add_action( 'wp_ajax_nopriv_exportOrders_process',   array($orders,'exportOrders_process' ) );

				
				add_action( 'wp_ajax_importCustomers_process',  array($users,'importCustomers_process' ) );
				add_action( 'wp_ajax_nopriv_importCustomers_process',   array($users,'importCustomers_process' ) );				

				add_action( 'wp_ajax_importCoupons_process',  array($coupons,'importCoupons_process' ) );
				add_action( 'wp_ajax_nopriv_importCoupons_process',   array($coupons,'importCoupons_process' ) );
				
				add_action( 'wp_ajax_importOrders_process',  array($orders,'importOrders_process' ) );
				add_action( 'wp_ajax_nopriv_importOrders_process',   array($orders,'importOrders_process' ) );					
				
				add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', array($this, 'handle_custom_query_var' ), 10, 2 );

				// deactivation survey 

				include( plugin_dir_path(__FILE__) .'/lib/codecabin/plugin-deactivation-survey/deactivate-feedback-form.php');	
				add_filter('codecabin_deactivate_feedback_form_plugins', function($plugins) {

					$plugins[] = (object)array(
						'slug'		=> 'store-migration-products-orders-import-export-with-excel',
						'version'	=> '1.9'
					);

					return $plugins;

				});	
				
				register_activation_hook( __FILE__, array( $this, 'notification_hook' ) );

				add_action( 'admin_notices', array( $this,'notification' ) );
				add_action( 'wp_ajax_nopriv_push_not',array( $this, 'push_not'  ) );
				add_action( 'wp_ajax_push_not', array( $this, 'push_not' ) );	
			
		}

		public function notification(){

			$screen = get_current_screen();
			//var_dump( $screen );
			if ( 'toplevel_page_store-migration-woocommerce'  !== $screen->base )
			return;

			/* Check transient, if available display notice */
			if( get_transient( $this->plugin."_notification" ) ){
				?>
				<div class="updated notice  eshopMigrationWooCommerce_notification">
					<a href="#" class='dismiss' style='float:right;padding:4px' >close</a>
					<h3><?php esc_html_e( "Add your Email below & get ", 'imue' ); ?><strong style='color:#00a32a'>10%</strong><?php esc_html_e( " in our PRO plugins! ", 'imue' ); ?></h3>
					<form method='post' id='eshopMigrationWooCommerce_signup'>
						<p>
						<input required type='email' name='woopei_email' />
						<input required type='hidden' name='product' value='3525' />
						<input type='submit' class='button button-primary' name='submit' value='<?php esc_html_e("Sign up", "imue" ); ?>' />
						<i><?php esc_html_e( "By adding your email you will be able to use your email as coupon to a future purchase at ", 'imue' ); ?><a href='https://extend-wp.com' target='_blank' >extend-wp.com</a></i>
						</p>						
						
					</form>
				</div>
				<?php
			}
		}


		public function push_not(){
			
			delete_transient( $this->plugin."_notification" );
					
		}		
		public function notification_hook() {
			set_transient( $this->plugin."_notification", true );
		}
		
		public function handle_custom_query_var( $query, $query_vars ) {
			if ( isset( $query_vars['s'] ) && ! empty( $query_vars['s'] ) ) {
				$query['s'] = esc_attr( $query_vars['s'] );
			}

			return $query;
		}
		
		public function onActivation( $hook ){
		}
		
		function onDeactivation() {
				
		}
		
	    public function print_scripts() {
	               //if want to print some inline script
	    }		

		public function translate() {
	         load_plugin_textdomain( $this->plugin, false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
	    }
		
		public function BackEndScripts( $hook ){
			
			$screen = get_current_screen();
			//var_dump( $screen );
			//if ( 'toplevel_page_store-migration-woocommerce'  !== $screen->base )
				//return;
	
			wp_enqueue_style( esc_html( $this->plugin )."adminCss", plugins_url( "/css/backend.css?v=223", __FILE__ ) );	
			wp_enqueue_style( esc_html( $this->plugin )."adminCss");	
			
			wp_enqueue_script('jquery');
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_script("jquery-ui-tabs");
			wp_enqueue_style( esc_html( $this->plugin ).'ui_style', plugins_url( '/css/jquery-ui.css', __FILE__  ) );
			wp_enqueue_script( esc_html( $this->plugin ).'xlsx', plugins_url( "/js/xlsx.js", __FILE__ ), array('jquery') , null, true );	
			wp_enqueue_script( esc_html( $this->plugin ).'xlsx');			

			wp_enqueue_script( esc_html( $this->plugin ).'filesaver', plugins_url( "/js/filesaver.js", __FILE__ ), array('jquery') , null, true );	
			wp_enqueue_script( esc_html( $this->plugin ).'filesaver');

			wp_enqueue_script( esc_html( $this->plugin ).'tableexport', plugins_url( "/js/tableexport.js", __FILE__ ), array('jquery') , null, true );	
			wp_enqueue_script( esc_html( $this->plugin ).'tableexport');
			
			if( ! wp_script_is( esc_html( $this->plugin )."_fa", 'enqueued' ) ) {
				wp_enqueue_style( esc_html( $this->plugin )."_fa", plugins_url( '/css/font-awesome.min.css', __FILE__ ));
			}
			
			wp_enqueue_script( esc_html( $this->plugin )."adminJs", plugins_url( "/js/backend.js?v=dvss" , __FILE__ ) , array('jquery','wp-color-picker','jquery-ui-tabs','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-accordion') , null, true);	
			

			$this->localizeBackend = array( 
				'RestRoot' => esc_url_raw( rest_url() ),
				'plugin_url' => plugins_url( '', __FILE__ ),
				'siteUrl'	=>	site_url(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'plugin_wrapper'=> esc_html( $this->plugin ),
				'exportfile' => plugins_url( '/js/tableexport.js', __FILE__ )
			);		
			wp_localize_script( esc_html( $this->plugin )."adminJs", esc_html( $this->plugin ) , $this->localizeBackend );
			wp_enqueue_script( esc_html( $this->plugin )."adminJs" );

		}	
		

		public function SettingsPage(){
			add_menu_page( esc_html__($this->shortName,"procsme") ,esc_html__($this->shortName,"procsme"), 'administrator', esc_html__( $this->slug ),  array($this, 'init'), 'dashicons-upload','50');
			add_submenu_page( 'woocommerce', esc_html__( $this->shortName ), esc_html__( $this->shortName ), 'manage_options', esc_html__( $this->slug ), array($this, 'init') );			
		}		
		
		public function Links($links){
			$mylinks[] =  '<a href="' . admin_url( "admin.php?page=".$this->slug ) . '">'.esc_html__( "Settings", "procsme" ).'</a>';
			$mylinks[] = "<a href='".esc_url( $this->proUrl )."' target='_blank'>".esc_html__( "PRO Version", "procsme" )."</a>";
			return array_merge( $links, $mylinks );			
		}


		
		public function init(){
			print "<div class='".$this->plugin."'>";
				$this->adminHeader();
				$this->adminSettings();
				$this->adminFooter();
			print "</div>";		
		}
		
		public function proModal(){ ?>
			<div id="<?php print esc_html( $this->plugin ).'Modal'; ?>" style='display:none;'>
			  <!-- Modal content -->
			  <div class="modal-content">
				<div class='<?php print esc_html( $this->plugin ); ?>clearfix'><span class="close">&times;</span></div>
				<div class='<?php print esc_html( $this->plugin ); ?>clearfix'>
					<div class='<?php print esc_html( $this->plugin ); ?>columns2'>
						<center>
							<img style='width:90%' src='<?php echo esc_url( plugins_url( 'images/'.esc_html( $this->slug ).'-pro.png', __FILE__ ) ); ?>' style='width:100%' />
						</center>
					</div>
					
					<div class='<?php print esc_html( $this->plugin ); ?>columns2'>
						<p><i class='fa fa-check'></i> <?php esc_html_e('Import Variable, Affiliate, Subscription Products with excel','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('WPML support for Product Translations Import ','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('ACF & YOAST SEO META support for Import in products','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('export Variable, Affiliate, Subscription Products to excel','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('Products custom Fields Support','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('Bulk import taxonomy terms for products  with excel','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('import Product Reviews  with excel','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('export Product Reviews  to excel','procsme' ); ?></strong></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('import Orders with variable products','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('import Orders & apply Coupons','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('import/export WooCommerce Subscriptions','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('create Customer during Order import','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('Customers custom fields Support','procsme' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e('.. and a lot more!','procsme' ); ?></p>
						<p class='bottomToUp'><center><a target='_blank' class='proUrl' href='<?php print esc_url( $this->proUrl ); ?>'><?php esc_html_e('GET IT HERE', 'procsme' ); ?></a></center></p>
					</div>
				</div>
			  </div>
			</div>		
			<?php
		}		
		
}
$instantiate = new StoreMigrationWooCommerce();