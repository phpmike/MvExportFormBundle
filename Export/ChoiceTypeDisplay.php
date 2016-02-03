<?php
/*
Copyright 2016 Michael Veroux
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and limitations under the License.
*/

namespace Mv\ExportFormBundle\Export;

use Symfony\Component\Form\FormView;
use Symfony\Component\Translation\Translator;

/**
 * Class ChoiceTypeDisplay
 *
 * @package Mv\ExportFormBundle\Export
 * @author Michaël VEROUX
 */
class ChoiceTypeDisplay
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * ChoiceTypeDisplay constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormView $formView
     *
     * @return string
     * @author Michaël VEROUX
     */
    public function get(FormView $formView)
    {
        $value = $formView->vars['value'];
        $display = array();
        $expanded = isset($formView->vars['expanded']) && $formView->vars['expanded'];
        $multiple = isset($formView->vars['multiple']) && $formView->vars['multiple'];
        if ($expanded && $multiple) {
            $value = array_filter($value, function ($val) {
                return $val;
            });
            $value = array_keys($value);
        }
        foreach ($formView->vars['choices'] as $key => $choiceView) {
            if ($multiple) {
                if ($expanded) {
                    $compare = $key;
                } else {
                    $compare = $choiceView->value;
                }
                if (in_array($compare, $value)) {
                    $display[] = $this->translator->trans($choiceView->label, array(), $formView->vars['translation_domain']);
                }
            } else {
                if ($value == $choiceView->value) {
                    return $this->translator->trans($choiceView->label, array(), $formView->vars['translation_domain']);
                }
            }
        }
        if (!count($display)) {
            return $formView->vars['placeholder'];
        }

        return implode(PHP_EOL, $display);
    }
}
