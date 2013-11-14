<?php

class UploadClass
{
	var $maxSize;
	var $allowedExt;
	var $fileInfo = array();
 
	function config($maxSize,$allowedExt)
	{
		$this->maxSize = $maxSize;
		$this->allowedExt = $allowedExt;
	}
 
	function generateRandStr($length)
	{
      $randstr = "";
      for($i=0; $i< $length; $i++){
         $randnum = mt_rand(0,61);
         if($randnum < 10)
         {
            $randstr .= chr($randnum+48);
         }else if($randnum < 36)
         {
            $randstr .= chr($randnum+55);
         }else
         {
            $randstr .= chr($randnum+61);
         }
      }
      return $randstr;
   }
   
   function check($uploadName)
   {
   	if(isset($_FILES[$uploadName]))
   	{
   		$this->fileInfo['ext'] = substr(strrchr($_FILES[$uploadName]["name"], '.'), 1);
   		$this->fileInfo['name'] = basename($_FILES[$uploadName]["name"]);
   		$this->fileInfo['size'] = $_FILES[$uploadName]["size"];
   		$this->fileInfo['temp'] = $_FILES[$uploadName]["tmp_name"]; 
   		
   		if($this->fileInfo['size'] < $this->maxSize)
   		{
   			if(strlen($this->allowedExt)>0)
   			{
   				$exts = explode(',',$this->allowedExt);
   				
   				if(in_array($this->fileInfo['ext'],$exts))
   				{
   					return true;
   				}

   				echo 'Invalid file extension. Allowed extensions are '.$this->allowedExt;
				return false; //failed ext
			}
			echo 'Sorry but there is an error in our server. Please try again later.';
			return false; //All ext allowed
		}
		else
		{
			if($this->maxSize < 1000000)
			{
				$rsi = round($this->maxSize/1000,2).' Kb';
			}
			elseif($this->maxSize < 1000000000)
			{
				$rsi = round($this->maxSize/1000000,2).' Mb';
			}
			else
			{
				$rsi = round($this->maxSize/1000000000,2).' Gb';
			}
			
			echo 'File is too big. Maximum allowed size is '.$rsi;
			return false; //failed size
		}
	}
	echo 'Oops! An unexpected error occurred, please try again later.';
	return false; //Either form not submitted or file/s not found
	}
 
	function upload($name,$dir,$fname=false){
		if(!is_dir($dir))
		{
			echo 'Sorry but there is an error in our server. Please try again later.';
			return false; //Directory doesn't exist! 
		}
		if($this->check($name))
		{
			//Process upload. All info stored in array fileinfo:
			//Dir OK, keep going:
			//Get a new filename:
			if(!$fname)
			{
				$this->fileInfo['fname'] = $this->generateRandStr(15).'.'.$this->fileInfo['ext'];
			}else
			{
				$this->fileInfo['fname'] = $fname;
			}
			while(file_exists($dir.$this->fileInfo['fname']))
			{
				$this->fileInfo['fname'] = $this->generateRandStr(15).'.'.$this->fileInfo['ext'];
			}
			//Unique name gotten
			// Move file:
			if(@move_uploaded_file($this->fileInfo['temp'], $dir.$this->fileInfo['fname']))
			{
				return true;
			}else
			{
				echo 'The file could not be uploaded, although everything went ok  ... Please try again later.';
				return false; //File not moved
			}
		}
		else
		{
			return false;
		}
	}

}