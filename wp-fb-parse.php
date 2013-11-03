<?php 
/*
 * Plugin Name: Wordpress with Facebook 
 * Author: Mahibul Hasan
 * Description: Customized plugin to handle facebook wordrpess and parse.com together.
 * */

class WpFbParseDotCom{
	
	//base directory of this plugin
	public $plugin_directory;
	
	//url to indicate plugin's folder
	public $plugin_uri;
	
	//locale
	public $locale = 'en_US';
	
	//fb instance
	public $facebook;
	
	//parse.com instance
	public $parse_dot_com;
	
	//fb credentials
	public $app_id;
	public $app_secret;
	
	
	function __construct(){
		
		$this->plugin_directory = dirname(__FILE__) . '/';
		$this->plugin_uri = plugins_url('/', __FILE__);
		
		//facebook
		include $this->plugin_directory . 'lib/facebook.php';
		$this->app_id = get_option('facebook_app_id');
		$this->app_secret = get_option('facebook_app_secret');
		$this->facebook = new Facebook( array(
				'appId'  => $this->app_id,
				'secret' => $this->app_secret
		) );
		
		//pares.com		
		
		
		
		$this->init();
		
		
	}
	
	
	//initialize the hooks
	function init(){
		//add a connect to facebook to user's profile
		add_action('show_user_profile', array(&$this, 'fb_connect_at_profile_page'));

		//admin page to store the facebook api and secret
		add_action('admin_menu', array(&$this, 'admin_menu'));
	}
	
	
	/**
	 * add a connect to button at profile page of an user
	 * @@$profileuser is profile user information
	 * */
	function fb_connect_at_profile_page($profileuser){
		
		$user = $this->facebook->getUser();
		
		if($_GET['facebook_connect'] == '1' && isset($_GET['code']) && !empty($user)){
			update_user_meta($profileuser->ID, 'facebook_id', $user);			
		}
		
		if($_GET['facebook_connect'] == '2'){
			delete_user_meta($profileuser->ID, 'facebook_id');
		}
		
		$fb_user_id = $this->get_connected_facebook_user($profileuser->ID);
		
		if($fb_user_id){
			$fb_profile = $this->facebook->api('/' . $fb_user_id);
		}
		
		$loginUrl = $this->facebook->getLoginUrl(array('redirect_uri' => $this->get_fb_redirect_url(array('facebook_connect' => '1'))));
		//$logoutUrl = $this->facebook->getLogoutUrl(array('redirect_uri' => $this->get_fb_redirect_url(array('facebook_connect' => '2'))));
		$disconnectUrl = $this->get_fb_redirect_url(array('facebook_connect' => '2'));
		//var_dump($logoutUrl);
			
				
		?>
		<h3>Facebook</h3>
		<?php if($fb_profile): ?>
		<table class="form-table">				
			<tr>
				<th scope="row">Status</th>
				<td>Connected </td>
			</tr>					
			<tr>
				<th scope="row">Profile</th>
				<td> <a target="_blank" href="<?php echo $fb_profile['link']; ?>">  <img title="<?php echo $fb_profile['name']; ?>" src="https://graph.facebook.com/<?php echo $fb_user_id; ?>/picture"> </a> <a href="<?php echo $disconnectUrl; ?>">different user?</a> </td>
			</tr>					
		</table>
		<?php else: ?>
		<table class="form-table">
			<tr>
				<th scope="row">Status</th>						
				<td> Disconnected!</td>						
			</tr>					
			<tr>
				<th scope="row"> Profile </th>
				<td>  <a href="<?php echo $loginUrl; ?>"> <input type="button" value="connect to facebook" class="button button-primary"></a></td>
			</tr>					
		</table>
		<?php endif; ?>
		<?php 
	}
	
	
	//save facebook profile with wordpress
	function get_connected_facebook_user($user_id){
		return get_user_meta($user_id, 'facebook_id', true);
	}
	
	
	//get redirect url for wordpress backend
	function get_fb_redirect_url($param = array()){
		return add_query_arg($param, admin_url('profile.php'));
	}
	
	
	//admin menu
	function admin_menu(){
		add_options_page('facebook app options page', 'Facebook', 'manage_options', 'facebook_app_options_page', array(&$this, 'options_page_content'));
	}
	
	//options page content
	function options_page_content(){
		include $this->plugin_directory . 'includes/options-page.php';
	}
	
	
	//facebook credentials
	function get_fb_credentials(){
		return array(
			'app_id' => get_option('facebook_app_id'),
			'app_secret' => get_option('facebook_app_secret')
		);
	}
	
}

global $wp_fb_parse;
$wp_fb_parse = new WpFbParseDotCom();

?>