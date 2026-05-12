<?php

namespace App\Models;

use CodeIgniter\Model;

class FieldModel extends Model {

    public function getFieldName($tables)
    {
        $output = [];

        $isSQLite = (strpos($this->db->getPlatform(), 'SQLite') !== false);

        foreach ($tables as $table) {
            $tableName = $this->db->prefixTable($table);

            if ($isSQLite) {
                $sql = "PRAGMA table_info('" . $tableName . "')";
                $query = $this->db->query($sql);

                if ($query) {
                    $result = $query->getResultArray();
                    foreach ($result as $row) {
                        $fieldName = $row['name'];
                        $label = lang('Fields.' . $fieldName, [], 'fa');
                        $output[$fieldName] = ($label != 'Fields.' . $fieldName) ? $label : $fieldName;
                    }
                }
            } else {
                // MySQL
                $query = $this->db->query("SHOW FULL COLUMNS FROM `" . $tableName . "`");
                $result = $query->getResultArray();
                $output = array_merge($output, array_column($result, 'Comment', 'Field'));
            }
        }

        return $output;
    }
}