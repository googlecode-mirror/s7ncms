<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * S7Ncms - www.s7n.de
 *
 * Copyright (c) 2007-2009, Eduard Baun <eduard at baun.de>
 * All rights reserved.
 *
 * See license.txt for full text and disclaimer
 *
 * @author Eduard Baun <eduard at baun.de>
 * @copyright Eduard Baun, 2007-2009
 * @version $Id$
 */
class Page_Controller extends Controller {
	
	public function index($id)
	{
		$view = View::factory('page')
			->bind('page', $page)
			->bind('content', $content);
		
		$page = menus::$page;
    	$content = $page->content();
		
    	echo $view;
	}
}