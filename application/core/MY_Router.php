<?php

class MY_Router extends CI_Router
{
	protected function _set_request($segments = array())
	{
//		\NewFramework\Logger::write(__METHOD__, json_encode($segments));

		parent::_set_request($segments);

//		\NewFramework\Logger::write('segments', json_encode($this->uri->rsegments));
	}
}
