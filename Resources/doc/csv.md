# Override CSV class

You can override the csv class used to write csv.  
By default csv is written in memory, maybe some case needs to do in other way.

## Create your class and implement Mv\ExportFormBundle\Tool\CsvInterface

```php
use Mv\ExportFormBundle\Tool\CsvInterface;

class MyCustomCsv implements CsvInterface
{
    /**
     * @param array $csv
     *
     * @return void
     */
    public function putCsv(array $csv)
    {
        // Do something with this array
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        // Return CSV data as string
    }
}
```

## Change your service declaration

Before, you have:

```xml
<service id="your_service.id" parent="mv_export_form.csv_export">
    <call method="setFormName">
        <argument>your_form_name</argument>
    </call>
</service>
```

After, you will have (use full namespace of you custom class):

```xml
<service id="your_service.id" parent="mv_export_form.xml_export">
    <call method="setFormName">
        <argument>your_form_name</argument>
    </call>
    <call method="setCsvClassName">
        <argument>MyCustomCsv</argument>
    </call>
</service>
```
