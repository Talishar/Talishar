.PHONY: lint
lint:
	docker run -ti --rm -v `pwd`:/build overtrue/phplint:8.1 /build/
