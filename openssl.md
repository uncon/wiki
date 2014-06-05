# OpenSSL One-Liners

## Extract certificate details
	openssl x509 -text -in cert.pem

## Remove a passphrase from a PEM private key file
	openssl rsa -in userkey.pem -out userkey-nopass.pem

## Convert PKCS#12 bundle to PEM
	openssl pkcs12 -in bundle.p12 -out userkey.pem -nodes -clcerts

## Generate hash-based symlinks
`certlink.sh`

	#!/bin/sh
	#
	# usage: certlink.sh filename [filename ...]
	
	for CERTFILE in $*; do
		# make sure file exists and is a valid cert
		test -f "$CERTFILE" || continue
		HASH=$(openssl x509 -noout -hash -in "$CERTFILE")
		test -n "$HASH" || continue
	
		# use lowest available iterator for symlink
		for ITER in 0 1 2 3 4 5 6 7 8 9; do
			test -f "${HASH}.${ITER}" && continue
			ln -s "$CERTFILE" "${HASH}.${ITER}"
			test -L "${HASH}.${ITER}" && break
		done
	done

## Verify a certificate
	openssl verify -CApath . [certificate]

## View the signer of a certificate
	openssl x509 -in cert.pem -noout -issuer -issuer_hash

## View the hash value of a certificate
	openssl x509 -noout -hash -in cert.pem
