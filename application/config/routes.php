<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
//$route['finalproducts'] = "finalproducts";
$route['ucreate']                       = 'c_ucreate';
$route['registration']                  = 'c_registration';
$route['user/password_reset']           = 'c_user/password_reset';
$route['contact']                       = 'c_contact';
$route['products']                      = 'c_products';
$route['preview/show/(:num)']           = 'c_preview/show/$1';
$route['ucreate/(:num)']                = 'c_ucreate/index/$1';
$route['shopping_cart']                 = 'c_shopping_cart';
$route['customer/index']                = 'c_customer/index';
$route['admin']                         = 'c_admin/index';
$route['admin/index']                   = 'c_admin/index';
$route['producent']                     = 'c_producent/index';
$route['producent/index']               = 'c_producent/index';
$route['welcome']                       = "c_welcome";
$route['welcome/index']                 = "c_welcome/index";
$route['default_controller']            = "c_welcome";
$route['404_override']                  = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */