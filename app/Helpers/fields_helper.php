<?php
function buildFieldsName($inputs)
{
    $fields_name = [];

    foreach ($inputs as $key => $input) {
        // اول از فایل زبان بخون
        $label = lang('Fields.' . $key, [], 'fa');

        // اگه در فایل زبان نبود، از placeholder توی data بگیر
        if ($label == 'Fields.' . $key && isset($input['data']['placeholder'])) {
            $label = $input['data']['placeholder'];
        }

        // اگه بازم نشد، از خود کلید استفاده کن
        if ($label == 'Fields.' . $key) {
            $label = $key;
        }

        $fields_name[$key] = $label;
    }

    return $fields_name;
}

function mergeFieldsName($dbFields, $inputs)
{
    $customFields = buildFieldsName($inputs);
    return array_merge($dbFields, $customFields);
}
