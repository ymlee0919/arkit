***

# PdfBuilder

Class for building a pdf from a web page



* Full name: `\Arkit\Services\Pdf\PdfBuilder`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`PDF_FORMAT_A4`|public| |&#039;A4&#039;|
|`PDF_FORMAT_E4`|public| |&#039;E4&#039;|
|`PDF_FORMAT_RA4`|public| |&#039;RA4&#039;|
|`PDF_FORMAT_SRA4`|public| |&#039;SRA4&#039;|
|`PDF_FORMAT_SUPER_A4`|public| |&#039;SUPER_A4&#039;|
|`PDF_FORMAT_A4_LONG`|public| |&#039;A4_LONG&#039;|
|`PDF_FORMAT_F4`|public| |&#039;F4&#039;|
|`PDF_FORMAT_P4`|public| |&#039;P4&#039;|
|`PDF_FORMAT_LETTER`|public| |&#039;LETTER&#039;|
|`PDF_FORMAT_LEGAL`|public| |&#039;LEGAL&#039;|
|`PDF_FORMAT_GOVERNMENTLETTER`|public| |&#039;GOVERNMENTLETTER&#039;|
|`PDF_FORMAT_GOVERNMENTLEGAL`|public| |&#039;GOVERNMENTLEGAL&#039;|
|`PDF_ORIENTATION_PORTRAIT`|public| |&#039;P&#039;|
|`PDF_ORIENTATION_LANDSCAPE`|public| |&#039;L&#039;|
|`PDF_ORIENTATION_AUTO`|public| |&#039;&#039;|
|`PDF_UNIT_POINT`|public| |&#039;pt&#039;|
|`PDF_UNIT_MILLIMETER`|public| |&#039;mm&#039;|
|`PDF_UNIT_CENTIMETER`|public| |&#039;cm&#039;|
|`PDF_UNIT_INCH`|public| |&#039;cm&#039;|
|`PDF_FONT_AEFURAT`|public| |&#039;aefurat&#039;|
|`PDF_FONT_DEJAVUSANS`|public| |&#039;dejavusans&#039;|
|`PDF_FONT_DEJAVUSANSMONO`|public| |&#039;dejavusansmono&#039;|
|`PDF_FONT_DEJAVUSERIF`|public| |&#039;dejavuserif&#039;|
|`PDF_FONT_FREEMONO`|public| |&#039;freemono&#039;|
|`PDF_FONT_FREESANS`|public| |&#039;freesans&#039;|
|`PDF_FONT_FREESERIF`|public| |&#039;freeserif&#039;|
|`PDF_FONT_HELVETICA`|public| |&#039;helvetica&#039;|
|`PDF_FONT_PDFACOURIER`|public| |&#039;pdfacourier&#039;|
|`PDF_FONT_PDFAHELVETICA`|public| |&#039;pdfahelvetica&#039;|
|`PDF_FONT_PDFATIMES`|public| |&#039;pdfatimes&#039;|
|`PDF_FONT_TIMES`|public| |&#039;times&#039;|


## Methods


### __construct



```php
public __construct(string $orientation, string $format, string $language = &#039;en&#039;, array $margin = [7, 7, 7, 10]): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$orientation` | **string** |  |
| `$format` | **string** |  |
| `$language` | **string** |  |
| `$margin` | **array** |  |





***

### setFont



```php
public setFont(string $fontName): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fontName` | **string** |  |





***

### writeHTML



```php
public writeHTML(string $html): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$html` | **string** |  |





***

### addPage



```php
public addPage(): void
```












***

### save



```php
public save(string $fileName): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fileName` | **string** |  |




**Throws:**

- [`HTML2PDF_exception`](../../../HTML2PDF_exception.md)



***

### display



```php
public display(string $outputName): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$outputName` | **string** |  |




**Throws:**

- [`HTML2PDF_exception`](../../../HTML2PDF_exception.md)



***

### download



```php
public download(string $outputName): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$outputName` | **string** |  |




**Throws:**

- [`HTML2PDF_exception`](../../../HTML2PDF_exception.md)



***


***
> Automatically generated on 2023-12-13
