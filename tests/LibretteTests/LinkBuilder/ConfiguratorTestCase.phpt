<?php
namespace LibretteTests\LinkBuilder;

use Latte\Loaders\StringLoader;
use Librette;
use Nette;
use Tester;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 */
class ConfiguratorTestCase extends Tester\TestCase
{

	/** @var \Nette\DI\Container */
	protected $container;


	public function setUp()
	{
		$configurator = new Nette\Configurator();
		$configurator->addConfig(__DIR__ . '/config/config.neon');
		$configurator->setTempDirectory(TEMP_DIR);
		$this->container = $configurator->createContainer();
	}


	public function testBasic()
	{
		$builder = $this->container->getService('linkBuilder.builder');
		Tester\Assert::type('Librette\LinkBuilder\LinkBuilder', $builder);
		Tester\Assert::same('http://example.com/lorem/ipsum', $builder->link('Lorem:ipsum'));
	}


	public function testLatte()
	{
		$latteFactory = $this->container->getByType('Nette\Bridges\ApplicationLatte\ILatteFactory');
		/** @var \Latte\Engine $engine */
		$engine = $latteFactory->create();
		$filters = $engine->getFilters();
		Tester\Assert::true(isset($filters['getlinkbuilder']));
		Tester\Assert::type('Librette\LinkBuilder\LinkBuilder', $filters['getlinkbuilder']());
		$engine->setLoader(new StringLoader());
		Tester\Assert::same('http://example.com/lorem/ipsum', $engine->renderToString('{link Lorem:ipsum}'));
		Tester\Assert::same('<a href="http://example.com/lorem/ipsum"></a>', $engine->renderToString('<a n:href="Lorem:ipsum"></a>'));
	}
}


\run(new ConfiguratorTestCase());
