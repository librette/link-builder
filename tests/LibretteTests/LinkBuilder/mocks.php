<?php
namespace LibretteTests\LinkBuilder;

use Nette;
use Nette\Application\IRouter;
use Nette\Application\Request;
use Nette\Object;


class Router extends Object implements IRouter
{

	/** @var Request */
	public $passedRequest;

	public $result;


	function match(Nette\Http\IRequest $httpRequest)
	{
	}


	function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		$this->passedRequest = $appRequest;

		return $refUrl->baseUrl . $this->result;
	}

}
