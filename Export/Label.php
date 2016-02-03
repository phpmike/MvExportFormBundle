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
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Environment;

/**
 * Class Label
 *
 * @package Mv\ExportFormBundle\Export
 * @author Michaël VEROUX
 */
class Label implements LabelInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @param TranslatorInterface $translator
     * @param Twig_Environment    $twig
     */
    public function __construct(TranslatorInterface $translator, Twig_Environment $twig)
    {
        $this->translator = $translator;
        $this->twig = $twig;
    }

    /**
     * @param FormView $formView
     *
     * @return string
     * @author Michaël VEROUX
     */
    public function trans(FormView $formView)
    {
        if ($formView->vars['translation_domain']) {
            $translationDomain = $formView->vars['translation_domain'];
        } else {
            $translationDomain = 'messages';
        }

        $labelKey = $formView->vars['label'];
        $labelFormat = $formView->vars['label_format'];
        if (null === $labelKey) {
            if (null === $labelFormat) {
                $template = $this->twig->createTemplate('{{ name|humanize }}');
                $labelKey = $template->render(array(
                    'name'  =>  $formView->vars['name'],
                ));
            } else {
                $template = $this->twig->createTemplate('{{ label_format|replace({"%name%": name,"%id%": id}) }}');
                $labelKey = $template->render(array(
                    'name'  =>  $formView->vars['name'],
                    'id'    =>  $formView->vars['id'],
                ));
            }
        }

        return $this->translator->trans($labelKey, array(), $translationDomain);
    }
}
