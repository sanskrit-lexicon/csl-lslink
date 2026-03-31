dict=$1
date
echo "BEGIN ${dict}"
txtout="data/${dict}_lslinks.txt"
cmd="/c/xampp/php/php.exe lslinkscli.php ${dict} ../${dict} ${txtout}"
echo $cmd
$cmd
wc -l "${txtout}"
sqliteout="sqlite/${dict}_lslinks.sqlite"
echo "making ${sqliteout}"
python3 make_sqlite.py ${txtout} ${sqliteout}
du -sh ${sqliteout}
zipout="zip/${dict}_lslinks.sqlite.zip"
zip ${zipout} ${sqliteout}
du -sh ${zipout}
echo "END ${dict}"
date
