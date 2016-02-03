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

use DOMDocument;

/**
 * Class XmlExport
 *
 * @package Mv\ExportFormBundle\Export
 * @author Michaël VEROUX
 */
class XmlExport extends AbstractExport
{
    /**
     * @var DOMDocument
     */
    protected $dom;

    /**
     * @return string
     * @author Michaël VEROUX
     */
    public function getXml()
    {
        $this->doXml();

        return $this->dom->saveXML();
    }

    /**
     * @return DOMDocument
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @return void
     * @author Michaël VEROUX
     */
    private function doXml()
    {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $itemsNode = $this->dom->createElement('items');
        $this->dom->appendChild($itemsNode);
        $labels = $this->collection->getLabels();
        foreach ($this->collection->all() as $line) {
            foreach ($line as $label => $value) {
                $cdataValue = $this->dom->createCDATASection($value);
                $item = $this->dom->createElement('item');
                $item->appendChild($cdataValue);
                $item->setAttribute('key', $label);
                if (isset($labels[$label])) {
                    $item->setAttribute('label', $labels[$label]);
                }
                $itemsNode->appendChild($item);
            }
        }
    }
}
