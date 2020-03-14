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
			<script>
				
				function clearhigh()
				{
						var dividers=document.getElementsByClassName("divider");
						for(var i=0;i<dividers.length;i++){
								dividers[i].style.backgroundColor="#fff";
						}
				}
				
				function clearvar()
				{
						var dividers=document.getElementsByClassName("variable");
						for(var i=0;i<dividers.length;i++){
								dividers[i].style.backgroundColor="#fff";
						}
				}
	
				function daxby(event)
				{
						var variables=document.getElementsByClassName("variable");
						var currentvariable=event.target.innerHTML;
						for(var i=0;i<variables.length;i++){
								if(variables[i].innerHTML==currentvariable) variables[i].style.backgroundColor="#fdf";
						}			
				}

				function bixby(event)
				{
						var dividers=document.getElementsByClassName("divider");
						var currentdivider=event.target.innerHTML;
						
						if(currentdivider=="(") currentend=")";
						if(currentdivider==")") currentend="(";
						if(currentdivider=="{") currentend="}";
						if(currentdivider=="}") currentend="{";
					
					
						var itemno=-1;
						for(var i=0;i<dividers.length;i++){
								dividers[i].style.backgroundColor="#fff";
								if(dividers[i].id==event.target.id){
										itemno=i;
								} 
						}
					
						if(currentdivider=="("||currentdivider=="{"){
								var cnt=0;
								for(var i=itemno+1;i<dividers.length;i++){
										if(dividers[i].innerHTML==currentend&&cnt==0){
												dividers[i].style.backgroundColor="#f84";
												break;
										}else if(dividers[i].innerHTML==currentend){
												cnt--;
										}else if(dividers[i].innerHTML==currentdivider){
												cnt++;
										}
								}
						}else if(currentdivider==")"||currentdivider=="}"){
								var cnt=0;
								for(var i=itemno-1;i>0;i--){
										if(dividers[i].innerHTML==currentend&&cnt==0){
												dividers[i].style.backgroundColor="#f84";
												break;
										}else if(dividers[i].innerHTML==currentend){
												cnt--;
										}else if(dividers[i].innerHTML==currentdivider){
												cnt++;
										}
								}
						}
				}
				
			</script>
			<style>
				
				.htmltag{
						color:darkred;
				}
				
				.htmltagend{
						color:darkred;
				}
				
				.func{
						color:darkgreen;
				}				
				
				.divider{
						color:cadetblue;
				}
				
				.string{
						color:orange;
				}
				
				.variable{
						color:darkmagenta;
				}
				
				.comment{
						color:#bbb;
				}				
				
			</style>
	</head>
	<body>
<?php			

// ------------------------------------------------------------------------------------------------------------
// -----------=============######## Apache Directory Listing and Preview System ########=============-----------
// ------------------------------------------------------------------------------------------------------------
//  Copyright a97marbr / HGustavs
//
//        (\ /)
//        (. .)           
//       c(")(")  ∴ 
//-------------------------------------------------------------------------------------------------------------

date_default_timezone_set('Europe/Stockholm');

if(!isset($_GET['filetype'])){
		$filetype="UNK";
}else{
		$filetype=strtolower($_GET['filetype']);
}

if(!isset($_GET['inurl'])){
		$filename="/";
}else{
		$filename=$_GET['inurl'];
}
		
if($filetype=="") $filetype="UNK";

//-------------------------------------------------------------------------------------------------
// parseMarkdown - parse a big markdown block
//-------------------------------------------------------------------------------------------------
		
function parseMarkdown($inString)
{	
		$inString=preg_replace("/\</", "&lt;",$inString);
		$inString=preg_replace("/\>/", "&gt;",$inString);

		$inString=preg_replace("/^\~{3}(\r\n|\n|\r)/m", "~~~@@@",$inString);
		$inString=preg_replace("/^\=\|\=(\r\n|\n|\r)/m", "=|=&&&",$inString);

		$str="";

		//$codearray=explode('~~~', $inString);
		$codearray=preg_split("/\~{3}|\=\|\=/", $inString);

		$specialBlockStart=true;
		foreach ($codearray as $workstr) {
				if(substr($workstr,0,3)==="@@@" && $specialBlockStart===true){
						$specialBlockStart=false;
						$workstr="<pre><code>".substr($workstr,3)."</code></pre>";
				} else if (substr($workstr,0,3)==="&&&" && $specialBlockStart===true){
						$specialBlockStart=false;
						$workstr="<div class='console'><pre>".substr($workstr,3)."</pre></div>";
				} else if ($workstr !== "") {
						$workstr=parseLineByLine(preg_replace("/^\&{3}|^\@{3}/","",$workstr));
						$specialBlockStart=true;
				}
				$str.=$workstr;

		}

		return "<div id='markdown'>".$str."</div>";
}

//-------------------------------------------------------------------------------------------------
// parseLineByLine - parse a line of markdown code
//-------------------------------------------------------------------------------------------------

function parseLineByLine($inString) {
	$str = $inString;	
	$markdown = "";

	$currentLineFeed = strpos($str, PHP_EOL);
	$currentLine = "";
	$prevLine = "";
	$remainingLines = "";
	$nextLine = "";

	while($currentLineFeed !== false) { // EOF
		$prevLine = $currentLine;
		$currentLine = substr($str, 0, $currentLineFeed);
		$remainingLines = substr($str, $currentLineFeed + 1, strlen($str));

		$nextLine = substr($remainingLines, 0, strpos($remainingLines, PHP_EOL));


		$markdown = identifier($prevLine, $currentLine, $markdown, $nextLine);

		// line done parsing. change start position to next line
				$str = $remainingLines;
				$currentLineFeed = strpos($str, PHP_EOL);

	}

	return $markdown;
}
		
//-------------------------------------------------------------------------------------------------
// identifier - identify what to parse and parse it
//-------------------------------------------------------------------------------------------------
		
function identifier($prevLine, $currentLine, $markdown, $nextLine) {
		// handle ordered lists
		if(isOrderdList($currentLine) || isUnorderdList($currentLine)) {
				$markdown .= handleLists($currentLine, $prevLine, $nextLine);
		}else if(isTable($currentLine)){
				// handle tables
				$markdown .= handleTable($currentLine, $prevLine, $nextLine);
		}else{
				// If its ordinary text then show it directly
				$newestLine=markdownBlock($currentLine);
				if(!preg_match('/\<\/h/', $newestLine)){
						$newestLine.= "<br>";
				}
				$markdown.=$newestLine;
		}

		// close table
		if(!isTable($currentLine) && !isTable($nextLine)){
				$markdown .= "</tbody></table>";
		}
		return $markdown;
}
		
//-------------------------------------------------------------------------------------------------
// isORderedList - Check if its an ordered list / unorderedlist/table
//-------------------------------------------------------------------------------------------------		

function isOrderdList($item) {
		// return 1 if ordered list
		//return preg_match('/^\s*\d+\.\s(.*)/', $item);
		return preg_match('/^\s*\d\.\s.*$/', $item);
}

// Check if its an unordered list
function isUnorderdList($item) {
		// return 1 if unordered list
		//return preg_match('/^\s*(\-|\*)\s+[^|]/', $item); // doesn't support dash like markdown!
		return preg_match('/^\s*[\-\*]\s.*$/', $item);
}

// Check if its a table
function isTable($item) {
		// return 1 if space followed by a pipe-character and have closing pipe-character
		//return preg_match('/^\s*\|\s*(.*)\|/', $item);
		return false; // disabled for now
}

//-------------------------------------------------------------------------------------------------
// handleLists - The creation and destruction of lists
//-------------------------------------------------------------------------------------------------			

function handleLists($currentLine, $prevLine, $nextLine) {
		global $openedSublists;
		$markdown = "";
		$value = "";
		//$currentLineIndentation = substr_count($currentLine, ' ');
		//$nextLineIndentation = substr_count($nextLine, ' ');          
		$currentLineIndentation = strlen($currentLine)-strlen(ltrim($currentLine));
		$nextLineIndentation = strlen($nextLine)-strlen(ltrim($nextLine));
		// decide value
		if(isOrderdList($currentLine)) $value = preg_replace('/^\s*\d*\.\s(.*)/','$1',$currentLine);        
		if(isUnorderdList($currentLine)) $value = preg_replace('/^\s*[\-\*]\s(.*)/','$1',$currentLine);        
		// Open new ordered list
		if(!(isOrderdList($prevLine) || isUnorderdList($prevLine)) && isOrderdList($currentLine)) {
				$markdown .= "<ol>"; // Open a new ordered list
				if($openedSublists!=null) array_push($openedSublists,0);
		}
		if(!(isUnorderdList($prevLine) || isOrderdList($prevLine) ) && isUnorderdList($currentLine)){
				$markdown .= "<ul>"; //Open a new unordered list          
				if($openedSublists!=null) array_push($openedSublists,1);
		} 
		 // Open a new sublist
		if($currentLineIndentation < $nextLineIndentation && (isUnorderdList($nextLine) || isOrderdList($nextLine))) {
				$markdown .= "<li>";
				$markdown .=  markdownBlock($value);
				// begin open sublist
				if(isOrderdList($nextLine)) {
						$markdown .= "<ol>";
						array_push($openedSublists,0);
				} else {
						$markdown .= "<ul>";
						array_push($openedSublists,1);
				}
		}

		// Stay in current list or sublist OR next line is not a list line
		if($currentLineIndentation === $nextLineIndentation) {
					$markdown .= "<li>";
					$markdown .=  markdownBlock($value);
					$markdown .= "</li>";
		}
		
		// Close sublists
		if($currentLineIndentation > $nextLineIndentation) {
				$markdown .= "<li>";
				$markdown .=  markdownBlock($value);
				$markdown .= "</li>";
				$sublistsToClose = ($currentLineIndentation - $nextLineIndentation) / 2;
				for($i = 0; $i < $sublistsToClose; $i++) {
						if($openedSublists!=null){
								$whatSublistToClose = array_pop($openedSublists);
						}else{
								$whatSublistToClose=0;
						}

						if($whatSublistToClose === 0) { // close ordered list
								$markdown .= "</ol>";
						} else { // close unordered list
								$markdown .= "</ul>";
						}
						$markdown .= "</li>";
				}
		}

		// Close all open lists if no more list rows are detected
		if(!(isOrderdList($nextLine) || isUnorderdList($nextLine) )) {
			$sublistsToClose=sizeof($openedSublists);
			for($i = $sublistsToClose; $i > 0; $i--) {
					$whatSublistToClose = array_pop($openedSublists);

					if($whatSublistToClose === 0) { // close ordered list
							$markdown .= "</ol>";
					} else { // close unordered list
							$markdown .= "</ul>";
					}
					if($i>1){
							$markdown .= "</li>";                
					}
			}
		}
		return $markdown;
}
		

//-------------------------------------------------------------------------------------------------
// handleTable - Handling of tables
//-------------------------------------------------------------------------------------------------	

function handleTable($currentLine, $prevLine, $nextLine) {
		global $tableAlignmentConf;
		$markdown = "";
		$columns = array_values(array_map("trim", array_filter(explode('|', $currentLine), function($k) {
				return $k !== '';
		})));
		// open table
		if(!isTable($prevLine)) {
				$markdown .= "<table class='markdown-table'>";
		}
		// create thead
		if(!isTable($prevLine) && preg_match('/^\s*\|\s*[:]?[-]*[:]?\s*\|/', $nextLine)) {
				$markdown .= "<thead>";
				$markdown .= "<tr>";
				for($i = 0; $i < count($columns); $i++) {
						$markdown .= "<th>".$columns[$i]."</th>";
				}
				$markdown .= "</tr>";
				$markdown .= "</thead>";
		}
		// create tbody
		else {
				// configure alignment
				if(preg_match('/^\s*\|\s*[:]?[-]*[:]?\s*\|/', $currentLine)) {
						for($i = 0; $i < count($columns); $i++) {
								// align center
								if(preg_match('/[:][-]*[:]/', $columns[$i])) $tableAlignmentConf[$i] = 1;
								// align right
								else if(preg_match('/[-]*[:]/', $columns[$i])) $tableAlignmentConf[$i] = 2;
								// align left
								else $tableAlignmentConf[$i] = 3;
						}
				}
				// handle table row
				else {
						$markdown .= "<tr style=''>";
						for($i = 0; $i < count($columns); $i++) {
								$alignment = "";

								if($tableAlignmentConf[$i] === 1) $alignment = "center";
								else if($tableAlignmentConf[$i] === 2) $alignment = "right";
								else $alignment = "left";
								if(preg_match('/^[*].{1}\s*(.*)[*].{1}/',$columns[$i])){
										$markdown .= "<td style='text-align: " . $alignment . ";font-weight:bold;'>" . preg_replace('/[*].{1}/', '', $columns[$i]) . "</td>";
		}else if(preg_match('/^[*].{0}\s*(.*)[*].{0}/',$columns[$i])){
										$markdown .= "<td style='text-align: " . $alignment . ";font-style:italic'>" . preg_replace('/[*].{0}/', '', $columns[$i]) . "</td>";
						}else if(preg_match('/^[`].{0}\s*(.*)[`].{0}/',$columns[$i])){
										$markdown .= "<td style='text-align: " . $alignment . ";'><code style='border-radius: 3px; display: inline-block; color: white; background: darkgray; padding: 2px;''>" . preg_replace('/[`].{0}/', '', $columns[$i]) . "</code></td>";
						}else{
								$markdown .= "<td style='text-align: " . $alignment . ";'>" . $columns[$i] . "</td>";
						}
						}
						$markdown .= "</tr>";
				}
		}
		return $markdown;
}
		
//-------------------------------------------------------------------------------------------------
// markdownBlock - Block of markdown
//-------------------------------------------------------------------------------------------------			

function markdownBlock($instring)
{
		//Regular expressions for italics
		$instring = preg_replace("/\*{4}(.*?)\*{4}/", "<strong><em>$1</em></strong>",$instring);	
		$instring = preg_replace("/\*{3}(.*?)\*{3}/", "<em>$1</em>",$instring);	
		$instring = preg_replace("/\*{2}(.*?)\*{2}/", "<em>$1</em>",$instring);	

		// Bold
		$instring = preg_replace("/\_{4}(.*?)\_{4}/", "<strong><em>$1</em></strong>",$instring);	
		$instring = preg_replace("/\_{3}(.*?)\_{3}/", "<strong>$1</strong>",$instring);	
		$instring = preg_replace("/\_{2}(.*?)\_{2}/", "<strong>$1</strong>",$instring);	

		// Headings -- 6 levels
		$instring = preg_replace("/^\#{6}\s(.*)=*/m", "<h6>$1</h6>",$instring);	
		$instring = preg_replace("/^\#{5}\s(.*)=*/m", "<h5>$1</h5>",$instring);	
		$instring = preg_replace("/^\#{4}\s(.*)=*/m", "<h4>$1</h4>",$instring);	
		$instring = preg_replace("/^\#{3}\s(.*)=*/m", "<h3>$1</h3>",$instring);	
		$instring = preg_replace("/^\#{2}\s(.*)=*/m", "<h2>$1</h2>",$instring);	
		$instring = preg_replace("/^\#{1}\s(.*)=*/m", "<h1>$1</h1>",$instring);	

		//Regular expression for line
		$instring = preg_replace("/^(\-{3}\n)/m", "<hr>",$instring);

		// Hard line break support
		//$instring= preg_replace ("/(\r\n|\n|\r){3}/","<br><br>",$instring);
		//$instring= preg_replace ("/(\r\n|\n|\r)/","<br>",$instring);

		// Fix for swedish characters
		$instring= str_replace ("å","&aring;",$instring);				
		$instring= str_replace ("Å","&Aring;",$instring);				
		$instring= str_replace ("ä","&auml;",$instring);				
		$instring= str_replace ("Ä","&Auml;",$instring);				
		$instring= str_replace ("ö","&ouml;",$instring);				
		$instring= str_replace ("Ö","&Ouml;",$instring);				

		// a href Link
		// !!!url,text to show!!!
		$instring = preg_replace("/\!{3}(.*?\S),(.*?\S)\!{3}/","<a href='$1' target='_blank'>$2</a>",$instring);

		// External img src !!!
		// |||src|||	
		//$instring = preg_replace("/\|{3}(.*?\S)\|{3}/","<img src='$1' />",$instring);        
		$instring = preg_replace("/\|{3}(.+),([0-9]+)?,([0-9]+)?\|{3}/","<img class='imgzoom' src='$1' onmouseover='originalImg(this, $3)' onmouseout='thumbnailImg(this, $2)' width='$2px' style='border: 3px solid #614875;' />",$instring);
		$instring = preg_replace("/\|{3}(.*?\S)\|{3}/","<img src='$1' />",$instring);

		// External mp4 src !!!
		// ==[src]==	
		$instring = preg_replace("/\={2}\[(.*?\S)\]\={2}/","<video width='80%' style='display:block; margin: 10px auto;' controls><source src='$1' type='video/mp4'></video>",$instring);

		// External mp4 src !!!
		// ==[src]==	
		$instring = preg_replace("/\={2}\{(.*?\S)}\={2}/","<span id='placeholder-$1'></span>",$instring);

		// Image Movie Link format: <img src="pngname.png" class="gifimage" onclick="showGif('gifname.gif');"/>
		// +++image.png,image.gif,id+++
		$instring = preg_replace("/\+{3}(.*?\S),(.*?\S)\+{3}/","<div class='gifwrapper'><img class='gifimage' src='$1' onclick=\"toggleGif('$2', '$1', this);\" target='_blank' /><img class='playbutton' src='../Shared/icons/PlayT.svg'></div>",$instring);

		// Right Arrow for discussing menu options
		$instring = preg_replace("/\s[\-][\>]\s/","&rarr;",$instring);

		// Strike trough text
		$instring = preg_replace("/\-{4}(.*?\S)\-{4}/","<span style=\"text-decoration:line-through;\">$1</span>",$instring);

		// Importand Rows in code file in different window ===
		// ===filename,start row,end row, text to show===
		$instring = preg_replace("/\={3}(.*?\S),(.*?\S),(.*?\S),(.*?\S)\={3}/", "<span class='impword' onmouseover=\"highlightRows(\'$1\',$2,$3)\" onmouseout=\"dehighlightRows(\'$1\',$2,$3)\">$4</span>", $instring);

		// Three or more dots should always be converted to an ellipsis.
		$instring = preg_replace("/\.{3,}/", "&hellip;", $instring);

		// Iframe, website inside a inline frame - (--url,width,height--)
		$instring = preg_replace("/\(\-{2}(.*?\S),(.*?\S),(.*?\S)\-{2}\)/", "<iframe src='$1' style='width:$2px; height:$3px;'></iframe>", $instring);

		// Quote text, this will be displayed in an additional box
		// ^ Text you want to quote ^
		$instring = preg_replace("/\^{1}\s(.*?\S)\s\^{1}/", "<blockquote>$1</blockquote><br/>", $instring);

		return $instring;		
}

//-------------------------------------------------------------------------------------------------
// colorize - Assign the class depending on the kind of token
//-------------------------------------------------------------------------------------------------
		
$tags=array("body"=>"htmltag","html"=>"htmltag","style"=>"htmltag","table"=>"htmltag","tr"=>"htmltag","td"=>"htmltag","foreach"=>"func","if"=>"func","array"=>"func","echo"=>"func","head"=>"htmltag");
		
function colorize($token,$prevop)
{
		global $tags;

		$testtoken=trim($token);
		$token=str_replace("\t","&nbsp;&nbsp;",$token);
		
		if((isset($tags[$testtoken]))&&($prevop=="<")){ // 
				return "<span class='".$tags[$testtoken]."'>".$token."</span>";
		}else if((isset($tags[$testtoken]))&&($prevop=="/")){
				return "<span class='".$tags[$testtoken]."end'>".$token."</span>"; // </&gt;></div>
		}else if((isset($tags[$testtoken]))){
				return "<span class='".$tags[$testtoken]."'>".$token."</span>";		
		}else if($prevop=="$"){
				return "<span class='variable' onmouseover='daxby(event)' onmouseout='clearvar();' >".$token."</span>";				
		}
		return $token;
}

//-------------------------------------------------------------------------------------------------
// fixhtml - Removes html tags by swapping < and > for html entities
//-------------------------------------------------------------------------------------------------
		
function fixhtml($token)
{
		$token=str_replace("<","&lt;",$token);
		$token=str_replace(">","&gt;",$token);
		return $token;
}

//-------------------------------------------------------------------------------------------------
// syntax - Syntax highlighting for php/javascript/html
//-------------------------------------------------------------------------------------------------

function syntax($content)
{
		global $tags;
		$contentarr=explode("\n",$content);
	
		$divider=0;
		$ret="";
		$strmode=0;	
		foreach($contentarr as $contentrow){
				$length = strlen($contentrow);
				$token="";
				$prevop="";
				$strmode=0;
				$tabs="";			
				for ($i=0; $i<$length; $i++) {
						$curstr=$contentrow[$i];
						if($curstr=="/"&&$prevop=="/"&&$strmode!=2){
								$ret.=colorize($token,$prevop);
								$prevop=$curstr;	
								$strmode=2;
								$token="";
						}else if($curstr=='"'&&$strmode==0){
								// Start string mode!
								$ret.=colorize($token,$prevop);
								$prevop=$curstr;	
								$strmode=1;
								$token="";							
						}else if($curstr=='"'&&$strmode==1){
								// Print string and disable string mode!
								$ret.="<span class='string'>&quot;".fixhtml($token)."&quot;</span>";						
								$strmode=0;
								$token="";
						}else if($strmode==1||$strmode==2){
								// Process content in string mode!
								if($curstr=="<") $curstr="&lt;";
								if($curstr==" ") $curstr="&nbsp;";
								$token.=$curstr;						
						}else if($curstr=="\t"){
								$tabs.="&nbsp;&nbsp;";
						}else if($curstr==" "||$curstr=="<"||$curstr==">"||$curstr==","||$curstr==":"||$curstr=="/"||$curstr=="("||$curstr==")"||$curstr=="."||$curstr=="{"||$curstr=="}"||$curstr=="$"||$curstr=="="||$curstr=="-"){
								// If there are tabs ... add in before token
								if($tabs!=""){
										//if($curstr=="<") $ret.="<div style='border-left:1px solid red;'>";
										$ret.=$tabs;
										$tabs="";
								}
								$ret.=colorize($token,$prevop);
								$prevop=$curstr;							
								if($curstr=="<") $curstr="&lt;";
								if($curstr==">") $curstr="&gt;";
								if($curstr!=" "){
										$ret.="<span onmouseover='bixby(event)' onmouseout='clearhigh();' class='divider' id='".$divider."' >".$curstr."</span>";
										$divider++;
								}else{
										$ret.=$curstr;
								}
								$token="";
						}else{
								$token.=$curstr;
						}
				}

				if($strmode!=2){
						if($tabs!=""){
								//if($curstr=="<") $ret.="<div style='border-left:1px solid red;'>";
								$ret.=$tabs;
								$tabs="";
						}					
						$ret.=colorize($token,$prevop)."<br>";
				}else{
						// We are at end of line and print preceding comment line
						$ret.="<span class='comment'>/".$token."</span><br>";							
				}
		}
	
		return $ret;
}
		
//-------------------------------------------------------------------------------------------------
// pretty - HTML / XML Pretty Printing
//-------------------------------------------------------------------------------------------------	
function pretty($content)
{
		$ret="";
		$mode=0;
		$line="";
		$indent=0;
		$tag="";
		$prev="";

		$length = strlen($content);
		for ($i=0; $i<$length; $i++) {
				$curstr=$content[$i];
				if($curstr=='\n'||$curstr=='\t'){
						// Ignore enter and tab
				}else{
						if($mode==0&&$curstr=='<'){
								// Start tag mode
								$tag=$curstr;
								$mode=1;
						}else if($mode==1&&$curstr=='>'){
								$tag.=$curstr;
								$mode=0;
								// If end tag write \n after tag -- if start tag 
								if(strpos($tag,"/")!=false){
										$indent--;
										if($prev=="tag") $ret.="\n".indent($indent);
										$ret.=$tag;
								}else{
										if($prev=="tag") $ret.="\n";
										$ret.=indent($indent).$tag;
										$indent++;
								}
								$prev="tag";
						}else if($mode==1){
								$tag.=$curstr;
						}else if($mode==0){
								// Text mode
								$ret.=$curstr;
								$prev="text";
						}
				}
		}
	
		if($mode==1){
			$ret.=indent($indent).$tag;
		}
	
		return $ret;
}
		
function indent($levels)
{
		$str="";
		for($i=0;$i<$levels;$i++){
				$str.="\t";
		}
		return $str;
}
		
if($filetype=="php"||$filetype=="js"||$filetype=="html"){
		$content = file_get_contents(getcwd().$filename);
		echo "<div style='font-family:courier;'>";
		if($filetype=="html"){
				echo syntax(pretty($content));
		}else{
				echo syntax($content);
		}
		echo "</div>";
}else if($filetype=="png"||$filetype=="svg"){
		echo "<img src='".$filename."'>";
}else if($filetype=="UNK"){
		// Assume directory
		//$filename=substr($filename,0,strpos($filename,"?"));
		echo "<iframe style='width:100%;height:100%;' src='".$filename."?nobread=true'></iframe>";
}else if($filetype=="md"){
		$content = file_get_contents("http://localhost".$filename);
		echo parseMarkdown($content);
}else{
		// No preview
}

?>
	</body>
</html>