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

include('config.php');
if (! $db)
{
  echo("<p>Database error.</p>");
  exit();
}
$id="";
if (isset($_GET["id"]))
{
  if (!is_numeric($_GET["id"]))
  {
    echo("<p>fale</p>");
    exit();
  }
  $id = $_GET["id"];
}
else
  exit();
if (! $db)
{
  echo("<p>Database error.</p>");
  exit();
}
$pollquery = "SELECT * FROM polls WHERE id=".$id;
$qpolls=mysql_query($pollquery);
if (!$qpolls)
{
  echo("<p>Database errors.</p>");
  exit();
}
$code="";
while ($row = mysql_fetch_array($qpolls))
{
  $code.="<h3 class=\"pollq\">".$row["question"]."</h3><ul>";
  $pollanswers = mysql_query("SELECT * FROM answers WHERE poll_id=".$row["id"]);
  if (!$pollanswers)
  {
    echo("<p>Database error.</p>");
    exit();
  }
  while ($arow = mysql_fetch_array($pollanswers))
  {
    $code.="<li class=\"polla\"><a href=\"".$baseurl."vote.php?id=".$id."&a=".$arow["id"]."\">".$arow["answer"]."</a></li>";
  }
  $code.="</ul>";
}
?>
<textarea rows="20" cols="50"><?php echo $code; ?></textarea>
<?php echo $code; ?>
</body>
</html>
