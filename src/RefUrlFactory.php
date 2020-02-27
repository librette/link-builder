<?php
namespace Librette\LinkBuilder;

use Nette\Http\Request;
use Nette\Http\Url;

/**
 * @author David Matějka
 */
class RefUrlFactory
{

	/** @var \Nette\Http\Url */
	protected $fallbackUrl;

	/** @var \Nette\Http\Request */
	protected $httpRequest;


	/**
	 * @param Url
	 * @param Request
	 */
	public function __construct(Url $fallbackUrl, Request $httpRequest)
	{
		$this->fallbackUrl = $fallbackUrl;
		$this->httpRequest = $httpRequest;
	}


	/**
	 * @return \Nette\Http\Url|\Nette\Http\UrlScript
	 */
	public function create()
	{
		$url = $this->httpRequest->getUrl();
		if (!$url->getHost()) {
			$url = $this->fallbackUrl;
		}

		return $url;
	}
}
