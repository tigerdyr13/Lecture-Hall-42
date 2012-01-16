<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Kristian Skårhøj <kristians25@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Lecture Comments' for the 'aud42cmtscr' extension.
 *
 * @author	Kristian Skårhøj <kristians25@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_aud42cmtscr
 */
class tx_aud42cmtscr_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_aud42cmtscr_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_aud42cmtscr_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'aud42cmtscr';	// The extension key.
	
	/**
	 * Main method of your PlugIn
	 *
	 * @param	string		$content: The content of the PlugIn
	 * @param	array		$conf: The PlugIn Configuration
	 * @return	The content that should be displayed on the website
	 */
	function main($content, $conf)	{
	
	
		
		switch((int)$this->cObj->data['tx_aud42cmtscr_mode'])	{
			case 0:				
				$returnValue=$this->submitComment();			
			break;
			case 1:
				$returnValue=$this->editComment();
			break;
			case 2:
				$returnValue=$this->userCommentList();
			break;
			case 3:
				$returnValue=$this->adminCommentList();
			break;
			case 4:
				$returnValue=$this->auditoryScreen();
			break;
			default:
				return $this->pi_wrapInBaseClass($this->submitComment());
			break;
		}
		
		return $returnValue;
	}
	
	
		/**
		 * Shows a formfield thorugh wich users can submit a comment
		 *
		 * @return	HTML list of table entries
		 */

		function submitComment() {
			global $TYPO3_DB, $TSFE;

			$data=t3lib_div::_POST(); // definer vairablen $data til at bestå af formens afsendte data
			
		
			// processerer data		
			if($data['comment_send']!='' && trim($data["user_comment"])!='') {
				$frontEndUserRecord = $TSFE->fe_user->user;
				$fieldArray = array (
								'pid' => 5, 
								'comment' => trim($data["user_comment"]),
								'crdate' => time(),
								'fe_user_uid' => $frontEndUserRecord["uid"],
								'fe_user_name' => $frontEndUserRecord["username"]
							 );
				
				$TYPO3_DB->exec_INSERTquery("tx_aud42cmtscr_comments", $fieldArray);
			}

	//formaterer arrayet "comments" + laver html visning.


	        $output = '<div id="portalmessageszone" class="wpz nolayoutchange">
	            <div class="webpart">
					<div class="topbar">
						<span class="topBarTitle">Stil et spørgsmål:</span>
					</div>
					<div class="inner">';			
			$output.= '<form method="post" action="'.t3lib_div::linkThisScript().'">';
			$output.= '<textarea name="user_comment" rows="10" cols="65"></textarea><br/>';
			$output.= '<input type="submit" name="comment_send" value="Spørg">';
			$output.= '</form></div></div></div>';

			return($output);
		}
	
	function editComment() {
				global $TYPO3_DB, $TSFE;

				if (count($_POST)) {
				    // process the POST data
		 		    $this->add_comment($_POST);

				    // redirect to the same page without the POST data
				    header("Location: http://localhost:8888/Auditorie42/index.php?id=4");
				    die;
				}	
			
				
					

					

				//formaterer arrayet "comments" + laver html visning.

						$oldComment = $TYPO3_DB->exec_SELECTgetRows('comment', "tx_aud42cmtscr_comments", "uid=".intval($this->piVars['uid']));

				        $output = '<div id="portalmessageszone" class="wpz nolayoutchange">
				            <div class="webpart">
								<div class="topbar">
									<span class="topBarTitle">Rediger dit spørgsmål:</span>
								</div>
								<div class="inner">';			
		//				$output.= '<form method="post" action="index.php?id=32">';
						$output.= '<form method="post" action="'.t3lib_div::linkThisScript().'">';			
						$output.= '<textarea name="user_comment" rows="10" cols="65">'.$oldComment[0]['comment'].'</textarea><br/>';
						$output.= '<input type="submit" name="comment_send" value="Send dit ændrede spørgsmål">';
						$output.= '</form></div></div></div>';
						$output.='<div><a href="index.php?id=4">Go back to view list of user comments.</a></div>';


						return($output);
									
				
			
		}
	
		
	function add_comment() {
		global $TYPO3_DB;		
		// processerer data	fra redigering af kommentar					
		$data=t3lib_div::_POST(); // definer vairablen $data til at bestå af formens afsendte data
		if($data['comment_send']!='' && trim($data["user_comment"])!='') {
			$frontEndUserRecord = $TSFE->fe_user->user;
			$fieldArray = array (
							'pid' => 5, 
							'comment' => trim($data["user_comment"]),
							'crdate' => time(),
						 );

			$TYPO3_DB->exec_UPDATEquery("tx_aud42cmtscr_comments", "uid=".intval($this->piVars['uid']), $fieldArray);
		}
	}
	/**
	 * Shows a list of database entries
	 *
	 * @param	string		$content: content of the PlugIn
	 * @param	array		$conf: PlugIn Configuration
	 * @return	HTML list of table entries
	 */
	
	

	
	
	
	
	function userCommentList() {
			# Opdaterer siden hvert 10. sekund:

//			$refreshUrl = $this->pi_linkTP_keepPIvars_url(array(), 0, TRUE);
//			$GLOBALS['TSFE']->additionalHeaderData['admPanelCSS-Skin'] = '<meta http-equiv="refresh" content="10;url='.$refreshUrl.'">';

			global $TYPO3_DB;



	// processerer data		
			if($this->piVars['cmd']=='delete') {
				$TYPO3_DB->exec_UPDATEquery("tx_aud42cmtscr_comments", "uid=".intval($this->piVars['uid']), array("deleted"=>1));
			}


	//formaterer arrayet "comments" + laver html visning.
			$comments = $TYPO3_DB->exec_SELECTgetRows('comment, uid, status, crdate, fe_user_uid, fe_user_name', "tx_aud42cmtscr_comments", "pid=5 AND deleted=0", "", "crdate DESC");

			$output = '	<div id="portalmessageszone" class="wpz nolayoutchange">
							<div class="webpart">
								<div class="topbar">
									<span class="topBarTitle">Spørgsmål i kø:</span>
								</div>

								<div class="inner">
									<div style="padding: 10px 15px 15px;" id="myportalmessages">';

								/**
								 * Sætter prefikset sek/min/tim på tiden
								 *
								 */
								function _ago($tm,$rcs = 0) {
								    $cur_tm = time(); $dif = $cur_tm-$tm;
								    $pds = array('sek','min','tim','dag','uge','måned','år','årti');
								    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
								    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--);
										if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
								    	$no = floor($no); if($no <> 1) $pds[$v] .=''; $x=sprintf("%d %s ",$no,$pds[$v]);
								    	if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
								    return $x;
								}


			foreach($comments as $row) { 									
					//Tid spørgsmålet har ligget

					$output.='
						<div class="portalmessage">
						<div class="date">'._ago(intval($row['crdate'])).'</div>
									<div class="msg" style="';

					$output.='" >';		

					// Selve kommentaren
					$linkingParameters = array('cmd' => 'show', 'uid' => $row["uid"]);
					$output.='<div>'.htmlspecialchars($row["comment"]).'</div>';

					//Brugernavn
					$output.='	<div class="senderinfo" title=""><img src="fileadmin/templates/default_files/user.png" alt="">';
								if($row['fe_user_uid']!='') {
								$userName='&nbsp;'.$row['fe_user_name'];
								}
								else {				
								$userName='&nbsp;(anonym)';
								}
					$output.=$userName;
					$output.='	</div>';

					$output.='	</div>';

					//slet/vis knapper:			
//					$output.='<form method="post" action="'.t3lib_div::linkThisScript().'">';
//					$output.='<input type="hidden" name="uid" value="'.$row["uid"].'">';
					$output.='<div style="float: right;">';

					if ($row['fe_user_uid']=!'' && $row['fe_user_uid']===$GLOBALS['TSFE']->fe_user->user['uid'])	{
						$linkingParameters = array('cmd' => 'edit', 'uid' => $row["uid"]);												
						$output.='&nbsp;'.$this->pi_linkTP_keepPIvars(
													'<img src="fileadmin/templates/default_files/edit.png">',												
													$linkingParameters,
													0,
													0,
													31
												);
						$linkingParameters = array('cmd' => 'delete', 'uid' => $row["uid"]);
						$output.=$this->pi_linkTP_keepPIvars(		
													'<img src="fileadmin/templates/default_files/delete-silk.png">',
													$linkingParameters,
													0,
													TRUE
												);
					}
					

					$output.='</div>';
//					$output.='</form>';




					$output.='	<div style="clear: both;">
								</div>
								</div>';

					//Slet alle box


			}
					$output.='	<div style="float: right; height: 20px; background-color: black;">
									<a onclick="alert();return false;" href="#" title="Slet ALLE">
									<span style="color: white;">SLET ALLE&nbsp;</span><img src="fileadmin/templates/default_files/delete-silk-all.png" alt="Luk" align="absmiddle">
									</a>
								</div>';
					$output.='	</div></div></div></div>';

			return($output);

		}
	


		function adminCommentList() {
						# Opdaterer siden hvert 10. sekund:

						$refreshUrl = $this->pi_linkTP_keepPIvars_url(array(), 0, TRUE);
						$GLOBALS['TSFE']->additionalHeaderData['admPanelCSS-Skin'] = '<meta http-equiv="refresh" content="10;url='.$refreshUrl.'">';

						global $TYPO3_DB;


				// processerer data		
						debug($this->piVars);
						if($this->piVars['cmd']=='delete') {
							$TYPO3_DB->exec_UPDATEquery("tx_aud42cmtscr_comments", "uid=".intval($this->piVars['uid']), array("deleted"=>1));
						}

						$statusNOW = $TYPO3_DB->exec_SELECTgetRows('status', "tx_aud42cmtscr_comments", "uid=".intval($this->piVars['uid']));
						if($this->piVars['cmd']=='show') {
								if($statusNOW[0]['status']=='0') {
				//					$TYPO3_DB->exec_UPDATEquery("tx_aud42cmtscr_comments", 'pid=5 AND status=1 AND deleted=0', array("status"=>0));
									$TYPO3_DB->exec_DELETEquery("tx_aud42cmtscr_comments", 'pid=5 AND status=1 AND deleted=0', array("deleted"=>1));
									$TYPO3_DB->exec_UPDATEquery("tx_aud42cmtscr_comments", "uid=".intval($this->piVars['uid']), array("status"=>1));
								}
								if($statusNOW[0]['status']=='1') {
									$TYPO3_DB->exec_UPDATEquery("tx_aud42cmtscr_comments", "uid=".intval($this->piVars['uid']), array("status"=>0));
								}
						}

				//formaterer arrayet "comments" + laver html visning.

						$comments = $TYPO3_DB->exec_SELECTgetRows('comment, uid, status, crdate, fe_user_uid, fe_user_name', "tx_aud42cmtscr_comments", "pid=5 AND deleted=0", "", "crdate DESC");

						$output = '	<div id="portalmessageszone" class="wpz nolayoutchange">
										<div class="webpart">
											<div class="topbar">
												<span class="topBarTitle">Spørgsmål i kø:</span>
											</div>

											<div class="inner">
												<div style="padding: 10px 15px 15px;" id="myportalmessages">';

											/**
											 * Sætter prefikset sek/min/tim på tiden
											 *
											 */
											function _ago($tm,$rcs = 0) {
											    $cur_tm = time(); $dif = $cur_tm-$tm;
											    $pds = array('sek','min','tim','dag','uge','måned','år','årti');
											    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
											    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--);
													if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
											    	$no = floor($no); if($no <> 1) $pds[$v] .=''; $x=sprintf("%d %s ",$no,$pds[$v]);
											    	if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
											    return $x;
											}


						foreach($comments as $row) { 									
								//Tid spørgsmålet har ligget

								$output.='
									<div class="portalmessage" style="'.($row['status']==1 ? 'background-color:grey;' : '').'">
									<div class="date">'._ago(intval($row['crdate'])).'</div>
												<div class="msg" style="';

								$output.='" >';		

								// Selve kommentaren
								$linkingParameters = array('cmd' => 'show', 'uid' => $row["uid"]);
								$output.='	<div>
												'.$this->pi_linkTP_keepPIvars(
													htmlspecialchars($row["comment"]),
													$linkingParameters,
													0,
													TRUE
												).'
											</div>';

								//Brugernavn
								$output.='	<div class="senderinfo" title=""><img src="fileadmin/templates/default_files/user.png" alt="">';
											if($row['fe_user_uid']!='') {
											$userName='&nbsp;'.$row['fe_user_name'];
											 
											}
											else {				
											$userName='&nbsp;(anonym)';
											}
								$output.=$userName;
								$output.='	</div>';

								$output.='	</div>';

								//slet/vis knapper:			
								$output.='<form method="post" action="'.t3lib_div::linkThisScript().'">';
								$output.='<input type="hidden" name="uid" value="'.$row["uid"].'">';
								$output.='<div style="float: right;">';

								$linkingParameters = array('cmd' => 'delete', 'uid' => $row["uid"]);
								$output.=$this->pi_linkTP_keepPIvars(
															'<img src="fileadmin/templates/default_files/delete-silk.png">',
															$linkingParameters,
															0,
															TRUE
														);

								$output.='</div></form>';




								$output.='	<div style="clear: both;">
											</div>
											</div>';

								//Slet alle box


						}
								$output.='	<div style="float: right; height: 20px; background-color: black;">
												<a onclick="alert();return false;" href="#" title="Slet ALLE">
												<span style="color: white;">SLET ALLE&nbsp;</span><img src="fileadmin/templates/default_files/delete-silk-all.png" alt="Luk" align="absmiddle">
												</a>
											</div>';
								$output.='	</div></div></div></div>';

						return($output);

					}
					
					
	
	
	function auditoryScreen() {		
		global $TYPO3_DB;
	
		# Opdaterer siden hvert 10. sekund:
		$GLOBALS['TSFE']->additionalHeaderData['admPanelCSS-Skin'] = '<meta http-equiv="refresh" content="4">';
		
		
		//Er der overhovedet spørgsmål?
		$anyComments = $TYPO3_DB->exec_SELECTgetRows('comment, uid, status', "tx_aud42cmtscr_comments", "pid=5 AND deleted=0", "", "crdate DESC");
		if(count($anyComments)!=0) {
			$openComments = $TYPO3_DB->exec_SELECTgetRows('comment, uid, status, fe_user_name', "tx_aud42cmtscr_comments", "pid=5 AND deleted=0 AND status=1", "", "crdate DESC");
			$pendingComments = $TYPO3_DB->exec_SELECTgetRows('comment, uid, status', "tx_aud42cmtscr_comments", "pid=5 AND deleted=0 AND status=0", "", "crdate DESC");
			//Hvis der er åbne spørgsmål, vis dem da
			if(count($openComments)!=0) {
				foreach($openComments as $row) {
					
					//rutine der beregner font-størrelsen i forhold til antallet af karakterer i spørgsmålet
					$commentSize = array(0,50,100,150,200,250,300,350,400,450);
					$fontSize = array(95,87,78,71,64,58,54,48,46,44);
					$charCount = strlen($row['comment']);		
					for($a = 0; ($a <= sizeof($commentSize))&&($charCount > $commentSize[$a]); $a++);
					$commentFont = intval($fontSize[$a]);
					
					$output='<div style="width:110px;text-align:center;position:absolute;top:175px;left:40px;"><img src="fileadmin/templates/default_files/questioner.png" width="105" height="95" />';
					$output.='<div style="margin-top:5px;color:white;font-family:arial;font-size:16px;font-weight:bold;font-style:italic;text-align:center;">';

					// Skriver brugernavnet
					if($row['fe_user_name']=='') {
						$output.='anonym';
						}
					else {
						$output.= htmlspecialchars($row["fe_user_name"]);
						}
					$output.='</div></div>';
					$output.='<div style="color:white;font-family:arial;font-size:'.$commentFont.'px;font-weight:bold;width:1085px;height:570px;position:absolute;top:175px;left:180px;">'.htmlspecialchars($row["comment"]).'</div>';
					}			
			}
			//Hvis der er uåbnede spørgsmål, vis da køtal
			else {

				$output ='<div align="center" style="position:absolute;top:200;left:450;width:373px;height:316px;">';
				$output.='<div style="color:white;font-family:arial;font-size:75px;font-weight:bold;position:relative;top:300;left:128;width:115px;text-align:center;">'.count($pendingComments).'
							</div>
							<img src="fileadmin/templates/default_files/questionQue.png" alt="Der er spørgsmål i kø!" height="316" width="373" />
							</div>';
			}
		}
		//Hvis der ingen spørgsmål er, vis da "klokken"
		else {
			$clock_x = rand(0,1000);
			$clock_y = rand(160,680);
			$output = '<div style="color:white;font-family:arial;font-size:75px;position:absolute;top:'.$clock_y.';left:'.$clock_x.';width:285px;"><img style="vertical-align:-10%;" src="fileadmin/templates/default_files/clock2.png" alt="Klokken er" height="70" width="70" /> '.date('H:i',time()).'</div>';
		}

		return($output);
			
	}
	
	/**
	 * Creates a list from a database query
	 *
	 * @param	ressource	$res: A database result ressource
	 * @return	A HTML list if result items
	 */
	function makelist($res)	{
		$items=array();
			// Make list table rows
		while($this->internal['currentRow'] = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$items[]=$this->makeListItem();
		}
	
		$out = '<div'.$this->pi_classParam('listrow').'>
			'.implode(chr(10),$items).'
			</div>';
		return $out;
	}
	
	/**
	 * Implodes a single row from a database to a single line
	 *
	 * @return	Imploded column values
	 */
	function makeListItem()	{
		$out='
				<p'.$this->pi_classParam('listrowField-fe-user-uid').'>'.$this->getFieldContent('fe_user_uid').'</p>
				<p'.$this->pi_classParam('listrowField-status').'>'.$this->getFieldContent('status').'</p>
			';
		return $out;
	}
	/**
	 * Display a single item from the database
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	HTML of a single database entry
	 */
	function singleView($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj = 1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!
	
			// This sets the title of the page for use in indexed search results:
		if ($this->internal['currentRow']['title'])	$GLOBALS['TSFE']->indexedDocTitle=$this->internal['currentRow']['title'];
	
		$content='<div'.$this->pi_classParam('singleView').'>
			<H2>Record "'.$this->internal['currentRow']['uid'].'" from table "'.$this->internal['currentTable'].'":</H2>
			<table>
				<tr>
					<td nowrap="nowrap" valign="top"'.$this->pi_classParam('singleView-HCell').'><p>'.$this->getFieldHeader('comment').'</p></td>
					<td valign="top"><p>'.$this->getFieldContent('comment').'</p></td>
				</tr>
				<tr>
					<td nowrap="nowrap" valign="top"'.$this->pi_classParam('singleView-HCell').'><p>'.$this->getFieldHeader('fe_user_uid').'</p></td>
					<td valign="top"><p>'.$this->getFieldContent('fe_user_uid').'</p></td>
				</tr>
				<tr>
					<td nowrap="nowrap" valign="top"'.$this->pi_classParam('singleView-HCell').'><p>'.$this->getFieldHeader('status').'</p></td>
					<td valign="top"><p>'.$this->getFieldContent('status').'</p></td>
				</tr>
				<tr>
					<td nowrap'.$this->pi_classParam('singleView-HCell').'><p>Last updated:</p></td>
					<td valign="top"><p>'.date('d-m-Y H:i',$this->internal['currentRow']['tstamp']).'</p></td>
				</tr>
				<tr>
					<td nowrap'.$this->pi_classParam('singleView-HCell').'><p>Created:</p></td>
					<td valign="top"><p>'.date('d-m-Y H:i',$this->internal['currentRow']['crdate']).'</p></td>
				</tr>
			</table>
		<p>'.$this->pi_list_linkSingle($this->pi_getLL('back','Back'),0).'</p></div>'.
		$this->pi_getEditPanel();
	
		return $content;
	}
	/**
	 * Returns the content of a given field
	 *
	 * @param	string		$fN: name of table field
	 * @return	Value of the field
	 */
	function getFieldContent($fN)	{
		switch($fN) {
			case 'uid':
				return $this->pi_list_linkSingle($this->internal['currentRow'][$fN],$this->internal['currentRow']['uid'],1);	// The "1" means that the display of single items is CACHED! Set to zero to disable caching.
			break;
			
			default:
				return $this->internal['currentRow'][$fN];
			break;
		}
	}
	/**
	 * Returns the label for a fieldname from local language array
	 *
	 * @param	[type]		$fN: ...
	 * @return	[type]		...
	 */
	function getFieldHeader($fN)	{
		switch($fN) {
			
			default:
				return $this->pi_getLL('listFieldHeader_'.$fN,'['.$fN.']');
			break;
		}
	}
	
	/**
	 * Returns a sorting link for a column header
	 *
	 * @param	string		$fN: Fieldname
	 * @return	The fieldlabel wrapped in link that contains sorting vars
	 */
	function getFieldHeader_sortLink($fN)	{
		return $this->pi_linkTP_keepPIvars($this->getFieldHeader($fN),array('sort'=>$fN.':'.($this->internal['descFlag']?0:1)));
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/aud42cmtscr/pi1/class.tx_aud42cmtscr_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/aud42cmtscr/pi1/class.tx_aud42cmtscr_pi1.php']);
}

?>