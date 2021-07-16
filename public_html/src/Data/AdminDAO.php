<?PHP

namespace src\Data;

use src\Data\MemberDAO;
use src\Data\config\DBConfig;
use vendor\Images\SimpleImage;

class AdminDAO extends DBConfig
{
    protected $con = "";
    protected $database = "";
    private $dbh = "";
    private $table = "";
    public $validTables = array(
        "categories", "cms", "member", "status", "user", "donator", "profession", "state", "city", "news", "ground", "ground_building", "rld_whore", "weapon", "protection",
        "airplane", "helpsystem", "vehicle", "residence", "crime", "crime_org", "steal_vehicle", "possess", "possession", "forum_category", "forum_status", "forum_topic",
        "forum_reaction", "smuggle", "detective", "family", "family_alliance", "family_bf_donation_log", "family_bf_send_log", "family_brothel_whore", "family_garage",
        "family_join_invite", "family_mercenary_log", "family_raid", "garage", "gym_competition", "user_garage", "market", "message", "murder_log", "poll_answer",
        "poll_question", "poll_vote", "seo", "shoutbox_nl", "shoutbox_en", "user_captcha", "user_friend_block"
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
            $list = $this->checkCommentsForFieldsInRows($list, $statement);
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
            $list = $this->checkCommentsForFieldsInRows($list, $statement);
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
            $list = $this->checkCommentsForFieldsInRows($list, $statement);
            return $list;
        }
    }
    
    private function checkCommentsForFieldsInRows($list, $statement)
    {
        foreach($statement AS $key => $val)
        {
            $arr = array();
            foreach($val AS $fieldKey => $value)
            {
                $statement = $this->dbh->prepare("SELECT c.`COLUMN_COMMENT` FROM `information_schema`.`COLUMNS` AS c WHERE c.`TABLE_SCHEMA` ='$this->database' AND c.`TABLE_NAME` = '$this->table' AND c.`COLUMN_NAME` = :key ");
                $statement->execute(array(':key' => $fieldKey));
                $row = $statement->fetch();
                
                if(isset($row['COLUMN_COMMENT']) && $row['COLUMN_COMMENT'] != '')
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
                }
            }
            $arr[$key] = $val;
            array_push($list,$arr);
        }
        return $list;
    }
    
    private function checkCommentsForFieldsInEdit($list,$statement)
    {
        foreach($statement AS $key => $val)
        {
            $arr = array();
            foreach($val AS $fieldKey => $value)
            {
                $statement = $this->dbh->prepare("SELECT c.`COLUMN_COMMENT` FROM `information_schema`.`COLUMNS` AS c WHERE c.`TABLE_SCHEMA` ='$this->database' AND c.`TABLE_NAME` = '$this->table' AND c.`COLUMN_NAME` = :key ");
                $statement->execute(array(':key' => $fieldKey));
                $row = $statement->fetch();
                
                if(isset($row['COLUMN_COMMENT']) && $row['COLUMN_COMMENT'] != '')
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
                                if($row[$factor] == $val[$fieldKey])
                                    $koppelArr[$row[$factor]] = array($row[$show] => "checked");
                                else
                                    $koppelArr[$row[$factor]] = $row[$show];
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
                                if($row[$factor] == $val[$fieldKey])
                                    $koppelArr[$row[$factor]] = array("".$row[$showMore[0]]." ".$row[$showMore[1]]."" => "checked");
                                else
                                    $koppelArr[$row[$factor]] = $row[$showMore[0]]." ".$row[$showMore[1]];
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
                            foreach(array_keys($select) AS $key) if($key+1 == $value) $select[$key] = array($select[$key] => "checked");
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
                }
                unset($row);
            }
            $arr[$key] = $val;
            array_push($list,$arr);
        }
        return $list;
    }
    
    public function sortRows($data)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
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
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $id = (int)$id;
            $statement = $this->dbh->prepare("UPDATE `$this->table` SET `active` = '1' WHERE `id` = :id");
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
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $id = (int)$id;
            $statement = $this->dbh->prepare("UPDATE `$this->table` SET `active` = '0' WHERE `id` = :id");
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
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $id = (int)$id;
            $statement = $this->dbh->prepare("UPDATE `$this->table` SET `deleted` = '1' WHERE `id` = :id");
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
            $statement = $this->dbh->prepare("SELECT * FROM `$this->table` WHERE `deleted` = '0' AND `id`=:id");
            $statement->execute(array(':id' => $id));
            $list = array();
            $list = $this->checkCommentsForFieldsInEdit($list,$statement);
            return $list;
        }
    }
    
    public function saveEditedRow($post, $id, $files = false)
    {
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
        {
            $editStr = "";
            $parArr = array();
            $denySaves = array("id", "password", "memberID", "active", "position", "deleted", "uploadDir", "imageWidth", "imageHeight");
            
            if(isset($post['uploadDir']))
                $uploadDir = $post['uploadDir'];
            else
                $uploadDir = "uploads";
            if(isset($post['imageWidth'])) $imageWidth = $post['imageWidth'];
            if(isset($post['imageHeight'])) $imageHeight = $post['imageHeight'];
            include(DOC_ROOT . '/vendor/Images/SimpleImage.php');
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
                if(!empty($statement) && !in_array($key,$denySaves))
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
        if(isset($_SESSION['cp-logon']) && in_array($this->table,$this->validTables))
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
                $statement = $this->dbh->prepare("SELECT `COLUMN_NAME`, `COLUMN_COMMENT` FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` ='$this->database' AND `TABLE_NAME` = '$this->table' AND `COLUMN_NAME` IN ($fields)");
                $statement->execute();            
            }
            elseif($skipFields != FALSE)
            {
                $skipFieldsArr = explode(',', $skipFields);
                foreach($skipFieldsArr AS $k => $v) $skipFieldsArr[$k] = " '" . $v . "'";
                $skipFields = implode(',', $skipFieldsArr);
                $skipFields = ltrim($skipFields, ' ');
                $statement = $this->dbh->prepare("SELECT `COLUMN_NAME`, `COLUMN_COMMENT` FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` ='$this->database' AND `TABLE_NAME` = '$this->table' AND `COLUMN_NAME` NOT IN ($skipFields)");
                $statement->execute();
            }
            
            $searchByCouple = $searchByCoupleAndMore = false;
            $validatedFields = $coupleSelectArr = array();
            $koppel = $factor = $colName = $show = "";
            while ($row = $statement->fetch())
            {
                if(!in_array($row['COLUMN_NAME'], $validatedFields))
                    $validatedFields[] = $row['COLUMN_NAME'];
                    
                if(!in_array($row['COLUMN_NAME'], $coupleSelectArr))
                    $coupleSelectArr[] = $row['COLUMN_NAME'];
                
                if($row['COLUMN_NAME'] == $searchBy)
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
                                
                                if($cPar == 'couple') $koppel = $cVal;
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
                    ON (ht.`$colName`=st.`$factor`)
                    WHERE (st.`$show` LIKE :search OR st.`id` LIKE :search)
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
                    ON (ht.`$colName`=st.`$factor`)
                    WHERE (st.`$showMore[0]` LIKE :search OR st.`$showMore[1]` LIKE :search
                        OR CONCAT(st.`$showMore[0]`, ' ', st.`$showMore[1]`) LIKE :search
                        OR CONCAT(st.`$showMore[1]`, ' ', st.`$showMore[0]`) LIKE :search
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
            $list = $this->checkCommentsForFieldsInRows($list, $statement);
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
            $list = $this->checkCommentsForFieldsInRows($list, $statement);
            return $list;
        }
    }
    
    public function getValidTables()
    {
        return $this->validTables;
    }
}
