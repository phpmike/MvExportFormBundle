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

use Mv\ExportFormBundle\Exception\LogicException;
use Mv\ExportFormBundle\Tool\CsvInterface;

/**
 * Class CsvExport
 *
 * @package Mv\ExportFormBundle\Export
 * @author Michaël VEROUX
 */
class CsvExport extends AbstractExport
{
    /**
     * @var string
     */
    protected $csvClassName = '\Mv\ExportFormBundle\Tool\Csv';

    /**
     * @param string $csvClassName
     *
     * @return $this
     */
    public function setCsvClassName($csvClassName)
    {
        $this->csvClassName = $csvClassName;

        return $this;
    }

    /**
     * @return string
     * @author Michaël VEROUX
     */
    public function getCsv()
    {
        $csv = $this->getCsvObject();
        $csv->putCsv($this->collection->getLabels());
        foreach ($this->collection->all() as $line) {
            $csv->putCsv($line);
        }

        return (string)$csv;
    }

    /**
     * @return CsvInterface
     * @author Michaël VEROUX
     */
    private function getCsvObject()
    {
        $csvClass = $this->csvClassName;
        $csv = new $csvClass();
        if (!$csv instanceof CsvInterface) {
            throw new LogicException('Bad CSV class name!');
        }

        return $csv;
    }
}
