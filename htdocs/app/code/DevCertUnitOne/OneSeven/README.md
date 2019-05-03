# DevCertUnitOne_OneSeven

## Purpose
Task for Certified Developer Study Group, Unit One, Task Seven.

## Task
Create an extension which logs every order save operation into the file. Design your extension in the way, that an error that happens in the extension won't prevent order from being saved.

### Hints
- Use event/observer
- This is about understanding the difference between events and choosing the right one for the task
- The exam tests the understanding of key events in key flows; e.g. The sales order save flow (e.g. When/where in the flow are events triggered, what objects they pass, what effects they can have on the flow if an error occurs)

- [x] Add an events.xml
- [x] Add an Event Observer class
- [x] Inject the logger
