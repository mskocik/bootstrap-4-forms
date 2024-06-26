<?php
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 */

namespace Czubehead\BootstrapForms\Inputs;


use Czubehead\BootstrapForms\Traits\BootstrapButtonTrait;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\Html;

/**
 * Class SubmitButtonInput. Form can be submitted with this.
 * @package Czubehead\BootstrapForms\Inputs
 */
class SubmitButtonInput extends SubmitButton
{
	use BootstrapButtonTrait;

	public function getControl($caption = NULL): Html
	{
		$control = parent::getControl($caption);
		$control->setName('button');
		$control->addHtml($caption ?? (string) $this->caption);
		$this->addBtnClass($control);

		return $control;
	}
}
