# Autopop plugin for Craft CMS 3.x

Helps automatically populate entry content.

---

## Requirements

This plugin requires Craft CMS 3.0.0-RC1 or later.


## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require onedesign/autopop

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Autopop.


## Use

```twig
{# The initial entries to start with #}
{% set entries = craft.entries.section('news') %}

{# Returns an Element Query that includes all entries NOT in the `featuredNews` field for the passed `entry` #}
{% set populatedEntries = craft.autopop.entries({
  entries: entries,
  entry: entry,
  excludeFields: ['featuredNews']
}) %}

{# You can continue to refine this set by passing in additional arrays of entry `excludeIds` to exclude. #}
{% set populatedEntries = craft.autopop.entries({
  entries: populatedEntries,
  excludeIds: [5, 13]
}) %}

{# If you have an Entries field that may not have all of it's entries set, you can also easily "fill" all of the slots in that field #}
{% set filledEntries = craft.autopop.fillField({
  entries: populatedEntries,
  field: entry.featuredEntries
}) %}
```

Brought to you by [One Design Company](https://www.onedesigncompany.com)