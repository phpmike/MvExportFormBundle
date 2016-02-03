# Events 

You can subscribe to events for support more form types and to ignore some field. 

## CustomTypeEvent

You have to subscribe to mv_export_form.type_convert.event  
This event give access to FormView object and you have to set displayable value that will be used in export. 

## RemoveTypeEvent

You have to subscribe to mv_export_form.remove_type.event  
Don't forget to verifie that you work with concerned form and set the fields you don't want to export.