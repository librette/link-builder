<?php
namespace Librette\LinkBuilder\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\PhpGenerator\PhpLiteral;

/**
 * @author David Matějka
 */
class LinkBuilderExtension extends CompilerExtension
{

	protected $defaults = [
		'fallbackUrl'   => 'http://localhost/',
		'registerMacro' => TRUE,
	];


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$fallbackUrl = new Statement('Nette\Http\UrlScript', [$config['fallbackUrl']]);
		$builder->addDefinition($this->prefix('refUrlFactory'))
				->setClass('Librette\LinkBuilder\RefUrlFactory', [$fallbackUrl]);
		$builder->addDefinition($this->prefix('builder'))
				->setClass('Librette\LinkBuilder\LinkBuilder', [1 => new Statement($this->prefix('@refUrlFactory::create'))]);
	}


	public function beforeCompile()
	{
		$config = $this->getConfig($this->defaults);
		if ($config['registerMacro'] === TRUE) {
			$engine = $this->getContainerBuilder()->getDefinition('nette.latteFactory');
			$engine->addSetup('?->onCompile[] = function($engine) { \Librette\LinkBuilder\Latte\LinkMacroSet::install($engine->getCompiler()); }', ['@self']);
			$engine->addSetup('addFilter', ['getLinkBuilder',
					new PhpLiteral('function() { return $this->getByType("Librette\\LinkBuilder\\LinkBuilder");}')]);
		}
	}

}
