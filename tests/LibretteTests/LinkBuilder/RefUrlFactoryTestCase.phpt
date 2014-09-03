<?php
namespace LibretteTests\LinkBuilder;

use Librette;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 */
class RefUrlFactoryTestCase extends Tester\TestCase
{

	public function setUp()
	{
	}


	public function testStandard()
	{
		$refUrlFactory = new Librette\LinkBuilder\RefUrlFactory(new Nette\Http\Url("http://fallback.com/"),
			new Nette\Http\Request(new Nette\Http\UrlScript("http://main.com")));

		Assert::same("http://main.com/", $refUrlFactory->create()->absoluteUrl);
	}


	public function testFallback()
	{
		$refUrlFactory = new Librette\LinkBuilder\RefUrlFactory(new Nette\Http\Url("http://fallback.com/"),
			new Nette\Http\Request(new Nette\Http\UrlScript()));

		Assert::same("http://fallback.com/", $refUrlFactory->create()->absoluteUrl);
	}
}


\run(new RefUrlFactoryTestCase());
