<?php
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 */

namespace Czubehead\BootstrapForms\Traits;

use Czubehead\BootstrapForms\BootstrapContainer;
use Czubehead\BootstrapForms\Inputs\ButtonInput;
use Czubehead\BootstrapForms\Inputs\CheckboxInput;
use Czubehead\BootstrapForms\Inputs\CheckboxListInput;
use Czubehead\BootstrapForms\Inputs\DateTimeInput;
use Czubehead\BootstrapForms\Inputs\MultiselectInput;
use Czubehead\BootstrapForms\Inputs\RadioInput;
use Czubehead\BootstrapForms\Inputs\SelectInput;
use Czubehead\BootstrapForms\Inputs\SubmitButtonInput;
use Czubehead\BootstrapForms\Inputs\TextAreaInput;
use Czubehead\BootstrapForms\Inputs\TextInput;
use Czubehead\BootstrapForms\Inputs\UploadInput;
use Nette\ComponentModel\IComponent;
use Nette\Forms\Container;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Forms\Controls\TextArea;
use Nette\Forms\Controls\TextInput as NetteTextInput;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Stringable;

/**
 * Trait BootstrapContainerTrait.
 * Implements methods to add inputs.
 * @package Czubehead\BootstrapForms
 */
trait BootstrapContainerTrait
{
	/**
	 * @param string           $name
	 * @param null|string|Html $content
	 * @param string|null      $btnClass secondary button class (primary is 'btn')
	 * @param string|null      $btnClass secondary button class (primary is 'btn')
	 * @return ButtonInput
	 */
	public function addButton(string $name, string|Stringable|null $content = NULL): Button
	{
		$comp = new ButtonInput($content);
		$comp->setBtnClass('btn-secondary');    // default value
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string $name
	 * @param null   $caption
	 * @return CheckboxInput
	 */
	public function addCheckbox(string $name, string|Stringable|null $caption = null): Checkbox
	{
		$comp = new CheckboxInput($caption);
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string     $name
	 * @param null       $label
	 * @param array|null $items
	 * @return CheckboxListInput
	 */
	public function addCheckboxList(string $name, string|Stringable|null $label = null, ?array $items = null): CheckboxList
	{
		$comp = new CheckboxListInput($label, $items);
		$this->addComponent($comp, $name);

		return $comp;
	}

	public abstract function addComponent(IComponent $component, ?string $name, ?string $insertBefore = NULL);

	/**
	 * @param string $name
	 * @return BootstrapContainer
	 */
	public function addContainer(string|int $name): Container
	{
		$control = new BootstrapContainer;
		$control->currentGroup = $this->currentGroup;
		if ($this->currentGroup !== NULL) {
			/** @noinspection PhpUndefinedMethodInspection */
			$this->currentGroup->add($control);
		}

		return $this[ $name ] = $control;
	}

	/**
	 * Adds a datetime input.
	 * @param string $name  name
	 * @param string $label label
	 * @return DateTimeInput
	 */
	public function addDateTimeInput($name, $label)
	{
		$comp = new DateTimeInput($label);
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param      $name
	 * @param string|Stringable|null $label
	 * @return TextInput
	 */
	public function addEmail(string $name, string|Stringable|null $label = NULL): NetteTextInput
	{
		return $this->addText($name, $label)
					->addRule(Form::Email);
	}

	/**
	 * Adds error to a specific component
	 * @param string $componentName
	 * @param string $message
	 */
	public function addInputError($componentName, $message)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$this[ $componentName ]->addError($message);
	}

	/**
	 * @param string $name
	 * @param  string|Stringable|null  $label
	 * @return TextInput
	 */
	public function addInteger(string $name, string|Stringable|null $label = NULL): NetteTextInput
	{
		return $this->addText($name, $label)
					->addRule(Form::Integer);
	}

	/**
	 * @param string     $name
	 * @param string|Stringable|null $label
	 * @param array|null $items
	 * @param null       $size
	 * @return MultiselectInput
	 */
	public function addMultiSelect(string $name, string|Stringable|null $label = null, ?array $items = NULL, ?int $size = NULL): MultiSelectBox
	{
		$comp = new MultiselectInput($label, $items);
		if ($size !== NULL) {
			$comp->setHtmlAttribute('size', $size);
		}
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @return UploadInput
	 */
	public function addMultiUpload(string $name, string|Stringable|null $label = NULL): UploadControl
	{
		return $this->addUpload($name, $label, TRUE);
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @param null   $cols
	 * @param null   $maxLength
	 * @return TextInput
	 */
	public function addPassword(string $name, string|Stringable|null $label = NULL, ?int $cols = NULL, ?int $maxLength = NULL): NetteTextInput
	{
		return $this->addText($name, $label, $cols, $maxLength)
					->setHtmlType('password');
	}

	public function addRadioList(string $name, $label = NULL, ?array $items = NULL): RadioList
	{
		$comp = new RadioInput($label, $items);
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @param array  $items
	 * @param null   $size ignore
	 * @return SelectInput
	 */
	public function addSelect(string $name, string|Stringable|null $label = NULL, ?array $items = NULL, ?int $size = NULL): SelectBox
	{
		$comp = new SelectInput($label, $items);
		if ($size !== NULL) {
			$comp->setHtmlAttribute('size', $size);
		}
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string $name
	 * @param string $caption
	 * @param string $btnClass secondary button class (primary is 'btn')
	 * @return SubmitButtonInput
	 */
	public function addSubmit(string $name, string|Stringable|null $caption = NULL): SubmitButton
	{
		$comp = new SubmitButtonInput($caption);
		$comp->setBtnClass('btn-primary');
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @param null   $cols      ignored
	 * @param null   $maxLength ignored
	 * @return TextInput
	 */
	public function addText(string $name, string|Stringable|null $label = NULL, ?int $cols = NULL, ?int $maxLength = NULL): NetteTextInput
	{
		$comp = new TextInput($label);
		if ($cols !== NULL) {
			$comp->setHtmlAttribute('cols', $cols);
		}
		if ($maxLength != NULL) {
			$comp->setHtmlAttribute('maxlength', $cols);
		}
		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @param null   $cols ignored
	 * @param null   $rows ignored
	 * @return TextAreaInput
	 */
	public function addTextArea(string $name, string|Stringable|null $label = NULL, ?int $cols = NULL, ?int $rows = NULL): TextArea
	{
		$comp = new TextAreaInput($label);
		if ($cols !== NULL) {
			$comp->setHtmlAttribute('cols', $cols);
		}
		if ($rows !== NULL) {
			$comp->setHtmlAttribute('rows', $rows);
		}

		$this->addComponent($comp, $name);

		return $comp;
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @param bool   $multiple
	 * @return UploadInput
	 */
	public function addUpload(string $name, string|Stringable|null $label = NULL, bool $multiple = FALSE): UploadControl
	{
		$comp = new UploadInput($label, $multiple);
		$this->addComponent($comp, $name);

		return $comp;
	}
}