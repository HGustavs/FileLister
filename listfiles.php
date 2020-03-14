<?php
// -------------------------------------------------------------------------------------------------------------
// -----------=============######## Apache Directory Listing and Preview System ########=============-----------
// -------------------------------------------------------------------------------------------------------------
//  Copyright a97marbr / HGustavs
//
//        (\ /)
//        (. .)           
//       c(")(")  ∴ 
//-------------------------------------------------------------------------------------------------------------

date_default_timezone_set('Europe/Stockholm');

?>
<html>
    <head>
			
			<style>
				table{
					font-family: Lucida Grande,Lucida Sans Unicode,Lucida Sans,Geneva,Verdana,sans-serif;
					font-size: 10px;
					font-style: normal;
					font-variant: normal;
					font-weight: 100;
					border-collapse: collapse;
				}		

				th {
						background: #eee;
						border-bottom: 1px groove;
				}
				
				.hi {
					 background:#fff;
				}

				.lo {
					 background:#e8e8e8;
				}
				
				.breadcrumb {
						padding:3px;
						border:1px solid red;
						background-color:#def;
						margin-right:2px;
				}
				
				tr:nth-child(even) {
  					background: #fff;
				}
				
				tr:nth-child(odd) {
  					background: #eee;
				}
			
				#prev {
						position: fixed;
						top: 0px;
						left: 400px;
						right: 0px;
						bottom:0px;
				}
				
			</style>

<script>

var folder='<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" \
	 width="15px" height="12px" viewBox="0 0 65.25 52.417" enable-background="new 0 0 65.25 52.417" xml:space="preserve"> \
<path opacity="0.03" fill-rule="evenodd" clip-rule="evenodd" d="M64.408,48.621c0.053-0.394,0.085-0.809,0.082-1.263 \
	c-0.086-11.284-0.113-20.569-0.162-31.854c-0.115-3.739-2.697-4.061-5.563-4.055c-7.827,0.015-15.654,0.073-23.479-0.024 \
	c-3.979-0.049-8.795-1.645-11.17-5.334c-0.412-0.64-2.14-0.65-3.267-0.676c-3.495-0.082-6.924-0.125-10.485-0.086 \
	C5.851,5.378,4.513,7.09,4.549,11.216C4.653,23.038,4.57,34.862,4.579,46.685c0.001,1.301-0.285,2.702,0.917,3.72 \
	c0.704,0.372,1.407,1.066,2.113,1.069c17.76,0.069,35.519,0.058,53.279,0.081c2.347,0.003,3.258-1.108,3.512-2.876h0.008V48.621z"/> \
<path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M63.764,47.809c0.053-0.394,0.085-0.809,0.082-1.263 \
	c-0.086-11.284-0.113-20.569-0.162-31.854c-0.115-3.739-2.697-4.061-5.563-4.055c-7.827,0.015-15.654,0.073-23.479-0.024 \
	c-3.979-0.049-8.795-1.645-11.17-5.334c-0.412-0.64-2.14-0.65-3.267-0.676C16.71,4.521,13.281,4.477,9.72,4.516 \
	c-4.513,0.049-5.852,1.762-5.815,5.888c0.104,11.822,0.021,23.646,0.029,35.468c0.001,1.301-0.285,2.702,0.917,3.72 \
	c0.704,0.372,1.407,1.066,2.113,1.069c17.76,0.069,35.519,0.058,53.279,0.081c2.347,0.003,3.258-1.108,3.512-2.876h0.008V47.809z"/> \
<path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M63.29,47.179c0.053-0.394,0.085-0.809,0.082-1.263 \
	c-0.086-11.284-0.113-20.569-0.162-31.854c-0.115-3.739-2.697-4.061-5.563-4.055c-7.827,0.015-15.654,0.073-23.479-0.024 \
	c-3.979-0.049-8.795-1.645-11.17-5.334c-0.412-0.64-2.14-0.65-3.267-0.676c-3.495-0.082-6.924-0.125-10.485-0.086 \
	C4.733,3.936,3.395,5.648,3.431,9.774C3.535,21.596,3.452,33.419,3.46,45.242c0.001,1.301-0.285,2.702,0.917,3.72 \
	c0.704,0.372,1.407,1.066,2.113,1.069c17.76,0.069,35.519,0.058,53.279,0.081c2.347,0.003,3.258-1.108,3.512-2.876h0.008V47.179z"/> \
<path fill-rule="evenodd" clip-rule="evenodd" fill="#82D0F4" d="M62.737,13.306c0.048,11.285,0.076,20.57,0.162,31.854 \
	c0.019,2.531-0.712,4.2-3.603,4.196c-17.76-0.023-35.52-0.012-53.279-0.081c-0.706-0.002-1.409-0.697-2.113-1.069 \
	c0.009-10.456,0.002-20.913,0.034-31.37c0.018-5.765,0.842-6.585,6.678-6.593c15.27-0.02,30.541,2.029,45.81,1.958 \
	C58.639,12.192,60.682,12.663,62.737,13.306z"/> \
<path fill-rule="evenodd" clip-rule="evenodd" fill="#CDECF9" d="M62.737,13.306c-2.055-0.644-4.098-1.114-6.311-1.104 \
	c-15.27,0.071-30.54-1.978-45.81-1.958c-5.836,0.008-6.661,0.828-6.678,6.593c-0.032,10.457-0.025,20.914-0.034,31.37 \
	c-1.202-1.019-0.916-2.419-0.917-3.72C2.979,32.664,3.062,20.84,2.958,9.019C2.921,4.893,4.259,3.18,8.772,3.131 \
	c3.561-0.039,6.99,0.004,10.485,0.086c1.127,0.026,2.855,0.036,3.267,0.676c2.375,3.69,7.191,5.285,11.17,5.334 \
	c7.825,0.097,15.652,0.039,23.479,0.024C60.04,9.245,62.622,9.567,62.737,13.306z"/> \
<path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="#2B3990" stroke-width="6.0" stroke-miterlimit="10" d=" \
	M62.656,13.306c-0.115-3.739-2.697-4.061-5.563-4.055c-7.827,0.015-15.654,0.073-23.479-0.024c-3.979-0.049-8.795-1.645-11.17-5.334 \
	c-0.412-0.64-2.14-0.65-3.267-0.676c-3.495-0.082-6.924-0.125-10.485-0.086C4.178,3.18,2.84,4.893,2.876,9.019 \
	C2.98,20.84,2.897,32.664,2.906,44.487c0.001,1.301-0.285,2.702,0.917,3.72c0.704,0.372,1.407,1.066,2.113,1.069 \
	c17.76,0.069,35.519,0.058,53.279,0.081c2.891,0.004,3.622-1.665,3.603-4.196C62.731,33.876,62.704,24.591,62.656,13.306z"/> \
</svg>';

</script>
			
<?php
			
    function format_size($size) {
        $mod = 1024;
        $units = explode(' ','B KB MB GB TB PB');
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

		error_reporting(E_ERROR | E_PARSE);
		
		$requri=$_SERVER['REQUEST_URI'];
		$reqpos=strrpos($requri,"?");
		$rparam=substr($requri,$reqpos+9);
		if($rparam!="true") $rparam="false";
		if($reqpos!==false){
				$requri=substr($requri,0,$reqpos);
		}
			
    $dir=getcwd();
		$dir.=$requri;
    $files=scandir($dir);
    echo "<script> filelist=[";
		$i=0;
    foreach($files as $filename){
        if($i>0){
						echo ",";
				}
				echo "{";
        echo "filename:'".$filename."',";
				
				$size=filesize($filename);
				if($size=="") $size=0;
			
				echo "sizetext:'".format_size($size)."',";
				echo "size:".$size.",";
				echo "type:'".filetype($filename)."',";
				if(strpos($filename,".")===false){
						$tpos="";
				}else if(strpos($filename,".")==0){
						$tpos="HIDDEN";
				}else{
						$tpos=strrpos($filename,".");
						$tpos=substr($filename,-(strlen($filename)-$tpos-1));
				}
				echo "ext:'".$tpos."',";
				echo "modif:'".date ("Ymd H:i:s.", filemtime($filename))."'";
        echo "}";
				$i++;
    }
    echo "];\n";

		echo "var path='".$requri."';\n";
		
		echo "</script>";
			
		echo "<script>var nobread='".$rparam."';</script>";

?>
			
		<script>
		function showfiles()
		{
				var str="";	
				
				// Sort folders on top and treat upper and lower case the same
				filelist.sort(
						function(a, b){
								if((a.type=="dir")&&(b.type=="dir")){
										if(a.filename.toUpperCase()>b.filename.toUpperCase()){
												return 1;
										}else{
												return -1;
										}
								}else if(	a.type=="dir"){
										return -1;
								}else if(b.type=="dir"){
										return 1;
								}else{
									return 0;
								}
						}
				);
				
				// Breadcrumbs
				if(nobread!="true"){
						var patharr=path.split("/");
						oldstr=patharr[0];
						str+="<span class='breadcrumb'><a href='/'>/</a></span>";
						for(var i=1;i<patharr.length;i++){
								sstr="";
								for(var j=0;j<i;j++){
										sstr+=patharr[j]+"/";
								}
								if(oldstr!="") str+="<span class='breadcrumb'><a href='"+sstr+"'>"+oldstr+"</a>/</span>";
								oldstr=patharr[i];
						}
				}
				
				str+="<table>";
				str+="<tr>";
				str+="<th>&nbsp;</th>";			
				str+="<th>Filename</th>";
				str+="</tr>";
				for(var i=0;i<filelist.length;i++){
						var file=filelist[i];

						if(file.filename!=".."&&file.filename!="."){
								str+="<tr ";
								if(nobread!="true") str+="onmouseover='hoverrow(\""+path+file.filename+"\",\""+file.ext+"\")' ";
								str+="class='hi' >";							

								str+="<td>";
								if(file.type=="dir") str+=folder;
								str+="</td>";

								str+="<td>";
								str+="<a href='"+path+file.filename+"'>"
								str+=file.filename;
								str+="</td>";

								str+="<td>";
								if(file.type!="dir") str+=file.ext;
								str+="</td>";

								str+="<td>";
								str+=file.size;
								str+="</td>";		
							
								str+="<td>";
								str+=file.type;
								str+="</td>";									 

								str+="</tr>";
						}
				}
				str+="</table>";
				
				document.getElementById("content").innerHTML=str;
			
		}
			
		function hoverrow(filename,filetype)
		{
				var str="";
				str+= "<iframe style='width:100%;height:100%;' src='/previewfiles.php?inurl="+encodeURIComponent(filename)+"&filetype="+filetype+"'></iframe>";
				document.getElementById("prev").innerHTML=str;
		}
			
		</script>
	
		</head>
    <body onload="showfiles();">
			<div id="content"></div>
			<div id="prev"></div>
    </body>

</html>