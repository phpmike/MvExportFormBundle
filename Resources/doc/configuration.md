# Configuring a form export

Your form and embedded forms have to be declared as service.

## Extend export service

The service to extend depend on type export expected:

* **xml**: mv_export_form.xml_export  
* **array**: mv_export_form.array_export  
* **csv**: mv_export_form.csv_export


### Service declaration XML example

```xml
<service id="your_service.id" parent="mv_export_form.xml_export">
    <call method="setFormName">
        <argument>your_form_name</argument>
    </call>
</service>
```

### Service declaration YML example

```yml
your_service.id:
    parent: mv_export_form.xml_export
    calls:
        - [ setFormName, ['your_form_name']]
```

## Usage

You have to send the entities supported by the form you have declared like this:

```php
$this->get('your_service.id')->export($entities);
```

And get the result:

```php
$this->get('your_service.id')->getXml();
```

The service declaration example was XML, but for CSV:

```php
$this->get('your_service.id')->getCsv();
```

And Array:

```php
$this->get('your_service.id')->getArray();
```

That's all!