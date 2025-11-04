# Web app service: VeilData utility

The `Bayfront\BonesService\WebApp\Utilities\VeilData` utility class is used to work with the data array from within a Veil view file.
Using this utility class eliminates the need to check if a `$data` property exists from within Veil views.

The Web app service automatically sets the entire `$data` array using the `webapp.response.data` [filter](filters.md).

The following constants are included, which can be helpful to pass to the view:

- `ACTION_CREATE`
- `ACTION_READ`
- `ACTION_LIST`
- `ACTION_EDIT`
- `ACTION_DELETE`

All methods are static.

- [set](#set)
- [getData](#getdata)
- [get](#get)
- [has](#has)
- [sanitize](#sanitize)

## set

**Description:**

Set data.

**Parameters:**

- `$array` (array)

**Returns:**

- (void)

## getData

**Description:**

Get entire data array.

**Parameters:**

- `$sanitize = true` (bool)

**Returns:**

- (array)

## get

**Description:**

Get a data item using "dot" notation,
returning an optional default value if not found.

**Parameters:**

- `$key` (string): Key to return in "dot" notation
- `$default = null` (mixed): Default value to return
- `$sanitize = true` (bool)

**Returns:**

- (mixed)

## has

**Description:**

Checks if data key exists and not null using "dot" notation.

**Parameters:**

- `$key` (string)

**Returns:**

- (bool)

## sanitize

**Description:**

Sanitize strings and arrays for output to a Veil template.

**Parameters:**

- `$data` (mixed)

**Returns:**

- (mixed)