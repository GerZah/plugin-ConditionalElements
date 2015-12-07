<?php

// Include global helper functions to be accessible from within all objects
define('CONDITIONALELEMENTSFILES_DIR',dirname(__FILE__));
require_once CONDITIONALELEMENTSFILES_DIR.'/helpers/ConditionalElementsHelpers.php';

/**
* ConditionalElements plugin.
*
* @package Omeka\Plugins\ConditionalElements
*/
class ConditionalElementsPlugin extends Omeka_Plugin_AbstractPlugin {
	/**
	* @var array This plugin's hooks.
	*/
	protected $_hooks = array(
		'initialize',
		'install',
		'uninstall',
		'admin_head', // embed our jQuery code when adding / editing objects
		'define_acl',
	);

  protected $_options = array(
    'conditional_elements_dependencies' => "[]",
  );

  /**
  * Install the plugin.
  */
  public function hookInstall() {

    SELF::_installOptions();
  }

  /**
  * Uninstall the plugin.
  */
  public function hookUninstall() {

    SELF::_uninstallOptions();
  }

	/**
	* @var array This plugin's filters.
	*/
	protected $_filters = array('admin_navigation_main');

   /**
     * Add the translations.
     */
  public function hookInitialize()
  {
    add_translation_source(dirname(__FILE__) . '/languages');
  }


  /**
   * Define the ACL.
   *
   * @param array $args
   */
	function hookDefineAcl($args)
	{
		// Restrict access to super and admin users.
		$args['acl']->addResource('ConditionalElements_Index');
	}

	function filterAdminNavigationMain($nav)
	{
		if(is_allowed('ConditionalElements_Index', 'index')) {
			$nav[] = array('label' => __('Conditional Elements'), 'uri' => url('conditional-elements'));
		}
		return $nav;
	}

	public function hookAdminHead($args) {
		// Core hookAdminHead taken from ElementTypes plugin

		$request = Zend_Controller_Front::getInstance()->getRequest();

		$module = $request->getModuleName();
		if (is_null($module)) { $module = 'default'; }

		$controller = $request->getControllerName();
		$action = $request->getActionName();

		if ($module === 'default' &&
				$controller === 'items' &&
				in_array($action, array('add',  'edit')) )
		{

			// ------------------------------------------
			// An array of dependencies:
			// Each dependency is represented by a "dependee", a "term", and a "dependent".
			// ... meaning: If and only if the "dependee"'s value equals the "term", the "dependent" will be visible.

			// Retrieve dependencies from Database
			/* */
			$json=get_option('conditional_elements_dependencies');
			if (!$json) { $json="[]"; } # else { $json = $this->_removeOutdatedDependencies($json); }
			/* */

			echo "<script>var conditionalElementsDep=$json;</script>";
			// ------------------------------------------

			queue_js_file('conditionalelements');
		} # if ($module === 'default' ...
	} # public function hookAdminHead()

} # class
