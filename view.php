<?php
###############################################################################
# Copyright (c) 2009, Matthew F. Coates
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are met:
#
# 1. Redistributions of source code must retain the above copyright notice,
# this list of conditions and the following disclaimer.
# 2. Redistributions in binary form must reproduce the above copyright
# notice, this list of conditions and the following disclaimer in the
# documentation and/or other materials provided with the distribution.
# 3. All advertising materials mentioning features or use of this software
# must display the following acknowledgement:
# "This product includes software developed by Matthew F. Coates"
# 4. The name of the author "Matthew F. Coates" may not be used to endorse
# or promote products derived from this software without specific prior
# written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER "AS IS"
# AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
# ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER BE
# LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
# CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
# SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
# INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
# CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
# ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
# THE POSSIBILITY OF SUCH DAMAGE.
###############################################################################
include("config.php");
class View
{
  private $baseurl="http://localhost/~matt/webpoll/";
  private $db;
  private $results = false;
  private $links = false;
  private $question = false;
  private $id = -1;
  private $del = false;
  public function show()
  {
    global $db;
    $this->db = $db;
    $this->checkdb();
    $this->showpolls();
  }
  public function code()
  {
    global $db;
    $this->db = $db;
    $this->checkdb();
    $this->showcode();
  }
  public function setid($i) {$this->id = $i;}
  public function showresults() {$this->results = true;}
  public function showlinks() {$this->links = true;}
  public function showquestion() {$this->question = true;}
  public function showdel() {$this->del = true;}
  private function checkdb()
  {
    if (! $this->db)
    {
      echo("<p>Database error.</p>");
      exit();
    }
  }
  private function getpolls()
  {
    $pollquery = "SELECT * FROM polls";
    if ($this->id != -1)
      $pollquery = $pollquery." WHERE id=".$this->id;
    $qpolls=mysql_query($pollquery,$this->db);
    if (!$qpolls)
    {
      echo("<p>Database errors.</p>");
      exit();
    }
    return $qpolls;
  }
  private function showanswers($id)
  {
    $pollanswers = mysql_query("SELECT * FROM answers WHERE poll_id=".$id." ORDER BY id",$this->db);
    echo("<ol>");
    if (!$pollanswers)
    {
      echo("<p>Database error.</p>");
      exit();
    }
    $answers = array();
    $total = 0;
    while ($arow = mysql_fetch_array($pollanswers))
    {
      $answers[]=$arow;
      $total+=$arow["votes"];
    }
    foreach($answers as $arow)
    {
      $percent=0;
      if ($total > 0)
	$percent=round(($arow["votes"]/$total)*100);
      echo("\n<li>".$arow["answer"]);
      if ($this->del)
      {
  ?>
	<form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
	  <input type="hidden" name="pid" value="<? echo $id; ?>"/>
	  <input type="hidden" name="aid" value="<? echo $arow["id"]; ?>"/>
	  <input type="submit" name="deleteanswer" value="delete this answer"/>
	</form>
  <?
      }    
      if ($this->results)
	echo(" <div class='pbox'><div class='pbar' style='width:".$percent."%;'>".$arow["votes"]." votes, ".$percent."%</div></div>");
      echo("</li>");
    }

    echo("</ol>");
  }

  private function showpolls()
  {
    $qpolls = $this->getpolls();
    echo("<ul>");
    while ($row = mysql_fetch_array($qpolls))
    {
      echo("<li>".$row["title"]);
      if ($this->question)
	echo(": <b>".$row["question"]."</b>");
      if ($this->del)
      {
  ?>
	<form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
	  <input type="hidden" name="id" value="<? echo $row["id"]; ?>"/>
	  <input type="submit" name="deletepoll" value="delete this poll"/>
	</form>
  <?
      }    

      if ($this->links)
      {
	echo("\n <a href=\"admin.php?id=".$row["id"]."&edit=1\"><small>edit</small></a>");
	echo("\n <a href=\"admin.php?id=".$row["id"]."&results=1\"><small>results</small></a>");
      }
      echo("</li>");
      $this->showanswers($row["id"]);
    }
    echo("</ul>");
  }
  private function showcode()
  {
    $qpolls = $this->getpolls();
    $code="";
    while ($row = mysql_fetch_array($qpolls))
    {
      $code.="<h3 class=\"pollq\">".$row["question"]."</h3>\n<ul>";
      $pollanswers = mysql_query("SELECT * FROM answers WHERE poll_id=".$row["id"]." ORDER BY id");
      if (!$pollanswers)
	exit();
      while ($arow = mysql_fetch_array($pollanswers))
      {
	$code.="\n<li class=\"polla\">\n  <a href=\"".$this->baseurl."vote.php?id=".$arow["id"]."\">".$arow["answer"]."</a>\n</li>";
      }
      $code.="\n</ul>";
    }
    echo("<textarea rows='20' cols='50'>$code</textarea>");
  }
}
?>
