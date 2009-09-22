<html>
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

echo("<hr/><a href=\"".$_SERVER['PHP_SELF']."?new\">Create a new poll</a><hr/>");
include('config.php');
if (! $db)
{
  echo("<p>Database error.</p>");
  exit();
}
$links=$q=1;
include('view.partial');
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
if (isset($_POST['create']))
{
  $t=$_POST['t'];
  $q=$_POST['q'];

  $query = "INSERT INTO polls SET title='$t',question='$q'";
  if(! mysql_query($query))
  {
    echo("<p>Database error.</p>");
    exit();
  }
  $pid=mysql_insert_id();
  echo("<p>Poll added. id=$pid</p>");
}
if (isset($_POST['answer']))
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
}
if (isset($_POST['answer']) || isset($_POST['create']))
{
?>
  <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
    Answer: <input type="text" size="25" maxlength="128" name="a" /><br/>
    <input type="hidden" name="pid" value="<? echo $pid; ?>" />
    <input type="submit" value="add answer" name="answer" />
    <input type="submit" value="done" name="done" />
  </form>
<?
}
?>
</body>
</html>
