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

namespace Mv\ExportFormBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormView;

/**
 * Class CustomTypeEvent
 *
 * @package Mv\ExportFormBundle\Event
 * @author Michaël VEROUX
 */
class CustomTypeEvent extends Event
{
    /**
     * @var string
     */
    protected $exportType;

    /**
     * @var string
     */
    protected $typeName;

    /**
     * @var FormView
     */
    protected $formView;

    /**
     * @var string|null
     */
    protected $displayable;

    /**
     * CustomTypeEvent constructor.
     *
     * @param string   $exportType
     * @param string   $typeName
     * @param FormView $formView
     */
    public function __construct($exportType, $typeName, FormView $formView)
    {
        $this->exportType = $exportType;
        $this->typeName = $typeName;
        $this->formView = $formView;
    }

    /**
     * @return string
     */
    public function getExportType()
    {
        return $this->exportType;
    }

    /**
     * @return null|string
     */
    public function getDisplayable()
    {
        return $this->displayable;
    }

    /**
     * @param null|string $displayable
     *
     * @return $this
     */
    public function setDisplayable($displayable)
    {
        $this->displayable = $displayable;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return FormView
     */
    public function getFormView()
    {
        return $this->formView;
    }
}