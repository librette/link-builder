<?php
namespace LibretteTests\LinkBuilder;

use Librette;
use Nette;
use Tester;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/mocks.php';


/**
 * @author David Matějka
 */
class LinkBuilderTestCase extends Tester\TestCase
{

	public function setUp()
	{
	}


	public function testLink()
	{
		$router = new Router();
		$router->result = 'foo';
		$linkBuilder = new Librette\LinkBuilder\LinkBuilder($router, new Nette\Http\Url('http://localhost/'));
		Tester\Assert::same('http://localhost/foo', $linkBuilder->link('Foo:', ['xx' => 'yy']));
		Tester\Assert::same('Foo', $router->passedRequest->presenterName);
		Tester\Assert::equal(['action' => 'default', 'xx' => 'yy'], $router->passedRequest->parameters);
	}


	public function testChangeRefUrl()
	{
		$router = new Router();
		$router->result = 'foo';
		$linkBuilder = new Librette\LinkBuilder\LinkBuilder($router, new Nette\Http\Url('http://localhost/'));
		Tester\Assert::same('http://google.com/foo', $linkBuilder->withRefUrl('http://google.com')->link('Foo:', ['xx' => 'yy']));
	}


	public function testHash()
	{
		$router = new Router();
		$router->result = 'foo';
		$linkBuilder = new Librette\LinkBuilder\LinkBuilder($router, new Nette\Http\Url('http://localhost/'));
		Tester\Assert::same('http://localhost/foo#bar', $linkBuilder->link('Foo:#bar'));
	}
}

\run(new LinkBuilderTestCase());
