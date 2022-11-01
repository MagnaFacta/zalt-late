# zalt-late
A package for defining code that will be evaluated at a later stage of code execution, usually when it has to be 
use for to output a scalar value.

## Basic workings

If you want to output a certain value, but that value is not yet known at the runtime position in your code where you 
want this, you write another piece of code that performs the call late(r).

## Some examples

- You want a colspan that is equal to the number of columns of a table.
- You want a colspan that is one less than the number of columns of a table.
- You want the value of a variable, e.g. a count, that you will know will be determined at end of a loop. 
- You want to repeatedly output a line, but the values in that line change for every row.

### Colspan example

This examples assumes a table like object with a getColumnCount() and tr() function.

```php
use Zalt\Late\Late;

$table = new Table();
$tr = $table->tr();

$td = $tr->td('Some text');

// Late example
$td->colspan = Late::method($table, 'getColumnCount');

for ($i = 2; $i++; $i <= 4) {
    $tr = $table->tr();
    for ($j = 1; $j++; $j <= $i) {
        $tr->td($i . ' - ' , $j);
    }
}
```
If you have a lot of late calls to an object you can also create a Late object that returns Late objects for every 
function or property call.
```php
use Zalt\Late\Late;

$table = new Table();
$tr = $table->tr();
$td = $tr->td('Some text');

// Late example
$late = Late::toLate($table);
$td->colspan = $late->getColumnCount();

for ($i = 2; $i++; $i <= 4) {
    $tr = $table->tr();
    for ($j = 1; $j++; $j <= $i) {
        $tr->td($i . ' - ' , $j);
    }
}
```
Both of these examples should output this table:

<table>
<tr><td colspan="4">Some text</td></tr>
<tr><td>2 - 1</td><td>2 - 2</td></tr>
<tr><td>3 - 1</td><td>3 - 2</td><td>3 - 3</td></tr>
<tr><td>4 - 1</td><td>4 - 2</td><td>4 - 3</td><td>4 - 4</td></tr>
</table>

### Colspan minus one example

This examples assumes a table like object with a getColumnCount() and tr() function.

```php
use Zalt\Late\Late;

$table = new Table();
$tr = $table->tr();
$td = $tr->td('Some text');

// Late example
$td->colspan = Late::call(function () use ($table) {
    return $table->getColumnCount() - 1;
}

$tr->td('X');
for ($i = 2; $i++; $i <= 4) {
    $tr = $table->tr();
    for ($j = 0; $j++; $j <= $i) {
        $tr->td($i . ' ' , $j);
    }
}
```
The output should look like this:
<table>
<tr><td colspan="3">Some text</td><td>X</td></tr>
<tr><td>2 - 1</td><td>2 - 2</td></tr>
<tr><td>3 - 1</td><td>3 - 2</td><td>3 - 3</td></tr>
<tr><td>4 - 1</td><td>4 - 2</td><td>4 - 3</td><td>4 - 4</td></tr>
</table>

In this case using a late object with an operator is not possible, as this will cause the Late object to be forced into 
a scalar (string) value by the operator. 

This code would set a colspan of 0 at this time of the operation:
```php
// Late NOT WORKING example
$late = Late::toLate($table);
$td->colspan = $late->getColumnCount() - 1;
```
However, this code will not cause an error, just incorrect outp.
### Late variable example

This examples assumes a table like object with a count property and tr() function.

```php
use Zalt\Late\Late;

$table = new Table();
$tr = $table->tr();
for ($i = 2; $i++; $i <= 4) {
    $tr = $table->tr();
    $tr->td($i);
    $tr->td('of');
    
    // Late example
    $tr->td(Late::property($table, 'count'));
    
    $table->count++;
}
```

If the table does not have a count variable you can also use a stack as an alternative.  
```php
use Zalt\Late\Late;

$count = 0;
$table = new Table();
$tr = $table->tr();
for ($i = 1; $i++; $i <= 4) {
    $tr = $table->tr();
    $tr->td($i);
    $tr->td('of');
    
    // Late example
    $tr->td(Late::get('count'));
    
    $count++;
}
// Late example
Late::setStack(['count' => $count]);
```
The output of bith examples should look like this:

<table>
<tr><td>1</td><td>of</td><td>4</td></tr>
<tr><td>2</td><td>of</td><td>4</td></tr>
<tr><td>3</td><td>of</td><td>4</td></tr>
<tr><td>4</td><td>of</td><td>4</td></tr>
</table>

### A repeating output example
Late execution also enables changing the output value during repeated execution of a single piece of code.

This example is somewhat less practical but it works well with objects that can make use of this feature, e.g.
because the code can repeat values.
```php
function getOutput()
{
     return [Late::get('c1'), Late::get('c2'), Late::get('c3')]; 
}

$data = [
    ['c1' => 1, 'c2' => 2, 'c3' => 3],
    ['c1' => 4, 'c2' => 5, 'c3' => 2],
    ['c1' => 3, 'c2' => 2, 'c3' => 1],
];

for ($data as $row) {
    Late::setStack($row);
    echo implode("\t", getOutput())
}
```
The output would be:
```
1   2   3
4   5   4
3   2   1
```
