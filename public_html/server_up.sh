files=$(ls *.html *.js *.php *.xml *.kml 2> /dev/null)
rsync -tavz $files stockhod@mercury.pr.erau.edu:/users/stockhod/public_html/
