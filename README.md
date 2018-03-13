# Keboola Csv2Xml Processor

Convert csv files to xml files.

## Functionality

Processor takes all files in input directory `/data/in/files` (and all subdirectories) that have `.csv`
extension and transfers them to `.xml` files.

## Configuration

Example processor configuration:
```
{
    "definition": {
        "component": "jakub-bartel.processor-csv2xml"
    },
    "parameters": {
        "root_node": "eshop",
        "item_node": "product"
    }
}
```

For input csv:
```
"name","color","size","price"
"XY","Blue","123","34"
"ZW","Red","11","6"
```

the processed xml will look like:
```
<?xml version="1.0" encoding="UTF-8"?>
<eshop>
    <product>
        <name>XY</name>
        <color>Blue</color>
        <size>123</size>
        <price>34</price>
    </product>
    <product>
        <name>ZW</name>
        <color>Red</color>
        <size>11</size>
        <price>6</price>
    </product>
</eshop>
```
