<?php
function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters){    
	try{
        $messagePostfix = '';
        $un = strtoupper(uniqid(time()));
        $eol = CAllEvent::GetMailEOL();
        
        $additional_headers = preg_replace_callback('#^\s*ADD-FILE:\s*([^\r\n]+)(?:[\r\n]+|$)#sim', function ($m) use (&$messagePostfix, $un, $eol) {
            $strAddFile = trim($m[1]);
            if (empty($strAddFile)) {
                return '';
            }
            
            $arrFileAa = preg_split('#\s*;\s*#', $strAddFile, -1, PREG_SPLIT_NO_EMPTY);
			
            foreach ($arrFileAa as $strFileAs) {               
                $arrFileAs = preg_split('#\s*=>\s*#', $strFileAs, -1, PREG_SPLIT_NO_EMPTY);				
                
                $f = fopen($arrFileAs[0], 'rb');
                if ($f === false) {
                    $fileData = 'Сan not open file ' . $arrFileAs[0];
                    $fileName = $arrFileAs[1] . '.txt';
                } else {
                    $fileData = fread($f, filesize($arrFileAs[0]));
                    $fileName = $arrFileAs[1];
                    fclose($f);
                }
                $messagePostfix .= '--------' . $un . $eol;
                $messagePostfix .= 'Content-Type: application/octet-stream;name="' . $fileName . '"' . $eol;
                $messagePostfix .= 'Content-Disposition:attachment;filename="' . $fileName . '"' . $eol;
                $messagePostfix .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
                $messagePostfix .= chunk_split(base64_encode($fileData)) . $eol . $eol;
            }
            return '';
        }, $additional_headers);
        
        if($messagePostfix){
            $messagePrefix = '--------' . $un . $eol;
            $additional_headers = preg_replace_callback('#^\s*(Content-Type:[^\r\n]+)(?:[\r\n]+|$)#sim', function ($m) use (&$messagePrefix, $eol) {
                $messagePrefix .= $m[1] . $eol;
                return '';
            }, $additional_headers);
            $additional_headers = preg_replace_callback('#^\s*(Content-Transfer-Encoding:[^\r\n]+)(?:[\r\n]+|$)#sim', function ($m) use (&$messagePrefix, $eol) {
                $messagePrefix .= $m[1] . $eol;
                return '';
            }, $additional_headers);
            $additional_headers = trim($additional_headers) . $eol;
            $additional_headers .= 'Mime-Version: 1.0' . $eol;
            $additional_headers .= 'Content-Type:multipart/mixed;boundary="------' . $un . '"' . $eol . $eol;

            $message = $messagePrefix . $eol . $eol . $message . $eol . $eol . $messagePostfix;
        }
        
        if($additional_parameters != ''){
            return @mail($to, $subject, $message, $additional_headers, $additional_parameters);
        }
        return @mail($to, $subject, $message, $additional_headers);
    
	}catch(Exception $e){
        return false;
    }
}