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

/**
 * Class FormExportEvents
 *
 * @package Mv\ExportFormBundle\Event
 * @author Michaël VEROUX
 */
class FormExportEvents
{
    const CUSTOM_TYPE_CONVERT = 'mv_export_form.type_convert.event';
    const REMOVE_TYPE = 'mv_export_form.remove_type.event';
}
