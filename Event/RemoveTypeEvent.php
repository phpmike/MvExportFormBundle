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

/**
 * Class RemoveTypeEvent
 *
 * @package Mv\ExportFormBundle\Event
 * @author Michaël VEROUX
 */
class RemoveTypeEvent extends Event
{
    /**
     * @var array
     */
    protected $removeTypes = array();

    /**
     * @var array
     */
    protected $removeRootTypes = array();

    /**
     * @var string
     */
    protected $exportType;

    /**
     * RemoveTypeEvent constructor.
     *
     * @param string $exportType
     */
    public function __construct($exportType)
    {
        $this->exportType = $exportType;
    }

    /**
     * @return string
     */
    public function getExportType()
    {
        return $this->exportType;
    }

    /**
     * @return array
     */
    public function getRemoveTypes()
    {
        return $this->removeTypes;
    }

    /**
     * @param array $removeTypes
     *
     * @return $this
     */
    public function setRemoveTypes($removeTypes)
    {
        $this->removeTypes = $removeTypes;

        return $this;
    }

    /**
     * @param string $removeType
     *
     * @return $this
     */
    public function addRemoveType($removeType)
    {
        $this->removeTypes[] = $removeType;

        return $this;
    }

    /**
     * @return array
     */
    public function getRemoveRootTypes()
    {
        return $this->removeRootTypes;
    }

    /**
     * @param array $removeRootTypes
     *
     * @return $this
     */
    public function setRemoveRootTypes($removeRootTypes)
    {
        $this->removeRootTypes = $removeRootTypes;

        return $this;
    }

    /**
     * @param string $removeRootType
     *
     * @return $this
     */
    public function addRemoveRootType($removeRootType)
    {
        $this->removeRootTypes[] = $removeRootType;

        return $this;
    }
}
