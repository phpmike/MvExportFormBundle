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

namespace Mv\ExportFormBundle\Tool;

use SplFileObject;

/**
 * Class Csv
 *
 * @package Mv\ExportFormBundle\Tool
 * @author Michaël VEROUX
 */
class Csv implements CsvInterface
{
    /**
     * @var SplFileObject
     */
    protected $csvFile;

    /**
     * Csv constructor.
     */
    public function __construct()
    {
        $this->csvFile = new SplFileObject('php://memory', 'w+');
    }

    /**
     * @param array $csv
     *
     * @return void
     * @author Michaël VEROUX
     */
    public function putCsv(array $csv)
    {
        $this->csvMode();
        $this->csvFile->fputcsv($csv);
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        $this->stringMode();
        $content = '';
        $this->csvFile->setFlags(SplFileObject::READ_AHEAD);
        $this->csvFile->rewind();
        while ($line = $this->csvFile->current()) {
            $content .= $line;
            $this->csvFile->next();
        }

        return $content;
    }

    /**
     * @return void
     * @author Michaël VEROUX
     */
    private function csvMode()
    {
        $this->csvFile->setFlags(SplFileObject::READ_AHEAD + SplFileObject::READ_CSV);
        $this->csvFile->setCsvControl(';', '"');
    }

    /**
     * @return void
     * @author Michaël VEROUX
     */
    private function stringMode()
    {
        $this->csvFile->setFlags(SplFileObject::READ_AHEAD);
    }
}
