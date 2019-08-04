<?php
namespace App\Models;

use System\BaseModel;

class AwardClass extends BaseModel
{
    public function getAwardClasses()
    {
        return $this->db->select('* FROM awardClass order by awardName');
    }

    public function insert($data)
    {
        $this->db->insert('awardClass', $data);
    }

    public function update($data, $where)
    {
        $this->db->update('awardClass', $data, $where);
    }

    public function delete($where)
    {
        $result = $this->db->delete('awardClass', $where);
        return $result;
    }

    public function get_awardClass($classId)
    {
        $data = $this->db->select('select c.classId, c.awardName, COUNT(*) numInUse
        FROM awardclass c
        INNER JOIN award a ON a.awardClassId = c.classId
        WHERE classId = :classId', ['classId' => $classId]);
        
        return (isset($data[0]) ? $data[0] : null);
    }
}


