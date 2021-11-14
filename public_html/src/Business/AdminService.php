<?PHP

namespace src\Business;

use src\Data\AdminDAO;

class AdminService
{
    private $data;
    public $table;
    
    public function __construct($table = "",$entity = "")
    {
        $this->data = new AdminDAO($table, $entity);
        $this->table = $table;
    }
    
    public function __destruct()
    {
        $this->data = null;
        $this->table = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function getTableRows($from = 0, $to = 15, $onlyFields = FALSE)
    {
        return $this->data->getTableRows($from, $to, $onlyFields);
    }
    
    public function getTableRowById($id)
    {
        return $this->data->getTableRowById($id);
    }
    
    public function getTableRowByMemberId($memberID)
    {
        return $this->data->getTableRowByMemberId($memberID);
    }
    
    public function sortRows($data)
    {
        return $this->data->sortRows($data);
    }
    
    public function activateRow($id)
    {
        return $this->data->activateRow($id);
    }
    
    public function deactivateRow($id)
    {
        return $this->data->deactivateRow($id);
    }
    
    public function getValidTables()
    {
        return $this->data->getValidTables();
    }
    
    public function deleteRow($id)
    {
        return $this->data->deleteRow($id);
    }
    
    public function editRow($id)
    {
        return $this->data->editRow($id);
    }
    
    public function saveEditedRow($post, $id, $files = false)
    {
        return $this->data->saveEditedRow($post, $id, $files);
    }
    
    public function createToEditRow()
    {
        return $this->data->createToEditRow();
    }
    
    public function getLastInsertId()
    {
        return $this->data->getLastInsertId();
    }
    
    public function getLastRecord()
    {
        return $this->data->getLastRecord();
    }
    
    public function searchRecords($search,$searchBy,$fields,$skipFields)
    {
        return $this->data->searchRecords($search,$searchBy,$fields,$skipFields);
    }
    
    public function getCMSTableRowByUserId($id)
    {
        return $this->data->getCMSTableRowByUserId($id);
    }
    
    public function resetMafiasource($data, $nextRoundStartDate = "now")
    {
        return $this->data->resetMafiasource($data, $nextRoundStartDate);
    }
}
