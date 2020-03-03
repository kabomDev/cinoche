<?php
// 
class SalleModel extends AbstractModel
{
    protected $database;

    public function FindAllSalles()//$queryFields
    {
       
        $sql =
        "SELECT * FROM salle";
        return $this->database->query($sql);
    }
}