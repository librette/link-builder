<?php
namespace Librette\LinkBuilder;

use Nette\Application;
use Nette\Application\IRouter;
use Nette\Application\UI\Presenter;
use Nette\Http\Url;

/**
 * @author David Matějka
 * @author David Grudl
 */
class LinkBuilder
{

	/** @var \Nette\Application\IRouter */
	protected $router;

	/** @var \Nette\Http\Url */
	protected $refUrl;


	/**
	 * @param IRouter
	 * @param Url
	 */
	public function __construct(IRouter $router, Url $refUrl = NULL)
	{
		$this->refUrl = $refUrl;
		$this->router = $router;
	}


	/**
	 * @param string|Url
	 * @return self
	 */
	public function withRefUrl($url)
	{
		if (!$url instanceof Url) {
			$url = new Url($url);
		}

		return new static($this->router, $url);
	}


	/**
	 * URL factory.
	 *
	 * @param string $destination in format "[module:]presenter:action"
	 * @param array $args array of arguments
	 * @return string URL
	 * @throws InvalidLinkException
	 */
	public function link($destination, array $args = [])
	{
		if (!$this->refUrl) {
			throw new InvalidStateException("Reference URL is not set.");
		}
		// 1) fragment
		$a = strpos($destination, '#');
		if ($a === FALSE) {
			$fragment = '';
		} else {
			$fragment = substr($destination, $a);
			$destination = substr($destination, 0, $a);
		}

		// 2) ?query syntax
		$a = strpos($destination, '?');
		if ($a !== FALSE) {
			parse_str(substr($destination, $a + 1), $args); // requires disabled magic quotes
			$destination = substr($destination, 0, $a);
		}

		// 3) URL scheme
		$a = strpos($destination, '//');
		if ($a !== FALSE) {
			$destination = substr($destination, $a + 2);
		}


		if ($destination == NULL) { // intentionally ==
			throw new InvalidLinkException("Destination must be non-empty string.");
		}

		// 5) presenter: action
		$a = strrpos($destination, ':');
		$action = (string) substr($destination, $a + 1);
		$presenter = substr($destination, 0, $a);
		if ($presenter[0] == ":") {
			$presenter = substr($presenter, 1);
		}
		if (!$action) {
			$action = 'default';
		}

		// ADD ACTION
		$args[Presenter::ACTION_KEY] = $action;

		$request = new Application\Request($presenter, Application\Request::FORWARD, $args, [], []);

		// CONSTRUCT URL
		$url = $this->router->constructUrl($request, $this->refUrl);
		if ($url === NULL) {
			unset($args[Presenter::ACTION_KEY]);
			$params = urldecode(http_build_query($args, NULL, ', '));
			throw new InvalidLinkException("No route for $presenter:$action($params)");
		}

		return $url . $fragment;
	}

}
