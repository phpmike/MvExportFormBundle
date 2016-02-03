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

use Mv\ExportFormBundle\Event\CustomTypeEvent;
use Mv\ExportFormBundle\Event\FormExportEvents;
use Mv\ExportFormBundle\Event\RemoveTypeEvent;
use Mv\ExportFormBundle\Exception\LogicException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Traversable;
use DateTime;

/**
 * Class AbstractExport
 *
 * @package Mv\ExportFormBundle\Export
 * @author Michaël VEROUX
 */
abstract class AbstractExport
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var LabelInterface
     */
    protected $label;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ChoiceTypeDisplay
     */
    protected $choiceDisplay;

    /**
     * @var string
     */
    protected $formName;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $rootRemoves = array(
        '_token',
    );

    /**
     * @var array
     */
    protected $typeRemoves = array(
        'hidden',
    );

    /**
     * CsvExport constructor.
     *
     * @param FormFactoryInterface     $formFactory
     * @param LabelInterface           $label
     * @param EventDispatcherInterface $eventDispatcher
     * @param ChoiceTypeDisplay        $choiceTypeDisplay
     * @param string                   $formName
     */
    public function __construct(FormFactoryInterface $formFactory, LabelInterface $label, EventDispatcherInterface $eventDispatcher, ChoiceTypeDisplay $choiceTypeDisplay, $formName = '')
    {
        $this->formFactory = $formFactory;
        $this->label = $label;
        $this->eventDispatcher = $eventDispatcher;
        $this->choiceDisplay = $choiceTypeDisplay;
        $this->formName = $formName;
    }

    /**
     * @param LabelInterface $label
     *
     * @return $this
     */
    public function setLabel(LabelInterface $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param string $formName
     *
     * @return $this
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;

        return $this;
    }

    /**
     * @param $entities
     *
     * @return bool
     * @author Michaël VEROUX
     */
    public function export($entities)
    {
        if (!(is_array($entities) || $entities instanceof Traversable)) {
            throw new LogicException('$entities expected to be Traversable.');
        }
        $state = true;
        $this->collection = new Collection();

        $event = new RemoveTypeEvent($this->formName);
        $event->setRemoveTypes($this->typeRemoves);
        $this->eventDispatcher->dispatch(FormExportEvents::REMOVE_TYPE, $event);
        $this->typeRemoves = $event->getRemoveTypes();
        $this->rootRemoves = array_merge($this->rootRemoves, $event->getRemoveRootTypes());

        foreach ($entities as $entity) {
            try {
                $this->extractFromForm($entity);
            } catch (\Exception $e) {
                $state = false;
            }
        }

        return $state;
    }

    /**
     * @param object $entity
     *
     * @return void
     * @author Michaël VEROUX
     */
    private function extractFromForm($entity)
    {
        $form = $this->formFactory->create($this->formName, $entity);
        $views = $form->createView()->children;
        $views = $this->unsetRemoves($views, $this->rootRemoves);

        $values = array($this->formName => array());
        $labels = $values;
        $this->extractValues($views, $values[$this->formName], $labels[$this->formName], $this->formName);


        $flattenLabels = $this->flatten($labels);
        $flattenValues = $this->flatten($values);

        $this->collection->mergeLabels($flattenLabels);
        $this->collection->addLine($flattenValues);
    }

    /**
     * @param FormView[] $formViews
     * @param array      $values
     * @param array      $labels
     * @param string     $currentKey
     *
     * @return void
     * @author Michaël VEROUX
     */
    private function extractValues($formViews, &$values, &$labels, $currentKey)
    {
        foreach ($formViews as $key => $view) {
            /** @var FormView $view */
            $formType = $this->getFormType($view);
            if ('collection' === $formType) {
                $collection = $this->extractCollection($view->children);
                $labels[$key] = $collection['label'];
                $values[$key] = $collection['value'];
            } else {
                if (in_array($formType, $this->typeRemoves)) {
                    continue;
                }
                $displayValue = $this->getDisplayValue($formType, $view);
                if (!is_string($displayValue)) {
                    $labels[$key] = $values[$key] = array();
                    $this->extractValues($view->children, $values[$key], $labels[$key], $key);
                } else {
                    $labels[$key] = $this->label->trans($view);
                    $values[$key] = $displayValue;
                }
            }
        }
    }

    /**
     * @param FormView[] $formViews
     *
     * @return array
     * @author Michaël VEROUX
     */
    private function extractCollection($formViews)
    {
        if (isset($formViews[0])) {
            $formType = $this->getFormType($formViews[0]);
        }
        $values = array();
        $subValues = array('collection' => array());
        $subLabels = $subValues;
        foreach ($formViews as $key => $formView) {
            $this->extractValues($formView->children, $subValues['collection'], $subLabels['collection'], 'collection');
            if (!isset($labels)) {
                $labels = $subLabels['collection'];
            }
            if (!count($values)) {
                $values = $subValues['collection'];
            } else {
                $values = $this->concatArrayValues($values, $subValues['collection']);
            }
        }

        return array(
            'label' => $labels,
            'value' => $values,
        );
    }

    /**
     * @param array $arraySource
     * @param array $array
     *
     * @return array
     * @author Michaël VEROUX
     */
    private function concatArrayValues($arraySource, $array)
    {
        array_walk($arraySource, function (&$value, $key) use ($array) {
            if (isset($array[$key])) {
                $converted = $array[$key];
                if (is_array($converted)) {
                    // This exists because some customs form types could not be implemented with subscribers
                    $converted = implode(PHP_EOL, $converted);
                }
                if (is_array($value)) {
                    $value = implode(PHP_EOL, $value);
                }
                $value = $value.PHP_EOL.$converted;
            }
        });

        return $arraySource;
    }

    /**
     * @param string   $type
     * @param FormView $formView
     *
     * @return string
     * @author Michaël VEROUX
     */
    private function getDisplayValue($type, FormView $formView)
    {
        $display = $formView->vars['value'];

        if ('datetime' == $type) {
            if ($formView->vars['data'] instanceof DateTime) {
                $display = $formView->vars['data']->format('d/m/Y H:i');
            }
        }

        if ('date' == $type) {
            if ($formView->vars['data'] instanceof DateTime) {
                $display = $formView->vars['data']->format('d/m/Y');
            }
        }

        if ('checkbox' == $type) {
            $display = '1' == $display ? 'Oui' : 'Non';
        }

        if ('choice' == $type) {
            $display = $this->choiceDisplay->get($formView);
        }

        if ('entity' == $type) {
            if (is_array($display)) {
                array_walk($display, function (&$value) {
                    $value = (string)$value;
                });
                $display = implode(PHP_EOL, $display);
            }
        }

        $event = new CustomTypeEvent($this->formName, $type, $formView);
        $this->eventDispatcher->dispatch(FormExportEvents::CUSTOM_TYPE_CONVERT, $event);
        if ($event->isPropagationStopped()) {
            $display = $event->getDisplayable();
        }

        if (null === $display) {
            $display = '';
        }

        return $display;
    }

    /**
     * @param FormView $formView
     *
     * @return FormView
     * @author Michaël VEROUX
     */
    private function getFormType(FormView $formView)
    {
        $keyType = count($formView->vars['block_prefixes']) - 2;
        $type = $formView->vars['block_prefixes'][$keyType];

        return $type;
    }

    /**
     * @param array $views
     * @param array $removes
     *
     * @return array
     * @author Michaël VEROUX
     */
    private function unsetRemoves($views, $removes)
    {
        $removes = array_flip($removes);
        $diff = array_diff_key($views, $removes);

        return $diff;
    }

    /**
     * @param array  $array
     * @param string $prefix
     *
     * @return array
     * @author Michaël VEROUX
     */
    private function flatten($array, $prefix = '')
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->flatten($value, $prefix.$key.'_');
            } else {
                $result[$prefix.$key] = $value;
            }
        }

        return $result;
    }
}
