<?php
/*
Plugin Name: PMPro Reports for Editors
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-reports-for-editors/
Description: Give WP editors access to the PMPro reports.
Version: .1
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
*/

//add pmpro caps to editors
function pmprorfe_init()
{
	//add pmpro caps to editors
	$role = get_role( 'editor' );
	$role->add_cap('pmpro_memberslist');
	$role->add_cap('pmpro_memberslist_csv');
	$role->add_cap('pmpro_orders');
	$role->add_cap('pmpro_orders_csv');
	$role->add_cap('pmpro_reports');		
}
add_action("init", "pmprorfe_init", 1);

//draw the admin menu for editors
function pmprofre_add_pages()
{
	global $wpdb;

	//we already drew this for admins
	if ( is_super_admin() )
		return;
	
	if(current_user_can("pmpro_memberslist") ||
	   current_user_can("pmpro_memberslist_csv") ||
	   current_user_can("pmpro_orders") ||
	   current_user_can("pmpro_orders_csv") ||
	   current_user_can("pmpro_reports"))
	{		
		add_menu_page(__('Memberships', 'pmpro'), __('Memberships', 'pmpro'), 'read', 'pmpro-memberslist', 'pmpro_memberslist', PMPRO_URL . '/images/menu_users.png');
		add_submenu_page('pmpro-memberslist', __('Members List', 'pmpro'), __('Members List', 'pmpro'), 'pmpro_memberslist', 'pmpro-memberslist', 'pmpro_memberslist');
		add_submenu_page('pmpro-memberslist', __('Reports', 'pmpro'), __('Reports', 'pmpro'), 'pmpro_reports', 'pmpro-reports', 'pmpro_reports');	
		add_submenu_page('pmpro-memberslist', __('Orders', 'pmpro'), __('Orders', 'pmpro'), 'pmpro_orders', 'pmpro-orders', 'pmpro_orders');		
	}
}
add_action('admin_menu', 'pmprofre_add_pages');

//draw the admin bar for editors
function pmprorfe_admin_bar_menu()
{
	global $wp_admin_bar;
	
	//we already drew this for admins
	if ( is_super_admin() || !is_admin_bar_showing() )
		return;
		
	//now make sure they have a PMPro reports cap
	if(current_user_can("pmpro_memberslist") ||
	   current_user_can("pmpro_memberslist_csv") ||
	   current_user_can("pmpro_orders") ||
	   current_user_can("pmpro_orders_csv") ||
	   current_user_can("pmpro_reports"))
	{	
		$wp_admin_bar->add_menu( array(
		'id' => 'paid-memberships-pro',
		'title' => __( 'Memberships', 'pmpro'),
		'href' => get_admin_url(NULL, '/admin.php?page=pmpro-membershiplevels') ) );
	}
	
	if(current_user_can("pmpro_memberslist"))
	{
		$wp_admin_bar->add_menu( array(
		'id' => 'pmpro-members-list',
		'parent' => 'paid-memberships-pro',
		'title' => __( 'Members List', 'pmpro'),
		'href' => get_admin_url(NULL, '/admin.php?page=pmpro-memberslist') ) );
	}
	
	if(current_user_can("pmpro_reports"))
	{
		$wp_admin_bar->add_menu( array(
		'id' => 'pmpro-reports',
		'parent' => 'paid-memberships-pro',
		'title' => __( 'Reports', 'pmpro'),
		'href' => get_admin_url(NULL, '/admin.php?page=pmpro-reports') ) );
	}
	
	if(current_user_can("pmpro_orders"))
	{
		$wp_admin_bar->add_menu( array(
		'id' => 'pmpro-orders',
		'parent' => 'paid-memberships-pro',
		'title' => __( 'Orders', 'pmpro'),
		'href' => get_admin_url(NULL, '/admin.php?page=pmpro-orders') ) );
	}
}
add_action('admin_bar_menu', 'pmprorfe_admin_bar_menu', 1000);