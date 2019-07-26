<?php
namespace App\Models;
use System\BaseModel;
class Award extends BaseModel
{
    public function getAwards()
    {
        return $this->db->select('* FROM award ORDER BY awardDateTime DESC');
    }
    public function get_award($awardId)
    {
        $data = $this->db->select('* from award where awardId = :awardId', [':awardId' => $awardId]);
        return (isset($data[0]) ? $data[0] : null);
    }
    public function insert($data)
    {
        $this->db->insert('award', $data);
    }
    public function update($data, $where)
    {
         $this->db->update('award', $data, $where);
    }
    public function delete($where)
    {
        $this->db->delete('award', $where);
    }
    public function getAwardByAwardees($name)
    {
        return $this->db->select('awardName, count(*) AS count FROM award, awardClass WHERE award.awardClassId = awardClass.classId AND awardeeFullName = :awardeeFullName GROUP BY awardName', [':awardeeFullName' => $name]);
    }
    public function getAwardByLocation()
    {
        return $this->db->select('awardeeLocation, count(*) AS count FROM award GROUP BY awardeeLocation');
    }
    public function getAwardByManager()
    {
        return $this->db->select('awardeeManager, count(*) AS count FROM award GROUP BY awardeeManager');
    }
    
    public function getAwardByPosition()
    {
        return $this->db->select('awardeePosition, count(*) AS count FROM award GROUP BY awardeePosition');
    }

    public function getAwardValues($awardId)
    {
        $data = $this->db->select('awardClass.awardName, users.firstName, users.lastName, users.email, users.signature, award.awardDateTime, award.awardeeEmail, 
        award.awardeeFullName, award.awardeeLocation, award.awardeeManager, award.awardeePosition, award.awardFilePath, award.awardId
        FROM `award` 
        JOIN awardClass ON award.awardClassId = awardClass.classId
        JOIN users ON award.awardCreatorId = users.userId
        WHERE award.awardId = :awardId', [':awardId' => $awardId]);
        return (isset($data[0]) ? $data[0] : null);
    }

    public function getAwardsDetails()
    {
        return $this->db->select('awardClass.awardName, users.firstName, users.lastName, users.email, users.signature, award.awardDateTime, award.awardeeEmail, 
        award.awardeeFullName, award.awardeeLocation, award.awardeeManager, award.awardeePosition, award.awardFilePath, award.awardId, award.awardCreatorId
        FROM `award` 
        JOIN awardClass ON award.awardClassId = awardClass.classId
        JOIN users ON award.awardCreatorId = users.userId ORDER BY award.awardId DESC');
    }
}