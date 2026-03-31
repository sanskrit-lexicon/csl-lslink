<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
?>
<?php
//lsrecscli.php 03-24-2026
/* How to use.
1. make a directory  in the 'cologne' directory of local installation
     (Jim's directory is named '_lsrecs')
2. put this lsrecscli.php in _lsrecs. This is how I run the program.
3. pull csl-websanlexicon repo  # (for latest basicadjust.php)
4. Choose a dictionary. I'll use 'sch' as example
5. regnerate local display for sch (from csl-pywork/v02)
6. cd to _lsrecs
7. Run the program from terminal using php executable:
   /c/xampp/php/php.exe lsrecscli.php sch ../sch sch_lsrecs.txt
   2nd argument is the directory where local installation of sch displays live
   3rd argument is the output file name (put in _lsrecs directory)
8. Only run for dictionaries where basicadjust knows about ls references
   Program will work for other dictionaries (e.g. ls refs not in ap90.txt).
   I have run for pw, pwg, mw, gra, sch.  Not sure if other dicts are known
   by basicadjust.
*/
$dict = $argv[1];  // lower-case dictionary code
$dicthome = $argv[2];
//$filein = $argv[2];  // xml file
$filein = "{$dicthome}/pywork/{$dict}.xml";
$fileout = $argv[3];
$webdir = "{$dicthome}/web/webtc";  // where basicadjust lives
$_REQUEST['dict'] = 'gra';
require_once("{$webdir}/basicadjust.php");
require_once("{$webdir}/parm.php");
$getParms = new Parm();
$xmlfilename = $filein;
echo($xmlfilename);
$lsrecs_all = array();

print("working...\n");
$xmllines = file($xmlfilename);
$nlines = count($xmllines);
for($i=0;$i<$nlines;$i++) {
 //if ($i == 5) {break;}  // dbg
 $xmlrec = $xmllines[$i];
 $xmldata = array($xmlrec);
 $adjxml = new BasicAdjust($getParms,$xmldata);
 $lsrecs = $adjxml->lsrecs;
 // append to $lsrecs_all
 foreach($lsrecs as $lsrec) {
  $lsrecs_all[] = $lsrec;
 }
}
// output  We should eliminate duplicates
// each lsrec is an array with two elements
// the matchstring and the href
$fh = fopen($fileout, "w");
foreach($lsrecs_all as $lsrec) {
 list($ls,$href) = $lsrec;
 $line = "$ls\t$href";
 fwrite($fh, $line . "\n");
}
fclose($fh);
print("DONE\n");
class LSREC_data {
 public $matches, $basicOption;
 public $xmlmatches;
 public $lsrecs;
 public function __construct($xmlrec) {
 $dbg=false;
 $this->basicOption = true;
 $getParms = new Parm();
 $dict = $getParms->dict;
 
 $xmldata = array($xmlrec);
  $adjxml = new BasicAdjust($getParms,$xmldata);
  $lsrecs = $adjxml->lsrecs;
  $nlsrecs = count($lsrecs);
  for($i=0;$i<$nlsrecs;$i++) {
   $lsrec = $lsrecs[$i];
   list($ls,$href) = $lsrec;
   dbgprint(true,"$ls\t$href\n");
  }
 }
}
?>
