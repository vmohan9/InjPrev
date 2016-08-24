<?php

function pwi()
{
	//Binding parameters for PDO
    $ar = array(":a", ":b", ":c", ":d",":e",":f",":g");
    $numargs = func_num_args();
    
    $arg_list = func_get_args();
    
    $numarg=$numargs-1;
	//Split according to number of input place holders
    $query_a=explode("%s",$arg_list[1]);
    $s="";
    $j=0;
    $c=count($query_a);
	//Constructing a dynamic PDO statement
    for ($i = 0; $i < $numarg; $i++) {
         $s=$s.$query_a[$i] ;
    if($i < ($c-1))
    {

    $s=$s.$ar[$j];
    $j++;
    
    }
        
    }
try
{
    $conn = $arg_list[0];
    $db_det="mysql:host=".$conn[0].";dbname=".$conn[1];
    $uname = $conn[2];
    $pass = $conn[3];
    $p = new PDO($db_det,$uname,$pass);
$st = $p->prepare($s);
//Preparing its equivalent PDO statement
for($i = 0;$i < ($numargs-2) ;$i++)
    {
        $st->bindParam($ar[$i],$arg_list[$i+2]);
        
    }
    
    $st->execute();
    $res = $st->fetchAll();
     return $res;
}
catch( PDOException $Exception ) {
	 throw new MyDatabaseException( $Exception->getMessage( ) , $Exception->getCode( ) );
}
}

function sanitize_input()
{

    $arg_list = func_get_args();
    $type = $arg_list[2];
    $input = $arg_list[1];
    $conn = $arg_list[0];
	$host=$conn[0];  //connection details
	$user=$conn[2];
	$pwd=$conn[3];
	$dbname=$conn[1];
	$con=mysqli_connect($host,$user,$pwd,$dbname); 
	$length = strlen($input);
    $pos = false;
    
    if(strcasecmp(substr($input,0,2),"0X")==0)
    {
        $input = preg_replace('/0x/', '', $input); //hex encoding
        $input = pack('H*', $input);
        
    }
    if(strpos($input,"%27%20")==true or strpos($input,"%20")==true) //url encoding
    {
        $input = urldecode($input);
        $pos = true;
    }
	$words = explode(" ", $input);

	$io="";

	if(strcasecmp($type,"string")==0)
	{	
    	for($i=0; $i<count($words); $i++)
    	{
    		if(substr($words[$i], -1)=="'" or strcmp(substr($words[$i],-2),"';")==0 or $pos == true )  // different types of sql injection
    		{

                if(strcasecmp($words[$i+1],"char(")==0)
                 {
                    $input = mysqli_real_escape_string($con,$input); break;
                }
    			elseif(strcasecmp($words[$i+1],"or")==0)
    			{
					$input = mysqli_real_escape_string($con,$input); break;
    			}
				elseif(strcasecmp($words[$i+1],"union")==0 && strcasecmp($words[$i+2],"select")==0)
				{
    				$input = mysqli_real_escape_string($con,$input);break;
				}
    			elseif(strcasecmp($words[$i+1],"drop")==0)
    			{
    				$input = mysqli_real_escape_string($con,$input);break;
    			}
    			elseif(strcasecmp($words[$i+1],"create")==0)
    			{
    				$input = mysqli_real_escape_string($con,$input);break;
    			}
    			elseif(strcasecmp($words[$i+1],"insert")==0)
    			{
    				$input = mysqli_real_escape_string($con,$input);break;
    			}
    			elseif(strcasecmp($words[$i+1],"truncate")==0)
    			{
    				$input = mysqli_real_escape_string($con,$input);break;
    			}
    			elseif(strcasecmp($words[$i+1],"select")==0)
    			{
    				$input = mysqli_real_escape_string($con,$input);break;
    			}
    			elseif(strcasecmp($words[$i+1],"update")==0)
    			{
    				$input = mysqli_real_escape_string($con,$input);break;
    			}
    		    elseif(strcasecmp($words[$i+1],"shutdown")==0)
    			{
    				$input = mysqli_real_escape_string($con,$input);break;
    			}

    		}
    		
    			
    	}

    		
    }
   
    return $input;

}  

?>