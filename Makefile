VERSION = $(shell grep 'Version:' style.css | sed 's/Version:[ \t]*//')

build:
	mkdir wplitho/
	cp admin.php header.php hpanelweb.js loop.php options.php footer.php home-hdpi.png index.php nav-hdpi.png right-arrow.png functions.php home.png left-arrow.png nav.png style.css wplitho/
	zip -r "wplitho-${VERSION}.zip" wplitho/
	rm -rf wplitho/

.PHONY: build
