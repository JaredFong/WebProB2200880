<?php

	if (isset($_GET['id']))
	{
		$id=$_GET['id'];
		include '../dbconnect.php';
		
		print ("<SCRIPT LANGUAGE='JavaScript'>
		var answer=window.confirm('Are you want to delete?')
		if(answer){
										
			 window.location='delete_news.php?delete=ya&bil=$id';					 	 
		}
		else
		{				 
			 window.location='news_update_list.php?';
		}	
		</SCRIPT>");	
			
	}
	
?>

 <?php
 
 	if (isset($_GET['delete']))
	{		
		include '../dbconnect.php';
		$id=$_GET['bil'];		
		$sql = "DELETE FROM news_update WHERE id = '$id'"; 
		mysqli_query($dbc,$sql) or die(mysqli_error());	

		print "<script>";
        print "self.location='news_update_list.php?';"; 
        print "</script>";			
			
	}  
 ?>