<?
  $file = $_GET['file'];

  if (($fp = fopen($file,"r"))==false)
  {
     die ("error downloading file");
  }
  else
  {
     while (!feof($fp))
     {
        $line=fgets($fp,1024);
        print $line;
     }
  }

  fclose ($fp);
?>
