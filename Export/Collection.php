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

/**
 * Class Collection
 *
 * @package Mv\ExportFormBundle\Export
 * @author Michaël VEROUX
 */
class Collection
{
    /**
     * @var array
     */
    protected $labels = array();

    /**
     * @var array
     */
    protected $lines = array();

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param array $labels
     *
     * @return $this
     */
    public function mergeLabels($labels)
    {
        $this->labels = array_merge($this->labels, $labels);

        return $this;
    }

    /**
     * @param array $line
     *
     * @return $this
     * @author Michaël VEROUX
     */
    public function addLine($line)
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * @return array
     * @author Michaël VEROUX
     */
    public function all()
    {
        $emptyLabels = array_fill_keys(array_keys($this->labels), '');
        $all = array();
        foreach ($this->lines as $line) {
            $all[] = array_merge($emptyLabels, $line);
        }

        return $all;
    }
}
