<?php
namespace Librette\LinkBuilder\Latte;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * @author David Matějka
 */
class LinkMacroSet extends MacroSet
{

	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('link', [$me, 'macroLink']);
		$me->addMacro('plink', [$me, 'macroLink']);
		$me->addMacro('href', NULL, NULL, function (MacroNode $node, PhpWriter $writer) use ($me) {
			return ' ?> href="<?php ' . $me->macroLink($node, $writer) . ' ?>"<?php ';
		});
	}


	/**
	 * overwritten default link macro, added fallback to link generator when neither control or presenter is available
	 *
	 * @param MacroNode
	 * @param PhpWriter
	 * @return string
	 */
	public function macroLink(MacroNode $node, PhpWriter $writer)
	{
		return $writer->write('
		$_currentLinkBuilder = NULL;
		if(isset(' . ($node->name === 'plink' ? '$_presenter' : '$_control') . ')) {
			$_currentLinkBuilder = ' . ($node->name === 'plink' ? '$_presenter' : '$_control') . ';
		} else {
			$_currentLinkBuilder = $template->global->linkBuilder;
		}
		echo %escape(%modify($_currentLinkBuilder->link(%node.word, %node.array?)));
		');
	}

}
