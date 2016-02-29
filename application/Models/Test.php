<?php namespace application\Models;

/**
* ETAN Web Application Framework
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @copyright 2016 NateDentzau.Net
* @license https://opensource.org/licenses/MIT MIT License
*/

use \system\Model;

class Test extends Model
{
    public function getAllDays(): array
    {
        $sql = "SELECT * FROM Days";
        $result = $this->db->query($sql);

        $days = [];
        foreach ($result as $row)
        {
            $days[] = [
                "ID"    => $row["ID"],
                "Name"  => $row["Name"],
            ];
        }

        return $days;
    }

    public function __toString(): string 
    {
        $output = "";

        foreach ($this->getAllDays() as $row)
        {
            $output .= "{$row['ID']} {$row['Name']}\n";
        }

        return $output;
    }
}