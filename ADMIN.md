---

### Available Settings

#### URL Match RegExp

Defines a [regular expression](http://regular-expressions.info) that will reliably match a segment of your image filenames that can be rewritten to translate a full-res image URL into one appropriate for a splash image.

#### URL Replacement Value

Defines the string to replace the matched segment with. Matched groups can be referenced via $1, $2, etc.

---

#### Examples

**This example of the simplest possible use case translates the url fragment `full` to `thumb`:**

- URL Match RegExp: `/full/`
- URL Replacement Value: `thumb`

**The default matches a filename like `FECHearing-300` and translates it into `FECHearing-180sq`:**
    
- URL Match RegExp: `/([\w-%]+)-[\d]+(sq)?/`
- URL Replacement Value: `$1-180sq`

---
