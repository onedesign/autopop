<?php
/**
 * Autopop plugin for Craft CMS 3.x
 *
 * Helps automatically populate entry content.
 *
 * @link      https://onedesigncompany.com
 * @copyright Copyright (c) 2018 One Design Company
 */

namespace onedesign\autopop\variables;

use onedesign\autopop\Autopop;
use craft\elements\Entry;

use Craft;

/**
 * @author    One Design Company
 * @package   Autopop
 * @since     1.0.0
 */
class AutopopVariable
{
    /**
     * Autopopulate Entries
     *
     * @return ElementCriteriaModel
     * @param Array $options The options to filter by
     **/
    public function entries($options)
    {
        $elementQuery = $options['entries'];
        $excludedIds = [];

        // Add explictly excluded ids
        if (isset($options['excludeIds'])) {
            $excludedIds = $options['excludeIds'];
        }

        // Entries
        if (isset($options['entry']) && isset($options['excludeFields'])) {
            $entry = $options['entry'];
            $fields = $options['excludeFields'];
            foreach ($fields as $field) {
                // Attempt to find the field on the entry
                if (isset($entry[$field])) {
                    // Does the field have length?
                    $entryField = $entry[$field];
                    if (count($entryField)) {
                        // Add the id for each field
                        foreach ($entryField as $fieldEntry) {
                            $fieldId = $fieldEntry['id'];
                            array_push($excludedIds, $fieldId);
                        }
                    }
                }
            }
        }

        // Remove excluded ids
        $excludedIdsString = 'not ' . implode(', not ', $excludedIds);
        if ($elementQuery->id) {
            $elementQuery->id = $elementQuery->id . ', ' . $excludedIdsString;
        } else {
            $excludedIdsString = 'and, ' . $excludedIdsString;
            $elementQuery->id = $excludedIdsString;
        }

        return $elementQuery;
    }

    /**
     * Fill Field
     *
     * @return ElementCriteriaModel
     * @param Array $options The options to filter by
     **/
    public function fillField($options)
    {
        $elementQuery = $options['entries'];
        $fieldQuery = Entry::find();
        $filledIds = [];

        // Add explictly excluded ids
        if (isset($options['excludeIds'])) {
            $excludedIds = $options['excludeIds'];
            $elementQuery = $this->entries([
                'entries' => $elementQuery,
                'excludeIds' => $excludedIds
            ]);
        }

        // Fill up empty slots in the field
        if (isset($options['field'])) {
            $field = $options['field'];
            for ($idx = 0; $idx< $field->limit; $idx++) {
                $fieldId = null;
                if (isset($field[$idx])) {
                    $fieldId = $field[$idx]['id'];
                } else {
                    $fieldId = $elementQuery->all()[0]['id'];
                }
                array_push($filledIds, $fieldId);
                $elementQuery = $this->entries([
                    'entries' => $elementQuery,
                    'excludeIds' => [$fieldId]
                ]);
            }
        }

        $fieldQuery->id = $filledIds;
        $fieldQuery->fixedOrder = true;

        return $fieldQuery;
    }
}