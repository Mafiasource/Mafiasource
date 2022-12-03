<?PHP

namespace src\Data;

use src\Data\MemberDAO;
use src\Data\StatisticDAO;
use src\Data\config\DBConfig;
use vendor\SimpleImage;

use DateTime;

class AdminDAO extends DBConfig
{
    protected $con = "";
    protected $database = "";
    private $dbh = "";
    private $table = "";
    private $phpDateFormat = "Y-m-d H:i:s";
    private $denySaves = array("login", "login_fail");
    public $validTables = array(
        "cms", "member", "status", "user", "donator", "profession", "state", "city", "news", "ground", "ground_building", "rld_whore", "weapon", "protection", "airplane",
        "helpsystem", "vehicle", "residence", "crime", "crime_org", "steal_vehicle", "possess", "possession", "forum_category", "forum_status", "forum_topic",
        "forum_reaction", "smuggle", "detective", "family", "family_alliance", "family_bf_donation_log", "family_bf_send_log", "family_brothel_whore", "family_garage",
        "family_join_invite", "family_mercenary_log", "family_raid", "garage", "gym_competition", "user_garage", "market", "message", "murder_log", "poll_answer",
        "poll_question", "poll_vote", "seo", "shoutbox_nl", "shoutbox_en", "user_friend_block", "round", "login", "login_fail", "ip_ban"
    );
    
    public function __construct($table = "")
    {
        global $connection;
        $this->con = $connection;
        $this->dbh = $connection->con;
        $this->table = $table;
        $this->database = $connection->database;
    }
    
    public function __destruct()
    {
        $this->dbh = $this->con = null;
    }
    
    public function getRecordsCount()
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $statement = $this->dbh->prepare("SELECT COUNT(*) AS `total` FROM `$this->table` WHERE `deleted` = 0 ORDER BY `position` ASC");
            $statement->execute();
            $row = $statement->fetch();
            if(isset($row['total'])) return $row['total'];
        }
    }
    
    public function getLastInsertId()
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $statement = $this->dbh->prepare("SELECT `id` FROM `$this->table` ORDER BY `id` DESC LIMIT 1");
            $statement->execute();
            $row = $statement->fetch();
            if(isset($row['id']) && $row['id'] > 0)
                $id = $row['id']+1;
            else
                $id = 1;
            return $id;
        }
    }
    
    public function getLastRecord()
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $statement = $this->dbh->prepare("SELECT * FROM `$this->table` WHERE `active`='1' AND `deleted` = '0' ORDER BY `id` DESC LIMIT 1");
            $statement->execute();
            $list = array();
            $list = $this->checkFieldsComments($list, $statement);
            return $list;
        }
    }
    
    public function getTableRows($from = 0, $to = 15, $onlyFields = FALSE)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            if(is_array($onlyFields))
            {
                if(!in_array('position', $onlyFields)) $onlyFields[] = "position";
                if(!in_array('active', $onlyFields)) $onlyFields[] = "active";
                if(!in_array('deleted', $onlyFields)) $onlyFields[] = "deleted";
                $fields = "";
                foreach($onlyFields AS $field)
                    $fields .= "`".$field."`, ";
                
                $fields = substr($fields, 0, -2);
            }
            else
                $fields = "*";
                
            $statement = $this->dbh->prepare("SELECT $fields FROM `$this->table` WHERE `deleted` = '0' ORDER BY `position` ASC, `id` ASC LIMIT $from,$to");
            $statement->execute();
            $list = array();
            $list = $this->checkFieldsComments($list, $statement);
            return $list;
        }
    }
    
    public function getTableRowById($id)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $statement = $this->dbh->prepare("SELECT * FROM `$this->table` WHERE `deleted` = '0' AND `id`=:id");
            $statement->execute(array(':id' => $id));
            $list = array();
            $list = $this->checkFieldsComments($list, $statement);
            return $list;
        }
    }
    
    public function getTableRowByMemberId($id)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $statement = $this->dbh->prepare("SELECT * FROM `$this->table` WHERE `deleted` = '0' AND `memberID`=:id");
            $statement->execute(array(':id' => $id));
            $list = array();
            $list = $this->checkFieldsComments($list, $statement);
            return $list;
        }
    }
    
    private function checkFieldsComments($list, $statement, $type = 'rows')
    {
        foreach($statement AS $key => $val)
        {
            $arr = array();
            foreach($val AS $fieldKey => $value)
            {
                $statement = $this->dbh->prepare("SELECT c.`COLUMN_COMMENT` FROM `information_schema`.`COLUMNS` AS c WHERE c.`TABLE_SCHEMA` ='$this->database' AND c.`TABLE_NAME` = '$this->table' AND c.`COLUMN_NAME` = :key ");
                $statement->execute(array(':key' => $fieldKey));
                $row = $statement->fetch(\PDO::FETCH_ASSOC);
                
                if(isset($row['COLUMN_COMMENT']) && $row['COLUMN_COMMENT'] != '')
                {
                    switch($type)
                    {
                        default:
                        case 'rows':
                            $val =  $this->checkCommentsForFieldInRow($row, $fieldKey, $value, $val);
                            break;
                        case 'edit':
                            $val = $this->checkCommentsForFieldInEdit($row, $fieldKey, $value, $val, $key);
                            break;
                    }
                }
                unset($row);
            }
            $arr[$key] = $val;
            array_push($list, $arr);
        }
        return $list;
    }
    
    private function checkCommentsForFieldInRow($row, $fieldKey, $value, $val)
    {
        if(strpos($row['COLUMN_COMMENT'], '&'))
        {
            $comments = explode('&', $row['COLUMN_COMMENT']);
            foreach($comments AS $comment)
            {
                $split = explode('=', $comment);
                $cPar = $split[0];
                $cVal = $split[1];
                if(strpos($cVal, ',')) $multipleVals = explode(',', $cVal);
                
                if($cPar == 'couple') $koppel = $cVal;
                if(isset($koppel) && $koppel != '' && $cPar == 'factor') $factor = $cVal;
                if(isset($koppel) && $koppel != '' && isset($factor) && $factor != '' && $cPar == 'show') $show = $cVal;
                if(isset($koppel) && $koppel != '' && isset($factor) && $factor != '' && isset($show) && strpos($show, ',') !== FALSE) $showMore = explode(',', $show);
            }
            if(isset($show) && $show != '' && !isset($showMore))
            {
                $statement = $this->dbh->prepare("SELECT `$show` FROM `$koppel` WHERE `$factor` = :factorId AND `active`='1' AND (`deleted`='0' OR `deleted`='-1')");
                $statement->execute(array(':factorId' => $value));
                $row = $statement->fetch();
                if(isset($row[$show])) $val[$fieldKey] = $row[$show];
                unset($show);
            }
            elseif(isset($show) && $show != '' && isset($showMore))
            {
                $statement = $this->dbh->prepare("SELECT `$showMore[0]`, `$showMore[1]` FROM `$koppel` WHERE `$factor` = :factorId  AND `active`='1' AND (`deleted`='0' OR `deleted`='-1')");
                $statement->execute(array(':factorId' => $value));
                $row = $statement->fetch();
                if(isset($row[$showMore[0]]) && isset($row[$showMore[1]])) $val[$fieldKey] = $row[$showMore[0]]. " ".$row[$showMore[1]];
                if(isset($showMore[2])) $val[$fieldKey] .= " ,..";
                unset($showMore);
                unset($show);
            }
        }
        else
        {
            $split = explode('=',$row['COLUMN_COMMENT']);
            $cPar = $split[0];
            $cVal = $split[1];
            if(strpos($cVal,',')) $multipleVals = explode(',',$cVal);
            
            if($cPar == 'select' && isset($multipleVals) && !empty($multipleVals)) $select = $multipleVals;
            
            if($cPar == 'select' && isset($select))
            {
                if(is_numeric($value) && $value >= 1)
                    $val[$fieldKey] = $select[$value-1];
            }
            elseif($cPar == 'type' && $cVal == 'yesno')
            {
                if($value == 0) $val[$fieldKey] = "Nee";
                if($value == 1) $val[$fieldKey] = "Ja";
            }
        }
        return $val;
    }
    
    private function checkCommentsForFieldInEdit($row, $fieldKey, $value, $val, $key)
    {
        if(strpos($row['COLUMN_COMMENT'], '&'))
        {
            $comments = explode('&', $row['COLUMN_COMMENT']);
            foreach($comments AS $comment)
            {
                $split = explode('=', $comment);
                $cPar = $split[0];
                $cVal = $split[1];
                if(strpos($cVal, ',')) $multipleVals = explode(',', $cVal);
                
                if($cPar == 'couple') $koppel = $cVal;
                if(isset($koppel) && $koppel != '' && $cPar == 'factor') $factor = $cVal;
                if(isset($koppel) && $koppel != '' && isset($factor) && $factor != '' && $cPar == 'show') $show = $cVal;
                if(isset($koppel) && $koppel != '' && isset($factor) && $factor != '' && isset($show) && strpos($show, ',') !== FALSE) $showMore = explode(',', $show);
                
                if($cPar == 'type' && $cVal == 'upload') $upload = $cVal;
                if(isset($upload) && $upload != '' && $cPar == 'width') $width = $cVal;
                if(isset($upload) && $upload != '' && isset($width) && $width != '' && $cPar == 'height') $height = $cVal;
            }
            if(isset($show) && $show != '' && !isset($showMore))
            {
                $statement = $this->dbh->prepare("SELECT `$factor`,`$show` FROM `$koppel` WHERE `active`='1' AND (`deleted`='0' OR `deleted`='-1') ORDER BY `position` ASC");
                $statement->execute();
                $koppelArr = array();
                foreach($statement AS $row)
                {
                    $koppelArr[$row[$factor]] = $row[$show];
                    if($row[$factor] == $val[$fieldKey])
                        $koppelArr[$row[$factor]] = array($row[$show] => "checked");
                }
                $val[$fieldKey] = array('couple' => $koppelArr);
                unset($show);
            }
            elseif(isset($show) && $show != '' && isset($showMore))
            {
                $statement = $this->dbh->prepare("SELECT `$factor`,`$showMore[0]`,`$showMore[1]` FROM `$koppel` WHERE `active`='1' AND (`deleted`='0' OR `deleted`='-1') ORDER BY `position` ASC");
                $statement->execute();
                $koppelArr = array();
                foreach($statement AS $row)
                {
                    $koppelArr[$row[$factor]] = $row[$showMore[0]]." ".$row[$showMore[1]];
                    if($row[$factor] == $val[$fieldKey])
                        $koppelArr[$row[$factor]] = array("".$row[$showMore[0]]." ".$row[$showMore[1]]."" => "checked");
                }
                $val[$fieldKey] = array('couple' => $koppelArr);
                unset($showMore);
                unset($show);
            }
            elseif(isset($upload) && isset($width) && is_numeric($width) && !isset($height))
            {
                $uploadArr = array('upload' => $value, 'imageWidth' => $width);
                $val[$fieldKey] = array('uploadWithSize' => $uploadArr);
            }
            elseif(isset($upload) && isset($height) && is_numeric($height) && !isset($width))
            {
                $uploadArr = array('upload' => $value, 'imageHeight' => $height);
                $val[$fieldKey] = array('uploadWithSize' => $uploadArr);
            }
            elseif(isset($upload) && isset($width) && is_numeric($width) && isset($height) && is_numeric($height))
            {
                $uploadArr = array('upload' => $value, 'imageWidth' => $width, 'imageHeight' => $height);
                $val[$fieldKey] = array('uploadWithSize' => $uploadArr);
            }
        }
        else
        {
            $split = explode('=', $row['COLUMN_COMMENT']);
            $cPar = $split[0];
            $cVal = $split[1];
            if(strpos($cVal, ',')) $multipleVals = explode(',', $cVal);
            
            if($cPar == 'select' && isset($multipleVals) && !empty($multipleVals)) $select = $multipleVals;
            
            if($cPar == 'select' && isset($select))
            {
                foreach(array_keys($select) AS $key) if($key + 1 == $value) $select[$key] = array($select[$key] => "checked");
                $val[$fieldKey] = array('select' => $select);
            }
            elseif($cPar == 'type' && $cVal == 'disabled')
                $val[$fieldKey] = array('disabled' => $value);
            elseif($cPar == 'type' && $cVal == 'cms')
                $val[$fieldKey] = array('cms' => $value);
            elseif($cPar == 'type' && $cVal == 'yesno')
                $val[$fieldKey] = array('yesno' => $value);
            elseif($cPar == 'type' && $cVal == 'upload')
                $val[$fieldKey] = array('upload' => $value);
        }
        return $val;
    }
    
    public function sortRows($data)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables) && !in_array($this->table, $this->denySaves))
        {
            foreach($data AS $key => $val)
            {
                $statement = $this->dbh->prepare("UPDATE `$this->table` SET `position`=:val WHERE `deleted` = '0' AND `id`=:key");
                $statement->execute(array(':val' => $val, ':key' => $key));
            }
            return TRUE;
        }
        else
            return FALSE;
    }
    
    public function activateRow($id)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables) && !in_array($this->table, $this->denySaves))
        {
            $id = (int)$id;
            $statement = $this->dbh->prepare("UPDATE `$this->table` SET `active` = '1' WHERE `id` = :id AND `active`='0'");
            if($statement->execute(array(':id' => $id)))
                return TRUE;
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    
    public function deactivateRow($id)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables) && !in_array($this->table, $this->denySaves))
        {
            $id = (int)$id;
            $statement = $this->dbh->prepare("UPDATE `$this->table` SET `active` = '0' WHERE `id` = :id AND `active`='1' AND `deleted`!='-1'");
            if($statement->execute(array(':id' => $id)))
                return TRUE;
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    
    public function deleteRow($id)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables) && !in_array($this->table, $this->denySaves))
        {
            $id = (int)$id;
            $statement = $this->dbh->prepare("UPDATE `$this->table` SET `deleted` = '1' WHERE `id` = :id AND `deleted`='0'");
            if($statement->execute(array(':id' => $id)))
                return TRUE;
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    
    public function editRow($id)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $statement = $this->dbh->prepare("SELECT * FROM `$this->table` WHERE `id`=:id AND `deleted` = '0'");
            $statement->execute(array(':id' => $id));
            $list = array();
            $list = $this->checkFieldsComments($list, $statement, 'edit');
            return $list;
        }
    }
    
    public function saveEditedRow($post, $id, $files = false)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table, $this->validTables) && !in_array($this->table, $this->denySaves))
        {
            $editStr = "";
            $parArr = array();
            $denyFields = array("id", "password", "memberID", "active", "position", "deleted", "uploadDir", "imageWidth", "imageHeight");
            
            if(isset($post['uploadDir']))
                $uploadDir = $post['uploadDir'];
            else
                $uploadDir = "uploads";
            if(isset($post['imageWidth'])) $imageWidth = $post['imageWidth'];
            if(isset($post['imageHeight'])) $imageHeight = $post['imageHeight'];
            include(DOC_ROOT . '/vendor/SimpleImage.php');
            foreach($post AS $key => $value)
            {
                if(isset($files[$key]) && $files[$key]['error'] == UPLOAD_ERR_OK)
                {
                    if (!file_exists(DOC_ROOT . '/web/public/images/'.$uploadDir))
                        mkdir(DOC_ROOT . '/web/public/images/'.$uploadDir, 0777, true);
                    
                	$UploadDirectory	= DOC_ROOT . '/web/public/images/'.$uploadDir.'/';
                    
                	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
                		$error = "Invalid headers encountered";
                	
                	if ($files[$key]['size'] > 5242880)
                		$error = "File size to big";
                	
                	switch(strtolower($files[$key]['type']))
                	{
                        case 'image/png': 
                        case 'image/gif': 
                        case 'image/jpeg': 
                        case 'image/pjpeg':
                        break;
                        default:
                       	$error = "Invalid file type discovered.";
                	}
                    if(!isset($error))
                    {
                        $File_Name          = strtolower($files[$key]['name']);
                    	$File_Ext           = substr($File_Name, strrpos($File_Name, '.'));
                    	$fileName           = substr(md5(uniqid(rand(), true)),0,8);
                    	$NewFileName 		= $fileName.$File_Ext;
                        
                        if (!empty($value) && file_exists(DOC_ROOT . '/web/public/images/'.$uploadDir.'/'.$value))
                            unlink(DOC_ROOT . '/web/public/images/'.$uploadDir.'/'.$value);
                        
                        if (file_exists(DOC_ROOT . '/web/public/images/'.$uploadDir.'/'.$NewFileName))
                            unlink(DOC_ROOT . '/web/public/images/'.$uploadDir.'/'.$NewFileName);
                        
                        $image = new SimpleImage();
                        $image->load($files[$key]['tmp_name']);
                        if(isset($imageWidth) && isset($imageHeight) && is_numeric($imageWidth) && is_numeric($imageHeight))
                            $image->resize($imageWidth,$imageHeight);
                        elseif(isset($imageWidth) && !isset($imageHeight) && is_numeric($imageWidth))
                            $image->resizeToWidth($imageWidth);
                        elseif(isset($imageHeight) && !isset($imageWidth) && is_numeric($imageHeight))
                            $image->resizeToHeight($imageHeight);
                        else
                            $image->resizeToWidth('420');
                        
                        $image->save($files[$key]['tmp_name']);
                    	
                    	if(move_uploaded_file($files[$key]['tmp_name'], $UploadDirectory.$NewFileName ))
                        {
                            chmod($UploadDirectory.$NewFileName, 0644);
                            $value = $NewFileName;
                    	}
                    }
                }
                $statement = $this->dbh->prepare("SELECT * FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` = '$this->database' AND `TABLE_NAME` = '$this->table' AND `COLUMN_NAME` = :key");
                $statement->execute(array(':key' => $key));
                if(!empty($statement) && !in_array($key, $denyFields))
                {
                    $editStr .= " `$key` = :".$key.",";
                    $arr = array(':'.$key.'' => $value);
                    $parArr = array_merge($parArr, $arr);
                }
            }
            if($editStr != '')
            {
                $editStr = rtrim($editStr, ",");
                $arr = array(':id' => $id);
                $parArr = array_merge($parArr, $arr);
                $statement = $this->dbh->prepare("UPDATE `$this->table` SET $editStr  WHERE `id` = :id");
                if($statement->execute($parArr))
                    return TRUE;
                else
                    return FALSE;
            }
            else
                return FALSE;
        }
    }
    
    public function createToEditRow()
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables) && !in_array($this->table, $this->denySaves))
        {
            $statement = $this->dbh->prepare("SELECT `position` FROM `$this->table` ORDER BY `position` DESC LIMIT 1");
            $statement->execute();
            $row = $statement->fetch();
            if(isset($row['position']) && $row['position'] > 0)
                $position = $row['position']+1;
            else
                $position = 1;
            
            $statement = $this->dbh->prepare("INSERT INTO `$this->table` (`position`) VALUES (:position)");
            if($statement->execute(array(':position' => $position)))
                return $this->editRow($this->dbh->lastInsertId());
            else
                return FALSE;
        }
    }
    
    public function searchRecords($search, $searchBy, $fields, $skipFields)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table, $this->validTables))
        {
            if($fields != FALSE)
            {
                $fieldsArr = explode(',', $fields);
                foreach($fieldsArr AS $k => $v) $fieldsArr[$k] = " '" . $v . "'";
                $fields = implode(',', $fieldsArr);
                $fields = ltrim($fields, ' ');         
            }
            elseif($skipFields != FALSE)
            {
                $skipFieldsArr = explode(',', $skipFields);
                foreach($skipFieldsArr AS $k => $v) $skipFieldsArr[$k] = " '" . $v . "'";
                $skipFields = implode(',', $skipFieldsArr);
                $fields = $skipFields = ltrim($skipFields, ' ');
            }
            $statement = $this->dbh->prepare("SELECT `COLUMN_NAME`, `COLUMN_COMMENT` FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` ='$this->database' AND `TABLE_NAME` = '$this->table' AND `COLUMN_NAME` IN ($fields)");
            $statement->execute();
            
            $searchByCouple = $searchByCoupleAndMore = false;
            $validatedFields = $coupleSelectArr = array();
            $koppel = $factor = $colName = $show = "";
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC))
            {
                if(!in_array($row['COLUMN_NAME'], $validatedFields))
                    $validatedFields[] = $row['COLUMN_NAME'];
                    
                if(!in_array($row['COLUMN_NAME'], $coupleSelectArr))
                    $coupleSelectArr[] = $row['COLUMN_NAME'];
                
                if($row['COLUMN_NAME'] == $searchBy && in_array($searchBy, $validatedFields))
                {
                    if($row['COLUMN_COMMENT'] != '')
                    {
                        if(strpos($row['COLUMN_COMMENT'], '&'))
                        {
                            $comments = explode('&', $row['COLUMN_COMMENT']);
                            foreach($comments AS $comment)
                            {
                                $split = explode('=', $comment);
                                $cPar = $split[0];
                                $cVal = $split[1];
                                
                                if($cPar == 'couple') $koppel = in_array($cVal, $this->validTables) ? $cVal : '';
                                if(isset($koppel) && $koppel != '' && $cPar == 'factor') $factor = $cVal;
                                if(isset($koppel) && $koppel != '' && isset($factor) && $factor != '' && $cPar == 'show') $show = $cVal;
                                if(isset($koppel) && $koppel != '' && isset($factor) && $factor != '' && isset($show) && strpos($show, ',') !== FALSE) $showMore = explode(',', $show);
                            }
                            if(isset($show) && $show != '' && !isset($showMore))
                            {
                                $colName = $row['COLUMN_NAME'];
                                $searchByCouple = true;
                            }
                            elseif(isset($show) && $show != '' && isset($showMore))
                            {
                                $colName = $row['COLUMN_NAME'];
                                $searchByCoupleAndMore = true;
                            }
                        }
                    }
                }
            }
            
            $by = "id";
            if(in_array($searchBy, $validatedFields)) $by = $searchBy;
            $selectFields = implode(',', $validatedFields);
            
            foreach($coupleSelectArr AS $k => $v) $coupleSelectArr[$k] = "ht." . $v;
            $coupleSelectFields = implode(',', $coupleSelectArr);
            
            if(isset($searchByCouple) && $searchByCouple == true && $by == $colName)
            {
                $searchQuery = "
                    SELECT ht.`id`, $coupleSelectFields, ht.`active`, ht.`deleted`, ht.`position`
                    FROM `$this->table` AS ht
                    LEFT JOIN `$koppel` AS st
                    ON (ht.`{$colName}`=st.`{$factor}`)
                    WHERE (st.`{$show}` LIKE :search OR st.`id` LIKE :search)
                    AND ht.`deleted` = '0'
                    ORDER BY ht.`position` ASC
                ";
            }
            elseif(isset($searchByCoupleAndMore) && $searchByCoupleAndMore == true  && $by == $colName)
            {
                $searchQuery = "
                    SELECT ht.`id`, $coupleSelectFields, ht.`active`, ht.`deleted`, ht.`position`
                    FROM `$this->table` AS ht
                    LEFT JOIN `$koppel` AS st
                    ON (ht.`{$colName}`=st.`{$factor}`)
                    WHERE (st.`{$showMore[0]}` LIKE :search OR st.`{$showMore[1]}` LIKE :search
                        OR CONCAT(st.`{$showMore[0]}`, ' ', st.`{$showMore[1]}`) LIKE :search
                        OR CONCAT(st.`{$showMore[1]}`, ' ', st.`{$showMore[0]}`) LIKE :search
                        OR st.`id` LIKE :search)
                    AND ht.`deleted` = '0'
                    ORDER BY ht.`position` ASC
                ";
            }
            else
                $searchQuery = "SELECT `id`, $selectFields, `active`, `deleted`, `position` FROM `$this->table` WHERE `$by` LIKE :search AND `deleted` = '0' ORDER BY `position` ASC";
            
            $statement = $this->dbh->prepare($searchQuery);
            $statement->execute(array(':search' => "%".$search."%"));
            $list = array();
            $list = $this->checkFieldsComments($list, $statement);
            return $list;
        }
    }
    
    public function getCMSTableRowByUserId($id)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table, $this->validTables))
        {
            $statement = $this->dbh->prepare("SELECT * FROM `$this->table` WHERE `deleted` = '0' AND `memberID`=:id");
            $statement->execute(array(':id' => $id));
            $list = array();
            $list = $this->checkFieldsComments($list, $statement);
            return $list;
        }
    }
    
    public function resetMafiasource($data, $nextRoundStartDate = "now")
    {
        // Backup database
        $adminReset = true;
        include_once DOC_ROOT . "/app/cronjob/dbbackup.php";
        $adminReset = null;
        $dbbackup = $saveFile;
        $saveFile = null;
        
        // Fetch and store hall of fame into new json object
        $statisticData = new StatisticDAO();
        $statistic = $statisticData->getStatisticsPage();
        $hof = $statisticData->getHallOfFamePage(); // Arr
        
        $hof['game'] = $statistic->getGameStatistic();
        $hof['richest'] = $statistic->getRichestStatistic();
        $hof['mostHonored'] = $statistic->getMostHonoredStatistic();
        $hof['killerking'] = $statistic->getKillerkingStatistic();
        $hof['prisonBreaking'] = $statistic->getPrisonBreakingStatistic();
        $hof['carjacking'] = $statistic->getCarjackingStatistic();
        $hof['crimes'] = $statistic->getCrimesStatistic();
        $hof['pimping'] = $statistic->getPimpingStatistic();
        $hof['smuggling'] = $statistic->getSmugglingStatistic();
        $hof['referral'] = $statistic->getReferralStatistic();
        
        $memberList = array();
        foreach($hof['members'] AS $member)
        {
            $hofMember['getScorePosition'] = $member->getScorePosition();
            $hofMember['getId'] = $member->getId();
            $hofMember['getUsername'] = $member->getUsername();
            $hofMember['getDonatorID'] = $member->getDonatorID();
            $hofMember['getUsernameClassName'] = $member->getUsernameClassName();
            $hofMember['getAvatar'] = $member->getAvatar();
            $hofMember['getScore'] = $member->getScore();
            $hofMember['getFamilyID'] = $member->getFamilyID();
            $hofMember['getFamily'] = $member->getFamily();
            
            array_push($memberList, $hofMember);
        }
        
        $hofObj['members'] = $memberList;
        
        $famList = array();
        foreach($hof['families'] AS $fam)
        {
            $hofFamily['getName'] = $fam->getName();
            $hofFamily['getVip'] = $fam->getVip();
            $hofFamily['getMoney'] = $fam->getMoney();
            $hofFamily['getTotalScore'] = $fam->getTotalScore();
            
            array_push($famList, $hofFamily);
        }
        
        $hofObj['families'] = $famList;
        
        $hofGame['getTotalMembers'] = $hof['game']->getTotalMembers();
        $hofGame['getTotalCash'] = $hof['game']->getTotalCash();
        $hofGame['getTotalBank'] = $hof['game']->getTotalBank();
        $hofGame['getTotalMoney'] = $hof['game']->getTotalMoney();
        $hofGame['getAverageMoney'] = $hof['game']->getAverageMoney();
        $hofGame['getTotalFamilies'] = $hof['game']->getTotalFamilies();
        $hofGame['getTotalBullets'] = $hof['game']->getTotalBullets();
        $hofGame['getAverageBullets'] = $hof['game']->getAverageBullets();
        $hofGame['getTotalDeathNow'] = $hof['game']->getTotalDeathNow();
        $hofGame['getTotalBanned'] = $hof['game']->getTotalBanned();
        $gameObj = $hofGame;
        
        $hofObj['game'] = $gameObj;
        
        foreach(array_keys($hof) AS $key)
        {
            $denyKeys = array("game", "members", "families", "startDate", "endDate");
            if(!in_array($key, $denyKeys))
            {
                $statList = $dataObj = array();
                foreach($hof[$key] AS $stat)
                {
                    $obj = new \stdClass();
                    $obj->getKey = $stat->getKey();
                    $obj->getValue = $stat->getValue();

                    array_push($statList, $obj);
                }
                
                $dataObj = $statList;
                
                switch($key)
                {
                    default:
                    case "richest":
                        $hofObj['richest'] = $dataObj;
                        break;
                    case "mostHonored":
                        $hofObj['mostHonored'] = $dataObj;
                        break;
                    case "killerking":
                        $hofObj['killerking'] = $dataObj;
                        break;
                    case "prisonBreaking":
                        $hofObj['prisonBreaking'] = $dataObj;
                        break;
                    case "carjacking":
                        $hofObj['carjacking'] = $dataObj;
                        break;
                    case "crimes":
                        $hofObj['crimes'] = $dataObj;
                        break;
                    case "pimping":
                        $hofObj['pimping'] = $dataObj;
                        break;
                    case "smuggling":
                        $hofObj['smuggling'] = $dataObj;
                        break;
                    case "referral":
                        $hofObj['referral'] = $dataObj;
                        break;
                }
            }
        }
        $hofJson = json_encode($hofObj);
        // /End create hof json
        
        // Insert current round data, its hall of fame json and database backup path.
        $this->con->setData("
            INSERT INTO `round` (`round`, `startDate`, `endDate`, `hofJson`, `dbbackup`) VALUES (:rnd, :sDate, :eDate, :json, :dbbckp)
        ", array(
            ':rnd' => (int)$data['round-no'],
            ':sDate' => date($this->phpDateFormat, strtotime($data['start-date'])),
            ':eDate' => date($this->phpDateFormat, strtotime($data['end-date'])),
            ':json' => stripslashes($hofJson),
            'dbbckp' => $dbbackup
        ));
        
        $startDate = $nextRoundStartDate !== "now" && (DateTime::createFromFormat('Y-m-d H:i:s', $nextRoundStartDate) !== false) ? $nextRoundStartDate : date("Y-m-d H:i:s");
        
        // Standard reset of game
        $this->con->setData("
            TRUNCATE TABLE `bank_log`;
            UPDATE `bullet_factory` SET `bullets`='10000', `priceEachBullet`='2500', `production`='0';
            UPDATE `business` SET `last_price`=`opening_price`, `close_price`=`opening_price`, `high_price`=`opening_price`, `low_price`=`opening_price`;
            TRUNCATE TABLE `business_stock`;
            TRUNCATE TABLE `change_email`;
            TRUNCATE TABLE `crime_org_prep`;
            TRUNCATE TABLE `detective`;
            TRUNCATE TABLE `drug_liquid`;
            TRUNCATE TABLE `equipment`;
            UPDATE `family` SET `vip`='0';
            TRUNCATE TABLE `family_alliance`;
            TRUNCATE TABLE `family_bank_log`;
            TRUNCATE TABLE `family_bf_donation_log`;
            TRUNCATE TABLE `family_bf_send_log`;
            TRUNCATE TABLE `family_brothel_whore`;
            TRUNCATE TABLE `family_crime`;
            TRUNCATE TABLE `family_donation_log`;
            TRUNCATE TABLE `family_garage`;
            TRUNCATE TABLE `family_join_invite`;
            TRUNCATE TABLE `family_mercenary_log`;
            TRUNCATE TABLE `family_raid`;
            TRUNCATE TABLE `fifty_game`;
            TRUNCATE TABLE `forum_reaction`;
            TRUNCATE TABLE `forum_read`;
            TRUNCATE TABLE `forum_topic`;
            TRUNCATE TABLE `garage`;
            UPDATE `ground` SET `userID`='0', `building1`='0', `building2`='0', `building3`='0', `building4`='0', `building5`='0', `cBuilding1`='0', `cBuilding2`='0',
                `cBuilding3`='0', `cBuilding4`='0', `cBuilding5`='0';
            TRUNCATE TABLE `gym_competition`;
            TRUNCATE TABLE `hitlist`;
            TRUNCATE TABLE `honorpoint_log`;
            TRUNCATE TABLE `login`;
            TRUNCATE TABLE `lottery`;
            TRUNCATE TABLE `lottery_winner`;
            TRUNCATE TABLE `market`;
            TRUNCATE TABLE `message`;
            TRUNCATE TABLE `murder_log`;
            TRUNCATE TABLE `notification`;
            UPDATE `possess` SET `userID`='0', `profit`='0', `profit_hour`='0', `stake`='50000';
            TRUNCATE TABLE `possess_transfer`;
            TRUNCATE TABLE `prison`;
            TRUNCATE TABLE `recover_password`;
            UPDATE `rld` SET `windows`='1', `priceEachWindow`='150';
            TRUNCATE TABLE `rld_whore`;
            TRUNCATE TABLE `shoutbox_en`;
            TRUNCATE TABLE `shoutbox_nl`;
            TRUNCATE TABLE `smuggle_unit`;
            UPDATE `user`
              SET `restartDate`= :startDate, `isProtected`='1', `activeTime`='0', `referralProfits`='0', `warns`='0', `forumPosts`='0', `rankpoints`='0', `health`='100', `score`='0',
                `cash`='2500', `bank`='10000', `swissBank`='0', `swissBankMax`='100000000', `prisonBusts`='0', `honorPoints`='0', `whoresStreet`='0', `kills`='0', `deaths`='0', `headshots`='0',
                `bullets`='10', `weapon`='0', `protection`='0', `airplane`='0', `weaponExperience`='0', `weaponTraining`='0', `residence`='0', `residenceHistory`='', `power`='0', `cardio`='0',
                `gymCompetitionWin`='0', `gymCompetitionLoss`='0', `gymProfit`='0', `gymScorePointsEarned`='0', `daily1Amount`='0', `daily2Amount`='0', `daily3Amount`='0', `dailyCompletedDays`='1',
                `luckybox`='0', `credits`='0', `creditsWon`='0', `crimesLv`='1', `crimesXp`='0,00', `crimesProfit`='0', `crimesSuccess`='0', `crimesFail`='0', `crimesRankpoints`='0',
                `vehiclesLv`='1', `vehiclesXp`='0,00', `vehiclesProfit`='0', `vehiclesSuccess`='0', `vehiclesFail`='0', `vehiclesRankpoints`='0', `pimpLv`='1', `pimpXp`='0,00',
                `pimpProfit`='0', `pimpAttempts`='0', `pimpAmount`='0', `smugglingLv`='1', `smugglingXp`='0,00', `smugglingProfit`='0', `smugglingTrips`='0', `smugglingUnits`='0',
                `smugglingBusts`='0', `m5c`='0', `m8c`='0', `publicMission`='0', `lrsID_nl`='0', `lrfsID_nl`='0', `lrsID_en`='0', `lrfsID_en`='0', `ground`='0', `smugglingCapacity`='0',
                `cHalvingTimes`='0', `cBribingPolice`='0',`cCrimes`='0', `cWeaponTraining`='0', `cGymTraining`='0', `cStealVehicles`='0', `cPimpWhores`='0',
                `cFamilyRaid`='0', `cFamilyCrimes`='0', `cBombardement`='0', `cTravelTime`='0', `cPimpWhoresFor`='0';
            TRUNCATE TABLE `user_captcha`;
            TRUNCATE TABLE `user_friend_block`;
            TRUNCATE TABLE `user_garage`;
            TRUNCATE TABLE `user_mission_carjacker`;
            TRUNCATE TABLE `user_residence`;
        ", array(':startDate' => $startDate));
        
        // Reset according to $data params
        
        function removeFamilies()
        {
            global $connection;
            $connection->setData("
                TRUNCATE TABLE `family`;
                UPDATE `user` SET `familyID`='0'
            ");
        }
        
        if(isset($data['member-status']) && $data['member-status'] === "rollback-status")
            $this->con->setData("
                UPDATE `user` SET `donatorID`='0' WHERE `donatorID`='1';
    			UPDATE `user` SET `donatorID`='1' WHERE `donatorID`='5';
    			UPDATE `user` SET `donatorID`='5' WHERE `donatorID`='10';
            ");
            
        if(isset($data['member-status']) && $data['member-status'] === "discard-status")
            $this->con->setData("UPDATE `user` SET `donatorID`='0'");
        
        if(isset($data['member-status']) && $data['member-status'] === "remove-members")
        {
            $statusID = 2;
            if(isset($data['keep-team']) && $data['keep-team'] === "keep")
                $statusID = 6;
            
            $this->con->setData("DELETE FROM `user` WHERE `statusID`> :sID", array(':sID' => $statusID));
            
            removeFamilies();
        }
        
        if(isset($data['remove-families']) && $data['remove-families'] === "remove")
            removeFamilies();
        
        global $route;
        return $route->successMessage($route->settings['gamename'] . " heeft een reset ondergaan! Volgende datum werd alvast genoteerd voor de volgende ronde: " . $startDate);
    }
    
    public function getValidTables()
    {
        return $this->validTables;
    }
}
