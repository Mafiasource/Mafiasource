<?PHP
 
namespace src\Business\Logic;

use vendor\SimpleImage;
 
class UploadService
{
    public $response = 0;
    public $uploadedFileName = '';
    
    public function __construct($files, $fieldName, $saveDir, $nameForFile, $width, $subDirUploads = true, $height = false)
    {
        global $security;
        
        $UploadDirectory = $saveDir.'/';
        if($subDirUploads === true)
        	$UploadDirectory = $saveDir.'/uploads/';
        
        if(is_uploaded_file($_FILES[$fieldName]['tmp_name']) === false)
            $error = (string)5;
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        switch(strtolower(finfo_file($finfo, $files[$fieldName]['tmp_name'])))
        {
            case 'image/png':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/pjpeg':
                break;
            default:
            	$error = (string)3;
                break;
        }
        finfo_close($finfo);
        
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
    		$error = "Invalid headers encountered";
    	
    	if ($files[$fieldName]['size'] > 5242880)
    		$error = "Upload > 5MB";
        
        $f = fopen($files[$fieldName]['tmp_name'],'r');
        $content="";
        while(!feof($f)) $content .= fgets($f);
        fclose($f);
        $badWords = array(
            "html", "php", "form", "iframe", "link", "script", "java", "submit", "body", "head", "var", "function", "href",
            "jar", "jscript", "javascript", "wscript", "vbscript", "vbs", "applescript", "behavior", "mocha", "livescript", 
            "view-source"
        );
        foreach($badWords AS $badWord)
        {
            if(strpos(strtoupper((string)$content), strtoupper((string)$badWord)))
                $error = "Untrusted file detected, please upload another or try to make this one safe using an image editor"; // error 4
        }
        
        if(!isset($error))
        {
            if (!file_exists($saveDir))
                mkdir($saveDir, 0755, true);
            
            if($subDirUploads === true)
                if (!file_exists($saveDir.'/uploads'))
                    mkdir($saveDir.'/uploads', 0755, true);
            
            $File_Name          = strtolower($files[$fieldName]['name']);
        	$File_Ext           = substr($File_Name, strrpos($File_Name, '.'));
        	$fileName           = $nameForFile.'_'.$security->randInt(0,9);
        	$NewFileName 		= $fileName.$File_Ext;

            if (file_exists($UploadDirectory.$NewFileName)) {
                unlink($UploadDirectory.$NewFileName);
            }
            
            include(DOC_ROOT . '/vendor/SimpleImage.php');
            $image = new SimpleImage();
            $image->load($files[$fieldName]['tmp_name']);
            if($height !== false && is_numeric($height))
                $image->resize($width, $height);
            else
                $image->resizeToWidth($width);
            $image->save($files[$fieldName]['tmp_name']);

        	if(move_uploaded_file($files[$fieldName]['tmp_name'], $UploadDirectory.$NewFileName ))
            {
                chmod($UploadDirectory.$NewFileName, 0644);
                $this->uploadedFileName = $NewFileName;
        		$success = 1;//$response = $l['UPLOAD_AVATAR_SUCCESS'];
        	}
            else
        		$error = 5;//$response = $l['UPLOAD_AVATAR_FAILED']; // error 5
            
            if(isset($error))
                $this->response = array('error' => $error);
            elseif(isset($success))
                $this->response = array('success' => $success);
        }
        else
            $this->response = array('error' => $error);
    }
}
