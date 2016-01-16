<?php

namespace App\Plugins;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{
		if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			//Register roles
			$roles = array(
				'users'  => new Role('Users'),
				'guests' => new Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//Private area resources
			$privateResources = array(
				'App\Controllers\companies'    => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'App\Controllers\products'     => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'App\Controllers\producttypes' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'App\Controllers\invoices'     => array('index', 'profile'),

				'App\Controllers\Api\V1\about' => array('index'),
			);
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Public area resources
			$publicResources = array(
				'App\Controllers\index'    => array('index'),
				'App\Controllers\about'    => array('index'),
				'App\Controllers\register' => array('index'),
				'App\Controllers\errors'   => array('show401', 'show404', 'show500'),
				'App\Controllers\session'  => array('index', 'register', 'start', 'end'),
				'App\Controllers\contact'  => array('index', 'send'),
			);
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Grant access to public areas to both users and guests
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}

			//Grant access to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{

		$auth = $this->session->get('auth');
		if (!$auth){
			$role = 'Guests';
		} else {
			$role = 'Users';
		}

		$namespace  = $dispatcher->getNamespaceName();
		$controller = $dispatcher->getControllerName();
		$path       = $namespace . '\\' . $controller;
		$action     = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $path, $action);
		if ($allowed != Acl::ALLOW) {
			$dispatcher->forward(array(
				'namespace'  => 'App\Controllers',
				'controller' => 'errors',
				'action'     => 'show401'
			));
                        $this->session->destroy();
			return false;
		}
	}
}
