# TYPO3 Extension `container_modify_fields`

This extensions makes it possible to modify the TCA of container children elements.

Examples:

- The content element "text" shouldn't have a field "header_link" if inside a container
- The header of content element "header" must be required if in colPos "100" of a container.

## Usage:

Install with `composer req georgringer/container-modify-fields`.

## Configuration

The configuration must be done in PageTsConfig with the following syntax:

```
    TCEFORM.tt_content.container {
        <container-ctype>.<colpos>.<child-ctype>.<field> {
            # currently supported is only:
            required = 1
            disabled = 1
        }

        # Instead of <colpos> and <child-ctype> also "_all" is valid
    }
```

### Example 1

> The behaviour of elements inside container "b13-2cols-with-header-container" is changed:
> - All elements: No header_link field
> - Element "text": No header field

```
TCEFORM.tt_content.container {
  b13-2cols-with-header-container {
    _all {
      _all {
        header_link.disabled = 1
      }
      text {
        header.disabled = 1
      }
    }
```


### Example 2

> The behaviour of elements inside container "b13-2cols-with-header-container" is changed:
> - The header element inside colPos "200" is set to required

```
TCEFORM.tt_content.container {
  b13-2cols-with-header-container {
   200 {
      header {
        header.required = 1
      }
    }
  }
}

```
