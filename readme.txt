
This csl-lslink repo helps in the management of literary source links
for the cdsl dictionaries.

The script redo_one_xampp.sh takes one parameter (lower-case dictionary code).

Example log for 'ap' dictionary code.
---------------------------------------
sh redo_one_xampp.sh ap
Sat May  2 15:02:01 EDT 2026
BEGIN ap
/c/xampp/php/php.exe lslinkscli.php ap ../ap data/ap_lslinks.txt ../ap/pywork/ap.xml
working...
DONE
34733 data/ap_lslinks.txt
making sqlite/ap_lslinks.sqlite
create_index takes 0.03 seconds
34733 lines read from data/ap_lslinks.txt
34733 rows written to sqlite/ap_lslinks.sqlite
0.19 seconds for batch size 10000
4.0M    sqlite/ap_lslinks.sqlite
  adding: sqlite/ap_lslinks.sqlite (172 bytes security) (deflated 82%)
752K    zip/ap_lslinks.sqlite.zip
END ap
Sat May  2 15:03:04 EDT 2026
---------------------------------------------
The script reads ap.xml (from local installation location) and generates
 - data/ap_lslinks.txt lines with 2 tab-delimited fields
   - ls references
   - corresponding url for the link reference
   Note data directory is NOT tracked by git.
 - sqlite/ap_lslinks.sqlite
   Note sqlite directory is NOT tracked by git
 - ap_lslinks.sqlite.zip  Zipped version.
   Note This IS tracked by git.
