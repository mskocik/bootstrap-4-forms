<?php
/**
 * Created by Petr Čech (czubehead).
 * Timestamp: 20.5.18 17:12
 */

namespace Czubehead\BootstrapForms\Grid;


use Nette\Utils\Html;
use Nette\SmartObject;
use Nette\ComponentModel\IComponent;
use Czubehead\BootstrapForms\BootstrapRenderer;
use Czubehead\BootstrapForms\Enums\RendererConfig;
use Czubehead\BootstrapForms\Enums\RendererOptions;
use Czubehead\BootstrapForms\Traits\BootstrapContainerTrait;
use Nette\Forms\Controls\BaseControl;

/**
 * Class BootstrapCell.
 * Represents a row-column pair = table cell in Bootstrap grid system. This is the part with col-*-* class.
 * Only one component can be present.
 * @package Czubehead\BootstrapForms\Grid
 * @property-read int  $numOfColumns     		Number of Bootstrap columns to occupy
 * @property-read BaseControl[] $childControls     Nested child control if any
 * @property-read Html $elementPrototype 		the Html div that will be rendered. You may define additional
 *                properties.
 */
class BootstrapCell
{
	use SmartObject;
	use BootstrapContainerTrait;

	/**
	 * Only use 'col' class (auto stretch)
	 */
	const COLUMNS_NONE = FALSE;
	/**
	 * Use 'col-auto'
	 */
	const COLUMNS_AUTO = NULL;
	/**
	 * @var int
	 */
	protected $numOfColumns;
	/**
	 * @var BaseControl[]
	 */
	protected $childControls = [];
	/**
	 * @var BootstrapRow
	 */
	protected $row;
	/**
	 * @var Html
	 */
	protected $elementPrototype;

	/**
	 * BootstrapRow constructor.
	 * @param BootstrapRow   $row          Row this is a child of
	 * @param int|null|false $numOfColumns Number of Bootstrap columns to occupy. You can use an integer or
	 *                                     BootstrapCell::COLUMNS_* constant (see their docs for more)
	 */
	public function __construct(BootstrapRow $row, $numOfColumns)
	{
		$this->numOfColumns = $numOfColumns;
		$this->row = $row;

		$this->elementPrototype = Html::el('div');
	}

	/**
	 * Gets the prototype of this cell so you can define additional attributes. Col-* class is added during
	 * rendering and is not present, so don't add it...
	 * @return Html
	 */
	public function getElementPrototype()
	{
		return $this->elementPrototype;
	}

	/**
	 * @return int|false|null
	 * @see BootstrapCell::$numOfColumns
	 */
	public function getNumOfColumns()
	{
		return $this->numOfColumns;
	}

	/**
	 * Renders the cell into Html object
	 * @return Html
	 */
	public function render()
	{
		$element = $this->elementPrototype;
		/** @var BootstrapRenderer $renderer */
		$renderer = $this->row->getParent()->form->renderer;

		$element = $renderer->configElem(RendererConfig::gridCell, $element);
		$element->class[] = $this->createClass();

		foreach ($this->childControls as $control) {
			if ($control->getOption(RendererOptions::_rendered)) continue;
			$pairHtml = $renderer->renderPair($control);
			$element->addHtml($pairHtml);
		}

		return $element;
	}

	/**
	 * Delegate to underlying component.
	 * @param IComponent $component
	 * @param            $name
	 * @param null       $insertBefore
	 */
	protected function addComponent(IComponent $component, $name, $insertBefore = NULL)
	{
		/** @noinspection PhpInternalEntityUsedInspection */
		$this->row->addComponent($component, $name, $insertBefore);
		$this->childControls[] = $component;
	}

	/**
	 * Creates column class based on numOfColumns
	 * @return string
	 */
	protected function createClass()
	{
		$cols = $this->numOfColumns;
		if ($cols === self::COLUMNS_NONE) {
			return 'col';
		} elseif ($cols === self::COLUMNS_AUTO) {
			return 'col-auto';
		} else {
			// number
			if ($this->row->gridBreakPoint != NULL) {
				return 'col-' . $this->row->gridBreakPoint . '-' . $this->numOfColumns;
			} else {
				return 'col-' . $this->numOfColumns;
			}
		}
	}
}