<html>
<head>
<style>
.pbox{width:250px;border:1px solid #000;height:23px;}
.pbar{white-space:nowrap;background:#ddf;height:20px;color:#005;text-align:right;padding:3px 0px 0px 0px;}

</style>
</head>
<body>
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

function answerform($pid)
{
?>
  <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
    Add a new answer: <input type="text" size="25" maxlength="128" name="a" /><br/>
    <input type="hidden" name="pid" value="<? echo $pid; ?>" />
    <input type="submit" value="add answer" name="answer" />
  </form>
<?
}
function stdview($pid)
{
  $view = new View();
  $view->setid($pid);
  $view->showquestion();
  $view->showdel();
  $view->show();
  answerform($pid);
  echo("<hr/><b>HTML for email:</b><br/>");
  $view->code();
}
echo("<hr/><a href=\"".$_SERVER['PHP_SELF']."?new\">Create a new poll</a>");
echo(" <a href=\"".$_SERVER['PHP_SELF']."\">List all polls</a><hr/>");
include('config.php');
include('view.php');
if (! $db)
{
  echo("<p>Database error.</p>");
  exit();
}
$pid=0;
if (isset($_GET['new']))
{
?>
  <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
    Poll title: <input type="text" size="25" maxlength="128" name="t" /><br/>
    Poll question:</br> <textarea rows="10" cols="26" maxlength="256" name="q" ></textarea><br/>
    <input type="submit" value="create poll" name="create" />
  </form>
<?
}
else if (isset($_POST['create']))
{
  $t=$_POST['t'];
  $q=$_POST['q'];

  $query = "INSERT INTO polls SET title='$t',question='$q'";
  if(! mysql_query($query))
  {
    echo("<p>Database error.</p>");
    exit();
  }
  $id=$pid=mysql_insert_id();
  echo("<p>Poll created, please add responces below.</p>");
  stdview($pid);
}
else if (isset($_POST['answer']))
{
  $a=$_POST['a'];
  $pid=$_POST['pid'];
  $query = "INSERT INTO answers SET poll_id='$pid',answer='$a'";
  if(! mysql_query($query))
  {
    echo("<p>Database error.</p>");
    exit();
  }
  echo("<p>Answer added to poll.</p>");
  stdview($pid);
}
else if (isset($_GET['edit']))
{
  $pid=$_GET['id'];
  stdview($pid);
}
else if (isset($_POST['deleteanswer']))
{
  $pid=$_POST['pid'];
  $aid=$_POST['aid'];
  $query = "DELETE FROM answers WHERE id='$aid'";
  if(! mysql_query($query))
  {
    echo("<p>Error deleting answer</p>");
    exit();
  }
  echo("<p>Answer deleted from poll</p>");
  stdview($pid);
}
else if (isset($_POST['deletepoll']))
{
  $pid=$_POST['id'];
  $query = "DELETE FROM answers WHERE poll_id='$pid'";
  if(! mysql_query($query))
  {
    echo("<p>Error deleting answers from poll.  Poll not deleted.</p>");
    exit();
  }
  $query = "DELETE FROM polls WHERE id='$pid'";
  if(! mysql_query($query))
  {
    echo("<p>Error deleting poll</p>");
    exit();
  }
  echo("<p>Poll deleted</p>");
  $view = new View();
  $view->showquestion();
  $view->showlinks();
  $view->show();
}
else if (isset($_GET['results']))
{
  $view = new View();
  $view->setid($_GET["id"]);
  $view->showresults();
  $view->show();
}
else
{
  $view = new View();
  $view->showquestion();
  $view->showlinks();
  $view->show();
}

?>
</body>
</html>
