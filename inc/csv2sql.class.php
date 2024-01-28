<?php
/*
		This code is under Gnu General Public License
		
		+--------------------------------+
		|   DO NOT MODIFY THIS HEADERS   |
		+--------------------------------+---------------+
		|   Written by TuTToWeB                          |
		|   Email: valeriogiuffrida@hotmail.com          |
		+------------------------------------------------+
		
		+--------------------------------------------------------------------------------+
		|   Version: 0.1, Relased at 12/02/2006 22:30 (GMT + 1.00)                       |
		+--------------------------------------------------------------------------------+
		
		+----------------+
		|   Tested on    |
		+----------------+-----+
		|  APACHE => 2.0.52    |
		|     PHP => 5.0.3     |
		+----------------------+
		
		+---------------------+
		|  How to report bug  |
		+---------------------+-----------------------------------------------------------------+
		|   You can e-mail me using the email addres written above. That email is also my msn   |
		|   contact, so you can use it for contact me on MSN.                                   |
		+---------------------------------------------------------------------------------------+
		
		+-----------+
		|  Methods  |
		+-----------+------------------------------------------------------------------------------------------------+
		|   - Costructor (string $csv_input_file, string $delimitator, string $table_name, string $output_filename)  |
		|   - Export(bool $file=true)                                                                                |
		|     +--> Set $file true, sql query will be written on output file, on the contrary, set it false, function |
		|          will return an array filled with all query.                                                       |
		+------------------------------------------------------------------------------------------------------------+
*/

class csv2sql
{
  var $csvfile,$delimitatore,$nometable,$outputfile;
  var $_FIELD;
  /*costruttore*/
  function csv2sql($cf,$del,$nt,$opf)
  {
    $this->csvfile = $cf;
    $this->delimitatore = $del;
    $this->nometable = $nt;
    $this->outputfile = $opf;

    /*$_FIELD = array(
                'VARCHAR',
                'TINYINT',
                'TEXT',
                'DATE',
                'SMALLINT',
                'MEDIUMINT',
                'INT',
                'BIGINT',
                'FLOAT',
                'DOUBLE',
                'DECIMAL',
                'DATETIME',
                'TIMESTAMP',
                'TIME',
                'YEAR',
                'CHAR',
                'TINYBLOB',
                'TINYTEXT',
                'BLOB',
                'MEDIUMBLOB',
                'MEDIUMTEXT',
                'LONGBLOB',
                'LONGTEXT',
                'ENUM',
                'SET',
                'BINARY',
                'VARBINARY'); */
  }


  /*funzione di esportazione: legge il file csv e lo converte in sql. Lo si può
  esportare su file oppure la funzione restituisce un array con le varie operazioni
  sql*/
  function Export($file=true)
  {
	ini_set('magic_quotes_sybase','1'); //para Centura SQLBase Server

	$csvhandle = file($this->csvfile);                    //Apro e leggo il contenuto del file csv e lo metto in un vettore
    $field = explode($this->delimitatore,$csvhandle[0]);       //leggo i campi sul primo elemento del vettore
    $campi = "";
    foreach ($field as $campo)
    {
     $campi .= "".trim($campo).",";
    }
    
    $campi = trim(substr($campi,0,-1));                   //Metto tutti i campi in un'unica variabile

    if ($file==true)$output_handle = fopen($this->outputfile,"w") or die ("IMPOSSIBILE CREARE IL FILE ". $outputfile);      //Apro l'handle del file

    for ($i=1;$i<count($csvhandle);$i++)            //Inizio il ciclo x creare le query
    {
      $valori = explode($this->delimitatore,$csvhandle[$i]);
      $values = "";
      foreach ($valori as $val)
      {
       $val=trim($val);
       if (eregi("NULL",$val) == 0)
          $values .= "'".addslashes($val)."',";
       else
          $values .= "NULL,";
      }
      $values = trim(substr($values,0,-1));    //Inserisco tutti i valori in un'unica variabili distinguentoli dai
                                                   //dai valori nulli e non nulli
      $query = "INSERT INTO ".$this->nometable."(".$campi.") VALUES (".trim($values).")";    //creo la query
      if ($file==true)@fwrite($output_handle,$query."\r\n");                                          //e la scrivo nel file
      else $_QUERY[]=$query;
    }
    if ($file==true)fclose($output_handle);                       //chiudo il file
    else return $_QUERY;
  }
}
                                                                                                 //di output

?>
